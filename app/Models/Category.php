<?php

namespace App\Models;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';
    protected $fillable=[
        'name'
    ];
    public function blogs(){
        return $this->belongsToMany(Blog::class);
    }
}
