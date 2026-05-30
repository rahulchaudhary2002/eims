<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\ContactRequest;
use App\Models\Inquiry;

class ContactController extends Controller
{
    public function index()
    {
        return view('website.contact');
    }

    public function store(ContactRequest $request)
    {
        $data = $request->validated();

        Inquiry::create([
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'],
            'message' => $data['message'],
            'source'  => 'contact_page',
            'status'  => 'new',
        ]);

        return back()->with('success', 'Thank you for reaching out. We will get back to you soon!');
    }
}
