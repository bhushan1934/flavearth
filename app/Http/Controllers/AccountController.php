<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('account.index');
    }

    public function orders()
    {
        return view('account.orders');
    }

    public function settings()
    {
        return view('account.settings');
    }
}