<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartModel extends Model
{
    protected $primaryKey = 'cart_id';
    protected $table = 'cart';
    protected $fillable = [
        'fk_book_id','fk_user_id','quantity','status',
    ];
}
