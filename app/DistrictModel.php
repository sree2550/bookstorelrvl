<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DistrictModel extends Model
{
    protected $table = 'district';
    protected $fillable = [
        'district_id', 'district_name',
    ];
}
