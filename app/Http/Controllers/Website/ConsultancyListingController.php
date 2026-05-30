<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\Request;

class ConsultancyListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Institution::active()
            ->where('type', 'consultancy')
            ->with([
                'consultancyServices'     => fn($q) => $q->where('is_active', true)->limit(4),
                'consultancyDestinations' => fn($q) => $q->where('is_active', true),
            ])
            ->withCount([
                'consultancyServices'     => fn($q) => $q->where('is_active', true),
                'consultancyDestinations' => fn($q) => $q->where('is_active', true),
            ]);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($province = $request->input('province')) {
            $query->where('province', $province);
        }

        if ($district = $request->input('district')) {
            $query->where('district', $district);
        }

        if ($country = $request->input('destination')) {
            $query->whereHas('consultancyDestinations', fn($q) => $q->where('country', $country)->where('is_active', true));
        }

        if ($service = $request->input('service')) {
            $query->whereHas('consultancyServices', fn($q) => $q->where('service_type', $service)->where('is_active', true));
        }

        $consultancies = $query->orderBy('is_featured', 'desc')->orderBy('name')->paginate(12)->withQueryString();

        $provinces    = Institution::active()->where('type', 'consultancy')->whereNotNull('province')->distinct()->orderBy('province')->pluck('province');
        $destinations = \App\Models\ConsultancyDestination::where('is_active', true)->distinct()->orderBy('country')->pluck('country');
        $serviceTypes = \App\Models\ConsultancyService::SERVICE_TYPES;

        return view('website.consultancies.index', compact('consultancies', 'provinces', 'destinations', 'serviceTypes'));
    }

    public function show(Institution $institution)
    {
        abort_unless($institution->status === 'active' && $institution->type === 'consultancy', 404);

        $institution->load([
            'profile',
            'consultancyServices'     => fn($q) => $q->where('is_active', true),
            'consultancyDestinations' => fn($q) => $q->where('is_active', true),
            'posts'                   => fn($q) => $q->where('is_published', true)->orderByDesc('published_at')->limit(4),
            'reviews'                 => fn($q) => $q->where('is_approved', true)->with('student')->limit(6),
        ]);

        $institution->loadAvg(['reviews' => fn($q) => $q->where('is_approved', true)], 'rating');

        return view('website.consultancies.show', compact('institution'));
    }
}
