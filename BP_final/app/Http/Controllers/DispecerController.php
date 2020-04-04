<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DispecerController extends Controller
{
    public function index()
    {
        return view('dispecer.home');
    }

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:dispecer');
    }
}
