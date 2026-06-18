<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
class HomeController extends Controller
{
    public function index()
    {
        return view('guest.pages.home');
    }
}
