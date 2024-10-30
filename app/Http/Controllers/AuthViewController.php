<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthViewController extends Controller
{
    public function loginform(){
        try{
            return view('auth.login');
        }catch(\Exception $e){
            return redirect(route("login"))->with('error', 'Something went wrong');
           
        }
    }
    public function registerform(){
        try{
            return view('auth.register');
        }catch(\Exception $e){
            dd($e->getMessage());
        }
    }
}
