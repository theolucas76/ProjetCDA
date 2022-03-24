<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;


/**
 * @OA\RequestBody(
 *      request="Login",
 *      description="Login body",
 *      @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(ref="#/components/schemas/LoginRequest")
 *      )
 *  )
 */

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

//    public function register(Request $request): JsonResponse
//    {
//        //validate incoming request
//        $this->validate($request, [
//            'login' => 'required|string|unique:users',
//            'password' => 'required|string',
//            'role' => 'required|integer',
//	        'job' => 'required|integer'
//        ]);
//
//        try
//        {
//
//            $user = new Users;
//            $user->login = $request->input('login');
//            $user->password = app('hash')->make($request->input('password'));
//            $user->role = $request->input('role');
//			$user->job = $request->input('job');
//            $user->save();
//
//            return response()->json( [
//                'entity' => 'users',
//                'action' => 'create',
//                'result' => 'success'
//            ], 201);
//
//        }catch (\Exception $e) {
//            return response()->json( [
//                'entity' => 'users',
//                'action' => 'create',
//                'result' => 'failed'
//            ], 409);
//        }
//    }


    /**
     *
     *  @OA\Post(
     *      path="/login",
     *      summary="Login",
     *      tags={"Auth"},
     *      description="Log a user",
     *      @OA\RequestBody(ref="#/components/requestBodies/Login"),
     *
     *      @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/LoginResponse")
     *      ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthaurized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  default="Bad login/password"
     *              )
     *          )
     *     )
     *  )
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ValidationException
     */
    public function login(Request $request, Response $response): Response
    {
        //validate incoming request
        $this->validate($request, [
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $credentials = $request->only(['login', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return $this->unauthorizedResponse($response, "Bad login/password");
        }
        return $this->okResponse($response, [
            'user' => AuthController::me()->toArray(),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }

    /**
     * @return Users|null
     */

    public static function me(): ?Users
    {
        $myId = auth()->user()->getAuthIdentifier();
        return Users::getUserById($myId);
    }
}
