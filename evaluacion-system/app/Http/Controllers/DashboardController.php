<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // You can customize the view name if you have a specific dashboard view.
        // If you don't have a dashboard Blade file, create resources/views/dashboard.blade.php.
        return view('dashboard');
    }
}
