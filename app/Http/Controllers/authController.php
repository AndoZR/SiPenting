<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\villages;
use App\Models\districts;
use App\Models\akun_bidan;
use App\Models\akun_bapeda;
use App\Models\akun_dinkes;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\akun_puskesmas;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function login()
    // {
    //     $credentials = request(['username', 'password']);
    //     $username = $credentials['username'];
        
    //     // Check if the username exists in the database
    //     $userExists = User::where('username', $username)->exists();
    
    //     if (!$userExists) {
    //         return response()->json(['error' => 'Pengguna Tidak Ditemukan, Pastikan Username Anda Benar!'], 404);
    //     }

    //     // $credentials = request(['username', 'password']);

    //     if (! $token = auth()->attempt($credentials)) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     return $this->respondWithToken($token);
    // }

    public function login()
    {
        // Ambil hanya username dari request
        $username = request('username');

        if(preg_match('/^3511/', $username)){
            // Cek jika username warga bondowoso ada di database
            $user = User::where('username', $username)->first();
        }elseif (empty($username)) {
            // cek guest -> idnya 7 di database user '1919191919191919' sebagai default
            $user = User::where('username', '1919191919191919')->first();
        }else{
            return ResponseFormatter::error(null,"Aplikasi ini hanya dapat digunakan oleh warga Bondowoso, harap gunakan guest mode untuk mengakses aplikasi kalkulator gizi, stunting, dan juga informasi terkait stunting!",422);
        }
    
        if (!$user) {
            return ResponseFormatter::error(null,"Pengguna Tidak Ditemukan, Pastikan Username Anda Benar!",422);
            // return response()->json(['error' => ''], 404);
        }
    
        // Generate token jika username ditemukan
        // Biasanya menggunakan Laravel Passport atau Laravel Sanctum untuk token
        $token = auth()->login($user);

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
            'expires_in' => auth()->factory()->getTTL(),
            'role' => auth()->user()->role
        ]);
    }

    public function register(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'username' => [
                    'required',
                    'integer',
                    'digits:16',
                    'unique:users,nik',
                    function ($attribute, $value, $fail) {
                        // Cek apakah username dimulai dengan '3511'
                        if (!preg_match('/^3511/', $value)) {
                            $fail("Aplikasi ini hanya dapat digunakan oleh warga Bondowoso, harap gunakan guest mode untuk mengakses aplikasi kalkulator gizi, stunting, dan juga informasi terkait stunting.");
                        }
                    },
                ],
                // 'tanggalLahir' => 'required|date',
                'namaIbu' => 'required|string|max:255',
                // 'tinggiBadan' => 'required|integer',
                'id_desa' => 'required',
            ]);
     
            if ($validator->fails()) {
                // Mendapatkan pesan error tanpa field name
                $errors = $validator->errors()->all();
                
                return ResponseFormatter::error(null,$errors[0],422);
            };
    
            try {
                $dataUser = User::create([
                    'username' => $request->username,
                    'nik' => $request->username,
                    // 'tanggalLahir' => $request->tanggalLahir,
                    'namaIbu' => $request->namaIbu,
                    // 'tinggiBadan' => $request->tinggiBadan,
                    'password' => Hash::make($request->username),
                    'role' => 1,
                    'id_villages' => $request->id_desa,
                ]);
    
                return ResponseFormatter::success($dataUser, "Data User Berhasil Dibuat!");
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error(null, $e->getMessage(), 500);
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
            // Mendapatkan pesan error tanpa field name
            $errors = $validator->errors()->all();
                
            // // Menggabungkan pesan error menjadi satu string
            // $errorMessage = implode(' ', $errors);
            return ResponseFormatter::error(null,$errors[0],422);
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
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

    public function getKecamatan() {
        $data = districts::where('regency_id',3511)->get();

        return ResponseFormatter::success($data, 'Berhasil Mendapatkan Data!');
    }

    public function getDesa(Request $request) {
        try{
            if(isset($request->id_kecamatan)){
                $data = villages::where('district_id',$request->id_kecamatan)->get();
                return ResponseFormatter::success($data, 'Berhasil Mendapatkan Data!');
            }else{
                $data = villages::whereIn('district_id',
                [3511010,3511020,3511030,3511031,3511040,3511050,3511060,3511061,3511070,3511080,3511090,3511100,
                3511110,3511111,3511120,3511130,3511140,3511141,3511150,3511151,3511152,3511160,3511170]
                )->get();
                return ResponseFormatter::success($data, 'Berhasil Mendapatkan Data!');
            }
        }catch(Exception $e){
            return ResponseFormatter::error(null,$e->getMessage(),500);
        }

    }

    public function getIdSubs(Request $request){
        try{
            $user = Auth::user()->id;
            $data = User::find($user);
            $data->update([
                'id_subs' => $request->id_subs
            ]);

            return ResponseFormatter::success($data->id_subs, "Berhasil Mendapatkan ID Subs OneSignal!");
        }catch(Exception $e){
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(),500);
        }
    }

    public function sendNotif(){
        // dd($idsubs);
        $idsubs = Auth::user()->id_subs;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json; charset=utf-8',
            'Authorization' => 'Basic ZGZlNDhlMzMtZDdkZC00NmI3LWE5YzQtODg3MGNkODg5M2I4'
        ])->post('https://api.onesignal.com/notifications', [
            'app_id' => '9a7d21da-61b0-422e-b238-eb8cdc24cead',
            'name' => ['en' => 'My notification Name'],
            'contents' => ['en' => 'testing on notification from server online success yuhuuu'],
            'headings' => ['en' => 'English Title'],
            'include_subscription_ids' => [
                '860d76ea-153c-452c-96d5-7f6eab8dd67c',
                // 'SUBSCRIPTION_ID_2',
                // 'SUBSCRIPTION_ID_3',
            ],
        ]);

        // Cek respons dari request
        if ($response->successful()) {
            return ResponseFormatter::success( $response->json(), 'Berhasil Mengirim Notif!');
            // return $response->json(); // Mengembalikan data respons sebagai array JSON
        } else {
            return $response->body(); // Jika ada error
        }
    }



    // SISI WEBSITE
    public function registerBidan(Request $request) {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|unique:users,nik',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('viewRegisterBidan')->with('error', $validator->errors());
        };

        try {
            $dataUser = User::create([
                'username' => $request->nik,
                'nik' => $request->nik,
                'password' => Hash::make($request->nik),
                'role' => 2,
            ]);

            return redirect()->route('viewRegisterBidan')->with('success', 'Data User Berhasil Dibuat!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('viewRegisterBidan')->with('error', $e->getMessage());
        }
    }

    public function viewRegisterBidan() {
        return view('registerBidan');
    }

    public function viewLogin() {
        return view('registerBidan');
    }

    public function loginWeb() {
        if (Auth::check()) {
            // Jika sudah login, arahkan ke halaman home
            return redirect()->route('home');
        }
        return view('login');
    }

    public function loginBapeda(Request $request) {
        // Validasi input hanya untuk username
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8',
        ]);
    
        if ($validator->fails()) {
            // Kirimkan pesan error pertama
            return redirect()->route('login')->with('error', $validator->errors()->first());
        }
    
        try {
            // Mencari user berdasarkan username
            $password = $request->input('password');  // Ambil input password dari form
            $akunBapeda = akun_bapeda::first();       // Ambil satu-satunya akun bapeda dari database

            if ($akunBapeda && Hash::check($password, $akunBapeda->password)) {
                Auth::guard('bapeda')->login($akunBapeda);
                // Redirect atau logic setelah login sukses
            } else {
                return redirect()->route('login')->with('error', 'Password salah atau akun tidak ditemukan.');
            }

            // Regenerasi session untuk mencegah session fixation attacks
            $request->session()->regenerate();

            // Arahkan ke halaman setelah login
            return redirect('home');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }   

    public function loginPuskesmas(Request $request) {
        // Validasi input hanya untuk username
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8',
        ]);
    
        if ($validator->fails()) {
            // Kirimkan pesan error pertama
            return redirect()->route('login')->with('error', $validator->errors()->first());
        }
    
        try {
            // Mencari user berdasarkan username
            $password = $request->input('password');

            $akunPuskesmas = akun_puskesmas::all()->filter(function($akun) use ($password) {
                return Hash::check($password, $akun->password);
            })->first();

            if ($akunPuskesmas) {
                Auth::guard('puskesmas')->login($akunPuskesmas);
                // lanjutkan login
            } else {
                return redirect()->route('login')->with('error', 'Akun tidak ditemukan.');
            }

            // Regenerasi session untuk mencegah session fixation attacks
            $request->session()->regenerate();

            // Arahkan ke halaman setelah login
            return redirect('home');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }    

    public function logoutWeb(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
    
        // Arahkan ke halaman login
        return redirect('/login');
    }
}