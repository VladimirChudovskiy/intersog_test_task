<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Book API",
 *     version="1.0.0",
 *     description="API for managing books and authors",
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="/",
 *     description="API Server"
 * )
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
