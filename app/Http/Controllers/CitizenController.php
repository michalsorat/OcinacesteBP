<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CitizenController extends Controller
{
    public function about() {
        return view('views.aboutProject');
    }

    public function mobileApp() {
        return view('views.mobileAppInfo');
    }
}
