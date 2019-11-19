<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use App\Shell\Web\Extension\ErrorExt;
use App\Shell\Web\Monitor\ErrorMnt;

class ErrorController extends Controller
{
    private $ext;
    private $mnt;
	
	public function __construct(){
		$this->ext = new ErrorExt();
		$this->mnt = new ErrorMnt();
	}
	
	public function errors(Request $request){
		if(Auth::user()->can('view_errors')){
			
		}else{
			abort(403);
		}
	}
	
	public function createError(){
		if(Auth::user()->can('create_errors')){
			
		}else{
			
		}
	}
	
	public function storeError(Request $request){
		if(Auth::user()->can('create_errors')){
			
		}else{
			
		}
	}
	
	public function editError($uuid){
		if(Auth::user()->can('edit_errors')){
			
		}else{
			
		}
	}
	public function updateError(Request $request, $uuid){
		if(Auth::user()->can('edit_errors')){
			
		}else{
			
		}
	}
	
	public function deleteError($uuid){
		if(Auth::user()->can('delete_errors')){
			
		}else{
			
		}
	}
	public function destroyError($uuid){
		if(Auth::user()->can('delete_errors')){
			
		}else{
			
		}
	}
	
}
