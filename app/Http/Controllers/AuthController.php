<?php

namespace App\Http\Controllers;

use App\Foglobal\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Requests\User\UserAuthRequest;

class AuthController extends Controller
{

    public function __construct() {
        return auth()->shouldUse('api');
    }

    public function login(UserAuthRequest $request) {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        try {
            if(!$token = auth('api')->attempt($data)) {
                return ApiResponse::sendResponse('Unauthorized', 401);
            }

            return $this->responWithToken($token);
        }catch(\Exception $ex) {
            return ApiResponse::rollback($ex);
        }
    }

    public function profile() {
        return ApiResponse::sendResponse(new UserResource(auth('api')->user()), 200);
    }

    public function refresh() {
        return $this->responWithToken(auth('api')->refresh());
    }

    public function logout() {
        auth('api')->logout();
        return ApiResponse::sendResponse('Successfully logged out', 200);
    }

    protected function responWithToken($token) {
        // $payload = JWTAuth::manager()->getJwtProvider()->decode($token);
        return response()->json([
            'id' => auth('api')->user()->id,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
}
