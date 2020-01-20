<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseModel extends Model
{
    protected $table = 'purchase';
    protected $fillable = [
        'fk_book_id','fk_user_id','quantity','status',
    ];
}
