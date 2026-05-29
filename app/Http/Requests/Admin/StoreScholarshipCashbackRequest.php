<?php

namespace App\Http\Requests\Admin;

use App\Models\ScholarshipCashback;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreScholarshipCashbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $invoiceRule     = Rule::exists('commission_invoices', 'id');
        $applicationRule = Rule::exists('applications', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $invoiceRule->where('institution_id', $scope);
            $applicationRule->where('institution_id', $scope);
        }

        return [
            'student_id'                 => ['required', Rule::exists('students', 'id')],
            'application_id'             => ['nullable', $applicationRule],
            'commission_invoice_id'      => ['nullable', $invoiceRule],
            'commission_received_amount' => ['required', 'numeric', 'min:0'],
            'cashback_percentage'        => ['required', 'numeric', 'min:0', 'max:100'],
            'cashback_amount'            => ['required', 'numeric', 'min:0'],
            'status'                     => ['required', Rule::in(array_keys(ScholarshipCashback::STATUSES))],
            'payment_method'             => ['nullable', Rule::in(array_keys(ScholarshipCashback::PAYMENT_METHODS))],
            'transaction_reference'      => ['nullable', 'string', 'max:255'],
            'paid_at'                    => ['nullable', 'date'],
            'remarks'                    => ['nullable', 'string'],
        ];
    }
}
