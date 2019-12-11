<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use App\Shell\Web\Extension\StationExt;
use App\Shell\Web\Monitor\StationMnt;

class StationController extends Controller
{
	private $ext;
	private $mnt;
	
    public function __construct(){
		$this->ext = new StationExt();
		$this->mnt = new StationMnt();
	}
	
	public function stations(Request $request){
		if(Auth::user()->can('view_stations')){
			if(count($request->all())){
				$stations = $this->ext->searchStations($request->all());
			}else{				
				$stations = $this->ext->getPaginatedStations();
			}
			if(is_object($stations)){
				if(View::exists('w3.index.stations'))
					if(count($stations))
						return view('w3.index.stations')->with(compact('stations'));
					else
						return view('w3.index.stations')->with(compact('stations'))
								->with('notification', array('indicator'=>'warning', 'message'=>'Station(s) not found'));
				else{
					return back()->with('notification', $this->ext->missing_view);
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view stations'));
		}
	}
	
	public function station($uuid){
		if(Auth::user()->can('view_stations')){
			$station = $this->ext->getStation($uuid);
			if(!is_object($station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$station));
			
			
			if(View::exists('w3.show.station')){
				return view('w3.show.station')
						->with(compact('station'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view stations'));
		}
	}
	
	public function addStationFunction($uuid){
		if(Auth::user()->can('create_stations')){
			$station = $this->ext->getStation($uuid);
			if(!is_object($station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$station));
			
			$functions = $this->ext->getUnaddedFunctions($station);
			if(!is_object($functions))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$functions));
			
			if(View::exists('w3.create.station-function')){
				if(count($functions)){
					return view('w3.create.station-function')
						->with(compact('station', 'functions'))
						->with('notification', array('indicator'=>'information', 'message'=>'All fields with * sholuld not be left blank'));
				}else{
					return view('w3.create.station-function')
						->with(compact('station', 'functions'))
						->with('notification', array('indicator'=>'warning', 'message'=>'All functions have been added to the station'));
				}
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add functions to the station'));
		}
	}
	
	public function storeStationFunction(Request $request, $uuid){
		if(Auth::user()->can('create_stations')){
			$validation = $this->ext->validateStationFunctionData($request->all());
			if($validation->fails()){
				return back()->withErrors($validation)
						->withInput()
						->with('notification', $this->ext->validation);
			}
			
			$station = $this->ext->getStation($uuid);
			if(!is_object($station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$station));
			
			$function = $this->ext->getFunction($request['function_id']);
			if(!is_object($function))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$function));
			
			$notification = $this->mnt->createStationFunction($station, $function);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.station'))
					return redirect('station/'.$uuid)->with(compact('notification'));
				else
					return back()->with('notification', $this->ext->missing_view);
			}else{
				return back()->with(compact('notification'))->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add functions to the station'));
		}
	}
	
	public function deleteStationFunction($stn_uuid, $func_uuid){
		if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('delete_stations')){
			
			$station = $this->ext->getStation($stn_uuid);
			
			$function = $this->ext->getFunction($func_uuid);
			if(is_object($station) && is_object($function)){
				return $this->ext->deleteStationFunction($station, $function);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to remove a funtion from a station'));
		}
	}
	
	public function destroyStationFunction($stn_uuid, $func_uuid){
		if(Auth::user()->can('delete_stations')){
			
			$station = $this->ext->getStation($stn_uuid);
			if(!is_object($station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$station));
			
			$function = $this->ext->getFunction($func_uuid);
			if(!is_object($function))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$function));
			
			$notification = $this->mnt->deleteStationFunction($station, $function);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.station'))
					return redirect('station/'.$stn_uuid)->with(compact('notification'));
				else
					return back()->with('notification', $this->ext->missing_view);
			}
			
			if(is_object($station) && is_object($function)){
				return $this->ext->deleteStationFunction($station, $function);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to remove a funtion from a station'));
		}
	}
	
	public function addStationRecipient($uuid){
		if(Auth::user()->can('create_stations')){
			$station = $this->ext->getStation($uuid); //return var_dump($station->name);
			if(!is_object($station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$station));
			
			$users = $this->ext->getUnaddedRecipients($station); //return var_dump($users->first()->name);
			if(!is_object($users))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$users));
			
			if(View::exists('w3.create.station-recipient')){
				if(count($users)){
					return view('w3.create.station-recipient')
						->with(compact('station', 'users'))
						->with('notification', array('indicator'=>'information', 'message'=>'All fields with * sholuld not be left blank'));
				}else{
					return view('w3.create.station-recipient')
						->with(compact('station', 'users'))
						->with('notification', array('indicator'=>'warning', 'message'=>'No recipient to be added to the station'));
				}
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add recipients to the station'));
		}
	}
	
	public function storeStationRecipient(Request $request, $uuid){
		if(Auth::user()->can('create_stations')){
			$validation = $this->ext->validateStationRecipientData($request->all());
			if($validation->fails()){
				return back()->withErrors($validation)
						->withInput()
						->with('notification', $this->ext->validation);
			}
			
			$station = $this->ext->getStation($uuid);
			if(!is_object($station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$station));
			
			$user = $this->ext->getUser($request['user_id']);
			if(!is_object($user))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$user));
			
			$notification = $this->mnt->createStationRecipient($station, $user);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.station'))
					return redirect('station/'.$uuid)->with(compact('notification'));
				else
					return back()->with('notification', $this->ext->missing_view);
			}else{
				return back()->with(compact('notification'))->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add recipient to the station'));
		}
	}
	
	public function deleteStationRecipient($stn_uuid, $user_uuid){
		if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('delete_stations')){
			
			$station = $this->ext->getStation($stn_uuid);
			
			$user = $this->ext->getUser($user_uuid);
			if(is_object($station) && is_object($user)){
				return $this->ext->deleteStationRecipient($station, $user);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to remove a recipient from a station'));
		}
	}
	
	public function destroyStationRecipient($stn_uuid, $user_uuid){
		if(Auth::user()->can('delete_stations')){
			
			$station = $this->ext->getStation($stn_uuid);
			if(!is_object($station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$station));
			
			$user = $this->ext->getUser($user_uuid);
			if(is_null($user))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$user));
			
			$recipient = $this->ext->getStationRecipient($station, $user);
			if(!is_object($recipient))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$recipient));
			
			$notification = $this->mnt->deleteStationRecipient($recipient);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.station'))
					return redirect('station/'.$stn_uuid)->with(compact('notification'));
				else
					return back()->with('notification', $this->ext->missing_view);
			}
			
			if(is_object($station) && is_object($function)){
				return $this->ext->deleteStationFunction($station, $user);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to remove a recipient from a station'));
		}
	}
	
}
