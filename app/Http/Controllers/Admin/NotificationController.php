<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function readAll(Request $request)
    {
        Auth::guard('web')->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    public function read(Request $request, string $id)
    {
        $notification = Auth::guard('web')->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back();
    }
}
