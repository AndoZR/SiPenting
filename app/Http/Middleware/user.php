<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseFormatter;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class user
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next, ...$role): Response
    // {
    //     if ($request->user() && in_array($request->user()->role, $role)) {
    //         return $next($request);
    //     }

    //     return response()->json(['message' => 'Anda tidak memiliki akses!'], 403);
    // }

    public function handle(Request $request, Closure $next, ...$roles)
    {
        try{
            $user = $request->user();

            if ($user && in_array($user->role, $roles)) {
                return $next($request);
            }
            return response()->json(['message' => 'Anda tidak memiliki akses'], 403);
        }catch(Exception $e){
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(),"Error Kesalahan Server", 500);
        }
        // Jika pengguna tidak memiliki peran yang sesuai, Anda bisa mengembalikan respons tertentu, misalnya 403 Forbidden
    }
}
