<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\InstitutionReview;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

    public function index(Request $request): View
    {
        $records = $this->resourceQuery()
            ->with('student')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'total' => $this->resourceQuery()->count(),
            'approved' => $this->resourceQuery()->where('is_approved', true)->count(),
            'pending' => $this->resourceQuery()->where('is_approved', false)->count(),
            'average' => round((float) $this->resourceQuery()->avg('rating'), 1),
        ];

        return view($this->resourceView('index'), $this->viewData(compact('records', 'summary')));
    }
}
