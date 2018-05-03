<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }
}
