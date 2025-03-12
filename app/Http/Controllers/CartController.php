<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
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

        $coupons = Coupon::with('restriction')
            ->where('is_active', 1)
            ->where(function ($query) {
                $query->where('is_expired', 0)
                    ->orWhere('end_date', '>=', now());
            })
            ->get();

        $appliedCoupon = session('applied_coupon');
        if ($appliedCoupon) {
            $selectedCartIds = $carts->pluck('id')->toArray();
            if (empty($selectedCartIds) || !in_array($appliedCoupon['cart_id'], $selectedCartIds)) {
                session()->forget('applied_coupon');
                $appliedCoupon = null;
            }
        }

        return view('client.cart.index', compact('carts', 'subtotal', 'coupons', 'appliedCoupon'));
    }

    public function applyCoupon(Request $request)
    {
        session()->forget('applied_coupon');

        $couponCode = $request->input('cart-coupon');
        $coupon = Coupon::with('restriction')
            ->where('code', $couponCode)
            ->where('is_active', 1)
            ->where(function ($query) {
                $query->where('is_expired', 0)
                    ->orWhere('end_date', '>=', now());
            })
            ->first();

        if (!$coupon) {
            return response()->json(['status' => 'error', 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.']);
        }

        if ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) {
            return response()->json(['status' => 'error', 'message' => 'Mã giảm giá đã hết lượt sử dụng.']);
        }

        $selectedCartIds = $request->input('selected_cart_ids', []);
        $carts = Cart::whereIn('id', $selectedCartIds)->where('user_id', auth()->id())->get();

        if ($carts->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng chọn ít nhất một sản phẩm để áp dụng mã giảm giá.']);
        }

        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
            return $cart->quantity * $price;
        });

        $restriction = $coupon->restriction;
        $validProducts = $restriction && $restriction->valid_products ? json_decode($restriction->valid_products) : [];

        // Sửa lỗi cú pháp: Gán kết quả firstWhere vào biến
        $applicableCart = $carts->firstWhere(function ($cart) use ($validProducts) {
            return in_array($cart->product->id, $validProducts);
        });

        if (!$applicableCart) {
            return response()->json(['status' => 'error', 'message' => 'Mã giảm giá không áp dụng cho sản phẩm đã chọn.']);
        }

        if ($restriction && $restriction->min_order_value && $subtotal < $restriction->min_order_value) {
            return response()->json(['status' => 'error', 'message' => "Tổng đơn hàng phải đạt tối thiểu " . number_format($restriction->min_order_value) . "đ để áp dụng mã giảm giá."]);
        }

        $price = !empty($applicableCart->productVariant->sale_price) && $applicableCart->productVariant->sale_price > 0
            ? $applicableCart->productVariant->sale_price
            : $applicableCart->productVariant->price;
        $itemSubtotal = $price * $applicableCart->quantity;

        $discount = $coupon->discount_type === 'phan_tram'
            ? ($itemSubtotal * $coupon->discount_value / 100)
            : $coupon->discount_value;

        if ($restriction && $restriction->max_discount_value && $discount > $restriction->max_discount_value) {
            $discount = $restriction->max_discount_value;
        }

        $newSubtotal = $subtotal - $discount;

        session(['applied_coupon' => [
            'code' => $coupon->code,
            'discount' => $discount,
            'cart_id' => $applicableCart->id
        ]]);

        return response()->json([
            'status' => 'success',
            'message' => 'Áp dụng mã giảm giá thành công!',
            'new_subtotal' => number_format($newSubtotal),
            'discount' => number_format($discount)
        ]);
    }

    public function removeCartItem(Request $request)
    {
        $cartId = $request->input('cart_id');
        $cart = Cart::where('id', $cartId)->where('user_id', auth()->id())->first();

        if ($cart) {
            $cart->delete();
            $appliedCoupon = session('applied_coupon');
            if ($appliedCoupon && $appliedCoupon['cart_id'] == $cartId) {
                session()->forget('applied_coupon');
            }
            return response()->json(['status' => 'success', 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng.']);
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
