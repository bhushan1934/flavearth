<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    /**
     * Show the about page.
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Show the contact page.
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Handle contact form submission.
     */
    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Here you would typically send an email
        // For now, we'll just return a success response
        
        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    /**
     * Show the blog page.
     */
    public function blog()
    {
        return view('pages.blog');
    }

    /**
     * Show the privacy policy page.
     */
    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    /**
     * Show the terms page.
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Show the shipping page.
     */
    public function shipping()
    {
        return view('pages.shipping');
    }
}