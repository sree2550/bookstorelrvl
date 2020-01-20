<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookModel extends Model
{
    protected $primaryKey = 'book_id';

    protected $table = 'book';
    protected $fillable = [
        'book_name','cat_id','description','pub_name','price','quantity','discount','book_image','stock',
];

}
