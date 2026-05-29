<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentProfileRequest;
use App\Http\Requests\Admin\UpdateStudentProfileRequest;
use App\Models\Student;
use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    /** JSON tag fields that must be decoded before saving */
    private const TAG_FIELDS = ['career_interests', 'preferred_faculties'];

    public function index(Request $request): View
    {
        $query = StudentProfile::with('student');

        // Filters
        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($province = $request->input('province')) {
            $query->where('province', $province);
        }
        if ($district = $request->input('district')) {
            $query->where('district', $district);
        }
        if ($city = $request->input('city')) {
            $query->where('city', $city);
        }
        if ($request->filled('budget_min')) {
            $query->where('budget_max', '>=', (int) $request->input('budget_min'));
        }
        if ($request->filled('budget_max')) {
            $query->where('budget_min', '<=', (int) $request->input('budget_max'));
        }

        $profiles  = $query->latest()->paginate(20)->withQueryString();
        $students  = Student::orderBy('name')->get(['id', 'name', 'email']);

        // Distinct values for filter dropdowns
        $provinces = StudentProfile::whereNotNull('province')->distinct()->orderBy('province')->pluck('province');
        $districts = StudentProfile::whereNotNull('district')->distinct()->orderBy('district')->pluck('district');
        $cities    = StudentProfile::whereNotNull('city')->distinct()->orderBy('city')->pluck('city');

        return view('admin.student-profiles.index', compact(
            'profiles', 'students', 'provinces', 'districts', 'cities'
        ));
    }

    public function create(): View
    {
        $students = Student::doesntHave('profile')->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.student-profiles.create', compact('students'));
    }

    public function store(StoreStudentProfileRequest $request): RedirectResponse
    {
        $data = $request->validated();

        foreach (self::TAG_FIELDS as $field) {
            $data[$field] = json_decode($data[$field] ?? '[]', true) ?: [];
        }

        $profile = StudentProfile::create($data);

        return redirect()->route('admin.student-profiles.show', $profile)
            ->with('success', 'Student profile created successfully.');
    }

    public function show(StudentProfile $studentProfile): View
    {
        $studentProfile->load('student');

        return view('admin.student-profiles.show', compact('studentProfile'));
    }

    public function edit(StudentProfile $studentProfile): View
    {
        $studentProfile->load('student');

        // Include current student in dropdown (even if they already have a profile)
        $students = Student::where(function ($q) use ($studentProfile) {
            $q->doesntHave('profile')
              ->orWhere('id', $studentProfile->student_id);
        })->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.student-profiles.edit', compact('studentProfile', 'students'));
    }

    public function update(UpdateStudentProfileRequest $request, StudentProfile $studentProfile): RedirectResponse
    {
        $data = $request->validated();

        foreach (self::TAG_FIELDS as $field) {
            $data[$field] = json_decode($data[$field] ?? '[]', true) ?: [];
        }

        $studentProfile->update($data);

        return redirect()->route('admin.student-profiles.show', $studentProfile)
            ->with('success', 'Student profile updated successfully.');
    }

    public function destroy(StudentProfile $studentProfile): RedirectResponse
    {
        $studentProfile->delete();

        return redirect()->route('admin.student-profiles.index')
            ->with('success', 'Student profile deleted successfully.');
    }
}
