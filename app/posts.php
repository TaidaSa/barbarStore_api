<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class posts extends Model
{

    protected $table ="posts";
    protected $fillable =['price', 'description', 'image', 'status', 'barber_id'];


    //barbers -> posts relationship 
    public function barbers()
    {
        return $this->belongsTo(barbers::class, '', 'id');
    }

    //posts -> payments relationship 
    public function payment()
    {
        return $this->hasMany(payment::class, 'post_id', 'id');
    }
}
