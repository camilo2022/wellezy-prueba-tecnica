<?php

namespace App\Http\Requests\Api\Airport;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AirportIataRequest extends FormRequest
{
    /**
     * Maneja una solicitud fallida de validación.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        // Lanzar una excepción de validación con los errores de validación obtenidos
        throw new HttpResponseException(response()->json([
            'message' => 'Error de validación',
            'errors' => $validator->errors()
        ], 422));
    }

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
            'code' => 'required|string'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.required' => 'El campo codigo es obligatorio.',
            'code.string' => 'El campo codigo debe ser una cadena de texto.'
        ];
    }

        /**
     * Obtiene los atributos personalizados de los campos.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'code' => 'codigo'
        ];
    }
}
