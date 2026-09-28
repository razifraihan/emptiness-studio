<?php

use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Livewire\Home;
use App\Livewire\OrderShow;
use App\Livewire\ProductDetail;
use App\Livewire\Shop;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/shop', Shop::class)->name('shop');
Route::get('/shop/{product:slug}', ProductDetail::class)->name('product');
Route::get('/cart', Cart::class)->name('cart');
Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/order/{order:code}', OrderShow::class)
    ->middleware('signed')
    ->name('order.show');