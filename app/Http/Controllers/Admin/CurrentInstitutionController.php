<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CurrentInstitutionController extends Controller
{
    public function switch(Request $request): RedirectResponse
    {
        $request->validate([
            'institution_id' => ['required', 'integer', 'exists:institutions,id'],
        ]);

        /** @var \App\Models\User $user */
        $user = auth('web')->user();
        $institutionId = (int) $request->institution_id;

        if (! $user->canAccessInstitution($institutionId)) {
            return back()->with('error', 'You do not have access to this institution.');
        }

        session(['current_institution_id' => $institutionId]);

        return back()->with('success', 'Institution switched successfully.');
    }
}
