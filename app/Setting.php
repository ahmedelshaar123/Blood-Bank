<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model 
{

    protected $table = 'settings';
    public $timestamps = true;
    protected $fillable = array('phone', 'email', 'using_instructions', 'about_app', 'app_url', 'facebook_url', 'twitter_url', 'youtube_url', 'instagram_url', 'whatsapp_url', 'googleplus_url');

}