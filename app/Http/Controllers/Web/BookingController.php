<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentTransaction;
use App\Models\Room;
use App\Services\Payments\VnpayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(private readonly VnpayService $vnpay)
    {
    }

    public function store(Request $request): RedirectResponse
    {
        $guestRequired = Auth::check() ? 'nullable' : 'required';

        $data = $request->validate([
            'room_id'          => 'required|exists:rooms,id',
            'check_in'         => 'required|date|after_or_equal:today',
            'check_out'        => 'required|date|after:check_in',
            'guests'           => 'required|integer|min:1',
            'guest_name'       => $guestRequired . '|string|max:100',
            'guest_email'      => $guestRequired . '|email|max:255',
            'guest_phone'      => 'nullable|string|max:30',
            'payment_method'   => 'required|in:pay_later,vnpay',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        if ($data['payment_method'] === 'vnpay') {
            try {
                $this->vnpay->assertConfigured();
            } catch (\RuntimeException $e) {
                return back()->withInput()->with('error', $e->getMessage());
            }
        }

        $room   = Room::findOrFail($data['room_id']);
        $nights = (int) \Carbon\Carbon::parse($data['check_in'])->diffInDays($data['check_out']);
        $total  = $room->price_per_night * $nights;

        $booking = Booking::create([
            'user_id'          => Auth::id(),
            'room_id'          => $room->id,
            'check_in'         => $data['check_in'],
            'check_out'        => $data['check_out'],
            'guests'           => $data['guests'],
            'status'           => 'pending',
            'payment_status'   => 'unpaid',
            'total_price'      => $total,
            'special_requests' => $this->buildSpecialRequests($data),
        ]);

        if (!Auth::check()) {
            session(['guest_booking_id' => $booking->id]);
        }

        if ($data['payment_method'] === 'vnpay') {
            $txnRef = 'BKG-' . $booking->id . '-' . now()->format('YmdHis');
            $booking->update(['vnpay_txn_ref' => $txnRef]);

            PaymentTransaction::create([
                'payable_type' => Booking::class,
                'payable_id' => $booking->id,
                'amount' => $booking->total_price,
                'gateway' => 'vnpay',
                'status' => 'pending',
                'gateway_ref' => $txnRef,
            ]);

            $url = $this->vnpay->createPaymentUrl(
                $booking,
                $txnRef,
                (float) $booking->total_price,
                'Thanh toan dat phong ' . $booking->id,
                $request
            );

            return redirect()->away($url);
        }

        return redirect()->route('bookings.confirmation', $booking)
            ->with('success', 'Booking confirmed! We will contact you shortly.');
    }

    public function confirmation(Booking $booking): View
    {
        abort_unless($this->canViewBooking($booking), 403);
        $booking->load('room.roomType');
        return view('pages.booking.confirmation', compact('booking'));
    }

    public function index(): View
    {
        $bookings = Auth::user()->bookings()->with('room')->latest()->paginate(10);
        return view('pages.account.bookings', compact('bookings'));
    }

    private function canViewBooking(Booking $booking): bool
    {
        if (Auth::check()) {
            return $booking->user_id === Auth::id();
        }

        return $booking->user_id === null && session('guest_booking_id') === $booking->id;
    }

    private function buildSpecialRequests(array $data): ?string
    {
        $lines = [];

        if (!Auth::check()) {
            $lines[] = 'Khách: ' . $data['guest_name'];
            $lines[] = 'Email: ' . $data['guest_email'];
            if (!empty($data['guest_phone'])) {
                $lines[] = 'Điện thoại: ' . $data['guest_phone'];
            }
        }

        if (!empty($data['special_requests'])) {
            $lines[] = 'Yêu cầu: ' . $data['special_requests'];
        }

        return $lines ? implode("\n", $lines) : null;
    }
}
