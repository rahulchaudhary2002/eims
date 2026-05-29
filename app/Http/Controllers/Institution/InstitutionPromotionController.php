<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\Promotion;

class InstitutionPromotionController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = Promotion::class;
        $this->routeBase = 'promotions';
        $this->title = 'Promotion';
        $this->fileFields = ['image' => 'promotions'];
        $this->selectOptions = ['type' => Promotion::TYPES, 'status' => Promotion::STATUSES];
        $this->fields = [
            'type' => ['label' => 'Type', 'type' => 'select', 'rules' => ['required', 'string']],
            'title' => ['label' => 'Title', 'rules' => ['required', 'string', 'max:255']],
            'image' => ['label' => 'Image', 'type' => 'file', 'rules' => ['nullable', 'image', 'max:4096']],
            'target_url' => ['label' => 'Target URL', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
            'start_date' => ['label' => 'Start Date', 'type' => 'date', 'rules' => ['nullable', 'date']],
            'end_date' => ['label' => 'End Date', 'type' => 'date', 'rules' => ['nullable', 'date']],
            'amount' => ['label' => 'Amount', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
            'status' => ['label' => 'Status', 'type' => 'select', 'rules' => ['required', 'string']],
        ];
    }
}
