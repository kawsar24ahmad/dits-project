<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all orders from the database
        $orders = Order::with('user', 'service')->paginate(2);

        // Return the view with the orders data
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the order by ID
        $order = Order::findOrFail($id);

        // Return the view with the order data
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'status' => 'required',
        ]);

        // Find the order by ID
        $order = Order::findOrFail($id);

        // Update the order status
        $order->update([
            'status' => $request->input('status'),
        ]);

        // Redirect back with a success message
        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        // Redirect back with a success message
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');

    }
}
