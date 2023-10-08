<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    /**
     * @OA\Get(
     *    path="/api/v1/test",
     *     tags={"Ping"},
     *     summary="Ping API",
     *     description="Ping API",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Welcome to Ecommerce Multi Vendor API"),
     *             @OA\Property(property="data", type="object", example="null"),
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Welcome to Ecommerce Multi Vendor API',
            'data' => null
        ], 200);
    }
}