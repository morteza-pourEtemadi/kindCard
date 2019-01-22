<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    const STATUS_NOT_PAYED = 1;
    const STATUS_PAYED = 2;
    const STATUS_RECEIVED = 3;

    protected $fillable = [
        'telegram_id', 'name', 'last_name', 'username', 'code', 'activity', 'status'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
