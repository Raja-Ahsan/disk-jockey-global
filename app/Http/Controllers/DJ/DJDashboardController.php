<?php

namespace App\Http\Controllers\DJ;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DJ;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DJDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isDJ() || !Auth::user()->dj) {
                abort(403, 'Access denied. You must be a registered DJ.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $dj = Auth::user()->dj;
        
        // Get bookings for this DJ
        $bookings = Booking::where('dj_id', $dj->id);
        
        $stats = [
            'total_bookings' => $bookings->count(),
            'pending_bookings' => (clone $bookings)->where('booking_status', 'pending')->count(),
            'confirmed_bookings' => (clone $bookings)->where('booking_status', 'confirmed')->count(),
            'completed_bookings' => (clone $bookings)->where('booking_status', 'completed')->count(),
            'cancelled_bookings' => (clone $bookings)->where('booking_status', 'cancelled')->count(),
            'total_earnings' => (clone $bookings)->where('payment_status', 'paid')->sum('total_amount'),
            'pending_earnings' => (clone $bookings)->where('payment_status', 'partial')->sum('deposit_amount'),
            'this_month_earnings' => (clone $bookings)->where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
            'last_month_earnings' => (clone $bookings)->where('payment_status', 'paid')
                ->whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->sum('total_amount'),
        ];

        // Monthly earnings for last 6 months
        $monthlyEarnings = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyEarnings[] = [
                'month' => $date->format('M Y'),
                'earnings' => Booking::where('dj_id', $dj->id)
                    ->where('payment_status', 'paid')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('total_amount'),
            ];
        }

        // Booking status distribution
        $bookingStatusData = [
            'pending' => (clone $bookings)->where('booking_status', 'pending')->count(),
            'confirmed' => (clone $bookings)->where('booking_status', 'confirmed')->count(),
            'completed' => (clone $bookings)->where('booking_status', 'completed')->count(),
            'cancelled' => (clone $bookings)->where('booking_status', 'cancelled')->count(),
        ];

        // Recent bookings
        $recentBookings = Booking::with('user', 'event')
            ->where('dj_id', $dj->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Upcoming bookings
        $upcomingBookings = Booking::with('user', 'event')
            ->where('dj_id', $dj->id)
            ->whereIn('booking_status', ['pending', 'confirmed'])
            ->where('booking_date', '>=', now()->toDateString())
            ->orderBy('booking_date', 'asc')
            ->limit(5)
            ->get();

        // Recent orders (for products DJ purchased)
        $recentOrders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dj.dashboard', compact(
            'dj',
            'stats',
            'monthlyEarnings',
            'bookingStatusData',
            'recentBookings',
            'upcomingBookings',
            'recentOrders'
        ));
    }

    public function profile()
    {
        $dj = Auth::user()->dj;
        $dj->load('user', 'categories', 'reviews.user');
        
        return view('dj.dashboard.profile', compact('dj'));
    }

    public function edit()
    {
        $dj = Auth::user()->dj;
        $categories = Category::where('is_active', true)->get();
        
        return view('dj.dashboard.edit', compact('dj', 'categories'));
    }

    public function update(Request $request)
    {
        $dj = Auth::user()->dj;

        $request->validate([
            'stage_name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zipcode' => 'required|string|max:10',
            'hourly_rate' => 'required|numeric|min:0',
            'experience_years' => 'required|integer|min:0',
            'specialties' => 'nullable|string',
            'genres' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'equipment' => 'nullable|string',
            'is_available' => 'nullable',
        ]);

        $data = $request->only([
            'stage_name', 'bio', 'city', 'state', 'zipcode', 
            'hourly_rate', 'experience_years', 'phone', 'website', 'equipment'
        ]);

        if ($request->hasFile('profile_image')) {
            if ($dj->profile_image) {
                Storage::disk('public')->delete($dj->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('dj-profiles', 'public');
        }

        if ($request->filled('genres')) {
            $data['genres'] = array_map('trim', explode(',', $request->genres));
        } else {
            $data['genres'] = [];
        }

        if ($request->filled('specialties')) {
            $data['specialties'] = array_map('trim', explode(',', $request->specialties));
        } else {
            $data['specialties'] = [];
        }

        $data['is_available'] = $request->has('is_available') && $request->is_available == '1';

        $dj->update($data);

        if ($request->has('categories')) {
            $dj->categories()->sync($request->categories);
        }

        return redirect()->route('dj.dashboard.edit')->with('success', 'Profile updated successfully!');
    }

    public function bookings(Request $request)
    {
        $dj = Auth::user()->dj;
        
        $query = Booking::with('user', 'event')
            ->where('dj_id', $dj->id);

        if ($request->filled('status')) {
            $query->where('booking_status', $request->status);
        }

        $bookings = $query->orderBy('booking_date', 'desc')->paginate(15);

        return view('dj.dashboard.bookings', compact('bookings'));
    }

    public function showBooking($id)
    {
        $dj = Auth::user()->dj;
        $booking = Booking::with('user', 'dj.user', 'event')
            ->where('dj_id', $dj->id)
            ->findOrFail($id);

        return view('dj.dashboard.booking-show', compact('booking'));
    }
}
