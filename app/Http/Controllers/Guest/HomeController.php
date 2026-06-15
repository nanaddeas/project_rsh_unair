<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::active()->get();
        return view('guest.pages.home', compact('services'));
    }

    public function services()
    {
        $services = Service::active()->get();
        return view('guest.pages.services', compact('services'));
    }
}
