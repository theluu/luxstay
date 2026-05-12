<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        return response()->json([
            'data' => [
                'total_rooms'    => Room::count(),
                'total_bookings' => Booking::count(),
                'total_orders'   => Order::count(),
                'total_users'    => User::where('role', 'user')->count(),
                'revenue'        => Order::where('payment_status', 'paid')->sum('total'),
            ],
        ]);
    }
}
