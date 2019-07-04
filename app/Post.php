<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model 
{

    protected $table = 'posts';
    public $timestamps = true;
    protected $fillable = array('title', 'image', 'body');

    public function clients()
    {
        return $this->belongsToMany('App\Client');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

}