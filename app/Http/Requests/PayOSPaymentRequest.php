<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayOSPaymentRequest extends FormRequest
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
            'orderCode' => 'string',
            'amount' => 'numeric',
            'name' => 'string',
            'email' => 'email',
            'phone' => 'string|min:9|max:15',
            'address' => 'string|max:255',
            'items' => 'array|min:1',
            'items.*.name' => 'string|max:255',
            'items.*.quantity' => 'integer|min:1',
            'items.*.price' => 'numeric|min:0',
            'cancelUrl' => 'string|max:255',
            'returnUrl' => 'string|max:255',
            'expiredAt' => 'max:255',
        ];
    }
}
