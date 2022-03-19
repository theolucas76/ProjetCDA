<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    public function getAction(Request $request, Response $response, string $user_id)
    {

        var_dump($this->getParam($request, 'ticket_id'));
    }
}
