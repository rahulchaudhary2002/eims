<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\ConsultancyDestination;

class InstitutionConsultancyDestinationController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = ConsultancyDestination::class;
        $this->routeBase  = 'consultancy-destinations';
        $this->title      = 'Destination';
        $this->fields     = [
            'country'   => ['label' => 'Country', 'rules' => ['required', 'string', 'max:255']],
            'city'      => ['label' => 'City',    'rules' => ['nullable', 'string', 'max:255']],
            'is_active' => ['label' => 'Active',  'type' => 'checkbox', 'rules' => ['nullable']],
        ];
    }

    protected function beforeAction(): void
    {
        abort_unless($this->activeInstitution()->type === 'consultancy', 403, 'Only consultancy institutions can manage destinations.');
    }
}
