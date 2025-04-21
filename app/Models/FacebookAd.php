<?php

namespace App\Models;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Model;

class FacebookAd extends Model
{
    protected $fillable = [
        'user_id',
        'wallet_transaction_id',
        'page_link',
        'budget',
        'duration',
        'min_age',
        'max_age',
        'location',
        'button',
        'greeting',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function walletTransaction()
    {
        return $this->belongsTo(WalletTransaction::class);
    }

}
