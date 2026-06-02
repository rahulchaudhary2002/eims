<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCommissionPaymentRequest;
use App\Http\Requests\Admin\UpdateCommissionPaymentRequest;
use App\Models\CommissionInvoice;
use App\Models\CommissionPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CommissionPaymentController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = CommissionPayment::with(['commissionInvoice.institution']);
        $this->applyInvoiceInstitutionScope($query);

        if ($invoiceId = $request->input('commission_invoice_id')) {
            $query->where('commission_invoice_id', $invoiceId);
        }
        if ($method = $request->input('payment_method')) {
            $query->where('payment_method', $method);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('payment_date', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('payment_date', '<=', $dateTo);
        }

        $payments = $query->latest('payment_date')->paginate(20)->withQueryString();
        $invoices = $this->invoiceDropdownQuery()->get(['id', 'invoice_number', 'institution_id']);
        $paymentMethods = CommissionPayment::PAYMENT_METHODS;

        return view('admin.modules.commission-payments.index', compact('payments', 'invoices', 'paymentMethods'));
    }

    public function create(Request $request): View
    {
        $invoices = $this->invoiceDropdownQuery()
            ->withSum('payments', 'amount')
            ->get(['id', 'invoice_number', 'institution_id', 'commission_amount']);
        $paymentMethods = CommissionPayment::PAYMENT_METHODS;
        $selectedInvoiceId = $request->input('commission_invoice_id');

        if ($selectedInvoiceId) {
            $invoice = CommissionInvoice::findOrFail($selectedInvoiceId);
            $this->authorizeInstitution((int) $invoice->institution_id);
        }

        $invoiceData = $invoices->keyBy('id')->map(fn ($inv) => [
            'commission_amount' => (float) $inv->commission_amount,
            'paid'              => (float) ($inv->payments_sum_amount ?? 0),
            'remaining'         => max(0, (float) $inv->commission_amount - (float) ($inv->payments_sum_amount ?? 0)),
        ]);

        return view('admin.modules.commission-payments.create', compact('invoices', 'paymentMethods', 'selectedInvoiceId', 'invoiceData'));
    }

    public function store(StoreCommissionPaymentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $invoice = CommissionInvoice::findOrFail($data['commission_invoice_id']);
        $this->authorizeInstitution((int) $invoice->institution_id);

        if ($request->hasFile('payment_proof')) {
            $data['payment_proof'] = $request->file('payment_proof')
                ->store('commission-payments', 'public');
        }

        CommissionPayment::create($data);
        $this->syncInvoiceStatus($invoice);

        return redirect()->route('admin.commission-invoices.show', $invoice)
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(CommissionPayment $commissionPayment): View
    {
        $this->authorizePaymentAccess($commissionPayment);
        $commissionPayment->load('commissionInvoice.institution');

        return view('admin.modules.commission-payments.show', compact('commissionPayment'));
    }

    public function edit(CommissionPayment $commissionPayment): View
    {
        $this->authorizePaymentAccess($commissionPayment);
        $commissionPayment->load('commissionInvoice');

        $invoices = $this->invoiceDropdownQuery()
            ->withSum('payments', 'amount')
            ->get(['id', 'invoice_number', 'institution_id', 'commission_amount']);
        $paymentMethods = CommissionPayment::PAYMENT_METHODS;

        $currentAmount = (float) $commissionPayment->amount;
        $currentInvoiceId = $commissionPayment->commission_invoice_id;

        $invoiceData = $invoices->keyBy('id')->map(fn ($inv) => [
            'commission_amount' => (float) $inv->commission_amount,
            'paid'              => (float) ($inv->payments_sum_amount ?? 0),
            'remaining'         => max(0, (float) $inv->commission_amount - (float) ($inv->payments_sum_amount ?? 0) + ($inv->id === $currentInvoiceId ? $currentAmount : 0)),
        ]);

        return view('admin.modules.commission-payments.edit', compact('commissionPayment', 'invoices', 'paymentMethods', 'invoiceData'));
    }

    public function update(UpdateCommissionPaymentRequest $request, CommissionPayment $commissionPayment): RedirectResponse
    {
        $this->authorizePaymentAccess($commissionPayment);
        $data = $request->validated();

        $invoice = CommissionInvoice::findOrFail($data['commission_invoice_id']);
        $this->authorizeInstitution((int) $invoice->institution_id);

        if ($request->hasFile('payment_proof')) {
            if ($commissionPayment->payment_proof) {
                Storage::disk('public')->delete($commissionPayment->payment_proof);
            }
            $data['payment_proof'] = $request->file('payment_proof')
                ->store('commission-payments', 'public');
        } else {
            unset($data['payment_proof']);
        }

        $commissionPayment->update($data);

        $oldInvoiceId = $commissionPayment->getOriginal('commission_invoice_id');
        if ($oldInvoiceId && $oldInvoiceId !== $invoice->id) {
            $this->syncInvoiceStatus(CommissionInvoice::find($oldInvoiceId));
        }
        $this->syncInvoiceStatus($invoice);

        return redirect()->route('admin.commission-invoices.show', $invoice)
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(CommissionPayment $commissionPayment): RedirectResponse
    {
        $this->authorizePaymentAccess($commissionPayment);
        $invoice = $commissionPayment->commissionInvoice;

        if ($commissionPayment->payment_proof) {
            Storage::disk('public')->delete($commissionPayment->payment_proof);
        }

        $commissionPayment->delete();

        if ($invoice) {
            $this->syncInvoiceStatus($invoice);
        }

        return redirect()->route('admin.commission-invoices.show', $invoice)
            ->with('success', 'Payment deleted successfully.');
    }

    private function syncInvoiceStatus(CommissionInvoice $invoice): void
    {
        $totalPaid = $invoice->payments()->sum('amount');
        $commissionAmount = (float) $invoice->commission_amount;

        if ($commissionAmount > 0 && $totalPaid >= $commissionAmount) {
            $update = ['status' => 'paid'];
            if (! $invoice->paid_at) {
                $update['paid_at'] = now();
            }
            $invoice->update($update);
        } elseif ($invoice->status === 'paid' && $totalPaid < $commissionAmount) {
            $invoice->update(['status' => 'issued', 'paid_at' => null]);
        }
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

    private function applyInvoiceInstitutionScope(Builder $query): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereHas('commissionInvoice', fn (Builder $q) => $q->where('institution_id', $scope));
            }
        }
    }

    private function authorizePaymentAccess(CommissionPayment $payment): void
    {
        $payment->loadMissing('commissionInvoice');
        $this->authorizeInstitution((int) $payment->commissionInvoice?->institution_id);
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
