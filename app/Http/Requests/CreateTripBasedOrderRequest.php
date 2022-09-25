<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateTripBasedOrderRequest extends FormRequest
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
            'car_type_id' => 'required|numeric',

            'lat_start' => 'required|numeric', // location latitude
            'long_start' => 'required|numeric', // location longitude
            'name_start' => 'required|string|max:255', // location name
            'notes' => 'nullable|string|max:255',

            'lat_finish' => 'required|array|min:1',
            'lat_finish.*' => 'numeric',
            'long_finish' => 'required|array|min:1',
            'long_finish.*' => 'numeric',
            'name_finish' => 'required|array|min:1',
            'name_finish.*' => 'string|max:255',
            'distance' => 'array|min:1',
            'distance.*' => 'numeric',

            'later' => 'nullable|boolean', // now or later. 1 for later null or 0 for now
            'later_date' => 'required_if:later,1|nullable|date_format:Y-m-d|after:'.Carbon::yesterday(),
            'later_time' => 'required_if:later,1|nullable|date_format:H:i',

            'total_distance' => 'required|numeric'
        ];
    }
}
