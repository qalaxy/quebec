<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class OriginatorReaction extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'originator_reactions';
	
	protected $fillable = ['uuid', 'error_correction_id', 'aio_error_id', 'status', 'remarks'];
	
	public function errorCorrection(){
		$this->belongsTo('App\ErrorCorrection', 'error_correction_id');
	}
	
	public function aioError(){
		$this->belongsTo('App\AioError', 'aio_error_id');
	}
}
