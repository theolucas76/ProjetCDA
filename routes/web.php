<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function ($router) {
    $router->post('register', 'UsersController@postAction');
    $router->post('login', 'AuthController@login');

});

$router->group( ['prefix' => 'api/v1','middleware' => 'auth'], function ($router) {
    // USERS
    $router->group( ['prefix' => '/users'], function ($router) {
        $router->get('{userId}', 'UsersController@getAction');
        $router->get('', 'UsersController@getsAction');
        $router->put('update', 'UsersController@putAction');
        $router->delete('delete/{userId}', 'UsersController@deleteAction');
    } );

    //SITES
    $router->group( ['prefix' => '/sites'], function ($router) {
        $router->get('{siteId}', 'SiteController@getAction');
        $router->get('/user/{userId}', 'SiteController@getsActionByUser');
        $router->post('', 'SiteController@postAction');
    } );
} );



