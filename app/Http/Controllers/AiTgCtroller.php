<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AiTgCtroller extends Controller
{
    public function handleRequest(Request $request)
    {
        $message = $request->input('message');
        $image = $request->file('image'); 

        if ($image) {
            return $this->searchByImage($image);
        }

        $keywords = $this->parseMessage($message);

        
        $productsQuery = Product::with(['variants.attributeValues.attribute'])
            ->where('is_active', 1);

        $variantsQuery = ProductVariant::with(['product', 'attributeValues.attribute']);

        
        if (!empty($keywords['category'])) {
            $category = Category::where('name', 'like', '%' . $keywords['category'] . '%')
                ->first(); // Xóa orWhere slug
        
            if ($category) {
                $productsQuery->whereHas('categories', function ($q) use ($category) {
                    $q->where('categories.id', $category->id);
                });
                $variantsQuery->whereHas('product.categories', function ($q) use ($category) {
                    $q->where('categories.id', $category->id);
                });
            }
        }

        
        if (!empty($keywords['category_type'])) {
            $productsQuery->whereHas('categoryTypes', function ($q) use ($keywords) {
                $q->where('category_types.name', 'like', '%' . $keywords['category_type'] . '%');
            });
            $variantsQuery->whereHas('product.categoryTypes', function ($q) use ($keywords) {
                $q->where('category_types.name', 'like', '%' . $keywords['category_type'] . '%');
            });
        }

        
        if (!empty($keywords['brand'])) {
            $productsQuery->whereHas('brand', function ($q) use ($keywords) {
                $q->where('name', 'like', '%' . $keywords['brand'] . '%');
            });
            $variantsQuery->whereHas('product.brand', function ($q) use ($keywords) {
                $q->where('name', 'like', '%' . $keywords['brand'] . '%');
            });
        }


        if (!empty($keywords['price'])) {
            $productsQuery->where(function ($q) use ($keywords) {
                $q->where('sell_price', '<=', $keywords['price'])
                    ->orWhere(function ($q2) use ($keywords) {
                        $q2->where('sale_price', '<=', $keywords['price'])
                            ->where('sale_price_end_at', '>', now());
                    });
            });
            $variantsQuery->where(function ($q) use ($keywords) {
                $q->where('price', '<=', $keywords['price'])
                    ->orWhere(function ($q2) use ($keywords) {
                        $q2->where('sale_price', '<=', $keywords['price'])
                            ->where('sale_price_end_at', '>', now());
                    });
            });
        }

        // Lọc theo tên slug
        if (!empty($keywords['name'])) {
            $productsQuery->where('name', 'like', '%' . $keywords['name'] . '%');
            $variantsQuery->whereHas('product', function ($q) use ($keywords) {
                $q->where('name', 'like', '%' . $keywords['name'] . '%');
            });
        }

        $products = $productsQuery->get();
        $variants = $variantsQuery->get();

        // Tạo phản hồi
        $reply = $this->buildResponse($products, $variants);
        return response()->json(['reply' => $reply]);
    }

    private function parseMessage($message)
    {
        $keywords = [
            'category' => '',
            'category_type' => '',
            'brand' => '',
            'name' => '',
            'price' => '',
        ];

        $message = mb_strtolower($message);

        if (preg_match('/(thuốc|danh mục) ([\p{L}\s]+)/u', $message, $match)) {
            $keywords['category'] = trim($match[2]);
        }
        if (preg_match('/loại ([\p{L}\s]+)/u', $message, $match)) {
            $keywords['category_type'] = trim($match[1]);
        }
        if (preg_match('/thương hiệu ([\p{L}\s]+)/u', $message, $match)) {
            $keywords['brand'] = trim($match[1]);
        }
        if (preg_match('/(tôi muốn|tìm) ([\p{L}\s]+)/u', $message, $match)) {
            $keywords['name'] = trim($match[2]);
        }
        if (preg_match('/giá dưới (\d+)/', $message, $match)) {
            $keywords['price'] = (int)$match[1] * 1000;
        }

        return $keywords;
    }

    private function buildResponse($products, $variants)
    {
        if ($products->isEmpty() && $variants->isEmpty()) {
            return "Không tìm thấy sản phẩm hoặc biến thể phù hợp.";
        }

        $reply = "Dưới đây là các sản phẩm và biến thể phù hợp:<br>";

        foreach ($products as $product) {
            $price = $product->sale_price && $product->sale_price_end_at > now()
                ? $product->sale_price
                : $product->sell_price;
            $reply .= "- {$product->name}: " ."<br>";
        }

        foreach ($variants as $variant) {
            $price = $variant->sale_price && $variant->sale_price_end_at > now()
                ? $variant->sale_price
                : $variant->price;
            $attributes = $variant->attributeValues->pluck('value', 'attribute.name')->all();
            $attrText = implode(', ', array_map(fn($k, $v) => "$k: $v", array_keys($attributes), $attributes));
            $reply .= "- {$variant->product->name} (Biến thể - $attrText): " . number_format($price, 0, ',', '.') . " VND<br>";
        }

        return $reply;
    }

    private function searchByImage($image)
    {
        // Lấy tên file gốc từ ảnh tải lên
        $thumbnail = $image->getClientOriginalName(); // Ví dụ: "prospan-red-l.jpg"
    
        // Tìm sản phẩm dựa trên tên file
        $products = Product::where('thumbnail', 'like', '%' . $thumbnail . '%')
            ->orWhereHas('variants', function ($q) use ($thumbnail) {
                $q->where('thumbnail', 'like', '%' . $thumbnail . '%');
            })
            ->where('is_active', 1)
            ->get();
    
        // Tạo phản hồi
        $reply = $products->isEmpty()
            ? "Không tìm thấy sản phẩm nào giống với ảnh bạn cung cấp."
            : "Dưới đây là các sản phẩm tương tự:<br>" .
              $products->map(fn($p) => "- {$p->name}: ")->implode('<br>');
    
        return response()->json(['reply' => $reply]);
    }
}
