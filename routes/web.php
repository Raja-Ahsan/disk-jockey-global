<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DJController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DJManagementController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Admin\EventManagementController;

// Public Routes
Route::get('/', function () {
    $djs = \App\Models\DJ::with('user')
        ->available()
        ->verified()
        ->orderBy('rating', 'desc')
        ->limit(3)
        ->get();
    return view('home', compact('djs'));
})->name('home');
Route::get('/about-us', function () {
    return view('about-us');
})->name('about-us');
Route::get('/browse', [DJController::class, 'index'])->name('browse');
Route::get('/how-it-works', function () {
    return view('how-it-works');
})->name('how-it-works');
Route::get('/gallery', function () {
    return view('gallery');
})->name('gallery');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::get('/merch', [\App\Http\Controllers\ProductController::class, 'index'])->name('merch');
Route::get('/products/{id}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');

// Search Routes
Route::post('/search', [SearchController::class, 'search'])->name('search');
Route::post('/home-search', [SearchController::class, 'homeSearch'])->name('home.search');

// DJ Dashboard Route (must come BEFORE /dj/{id} to avoid route conflicts)
Route::middleware('auth')->get('/dj/dashboard', [\App\Http\Controllers\DJ\DJDashboardController::class, 'dashboard'])->name('dj.dashboard');

// DJ Routes (public profile view)
Route::get('/dj/{id}', [DJController::class, 'show'])->name('dj.show');
Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/signup', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/signup', [RegisterController::class, 'register']);
Route::get('/forgot-password', function () {
    return view('forgot-password');
})->name('password.request');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // DJ Dashboard Routes (all routes use DJ middleware from controller)
    Route::prefix('dj/dashboard')->name('dj.dashboard.')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\DJ\DJDashboardController::class, 'profile'])->name('profile');
        Route::get('/edit', [\App\Http\Controllers\DJ\DJDashboardController::class, 'edit'])->name('edit');
        Route::put('/update', [\App\Http\Controllers\DJ\DJDashboardController::class, 'update'])->name('update');
        Route::get('/bookings', [\App\Http\Controllers\DJ\DJDashboardController::class, 'bookings'])->name('bookings');
        Route::get('/bookings/{id}', [\App\Http\Controllers\DJ\DJDashboardController::class, 'showBooking'])->name('bookings.show');
    });

    // DJ Profile Management
    Route::get('/dj/create', [DJController::class, 'create'])->name('dj.create');
    Route::post('/dj', [DJController::class, 'store'])->name('dj.store');
    Route::get('/dj/{id}/edit', [DJController::class, 'edit'])->name('dj.edit');
    Route::put('/dj/{id}', [DJController::class, 'update'])->name('dj.update');
    Route::delete('/dj/{id}', [DJController::class, 'destroy'])->name('dj.destroy');

    // Cart Routes
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{id}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

    // Checkout Routes
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/confirm/{orderId}', [\App\Http\Controllers\CheckoutController::class, 'confirm'])->name('checkout.confirm');

    // Order Routes
    Route::get('/orders/{id}/confirmation', function($id) {
        $order = \App\Models\Order::with('items.product')->findOrFail($id);
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }
        return view('orders.confirmation', compact('order'));
    })->name('orders.confirmation');

    // Booking Routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
    Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{id}/complete', [BookingController::class, 'complete'])->name('bookings.complete');

        // Payment Routes
        Route::post('/bookings/{id}/payment-intent', [PaymentController::class, 'createPaymentIntent'])->name('payment.intent');
        Route::match(['get', 'post'], '/bookings/{id}/confirm-payment', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // DJ Management
    Route::get('/djs', [DJManagementController::class, 'index'])->name('djs.index');
    Route::get('/djs/{id}', [DJManagementController::class, 'show'])->name('djs.show');
    Route::get('/djs/{id}/edit', [DJManagementController::class, 'edit'])->name('djs.edit');
    Route::put('/djs/{id}', [DJManagementController::class, 'update'])->name('djs.update');
    Route::post('/djs/{id}/verify', [DJManagementController::class, 'verify'])->name('djs.verify');
    Route::post('/djs/{id}/unverify', [DJManagementController::class, 'unverify'])->name('djs.unverify');
    Route::delete('/djs/{id}', [DJManagementController::class, 'destroy'])->name('djs.destroy');
    
    // Booking Management
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [BookingManagementController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{id}', [BookingManagementController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{id}', [BookingManagementController::class, 'destroy'])->name('bookings.destroy');
    
    // Event Management
    Route::get('/events', [EventManagementController::class, 'index'])->name('events.index');
    Route::get('/events/{id}', [EventManagementController::class, 'show'])->name('events.show');
    Route::put('/events/{id}', [EventManagementController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [EventManagementController::class, 'destroy'])->name('events.destroy');
    
    // Product Management
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    
    // Product Category Management
    Route::resource('product-categories', \App\Http\Controllers\Admin\ProductCategoryController::class);
    
    // Order Management
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderManagementController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderManagementController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}', [\App\Http\Controllers\Admin\OrderManagementController::class, 'update'])->name('orders.update');
});

// Stripe Webhook
Route::post('/webhook/stripe', [PaymentController::class, 'webhook'])->name('webhook.stripe');
