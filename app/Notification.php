<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model 
{

    protected $table = 'notifications';
    public $timestamps = true;
    protected $fillable = array('title', 'body', 'donation_request_id','client_id');

    public function clients()
    {
        return $this->belongsToMany('App\Client');
    }

    public function donation_request()
    {
        return $this->belongsTo('App\DonationRequest');
    }

}