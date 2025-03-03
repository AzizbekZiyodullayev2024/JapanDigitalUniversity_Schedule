<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email|unique:users,email|max:255|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user=User::query()->create($validator);

        $token = $user->createToken('authToken')->plainTextToken;

        return apiResponse('User registered successfully', 201, ['user' => $user], $token);
    }

    public function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|email|exists:users,email|max:255|string',
            'password' => 'required|string|min:6'
        ]);

        $email = $validator['email'];
        $password = $validator['password'];

        $user = User::query()->where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect'
            ], 401);
        }
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'User logged out.'
        ]);
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        $user = User::firstOrCreate([
            'email' => $user->email
        ], [
            'name' => $user->name,
            'password' => Hash::make(Str::random(24))
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json(['token' => $token]);
    }


}