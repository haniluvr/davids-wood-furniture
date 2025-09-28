<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    // Middleware will be handled in routes

    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $wishlistItems = WishlistItem::where('user_id', $user->id)
            ->with('product')
            ->limit(6)
            ->get();

        return view('account', compact('user', 'orders', 'wishlistItems'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('account', compact('orders'));
    }

    public function wishlist()
    {
        $user = Auth::user();
        $wishlistItems = WishlistItem::where('user_id', $user->id)
            ->with('product')
            ->paginate(12);

        return view('account', compact('wishlistItems'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('account', compact('user'));
    }
}
