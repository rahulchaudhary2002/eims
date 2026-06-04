<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\ConsultancyService;

class InstitutionConsultancyServiceController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass    = ConsultancyService::class;
        $this->routeBase     = 'consultancy-services';
        $this->title         = 'Service';
        $this->selectOptions = ['service_type' => ConsultancyService::SERVICE_TYPES];
        $this->fields        = [
            'service_type' => ['label' => 'Service Type', 'type' => 'select',   'rules' => ['required', 'string']],
            'title'        => ['label' => 'Title',        'rules' => ['required', 'string', 'max:255']],
            'service_fee'  => ['label' => 'Service Fee',  'type' => 'number',   'rules' => ['nullable', 'numeric', 'min:0']],
            'is_active'    => ['label' => 'Active',       'type' => 'checkbox', 'rules' => ['nullable']],
            'description'  => ['label' => 'Description',  'type' => 'ckeditor', 'rules' => ['nullable', 'string']],
        ];
    }

    protected function beforeAction(): void
    {
        abort_unless($this->activeInstitution()->type === 'consultancy', 403, 'Only consultancy institutions can manage services.');
    }
}
