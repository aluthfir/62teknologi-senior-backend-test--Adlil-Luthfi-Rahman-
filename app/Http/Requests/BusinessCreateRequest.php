<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessCreateRequest extends FormRequest
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
            'key' => 'required',
            'alias' => 'required',
            'name' => 'required',
            'image_url' => 'required',
            'is_closed' => 'required',
            'url' => 'required',
            'review_count' => 'required',
            'rating' => 'required',
            'transactions' => 'required',
            'price' => 'required',
            'phone' => 'required',
            'display_phone' => 'required',
            'distance' => 'required',
        ];
    }
}
