<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
         $all_orders = Order::all();
         return view('admin.orders.index', compact('all_orders')); // Pass the orders to the view
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        //
    }

    public function edit($order_id) {
        $order = Order::findOrFail($order_id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }

    public function updateStatus($order_id, $status)
{
    // Define valid status values based on the ENUM in the database
    $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];

    // Check if the provided status is valid
    if (!in_array($status, $validStatuses)) {
        return redirect()->route('order.index')->with('error', 'Invalid status update');
    }

    // Find the order and update its status
    $order = Order::findOrFail($order_id);
    $order->status = $status;  // Update with the chosen status

    // Save the order
    $order->save();

    // Redirect with a success message
    return redirect()->route('order.index')->with('success', 'Order status updated to ' . ucfirst($status));
}


}