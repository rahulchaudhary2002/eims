<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipCashback;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentCashbackController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user('student')->id;
        $cashbacks = ScholarshipCashback::where('student_id', $studentId)
            ->with(['application.institution', 'application.applicable'])
            ->latest()
            ->paginate(12);

        $totalPaid = ScholarshipCashback::where('student_id', $studentId)
            ->where('status', 'paid')
            ->sum('cashback_amount');

        return view('student.cashbacks.index', compact('cashbacks', 'totalPaid'));
    }

    public function show(Request $request, ScholarshipCashback $cashback): View
    {
        abort_if($cashback->student_id !== $request->user('student')->id, 403);

        $cashback->load(['application.institution', 'application.applicable', 'commissionInvoice']);

        return view('student.cashbacks.show', compact('cashback'));
    }
}
