<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\InstitutionReview;

class InstitutionReviewController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = InstitutionReview::class;
        $this->routeBase = 'reviews';
        $this->title = 'Review';
        $this->relationships = ['student'];
        $this->fields = [
            'student_id' => ['label' => 'Student'],
            'rating' => ['label' => 'Rating'],
            'review' => ['label' => 'Review', 'type' => 'textarea'],
            'is_approved' => ['label' => 'Approved'],
        ];
    }
}
