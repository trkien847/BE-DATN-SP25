<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['admin.*'], function ($view) {
            $pendingCount = Comment::where('is_approved', 0)->count();
            $view->with('pendingCount', $pendingCount);
        });
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $carts = Cart::where('user_id', auth()->id())->get();
                $subtotal = $carts->sum(function ($cart) {
                    return $cart->quantity * ($cart->productVariant->sale_price > 0
                        ? $cart->productVariant->sale_price
                        : $cart->productVariant->price);
                });
            } else {
                $carts = collect();
                $subtotal = 0;
            }

            $view->with([
                'carts' => $carts,
                'subtotal' => $subtotal
            ]);
        });
    }
}
