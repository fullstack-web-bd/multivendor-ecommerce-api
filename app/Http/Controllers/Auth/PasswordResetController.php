<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetCodeRequest;
use App\Services\AuthService;
use App\Traits\Responsable;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    use Responsable;

    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    /**
     * @OA\Post(
     *    path="/api/v1/password/code",
     *    tags={"Auth"},
     *    summary="Send User Reset Code",
     *    description="Send User Reset Code",
     *    @OA\RequestBody(
     *     required=true,
     *     description="Send Reset Code to User",
     *     @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *         @OA\Property(property="email", type="string", example="test@example.com"),
     *      )
     *     ),
     *    ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User reset code sent successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function sendResetCode(ResetCodeRequest $request)
    {
        try {
            $this->authService->sendResetCode($request->only('email'));
            return $this->successResponse('Reset code sent successfully. Please check your email.', null);
        } catch (\Throwable $th) {
            return $this->errorResponse('Reset code could not be sent.', $th);
        }
    }

    /**
     * @OA\Post(
     *    path="/api/v1/password/reset",
     *    tags={"Auth"},
     *    summary="Reset User Password",
     *    description="Reset User Password",
     *    @OA\RequestBody(
     *     required=true,
     *     description="Send Reset Code to User",
     *     @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *         @OA\Property(property="email", type="string", example="test@example.com"),
     *         @OA\Property(property="reset_code", type="string", example="123456"),
     *         @OA\Property(property="password", type="string", example="12345678"),
     *      )
     *     ),
     *    ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User password reset successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function resetPassword(Request $request)
    {
        try {
            $data = $this->authService->resetPassword($request->all());
            return $this->successResponse('Your password has been reset successfully.', $data);
        } catch (\Throwable $th) {
            return $this->errorResponse('Your password could not be reset.', $th);
        }
    }
}
