<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\InquiryRequest;
use App\Models\Institution;
use App\Models\Inquiry;
use App\Models\InstitutionProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function create(Request $request)
    {
        $institution        = null;
        $institutionProgram = null;

        if ($instSlug = $request->input('institution')) {
            $institution = Institution::active()->where('slug', $instSlug)->first();
        }

        if ($programSlug = $request->input('program')) {
            $institutionProgram = InstitutionProgram::with('institution', 'program')
                ->where('slug', $programSlug)
                ->first();

            if (! $institution && $institutionProgram?->institution) {
                $institution = $institutionProgram->institution;
            }
        }

        $institutions = Institution::active()->orderBy('name')->get(['id', 'name']);

        return view('website.inquiry.create', compact('institution', 'institutionProgram', 'institutions'));
    }

    public function store(InquiryRequest $request)
    {
        $data = $request->validated();

        $student = Auth::guard('student')->user();

        $inquiry = Inquiry::create([
            'student_id'            => $student?->id,
            'institution_id'        => $data['institution_id'] ?? null,
            'institution_program_id' => $data['institution_program_id'] ?? null,
            'name'                  => $data['name'],
            'email'                 => $data['email'],
            'phone'                 => $data['phone'],
            'message'               => $data['message'],
            'source'                => $data['source'] ?? 'website',
            'status'                => 'new',
        ]);

        return back()->with('success', 'Your inquiry has been submitted. We will get back to you shortly.');
    }
}
