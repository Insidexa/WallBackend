<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Dingo\Api\Facade\API;
use Helpers\UserData;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Foundation;

/**
 * Class AuthController
 * @package App\Http\Controllers\Auth
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signin(Request $request) {

        $credentials = $request->only('email', 'password');
        
        try {
            if (! $token = \JWTAuth::attempt($credentials)) {
                return response()->json(['error' => trans('auth.failed')], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => trans('auth.could_not_create_token')], 500);
        }

        $user = \JWTAuth::authenticate($token);

        return response()->json(compact('token', 'user'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup (Request $request) {

        $validator = \Validator::make($request->all(), User::$rules);

        if ($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);
        
        $credentials = $request->only('name', 'email', 'password');

        User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => bcrypt($credentials['password'])
        ]);

        return $this->signin($request);
    }

    /**
     * @return mixed
     */
    public function validateToken() {
        return API::response()->array(['status' => trans('auth.success')])->statusCode(200);
    }
}
