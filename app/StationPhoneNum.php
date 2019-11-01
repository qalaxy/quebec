<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StationPhoneNum extends Model
{
    protected $table = 'station_phone_num';
	
	protected $fillable = ['station_id', 'phone_num_id'];
	
	protected $timestamps = false;
}
