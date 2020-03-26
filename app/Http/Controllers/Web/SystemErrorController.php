<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use App\Shell\Web\Extension\SysErrorExt;
use App\Shell\Web\Monitor\SysErrorMnt;

class SystemErrorController extends Controller
{
	public $ext;
	public $mnt;

	public function __construct(){
		$this->ext = new SysErrorExt();
		$this->mnt = new SysErrorMnt();
	}
    public function systemErrors(Request $request){
    	if(Auth::user()->can('view_errors')){
			if(count($request->all())){
				$errors = $this->ext->searchSysErrors($request->all());
			}else{				
				$errors = $this->ext->getPaginatedSysErrors();
			}
			if(is_object($errors)){
				if(View::exists('w3.index.system-errors'))
					if(count($errors))
						return view('w3.index.system-errors')->with(compact('errors'));
					else
						return view('w3.index.system-errors')->with(compact('errors'))
								->with('notification', array('indicator'=>'warning', 'message'=>'System error(s) not found'));
				else{
					return back()->with('notification', $this->ext->missing_view);
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$errors));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view system errors'));
		}
    }

    public function createSystemError(){
    	if(Auth::user()->can('create_errors')){
    		$systems = $this->ext->getSystems();
    		if(!is_object($systems))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=>$systems));


    		$account_stations = $this->ext->getUserAccountStations(Auth::user()); //return var_dump($account_stations);
    		if(!is_object($account_stations))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=>$account_stations));

    		if(View::exists('w3.create.system-error')){
    			return view('w3.create.system-error')
    					->with(compact('systems', 'account_stations'))
    					->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
    		}else{
    			return back()->with('notification', $this->ext->missing_view);
    		}
    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create system errors'));
    	}
    }

    public function storeSystemError(Request $request){
    	if(Auth::user()->can('create_errors')){
    		$validation = $this->ext->validateSystemErrorData($request->all());
    		if($validation->fails())
    			return back()->withErrors($validation)->withInput()->with('notification', $this->ext->validation);

    		$station = $this->ext->getStation($request['station_id']);
    		if(!is_object($station))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=>$station));

    		$system = $this->ext->getSystem($request['system_id']);
    		if(!is_object($system))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=>$system));

    		$number = $this->ext->getNextSystemErrorNumber($station, $system);
    		if(!is_int($number))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=>$number));

    		$notification = $this->mnt->createSystemError($request->all(), $system, $station, $number);
    		if(in_array('success', $notification)){
    			if(View::exists('w3.show.system-error'))
    				return redirect('system-error/'.$notification['uuid'])->with(compact('notification'));
    			else
    				return back()->with(compact('notification'))->withInput();
    		}else{
    			return back()->with(compact('notification'))->withInput();
    		}
    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create system errors'));
    	}
    }

    public function systemError($uuid){
    	if(Auth::user()->can('view_errors')){
    		$system_error = $this->ext->getSystemError($uuid);
    		if(!is_object($system_error))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=>$system_error));

    		if(View::exists('w3.show.system-error')){
    			return view('w3.show.system-error')->with(compact('system_error'));
    		}else{
    			return back()->with('notification', $this->ext->missing_view);
    		}
    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view system errors'));
    	}
    }
}
