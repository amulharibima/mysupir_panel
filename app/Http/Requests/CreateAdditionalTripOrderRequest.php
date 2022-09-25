<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdditionalTripOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'lat_start' => 'required|numeric', // location latitude
            'long_start' => 'required|numeric', // location longitude
            'name_start' => 'required|string|max:255', // location name

            'lat_finish' => 'required|array|min:1',
            'lat_finish.*' => 'numeric',
            'long_finish' => 'required|array|min:1',
            'long_finish.*' => 'numeric',
            'name_finish' => 'required|array|min:1',
            'name_finish.*' => 'string|max:255',
            'distance' => 'array|min:1',
            'distance.*' => 'numeric',

            'total_distance' => 'required|numeric'
        ];
    }
}
