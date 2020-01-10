<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class SupervisorReaction extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'supervisor_reactions';
	
	protected $fillable = ['uuid', 'user_id', 'error_correction_id', 'status', 'remarks'];
	
	public function errorCorrection(){
		return $this->belongsTo('App\ErrorCorrection', 'error_correction_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
	
}
