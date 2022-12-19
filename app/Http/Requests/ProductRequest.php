<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'price' => 'numeric|min:1',
            'category_id' => 'required|exists:App\Models\Category,id',
            'image' => 'mimes:jpeg,jpg,png,gif|max:10000',
            'status' => 'required',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'name.required' => 'Tên danh mục không được bỏ trống',
            'price.numeric' => 'Giá tiền không phải là số nguyên',
            'price.min' => 'Giá tiền không được nhỏ hơn 1',
            'category_id.required' => 'Danh mục không được bỏ trống',
            'category_id.exists' => 'Không tìm thấy danh mục',
            'image.mimes' => 'Vui lòng chọn ảnh có định dạng jpg, jpeg, png, gif',
            'image.max' => 'Vui lòng chọn ảnh có kích thước < 10 Mb',
            'status.required' => 'Cần cập nhật trạng thái',
        ];
    }
}
