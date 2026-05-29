<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\Referral;

class InstitutionReferralController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = Referral::class;
        $this->routeBase = 'referrals';
        $this->title = 'Referral';
        $this->relationships = ['application', 'student', 'referredBy'];
        $this->fields = [
            'referral_number' => ['label' => 'Referral Number'],
            'application_id' => ['label' => 'Application'],
            'student_id' => ['label' => 'Student'],
            'referred_by' => ['label' => 'Referred By'],
            'status' => ['label' => 'Status'],
            'referred_at' => ['label' => 'Referred At'],
            'viewed_at' => ['label' => 'Viewed At'],
        ];
    }
}
