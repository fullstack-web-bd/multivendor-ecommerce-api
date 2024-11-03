<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRegisterRequest;
use App\Services\AuthService;
use App\Traits\Responsable;

class VendorRegisterController extends Controller
{
    use Responsable;

    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    /**
     * @OA\Post(
     *    path="/api/v1/vendor-register",
     *    tags={"Auth"},
     *    summary="New Vendor Registration",
     *    description="New Vendor Registration",
     *    @OA\RequestBody(
     *     required=true,
     *     description="Create New Vendor with user data",
     *     @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(
     *         @OA\Property(property="name", type="string", example="User Name"),
     *         @OA\Property(property="email", type="string", example="test@example.com"),
     *         @OA\Property(property="password", type="string", example="12345678"),
     *         @OA\Property(property="shop_name", type="string", example="Sample Shop Name"),
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
    public function register(VendorRegisterRequest $request)
    {
        try {
            $request->merge(['is_customer' => false]);
            $registeredUser = $this->authService->register($request->validated());
            return $this->successResponse('Your account has been created successfully.', $registeredUser);
        } catch (\Throwable $th) {
            return $this->errorResponse('Your account could not be created.', $th);
        }
    }
}
