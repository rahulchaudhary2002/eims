<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Admission;
use App\Models\AdmissionApplication;
use App\Models\AdmissionReward;
use App\Models\Vendor;
use App\Notifications\AdmissionApplicationRequest;
use App\Notifications\CollectAdmissionRewardRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AdmissionController extends Controller
{
    public function index()
    {
        $admissions = Admission::latest()->paginate(12);
        return view('modules.admission.index', compact('admissions'));
    }

    public function show(Admission $admission)
    {
        return view('modules.admission.show', compact('admission'));
    }

    public function apply(Admission $admission)
    {
        return view('modules.admission.apply', compact('admission'));
    }

    public function storeApplication(Request $request, Admission $admission)
    {
        // Check if an application with the same admission_id, user_id, course_id, and grade already exists
        $existing = AdmissionApplication::where('admission_id', $admission->id)
            ->where('user_id', Auth::id())
            ->where('program_id', $request->program_id)
            ->first();

        if ($existing) {
            return redirect()->route('admission.show', $admission->slug)
                ->with('error', 'You have already submitted an application for this admission with the same program.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email',
            'phone'     => 'required|string|max:20',
            'program_id' => 'required|exists:programs,id',
            'notes'     => 'nullable|string|max:1000',
            'academic_documents'   => 'required|array|min:1',
            'academic_documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $documents = [];

        if ($request->hasFile('academic_documents')) {
            foreach ($request->file('academic_documents') as $file) {
                $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('academic_documents', $filename, 'public');
                $documents[] = $path;
            }
        }

        $application = AdmissionApplication::create([
            'application_uuid' => (string) Str::uuid(),
            'admission_id' => $admission->id,
            'user_id'      => Auth::id(),
            'full_name'    => $request->full_name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'status'       => 'pending',
            'notes'        => $request->notes,
            'program_id'    => $request->program_id,
            'academic_documents' => json_encode($documents),
        ]);

        $users = Vendor::whereHas('institutions', function ($query) use ($admission) {
            $query->where('institution_id', $admission->institution_id);
        })->get();

        Notification::send($users, new AdmissionApplicationRequest($application));

        return redirect()->route('admission.show', $admission->slug)
            ->with('success', 'Your application has been submitted successfully.');
    }

    public function myApplications()
    {
        $applications = AdmissionApplication::where('user_id', Auth::id())
            ->with('admission')
            ->latest()
            ->paginate(10);

        return view('modules.admission.my_applications', compact('applications'));
    }

    public function myRewards()
    {
        $rewards = AdmissionReward::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('modules.admission.my_rewards', compact('rewards'));
    }

    public function storeReward(Request $request, AdmissionApplication $application)
    {
        $existingReward = AdmissionReward::where('admission_application_id', $application->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReward) {
            return redirect()->route('admission.application', 'application')
                ->with('error', 'You have already submitted a reward request for this application.');
        }

        $request->validate([
            'admission_receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('admission_receipt')) {
            $file = $request->file('admission_receipt');
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('admission_receipts', $filename, 'public');

            // Create AdmissionReward record
            AdmissionReward::create([
                'user_id' => Auth::id(),
                'admission_application_id' => $application->id,
                'admission_receipt' => $path,
                'status' => 'pending',
            ]);

            $users = Admin::get();
            Notification::send($users, new CollectAdmissionRewardRequest());

            return redirect()->route('admission.application', 'application')
                ->with('success', 'Your admission receipt has been submitted successfully.');
        }

        return redirect()->route('admission.application', 'application')
            ->with('error', 'There was an error uploading your admission receipt. Please try again.');
    }
}
