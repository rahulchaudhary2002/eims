<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\UsesActiveInstitution;
use App\Models\Institution;
use App\Models\InstitutionProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionProfileController extends Controller
{
    use UsesActiveInstitution;

    public function institutions(): View
    {
        return view('institution.modules.profile.institutions', [
            'institutions' => request()->user('web')->activeInstitutions()->orderBy('institutions.name')->get(),
        ]);
    }

    public function index(): View
    {
        $institution = $this->activeInstitution()->load('profile');

        return view('institution.modules.profile.index', compact('institution'));
    }

    public function update(Request $request): RedirectResponse
    {
        $institution = $this->activeInstitution();

        $institutionData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'country' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $institution->update($institutionData);

        $profileData = $request->validate([
            'facilities' => ['nullable', 'string'],
            'infrastructure' => ['nullable', 'string'],
            'achievements' => ['nullable', 'string'],
            'accreditations' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
        ]);

        foreach (['facilities', 'infrastructure', 'achievements', 'accreditations'] as $field) {
            $profileData[$field] = array_values(array_filter(array_map('trim', explode("\n", (string) ($profileData[$field] ?? '')))));
        }

        foreach (['has_hostel', 'has_transportation', 'has_library', 'has_lab', 'has_cafeteria', 'has_sports', 'has_scholarship'] as $field) {
            $profileData[$field] = $request->boolean($field);
        }

        InstitutionProfile::query()->updateOrCreate(
            ['institution_id' => $institution->id],
            $profileData
        );

        return back()->with('success', 'Institution profile updated successfully.');
    }
}
