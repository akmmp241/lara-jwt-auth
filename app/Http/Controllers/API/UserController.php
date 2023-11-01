<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

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
            Log::error($exception->getMessage());
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

    public function sendMail(Request $request): JsonResponse
    {
        if (RateLimiter::tooManyAttempts('sendMail:' . Auth::id(), 1)) {
            $seconds = RateLimiter::availableIn('sendMail:' . Auth::id());
            throw new HttpResponseException(response([
                "success" => false,
                "message" => "You can send new verification after $seconds seconds",
            ], Response::HTTP_TOO_MANY_REQUESTS));
        }


        if (!Auth::check()) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => "You're not authorized to perform this action"
                ]
            ], Response::HTTP_UNAUTHORIZED));
        }

        if ($request->user()->getRememberTokenName() && $request->user()->email_verified_at) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => "Email already verified"
                ]
            ], Response::HTTP_OK));
        }

        $random = Str::random(40);
        $domain = URL::to('/');
        $url = $domain . '/verify-mail/' . $random;

        $data['url'] = $url;
        $data['email'] = Auth::user()->email;
        $data['title'] = "Email Verification";
        $data['body'] = "Please click below here to verify your email";

        $data = [
            "url" => $url,
            "email" => Auth::user()->email,
            "title" => "Email Verification",
            "body" => "Please click below here to verify your email"
        ];

        Mail::to($data['email'])->send(new VerifyEmail($data));

        Auth::user()->setRememberToken($random);
        Auth::user()->save();

        RateLimiter::hit('sendMail:' . Auth::id());

        return response()->json([
            "success" => true,
            "message" => "Mail sent successfully"
        ]);
    }

    public function verifyEmail(string $token): View
    {
        $user = User::query()->where('remember_token', $token)->first();

        abort_if(!$user, Response::HTTP_UNAUTHORIZED);

        if ($user->email_verified_at) {
            return view('verifiedMail', [
                "message" => "Email already verified"
            ]);
        }

        $user->email_verified_at = now();
        $user->save();

        return view('verifiedMail', [
            "message" => "Email verification success"
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
