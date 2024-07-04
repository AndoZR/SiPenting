<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Database\Seeders\userSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['username', 'password']);
        $username = $credentials['username'];
        
        // Check if the username exists in the database
        $userExists = User::where('username', $username)->exists();
    
        if (!$userExists) {
            return response()->json(['error' => 'Pengguna Tidak Ditemukan, Pastikan Username Anda Benar!'], 404);
        }

        // $credentials = request(['username', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'role' => auth()->user()->role
        ]);
    }

    public function register(Request $request)
    {
        if($request->role == 1){
            $validator = Validator::make($request->all(), [
                'username' => 'required|integer|digits:16|unique:users,nik',
                'tanggalLahir' => 'required|date',
                'namaIbu' => 'required|string|max:255',
                'tinggiBadan' => 'required|integer',
            ]);
     
            if ($validator->fails()) {
                return ResponseFormatter::error(null,$validator->errors(),422);
            };
    
            try {
                $dataUser = User::create([
                    'username' => $request->username,
                    'nik' => $request->username,
                    'tanggalLahir' => $request->tanggalLahir,
                    'namaIbu' => $request->namaIbu,
                    'bbPraHamil' => $request->bbPraHamil,
                    'tinggiBadan' => $request->tinggiBadan,
                    'password' => Hash::make($request->password),
                    'role' => 1,
                ]);
    
                return ResponseFormatter::success($dataUser, "Data User Berhasil Dibuat!");
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data gagal disimpan. Kesalahan Server", 500);
            }
        }else{
            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:users,nik',
            ]);
     
            if ($validator->fails()) {
                return ResponseFormatter::error(null,$validator->errors(),422);
            };
    
            try {
                $dataUser = User::create([
                    'username' => $request->username,
                    'nik' => $request->username,
                    'password' => Hash::make($request->password),
                    'role' => 2,
                ]);
    
                return ResponseFormatter::success($dataUser, "Data User Berhasil Dibuat!");
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data gagal disimpan. Kesalahan Server", 500);
            }
        }
    }

    public function getUser()
    {
        $user = Auth::user()->makeHidden('password');
        return ResponseFormatter::success($user, "Data User Berhasil Didapat!");
    }

    public function updateProfile(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'integer|digits:16',
            'tanggalLahir' => 'date',
            'namaIbu' => 'string|max:255',
            'bbPraHamil' => 'numeric|max:1000',
            'tinggiBadan' => 'numeric|max:1000',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null,$validator->errors(),422);
        };

        try{
            $data = User::find(Auth::user()->id);
    
            $data->update([
                'nik' => $request->username,
                'username' => $request->username,
                'tanggalLahir' => $request->tanggalLahir,
                'namaIbu' => $request->namaIbu,
                'bbPraHamil' => $request->bbPraHamil,
                'tinggiBadan' => $request->tinggiBadan,
                'password' => hash::make($request->username),
            ]);

            return ResponseFormatter::success($data, "Data Berhasil Diperbarui!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal diperbarui. Kesalahan Server!", 500);
        }
    }
}