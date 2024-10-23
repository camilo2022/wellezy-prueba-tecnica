<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LogoutRequest;
use App\Models\User;
use App\Traits\ApiResponser;
use Exception;

/**
 * @OA\Schema(
 *     schema="LogoutRequest",
 *     type="object",
 *     required={"user_id"},
 *     properties={
 *         @OA\Property(property="user_id", type="integer", example=1)
 *     }
 * )
 */

class LogoutController extends Controller
{
    use ApiResponser;

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Autenticación"},
     *     summary="Cerrar sesión",
     *     description="Revoca todos los tokens de acceso del usuario autenticado, cerrando su sesión.",
     *     security={{"Bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LogoutRequest"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sección cerrada exitosamente.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Sección cerrada exitosamente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al cerrar sesión",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Algo salió mal."),
     *             @OA\Property(property="error", type="string", example="Mensaje de error detallado")
     *         )
     *     )
     * )
    */

    public function logout(LogoutRequest $request)
    {
        try {
            // Obtener el usuario autenticado
            $user = User::findOrFail($request->user_id);

            // Revocar todos los tokens de acceso del usuario
            $user->tokens()->delete();

            // Devolver una respuesta exitosa
            return $this->successResponse(
                '',
                'Seccion cerrada exitosomente.',
                200
            );

        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => 'Algo salió mal.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
