<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Flight\FlightIndexRequest;
use App\Traits\ApiResponser;
use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;

/**
 * @OA\Schema(
 *     schema="FlightIndexRequest",
 *     type="object",
 *     properties={
 *         @OA\Property(property="direct", type="boolean", example=false),
 *         @OA\Property(property="currency", type="string", example="COP"),
 *         @OA\Property(property="searchs", type="integer", example=50),
 *         @OA\Property(property="class", type="boolean", example=false),
 *         @OA\Property(property="qtyPassengers", type="integer", example=1),
 *         @OA\Property(property="adult", type="integer", example=1),
 *         @OA\Property(property="child", type="integer", example=0),
 *         @OA\Property(property="baby", type="integer", example=0),
 *         @OA\Property(property="seat", type="integer", example=0),
 *         @OA\Property(property="itinerary", type="array",
 *             @OA\Items(type="object", properties={
 *                 @OA\Property(property="departureCity", type="string", example="MDE"),
 *                 @OA\Property(property="arrivalCity", type="string", example="YYZ"),
 *                 @OA\Property(property="hour", type="string", format="date-time", example="2024-10-31T05:00:00.000Z")
 *             })
 *         )
 *     }
 * )
 */

class FlightController extends Controller
{
    use ApiResponser;

    /**
     * @OA\Post(
     *     path="/api/flights",
     *     tags={"Vuelos"},
     *     summary="Obtener información de vuelos",
     *     description="Obtener los vuelos.",
     *     security={{"Bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FlightIndexRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vuelos consultados exitosamente.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="Seg1", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="segments", type="array",
     *                             @OA\Items(type="object",
     *                                 @OA\Property(property="productDateTime", type="object",
     *                                     @OA\Property(property="dateOfDeparture", type="string", example="2024-10-31"),
     *                                     @OA\Property(property="timeOfDeparture", type="string", example="12:48"),
     *                                     @OA\Property(property="dateOfArrival", type="string", example="2024-10-31"),
     *                                     @OA\Property(property="timeOfArrival", type="string", example="13:45"),
     *                                     @OA\Property(property="dayDeparture", type="string", example="jueves"),
     *                                     @OA\Property(property="dateFormatDeparture", type="string", example="31 de octubre de 2024"),
     *                                     @OA\Property(property="dayArrival", type="string", example="jueves"),
     *                                     @OA\Property(property="dateFormatArrival", type="string", example="31 de octubre de 2024"),
     *                                     @OA\Property(property="timeDepartureSeconds", type="integer", example=46080),
     *                                     @OA\Property(property="timeArrivalSeconds", type="integer", example=49500)
     *                                 ),
     *                                 @OA\Property(property="location", type="array",
     *                                     @OA\Items(type="object",
     *                                         @OA\Property(property="locationId", type="string"),
     *                                         @OA\Property(property="locationName", type="string")
     *                                     )
     *                                 ),
     *                                 @OA\Property(property="companyId", type="object",
     *                                     @OA\Property(property="marketingCarrier", type="string", example="DL")
     *                                 ),
     *                                 @OA\Property(property="flightOrtrainNumber", type="string", example="6120"),
     *                                 @OA\Property(property="attributeDetail", type="object",
     *                                     @OA\Property(property="attributeType", type="string", example="EFT"),
     *                                     @OA\Property(property="attributeDescription", type="string", example="0057")
     *                                 ),
     *                                 @OA\Property(property="equipment", type="string", example="Airbus A318/A319/A32"),
     *                                 @OA\Property(property="technicalStop", type="array", @OA\Items(type="string"))
     *                             )
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="recommendation", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="itemNumber", type="object",
     *                         @OA\Property(property="itemNumberId", type="object",
     *                             @OA\Property(property="number", type="string", example="1")
     *                         )
     *                     ),
     *                     @OA\Property(property="recPriceInfo", type="object",
     *                         @OA\Property(property="monetaryDetail", type="array",
     *                             @OA\Items(type="object",
     *                                 @OA\Property(property="amount", type="string")
     *                             )
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="bag", type="object",
     *                 @OA\Property(property="serviceTypeInfo", type="object",
     *                     @OA\Property(property="carrierFeeDetails", type="object",
     *                         @OA\Property(property="type", type="string", example="FBA")
     *                     )
     *                 ),
     *                 @OA\Property(property="serviceCoverageInfoGrp", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="itemNumberInfo", type="object",
     *                             @OA\Property(property="itemNumber", type="object",
     *                                 @OA\Property(property="number", type="string", example="1")
     *                             )
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="companies", type="array",
     *                 @OA\Items(type="string", example="DL")
     *             ),
     *             @OA\Property(property="priceMax", type="string", example="1479210"),
     *             @OA\Property(property="priceMin", type="string", example="1479210"),
     *             @OA\Property(property="hour", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="max", type="integer", example=46080),
     *                     @OA\Property(property="min", type="integer", example=46080)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al comunicarse con el servicio externo.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error al comunicarse con el servicio externo."),
     *             @OA\Property(property="error", type="string", example="Error details")
     *         )
     *     )
     * )
     */

    public function flights(FlightIndexRequest $request)
    {
        try {
            //return $request;
            $guzzleHttpClient = new GuzzleHttpClient(['base_uri' => 'https://staging.travelflight.aiop.com.co/api/']);

            $response = $guzzleHttpClient->request('POST', 'flights/v2', [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'direct' => $request->input('direct'),
                    'currency' => $request->input('currency'),
                    'searchs' => $request->input('searchs'),
                    'class' => $request->input('class'),
                    'qtyPassengers' => $request->input('qtyPassengers'),
                    'adult' => $request->input('adult'),
                    'child' => $request->input('child'),
                    'baby' => $request->input('baby'),
                    'seat' => $request->input('seat'),
                    'itinerary' => $request->input('itinerary')
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return $this->successResponse(
                [
                    'data' => $data
                ],
                'Vuelos consuldatos exitosamente.',
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
