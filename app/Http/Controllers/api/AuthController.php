<?php

namespace App\Http\Controllers\api;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request ->validate([
            'email' =>  'required|email',
            'password'  => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success'   => false,
                'message'   => 'email atau password salah'],
                401
            );
        }

        if(!$user->is_active){
            return response()->json([
                'success'   => false,
                'message'   => 'akun kamu sudah tidak aktif'],
                403
            );
        }
        //hapus token lama
        $user->tokens()->delete();

        //buat token baru
        $token = $user->createToken('mobile-app')->plainTextToken;

        LogActivity::log('login', 'Login via Api', 'User', $user->id);

        return response()->json([
            'succes'    => true,
            'message'   => 'Login Berhasil',
            'token'     => $token,
            'user'      =>[
                'id'    =>$user->id,
                'name'  =>$user->name,
                'email' =>$user->email,
                'role'  =>$user->role
            ],
        ]);
    }

    public function logout(Request $request){
        LogActivity::log('Logout', 'Logout via API', 'User', $request->user()->id);
        $request->user()->currentAccessToken()->delete;

        return response()->json([
            'success'   => false,
            'message'   => 'Logout Berhasil'
        ]);
    }
}
