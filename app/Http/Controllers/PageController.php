<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    /**
     * Display the Privacy Policy page.
     */
    public function privacyPolicy()
    {
        return view('privacy-policy');
    }

    /**
     * Display the Terms of Service page.
     */
    public function termsOfService()
    {
        return view('terms-of-service');
    }
}
