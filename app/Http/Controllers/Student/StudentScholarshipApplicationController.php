<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentScholarshipApplicationRequest;
use App\Models\Application;
use App\Models\Scholarship;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentScholarshipApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user('student')->id;
        $applications = Application::where('student_id', $studentId)
            ->whereNotNull('scholarship_id')
            ->with(['scholarship', 'institution', 'applicable'])
            ->latest()
            ->paginate(12);

        return view('student.scholarship-applications.index', compact('applications'));
    }

    public function create(Request $request): View
    {
        $studentId   = $request->user('student')->id;
        $scholarships = Scholarship::where('status', 'active')->orderBy('title')->get();
        $myApplications = Application::where('student_id', $studentId)
            ->with('institution')
            ->whereIn('status', ['submitted', 'under_review', 'admitted'])
            ->get();

        $selected = null;
        if ($request->has('scholarship')) {
            $selected = Scholarship::where('slug', $request->scholarship)->first();
        }

        return view('student.scholarship-applications.create', compact(
            'scholarships', 'myApplications', 'selected'
        ));
    }

    public function store(StoreStudentScholarshipApplicationRequest $request): RedirectResponse
    {
        $studentId = $request->user('student')->id;
        $data      = $request->validated();
        $data['student_id'] = $studentId;
        $data['status']     = 'pending';

        if (isset($data['application_id'])) {
            $app = Application::findOrFail($data['application_id']);
            abort_if($app->student_id !== $studentId, 403);
        }

        Application::create($data);

        return redirect()->route('student.scholarship-applications.index')
            ->with('success', 'Scholarship application submitted successfully.');
    }

    public function show(Request $request, Application $application): View
    {
        abort_if($application->student_id !== $request->user('student')->id, 403);
        abort_if(is_null($application->scholarship_id), 404);

        $application->load(['scholarship', 'institution', 'applicable']);

        return view('student.scholarship-applications.show', compact('application'));
    }
}
