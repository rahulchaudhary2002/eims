<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionCertification;
use Illuminate\Http\Request;

class CertificationListingController extends Controller
{
    public function index(Request $request)
    {
        $query = InstitutionCertification::with('institution')
            ->where('is_active', true)
            ->whereHas('institution', fn($q) => $q->active());

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($institution = $request->input('institution')) {
            $query->where('institution_id', $institution);
        }

        if ($request->filled('fee_max')) {
            $query->where('fee', '<=', $request->input('fee_max'));
        }

        $certifications = $query->orderBy('title')->paginate(12)->withQueryString();

        $institutions = Institution::active()->orderBy('name')->get(['id', 'name']);

        return view('website.certifications.index', compact('certifications', 'institutions'));
    }

    public function show(InstitutionCertification $certification)
    {
        abort_unless($certification->is_active, 404);

        $certification->loadMissing('institution');

        $relatedCertifications = InstitutionCertification::with('institution')
            ->where('institution_id', $certification->institution_id)
            ->where('id', '!=', $certification->id)
            ->where('is_active', true)
            ->orderBy('title')
            ->limit(4)
            ->get();

        return view('website.certifications.show', compact('certification', 'relatedCertifications'));
    }
}
