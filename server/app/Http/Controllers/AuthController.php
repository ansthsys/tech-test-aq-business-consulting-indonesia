<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $payload = $request->validated();

        if (!$token = auth("api")->attempt($payload)) {
            return $this->httpErrorResponse("Email or password is wrong", 401);
        }

        return $this->httpOkResponse("Success Login", [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth("api")->factory()->getTTL() * 60
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $payload = $request->validated();

        $user = User::create([
            'name' => $payload["name"],
            'email' => $payload["email"],
            'password' => Hash::make($payload["password"]),
        ]);

        return $this->httpOkResponse("Account created", $user, 201);
    }
}
