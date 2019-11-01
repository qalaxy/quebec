<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class Recipient extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'recipients';
	
	protected $fillable = ['uuid','station_id','user_id'];
	
	public function station(){
		return $this->belongsTo('App\Station', 'station_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
}
