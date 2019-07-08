<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

/**
 * Basic form request for api-like response
 *
 * Class ApiFormRequest
 * @package App\Http\Requests
 */
class ApiFormRequest extends FormRequest
{
    /**
     * Override default non-json requests behavior for responses
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator): void
    {
        $jsonResponse = response()->json(['errors' => $validator->errors()], 405);

        throw new HttpResponseException($jsonResponse);
    }
}