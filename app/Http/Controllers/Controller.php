<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendSuccessJsonResponse($msg)
    {
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => $msg
        ]);
    }

    public function sendFailureJsonResponse($msg)
    {
        return response()->json([
            'code' => 400,
            'status' => 'error',
            'message' => $msg
        ]);
    }
}
