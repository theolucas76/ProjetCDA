<?php

namespace App\Http\Controllers;

use App\Models\Enums\Role;
use App\Models\UserData;
use App\Models\Users;
use App\Models\Utils\ParameterHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *      request="Register",
 *      description="Register body",
 *      @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(ref="#/components/schemas/RegisterRequest")
 *      )
 *  )
 *
 * @OA\RequestBody(
 *     request="PutLoginAndPassword",
 *     description="Update Only Login and Password",
 *     @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(ref="#/components/schemas/PutLoginPasswordRequest")
 *     )
 * )
 *
 */
class UsersController extends Controller
{

    /**
     *
     * @OA\Get(
     *     path="/users/{userId}",
     *     summary="Get User By Id",
     *     tags={"Users"},
     *     security={{ "apiAuth": {} }},
     *     description="get user and user's data",
     *     @OA\Parameter(
     *          name="userId",
     *          description="User id",
     *          required=true,
     *          in="path"
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/UsersWithData")
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundResponse")
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized Response",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     *
     *
     * @param Request $request
     * @param Response $response
     * @param string $userId
     * @return Response
     */

    public function getAction(Request $request, Response $response, string $userId): Response
    {
        $myUser = Users::getUserById($userId);
        if ($myUser === null) {
            return $this->notFoundResponse($response, 'user');
        }
        $myUserArray = $myUser->toArray();
        unset($myUserArray['password']);

        $myUserData = UserData::getDataByUser($myUser->getId());
        $myUserArray['data'] = array_map(static function (UserData $data): array {
            return $data->toArray();
        }, $myUserData);

        return $this->okResponse($response, $myUserArray);
    }

    /**
     *
     * @OA\Get(
     *     path="/users",
     *     summary="Get All Users",
     *     tags={"Users"},
     *     security={{ "apiAuth": {} }},
     *     description="Get all users and user's data",
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="users",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/UsersWithData"),
     *                  minItems=2
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized Response",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */


    public function getsAction(Request $request, Response $response): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            return $this->okResponse($response, array('users' => array_map(static function (Users $user): array {
                $myUserArray = $user->toArray();
                $myUserData = UserData::getDataByUser($user->getId());
                $myUserArray['data'] = $myUserData;
                unset($myUserArray['password']);
                return $myUserArray;
            }, Users::getUsers())));
        }
        return $this->unauthorizedResponse($response, 'only director');
    }


    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Register",
     *     tags={"Users"},
     *     description="Register a user",
     *     @OA\RequestBody(ref="#/components/requestBodies/Register"),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/Users")
     *     ),
     *     @OA\Response(
     *          response="406",
     *          description="Error Not Acceptable",
     *          @OA\JsonContent(ref="#/components/schemas/NotAcceptableResponse")
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *          @OA\JsonContent(ref="#/components/schemas/InternalServerErrorResponse")
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized Response",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     *
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */

    public function postAction(Request $request, Response $response): Response
    {
        $myLogin = ParameterHelper::testLogin($this, $request, $response, true);
        if ($myLogin === null) {
            return $response;
        }

        $myRole = ParameterHelper::testRole($this, $request, $response, true);
        if ($myRole === null) {
            return $response;
        }

        $myJob = ParameterHelper::testJob($this, $request, $response, true);
        if ($myJob === null) {
            return $response;
        }

        $myPassword = ParameterHelper::testPassword($this, $request, $response, true);
        if ($myPassword === null) {
            return $response;
        }
        $myPassword = app('hash')->make($myPassword);

        if (Users::getUserByLogin($myLogin) !== null) {
            return $this->notAcceptableResponse($response, 'login exist');
        }

        $myUser = new Users();
        $myUser->setLogin($myLogin);
        $myUser->setPassword($myPassword);
        $myUser->setRole($myRole);
        $myUser->setJob($myJob);

        if (Users::addUser($myUser)) {
            return $this->okResponse($response, $myUser->toArray());
        }
        return $this->internalServerErrorResponse($response, 'Can\'t add user');

    }

    /**
     * @OA\Put(
     *     path="/users/update",
     *     tags={"Users"},
     *     description="Update a user",
     *     summary="Update User",
     *     security={{ "apiAuth": {} }},
     *     @OA\RequestBody(ref="#/components/requestBodies/PutLoginAndPassword"),
     *     @Oa\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/Users")
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundResponse")
     *     ),
     *     @OA\Response(
     *          response="406",
     *          description="Error Not Acceptable",
     *          @OA\JsonContent(ref="#/components/schemas/NotAcceptableResponse")
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *          @OA\JsonContent(ref="#/components/schemas/InternalServerErrorResponse")
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized Response",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     *
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */

    public function putAction(Request $request, Response $response): Response
    {
        if (AuthController::me() === null) {
            return $this->notFoundResponse($response, 'user');
        }

        $myUser = Users::getUserById(AuthController::me()->getId());

        if ($myUser === null) {
            return $this->notFoundResponse($response, 'user');
        }

        $myLogin = ParameterHelper::testLogin($this, $request, $response, false);
        if ($myLogin !== null) {
            $myUser->setLogin($myLogin);
        }

        $myPassword = ParameterHelper::testPassword($this, $request, $response, false) ?? microtime(true);
        if ($myPassword !== null) {
            $myPassword = app('hash')->make($myPassword);
            $myUser->setPassword($myPassword);
        }

        if (Users::updateUser($myUser)) {
            return $this->okResponse($response, $myUser->toArray());
        }
        return $this->internalServerErrorResponse($response, 'Can\'t update user');
    }

    /**
     * @OA\Delete(
     *     path="/users/delete/{userId}",
     *     tags={"Users"},
     *     description="Delete a user and return no content",
     *     summary="Delete User",
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          name="userId",
     *          description="User id",
     *          required=true,
     *          in="path"
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success"
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Not Found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundResponse")
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Internal Server Error",
     *          @OA\JsonContent(ref="#/components/schemas/InternalServerErrorResponse")
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Unauthorized Response",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse")
     *     )
     * )
     *
     *
     * @param Request $request
     * @param Response $response
     * @param string $userId
     * @return Response
     */

    public function deleteAction(Request $request, Response $response, string $userId): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            $myUser = Users::getUserById($userId);
            if ($myUser === null) {
                return $this->notFoundResponse($response, 'user');
            }

            if (Users::deleteUser($myUser)) {
                return $this->okResponse($response);
            }
            return $this->internalServerErrorResponse($response, 'Can\'t remove user');
        }
        return $this->unauthorizedResponse($response, 'only director');
    }

}
