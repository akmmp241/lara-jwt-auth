<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::query()->create($data);

        return response()->json([
            "message" => "User created successfully",
            "user" => new UserResource($user),
        ])->setStatusCode(201);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (!$token = Auth::attempt($data)) {
            throw new HttpResponseException(response()->json([
                "success" => false,
                "message" => "Username or password is incorrect",
            ], 401));
        }

        return $this->respondWithToken($token);
    }

        $token = Auth::user()->createToken("auth_token");

        return $this->respondWithToken($token->plainTextToken);
    }

    public function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            "success" => true,
            "access_token" => $token,
            "token_type" => "bearer",
            "expires_in" => Auth::factory()->getTTL() * 60
        ]);
    }
}
