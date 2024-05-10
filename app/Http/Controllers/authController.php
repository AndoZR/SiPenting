<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{
    public function index() {
        return 'index';
    }

    public function signIn(Request $request) {
        if (Auth::attempt($request->only('username', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('api_token')->plainTextToken;
            $token_id = $user->tokens->last()->id;
            return response()->json(['token' => $token, 'token_id' => $token_id], 200);
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
