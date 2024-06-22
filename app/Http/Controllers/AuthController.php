<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request): Response|Application|ResponseFactory
    {
        $credentials = $request->validated();
        // ตรวจสอบข้อมูลผู้ใช้จากฐานข้อมูลโดยใช้ Eloquent หรือ Query Builder
        $user = User::where('username', $credentials['username'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response([
                'error' => 'The Provided credentials are not correct',
                'message' => 'ผู้ใช้งานระบบหรือรหัสผ่านไม่ถูกต้อง'
            ], 422);
        }
//        $token = User::generateToken($user);
        $token = $user->createToken('main')->plainTextToken;
        return response([
            'user' => $user,
            'token' => $token,
            'message' => 'เข้าสู่ระบบสำเร็จ'
        ]);
    }

    public function me(Request $request){
        return $request->user();
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function listUsers(): JsonResponse
    {
        $users = User::all();
        return response()->json($users);
    }
}
