<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateTimeBasedOrderRequest extends FormRequest
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

            'start_date' => 'required|date_format:Y-m-d|after:'.Carbon::yesterday(),
            'start_time' => 'required|date_format:H:i',
            'finish_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'finish_time' => 'required|date_format:H:i',
        ];
    }
}
