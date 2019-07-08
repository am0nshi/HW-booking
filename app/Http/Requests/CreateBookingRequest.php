<?php

namespace App\Http\Requests;

/**
 * Class GetBookingListRequest
 * @package App\Http\Requests
 *
 * @property string $booked_from
 * @property string $booked_to
 */
class CreateBookingRequest extends ApiFormRequest
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
            '*.first_name' => 'required|string',
            '*.last_name' => 'required|string',
            '*.company' => 'nullable|string',
            '*.booked_from' => 'required|date|before:*.booked_to',
            '*.booked_to' => 'required|date',
        ];
    }
}
