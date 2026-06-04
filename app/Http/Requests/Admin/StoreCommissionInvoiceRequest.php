<?php

namespace App\Http\Requests\Admin;

use App\Models\CommissionInvoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommissionInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $institutionRule = Rule::exists('institutions', 'id');
        $admissionRule   = Rule::exists('admissions', 'id');
        $agreementRule   = Rule::exists('referral_agreements', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $institutionRule->where('id', $scope);
            $admissionRule->where('institution_id', $scope);
            $agreementRule->where('institution_id', $scope);
        }

        return [
            'invoice_number'          => ['nullable', 'string', 'max:255', 'unique:commission_invoices,invoice_number'],
            'institution_id'          => ['required', $institutionRule],
            'admission_id'            => ['nullable', $admissionRule],
            'referral_agreement_id'   => ['nullable', $agreementRule],
            'admission_paid_amount'   => ['required', 'numeric', 'min:0'],
            'commission_type'         => ['required', Rule::in(array_keys(CommissionInvoice::COMMISSION_TYPES))],
            'commission_value'        => ['required', 'numeric', 'min:0'],
            'commission_amount'       => ['required', 'numeric', 'min:0'],
            'student_cashback_amount' => ['required', 'numeric', 'min:0'],
            'status'                  => ['required', Rule::in(array_keys(CommissionInvoice::STATUSES))],
            'invoice_date'            => ['nullable', 'date'],
            'due_date'                => ['nullable', 'date', 'after_or_equal:invoice_date'],
            'paid_at'                 => ['nullable', 'date'],
        ];
    }
}
