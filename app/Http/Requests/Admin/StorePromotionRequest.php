<?php

namespace App\Http\Requests\Admin;

use App\Models\Promotion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $institutionRule = Rule::exists('institutions', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $institutionRule->where('id', $scope);
        }

        return [
            'institution_id' => ['nullable', $institutionRule],
            'type'           => ['required', Rule::in(array_keys(Promotion::TYPES))],
            'title'          => ['required', 'string', 'max:255'],
            'image'          => ['nullable', 'image', 'max:5120'],
            'target_url'     => ['nullable', 'url', 'max:500'],
            'start_date'     => ['nullable', 'date'],
            'end_date'       => ['nullable', 'date', 'after_or_equal:start_date'],
            'amount'         => ['nullable', 'numeric', 'min:0'],
            'status'         => ['required', Rule::in(array_keys(Promotion::STATUSES))],
        ];
    }
}
