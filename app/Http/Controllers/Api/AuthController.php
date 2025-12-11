<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //POST
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        //BUSCAR USUARIO 
        $user = User::where('email', $request->email)->first();
        // verificar contraseña

        if (!$user || !Hash::check($request->password, $user->password)) {
        
            return response()->json([
                'access_token' => false,
                'message' => 'Credenciales invalidas'
            ],401);
        }
        
        // crear token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ],200);
    }

    //Post/api/logout
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cierre de sesión exitoso',
        ],200);
    }
}       
    