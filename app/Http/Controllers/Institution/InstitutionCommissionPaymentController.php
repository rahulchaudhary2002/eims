<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\CommissionInvoice;
use App\Models\CommissionPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InstitutionCommissionPaymentController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = CommissionPayment::class;
        $this->routeBase = 'commission-payments';
        $this->title = 'Commission Payment';
        $this->fileFields = ['payment_proof' => 'commission-payments'];
        $this->relationships = ['commissionInvoice'];
        $this->selectOptions = ['payment_method' => CommissionPayment::PAYMENT_METHODS];
        $this->fields = [
            'commission_invoice_id' => ['label' => 'Commission Invoice', 'type' => 'select', 'rules' => ['required', 'integer', 'exists:commission_invoices,id']],
            'amount' => ['label' => 'Amount', 'type' => 'number', 'rules' => ['required', 'numeric', 'min:0']],
            'payment_method' => ['label' => 'Payment Method', 'type' => 'select', 'rules' => ['required', 'string']],
            'transaction_reference' => ['label' => 'Transaction Reference', 'rules' => ['nullable', 'string', 'max:255']],
            'payment_proof' => ['label' => 'Payment Proof', 'type' => 'file', 'rules' => ['nullable', 'file', 'max:10240']],
            'payment_date' => ['label' => 'Payment Date', 'type' => 'date', 'rules' => ['required', 'date']],
            'remarks' => ['label' => 'Remarks', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
        ];
    }

    protected function resourceQuery(): Builder
    {
        return CommissionPayment::query()->whereHas('commissionInvoice', fn ($query) => $query->where('institution_id', $this->activeInstitutionId()));
    }

    protected function forceInstitutionScope(array $data, ?Model $record = null): array
    {
        abort_unless(CommissionInvoice::where('institution_id', $this->activeInstitutionId())->whereKey($data['commission_invoice_id'] ?? null)->exists(), 403);

        return $data;
    }

    protected function selectOptions(): array
    {
        return array_merge($this->selectOptions, [
            'commission_invoice_id' => CommissionInvoice::where('institution_id', $this->activeInstitutionId())->latest()->pluck('invoice_number', 'id')->all(),
        ]);
    }
}
