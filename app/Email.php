<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class Email extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'emails';
	
	protected $fillable = ['uuid', 'address'];
	
	public function account(){
		return $this->belongsToMany('App\Account', 'account_email', 'email_id', 'account_id');
	}
	
	public function station(){
		return $this->belongsToMany('App\Station', 'station_email', 'email_id', 'station_id');
	}
}
