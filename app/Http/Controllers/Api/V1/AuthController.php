<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'cellphone' => 'required|string|min:11|max:11|unique:users,email',
            'password' => 'required|min:3',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails())
            return ApiResponse::errorResponse('validation', $validator->messages(), 422);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'cellphone' => $request->cellphone,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('myApp')->plainTextToken;

        return ApiResponse::successResponse(['user' => $user, 'token' => $token], 201);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails())
            return ApiResponse::errorResponse('validation', $validator->messages(), 422);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password))
            return ApiResponse::errorResponse('NotFound', 'user Not Found...', 422);

        $token = $user->createToken('myApp')->plainTextToken;
        return ApiResponse::successResponse(['user' => $user, 'token' => $token], 200);

    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return ApiResponse::successResponse('logged out...', 200);

    }
}
