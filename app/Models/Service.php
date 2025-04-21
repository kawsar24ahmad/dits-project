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
        'icon',
        'type',
        'form_fields_json',
        'view_path',
        'external_link'
    ];


    public function category()  {
        return $this->belongsTo(Category::class);
    }

}
