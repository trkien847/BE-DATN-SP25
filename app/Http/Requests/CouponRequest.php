<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:coupons,code',
            'title' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:phan_tram,co_dinh',
           'discount_value' => [
            'required',
            'numeric',
            'min:0',
            function ($attribute, $value, $fail) {
                if ($this->input('discount_type') == 'phan_tram' && $value >= 70) {
                    $fail('Giá trị giảm giá phần trăm không thể vượt quá 70%.');
                } elseif ($this->input('discount_type') == 'co_dinh' && $value >= 100000) {
                    $fail('Giá trị giảm giá cố định không thể vượt quá 100.000đ.');
                }
            },
        ],
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date_format:Y-m-d\TH:i|before_or_equal:end_date',
            'end_date' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:start_date',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount_value' => 'nullable|numeric|min:0',
            'valid_categories' => 'nullable|array',
            'valid_categories.*' => 'exists:categories,id',
            'valid_products' => 'nullable|array',
            'valid_products.*' => 'exists:products,id',
            'user_id' => 'nullable|array',
            'user_id.*' => 'exists:users,id',
        ];
    }
    public function messages(): array
    {
        return [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'code.max' => 'Mã giảm giá không được vượt quá 50 ký tự.',
            'discount_value.required' => 'Giá trị giảm giá là bắt buộc.',
            'discount_value.numeric' => 'Giá trị giảm giá phải là số.',
            'discount_value.max' => 'Giá trị giảm giá không thể lớn hơn 80.',
            'discount_value.min' => 'Giá trị giảm giá phải lớn hơn hoặc bằng 0.',
            // Thông báo lỗi cho start_date và end_date
            'start_date.date_format' => 'Ngày bắt đầu không đúng định dạng (Y-m-d H:i).',
            'start_date.before_or_equal' => 'Ngày bắt đầu phải trước hoặc bằng ngày kết thúc.',

            'end_date.date_format' => 'Ngày kết thúc không đúng định dạng (Y-m-d H:i).',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'valid_categories.*.exists' => 'Danh mục không hợp lệ.',
            'valid_products.*.exists' => 'Sản phẩm không hợp lệ.',
            'user_id.*.exists' => 'Khách hàng không hợp lệ.',
        ];
    }
}
