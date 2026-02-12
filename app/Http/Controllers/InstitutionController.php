<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function show($institution_type, $institution_slug)
    {
        $institution = Institution::with('institutionType')
            ->ofType($institution_type)
            ->where('slug', $institution_slug)
            ->firstOrFail();

        return view('modules.institution.show', compact('institution'));
    }

    public function query($institution_type, $institution_slug, Request $request)
    {
        $institution = Institution::with('institutionType')
            ->ofType($institution_type)
            ->where('slug', $institution_slug)
            ->firstOrFail();

        return view('modules.institution.query', compact('institution'));
    }

    public function storeQuery($institution_type, $institution_slug, Request $request)
    {
        $institution = Institution::with('institutionType')
            ->ofType($institution_type)
            ->where('slug', $institution_slug)
            ->firstOrFail();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'type' => 'required|string|max:100',
            'message' => 'required|string|max:2000',
        ]);

        Enquiry::create([
            'institution_id' => $institution->id,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'type' => $validated['type'],
            'message' => $validated['message'],
        ]);

        return redirect()->route('institution.query', ['institution_type' => $institution_type, 'institution_slug' => $institution_slug])
            ->with('success', 'Your question has been submitted successfully.');
    }

    public function admissions($institution_type, $institution_slug)
    {
        $institution = Institution::with('institutionType')
            ->ofType($institution_type)
            ->where('slug', $institution_slug)
            ->firstOrFail();

        $admissions = $institution->admissions()->latest()->paginate(12);

        return view('modules.institution.admission', compact('admissions', 'institution'));
    }
}
