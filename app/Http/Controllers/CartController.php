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
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
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

        // Kiểm tra xem người dùng đã chọn biến thể nào chưa
        $selectedVariantId = $request->product_variant_id ?? $product->variants->first()->id;

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('product_variant_id', $selectedVariantId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity ?? 1;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'product_variant_id' => $selectedVariantId,
                'quantity' => $request->quantity ?? 1,
            ]);
        }


        // Lấy toàn bộ giỏ hàng và load sản phẩm & biến thể
        $carts = Cart::where('user_id', $user->id)
            ->with(['product', 'productVariant'])
            ->get();

        // Tính tổng tiền giỏ hàng
        $subtotal = $carts->sum(function ($cart) {
            $price = (!empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0)
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
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
                    'sale_price' => $cart->productVariant->sale_price,
                    'sell_price' => $cart->productVariant->price,
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
    public function remove(Request $request)
    {
        $cart = Cart::find($request->cart_id);
        if ($cart) {
            $cart->delete();
            return response()->json(['status' => 'success', 'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công!']);
        }
        return response()->json(['status' => 'error', 'message' => 'Sản phẩm không tồn tại trong giỏ hàng!']);
    }
    public function update(Request $request)
    {
        $cart = Cart::find($request->cart_id);

        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng!'
            ]);
        }

        // Update quantity
        $cart->quantity = $request->quantity;
        $cart->save();

        // Calculate new cart total
        $carts = Cart::where('user_id', auth()->id())->get();
        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
            return $cart->quantity * $price;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật giỏ hàng thành công!',
            'subtotal' => number_format($subtotal, 2) . 'đ'
        ]);
    }
}
