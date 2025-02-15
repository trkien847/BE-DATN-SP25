<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::where('user_id', auth()->id())->get();
        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->product->sale_price) && $cart->product->sale_price > 0 
                ? $cart->product->sale_price 
                : $cart->product->sell_price;
            return $cart->quantity * $price;
        }); 
        return view('client.cart.index', compact('carts', 'subtotal'));
    }

    public function addToCart(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Bạn cần đăng nhập để thêm vào giỏ hàng!']);
        }

        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Sản phẩm không tồn tại!']);
        }

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'product_variant_id' => null,
                'quantity' => 1,
            ]);
        }

        // Lấy toàn bộ giỏ hàng để trả về
        $carts = Cart::where('user_id', $user->id)->with('product')->get();

        // Tính tổng tiền giỏ hàng
        $subtotal = $carts->sum(function ($cart) {
            $price = (!empty($cart->product->sale_price) && $cart->product->sale_price > 0)
                ? $cart->product->sale_price
                : $cart->product->sell_price;
            return $cart->quantity * $price;
        });

        // Chuẩn bị dữ liệu giỏ hàng để gửi về frontend
        $cartItems = $carts->map(function ($cart) {
            return [
                'id' => $cart->id,
                'quantity' => $cart->quantity,
                'product' => [
                    'name' => $cart->product->name,
                    'thumbnail' => asset('upload/' . $cart->product->thumbnail),
                    'sale_price' => $cart->product->sale_price,
                    'sell_price' => $cart->product->sell_price
                ]
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
            'cart_count' => $carts->sum('quantity'),
            'subtotal' => number_format($subtotal, 2) . 'đ',
            'cart_items' => $cartItems
        ]);
    }
}
