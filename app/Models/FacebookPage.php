<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FacebookPage extends Model
{
    protected $fillable = [
        'user_id',
        'page_id',
        'page_name',
        'category',
        'page_access_token',
        'profile_picture',
        'cover_photo',
        'status',
        'page_username',

        'likes', 'followers'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
