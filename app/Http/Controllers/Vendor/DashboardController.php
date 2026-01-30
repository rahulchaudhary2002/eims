<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $instution = session('current_institution');
        $courseCount = Institution::find($instution->id)->courses()->count();
        $dueComission = Institution::find($instution->id)->commissions()->where('is_paid', false)->sum('commission_amount');
        $paidComission = Institution::find($instution->id)->commissions()->where('is_paid', true)->sum('commission_amount');
        return view('vendor.modules.dashboard.index', compact('courseCount', 'dueComission', 'paidComission'));
    }
}
