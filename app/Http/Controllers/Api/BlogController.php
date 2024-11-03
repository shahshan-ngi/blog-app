<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BlogCollection;

class BlogController extends Controller
{   
    public function allowed(){

        $user=Auth::guard('sanctum')->user();
        return true; //$user->hasAnyRole(['author','admin']);
    }
    public function index(Request $request){
        try {
            $blogs = Blog::with('user');
            if ($request->has('search')) {
                $blogs->search($request->search); 
            }
            // if ($request->has('categories')) {
            //     $selectedCategories = $request->input('categories');
            //     $blogs->with('categories')
            //         ->whereHas('categories', function ($query) use ($selectedCategories) {
            //             $query->whereIn('categories.id', $selectedCategories);
            //         });
            // }
    
            $blogCollection = $blogs->orderBy('created_at', 'asc')->get();
            
       
    
   
    
            return success(['blogs' =>new BlogCollection($blogCollection)], 'blogs retrieved successfully');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            if ($this->allowed()) {
                $blog = Blog::createBlog($request);
               
                 return success($blog, 'Task created successfully',201);
            } else {
                return forbidden(); 
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    public function update($id, StoreRequest $request)
    {
        try {
            if ($this->allowed()) {
                $blog = Blog::updateBlog($id, $request->all());
                return success($blog, 'blog updated successfully', 200);
            } else {
           
                return forbidden();
            }
        } catch (\Exception $e) {
          
            return error($e->getMessage());
        }
    }

    public function destroy($id){
        try {
            if ($this->allowed()) {
                Blog::deleteBlog($id);
    
                return success(null, 'Blog deleted successfully', 200);
            } else {
             
                return forbidden();
            }
        } catch (\Exception $e) {
        
            return error($e->getMessage());
        }
    }

    public function show($id){
        try{
            $blog=Blog::findorfail($id);
            return success(new BlogResource($blog), 'blog reutrned successfully', 200);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function myblogs(Request $request){
        try {
            $page=$request->page;
            $user = User::findOrFail($request->cookie('user_id'));

            $blogs = $user->blogs()->skip($skip)->take(5)->orderBy('created_at', 'desc')->get();
            
      
            return success(['blogs' => new BlogCollection($blogs)], 'Blogs returned successfully', 200);
    
        } catch (\Exception $e) {
          
            return error($e->getMessage());
        }
    }
    
}
