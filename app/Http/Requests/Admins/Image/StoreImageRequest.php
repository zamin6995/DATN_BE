<?php

namespace App\Http\Requests\Admins\Image;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
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
            'product_id' => ['nullable', 'numeric'],
            'color_id' => ['required', 'numeric'],
            "thumb" => ['required', 'file', "mimes:jpeg,png,jpg,gif,webp", 'max:2100000'],
            "list_thumb" => ['required', 'array', 'min:1'],
            "list_thumb.*" => ['required', 'file', "mimes:jpeg,png,jpg,gif,webp", 'max:2100000'],
        ];
    }

    public function messages()
    {
        return [
            "string" => ":attribute phải là một chuỗi kí tự.",
            "numeric" => ":attribute phải là một số.",
            "required" => ":attribute không được bỏ trống.",
            "max" => [
                "numeric" => ":attribute không được lớn hơn :max.",
                "file" => ":attribute không được nhiều hơn :max KB.",
                "string" => ":attribute không được nhiều hơn :max kí tự.",
                "array" => ":attribute không được nhiều hơn :max mục.",
            ],
            "min" => [
                "numeric" => ":attribute không được bé hơn :min.",
                "file" => ":attribute không được ít hơn :min KB.",
                "string" => ":attribute không được ít hơn :min kí tự.",
                "array" => ":attribute phải có ít nhất :min mục.",
            ],

        ];
    }

    public function attributes()
    {
        return [
            'level' => "Cấp độ",
            'description' => "Mô tả",
            'color_id' => "Màu sắc sản phẩm",
            'thumb' => "Hình ảnh",
            'list_thumb' => "Danh sách hình ảnh",
        ];
    }
}
