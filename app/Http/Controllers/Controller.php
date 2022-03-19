<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;


class Controller extends BaseController
{


    private ?array $params;

    public function __construct()
    {
        $this->params = null;
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    public function respondWithToken($token): JsonResponse
    {


        $myUser = new Users();
        return response()->json([
            'user' => $myUser->toArray(),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

    public function okResponse()
    {

    }


    public function getParam(Request $request, string $parameter): ?string
    {
        if (empty($this->params)) {
            $this->params = $request->all();
            $myInput = file_get_contents("php://input");
            if (is_object($myInput)) {
                $this->params = array_merge($this->params, get_object_vars($myInput));
            } else if (is_array($request->all())) {
                $this->params = array_merge($this->params, $request->all());
            }

            if ($myInput !== false && ($myJson = json_decode($myInput, true)) !== null && (is_array($myJson) || is_object($myJson))) {
                $this->params = array_merge($this->params, $myJson);
            }
        }
        return $this->params[$parameter] ?? null;
    }

}
