<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index()
    {
        $institution = session('current_institution');
        $enquiries = Enquiry::where('institution_id', $institution->id)->latest()->paginate(10);

        return view('vendor.modules.enquiry.index', compact('enquiries'));
    }

    public function show($enquiryId)
    {
        $institution = session('current_institution');
        $enquiry = Enquiry::where('institution_id', $institution->id)->where('id', $enquiryId)->firstOrFail();

        if ($enquiry->status === 'pending') {
            $enquiry->markAsRead();
        }

        return view('vendor.modules.enquiry.show', compact('enquiry'));
    }

    public function reply(Request $request, $enquiryId)
    {
        $request->validate([
            'reply_message' => 'required|string',
        ]);

        $institution = session('current_institution');
        $enquiry = Enquiry::where('institution_id', $institution->id)->where('id', $enquiryId)->firstOrFail();

        $enquiry->reply_message = $request->input('reply_message');
        $enquiry->replied_at = now();
        $enquiry->status = 'replied';
        $enquiry->save();

        return redirect()->route('vendor.enquiry.show', ['enquiry' => $enquiryId])
            ->with('success', 'Reply sent successfully.');
    }
}
