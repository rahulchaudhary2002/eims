<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Institution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function setCurrentInstitution(Request $request)
    {
        $request->validate([
            'institution_id' => 'required|exists:institutions,id',
        ]);

        $user = auth('web')->user();
        $institutionId = (int) $request->institution_id;

        // Super admin can switch to any institution
        if ($user->is_super_admin) {
            session(['current_institution_id' => $institutionId]);
            return response()->json(['success' => true, 'message' => 'Institution set successfully']);
        }

        // Normal users can only switch to their active assigned institutions
        $valid = $user->activeInstitutions()->where('institutions.id', $institutionId)->exists();

        if ($valid) {
            session(['current_institution_id' => $institutionId]);
            return response()->json(['success' => true, 'message' => 'Institution set successfully']);
        }

        return response()->json(['success' => false, 'message' => 'You do not have access to this institution'], 403);
    }

    public function readAllNotifications(Request $request)
    {
        $user = auth('web')->user();

        $user->notifications()
            ->whereNull('read_at')
            ->where('data->institution_id', session('current_institution_id'))
            ->get()
            ->markAsRead();

        return redirect()->back();
    }
}

