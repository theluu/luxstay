<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Room;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        return response()->json([
            'data' => [
                'total_bookings'   => Booking::count(),
                'total_orders'     => Order::count(),
                'total_rooms'      => Room::count(),
                'total_revenue'    => Booking::where('payment_status', 'paid')->sum('total_price')
                                   + Order::where('payment_status', 'paid')->sum('total'),
                'pending_bookings' => Booking::where('status', 'pending')->count(),
                'pending_orders'   => Order::where('status', 'pending')->count(),
            ],
        ]);
    }
}
