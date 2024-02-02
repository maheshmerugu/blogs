<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/** @OA\Info(title="Auricle API", version="1.0")(
* @OA\Swagger(
*     basePath="http://localhost/ocre8/",
*     schemes={"http", "https"},
*     host="http://localhost/ocre8/",
*     @SWG\Info(
*         version="1.0.0",
*         title="L5 Swagger API test today",
*         description="L5 Swagger API description",
*         @SWG\Contact(
*             email="your-email@domain.com"
*         ),
*     ),
* * @SWG\Definition(
* definition="ArrayOfStrings",
* type="array",
* @SWG\Items(
* type="string"
* )
* ),
*   @OA\SecurityScheme(
*      securityScheme="bearerAuth",
*      in="header",
*      name="bearerAuth",
*      type="http",
*      scheme="bearer",
*      bearerFormat="JWT",
* ),
*    @OA\Server(
*      url=L5_SWAGGER_CONST_HOST,
*      description="L5 Swagger OpenApi Server"
*    )
* )
* )
*/

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    function res_success($msg = 'Success!', $data = [])
    {
        return response()->json([
            'status' => 200,
            'message' => $msg,
            'data' => (Object) $data
        ]);
    }

    protected function sendWebResponse($status, $message, $data = [])
    {
        exit(json_encode([
            'status' => $status,
            "message" => $message,
            'data' => $data
        ]));

    }
}