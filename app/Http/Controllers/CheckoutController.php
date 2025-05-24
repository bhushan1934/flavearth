<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('checkout.index');
    }

    public function process(Request $request)
    {
        // Process checkout logic here
        return redirect()->route('checkout.success');
    }

    public function success()
    {
        return view('checkout.success');
    }
}