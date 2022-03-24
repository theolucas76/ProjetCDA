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

//PUBLIC ROUTES
$router->group(['prefix' => 'api/v1'], function ($router) {
    //AUTH
    $router->post('register', 'UsersController@postAction');
    $router->post('login', 'AuthController@login');

    $router->group(['prefix' => '/count/'], function ($router) {
        //SITES COUNTS
        $router->get('/sites', 'SiteController@getCountAction');
        $router->get('/currentSites', 'SiteController@getCurrentCountAction');
        $router->get('/previousSites', 'SiteController@getPreviousCountAction');
        $router->get('/sitesByYear/{year}', 'SiteController@getCountByYearAction');
        //USERS COUNTS
        $router->get('/users', 'UsersController@getCountAction');
        $router->get('/usersByRole/{roleId}', 'UsersController@getUsersCountByRoleAction');
        $router->get('/usersByJob/{jobId}', 'UsersController@getUsersCountByJobAction');
    });
});

//PRIVATE ROUTES
$router->group(['prefix' => 'api/v1', 'middleware' => 'auth'], function ($router) {
    // USERS
    $router->group(['prefix' => '/users'], function ($router) {
        $router->get('/{userId}', 'UsersController@getAction');
        $router->get('', 'UsersController@getsAction');
        $router->put('/update', 'UsersController@putAction');
        $router->delete('/delete/{userId}', 'UsersController@deleteAction');

        // USERS DATA
        $router->group(['prefix' => '/data'], function ($router) {
            $router->get('/all', 'UserDataController@getsAction');
            $router->get('/{dataId}', 'UserDataController@getAction');
            $router->get('/user/{userId}', 'UserDataController@getsByUserAction');
            $router->post('', 'UserDataController@postAction');
            $router->put('/update', 'UserDataController@putAction');
            $router->delete('/delete/{dataId}', 'UserDataController@deleteAction');
        });
    });

    //SITES
    $router->group(['prefix' => '/sites'], function ($router) {
        $router->get('', 'SiteController@getsAction');
        $router->get('/{siteId}', 'SiteController@getAction');
        $router->get('/user/{userId}', 'SiteController@getsActionByUser');
        $router->get('/year/{year}', 'SiteController@getByYearAction');
        $router->post('', 'SiteController@postAction');
        $router->put('/update', 'SiteController@putAction');
        $router->delete('/delete/{siteId}', 'SiteController@deleteAction');

        //SITES DATA
        $router->group(['prefix' => '/data'], function ($router) {
            $router->get('/all', 'SiteDataController@getsAction');
            $router->get('/{dataId}', 'SiteDataController@getAction');
            $router->get('/site/{siteId}', 'SiteDataController@getsBySiteAction');
            $router->post('', 'SiteDataController@postAction');
            $router->put('/update', 'SiteDataController@putAction');
            $router->delete('/delete/{dataId}', 'SiteDataController@deleteAction');
        });
    });

    //MATERIALS
    $router->group(['prefix' => '/materials'], function ($router) {
        $router->get('', 'MaterialController@getsAction');
        $router->get('/{materialId}', 'MaterialController@getAction');
        $router->get('/category/{categoryId}', 'MaterialController@getsByCategory');
        $router->post('', 'MaterialController@postAction');
        $router->put('/update', 'MaterialController@putAction');
        $router->delete('/delete/{materialId}', 'MaterialController@deleteAction');

        //MATERIALS DATA
        $router->group(['prefix' => '/data'], function ($router) {
            $router->get('/all', 'MaterialDataController@getsAction');
            $router->get('/{dataId}', 'MaterialDataController@getAction');
            $router->get('/material/{materialId}', 'MaterialDataController@getsByMaterialAction');
            $router->post('', 'MaterialDataController@postAction');
            $router->put('/update', 'MaterialDataController@putAction');
            $router->delete('/delete/{dataId}', 'MaterialDataController@deleteAction');
        });
    });

    //TICKETS
    $router->group(['prefix' => '/tickets'], function ($router) {
        $router->get('/{ticketId}', 'TicketController@getAction');
        $router->get('', 'TicketController@getsAction');
    });

});



