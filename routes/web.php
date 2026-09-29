<?php

use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Livewire\Home;
use App\Livewire\OrderShow;
use App\Livewire\PageShow;
use App\Livewire\ProductDetail;
use App\Livewire\ProjectIndex;
use App\Livewire\ProjectShow;
use App\Livewire\Shop;
use App\Livewire\Unsubscribe;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/shop', Shop::class)->name('shop');
Route::get('/shop/{product:slug}', ProductDetail::class)->name('product');
Route::get('/project', ProjectIndex::class)->name('project.index');
Route::get('/project/{project:slug}', ProjectShow::class)->name('project.show');
Route::get('/about', PageShow::class)->defaults('slug', 'about')->name('about');
Route::get('/info/{slug}', PageShow::class)->name('page.show');
Route::get('/unsubscribe/{token}', Unsubscribe::class)->name('unsubscribe');
Route::get('/cart', Cart::class)->name('cart');
Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/order/{order:code}', OrderShow::class)
    ->middleware('signed')
    ->name('order.show');

if (app()->isLocal()) {
    Route::get('/dev/mail/confirmation', fn () => new \App\Mail\OrderConfirmation(
        \App\Models\Order::with('items')->latest()->firstOrFail()
    ));

    Route::get('/dev/mail/shipped', fn () => new \App\Mail\OrderShipped(
        \App\Models\Order::with('items')->latest()->firstOrFail()
    ));
}