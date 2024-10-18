<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request): UserResource
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();
        if(!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'email or password is wrong'
                    ]
                ]
                    ], 401));
        }

        $user->token = Str::uuid()->toString();
        $user->save();

        return new UserResource($user);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        if(User::where('email', $data['email'])->exists()) {
            throw new HttpResponseException(response([
                'errors' => [
                    'email' => [
                        'email is already'
                    ]
                ]
                    ], 422));
        }

        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function getUser(Request $request): UserResource
    {
        $user = Auth::user();

        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request): UserResource
    {
        $data = $request->validated();
        $user = Auth::user();
        
        if(isset($data['name'])){
            $user->name = $data['name'];
        }

        if(isset($data['password'])){
            $user->password = Hash::make($data['password']);
        }
        
        $user->save();
        return new UserResource($user);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->token = null;
        $user->save();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
