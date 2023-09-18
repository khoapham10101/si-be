<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entities\User as EntitiesUser;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // Check email
        $user = EntitiesUser::where('email', $request->email)->first();

        // Check password
        if(!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Bad creds', 401);
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;
            return $this->successResponse([
                'access_token' => $token,
                'user' => $user
            ]);
        }

        return $this->errorResponse('Unauthorized', 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->successResponse([
            'message' => 'You have been successfully logged out!'
        ]);
    }
}
