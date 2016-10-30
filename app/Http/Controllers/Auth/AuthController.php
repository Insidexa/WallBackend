<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Dingo\Api\Facade\API;
use Illuminate\Validation\Validator;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use DB;
use Hash;
use Illuminate\Foundation;

class AuthController extends Controller
{
    protected $user = null;
    
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function signin(Request $request, $user = null) {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => trans('auth.failed')], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => trans('auth.could_not_create_token')], 500);
        }

        if ($user === null)
            $user = $this->user;

        return response()->json(compact('token', 'user'));
    }

    public function signup (Request $request) {
        $credentials = $request->only('name', 'email', 'password');

        $user = User::create($credentials);

        return $this->signin($request, $user);
    }

    public function validateToken() {
        return API::response()->array(['status' => trans('auth.success')])->statusCode(200);
    }
}
