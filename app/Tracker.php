<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tracker extends Model
{
    protected $table = 'trackers';
	
	protected $fillable = ['uuid', 'user_id', 'action', 'date', 'time'];
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
}
