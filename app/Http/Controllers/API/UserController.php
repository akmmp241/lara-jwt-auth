<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $user = User::query()->create($data);

        return response()->json([
            "message" => "User created successfully",
            "user" => new UserResource($user),
        ])->setStatusCode(201);
    }
}
