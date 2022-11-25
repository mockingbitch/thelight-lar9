<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\DashboardConstant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected $breadcrumb = DashboardConstant::BREADCRUMB;

    /**
     * @return View
     */
    public function index() : View
    {
        return view('dashboard.index', [
            'breadcrumb' => $this->breadcrumb
        ]);
    }
}
