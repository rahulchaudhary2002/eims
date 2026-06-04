<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentReviewRequest;
use App\Http\Requests\Student\UpdateStudentReviewRequest;
use App\Models\Institution;
use App\Models\InstitutionReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentReviewController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user('student')->id;
        $reviews   = InstitutionReview::where('student_id', $studentId)
            ->with('institution')
            ->latest()
            ->paginate(12);

        return view('student.reviews.index', compact('reviews'));
    }

    public function create(Request $request): View
    {
        $institutions = Institution::active()->orderBy('name')->get();

        $selected = null;
        if ($request->has('institution')) {
            $selected = Institution::where('slug', $request->institution)->first();
        }

        return view('student.reviews.create', compact('institutions', 'selected'));
    }

    public function store(StoreStudentReviewRequest $request): RedirectResponse
    {
        $studentId = $request->user('student')->id;
        $data      = $request->validated();
        $data['student_id']  = $studentId;
        $data['is_approved'] = false;

        InstitutionReview::create($data);

        return redirect()->route('student.reviews.index')
            ->with('success', 'Review submitted. It will be visible once approved.');
    }

    public function show(Request $request, InstitutionReview $review): View
    {
        abort_if($review->student_id !== $request->user('student')->id, 403);

        $review->load('institution');

        return view('student.reviews.show', compact('review'));
    }

    public function edit(Request $request, InstitutionReview $review): View
    {
        abort_if($review->student_id !== $request->user('student')->id, 403);
        abort_if($review->is_approved, 403);

        $institutions = Institution::active()->orderBy('name')->get();

        return view('student.reviews.edit', compact('review', 'institutions'));
    }

    public function update(UpdateStudentReviewRequest $request, InstitutionReview $review): RedirectResponse
    {
        abort_if($review->student_id !== $request->user('student')->id, 403);
        abort_if($review->is_approved, 403);

        $review->update($request->validated());

        return redirect()->route('student.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    public function destroy(Request $request, InstitutionReview $review): RedirectResponse
    {
        abort_if($review->student_id !== $request->user('student')->id, 403);
        abort_if($review->is_approved, 403);

        $review->delete();

        return redirect()->route('student.reviews.index')
            ->with('success', 'Review deleted.');
    }
}
