<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginModel extends Model
{
    protected $table = 'user_login';
    protected $fillable = [
        'login_id', 'email', 'password', 'usertype','user_status',
    ];
}
