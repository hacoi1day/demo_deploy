<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function storeUser(RegisterRequest $request)
    {
        $user = $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json($user);
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = $this->user->where('email', $request->email)->first();
            $user->token = $user->createToken('app')->accessToken;
            return response()->json($user);
        }
        return response()->json([
            'email' => 'Sai email hoặc mật khẩu.'
        ], 401);
    }

    public function userInfo()
    {
        return response()->json(Auth::guard('api')->user());
    }
}
