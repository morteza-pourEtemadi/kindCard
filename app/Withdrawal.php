<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    const STATUS_NOT_PAYED = 1;
    const STATUS_PAYED = 2;
    const STATUS_CANCELED = 3;

    protected $fillable = [
        'user_id', 'category_id', 'price', 'card_number', 'full_name', 'status', 'tracking_code', 'payed_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
