<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    protected $table = 'table_payment';
    protected $fillable = [
        'fk_user_id','total_amt','payment_type',
    ];
}
