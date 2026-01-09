<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admission;
use App\Models\AdmissionGrade;
use App\Models\Institution;
use Illuminate\Support\Facades\DB;

class AdmissionController extends Controller
{
    /**
     * Display a listing of admissions.
     */
    public function index()
    {
        $institution = session('current_institution');
        $admissions = Admission::where('institution_id', $institution->id)->latest()->paginate(10);

        return view('vendor.modules.admission.index', compact('admissions'));
    }

    /**
     * Show the form for creating a new admission.
     */
    public function create()
    {
        $institution = session('current_institution');
        $courses = Institution::find($institution->id)->courses;

        return view('vendor.modules.admission.create', compact('courses'));
    }

    /**
     * Store a newly created admission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'admission_type' => 'required|in:course,grade',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'courses' => 'required_if:admission_type,course|array',
            'grades' => 'required_if:admission_type,grade|array',
        ]);

        $institution = session('current_institution');

        DB::transaction(function () use ($request, $institution) {
            $admission = Admission::create([
                'title' => $request->title,
                'admission_type' => $request->admission_type,
                'institution_id' => $institution->id,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            $this->handleTypeData($admission, $request);
        });

        return redirect()->route('vendor.admission.index')->with('success', 'Admission created successfully.');
    }

    /**
     * Show the form for editing an admission.
     */
    public function edit(Admission $admission)
    {
        $institution = session('current_institution');
        $courses = Institution::find($institution->id)->courses;

        $selectedCourseIds = $admission->courses()
            ->pluck('courses.id')
            ->toArray();
        $grades = $admission->grades->sortBy('order')->pluck('name')->toArray();

        return view('vendor.modules.admission.edit', compact('admission', 'courses', 'grades', 'selectedCourseIds'));
    }

    /**
     * Update an admission.
     */
    public function update(Request $request, Admission $admission)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'admission_type' => 'required|in:course,grade',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'courses' => 'required_if:admission_type,course|array',
            'grades' => 'required_if:admission_type,grade|array',
        ]);

        DB::transaction(function () use ($request, $admission) {
            $admission->update([
                'title' => $request->title,
                'admission_type' => $request->admission_type,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            $this->handleTypeData($admission, $request);
        });

        return redirect()->route('vendor.admission.index')->with('success', 'Admission updated successfully.');
    }

    /**
     * Delete an admission.
     */
    public function destroy(Admission $admission)
    {
        DB::transaction(function () use ($admission) {
            $admission->grades()->delete();
            $admission->courses()->detach();
            $admission->delete();
        });

        return redirect()->route('vendor.admission.index')->with('success', 'Admission deleted successfully.');
    }

    /**
     * Handle courses or grades depending on admission type.
     */
    private function handleTypeData(Admission $admission, Request $request)
    {
        if ($request->admission_type === 'course') {
            // Sync selected courses
            $admission->courses()->sync($request->courses);
            // Remove old grades if any
            $admission->grades()->delete();
        } elseif ($request->admission_type === 'grade') {
            // Remove old grades
            $admission->grades()->delete();
            // Create new grades with order
            foreach ($request->grades as $index => $grade) {
                AdmissionGrade::create([
                    'admission_id' => $admission->id,
                    'name' => $grade,
                    'order' => $index,
                ]);
            }
            // Detach courses if any
            $admission->courses()->detach();
        }
    }

    /**
     * Show admission details.
     */
    public function show(Admission $admission)
    {
        $grades = $admission->grades->sortBy('order')->pluck('name')->toArray();
        $courses = $admission->courses;

        return view('vendor.modules.admission.show', compact('admission', 'grades', 'courses'));
    }
}
