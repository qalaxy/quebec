<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StationEmail extends Model
{
    protected $table = 'station_email';
	
	protected $fillable = ['station_id', 'email_id'];
	
	protected $timestamps = false;
}
