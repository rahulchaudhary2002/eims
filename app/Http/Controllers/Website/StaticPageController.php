<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;

class StaticPageController extends Controller
{
    public function about()
    {
        return view('website.static.about');
    }

    public function privacyPolicy()
    {
        return view('website.static.privacy-policy');
    }

    public function terms()
    {
        return view('website.static.terms-and-conditions');
    }
}
