<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="Wellezy prueba tecnica", version="0.1")
 * @OA\SecurityScheme(
 *     securityScheme="Bearer",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Ingresa tu token Bearer en el formato `Bearer {token}`"
 * )
 *
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="email", type="string", format="email"),
 *         @OA\Property(property="password", type="string")
 *     }
 * )
 * @OA\Schema(
 *     schema="LoginRequest",
 *     type="object",
 *     required={"email", "password"},
 *     properties={
 *         @OA\Property(property="email", type="string", format="email", example="camilo@example.com"),
 *         @OA\Property(property="password", type="string", format="password", example="123456")
 *     }
 * )
 */

class LoginController extends Controller
{
    use ApiResponser;

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Autenticación"},
     *     summary="Iniciar sesión",
     *     description="Autentica a un usuario con su correo electrónico y contraseña, devolviendo un token JWT.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", example="3|VLypOerLIE2HlwDQYIoM8B1Osy7xAtzKHxCpSbWZk0HfLsXDb8fDRrGHocKvM1oQp6j")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error en la autenticación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No se pudo iniciar sesión"),
     *             @OA\Property(property="error", type="string", example="Contraseña o correo invalido")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Algo salio mal",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Algo salió mal"),
     *             @OA\Property(property="error", type="string", example="error details")
     *         )
     *     )
     * )
    */

    public function login(LoginRequest $request)
    {
        try {
             // Busca el usuario en la base de datos a través del correo electrónico
            $user = User::where('email', '=', $request->email)->firstOrFail();

            // Comprueba si la contraseña proporcionada coincide con la contraseña almacenada en la base de datos
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('user_token')->plainTextToken;

                // Crea un nuevo token para el usuario y lo devuelve como respuesta en formato JSON
                return $this->successResponse(
                    [
                        'user' => $user,
                        'token' => $token
                    ],
                    'Usuario logeado exitosamente.',
                    200
                );
            }

            // Si las credenciales no son válidas, devuelve un mensaje de error en formato JSON
            return $this->errorResponse(
                [
                    'message' => 'No se pudo iniciar sesión',
                    'error' => 'Contraseña o correo invalido'
                ],
                401
            );

        } catch (Exception $e) {
            // Si ocurre una excepción, devuelve un mensaje de error en formato JSON
            return $this->errorResponse(
                [
                    'message' => 'Algo salió mal',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
