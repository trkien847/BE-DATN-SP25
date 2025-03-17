<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check(); // Chỉ cho phép user đã đăng nhập
    }

    public function rules(): array
    {
        return [
            'fullname' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore(Auth::id()), // Không trùng email người khác
            ],
            'phone_number' => 'nullable|string|max:20',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed', // new_password_confirmation phải khớp
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => 'Họ tên không được để trống.',
            'email.unique' => 'Email này đã được sử dụng.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ];
    }
}
