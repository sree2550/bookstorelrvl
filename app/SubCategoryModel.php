<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategoryModel extends Model
{
    protected $table = 'sub_category';
    protected $fillable = [
        'sub_catid', 'fk_subcat_id','subcategory_name',
    ];
}
