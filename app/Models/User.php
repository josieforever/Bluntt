<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Follow;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    public function feedPosts() {
        return $this->hasManyThrough( Post::class, Follow::class, 'user_id', 'user_id', 'id', 'followeduser');
    }

    public function followers() {
        return $this->hasMany(Follow::class, 'followeduser'); //where ever the followeduser matches the user id
    }
    public function following() {
        return $this->hasMany(Follow::class, 'user_id');
    }

    public function posts() {
        return $this->hasMany(Post::class, 'user_id');
    }

    protected function avatar(): Attribute {
        return Attribute::make(get: function($value) {
            if ($value) {
                return "/storage/avatars/{$value}";
            } else {
                return "/storage/fallback/default-monster.jpg";
            }

        });
    }

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
}
