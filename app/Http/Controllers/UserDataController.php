<?php

namespace App\Http\Controllers;

use App\Models\Enums\Role;
use App\Models\User;
use App\Models\UserData;
use App\Models\Users;
use App\Models\Utils\ParameterHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
/**
 * @OA\RequestBody(
 *     request="PostUserData",
 *     description="Post User data body",
 *     @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(ref="#/components/schemas/PostUserDataRequest")
 *      )
 * )
 */
/**
 * @OA\RequestBody(
 *     request="UpdateUserData",
 *     description="Update User data body",
 *     @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(ref="#/components/schemas/UpdateUserDataRequest")
 *      )
 * )
 */
class UserDataController extends Controller
{
    /**
     * @OA\Get(
     *     path="/users/data/{dataId}",
     *     summary="Get UserData By Id",
     *     tags={"UsersData"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          name="dataId",
     *          description="UserData id",
     *          required=true,
     *          in="path"
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/UserData")
     *          )
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
     * @param Request $request
     * @param Response $response
     * @param string $dataId
     * @return Response
     */
    public function getAction(Request $request, Response $response, string $dataId): Response
    {
        $myUserData = UserData::getDataById($dataId);
        if ($myUserData === null) {
            return $this->notFoundResponse($response, 'user data');
        }
        return $this->okResponse($response, $myUserData->toArray());
    }

    /**
     * @OA\Get(
     *     path="/users/data/all",
     *     summary="Get All UserData ",
     *     tags={"UsersData", "Admin"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="userData",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/UserData"),
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
            return $this->okResponse($response, array('userData' => array_map(static function(UserData $data): array {
                return $data->toArray();
            }, UserData::getAllUserData())));
        }
        return $this->unauthorizedResponse($response, 'only director');
    }

    /**
     * @OA\Get(
     *     path="/users/data/user/{userId}",
     *     summary="Get UserDatas By User",
     *     tags={"UsersData"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          name="userId",
     *          description="User id",
     *          required=true,
     *          in="path"
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="userData",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/UserData"),
     *                  minItems=2
     *              )
     *          )
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
     * @param Request $request
     * @param Response $response
     * @param string $userId
     * @return Response
     */
    public function getsByUserAction(Request $request, Response $response, string $userId): Response
    {
        $myUser = Users::getUserById($userId);
        if ($myUser === null) {
            return $this->notFoundResponse($response, 'user');
        }
        return $this->okResponse($response, array('userData' => static function(UserData $data): array {
            return $data->toArray();
        }, UserData::getDataByUser($userId)));
    }


    /**
     * @OA\Post(
     *      path="/users/data",
     *     summary="Post User Data",
     *     description="Post a user data, only director",
     *     tags={"UsersData"},
     *     security={{ "apiAuth": {} }},
     *     @OA\RequestBody(ref="#/components/requestBodies/PostUserData"),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/UserData")
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
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postAction(Request $request, Response $response): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            $myDataUserId = ParameterHelper::testDataUserId($this, $request, $response, true);
            if ($myDataUserId === null) {
                return $response;
            }

            if (Users::getUserById($myDataUserId) === null) {
                return $this->notFoundResponse($response, 'user');
            }

            $myDataKey = ParameterHelper::testDataKey($this, $request, $response, true);
            if ($myDataKey === null) {
                return $response;
            }
            $myDataColumn = ParameterHelper::testDataColumn($this, $request, $response, true);
            if ($myDataColumn === null) {
                return $response;
            }

            $myUserData = new UserData();
            $myUserData->setUserId($myDataUserId);
            $myUserData->setDataKey($myDataKey);
            $myUserData->setDataColumn($myDataColumn);

            if (UserData::addUserData($myUserData)) {
                return $this->okResponse($response, $myUserData->toArray());
            }
            return $this->internalServerErrorResponse($response, 'Can\'t add user data');
        }
        return $this->unauthorizedResponse($response, 'only director');
    }

    /**
     * @OA\Put(
     *     path="/users/data/update",
     *     summary="Update UserData",
     *     description="Update UserData with UserData Model in body",
     *     security={{ "apiAuth": {} }},
     *     tags={"UsersData"},
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateUserData"),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/UserData")
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
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function putAction(Request $request, Response $response): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            $myDataId = $this->getParam($request, 'data_id');
            $myUserData = UserData::getDataById($myDataId);
            if ($myUserData === null) {
                return $this->notFoundResponse($response, 'user data');
            }
            $myDataKey = ParameterHelper::testDataKey($this, $request, $response, false);
            if ($myDataKey !== null) {
                $myUserData->setDataKey($myDataKey);
            }
            $myDataColumn = ParameterHelper::testDataColumn($this, $request, $response, false);
            if ($myDataColumn !== null) {
                $myUserData->setDataColumn($myDataColumn);
            }

            if (UserData::updateUserData($myUserData)) {
                return $this->okResponse($response, $myUserData->toArray());
            }
            return $this->internalServerErrorResponse($response, 'Can\'t update user data');
        }
        return $this->unauthorizedResponse($response, 'only director');
    }

    /**
     * @OA\Delete(
     *     path="/users/data/delete/{dataId}",
     *     summary="Delete UserData",
     *     tags={"UsersData"},
     *     description="Delete a UserData",
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *          name="dataId",
     *          description="UserData Id",
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
     * @param Request $request
     * @param Response $response
     * @param string $dataId
     * @return Response
     */
    public function deleteAction(Request $request, Response $response, string $dataId): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            if (UserData::getDataById($dataId) === null) {
                return $this->notFoundResponse($response, 'user data');
            }
            if (UserData::deleteUserData($dataId)) {
                return $this->okResponse($response);
            }
            return $this->internalServerErrorResponse($response, 'Can\'t update user data');
        }
        return $this->unauthorizedResponse($response, 'only director');
    }

}
