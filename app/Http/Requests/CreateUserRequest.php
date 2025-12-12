<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
//            'password' => 'required|string',
//            'mobile' => 'nullable|unique:users|digits_between:10,15',
            'mobile' => 'nullable',
            'role' => 'nullable',
            'department_id' => 'required',
//            'status' => 'required',
            'type' => 'required',
            'license_class' => 'nullable|string',
            'license_number' => 'nullable|string',
            'license_expiry' => 'nullable|date',
            'vendor_id' => 'nullable',
//            'vendor_id' => 'nullable|exists:vendors,id',
        ];
    }
}
