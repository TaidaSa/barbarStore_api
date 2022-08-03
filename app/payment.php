<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    protected $table ="payment";
    protected $fillable =['post_id', 'user_id', 'data'];


    //users -> payments relationship 
    public function users()
    {
        return $this->belongsTo(users::class, '', 'id');
    }

    //posts -> payments relationship 
    public function posts()
    {
        return $this->belongsTo(posts::class, '', 'id');
    }
}
