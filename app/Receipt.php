<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    const STATUS_NOT_PAYED = 1;
    const STATUS_PAYED = 2;
    const STATUS_CANCELED = 3;

    protected $fillable = [
        'user_id', 'transactions', 'last_transaction_id', 'price', 'status', 'payed_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
