<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DJ;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_djs' => DJ::count(),
            'verified_djs' => DJ::where('is_verified', true)->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('booking_status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('booking_status', 'confirmed')->count(),
            'completed_bookings' => Booking::where('booking_status', 'completed')->count(),
            'cancelled_bookings' => Booking::where('booking_status', 'cancelled')->count(),
            'total_revenue' => Booking::where('payment_status', 'paid')->sum('total_amount'),
            'pending_revenue' => Booking::where('payment_status', 'partial')->sum('deposit_amount'),
            'this_month_revenue' => Booking::where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
            'last_month_revenue' => Booking::where('payment_status', 'paid')
                ->whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->sum('total_amount'),
        ];

        // Monthly revenue data for last 6 months
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => Booking::where('payment_status', 'paid')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('total_amount'),
            ];
        }

        // Booking status distribution
        $bookingStatusData = [
            'pending' => Booking::where('booking_status', 'pending')->count(),
            'confirmed' => Booking::where('booking_status', 'confirmed')->count(),
            'completed' => Booking::where('booking_status', 'completed')->count(),
            'cancelled' => Booking::where('booking_status', 'cancelled')->count(),
        ];

        // Recent bookings
        $recentBookings = Booking::with('user', 'dj.user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent DJs
        $recentDjs = DJ::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Top performing DJs
        $topDjs = DJ::with('user')
            ->orderBy('total_bookings', 'desc')
            ->orderBy('rating', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'recentBookings', 
            'recentDjs',
            'monthlyRevenue',
            'bookingStatusData',
            'topDjs'
        ));
    }
}
