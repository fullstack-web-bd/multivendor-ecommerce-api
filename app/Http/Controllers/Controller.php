<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="Ecommerce Multi Vendor API",
 *     description="Ecommerce Multi Vendor API Swagger Documentation.",
 *     @OA\Contact(name="DevsEnv")
 * )
 * @OA\PathItem(
 *    path="/api/v1"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}