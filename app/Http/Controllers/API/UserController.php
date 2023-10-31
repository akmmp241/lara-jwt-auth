<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\NoReturn;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::query()->create($data);

        return response()->json([
            "message" => "User created successfully",
            "user" => new UserResource($user),
        ])->setStatusCode(Response::HTTP_CREATED);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (!$token = Auth::attempt($data)) {
            throw new HttpResponseException(response()->json([
                "success" => false,
                "message" => "Username or password is incorrect",
            ], Response::HTTP_UNAUTHORIZED));
        }

        return $this->respondWithToken($token);
    }

    public function logout(): JsonResponse
    {
        try {
            Auth::logout();

            return response()->json([
                "success" => true,
                "message" => "User logged out successfully",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Failed to logout user",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get(): JsonResponse
    {
        try {
            return response()->json([
                "success" => true,
                "message" => "User retrieved successfully",
                "user" => new UserResource(Auth::user()),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                "success" => false,
                "message" => "Failed to get user",
                "user" => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $user = User::query()->findOrFail($data['user_id']);
            $user->fill($data);
            $user->save();
        } catch (\Exception $exception) {
            return response()->json([
                "success" => false,
                "message" => "Failed to update user",
                "user" => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            "success" => true,
            "message" => "User updated successfully",
            "user" => new UserResource($user),
        ]);
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
