<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'thumbnail'
    ];
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('title', 'like', '%' . $searchTerm . '%')
                     ->orWhere('content', 'like', '%' . $searchTerm . '%');
    }

    public static function createBlog($data)
    {   
        $name = '';
        $file=null;
      
        if ($data->file('thumbnail')) {
            $file = $data->file('thumbnail');
            $name = $file->getClientOriginalName();
        }

        $blog = self::create([
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

    public static function updateBlog($id, array $data)
    {
        $blog = self::findOrFail($id);
        $blog->update($data);
        return $blog;
    }

    public static function deleteBlog($id)
    {
        $blog = self::findOrFail($id);
  
        $blog->delete();
       
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }
}
