<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     type="object",
 *     required={"name", "email", "password", "password_confirmation"},
 *     properties={
 *         @OA\Property(property="name", type="string", example="Camilo Acacio"),
 *         @OA\Property(property="email", type="string", format="email", example="camilo@example.com"),
 *         @OA\Property(property="password", type="string", format="password", example="123456"),
 *         @OA\Property(property="password_confirmation", type="string", format="password", example="123456")
 *     }
 * )
*/

class RegisterController extends Controller
{
    use ApiResponser;

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Autenticación"},
     *     summary="Registrar un nuevo usuario",
     *     description="Crea un nuevo usuario en el sistema y devuelve un token de acceso.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest"),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado y autenticado exitosamente.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", example="3|VLypOerLIE2HlwDQYIoM8B1Osy7xAtzKHxCpSbWZk0HfLsXDb8fDRrGHocKvM1oQp6j"),
     *             @OA\Property(property="token_type", type="string", example="Bearer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al registrar al usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error al registrar al usuario."),
     *             @OA\Property(property="error", type="string", example="Mensaje de error detallado")
     *         )
     *     )
     * )
     */

    public function register(RegisterRequest $request)
    {
        try {
            //Create user
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            // Crear un token de acceso para el usuario utilizando Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

             // Devolver una respuesta con el token de acceso y el usuario creado
            return $this->successResponse(
                [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer'
                ],
                'Usuario registrado y autenticado exitosamente.',
                201
            );

        } catch (Exception $e) {
            // Devolver una respuesta de error en caso de excepción
            return $this->errorResponse(
                [
                    'message' => 'Error al registrar al usuario.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
