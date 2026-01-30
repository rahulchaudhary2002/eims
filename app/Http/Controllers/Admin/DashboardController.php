<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionAdmissionCommission;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $collegeCount = Institution::where('type', 'college')->count();
        $schoolCount = Institution::where('type', 'school')->count();
        $pendingComission = InstitutionAdmissionCommission::where('is_paid', false)->sum('commission_amount');
        $receivedComission = InstitutionAdmissionCommission::where('is_paid', true)->sum('commission_amount');
        return view('admin.modules.dashboard.index', compact('collegeCount', 'schoolCount', 'pendingComission', 'receivedComission'));
    }
}
