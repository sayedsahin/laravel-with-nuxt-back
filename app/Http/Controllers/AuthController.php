<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $attr = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed'],
        ]);
        $attr['password'] = bcrypt($attr['password']);
        $user = User::create($attr);
        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }
    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        $user = User::where('email', $attr['email'])->first();
        if (!$user || !Hash::check($attr['password'], $user->password)) {
            return response([
                'message' => 'Login Error'
            ], 401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            // 'data' => [
                'user' => $user,
                'token' => $token
            // ]
        ];
        return response($response, 201);
    }
    
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        // $request->user()->currentAccessToken()->delete();
        return [
            'message' => 'Loggout Success'
        ];
    }
}
