<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Institution;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $instution = Institution::find(session('current_institution_id'));
        $programCount = $instution?->programs()->count() ?? 0;
        $dueComission = 0;
        $paidComission = 0;
        $recentApplications = Application::with(['student', 'institutionProgram.program'])
            ->where('institution_id', $instution?->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('vendor.modules.dashboard.index', compact('programCount', 'dueComission', 'paidComission', 'recentApplications'));
    }
}
