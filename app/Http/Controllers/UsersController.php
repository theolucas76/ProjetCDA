<?php

namespace App\Http\Controllers;

use App\Models\Enums\Role;
use App\Models\Users;
use App\Models\Utils\ParameterHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class UsersController extends Controller
{
    public function getAction(Request $request, Response $response, string $userId): Response
    {
        $myUser = Users::getUserById($userId);
        if ($myUser === null) {
            return $this->notFoundResponse($response, 'user');
        }
        $myUserArray = $myUser->toArray();
        unset($myUserArray['password']);
        return $this->okResponse($response, $myUserArray);
    }

    public function getsAction(Request $request, Response $response): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            return $this->okResponse($response, array('users' => array_map(static function (Users $user): array {
                $myUserArray = $user->toArray();
                unset($myUserArray['password']);
                return $myUserArray;
            }, Users::getUsers())));
        }
       return $this->unauthorizedResponse($response, 'only director');
    }

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

    public function deleteAction(Request $request, Response $response, string $userId): Response
    {
        if (AuthController::me()->getRole()->__toInt() === Role::DIRECTOR) {
            $myUser = Users::getUserById($userId);
            if ($myUser === null) {
                return $this->notFoundResponse($response, 'user');
            }

            if (Users::deleteUser($myUser)) {
                return $this->okResponse($response, $myUser->toArray());
            }
            return $this->internalServerErrorResponse($response, 'Can\'t remove user');
        }
        return $this->unauthorizedResponse($response, 'only director');
    }

}
