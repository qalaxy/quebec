<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageResponse extends Model
{
    protected $table = 'message_response';
	
	protected $fillable = ['message_id', 'response_id'];
	
	protected $timestamps = false; 
}
