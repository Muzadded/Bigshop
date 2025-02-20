<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.home');
    }

    public function latest_order()
    {
        $latest_orders =  Order::where('status', 'pending')->get();
        return view('admin.home', compact('latest_orders')); // Pass the orders to the view
    }
}
