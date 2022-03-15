<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;


class Controller extends BaseController
{

    /**
     * @param $token
     * @return JsonResponse
     */
    public function respondWithToken($token) : JsonResponse
    {


        $myUser = new Users();
        return response()->json([
            'user' => $myUser->toArray(),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

    public function okResponse() {

    }


}
