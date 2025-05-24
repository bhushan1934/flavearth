<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Handle newsletter subscription.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        // Here you would typically save to database or send to email service
        // For now, we'll just return a success response
        
        return redirect()->back()->with('success', 'Thank you for subscribing to our newsletter! You will receive updates about our latest spices and exclusive offers.');
    }
}