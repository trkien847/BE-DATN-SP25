<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
        ];
    
        if ($this->has('subcategories')) {
            $rules['subcategories.*'] = 'nullable|string|max:255';
            $rules['subcategory_icons.*'] = 'nullable|max:255';
    
            if ($this->isMethod('post')) {
                $rules['name'] .= '|unique:categories,name';
                $rules['subcategories.*'] .= '|unique:category_types,name';
            }
        }
    
        return $rules;
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được bỏ trống',
            'name.string' => 'Tên phải là chuỗi',
            'name.max' => 'Tên không được vượt quá 255 ký tự',
            'name.unique' => 'Tên danh mục đã tồn tại',
            'subcategories.*.string' => 'Tên danh mục con phải là chuỗi',
            'subcategories.*.max' => 'Tên danh mục con không được vượt quá 255 ký tự',            
<<<<<<< HEAD
            'subcategory_icons.*.max' => 'Icon danh mục con không được vượt quá 255 ký tự',
            'subcategory_icons.*.unique' => 'Icon danh mục con đã tồn tại',
=======
>>>>>>> 82ee9425388d17214dc00abdc6e9da1fe5f04190
            'subcategories.*.unique' => 'Tên danh mục con đã tồn tại',
        ];
    }
}
