<?php

namespace App\Http\Requests\Admin;

use App\Models\CommissionPayment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommissionPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $invoiceRule = Rule::exists('commission_invoices', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $invoiceRule->where('institution_id', $scope);
        }

        return [
            'commission_invoice_id' => ['required', $invoiceRule],
            'amount'                => ['required', 'numeric', 'min:0.01'],
            'payment_method'        => ['required', Rule::in(array_keys(CommissionPayment::PAYMENT_METHODS))],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'payment_proof'         => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'payment_date'          => ['required', 'date'],
            'remarks'               => ['nullable', 'string'],
        ];
    }
}
