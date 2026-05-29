<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionSwitcherController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user('web');
        $institutions = $user->is_super_admin
            ? \App\Models\Institution::active()->orderBy('name')->get()
            : $user->activeInstitutions()->orderBy('institutions.name')->get();

        return view('institution.modules.select', compact('institutions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'institution_id' => ['required', 'integer', 'exists:institutions,id'],
        ]);

        $institutionId = (int) $data['institution_id'];
        $user = $request->user('web');

        abort_unless($user->canAccessInstitution($institutionId), 403);

        session(['active_institution_id' => $institutionId]);

        return redirect()->route('institution.dashboard');
    }
}
