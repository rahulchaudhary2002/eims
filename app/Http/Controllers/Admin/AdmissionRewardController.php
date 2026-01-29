<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionReward;
use App\Models\Institution;
use App\Models\InstitutionAdmissionCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        DB::beginTransaction();

        try {
            $reward->update(['status' => 'approved', 'reward' => $request->reward]);

            $institution = Institution::where('id', $reward->admissionApplication->admission->institution_id)->first();
            $commission = $institution->courses()
                ->where('course_id', $reward->admissionApplication->course_id)
                ->first()
                ->pivot
                ->commission_amount;

            InstitutionAdmissionCommission::create([
                'institution_id' => $institution->id,
                'admission_reward_id' => $reward->id,
                'commission_amount' => $commission,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to approve reward: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.admission.reward.index')->with('success', 'Admission reward approved successfully.');
    }

    public function reject(AdmissionReward $reward)
    {
        $reward->update(['status' => 'rejected']);
        return redirect()->route('admin.admission.reward.index')->with('success', 'Admission reward rejected successfully.');
    }
}
