<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramListingController extends Controller
{
    public function index(Request $request)
    {
        $query = InstitutionProgram::with(['institution', 'program.faculty'])
            ->whereHas('institution', fn($q) => $q->active())
            ->where('status', '!=', 'suspended');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('program', fn($pq) => $pq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($faculty = $request->input('faculty')) {
            $query->whereHas('program.faculty', fn($q) => $q->where('slug', $faculty));
        }

        if ($level = $request->input('level')) {
            $query->whereHas('program', fn($q) => $q->where('level', $level));
        }

        if ($institution = $request->input('institution')) {
            $query->where('institution_id', $institution);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($request->filled('fee_max')) {
            $query->where('total_fee', '<=', $request->input('fee_max'));
        }

        if ($request->boolean('admission_open')) {
            $query->where('status', 'open');
        }

        $statusOrder = "CASE status WHEN 'open' THEN 1 WHEN 'upcoming' THEN 2 WHEN 'closed' THEN 3 ELSE 4 END";

        $programs   = $query->orderByRaw($statusOrder)
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        $faculties    = Faculty::where('is_active', true)->orderBy('name')->get(['id', 'name', 'slug']);
        $institutions = Institution::active()->orderBy('name')->get(['id', 'name']);
        $levels       = Program::distinct()->whereNotNull('level')->orderBy('level')->pluck('level');
        $statuses     = InstitutionProgram::STATUSES;

        return view('website.programs.index', compact('programs', 'faculties', 'institutions', 'levels', 'statuses'));
    }

    public function show(Program $program)
    {
        abort_unless($program->is_active, 404);

        $institutionPrograms = InstitutionProgram::where('program_id', $program->id)
            ->whereHas('institution', fn($q) => $q->active())
            ->where('status', '!=', 'suspended')
            ->with(['institution', 'scholarships' => fn($q) => $q->where('status', 'active')])
            ->orderByRaw("CASE status WHEN 'open' THEN 1 WHEN 'upcoming' THEN 2 WHEN 'closed' THEN 3 ELSE 4 END")
            ->get();

        $program->load('faculty');

        return view('website.programs.show', compact('program', 'institutionPrograms'));
    }
}
