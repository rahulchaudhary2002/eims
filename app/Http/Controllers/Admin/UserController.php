<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Institution;
use App\Models\UserInstitution;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UserController extends Controller
{
    use ScopesForInstitution;
    public function index(Request $request): View
    {
        $query = User::query();

        // Institution scope: non-super-admins only see users in their current institution
        $scope = $this->institutionScope();
        if ($scope !== null) {
            $query->whereHas('institutions', fn($q) => $q->where('institutions.id', $scope)->wherePivot('is_active', true));
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_super_admin')) {
            $query->where('is_super_admin', (bool) $request->input('is_super_admin'));
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', (bool) $request->input('is_active'));
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.modules.user.index', compact('users'));
    }

    public function create(): View
    {
        $institutions = Institution::orderBy('name')->get(['id', 'name']);

        return view('admin.modules.user.create', compact('institutions'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['is_active']      = $request->boolean('is_active', true);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('users/avatars', 'public');
        }

        // Strip institution data from user fillable
        unset($data['institutions'], $data['primary_institution_id']);

        $user = User::create($data);

        // Sync institution assignments
        $this->syncInstitutions($user, $request);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        $this->authorizeUserAccess($user);
        $user->load('institutions');

        return view('admin.modules.user.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $this->authorizeUserAccess($user);
        $user->load('institutions');
        $institutions = Institution::orderBy('name')->get(['id', 'name']);

        return view('admin.modules.user.edit', compact('user', 'institutions'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorizeUserAccess($user);
        $data = $request->validated();

        $data['is_active']      = $request->boolean('is_active');

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('users/avatars', 'public');
        } else {
            unset($data['avatar']);
        }

        unset($data['institutions'], $data['primary_institution_id']);

        $user->update($data);

        // Sync institution assignments
        $this->syncInstitutions($user, $request);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeUserAccess($user);
        abort_if($user->is_super_admin, 403, 'Super admin users cannot be deleted.');

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        $this->authorizeUserAccess($user);
        $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $user->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'User status updated.');
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function authorizeUserAccess(User $user): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            abort_unless(
                $user->institutions()->where('institutions.id', $scope)->wherePivot('is_active', true)->exists(),
                403,
                'You do not have access to this user.'
            );
        }
    }

    private function syncInstitutions(User $user, Request $request): void
    {
        $rows = $request->input('institutions', []);

        if (empty($rows)) {
            $user->institutions()->sync([]);
            return;
        }

        $primaryId = (int) $request->input('primary_institution_id');
        $syncData  = [];

        foreach ($rows as $row) {
            $instId = (int) ($row['institution_id'] ?? 0);
            if (! $instId) {
                continue;
            }

            $syncData[$instId] = [
                'role'       => $row['role'] ?? 'staff',
                'is_primary' => $primaryId && $primaryId === $instId,
                'is_active'  => ($row['is_active'] ?? '1') === '1',
                'joined_at'  => ! empty($row['joined_at']) ? $row['joined_at'] : null,
            ];
        }

        // Ensure only one primary - if none specified, first row gets it
        $hasPrimary = collect($syncData)->contains('is_primary', true);
        if (! $hasPrimary && count($syncData) > 0) {
            $firstKey = array_key_first($syncData);
            $syncData[$firstKey]['is_primary'] = true;
        }

        $user->institutions()->sync($syncData);
    }
}
