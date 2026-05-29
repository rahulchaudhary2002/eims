<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFacultyRequest;
use App\Http\Requests\Admin\UpdateFacultyRequest;
use App\Models\Faculty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FacultyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Faculty::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'ilike', '%' . $search . '%');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', (bool) $request->input('is_active'));
        }

        $faculties = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.modules.faculties.index', compact('faculties'));
    }

    public function create(): View
    {
        return view('admin.modules.faculties.create');
    }

    public function store(StoreFacultyRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug']      = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        $faculty = Faculty::create($data);

        return redirect()->route('admin.faculties.show', $faculty)
            ->with('success', 'Faculty created successfully.');
    }

    public function show(Faculty $faculty): View
    {
        $faculty->load(['programs' => fn ($q) => $q->orderBy('name')]);
        return view('admin.modules.faculties.show', compact('faculty'));
    }

    public function edit(Faculty $faculty): View
    {
        return view('admin.modules.faculties.edit', compact('faculty'));
    }

    public function update(UpdateFacultyRequest $request, Faculty $faculty): RedirectResponse
    {
        $data = $request->validated();
        $data['slug']      = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        $faculty->update($data);

        return redirect()->route('admin.faculties.show', $faculty)
            ->with('success', 'Faculty updated successfully.');
    }

    public function destroy(Faculty $faculty): RedirectResponse
    {
        $faculty->delete();

        return redirect()->route('admin.faculties.index')
            ->with('success', 'Faculty deleted successfully.');
    }

    public function updateStatus(Request $request, Faculty $faculty): RedirectResponse
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $faculty->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Faculty status updated.');
    }
}
