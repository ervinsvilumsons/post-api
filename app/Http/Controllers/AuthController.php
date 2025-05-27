<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {
        if (!auth()->attempt($request->only('email', 'password'))) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Invalid credentials');
        }

        return response()->json([
            'token' => auth()->user()->createToken('api-token')->plainTextToken, 
            'user' => new UserResource(auth()->user()),
        ]);
    }

    public function register(AuthRequest $request)
    {
        $user = User::create($request->only('email', 'password', 'name'));

        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken, 
            'user' => new UserResource($user),
        ]);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        
        abort(Response::HTTP_UNAUTHORIZED, 'Unauthenticated');
    }
}
