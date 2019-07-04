<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Client extends Authenticatable
{

    protected $table = 'clients';
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'date_of_birth', 'blood_type_id', 'last_date_of_donation', 'city_id', 'phone', 'password', 'is_active', 'pin_code');
    protected $hidden = ['api_token', 'password'];

    public function governorates()
    {
        return $this->belongsToMany('App\Governorate');
    }

    public function blood_types()
    {
        return $this->belongsToMany('App\BloodType');
    }

    public function posts()
    {
        return $this->belongsToMany('App\Post');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function blood_type()
    {
        return $this->belongsTo('App\BloodType');
    }

    public function notifications()
    {
        return $this->belongsToMany('App\Notification');
    }

    public function tokens()
    {
        return $this->hasMany('App\Token');
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }

    public function donation_requests()
    {
        return $this->hasMany('App\DonationRequest');
    }

}