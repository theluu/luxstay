<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(): View
    {
        $rooms = Room::with('roomType')->where('is_available', true)->get();
        return view('pages.rooms.index', compact('rooms'));
    }

    public function suites(): View
    {
        $rooms = Room::with('roomType')->where('is_available', true)->get();
        return view('pages.rooms.suites', compact('rooms'));
    }

    public function show(string $slug): View
    {
        $room = Room::with(['roomType', 'amenities'])->where('slug', $slug)->firstOrFail();
        return view('pages.rooms.show', compact('room'));
    }
}
