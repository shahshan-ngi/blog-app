<?php
namespace App\Services;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogService
{
    public function getAllBlogs(Request $request)
    {
        $blogs = Blog::with('user');

        if ($request->has('search')) {
            $blogs->search($request->search);
        }

        if ($request->has('categories')) {
            $selectedCategories = $request->input('categories');
            $blogs->whereHas('categories', function ($query) use ($selectedCategories) {
                $query->whereIn('categories.id', $selectedCategories);
            });
        }

        return $blogs->orderBy('created_at', 'desc')->get();
    }

    public function createBlog(Request $data)
    {
        $name = '';
        $file = null;

        if ($data->file('thumbnail')) {
            $file = $data->file('thumbnail');
            $name = $file->getClientOriginalName();
        }

        $blog = Blog::create([
            'user_id' => Auth::guard('sanctum')->user()->id,
            'title' => $data->title,
            'content' => $data->content,
            'thumbnail' => $name ? $name : null,
        ]);

        if ($file) {
            $file->storeAs("images/blogs/{$blog->id}", $name, 'public');
        }

        $blog->categories()->attach($data->categories);

        return $blog;
    }

    public function updateBlog($id, array $data)
    {
        $blog = Blog::findOrFail($id);
        $blog->update($data);

        return $blog;
    }

    public function deleteBlog($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
    }

    public function getBlogById($id)
    {
        return Blog::findOrFail($id);
    }

    public function getUserBlogs($userId)
    {
        $user = User::findOrFail($userId);
        return $user->blogs()->orderBy('created_at', 'asc')->get();
    }
}
