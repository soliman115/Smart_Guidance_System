<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInfoFormRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|unique:users,email,' . $this->user()->id,
            'password' => 'nullable|string',
            'image' => 'filled',
            'phone' => 'nullable|unique:users,phone|string|max:12',
        ];
    }
}
