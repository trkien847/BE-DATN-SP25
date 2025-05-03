<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
    public function rules()
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'agree_terms' => 'required',
            'agree_medical' => 'required',
            'agree_age' => 'required',
            'agree_info' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'firstname.required' => 'Họ không được để trống.',
            'lastname.required' => 'Tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'agree_terms.required' => 'Vui lòng đồng ý với điều khoản sử dụng',
            'agree_medical.required' => 'Vui lòng xác nhận hiểu rõ quy định về mua thuốc',
            'agree_age.required' => 'Vui lòng xác nhận đủ tuổi',
            'agree_info.required' => 'Vui lòng đồng ý cung cấp thông tin',
        ];
    }
}
