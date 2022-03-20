<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Documentation Swagger pour l'API Heimdall Construction",
 *     version="0.0.1",
 *     @OA\Contact(
 *      email="theolucas76@gmail.com",
 *      name="Théo Lucas"
 *      )
 * )
 * @OA\Server(
 *     url="http://heimdallapiv2/api/v1"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Sign in with login get response with token",
 *     name="JWT",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="apiAuth"
 * )
 */


class Controller extends BaseController
{
    private ?array $params;

    public function __construct()
    {
        $this->params = null;
    }

    /**
     * @OA\Schema(
     *     schema="LoginResponse",
     *     description="Login response with token",
     *     @OA\Property(
     *          property="user",
     *          ref="#/components/schemas/Users"
     *     ),
     *     @OA\Property(
     *          property="token",
     *          default="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9oZWltZGFsbGFwaXYyXC9hcGlcL3YxXC9sb2dpbiIsImlhdCI6MTY0NzgwODc0NywiZXhwIjoxNjQ3ODEyMzQ3LCJuYmYiOjE2NDc4MDg3NDcsImp0aSI6IkNWdGpvemRaejRZMGY3NHciLCJzdWIiOjMsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.9k5lmNBipyVMf_QNrNV218WWdbXIAJGJzX_A4IUj-HY"
     *     ),
     *     @OA\Property(
     *          property="token_type",
     *          default="bearer"
     *     ),
     *     @OA\Property(
     *          property="expires_in",
     *          default=3600
     *     )
     * )
     *
     *
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
            $response->setContent($data);
        }
        return $response->setStatusCode(200);
    }

    /**
     * @OA\Schema(
     *     schema="NotFoundResponse",
     *     description="Error Not Found Object",
     *     @OA\Property(
     *          property="error",
     *          default="object not found"
     *     )
     * )
     *
     * @param Response $response
     * @param string $object
     * @return Response
     */

    public function notFoundResponse(Response $response, string $object): Response {
        $response->setContent( ['error' => $object . ' not found'] );
        return $response->setStatusCode(404);
    }

    /**
     *
     * @OA\Schema(
     *     schema="UnauthorizedResponse",
     *     description="Request Unauthorized",
     *     @OA\Property(
     *          property="error",
     *          default="unauthorized"
     *     )
     * )
     *
     * @param Response $response
     * @param string $reason
     * @return Response
     */

    public function unauthorizedResponse(Response $response, string $reason = ''): Response {
        if ( !empty( $reason ) ) {
            $response->setContent( ['error' => $reason] );
        }
        return $response->setStatusCode(401);
    }

    /**
     *
     * @OA\Schema(
     *     schema="NotAcceptableResponse",
     *     description="Parameter not acceptable",
     *     @OA\Property(
     *          property="error",
     *          default="parameter as valid parameter not acceptable"
     *     )
     * )
     *
     * @param Response $response
     * @param string $parameter
     * @return Response
     */

    public function notAcceptableResponse(Response $response, string $parameter ): Response {
        $response->setContent( ['error' => $parameter . ' parameter not acceptable'] );
        return $response->setStatusCode(406);
    }

    /**
     *
     * @OA\Schema(
     *     schema="InternalServerErrorResponse",
     *     description="Internal Server Error",
     *     @OA\Property(
     *          property="error",
     *          default="Can't add some objects"
     *     )
     * )
     *
     * @param Response $response
     * @param string $message
     * @return Response
     */
    public function internalServerErrorResponse(Response $response, string $message): Response {
        $response->setContent( ['error' => $message] );
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
