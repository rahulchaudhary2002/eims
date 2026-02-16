<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admission;
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
        $programs = Institution::find($institution->id)->programs;

        return view('vendor.modules.admission.create', compact('programs'));
    }

    /**
     * Store a newly created admission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'programs' => 'required|array',
            'programs.*' => 'exists:programs,id',
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

            $admission->programs()->attach($request->programs ?? []);
        });

        return redirect()->route('vendor.admission.index')->with('success', 'Admission created successfully.');
    }

    /**
     * Show the form for editing an admission.
     */
    public function edit(Admission $admission)
    {
        $institution = session('current_institution');
        $programs = Institution::find($institution->id)->programs;

        $selectedProgramIds = $admission->programs()
            ->pluck('programs.id')
            ->toArray();

        return view('vendor.modules.admission.edit', compact('admission', 'programs','selectedProgramIds'));
    }

    /**
     * Update an admission.
     */
    public function update(Request $request, Admission $admission)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'programs' => 'required|array',
            'programs.*' => 'exists:programs,id',
        ]);

        DB::transaction(function () use ($request, $admission) {
            $admission->update([
                'title' => $request->title,
                'admission_type' => $request->admission_type,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            $admission->programs()->sync($request->programs ?? []);
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
            $admission->programs()->detach();
            $admission->delete();
        });

        return redirect()->route('vendor.admission.index')->with('success', 'Admission deleted successfully.');
    }

    /**
     * Show admission details.
     */
    public function show(Admission $admission)
    {
        $programs = $admission->programs;

        return view('vendor.modules.admission.show', compact('admission', 'programs'));
    }
}
