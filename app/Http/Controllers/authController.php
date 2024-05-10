<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{
    public function index() {
        return 'selamat';
    }
    public function coba() {
        return 'selamat';
    }

    public function signIn(Request $request) {
        if (Auth::attempt($request->only('username', 'password'))) {
            $user = Auth::user();
            // $token = $user->createToken('token')->accessToken;
            $token = $request->header('Authorization');
            return $user;
            // return $token;
            // return response()->json(['token' => $token], 200);

        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
