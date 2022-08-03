<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $table ="categories";
    protected $fillable =['name'];

    //category -> barbers relationship 
    public function barbers()
    {
        return $this->hasMany(barbers::class, 'category_id', 'id');
    }
}
