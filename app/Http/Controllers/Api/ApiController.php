<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    // post
    public function Register(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'string', 'min:7', 'confirmed']
            ]
        );
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'status' => true,
            'message' => 'user created successfully'
        ]);
    }
    // post
    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => [' required', 'email'],
                'password' => ['required', 'string', 'min:7', 'confirmed']
            ]
        );
        // User::find($request);
        $token = JWTAuth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);
        if (!empty($token)) {
            return response()->json([
                'status' => true,
                'message' => 'Vous avez été connecté avec succès. Bienvenue',
                'token' => $token
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Les informations que vous avez saisies ne sont pas correctes'
        ]);
    }

    // Get
    public function profile()
    {
        $userData = Auth::user();
        return response()->json([
            'status' => true,
            'data' => $userData
        ]);
    }

    // Get
    public function refreshToken()
    {
        $rewToken = Auth::refresh();
        return response()->json([
            'status' => true,
            'token' => $rewToken
        ]);
    }

    // GET
    public function logout()
    {
        Auth::logout();

        return response()->json([
            'status' => true,
            'message' => 'Vous vous êtes déconnecté avec succès'
        ]);
    }
}
