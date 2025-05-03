<?php

namespace App\Http\Controllers;

use App\Events\OrderCancelRequested;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Models\Notification;
use App\Models\OrderOrderStatus;
use App\Models\OrderStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::where('user_id', auth()->id())
            ->with(['productVariant.product', 'productVariant.attributeValues.attribute'])
            ->get();

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


    public function showCheckout(Request $request)
    {
        if ($request->isMethod('get')) {
            $selectedProducts = json_decode($request->session()->get('selected_products'), true) ?? [];
            $couponCode = $request->session()->get('coupon_code', '');
            $discount = $request->session()->get('discount', 0);
            $grandTotal = $request->session()->get('grand_total', 0);
        } else {
            $selectedProducts = json_decode($request->input('selected_products'), true);
            $couponCode = $request->input('coupon_code');
            $discount = $request->input('discount');
            $grandTotal = $request->input('grand_total');
            $request->session()->put('selected_products', $request->input('selected_products'));
            $request->session()->put('coupon_code', $couponCode);
            $request->session()->put('discount', $discount);
            $request->session()->put('grand_total', $grandTotal);
        }

        $carts = Cart::where('user_id', auth()->id())->get();
        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
            return $cart->quantity * $price;
        });

        $appliedCoupon = null;
        if (!empty($couponCode)) {
            $appliedCoupon = Coupon::where('code', $couponCode)
                ->where('is_active', 1)
                ->where(function ($query) {
                    $query->where('is_expired', 0)
                        ->orWhere(function ($query) {
                            $query->where('is_expired', 1)
                                ->where('start_date', '<=', now())
                                ->where('end_date', '>=', now());
                        });
                })
                ->first();

            $subtotal = $carts->sum(function ($cart) {
                $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                    ? $cart->productVariant->sale_price
                    : $cart->productVariant->price;
                return $cart->quantity * $price;
            });
        }

        $user = auth()->user();
        $userAddress = $user->address;

        return view('client.cart.checkout', [
            'selectedProducts' => $selectedProducts,
            'couponCode' => $couponCode,
            'discount' => $discount,
            'grandTotal' => $grandTotal,
            'carts' => $carts,
            'subtotal' => $subtotal,
            'user' => $user,
            'userAddress' => $userAddress,
            'appliedCoupon' => $appliedCoupon
        ]);
    }

    public function processCheckout(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'payment_method' => 'required|in:cash,vnpay',
            'selected_products' => 'required|json',
            'total_amount' => 'required|numeric',
        ]);

        $code = 'ORDER-' . time() . '-' . auth()->id();
        $user = auth()->user();
        $paymentMethod = $request->input('payment_method');
        $selectedProducts = json_decode($request->input('selected_products'), true);

        if (empty($selectedProducts) || !is_array($selectedProducts)) {
            return redirect()->back()->withErrors(['error' => 'Dữ liệu sản phẩm không hợp lệ.'])->withInput();
        }

        $couponCode = $request->input('coupon_code');
        $discount = $request->input('coupon_discount_value');
        $grandTotal = $request->input('total_amount');
        $coupon_id = $request->input('coupon_id');
        $coupon_description = $request->input('coupon_description');
        $coupon_discount_type = $request->input('coupon_discount_type');

        if ($paymentMethod === 'vnpay') {
            $vnp_TmnCode = "K40TZFB2";
            $vnp_HashSecret = "O1S887RUKCIODDINIWXN3QHF8I1OTVKQ";
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_ReturnUrl = route('checkout.return');

            $vnp_TxnRef = $code;
            $vnp_OrderInfo = "Thanh toán đơn hàng #$code";
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $grandTotal * 100;
            $vnp_Locale = 'vn';
            $vnp_IpAddr = $request->ip();
            $vnp_CreateDate = date('YmdHis');

            $inputData = [
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => $vnp_CreateDate,
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_ReturnUrl,
                "vnp_TxnRef" => $vnp_TxnRef,
            ];

            ksort($inputData);
            $query = http_build_query($inputData);
            $hashdata = $query;
            $vnp_SecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= '?' . $query . "&vnp_SecureHash=" . $vnp_SecureHash;

            session([
                'order_data' => [
                    'code' => $code,
                    'user_id' => $user->id,
                    'phone_number' => $validatedData['phone'],
                    'email' => $validatedData['email'],
                    'fullname' => $validatedData['name'],
                    'address' => $validatedData['address'],
                    'total_amount' => $grandTotal,
                    'payment_method' => $paymentMethod,
                    'selected_products' => $selectedProducts,
                    'coupon_id' => $coupon_id,
                    'coupon_code' => $couponCode,
                    'coupon_description' => $coupon_description,
                    'coupon_discount_type' => $coupon_discount_type,
                    'coupon_discount_value' => $discount,
                ]
            ]);

            return redirect($vnp_Url);
        }

        try {
            $order = new Order();
            $order->code = $code;
            $order->user_id = auth()->id();
            $order->payment_id = 1;
            $order->phone_number = $validatedData['phone'];
            $order->email = $validatedData['email'];
            $order->fullname = $validatedData['name'];
            $order->address = $validatedData['address'];
            $order->total_amount = (float) $grandTotal;
            $order->is_paid = 0;
            if ($couponCode) {
                $order->coupon_id = $coupon_id;
                $order->coupon_code = $couponCode;
                $order->coupon_description = $coupon_description;
                $order->coupon_discount_type = $coupon_discount_type;
                $order->coupon_discount_value = (float) $discount;
            }
            $order->save();

            $orderStatus = new OrderOrderStatus();
            $orderStatus->order_id = $order->id;
            $orderStatus->order_status_id = 1;
            $orderStatus->note = 'Đơn hàng mới được tạo.';
            $orderStatus->save();

            $today = Carbon::today();

            foreach ($selectedProducts as $product) {
                $importProducts = DB::table('import_products')
                    ->where('product_id', $product['id'])
                    ->get();

                if ($importProducts->isEmpty()) {
                    return redirect()->back()->with('success', 'Không tìm thấy sản phẩm');
                }

                $validImportProducts = $importProducts->map(function ($import) use ($today) {
                    $expiryDate = Carbon::parse($import->expiry_date);
                    $monthsDiff = $today->diffInMonths($expiryDate);
                    $import->months_diff = $monthsDiff;
                    return $import;
                })
                    ->filter(function ($import) {
                        return $import->months_diff >= 8;
                    })
                    ->sortBy('months_diff');

                if ($validImportProducts->isEmpty()) {
                    return redirect()->back()->with('error', "Không có sản phẩm với product_id: {$product['id']} có expiry_date >= 8 tháng");
                }

                $variant = ProductVariant::where('id', $product['id_variant'])->first();
                if ($variant) {
                    if ($variant->stock >= $product['quantity']) {
                        $variant->stock -= $product['quantity'];
                        $variant->save();
                    } else {
                        return redirect()->back()->with('error', "Số lượng tồn kho không đủ cho sản phẩm: {$product['name']} (Variant ID: {$product['id_variant']})");
                    }
                } else {
                    return redirect()->back()->with('error', "Không tìm thấy variant với ID: {$product['id_variant']}");
                }

                $remainingQuantity = $product['quantity'];
                $importDetails = [];

                foreach ($validImportProducts as $importProduct) {
                    $importVariants = DB::table('import_product_variants')
                        ->where('import_product_id', $importProduct->id)
                        ->where('product_variant_id', $product['id_variant'])
                        ->get();

                    foreach ($importVariants as $importVariant) {
                        if ($remainingQuantity <= 0) {
                            break 2;
                        }

                        $availableQuantity = $importVariant->quantity;

                        if ($availableQuantity > 0) {
                            $quantityToDeduct = min($remainingQuantity, $availableQuantity);
                            $remainingQuantity -= $quantityToDeduct;

                            DB::table('import_product_variants')
                                ->where('id', $importVariant->id)
                                ->update(['quantity' => $importVariant->quantity - $quantityToDeduct]);

                            $import = DB::table('imports')
                                ->where('id', $importProduct->import_id)
                                ->first();

                            if (!$import) {
                                return redirect()->back()->with('error', "Không tìm thấy thông tin lô nhập với import_id: {$importProduct->import_id}");
                            }

                            $importDetails[] = [
                                'import_product' => $importProduct,
                                'quantity' => $quantityToDeduct,
                                'import_code' => $import->import_code,
                            ];
                        }
                    }
                }

                if ($remainingQuantity > 0) {
                    throw new \Exception("Số lượng tồn kho không đủ trong import_product_variants cho sản phẩm: {$product['name']} (Variant ID: {$product['id_variant']})");
                }

                if (empty($importDetails)) {
                    throw new \Exception("Không tìm thấy variant phù hợp với Variant ID: {$product['id_variant']}");
                }

                foreach ($importDetails as $detail) {
                    $importProduct = $detail['import_product'];
                    $quantity = $detail['quantity'];
                    $importCode = $detail['import_code'];

                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $product['id'];
                    $orderItem->product_variant_id = $product['id_variant'];
                    $orderItem->name = $product['name'];
                    $orderItem->price = $product['price'];
                    $orderItem->quantity = $quantity;
                    $orderItem->name_variant = $product['name_variant'];
                    $orderItem->attributes_variant = $product['variant_value'];
                    $orderItem->price_variant = $product['price'];
                    $orderItem->quantity_variant = $quantity;
                    $orderItem->manufacture_date = $importProduct->manufacture_date;
                    $orderItem->expiry_date = $importProduct->expiry_date;
                    $orderItem->import_code = $importCode;
                    $orderItem->save();
                }
            }

            foreach ($selectedProducts as $product) {
                Cart::where('user_id', auth()->id())
                    ->where('product_id', $product['id'])
                    ->where('product_variant_id', $product['id_variant'])
                    ->delete();
            }

            Mail::to($order->email)->send(new OrderConfirmation($order, $selectedProducts));
            return redirect()->route('thank-you')->with('success', 'Đơn hàng đã được tạo thành công!');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }


    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "O1S887RUKCIODDINIWXN3QHF8I1OTVKQ";
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = $request->except('vnp_SecureHash');

        ksort($inputData);
        $hashData = http_build_query($inputData);
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash && $request->vnp_ResponseCode == '00') {
            $orderData = session('order_data');
            if (!$orderData) {
                return redirect()->route('get-cart')->withErrors(['error' => 'Dữ liệu đơn hàng không hợp lệ']);
            }

            $selectedProducts = $orderData['selected_products'];


            try {
                $order = new Order();
                $order->code = $orderData['code'];
                $order->user_id = $orderData['user_id'];
                $order->payment_id = 2;
                $order->phone_number = $orderData['phone_number'];
                $order->email = $orderData['email'];
                $order->fullname = $orderData['fullname'];
                $order->address = $orderData['address'];
                $order->total_amount = (float) $orderData['total_amount'];
                $order->is_paid = 1;
                if ($orderData['coupon_code']) {
                    $order->coupon_id = $orderData['coupon_id'];
                    $order->coupon_code = $orderData['coupon_code'];
                    $order->coupon_description = $orderData['coupon_description'];
                    $order->coupon_discount_type = $orderData['coupon_discount_type'];
                    $order->coupon_discount_value = (float) $orderData['coupon_discount_value'];
                }
                $order->save();

                $orderStatus = new OrderOrderStatus();
                $orderStatus->order_id = $order->id;
                $orderStatus->order_status_id = 1;
                $orderStatus->note = 'Đơn hàng mới được tạo.';
                $orderStatus->save();

                $today = Carbon::today();

                foreach ($selectedProducts as $product) {
                    $importProducts = DB::table('import_products')
                        ->where('product_id', $product['id'])
                        ->get();

                    if ($importProducts->isEmpty()) {
                        throw new \Exception("Không tìm thấy sản phẩm với product_id: {$product['id']} trong bảng import_products");
                    }

                    $validImportProducts = $importProducts->map(function ($import) use ($today) {
                        $expiryDate = Carbon::parse($import->expiry_date);
                        $monthsDiff = $today->diffInMonths($expiryDate);
                        $import->months_diff = $monthsDiff;
                        return $import;
                    })
                        ->filter(function ($import) {
                            return $import->months_diff >= 8;
                        })
                        ->sortBy('months_diff');

                    if ($validImportProducts->isEmpty()) {
                        throw new \Exception("Không có sản phẩm với product_id: {$product['id']} có expiry_date >= 8 tháng");
                    }

                    $variant = ProductVariant::where('id', $product['id_variant'])->first();
                    if ($variant) {
                        if ($variant->stock >= $product['quantity']) {
                            $variant->stock -= $product['quantity'];
                            $variant->save();
                        } else {
                            throw new \Exception("Số lượng tồn kho không đủ cho sản phẩm: {$product['name']} (Variant ID: {$product['id_variant']})");
                        }
                    } else {
                        throw new \Exception("Không tìm thấy variant với ID: {$product['id_variant']}");
                    }

                    $remainingQuantity = $product['quantity'];
                    $importDetails = [];

                    foreach ($validImportProducts as $importProduct) {
                        $importVariants = DB::table('import_product_variants')
                            ->where('import_product_id', $importProduct->id)
                            ->where('product_variant_id', $product['id_variant'])
                            ->get();

                        foreach ($importVariants as $importVariant) {
                            if ($remainingQuantity <= 0) {
                                break 2;
                            }

                            $availableQuantity = $importVariant->quantity;

                            if ($availableQuantity > 0) {
                                $quantityToDeduct = min($remainingQuantity, $availableQuantity);
                                $remainingQuantity -= $quantityToDeduct;

                                DB::table('import_product_variants')
                                    ->where('id', $importVariant->id)
                                    ->update(['quantity' => $importVariant->quantity - $quantityToDeduct]);

                                $import = DB::table('imports')
                                    ->where('id', $importProduct->import_id)
                                    ->first();

                                if (!$import) {
                                    throw new \Exception("Không tìm thấy thông tin lô nhập với import_id: {$importProduct->import_id}");
                                }

                                $importDetails[] = [
                                    'import_product' => $importProduct,
                                    'quantity' => $quantityToDeduct,
                                    'import_code' => $import->import_code,
                                ];
                            }
                        }
                    }

                    if ($remainingQuantity > 0) {
                        throw new \Exception("Số lượng tồn kho không đủ trong import_product_variants cho sản phẩm: {$product['name']} (Variant ID: {$product['id_variant']})");
                    }

                    if (empty($importDetails)) {
                        throw new \Exception("Không tìm thấy variant phù hợp với Variant ID: {$product['id_variant']}");
                    }

                    foreach ($importDetails as $detail) {
                        $importProduct = $detail['import_product'];
                        $quantity = $detail['quantity'];
                        $importCode = $detail['import_code'];

                        $orderItem = new OrderItem();
                        $orderItem->order_id = $order->id;
                        $orderItem->product_id = $product['id'];
                        $orderItem->product_variant_id = $product['id_variant'];
                        $orderItem->name = $product['name'];
                        $orderItem->price = $product['price'];
                        $orderItem->quantity = $quantity;
                        $orderItem->name_variant = $product['name_variant'];
                        $orderItem->attributes_variant = $product['variant_value'];
                        $orderItem->price_variant = $product['price'];
                        $orderItem->quantity_variant = $quantity;
                        $orderItem->manufacture_date = $importProduct->manufacture_date;
                        $orderItem->expiry_date = $importProduct->expiry_date;
                        $orderItem->import_code = $importCode;
                        $orderItem->save();
                    }
                }

                foreach ($selectedProducts as $product) {
                    Cart::where('user_id', $orderData['user_id'])
                        ->where('product_id', $product['id'])
                        ->where('product_variant_id', $product['id_variant'])
                        ->delete();
                }

                Mail::to($order->email)->send(new OrderConfirmation($order, $selectedProducts));
                session()->forget('order_data');

                return redirect()->route('thank-you')->with('success', 'Thanh toán thành công qua VNPay!');
            } catch (\Exception $e) {
                return redirect()->route('get-cart')->withErrors(['error' => 'Có lỗi khi xử lý thanh toán: ' . $e->getMessage()]);
            }
        } else {
            return redirect()->route('get-cart')->withErrors(['error' => 'Thanh toán VNPay thất bại: ' . $request->vnp_ResponseCode]);
        }
    }



    public function orderHistory(Request $request)
    {
        $carts = Cart::where('user_id', auth()->id())
            ->with(['productVariant.product', 'productVariant.attributeValues.attribute'])
            ->get();

        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
            return $cart->quantity * $price;
        });

        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['latestOrderStatus', 'items.product.importProducts' => function ($query) {
                $query->latest();
            }])
            ->get();

        return view('client.cart.history', compact('orders', 'carts', 'subtotal'));
    }

    public function showRefundForm($orderId)
    {
        $carts = Cart::where('user_id', auth()->id())
            ->with(['productVariant.product', 'productVariant.attributeValues.attribute'])
            ->get();

        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
            return $cart->quantity * $price;
        });
        $order = Order::findOrFail($orderId);

        if (!in_array($order->latestOrderStatus->name ?? '', ['Chờ hoàn tiền'])) {
            return redirect()->back()->with('error', 'Đơn hàng không ở trạng thái chờ hoàn tiền!');
        }

        return view('client.cart.refund-form', compact('order', 'carts', 'subtotal'));
    }

    public function submitRefundInfo(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        if (!in_array($order->latestOrderStatus->name ?? '', ['Chờ hoàn tiền'])) {
            return redirect()->back()->with('error', 'Đơn hàng không ở trạng thái chờ hoàn tiền!');
        }

        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:255',
        ]);

        $order->update([
            'refund_bank_name' => $request->bank_name,
            'refund_account_number' => $request->account_number,
            'refund_account_holder' => $request->account_holder,
        ]);


        $canceledStatus = OrderStatus::where('name', 'Xác nhận thông tin')->first();
        if (!$canceledStatus) {
            return redirect()->back()->with('error', 'Trạng thái "Xác nhận thông tin" không tồn tại!');
        }
        OrderOrderStatus::create([
            'order_id' => $order->id,
            'order_status_id' => $canceledStatus->id,
            'note' => 'Đã xác nhận thông tin tài khoản',
        ]);


        $user = Auth::user();
        $admins = User::where('role_id', 3)->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => "Yêu cầu hoàn tiền từ {$user->fullname}",
                'title' => "Khách hàng {$user->fullname} đã yêu cầu hoàn tiền từ {$order->code}",
                'content' => "Số tiền hoàn: " . number_format($order->total_amount, 0, ',', '.') . " VNĐ - Ngân hàng: {$request->bank_name}",
                'type' => 'refund_request',
                'data' => [
                    'actions' => [
                        'view_details' => route('order.refund.details', $order->id),
                    ],
                ],
                'is_read' => 0,
            ]);
        }

        return redirect()->route('profile')->with('success', 'Thông tin tài khoản đã được lưu thành công!');
    }

    public function refundDetails(Request $request, $orderId)
    {
        $notificationId = $request->input('notification_id');
        $order = Order::with(['items.product', 'user'])->findOrFail($orderId);
        return view('admin.OrderManagement.refund-details', compact('order', 'notificationId'));
    }

    public function uploadRefundProof(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('proof_image')) {
            $image = $request->file('proof_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload'), $imageName);
            $order->refund_proof_image = $imageName;
        }
        $order->save();

        $status = OrderStatus::where('name', 'Chuyển khoản thành công')->first();
        if ($status) {
            OrderOrderStatus::create([
                'order_id' => $order->id,
                'order_status_id' => $status->id,
                'note' => 'Người dùng đã gửi ảnh xác nhận chuyển khoản',
            ]);
        }

        $notificationId = $request->input('notification_id');
        $notification = Notification::find($notificationId);
        $notification->is_read = 1;
        $notification->save();

        return redirect()->back()->with('success', 'Ảnh chuyển khoản đã được tải lên thành công!');
    }

    public function showConfirmForm($orderId)
    {
        $carts = Cart::where('user_id', auth()->id())
            ->with(['productVariant.product', 'productVariant.attributeValues.attribute'])
            ->get();

        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
            return $cart->quantity * $price;
        });
        $order = Order::findOrFail($orderId);

        if (!in_array($order->latestOrderStatus->name ?? '', ['Chuyển khoản thành công'])) {
            return redirect()->back()->with('error', 'Đơn hàng không ở trạng thái "Chuyển khoản thành công"!');
        }

        return view('client.cart.refund-confirm', compact('order', 'carts', 'subtotal'));
    }

    public function submitConfirm(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        if (!in_array($order->latestOrderStatus->name ?? '', ['Chuyển khoản thành công'])) {
            return redirect()->back()->with('error', 'Đơn hàng không ở trạng thái "Chuyển khoản thành công"!');
        }

        OrderOrderStatus::create([
            'order_id' => $order->id,
            'order_status_id' => 7,
            'note' => 'Đã nhận được tiền hoàn',
        ]);

        return redirect()->route('profile')->with('success', 'Xác nhận nhận tiền hoàn thành công!');
    }

    public function cancelOrder(Request $request, $orderId)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->with(['items.product', 'latestOrderStatus'])
            ->findOrFail($orderId);
            
        $currentStatus = $order->latestOrderStatus->name;

        if ($request->isMethod('post')) {
            $request->validate([
                'cancel_reason' => 'required|string|max:255',
            ]);

            if (!in_array($currentStatus, ['Chờ xác nhận', 'Chờ giao hàng'])) {
                return redirect()->back()->with('error', 'Không thể hủy đơn hàng này!');
            }

            $canceledStatus = OrderStatus::where('name', 'Chờ hủy')->first();
            if (!$canceledStatus) {
                return redirect()->back()->with('error', 'Trạng thái Chờ hủy không tồn tại!');
            }

            OrderOrderStatus::create([
                'order_id' => $order->id,
                'order_status_id' => $canceledStatus->id,
                'note' => $request->cancel_reason,
            ]);

            $admins = User::where('role_id', 3)->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => "Khách hàng {$user->fullname} đã yêu cầu hủy đơn hàng {$order->code}",
                    'content' => "Lý do: {$request->cancel_reason}. Tổng giá trị đơn hàng: " . number_format($order->total_amount) . " VNĐ",
                    'type' => 'order_cancel',
                    'data' => [
                        'order_id' => $order->id,
                        'actions' => [
                            'cancel_request' => route('order.rejectCancel', $order->id),
                            'accept_request' => route('order.acceptCancel', $order->id),
                            'view_details' => route('order.details', $order->id),
                        ],
                    ],
                ]);

                event(new OrderCancelRequested($user, $order, $request->cancel_reason));
            }

            return redirect()->route('profile')->with('success', 'Yêu cầu hủy đơn hàng đã được gửi thành công!');
        }

        $carts = Cart::where('user_id', auth()->id())
            ->with(['productVariant.product', 'productVariant.attributeValues.attribute'])
            ->get();

        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
            return $cart->quantity * $price;
        });
        return view('client.cart.cancel', compact('order', 'carts', 'subtotal'));
    }

    // từ chối yêu cầu hủy
    public function rejectCancel(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $currentStatus = $order->latestOrderStatus->name;

        if ($currentStatus !== 'Chờ hủy') {
            return redirect()->back()->with('error', 'Không thể từ chối yêu cầu hủy!');
        }

        $notificationId = $request->input('notification_id');
        $notification = Notification::find($notificationId);
        $notification->is_read = 1;
        $notification->save();

        $pendingStatus = OrderStatus::where('name', 'Chờ giao hàng')->first();
        OrderOrderStatus::create([
            'order_id' => $order->id,
            'order_status_id' => $pendingStatus->id,
            'modified_by' => Auth::id(),
            'note' => 'Yêu cầu hủy đã bị từ chối',
        ]);

        return redirect()->back()->with('success', 'Yêu cầu hủy đã bị từ chối!');
    }

    //chấp nhận yêu cầu hủy 
    public function acceptCancel(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại!');
        }

        $currentStatus = $order->latestOrderStatus->name;
        $cancelableStatuses = ['Chờ hủy', 'Yêu cầu hoàn hàng'];

        if (!in_array($currentStatus, $cancelableStatuses)) {
            return redirect()->back()->with('error', 'Không thể chấp nhận yêu cầu này!');
        }

        $paymentStatusMap = [
            1 => 'Đã hủy',
            2 => 'Chờ hoàn tiền',
        ];

        if (!array_key_exists($order->payment_id, $paymentStatusMap)) {
            return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ!');
        }

        $newStatusName = $paymentStatusMap[$order->payment_id];
        $canceledStatus = OrderStatus::where('name', $newStatusName)->first();
        if (!$canceledStatus) {
            return redirect()->back()->with('error', "Trạng thái '$newStatusName' không tồn tại!");
        }

        $notificationId = $request->input('notification_id');
        $notification = Notification::find($notificationId);
        $notification->is_read = 1;
        $notification->save();

        DB::transaction(function () use ($order, $canceledStatus, $currentStatus) {
            $note = $currentStatus === 'Chờ hủy'
                ? 'Yêu cầu hủy đã được chấp nhận'
                : 'Yêu cầu hoàn hàng đã được chấp nhận';

            OrderOrderStatus::create([
                'order_id' => $order->id,
                'order_status_id' => $canceledStatus->id,
                'note' => $note,
                'modified_by' => Auth::id(),
            ]);

            $productVariantIds = $order->items->pluck('product_variant_id')->all();
            $products = ProductVariant::whereIn('id', $productVariantIds)->get()->keyBy('id');

            foreach ($order->items as $detail) {
                $product = $products[$detail->product_variant_id];
                $product->stock += $detail->quantity;
                $product->save();
                Log::info("Hoàn kho cho sản phẩm {$product->id}, số lượng: {$detail->quantity}");
            }
        });

        $successMessage = $currentStatus === 'Chờ hủy'
            ? 'Yêu cầu hủy đã được chấp nhận và số lượng sản phẩm đã được hoàn lại vào kho!'
            : 'Yêu cầu hoàn hàng đã được chấp nhận và số lượng sản phẩm đã được hoàn lại vào kho!';

        if ($request->expectsJson()) {
            return response()->json(['message' => $successMessage], 200);
        }
        return redirect()->back()->with('success', $successMessage);
    }



    //xem chi tiết đơn hàng
    public function orderDetails(Request $request, $orderId)
    {
        $notificationId = $request->query('notification_id');
        $order = Order::with(['user', 'items.product', 'latestOrderStatus', 'orderStatuses'])
            ->findOrFail($orderId);
        $orderstar = OrderOrderStatus::where('id', $order->orderStatuses->last()->id)->first();
        return view('admin.OrderManagement.details', compact('order', 'notificationId', 'orderstar'));
    }


    public function returnOrder(Request $request, $orderId)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->findOrFail($orderId);
        $currentStatus = $order->latestOrderStatus->name;
        $completedTimestamp = $order->completedStatusTimestamp();
        $daysSinceCompleted = $completedTimestamp ? Carbon::parse($completedTimestamp)->diffInDays(Carbon::now()) : null;

        if ($request->isMethod('post')) {
            $request->validate([
                'return_reason' => 'required|string|max:255',
            ]);

            if ($currentStatus !== 'Hoàn thành' || !$completedTimestamp || $daysSinceCompleted > 7) {
                return redirect()->back()->with('error', 'Không thể hoàn đơn hàng này!');
            }

            $returnStatus = OrderStatus::where('name', 'Yêu cầu hoàn hàng')->first();
            if (!$returnStatus) {
                return redirect()->back()->with('error', 'Trạng thái Yêu cầu hoàn hàng không tồn tại!');
            }

            $imagePaths = [];
            if ($request->hasFile('return_images')) {
                foreach ($request->file('return_images') as $image) {
                    $imageName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('upload'), $imageName);
                    $imagePaths[] = $imageName;
                }
            }

            OrderOrderStatus::create([
                'order_id' => $order->id,
                'order_status_id' => $returnStatus->id,
                'modified_by' => $user->id,
                'note' => $request->return_reason,
                'evidence' => json_encode($imagePaths),
            ]);

            $admins = User::where('role_id', 3)->get();
            if ($admins->isEmpty()) {
            } else {
                foreach ($admins as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'title' => "Người dùng muốn hoàn hàng đơn hàng {$order->code}",
                        'content' => "Tổng đơn hàng: " . number_format($order->total_amount, 0, ',', '.') . " VNĐ. Lý do: {$request->return_reason}",
                        'type' => 'return_request',
                        'data' => [
                            'order_id' => $order->id,
                            'user_id' => $user->id,
                            'user_name' => $user->name,
                            'total_amount' => $order->total_amount,
                            'return_reason' => $request->return_reason,
                            'images' => $imagePaths,
                            'actions' => [
                                'cancel_request' => route('order.rejectCancel', $order->id),
                                'accept_request' => route('order.acceptCancel', $order->id),
                                'view_details' => route('order.details', $order->id),
                            ],
                        ],
                        'is_read' => 0,
                    ]);
                }
            }

            return redirect()->route('profile')->with('success', 'Yêu cầu hoàn hàng đã được gửi thành công!');
        }

        $carts = Cart::where('user_id', auth()->id())
            ->with(['productVariant.product', 'productVariant.attributeValues.attribute'])
            ->get();

        $subtotal = $carts->sum(function ($cart) {
            $price = !empty($cart->productVariant->sale_price) && $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
            return $cart->quantity * $price;
        });

        return view('client.cart.return', compact('order', 'carts', 'subtotal'));
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

        $selectedVariantId = $request->product_variant_id ?? $product->variants->first()->id;
        $variant = ProductVariant::with('attributeValues.attribute')->find($selectedVariantId);

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

        $carts = Cart::where('user_id', $user->id)
            ->with([
                'product',
                'productVariant.attributeValues.attribute'
            ])
            ->get();

        $subtotal = $carts->sum(function ($cart) {
            $price = $cart->productVariant->sale_price > 0
                ? $cart->productVariant->sale_price
                : $cart->productVariant->price;
            return $cart->quantity * $price;
        });

        $cartItems = $carts->map(function ($cart) {
            // Lấy các thuộc tính của biến thể
            $variantAttributes = $cart->productVariant->attributeValues->map(function ($av) {
                return $av->value;
            })->join(' ');

            return [
                'id' => $cart->id,
                'product_id' => $cart->product_id,
                'product_variant_id' => $cart->product_variant_id,
                'quantity' => $cart->quantity,
                'variant_name' => $variantAttributes ?: 'Mặc định', // Hiển thị "Mặc định" nếu không có thuộc tính
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
            'subtotal' => number_format($subtotal, 0, ',', '.') . 'đ',
            'cart_items' => $cartItems
        ]);
    }
    public function remove(Request $request)
    {
        $cart = Cart::find($request->cart_id);

        if ($cart) {
            $cart->delete();
        }

        // Lấy lại danh sách giỏ hàng và tính tổng số lượng sản phẩm
        $carts = Cart::where('user_id', auth()->id())->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công!',
            'cart_count' => $carts->sum('quantity')
        ]);
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

        $cart->quantity = $request->quantity;
        $cart->save();

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
            'subtotal' => number_format($subtotal, 2) . 'đ',
            'cart_count' => $carts->sum('quantity')
        ]);
    }
}
