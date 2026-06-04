<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInstitutionProgramRequest;
use App\Http\Requests\Admin\UpdateInstitutionProgramRequest;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionProgramController extends Controller
{
    public function index(Request $request): View
    {
        $query = InstitutionProgram::with(['institution', 'program']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', '%' . $search . '%')
                  ->orWhereHas('institution', fn($q2) => $q2->where('name', 'ilike', '%' . $search . '%'))
                  ->orWhereHas('program', fn($q2) => $q2->where('name', 'ilike', '%' . $search . '%'));
            });
        }

        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($programId = $request->input('program_id')) {
            $query->where('program_id', $programId);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($admissionFrom = $request->input('admission_from')) {
            $query->where('admission_start_date', '>=', $admissionFrom);
        }
        if ($admissionTo = $request->input('admission_to')) {
            $query->where('admission_end_date', '<=', $admissionTo);
        }
        if ($feeMin = $request->input('fee_min')) {
            $query->where('total_fee', '>=', $feeMin);
        }
        if ($feeMax = $request->input('fee_max')) {
            $query->where('total_fee', '<=', $feeMax);
        }

        $institutionPrograms = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $institutions        = Institution::where('type', '!=', 'consultancy')->orderBy('name')->get(['id', 'name']);
        $programs            = Program::orderBy('name')->get(['id', 'name']);

        return view('admin.modules.institution-programs.index', compact('institutionPrograms', 'institutions', 'programs'));
    }

    public function create(Request $request): View
    {
        $institutions = Institution::where('type', '!=', 'consultancy')->orderBy('name')->get(['id', 'name']);
        $programs     = Program::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        $selectedInstitutionId = $request->input('institution_id');
        $selectedProgramId     = $request->input('program_id');

        return view('admin.modules.institution-programs.create', compact(
            'institutions', 'programs', 'selectedInstitutionId', 'selectedProgramId'
        ));
    }

    public function store(StoreInstitutionProgramRequest $request): RedirectResponse
    {
        $data = $request->validated();
        abort_if(
            Institution::where('id', $data['institution_id'])->where('type', 'consultancy')->exists(),
            422,
            'Consultancy institutions cannot have programs.'
        );

        $institutionProgram = InstitutionProgram::create($data);

        return redirect()->route('admin.institution-programs.show', $institutionProgram)
            ->with('success', 'Institution program created successfully.');
    }

    public function show(InstitutionProgram $institutionProgram): View
    {
        $institutionProgram->load(['institution', 'program.faculty', 'subjects', 'scholarships', 'applications.student', 'applications.scholarship']);

        return view('admin.modules.institution-programs.show', compact('institutionProgram'));
    }

    public function edit(InstitutionProgram $institutionProgram): View
    {
        $institutionProgram->load(['institution', 'program', 'subjects']);

        $institutions = Institution::where('type', '!=', 'consultancy')->orderBy('name')->get(['id', 'name']);
        $programs     = Program::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('admin.modules.institution-programs.edit', compact('institutionProgram', 'institutions', 'programs'));
    }

    public function update(UpdateInstitutionProgramRequest $request, InstitutionProgram $institutionProgram): RedirectResponse
    {
        $data = $request->validated();
        abort_if(
            Institution::where('id', $data['institution_id'])->where('type', 'consultancy')->exists(),
            422,
            'Consultancy institutions cannot have programs.'
        );

        $institutionProgram->update($data);

        return redirect()->route('admin.institution-programs.show', $institutionProgram)
            ->with('success', 'Institution program updated successfully.');
    }

    public function destroy(InstitutionProgram $institutionProgram): RedirectResponse
    {
        $institutionProgram->delete();

        return redirect()->route('admin.institution-programs.index')
            ->with('success', 'Institution program deleted successfully.');
    }

    public function updateStatus(Request $request, InstitutionProgram $institutionProgram): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:open,closed,upcoming,suspended'],
        ]);

        $institutionProgram->update(['status' => $request->input('status')]);

        return back()->with('success', 'Status updated successfully.');
    }
}
