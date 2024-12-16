<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Display the list of orders for the logged-in user
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->get();
        return view('orders.index', compact('orders'));
    }

    // Display a specific order's details
    public function show($id)
    {
        // Fetch the commission by ID
        $commission = Commission::find($id);

        if (!$commission) {
            abort(404); // If the commission doesn't exist, show a 404 page
        }
        
        // Fetch the artist who created the commission
        $artist = $commission->user;

        return view('orders.show', compact('commission', 'artist'));
    }

    // Handle payment confirmation
    public function confirmPayment($id)
    {
        // Fetch the commission by ID
        $commission = Commission::find($id);

        if (!$commission) {
            return redirect()->route('orders.index')->with('error', 'Commission not found.');
        }

        // Create a new order entry or mark an order as paid
        Order::create([
            'user_id' => Auth::id(),
            'commission_id' => $commission->id,
            'status' => 'paid', // Or any other status you want to set
        ]);

        // Redirect to a success page or back to the orders page
        return redirect()->route('orders.index')->with('message', 'Payment confirmed successfully!');
    }
}
