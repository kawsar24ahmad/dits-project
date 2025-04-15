<?php

namespace App\Models;

use Illuminate\Cache\Events\CacheEvent;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'offer_price',
        'category_id',
        'thumbnail',
        'is_active',
    ];
    public function category()  {
        return $this->belongsTo(Category::class);
    }

}
