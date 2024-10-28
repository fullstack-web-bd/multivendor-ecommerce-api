<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Traits\Responsable;

class LoginController extends Controller
{
    use Responsable;

    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    /**
     * @OA\Post(
     *    path="/api/v1/login",
     *    tags={"Auth"},
     *    summary="New User Login",
     *    description="New User Login",
     *    @OA\RequestBody(
     *     required=true,
     *     description="Login User with user data",
     *     @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *         @OA\Property(property="email", type="string", example="test@example.com"),
     *         @OA\Property(property="password", type="string", example="12345678"),
     *      )
     *     ),
     *    ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User logged in successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        try {
            $loggedInUser = $this->authService->login($request->only('email', 'password'));
            return $this->successResponse('User logged in successfully.', $loggedInUser);
        } catch (\Throwable $th) {
            return $this->errorResponse('User could not be logged in.', $th);
        }
    }

    /**
     * @OA\Get(
     *    path="/api/v1/me",
     *    tags={"Auth"},
     *    summary="Get current logged in user data.",
     *    description="Get current logged in user data.",
     *    security={{"bearer":{}}},
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User data fetched successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function getLoggedInUser()
    {
        try {
            return $this->successResponse('User data fetched successfully.', auth()->user());
        } catch (\Throwable $th) {
            return $this->errorResponse('User data could not be fetched.', $th);
        }
    }
}
