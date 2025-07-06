<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCarRequest extends FormRequest
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
            'model' => 'required|string|max:255',
            'year' => 'required',
            'body_style' => 'nullable|string|max:255',
            'trim_level' => 'nullable',
            'color' => 'nullable',
            'car_number'=> 'required|unique:cars',
            'chassis' => 'nullable',
            'odometer' => 'required|string|max:255',
            'engine_capacity' => 'nullable|string',
            'fuel_type' => 'nullable',
            'tank_size' => 'nullable',
            'car_cost' => 'nullable',
            'purchase_date' => 'nullable',
            'condition' => 'nullable',
            'dvla_code' => 'nullable',
            'dvla_expiry' => 'nullable',
            'road_worthy_start_date' => 'required|date',
            'road_worthy_expiry_date' => 'required|date',
            'status' => 'nullable',
            'comment' => 'nullable',
            'insurance_start_date' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'user_id' => 'nullable|numeric',
            'car_group'=>'required'
        ];
    }
}
