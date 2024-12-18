<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Blog;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function blogs(){
        return $this->hasMany(Blog::class);
    }

    public static function createUser($data){
       
        if($data->file('profile_image')->isvalid()){
            $file=$data->file('profile_image');
            $name=$file->getClientOriginalName();
            $user = self::create([
                'name'=>$data->name,
                'email'=>$data->email,
                'password'=>$data->password,
                'profile_image'=>$name
            ]);
            $filepath=$data->file('profile_image')->storeAs("images/profile/{$user->id}",$name,'public');
            $user->profile_image=$name;
            $user->assignRole('author');
            $user->save();
            return $user;
        }else{
            return [];
        }
       
    }
}
