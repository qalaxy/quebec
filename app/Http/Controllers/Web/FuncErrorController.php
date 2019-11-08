<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class FuncErrorController extends Controller
{
    public function __construct(){
		$this->middleware('auth');
	}
	
	
	public function createError(Request $request){
		if(View::exists('w3.create.error')){
			return view('w3.create.error')->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
		}
	}
}
