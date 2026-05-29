<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Student::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', (bool) $request->input('is_active'));
        }

        $students = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.modules.student.index', compact('students'));
    }

    public function create(): View
    {
        return view('admin.modules.student.create');
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('students/avatars', 'public');
        }

        $student = Student::create($data);

        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student): View
    {
        $student->load([
            'profile',
            'academicRecords',
            'documents',
            'applications' => fn ($q) => $q->with(['institution', 'institutionProgram.program', 'scholarship'])->latest(),
            'scholarshipApplications' => fn ($q) => $q->with('scholarship.institution')->latest(),
            'scholarshipCashbacks' => fn ($q) => $q->with('commissionInvoice')->latest(),
            'favoriteInstitutions' => fn ($q) => $q->with('institution')->latest(),
        ]);

        return view('admin.modules.student.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        return view('admin.modules.student.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active');

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($student->avatar) {
                Storage::disk('public')->delete($student->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('students/avatars', 'public');
        } else {
            unset($data['avatar']);
        }

        $student->update($data);

        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        if ($student->avatar) {
            Storage::disk('public')->delete($student->avatar);
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    public function updateStatus(Request $request, Student $student): RedirectResponse
    {
        $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $student->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Student status updated.');
    }
}
