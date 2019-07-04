<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model 
{

    protected $table = 'blood_types';
    public $timestamps = true;
    protected $fillable = array('blood_type');

    public function clientss()
    {
        return $this->belongsToMany('App\Client');
    }

    public function clients()
    {
        return $this->hasMany('App\Client');
    }

    public function donation_requests()
    {
        return $this->hasMany('App\DonationRequest');
    }

}