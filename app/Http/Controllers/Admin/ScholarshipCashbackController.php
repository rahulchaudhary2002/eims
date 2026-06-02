<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreScholarshipCashbackRequest;
use App\Http\Requests\Admin\UpdateScholarshipCashbackRequest;
use App\Models\Application;
use App\Models\CommissionInvoice;
use App\Models\ScholarshipCashback;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScholarshipCashbackController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = ScholarshipCashback::with(['student', 'application', 'commissionInvoice.institution']);
        $this->applyInstitutionScope($query);

        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($applicationId = $request->input('application_id')) {
            $query->where('application_id', $applicationId);
        }
        if ($invoiceId = $request->input('commission_invoice_id')) {
            $query->where('commission_invoice_id', $invoiceId);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($paidFrom = $request->input('paid_from')) {
            $query->whereDate('paid_at', '>=', $paidFrom);
        }
        if ($paidTo = $request->input('paid_to')) {
            $query->whereDate('paid_at', '<=', $paidTo);
        }

        $cashbacks = $query->latest()->paginate(20)->withQueryString();
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $applications = $this->applicationDropdownQuery()->get(['id', 'application_number', 'institution_id']);
        $invoices = $this->invoiceDropdownQuery()->get(['id', 'invoice_number', 'institution_id']);
        $statuses = ScholarshipCashback::STATUSES;
        $paymentMethods = ScholarshipCashback::PAYMENT_METHODS;

        return view('admin.modules.scholarship-cashbacks.index', compact(
            'cashbacks',
            'students',
            'applications',
            'invoices',
            'statuses',
            'paymentMethods'
        ));
    }

    public function create(Request $request): View
    {
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $applications = $this->applicationDropdownQuery()->get(['id', 'application_number', 'institution_id']);
        $invoices = $this->invoiceDropdownQuery()
            ->whereDoesntHave('scholarshipCashback')
            ->with(['referralAgreement:id,student_cashback_percentage'])
            ->get(['id', 'invoice_number', 'institution_id', 'commission_amount', 'student_cashback_amount', 'referral_agreement_id']);
        $statuses = ScholarshipCashback::STATUSES;
        $paymentMethods = ScholarshipCashback::PAYMENT_METHODS;
        $selectedStudentId = $request->input('student_id');
        $selectedApplicationId = $request->input('application_id');
        $selectedInvoiceId = $request->input('commission_invoice_id');

        $invoiceData = $invoices->keyBy('id')->map(fn ($inv) => [
            'commission_amount'       => (float) $inv->commission_amount,
            'student_cashback_amount' => (float) $inv->student_cashback_amount,
            'cashback_percentage'     => (float) ($inv->referralAgreement?->student_cashback_percentage ?? 0),
        ]);

        return view('admin.modules.scholarship-cashbacks.create', compact(
            'students',
            'applications',
            'invoices',
            'statuses',
            'paymentMethods',
            'selectedStudentId',
            'selectedApplicationId',
            'selectedInvoiceId',
            'invoiceData'
        ));
    }

    public function store(StoreScholarshipCashbackRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeByInvoiceOrApplication($data);

        $cashback = ScholarshipCashback::create($data);

        return redirect()->route('admin.scholarship-cashbacks.show', $cashback)
            ->with('success', 'Scholarship cashback created successfully.');
    }

    public function show(ScholarshipCashback $scholarshipCashback): View
    {
        $this->authorizeCashbackAccess($scholarshipCashback);
        $scholarshipCashback->load(['student', 'application.institutionProgram.program', 'commissionInvoice.institution']);

        return view('admin.modules.scholarship-cashbacks.show', compact('scholarshipCashback'));
    }

    public function edit(ScholarshipCashback $scholarshipCashback): View
    {
        $this->authorizeCashbackAccess($scholarshipCashback);
        $scholarshipCashback->load(['student', 'application', 'commissionInvoice']);

        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $applications = $this->applicationDropdownQuery()->get(['id', 'application_number', 'institution_id']);
        $currentInvoiceId = $scholarshipCashback->commission_invoice_id;
        $invoices = $this->invoiceDropdownQuery()
            ->where(fn ($q) => $q->whereDoesntHave('scholarshipCashback')
                ->orWhere('id', $currentInvoiceId))
            ->with(['referralAgreement:id,student_cashback_percentage'])
            ->get(['id', 'invoice_number', 'institution_id', 'commission_amount', 'student_cashback_amount', 'referral_agreement_id']);
        $statuses = ScholarshipCashback::STATUSES;
        $paymentMethods = ScholarshipCashback::PAYMENT_METHODS;

        $invoiceData = $invoices->keyBy('id')->map(fn ($inv) => [
            'commission_amount'       => (float) $inv->commission_amount,
            'student_cashback_amount' => (float) $inv->student_cashback_amount,
            'cashback_percentage'     => (float) ($inv->referralAgreement?->student_cashback_percentage ?? 0),
        ]);

        return view('admin.modules.scholarship-cashbacks.edit', compact(
            'scholarshipCashback',
            'students',
            'applications',
            'invoices',
            'statuses',
            'paymentMethods',
            'invoiceData'
        ));
    }

    public function update(UpdateScholarshipCashbackRequest $request, ScholarshipCashback $scholarshipCashback): RedirectResponse
    {
        $this->authorizeCashbackAccess($scholarshipCashback);
        $data = $request->validated();
        $this->authorizeByInvoiceOrApplication($data);

        $scholarshipCashback->update($data);

        return redirect()->route('admin.scholarship-cashbacks.show', $scholarshipCashback)
            ->with('success', 'Scholarship cashback updated successfully.');
    }

    public function destroy(ScholarshipCashback $scholarshipCashback): RedirectResponse
    {
        $this->authorizeCashbackAccess($scholarshipCashback);
        $scholarshipCashback->delete();

        return redirect()->route('admin.scholarship-cashbacks.index')
            ->with('success', 'Scholarship cashback deleted successfully.');
    }

    public function updateStatus(Request $request, ScholarshipCashback $scholarshipCashback): RedirectResponse
    {
        $this->authorizeCashbackAccess($scholarshipCashback);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(ScholarshipCashback::STATUSES))],
        ]);

        $data = ['status' => $request->input('status')];

        if ($request->input('status') === 'paid' && ! $scholarshipCashback->paid_at) {
            $data['paid_at'] = now();
        }

        $scholarshipCashback->update($data);

        return back()->with('success', 'Cashback status updated.');
    }

    private function invoiceDropdownQuery(): Builder
    {
        $query = CommissionInvoice::orderBy('invoice_number');
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

    private function applicationDropdownQuery(): Builder
    {
        $query = Application::orderBy('application_number');
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

    private function applyInstitutionScope(Builder $query): void
    {
        $scope = $this->institutionScope();
        if ($scope === null) {
            return;
        }

        if (! $this->currentInstitutionIsAssigned()) {
            $query->whereRaw('1 = 0');
            return;
        }

        $query->where(function (Builder $q) use ($scope) {
            $q->whereHas('commissionInvoice', fn (Builder $iq) => $iq->where('institution_id', $scope))
              ->orWhereHas('application', fn (Builder $aq) => $aq->where('institution_id', $scope));
        });
    }

    private function authorizeCashbackAccess(ScholarshipCashback $cashback): void
    {
        $cashback->loadMissing(['commissionInvoice', 'application']);

        if ($cashback->commissionInvoice) {
            $this->authorizeInstitution((int) $cashback->commissionInvoice->institution_id);
            return;
        }

        if ($cashback->application) {
            $this->authorizeInstitution((int) $cashback->application->institution_id);
            return;
        }

        $user = auth('web')->user();
        abort_unless($user?->is_super_admin, 403, 'You do not have access to this record.');
    }

    private function authorizeByInvoiceOrApplication(array $data): void
    {
        if (! empty($data['commission_invoice_id'])) {
            $invoice = CommissionInvoice::findOrFail($data['commission_invoice_id']);
            $this->authorizeInstitution((int) $invoice->institution_id);
            return;
        }

        if (! empty($data['application_id'])) {
            $application = \App\Models\Application::findOrFail($data['application_id']);
            $this->authorizeInstitution((int) $application->institution_id);
        }
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
