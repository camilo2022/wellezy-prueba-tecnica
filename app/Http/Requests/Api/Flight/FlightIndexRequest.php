<?php

namespace App\Http\Requests\Api\Flight;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FlightIndexRequest extends FormRequest
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
            'direct' => 'required|boolean',
            'currency' => 'required|string',
            'searchs' => 'required|numeric',
            'class' => 'required|boolean',
            'qtyPassengers' => 'required|numeric',
            'adult' => 'required|numeric',
            'child' => 'required|numeric',
            'baby' => 'required|numeric',
            'seat' => 'required|numeric',
            'itinerary' => 'required|array',
            'itinerary.*' => 'array',
            'itinerary.*.departureCity' => 'required|string|uppercase',
            'itinerary.*.arrivalCity' => 'required|string|uppercase',
            'itinerary.*.hour' => 'required',
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
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser una cadena de texto.',
            'boolean' =>  'El campo :attribute debe ser true o false.',
            'numeric' => 'El campo :attribute debe ser numerico.',
            'array' => 'El campo :attribute debe ser un arreglo.',
            'uppercase' => 'El campo :attribute debe estar en mayusculas.'
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
            'direct' => 'vuelo directo',
            'currency' => 'divisa',
            'searchs' => 'registros por pagina',
            'class' => 'clase',
            'qtyPassengers' => 'cantidad de pasajeros',
            'adult' => 'adultos',
            'child' => 'niño',
            'baby' => 'bebé',
            'seat' => 'asiento',
            'itinerary' => 'itinerarios',
            'itinerary.*' => 'itinerario',
            'itinerary.*.departureCity' => 'ciudad de salida',
            'itinerary.*.arrivalCity' => 'ciudad de llegada',
            'itinerary.*.hour' => 'hora'
        ];
    }
}
