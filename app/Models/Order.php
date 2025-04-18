<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'name',
        'email',
        'phone',
        'currency',
        'status',
        'total_amount',
        'transaction_id',
        'payment_time',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
