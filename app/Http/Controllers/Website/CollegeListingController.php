<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Institution;
use Illuminate\Http\Request;

class CollegeListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Institution::active()
            ->where('type', 'college')
            ->with(['profile'])
            ->withCount(['programs', 'reviews'])
            ->withAvg(['reviews' => fn($q) => $q->where('is_approved', true)], 'rating');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($province = $request->input('province')) {
            $query->where('province', $province);
        }

        if ($district = $request->input('district')) {
            $query->where('district', $district);
        }

        if ($city = $request->input('city')) {
            $query->where('city', 'like', "%{$city}%");
        }

        if ($request->boolean('is_verified')) {
            $query->where('is_verified', true);
        }

        if ($request->boolean('is_featured')) {
            $query->where('is_featured', true);
        }

        if ($faculty = $request->input('faculty')) {
            $query->whereHas('programs.program.faculty', fn($q) => $q->where('slug', $faculty));
        }

        $sort = $request->input('sort', 'name');

        $colleges = $query->orderBy('is_featured', 'desc')
            ->when($sort === 'established_year', fn($q) => $q->orderByDesc('established_year'))
            ->when($sort !== 'established_year', fn($q) => $q->orderBy('name'))
            ->paginate(12)
            ->withQueryString();

        $provinces = Institution::active()->where('type', 'college')->whereNotNull('province')->distinct()->orderBy('province')->pluck('province');
        $faculties = Faculty::where('is_active', true)->orderBy('name')->get(['id', 'name', 'slug']);

        return view('website.colleges.index', compact('colleges', 'provinces', 'faculties'));
    }
}
