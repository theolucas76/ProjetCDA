<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        return response()->json([
            'user' => AuthController::me()->toArray(),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

    public function okResponse(Response $response, array $data = array()): Response {
        if ( !empty( $data )) {
            $response->setContent(json_encode($data));
        }
        return $response->setStatusCode(200);
    }

    public function notFoundResponse(Response $response, string $object): Response {
        $response->setContent(json_encode( ['error' => $object . ' not found'] ));
        return $response->setStatusCode(404);
    }

    public function unauthorizedResponse(Response $response, string $reason = ''): Response {
        if ( !empty( $reason ) ) {
            $response->setContent( json_encode( ['error' => $reason] ) );
        }
        return $response->setStatusCode(401);
    }

    public function notAcceptableResponse(Response $response, string $parameter ): Response {
        $response->setContent( json_encode( ['error' => $parameter . ' parameter not acceptable'] ) );
        return $response->setStatusCode(406);
    }

    public function internalServerErrorResponse(Response $response, string $message): Response {
        $response->setContent( json_encode( ['error' => $message] ) );
        return $response->setStatusCode(500);
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
