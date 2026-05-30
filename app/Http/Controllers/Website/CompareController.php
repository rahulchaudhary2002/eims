<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\StudentCompareItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompareController extends Controller
{
    private const MAX_ITEMS = 4;

    public function index()
    {
        $compareData = $this->getCompareData();

        $institutions = collect();
        $programs     = collect();

        if (! empty($compareData['institutions'])) {
            $institutions = Institution::whereIn('id', $compareData['institutions'])
                ->active()
                ->with(['profile', 'programs'])
                ->withCount(['programs', 'reviews'])
                ->withAvg(['reviews' => fn($q) => $q->where('is_approved', true)], 'rating')
                ->get();
        }

        if (! empty($compareData['programs'])) {
            $programs = InstitutionProgram::whereIn('id', $compareData['programs'])
                ->with(['institution', 'program.faculty', 'subjects'])
                ->get();
        }

        return view('website.compare.index', compact('institutions', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:institution,program'],
            'slug' => ['required', 'string'],
        ]);

        $type = $validated['type'];
        $id   = $this->resolveSlugToId($type, $validated['slug']);

        if (! $id) {
            return back()->with('error', 'Item not found.');
        }

        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $count   = StudentCompareItem::where('student_id', $student->id)->count();

            if ($count >= self::MAX_ITEMS) {
                return back()->with('error', 'Compare list is full. Remove an item first (max ' . self::MAX_ITEMS . ').');
            }

            $field = $type === 'institution' ? 'institution_id' : 'institution_program_id';

            StudentCompareItem::firstOrCreate([
                'student_id' => $student->id,
                $field       => $id,
            ]);
        } else {
            $compare = session('website_compare', ['institutions' => [], 'programs' => []]);
            $key     = $type === 'institution' ? 'institutions' : 'programs';
            $list    = $compare[$key] ?? [];
            $total   = count($compare['institutions'] ?? []) + count($compare['programs'] ?? []);

            if ($total >= self::MAX_ITEMS) {
                return back()->with('error', 'Compare list is full. Remove an item first (max ' . self::MAX_ITEMS . ').');
            }

            if (! in_array($id, $list)) {
                $list[]        = $id;
                $compare[$key] = $list;
                session(['website_compare' => $compare]);
            }
        }

        return back()->with('success', 'Added to compare list.');
    }

    public function destroy(Request $request, string $type, string $slug)
    {
        $id = $this->resolveSlugToId($type, $slug);

        if (! $id) {
            return back()->with('error', 'Item not found.');
        }

        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $field   = $type === 'institution' ? 'institution_id' : 'institution_program_id';

            StudentCompareItem::where('student_id', $student->id)
                ->where($field, $id)
                ->delete();
        } else {
            $compare = session('website_compare', ['institutions' => [], 'programs' => []]);
            $key     = $type === 'institution' ? 'institutions' : 'programs';

            $compare[$key] = array_values(array_filter($compare[$key] ?? [], fn($v) => $v !== $id));
            session(['website_compare' => $compare]);
        }

        return back()->with('success', 'Removed from compare list.');
    }

    private function resolveSlugToId(string $type, string $slug): ?int
    {
        if ($type === 'institution') {
            return Institution::where('slug', $slug)->value('id');
        }

        return InstitutionProgram::where('slug', $slug)->value('id');
    }

    private function getCompareData(): array
    {
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            $items   = StudentCompareItem::where('student_id', $student->id)->get();

            return [
                'institutions' => $items->whereNotNull('institution_id')->pluck('institution_id')->toArray(),
                'programs'     => $items->whereNotNull('institution_program_id')->pluck('institution_program_id')->toArray(),
            ];
        }

        return session('website_compare', ['institutions' => [], 'programs' => []]);
    }
}
