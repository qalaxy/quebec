<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErrorCorrectionStatus extends Model
{	
	protected $table = 'error_correction_status';
	
	protected $fillable = ['error_correction_id','status_id'];
	
	protected $timestamps = false;
	
}
