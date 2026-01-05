<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InstitutionController extends Controller
{
    public function profile()
    {
        $current_institution = session()->get('current_institution');

        if (!$current_institution) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'Please select an institution to continue.');
        }

        $institution = Institution::where('id', $current_institution->id)->first();

        if (!$institution) {
            session()->forget('current_institution');
            return redirect()->route('vendor.dashboard')
                ->with('error', 'Selected institution not found.');
        }

        return view('vendor.modules.institution.profile', compact('institution'));
    }

    public function edit()
    {
        $current_institution = session()->get('current_institution');

        if (!$current_institution) {
            return back()->with('error', 'Please select an institution first.');
        }

        $institution = Institution::where('id', $current_institution->id)->first();

        return view('vendor.modules.institution.edit', compact('institution'));
    }

    public function update(Request $request)
    {
        $current_institution = session()->get('current_institution');

        if (!$current_institution) {
            return back()->with('error', 'Please select an institution first.');
        }

        $institution = Institution::where('id', $current_institution->id)->first();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => ['nullable', 'email', Rule::unique('institutions')->ignore($institution->id)],
            'established_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'type' => ['required', Rule::in(['college', 'school'])],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['logo', 'cover_image']);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($institution->logo && Storage::disk('public')->exists($institution->logo)) {
                Storage::disk('public')->delete($institution->logo);
            }

            $logoPath = $request->file('logo')->store('institutions/logos', 'public');
            $data['logo'] = $logoPath;
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover if exists
            if ($institution->cover_image && Storage::disk('public')->exists($institution->cover_image)) {
                Storage::disk('public')->delete($institution->cover_image);
            }

            $coverPath = $request->file('cover_image')->store('institutions/covers', 'public');
            $data['cover_image'] = $coverPath;
        }

        $institution->update($data);

        return redirect()->route('vendor.institution.profile')
            ->with('success', 'Institution profile updated successfully.');
    }
}
