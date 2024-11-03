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
            Auth::login($user);
            $token = $user->createToken('auth-token')->plainTextToken;
            $cookies[] = cookie('auth_token', $token, 60 * 24, '/', null, false, false);
            $cookies[]= cookie('user_id',$user->id,60*24,'/',null,false,false);
    
            return success([
                'user' => $user,
                'auth_token' => $token
            ], 'Registered successfully', 201,$cookies);
        } catch (\Exception $e) {

            return error($e->getMessage(), 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user=User::where('email',$request->email)->first();
            if ( $user) {
                Auth::login($user);
                $token = $user->createToken('auth-token'); 
                
           
                $cookies[] = cookie('auth_token', $token->plainTextToken, 60 * 24, '/', null, false, false);
                $cookies[] = cookie('user_id', $user->id, 60 * 24, '/', null, false, false);
                
                return success([
                    'user' => $user,
                    'auth_token' => $token->plainTextToken
                ], 'Login successful.', 200, $cookies);
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
            $user = Auth::guard('sanctum')->user();
            $user->tokens()->delete();

            return success(null, 'Logged out successfully', 200);
        } catch (\Exception $e) {
    
            return error($e->getMessage(), 500);
        }
    }
    
    public function getUser(Request $request){
        try{
            $user=User::findorfail($request->cookie('user_id'));
            return success([
                'user' => ['id' => $user->id,
                    'profile_image' => $user->profile_image],
            ],'Login successful.', 200);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
