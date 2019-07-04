<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model 
{

    protected $table = 'tokens';
    public $timestamps = true;
    protected $fillable = array('platform', 'client_id','token');


    public function client()
    {
        return $this->belongsTo('App\Client');
    }

}