<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use App\Traits\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    use Responsable;

    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    /**
     * @OA\Post(
     *    path="/api/v1/logout",
     *    tags={"Auth"},
     *    summary="Logout User",
     *    description="Logout User",
     *    security={{"bearer":{}}},
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User logged out successfully."),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        try {
            $data = $this->authService->logout();
            return $this->successResponse('User logged out successfully.', $data);
        } catch (\Throwable $th) {
            return $this->errorResponse('User could not be logged out.', $th);
        }
    }
}
