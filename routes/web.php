<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\RazorpayController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Shop Pages
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/category/{slug?}', [CategoryController::class, 'show'])->name('category');

// Cart & Checkout
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Checkout (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place-order');
    Route::get('/order-confirmation/{orderId}', [CheckoutController::class, 'orderConfirmation'])->name('checkout.confirmation');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    
    // Razorpay Payment Routes
    Route::post('/razorpay/create-order', [RazorpayController::class, 'createOrder'])->name('razorpay.create-order');
    Route::post('/razorpay/verify-payment', [RazorpayController::class, 'verifyPayment'])->name('razorpay.verify-payment');
    
    // Account
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/account/settings', [AccountController::class, 'settings'])->name('account.settings');
    
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove.post');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');
});

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact/send', [PageController::class, 'sendContact'])->name('contact.send');
Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/shipping', [PageController::class, 'shipping'])->name('shipping');

// SEO Routes
Route::get('/sitemap.xml', function() {
    $products = App\Models\Product::all();
    return response()->view('sitemap', compact('products'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

// Test auth route
Route::get('/test-auth', function() {
    if (Auth::check()) {
        return 'Logged in as: ' . Auth::user()->email . ' (Admin: ' . (Auth::user()->is_admin ? 'Yes' : 'No') . ')';
    }
    return 'Not logged in';
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Admin Auth Routes (guest)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login.form');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login');
    });
    
    // Admin Protected Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Products Management
        Route::resource('products', AdminProductController::class)->names([
            'index' => 'admin.products.index',
            'create' => 'admin.products.create',
            'store' => 'admin.products.store',
            'show' => 'admin.products.show',
            'edit' => 'admin.products.edit',
            'update' => 'admin.products.update',
            'destroy' => 'admin.products.destroy',
        ]);
        Route::post('products/{product}/variants/{variant}/toggle-stock', [AdminProductController::class, 'toggleVariantStock'])
            ->name('admin.products.variants.toggle-stock');
        
        // Orders Management
        Route::resource('orders', AdminOrderController::class)->names([
            'index' => 'admin.orders.index',
            'show' => 'admin.orders.show',
            'edit' => 'admin.orders.edit',
            'update' => 'admin.orders.update',
        ])->except(['create', 'store', 'destroy']);
        
        // Shipment routes
        Route::post('/orders/{order}/shipment', [AdminOrderController::class, 'createShipment'])->name('admin.orders.shipment.create');
        Route::delete('/orders/{order}/shipment', [AdminOrderController::class, 'cancelShipment'])->name('admin.orders.shipment.cancel');
        Route::get('/orders/{order}/shipment/track', [AdminOrderController::class, 'trackShipment'])->name('admin.orders.shipment.track');
        Route::get('/orders/{order}/shipment/packing-slip', [AdminOrderController::class, 'generatePackingSlip'])->name('admin.orders.shipment.packing-slip');
        
        // Users Management
        Route::resource('users', AdminUserController::class)->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'show' => 'admin.users.show',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]);
        Route::patch('users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])
            ->name('admin.users.toggle-active');
    });
});