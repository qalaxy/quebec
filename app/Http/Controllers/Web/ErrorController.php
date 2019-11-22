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
			if(count($request->all())){
				$errors = $this->ext->searchErrors($request->all());
			}else{				
				$errors = $this->ext->getPaginatedErrors();
			}
			if(is_object($errors)){
				if(View::exists('w3.index.errors'))
					if(count($errors))
						return view('w3.index.errors')->with(compact('errors'));
					else
						return view('w3.index.errors')->with(compact('errors'))
								->with('notification', array('indicator'=>'warning', 'message'=>'Errors(s) not found'));
				else{
					return back()->with('notification', $this->ext->missing_view);
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$errors));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view errors'));
		}
	}
	
	public function createError(){
		if(Auth::user()->can('create_errors')){
			$functions = $this->ext->getFunctions();
			if(!is_object($functions))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$functions));
			
			$stations = $this->ext->getStations();
			if(!is_object($stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));
			
			$status = $this->ext->getErrorStatus();
			if(!is_object($status))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$status));
			
			if(View::exists('w3.create.error')){
				return view('w3.create.error')->with(compact('functions', 'stations', 'status'))
							->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank. Use Chrome browser v20.0'));
			}else{
				return back()->with('notification', $this->missing_view);
			}
			
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create functional errors'));
		}
	}
	
	public function storeError(Request $request){
		if(Auth::user()->can('create_errors')){
			$validation = $this->ext->validateErrorData($request->all());
			if($validation->fails()){
				return redirect('create-error')
						->withErrors($validation)
						->withInput();
			}
			
			$function = $this->ext->getFunction($request['function_id']);
			if(!is_object($function))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$function)); 
			
			$station = $this->ext->getStation($request['station_id']);
			if(!is_object($station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations)); 
			
			$recipients = $this->ext->getNotificationRecipients($station);
			if(!is_object($recipients))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$recipients));
			
			$number = $this->ext->getErrorNumber($station->id, $function->id); 
			if(is_null($number))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$number));
			else
				$request['number'] = $number;
				
			$notification = $this->mnt->createError($request->all(), $function, $station, $recipients); 
			if(in_array('success', $notification)){
				if($request['responsibility'] == 0){
					$error_email = $this->ext->sendErrorNotificationEmail($notification['uuid'], $recipients); 
					if(is_string($error_email)) $notification['message'] .= '. '.$error_email;
				}			
					
				if(View::exists('w3.show.error'))
					return redirect('error/'.$notification['uuid'])->with(compact('notification'));
				else
					return back()->with('notification', $this->ext->missing_view)->withInput();
			}else{
				return back()->with(compact('notification'))->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create functional errors'));
		}
	}
	
	public function showError($error_uuid){ 
		if(Auth::user()->can('view_errors')){//4471f490-0ab2-11ea-aa54-1746b95626cb 8+1+4+1+4+1+4+1+12=36
		
			$uuid = (strlen($error_uuid) > 36)? decrypt($error_uuid): $error_uuid;
			
			$error = $this->ext->getError($uuid);
			if(!is_object($error)){
				if(strlen($uuid) > 36)
					return redirect('/')->with('notification', array('indicator'=>'warning', 'message'=>$error));
				else return back()->with('notification', array('indicator'=>'warning', 'message'=>$error));
			}
			
			if(View::exists('w3.show.error')){
				return view('w3.show.error')->with(compact('error'));
			}else{
				if(strlen($error_uuid) > 36)
					return redirect('/')->with('notification', $this->ext->missing_view);
				else return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			if(strlen($error_uuid) > 36)
				return redirect('/')->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view errors'));
			else 
				return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view errors'));
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
	
	public function addErrorProduct($uuid){
		if(Auth::user()->can('create_errors')){
			$func_error = $this->ext->getError($uuid);
			if(!is_object($func_error))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$func_error));
			
			if(View::exists('w3.create.affected-product')){
				return view('w3.create.affected-product')->with(compact('func_error'))
						->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank.'));
			}else{
				return back()->with('notification', $this->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add affected products for the errors'));
		}
	}
	
}
