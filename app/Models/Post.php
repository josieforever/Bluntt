<?php

namespace App\Models;

use App\Models\User;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use Searchable;
    use HasFactory;

    protected $fillable = ['title', 'body', 'user_id'];

    public function toSearchableArray() {
        return [
            'title'=>$this->title,
            'body'=>$this->body
        ];
    }

    /* so here we have to spell out the code for the relationship between a Post and a User */
    public function user() {
        /* we have to access the belongsTo static function in the Post class */
        return $this->belongsTo(User::class, 'user_id');
        /* the belongsTo static function accepts the User class as its first argument and the name of the column
        sponsoring the relationship as its second argument and gives you access to the user class fields*/
        /* this function basically uses the 'user_id' parameter to return to us the object whose id matches the user_id */
        /* nos in addition to the title, body, and user fields we can leverage the user method to access the user object and its fields */
    }
}
