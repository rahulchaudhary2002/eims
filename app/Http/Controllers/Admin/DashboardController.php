<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\Institution;
use App\Models\InstitutionAdmissionCommission;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $institutionCount = Institution::active()->count();
        $pendingComission = InstitutionAdmissionCommission::where('is_paid', false)->sum('commission_amount');
        $receivedComission = InstitutionAdmissionCommission::where('is_paid', true)->sum('commission_amount');
        $recentAdmissionApplications = AdmissionApplication::latest()->limit(10)->get();

        return view('admin.modules.dashboard.index', compact('institutionCount', 'pendingComission', 'receivedComission', 'recentAdmissionApplications'));
    }
}
