<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Airport\AirportIataRequest;
use App\Traits\ApiResponser;
use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="AirportIataRequest",
 *     type="object",
 *     required={"email", "password"},
 *     properties={
 *         @OA\Property(property="code", type="string", example="medell")
 *     }
 * )
 */

class AirportController extends Controller
{
    use ApiResponser;

    /**
     * @OA\Post(
     *     path="/api/airports",
     *     tags={"Aeropuertos"},
     *     summary="Obtener códigos IATA",
     *     description="Recibe un código y devuelve los códigos IATA de la ciudad.",
     *     security={{"Bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AirportIataRequest")
     *     ),
     * @OA\Response(
     *         response=200,
     *         description="Códigos IATA devueltos con éxito.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="data", type="object",
     *                     @OA\Property(property="airports", type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="airportId", type="integer", example=271),
     *                             @OA\Property(property="codeIataAirport", type="string", example="AMV"),
     *                             @OA\Property(property="nameAirport", type="string", example="Amderma"),
     *                             @OA\Property(property="codeIso2Country", type="string", example="RU"),
     *                             @OA\Property(property="codeIcaoAirport", type="string", example="ULDD"),
     *                             @OA\Property(property="codeIataCity", type="string", example="AMV"),
     *                             @OA\Property(property="latitudeAirport", type="string", example="69,76667"),
     *                             @OA\Property(property="longitudeAirport", type="string", example="61,55"),
     *                             @OA\Property(property="timezone", type="string", example="Europe/Moscow"),
     *                             @OA\Property(property="GMT", type="integer", example=4),
     *                             @OA\Property(property="isRailRoad", type="integer", example=0),
     *                             @OA\Property(property="isBusStation", type="integer", example=0),
     *                             @OA\Property(property="nameTranslations", type="string",
     *                                 example="Amderma,Amderma,Amderma,..."),
     *                             @OA\Property(property="popularity", type="integer", example=0),
     *                             @OA\Property(property="phone", type="string", example=null),
     *                             @OA\Property(property="website", type="string", example=null),
     *                             @OA\Property(property="geonameId", type="integer", example=7668515),
     *                             @OA\Property(property="routes", type="integer", example=0),
     *                             @OA\Property(property="nameCountry", type="string", example="Russia"),
     *                             @OA\Property(property="FIELD20", type="string", example=null),
     *                             @OA\Property(property="FIELD21", type="string", example=null),
     *                             @OA\Property(property="new_city", type="object",
     *                                 @OA\Property(property="cityId", type="integer", example=262),
     *                                 @OA\Property(property="codeIataCity", type="string", example="AMV"),
     *                                 @OA\Property(property="codeIso2Country", type="string", example="RU"),
     *                                 @OA\Property(property="nameCity", type="string", example="Amderma"),
     *                                 @OA\Property(property="latitudeCity", type="string", example="69,76667"),
     *                                 @OA\Property(property="longitudeCity", type="string", example="61,55"),
     *                                 @OA\Property(property="timezone", type="string", example="Europe/Moscow"),
     *                                 @OA\Property(property="GMT", type="integer", example=4),
     *                                 @OA\Property(property="nameTranslations", type="string",
     *                                     example="Amderma,Amderma,Amderma,..."),
     *                                 @OA\Property(property="popularity", type="integer", example=0),
     *                                 @OA\Property(property="geonameId", type="integer", example=0),
     *                                 @OA\Property(property="FIELD12", type="string", example=null),
     *                                 @OA\Property(property="FIELD13", type="string", example=null)
     *                             ),
     *                             @OA\Property(property="new_country", type="object",
     *                                 @OA\Property(property="countryId", type="integer", example=193),
     *                                 @OA\Property(property="codeIso2Country", type="string", example="RU"),
     *                                 @OA\Property(property="codeIso3Country", type="string", example="RUS"),
     *                                 @OA\Property(property="numericIso", type="integer", example=643),
     *                                 @OA\Property(property="nameCountry", type="string", example="Russia"),
     *                                 @OA\Property(property="nameSpanish", type="string", example="Rusia"),
     *                                 @OA\Property(property="codeCurrency", type="string", example="RUB"),
     *                                 @OA\Property(property="nameCurrency", type="string", example="Ruble"),
     *                                 @OA\Property(property="continent", type="string", example="EU"),
     *                                 @OA\Property(property="languages", type="string", example=null),
     *                                 @OA\Property(property="codeFips", type="string", example="RS"),
     *                                 @OA\Property(property="population", type="integer", example=140702000),
     *                                 @OA\Property(property="nameTranslations", type="string",
     *                                     example="रूस,Rusija,Oroszország,..."),
     *                                 @OA\Property(property="neighbours", type="string", example=null),
     *                                 @OA\Property(property="phonePrefix", type="string", example="7"),
     *                                 @OA\Property(property="capital", type="string", example="Moscow")
     *                             )
     *                         )
     *                     ),
     *                     @OA\Property(property="cities", type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="cityId", type="integer", example=4493),
     *                             @OA\Property(property="codeIataCity", type="string", example="MDE"),
     *                             @OA\Property(property="codeIso2Country", type="string", example="CO"),
     *                             @OA\Property(property="nameCity", type="string", example="Medellin"),
     *                             @OA\Property(property="latitudeCity", type="string", example="6,235925"),
     *                             @OA\Property(property="longitudeCity", type="string", example="-75,575137"),
     *                             @OA\Property(property="timezone", type="string", example="America/Bogota"),
     *                             @OA\Property(property="GMT", type="integer", example=-5),
     *                             @OA\Property(property="nameTranslations", type="string", example="Medell"),
     *                             @OA\Property(property="popularity", type="integer", example=0),
     *                             @OA\Property(property="geonameId", type="integer", example=3674962),
     *                             @OA\Property(property="FIELD12", type="string", example=null),
     *                             @OA\Property(property="FIELD13", type="string", example=null),
     *                             @OA\Property(property="new_airports", type="array",
     *                                 @OA\Items(
     *                                     type="object",
     *                                     @OA\Property(property="airportId", type="integer", example=4723),
     *                                     @OA\Property(property="codeIataAirport", type="string", example="MDE"),
     *                                     @OA\Property(property="nameAirport", type="string", example="Jose Maria Cordova"),
     *                                     @OA\Property(property="codeIso2Country", type="string", example="CO"),
     *                                     @OA\Property(property="codeIcaoAirport", type="string", example="SKRG"),
     *                                     @OA\Property(property="codeIataCity", type="string", example="MDE"),
     *                                     @OA\Property(property="latitudeAirport", type="string", example="6,171382"),
     *                                     @OA\Property(property="longitudeAirport", type="string", example="-75,42821"),
     *                                     @OA\Property(property="timezone", type="string", example="America/Bogota"),
     *                                     @OA\Property(property="GMT", type="integer", example=-5),
     *                                     @OA\Property(property="isRailRoad", type="integer", example=0),
     *                                     @OA\Property(property="isBusStation", type="integer", example=0),
     *                                     @OA\Property(property="nameTranslations", type="string",
     *                                         example="Medellin Jose Marie Cordova,Jose Maria Cordova,..."),
     *                                     @OA\Property(property="popularity", type="integer", example=0),
     *                                     @OA\Property(property="phone", type="string", example=null),
     *                                     @OA\Property(property="website", type="string", example=null),
     *                                     @OA\Property(property="geonameId", type="integer", example=6300755),
     *                                     @OA\Property(property="routes", type="integer", example=42),
     *                                     @OA\Property(property="nameCountry", type="string", example="Colombia"),
     *                                     @OA\Property(property="FIELD20", type="string", example=null),
     *                                     @OA\Property(property="FIELD21", type="string", example=null)
     *                                 )
     *                             ),
     *                             @OA\Property(property="new_country", type="object",
     *                                 @OA\Property(property="countryId", type="integer", example=50),
     *                                 @OA\Property(property="codeIso2Country", type="string", example="CO"),
     *                                 @OA\Property(property="codeIso3Country", type="string", example="COL"),
     *                                 @OA\Property(property="numericIso", type="integer", example=170),
     *                                 @OA\Property(property="nameCountry", type="string", example="Colombia"),
     *                                 @OA\Property(property="nameSpanish", type="string", example="Colombia"),
     *                                 @OA\Property(property="codeCurrency", type="string", example="COP"),
     *                                 @OA\Property(property="nameCurrency", type="string", example="Peso"),
     *                                 @OA\Property(property="continent", type="string", example="SA"),
     *                                 @OA\Property(property="languages", type="string", example=null),
     *                                 @OA\Property(property="codeFips", type="string", example="CO"),
     *                                 @OA\Property(property="population", type="integer", example=44205293),
     *                                 @OA\Property(property="nameTranslations", type="string",
     *                                     example="Κολομβία,Колумбия,Colômbia,..."),
     *                                 @OA\Property(property="neighbours", type="string", example=null),
     *                                 @OA\Property(property="phonePrefix", type="string", example="57"),
     *                                 @OA\Property(property="capital", type="string", example="Bogota")
     *                             )
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Códigos IATA obtenidos exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="StatusCode", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="El código no puede estar vacío."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error en el servidor.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error al comunicarse con el servicio externo."),
     *             @OA\Property(property="error", type="string", example="Detalles del error.")
     *         )
     *     )
     * )
     */
    public function airports(AirportIataRequest $request)
    {
        try {
            $guzzleHttpClient = new GuzzleHttpClient(['base_uri' => 'https://staging.travelflight.aiop.com.co/api/']);

            $response = $guzzleHttpClient->request('POST', 'airports/v2', [
                'form_params' => [
                    'code' => $request->input('code'),
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return $this->successResponse(
                [
                    'data' => $data
                ],
                'Codigo IATA obtenidos exitosamente.',
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => 'Error al comunicarse con el servicio externo.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
