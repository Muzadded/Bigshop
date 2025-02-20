<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
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
        $order_count = Order::count();
        $user_count = User::count();
        return view('admin.home', compact('latest_orders','order_count','user_count')); // Pass the orders to the view
    }
}
