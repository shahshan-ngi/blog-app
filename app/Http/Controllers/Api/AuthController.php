<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterFormRequest;



class AuthController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        try {
            $request->password = Hash::make($request->password);
            $user = User::createUser($request);
            $token = $user->createToken('auth-token')->plainTextToken;
    
            return success([
                'user' => $user,
                'auth_token' => $token
            ], 'Registered successfully', 201);
        } catch (\Exception $e) {

            return error($e->getMessage(), 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $token = $user->createToken('auth-token');
                return success([
                    'user' => $user,
                    'auth_token' => $token->plainTextToken
                ],'Login successful.', 200);
            } else {
                return unauthorized('Invalid credentials.');
            }
        } catch (\Exception $e) {
         
            return error($e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            $user->tokens()->delete();

            return success(null, 'Logged out successfully', 200);
        } catch (\Exception $e) {
    
            return error($e->getMessage(), 500);
        }
    }
    
    public function getUser($id){
        try{
            $user=User::findorfail($id);
            return success([
                'user' => ['id' => $user->id,
                    'profile_image' => $user->profile_image],
            ],'Login successful.', 200);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
