<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model 
{

    protected $table = 'categories';
    public $timestamps = true;
    protected $fillable = array('title');

    public function posts()
    {
        return $this->belongsToMany('App\Post');
    }

}