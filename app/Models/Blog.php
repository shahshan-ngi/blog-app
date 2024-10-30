<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'thumbnail'
    ];

    public static function createBlog($data)
    {   
        $blog = self::create([
            'user_id'=>Auth::User()->id,
            'title' => $data->title,
            'content' => $data->content,
            'thumbnail'=>$data->thumbnail??null
        ]);
        
        
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
}
