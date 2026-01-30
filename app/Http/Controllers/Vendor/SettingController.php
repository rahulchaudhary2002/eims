<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Vendor;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function setCurrentInstitution(Request $request)
    {
        try {
            $request->validate([
                'institution_id' => 'required|exists:institutions,id'
            ]);

            // Get the institution
            $institution = Institution::find($request->institution_id);

            // Check if vendor has access to this institution
            $vendor = auth()->guard('vendor')->user();

            // Assuming you have a relationship between vendor and institutions
            if ($vendor->institutions->contains($institution)) {
                session(['current_institution' => $institution]);

                return response()->json([
                    'success' => true,
                    'message' => 'Institution set successfully'
                ]);
            }

            // If vendor doesn't have access, clear the session
            session()->forget('current_institution');

            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this institution'
            ], 403);
        } catch (\Exception $e) {
            // Clear session on any error
            session()->forget('current_institution');

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function readAllNotifications(Request $request)
    {
        $vendor = Vendor::find(auth()->guard('vendor')->user()->id);

        $vendor->notifications()
            ->whereNull('read_at')
            ->where('data->institution_id', session('current_institution')?->id)
            ->get()
            ->markAsRead();

        return redirect()->back();
    }
}
