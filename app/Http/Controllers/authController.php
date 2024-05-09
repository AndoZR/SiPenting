<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{
    public function index() {
        $data = User::get();
        dd($data);
        return 'selamat';
    }
    public function coba(Request $request) {
        var_dump($_POST);
        $data = User::get();
        return 'selamat';
    }

    public function signIn(Request $request) {
        if(strpos($request->username, 'admin') !== false){
            if(Auth::attempt($request->only('username', 'password'))) {
                return 'berhasil';
            }
        } else {
            if(Auth::attempt($request->only('username', 'password'))) {
                return 'berhasil';
            }
        }
        return redirect('/')->with('message', 'Username Atau Password Salah!');
    }
    
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
