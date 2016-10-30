<?php

/**s @var Dingo\Api\Routing\Router $api */
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    /** @var Dingo\Api\Routing\Router $api */
    $api->group([
        'namespace' => 'App\Http\Controllers',
        'middleware' => '\Barryvdh\Cors\HandleCors::class',
        'domain' => 'wall-backend.jashka'], function ($api) {
        /** @var Dingo\Api\Routing\Router $api */

        $api->post('signin', 'Auth\AuthController@signin');
        $api->post('signup', 'Auth\AuthController@signup');

        $api->group(['middleware' => 'jwt.auth','providers' => ['basic', 'jwt']], function (
            $api) {
            /** @var Dingo\Api\Routing\Router $api */


        });

    });
});