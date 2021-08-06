<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Activate2faRequest extends FormRequest
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
            'gotp' => 'bail|required|size:6'
        ];
    }

    public function messages()
    {
        return [
            'gotp' => 'Please Enter Valid OTP',
        ];
    }
}
