<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'organization_id', 'port', 'price', 'ref_id', 'tracking_code', 'card_number', 'status', 'ip', 'payment_date'
    ];
}
