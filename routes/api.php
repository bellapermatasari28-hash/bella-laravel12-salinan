<?php 
 
use App\Http\Controllers\Api\Customer\LoginController; 
use App\Http\Controllers\Api\Customer\LogoutController; 
use App\Http\Controllers\Api\Customer\MyOrderController; 
use App\Http\Controllers\Api\Customer\MyProfileController; 
use App\Http\Controllers\Api\Customer\RatingController; 
use App\Http\Controllers\Api\Customer\RegisterController; 
use App\Http\Controllers\Api\Public\CallbackController; 
use App\Http\Controllers\Api\Public\CartController; 
use App\Http\Controllers\Api\Public\CategoryController; 
use App\Http\Controllers\Api\Public\CheckoutController; 
use App\Http\Controllers\Api\Public\ProductController; 
use App\Http\Controllers\Api\Public\RajaOngkirController; 
use App\Http\Controllers\Api\Public\SliderController; 
use Illuminate\Support\Facades\Route; 
 
 
// ============================== 
// CUSTOMER 
// ============================== 
 
Route::prefix('customer')->group(function () { 
 
    // Authentication 
    Route::post('/register', [RegisterController::class, 'store']); 
    Route::post('/login', [LoginController::class, 'login']); 
 
    Route::post('/logout', [LogoutController::class, 'logout']) 
        ->middleware('auth:api'); 
 
    // Orders 
    Route::get('/my-orders', [MyOrderController::class, 'index']) 
        ->name('customer.my-orders'); 
 
    Route::get('/my-orders/{snap_token}', [MyOrderController::class, 'show']) 
        ->name('customer.my-orders.show'); 
 
    // Profile 
    Route::get('/profile', [MyProfileController::class, 'index']); 
    Route::post('/profile', [MyProfileController::class, 'update']); 
 
    // Rating 
    Route::post('/ratings', [RatingController::class, 'store']) 
        ->middleware('auth:api'); 
}); 
 
 
// ============================== 
// PUBLIC 
// ============================== 
 
Route::prefix('public')->group(function () { 
 
    // Sliders 
    Route::get('/sliders', [SliderController::class, 'index']); 
 
    // Products 
    Route::get('/products', [ProductController::class, 'index']); 
    Route::get('/products-populer', [ProductController::class, 'productPopular']); 
    Route::get('/products/{slug}', [ProductController::class, 'show']); 
 
    // Carts 
    Route::get('/carts', [CartController::class, 'index']); 
    Route::post('/carts', [CartController::class, 'store']); 
    Route::post('/carts/increment', [CartController::class, 'incrementCart']); 
    Route::post('/carts/decrement', [CartController::class, 'decrementCart']); 
    Route::delete('/carts/{id}', [CartController::class, 'destroy']); 
 
    // RajaOngkir 
    Route::get('/search-destination', [RajaOngkirController::class, 'searchDestination']); 
    Route::post('/check-ongkir', [RajaOngkirController::class, 'checkOngkir']); 
 
    // Checkout 
    Route::post('/checkout', [CheckoutController::class, 'store']); 
}); 
 
 
// ============================== 
// CATEGORIES 
// ============================== 
 
Route::get('/categories', [CategoryController::class, 'index']) 
    ->name('public.categories'); 
 
Route::get('/categories/{slug}', [CategoryController::class, 'show']) 
    ->name('public.categories.show'); 


// Route callback Midtrans
Route::post('/callback', [CallbackController::class, 'store'])->name('public.callback');