<?php

namespace App\Http\Controllers\Api;


use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\BlogService;
use App\Http\Requests\StoreRequest;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BlogCollection;
use Illuminate\Support\Facades\Session;

class BlogController extends Controller
{   
    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

   
    public function allowed(){

        $user=Auth::guard('sanctum')->user();
        return $user->hasAnyRole(['author','admin']);
    }
   
    public function index(Request $request)
    {
        try {
            $blogs = $this->blogService->getAllBlogs($request);
            return success(['blogs' => new BlogCollection($blogs)], 'Blogs retrieved successfully');
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            if ($this->allowed()) {
                $blog = $this->blogService->createBlog($request);
                return success($blog, 'Blog created successfully', 201);
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
                $blog = $this->blogService->updateBlog($id, $request->all());
                return success($blog, 'Blog updated successfully', 200);
            } else {
                return forbidden();
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            if ($this->allowed()) {
                $this->blogService->deleteBlog($id);
                return success(null, 'Blog deleted successfully', 200);
            } else {
                return forbidden();
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            if ($this->allowed()) {
                $blog = $this->blogService->getBlogById($id);
                return success(new BlogResource($blog), 'Blog retrieved successfully', 200);
            } else {
                return forbidden();
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }

    public function myblogs(Request $request)
    {
        try {
            if ($this->allowed()) {
                $userId = $request->cookie('user_id');
                $blogs = $this->blogService->getUserBlogs($userId);
                return success(['blogs' => new BlogCollection($blogs)], 'Blogs retrieved successfully', 200);
            } else {
                return forbidden();
            }
        } catch (\Exception $e) {
            return error($e->getMessage());
        }
    }
   
    
}
