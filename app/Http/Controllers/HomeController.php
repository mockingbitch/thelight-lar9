<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * @return View
     */
    public function index() : View
    {
        return view('home.index');
    }

    /**
     * @return View
     */
    public function catchError() : View
    {
        return view('home.error');
    }
}
