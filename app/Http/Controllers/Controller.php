<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function returnSuccess($data)
    {

        return response()->json([
            'code' => strval(200),
            'status' => true,
            'message' => "Success",
            'data' => $data,
        ], 200);
    }

    public function returnError($massage, $error = null)
    {
        return response()->json([
            'code' => 400,
            'status' => false,
            'message' => $massage,
            'error' => $error,
        ], 400);
    }

    public function returnErrorAuthorization($massage)
    {
        return response()->json([
            'code' => strval(401),
            'status' => false,
            'message' => $massage,
            'data' => [],
        ], 401);
    }
}
