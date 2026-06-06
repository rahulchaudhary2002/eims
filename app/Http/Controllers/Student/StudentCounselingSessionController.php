<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentCounselingSessionRequest;
use App\Http\Requests\Student\UpdateStudentCounselingSessionRequest;
use App\Models\CounselingSession;
use App\Models\Institution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentCounselingSessionController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user('student')->id;
        $sessions  = CounselingSession::where('student_id', $studentId)
            ->with(['institution', 'counselor'])
            ->latest()
            ->paginate(12);

        return view('student.counseling-sessions.index', compact('sessions'));
    }

    public function create(Request $request): View
    {
        $institutions = Institution::active()->orderBy('name')->get();

        $selected = null;
        if ($request->has('institution')) {
            $selected = Institution::where('slug', $request->institution)->first();
        }

        return view('student.counseling-sessions.create', compact('institutions', 'selected'));
    }

    public function store(StoreStudentCounselingSessionRequest $request): RedirectResponse
    {
        $studentId = $request->user('student')->id;
        $data      = $request->validated();
        $data['student_id'] = $studentId;
        $data['status']     = 'pending';

        CounselingSession::create($data);

        return redirect()->route('student.counseling-sessions.index')
            ->with('success', 'Counseling session requested successfully.');
    }

    public function show(Request $request, CounselingSession $counselingSession): View
    {
        abort_if($counselingSession->student_id !== $request->user('student')->id, 403);

        $counselingSession->load(['institution', 'counselor']);

        return view('student.counseling-sessions.show', compact('counselingSession'));
    }

    public function edit(Request $request, CounselingSession $counselingSession): View
    {
        abort_if($counselingSession->student_id !== $request->user('student')->id, 403);
        abort_if(!in_array($counselingSession->status, ['pending', 'scheduled', 'rescheduled']), 403);

        $institutions = Institution::active()->orderBy('name')->get();

        return view('student.counseling-sessions.edit', compact('counselingSession', 'institutions'));
    }

    public function update(UpdateStudentCounselingSessionRequest $request, CounselingSession $counselingSession): RedirectResponse
    {
        abort_if($counselingSession->student_id !== $request->user('student')->id, 403);
        abort_if(!in_array($counselingSession->status, ['pending', 'scheduled', 'rescheduled']), 403);

        $data = $request->validated();
        $data['status'] = $counselingSession->status === 'pending' ? 'pending' : 'rescheduled';

        $counselingSession->update($data);

        return redirect()->route('student.counseling-sessions.index')
            ->with('success', $counselingSession->status === 'pending' ? 'Request updated successfully.' : 'Session rescheduled successfully.');
    }

    public function cancel(Request $request, CounselingSession $counselingSession): RedirectResponse
    {
        abort_if($counselingSession->student_id !== $request->user('student')->id, 403);
        abort_if(!in_array($counselingSession->status, ['pending', 'scheduled', 'rescheduled']), 403);

        $counselingSession->update(['status' => 'cancelled']);

        return back()->with('success', 'Session cancelled.');
    }
}
