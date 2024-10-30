<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogViewController extends Controller
{
    // public function allowed(){
    //     return auth()->user()->hasAnyRole(['author','admin']);
    // }
    public function create()
    {   
        try{
            // if($this->allowed()){
               
                return view('blog.create');
            // }else{
            //     return redirect(route("blog.create"))->with('error', '403 Forbidden, you are not authorized to access this url');
            // }
            
        }catch(\Exception $e){
            return $e->getMessage();
            //return redirect(route("todos.index"))->with('error', '403 Forbidden,you are not authorized to access this url');
        }
      
    }

    public function edit(){
        try{
            return view('blog.update');
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
