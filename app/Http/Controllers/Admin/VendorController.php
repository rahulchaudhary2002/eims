<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $vendors = Vendor::paginate(10);
        return view('admin.modules.vendor.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $institutions = Institution::active()->get();
        return view('admin.modules.vendor.create', compact('institutions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:vendors,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'institutions' => 'nullable|array',
            'institutions.*' => 'exists:institutions,id',
        ]);

        $vendor = Vendor::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'is_active' => $request->is_active ?? false
        ]);

        // Sync institutions if provided
        if ($request->has('institutions')) {
            $vendor->institutions()->sync($request->institutions);
        }

        return redirect()->route('admin.vendor.index')
            ->with('success', 'Vendor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor): View
    {
        return view('admin.modules.vendor.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor): View
    {
        $institutions = Institution::active()->get();
        $vendor->load('institutions');

        return view('admin.modules.vendor.edit', compact('vendor', 'institutions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:vendors,email,' . $vendor->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'institutions' => 'nullable|array',
            'institutions.*' => 'exists:institutions,id',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $vendor->update([$updateData, 'is_active' => $request->is_active ?? false]);

        // Sync institutions
        if ($request->has('institutions')) {
            $vendor->institutions()->sync($request->institutions);
        } else {
            $vendor->institutions()->detach();
        }

        return redirect()->route('admin.vendor.index')
            ->with('success', 'Vendor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor): RedirectResponse
    {
        $vendor->delete();

        return redirect()->route('admin.vendor.index')->with('success', 'Vendor deleted successfully.');
    }
}
