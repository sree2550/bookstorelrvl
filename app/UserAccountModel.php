<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAccountModel extends Model
{
    protected $table = 'user_account';
    protected $fillable = [
        'fk_login_id','username','address','gender','country','state','district','pincode',
        'contact_no','status',
    ];
}
