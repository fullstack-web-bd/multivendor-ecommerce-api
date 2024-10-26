<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use App\Traits\Responsable;

class RegisterController extends Controller
{
    use Responsable;

    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    /**
     * @OA\Post(
     *    path="/api/v1/register",
     *    tags={"Auth"},
     *    summary="New User Registration",
     *    description="New User Registration",
     *    @OA\RequestBody(
     *     required=true,
     *     description="Create New User with user data",
     *     @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *         @OA\Property(property="name", type="string", example="User Name"),
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
     *             @OA\Property(property="message", type="string", example="User registered successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        try {
            $registeredUser = $this->authService->register($request->validated());
            return $this->successResponse('Your account has been created successfully.', $registeredUser);
        } catch (\Throwable $th) {
            return $this->errorResponse('Your account could not be created.', $th);
        }
    }
}
