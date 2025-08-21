<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $repo;

    public function __construct()
    {
        $this->repo = new UserRepository();
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => "Invalid Email or Password!",
                'errors' => [
                    'email' => ["Invalid Email or Password!"],
                ],
            ], 401);
        }

        $token = $user->createToken($user->id);

        return response()->json([
            'action' => 'login',
            'user' => $user,
            'token' => $token->plainTextToken,
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = $this->repo->createRecord($data, new User());

        return response()->json(['action' => 'registered']);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['action' => 'logout']);
    }
}
