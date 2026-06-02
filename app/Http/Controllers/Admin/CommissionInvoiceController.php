<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCommissionInvoiceRequest;
use App\Http\Requests\Admin\UpdateCommissionInvoiceRequest;
use App\Models\Admission;
use App\Models\CommissionInvoice;
use App\Models\Institution;
use App\Models\ReferralAgreement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissionInvoiceController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = CommissionInvoice::with(['institution', 'admission', 'referralAgreement']);
        $this->applyInstitutionScope($query);

        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($admissionId = $request->input('admission_id')) {
            $query->where('admission_id', $admissionId);
        }
        if ($agreementId = $request->input('referral_agreement_id')) {
            $query->where('referral_agreement_id', $agreementId);
        }
        if ($commissionType = $request->input('commission_type')) {
            $query->where('commission_type', $commissionType);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($invoiceDateFrom = $request->input('invoice_date_from')) {
            $query->whereDate('invoice_date', '>=', $invoiceDateFrom);
        }
        if ($invoiceDateTo = $request->input('invoice_date_to')) {
            $query->whereDate('invoice_date', '<=', $invoiceDateTo);
        }
        if ($dueDateFrom = $request->input('due_date_from')) {
            $query->whereDate('due_date', '>=', $dueDateFrom);
        }
        if ($dueDateTo = $request->input('due_date_to')) {
            $query->whereDate('due_date', '<=', $dueDateTo);
        }

        $invoices = $query->latest()->paginate(20)->withQueryString();
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $admissions = $this->admissionDropdownQuery()->get(['id', 'admission_number', 'institution_id']);
        $referralAgreements = $this->referralAgreementDropdownQuery()->get(['id', 'institution_id', 'commission_type', 'commission_value']);
        $commissionTypes = CommissionInvoice::COMMISSION_TYPES;
        $statuses = CommissionInvoice::STATUSES;

        return view('admin.modules.commission-invoices.index', compact(
            'invoices',
            'institutions',
            'admissions',
            'referralAgreements',
            'commissionTypes',
            'statuses'
        ));
    }

    public function create(Request $request): View
    {
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $admissions = $this->admissionDropdownQuery()->get(['id', 'admission_number', 'institution_id']);
        $referralAgreements = $this->referralAgreementDropdownQuery()->get(['id', 'institution_id', 'commission_type', 'commission_value', 'student_cashback_percentage', 'status']);
        $commissionTypes = CommissionInvoice::COMMISSION_TYPES;
        $statuses = CommissionInvoice::STATUSES;
        $selectedInstitutionId = $request->input('institution_id');
        $selectedAdmissionId = $request->input('admission_id');
        $selectedAgreementId = $request->input('referral_agreement_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.modules.commission-invoices.create', compact(
            'institutions',
            'admissions',
            'referralAgreements',
            'commissionTypes',
            'statuses',
            'selectedInstitutionId',
            'selectedAdmissionId',
            'selectedAgreementId'
        ));
    }

    public function store(StoreCommissionInvoiceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $data['invoice_number'] = ($data['invoice_number'] ?? '') ?: $this->nextInvoiceNumber();

        $invoice = CommissionInvoice::create($data);

        return redirect()->route('admin.commission-invoices.show', $invoice)
            ->with('success', 'Commission invoice created successfully.');
    }

    public function show(CommissionInvoice $commissionInvoice): View
    {
        $this->authorizeInvoiceAccess($commissionInvoice);
        $commissionInvoice->load(['institution', 'admission.student', 'referralAgreement', 'payments' => fn ($q) => $q->orderBy('payment_date'), 'scholarshipCashback.student']);

        return view('admin.modules.commission-invoices.show', compact('commissionInvoice'));
    }

    public function edit(CommissionInvoice $commissionInvoice): View
    {
        $this->authorizeInvoiceAccess($commissionInvoice);
        $commissionInvoice->load(['institution', 'admission', 'referralAgreement']);

        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $admissions = $this->admissionDropdownQuery()->get(['id', 'admission_number', 'institution_id']);
        $referralAgreements = $this->referralAgreementDropdownQuery()->get(['id', 'institution_id', 'commission_type', 'commission_value', 'student_cashback_percentage', 'status']);
        $commissionTypes = CommissionInvoice::COMMISSION_TYPES;
        $statuses = CommissionInvoice::STATUSES;

        return view('admin.modules.commission-invoices.edit', compact(
            'commissionInvoice',
            'institutions',
            'admissions',
            'referralAgreements',
            'commissionTypes',
            'statuses'
        ));
    }

    public function update(UpdateCommissionInvoiceRequest $request, CommissionInvoice $commissionInvoice): RedirectResponse
    {
        $this->authorizeInvoiceAccess($commissionInvoice);
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $data['invoice_number'] = ($data['invoice_number'] ?? '') ?: $commissionInvoice->invoice_number;

        $commissionInvoice->update($data);

        return redirect()->route('admin.commission-invoices.show', $commissionInvoice)
            ->with('success', 'Commission invoice updated successfully.');
    }

    public function destroy(CommissionInvoice $commissionInvoice): RedirectResponse
    {
        $this->authorizeInvoiceAccess($commissionInvoice);
        $commissionInvoice->delete();

        return redirect()->route('admin.commission-invoices.index')
            ->with('success', 'Commission invoice deleted successfully.');
    }

    public function updateStatus(Request $request, CommissionInvoice $commissionInvoice): RedirectResponse
    {
        $this->authorizeInvoiceAccess($commissionInvoice);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(CommissionInvoice::STATUSES))],
        ]);

        $data = ['status' => $request->input('status')];

        if ($request->input('status') === 'paid' && ! $commissionInvoice->paid_at) {
            $data['paid_at'] = now();
        }

        $commissionInvoice->update($data);

        return back()->with('success', 'Invoice status updated.');
    }

    private function nextInvoiceNumber(): string
    {
        do {
            $number = 'INV-' . now()->format('YmdHis') . '-' . random_int(100, 999);
        } while (CommissionInvoice::where('invoice_number', $number)->exists());

        return $number;
    }

    private function admissionDropdownQuery(): Builder
    {
        $query = Admission::orderBy('admission_number');
        $scope = $this->institutionScope();

        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('institution_id', $scope);
            }
        }

        return $query;
    }

    private function referralAgreementDropdownQuery(): Builder
    {
        $query = ReferralAgreement::orderBy('id');
        $scope = $this->institutionScope();

        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('institution_id', $scope);
            }
        }

        return $query;
    }

    private function institutionDropdownQuery(): Builder
    {
        $query = Institution::orderBy('name');
        $scope = $this->institutionScope();

        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('id', $scope)
                    ->whereHas('users', fn (Builder $q) => $q->where('users.id', auth('web')->id())->wherePivot('is_active', true));
            }
        }

        return $query;
    }

    private function applyInstitutionScope(Builder $query): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('institution_id', $scope);
            }
        }
    }

    private function authorizeInvoiceAccess(CommissionInvoice $invoice): void
    {
        $this->authorizeInstitution((int) $invoice->institution_id);
    }

    private function authorizeInstitution(int $institutionId): void
    {
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return;
        }

        abort_unless(
            (int) session('current_institution_id', 0) === $institutionId
                && $user?->activeInstitutions()->where('institutions.id', $institutionId)->exists(),
            403,
            'You do not have access to this institution.'
        );
    }

    private function currentInstitutionIsAssigned(): bool
    {
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return true;
        }

        $scope = (int) session('current_institution_id', 0);

        return $scope > 0
            && (bool) $user?->activeInstitutions()->where('institutions.id', $scope)->exists();
    }
}
