<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionReward;
use Illuminate\Http\Request;

class AdmissionRewardController extends Controller
{
    public function index()
    {
        $rewards = AdmissionReward::latest()->paginate(10);
        return view('admin.modules.admission_reward.index', compact('rewards'));
    }

    public function approve(AdmissionReward $reward, Request $request)
    {
        $request->validate([
            'reward' => 'required|numeric|min:0',
        ]);
        $reward->update(['status' => 'approved', 'reward' => $request->reward]);
        return redirect()->route('admin.admission.reward.index')->with('success', 'Admission reward approved successfully.');
    }

    public function reject(AdmissionReward $reward)
    {
        $reward->update(['status' => 'rejected']);
        return redirect()->route('admin.admission.reward.index')->with('success', 'Admission reward rejected successfully.');
    }
}
