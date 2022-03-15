<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        //validate incoming request
        $this->validate($request, [
            'login' => 'required|string|unique:users',
            'password' => 'required',
            'role' => 'required'
        ]);

        try
        {
            $user = new Users;
            $user->login = $request->input('login');
            $user->password = app('hash')->make($request->input('password'));
            $user->role = $request->input('role');
            $user->save();

            return response()->json( [
                'entity' => 'users',
                'action' => 'create',
                'result' => 'success'
            ], 201);

        }
        catch (\Exception $e)
        {
            return response()->json( [
                'entity' => 'users',
                'action' => 'create',
                'result' => 'failed'
            ], 409);
        }
    }


    public function login(Request $request): JsonResponse
    {
        //validate incoming request
        $this->validate($request, [
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $credentials = $request->only(['login', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }
}
