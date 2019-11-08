<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class FuncError extends Controller
{
    public function __construct(){
		$this->middleware('auth');
	}
	
	
	public function createError(Request $request){
		if(View::exists('w3.create.error')){
			return view('w3.create.error');
		}
	}
}
