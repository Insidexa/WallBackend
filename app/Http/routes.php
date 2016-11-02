<?php

/** @var Dingo\Api\Routing\Router $api */
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    /** @var Dingo\Api\Routing\Router $api */
    $api->group([
        'namespace' => 'App\Http\Controllers',
        'middleware' => \Barryvdh\Cors\HandleCors::class,
        'domain' => config('app.url')], function ($api) {
        /** @var Dingo\Api\Routing\Router $api */
        $api->get('/', 'HomeController@index');
        $api->post('signin', 'Auth\AuthController@signin');
        $api->post('signup', 'Auth\AuthController@signup');

        $api->group(['middleware' => 'jwt.auth','providers' => ['basic', 'jwt']], function (
            $api) {
            /** @var Dingo\Api\Routing\Router $api */

            $api->resource('walls', 'WallController');
            $api->resource('comments', 'CommentController');
            $api->delete('comments/{id}/{wallId}', 'CommentController@destroy');
            $api->resource('ignores', 'IgnoreController');
            $api->resource('likes', 'LikeController');
        });

    });
});