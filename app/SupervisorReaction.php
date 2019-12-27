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
	
	protected $fillable = ['uuid', 'error_correction_id', 'status_id'];
	
	public function errorCorrection(){
		$this->belongsTo('App\ErrorCorrection', 'error_correction_id');
	}
	
	public function status(){
		$this->belongsTo('App\Status', 'status_id');
	}
	
	
}
