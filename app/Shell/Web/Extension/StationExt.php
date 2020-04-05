<?php 
namespace App\Shell\Web\Extension;

use Exception;
use App\Account;
use App\Func;
use App\Station;
use App\Supervisor;
use App\User;

use App\Shell\Web\Base;
use App\Shell\Data\StationData;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StationExt extends Base{
	private $stn_data;
	
	public function __construct(){
		$this->stn_data = new StationData();
	}
	
	public function searchStations(array $data){
		try{
			$stations = Station::where($this->prepareSearchParam($data, ['name', 'abbreviation']))->paginate($this->stn_data->rows);
			if(is_null($stations)){
				throw new Exception('Stations have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $stations;
	}
	
	public function getPaginatedStations(){
		try{
			$stations = Station::paginate($this->stn_data->rows);
			if(is_null($stations)){
				throw new Exception('Stations have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $stations;
	}
	
	public function getStation($uuid){
		try{
			$station = Station::withUuid($uuid)->first();
			if(is_null($station)){
				throw new Exception('Station has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $station;
	}
	
	public function getUnaddedFunctions(Station $station){
		try{
			$functions = DB::table('functions')
								->whereNotIn('id', function($query)use($station){
									$query->select(DB::raw('function_id'))
											->from('station_function')
											->whereRaw('station_function.station_id='.$station->id);
								})
								->get();
			if(is_null($functions)){
				throw new Exception('AIS functions have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $functions;
	}
	
	public function validateStationFunctionData(array $data){
		$rules = [
			$this->stn_data->function_id_key => $this->stn_data->function_id_req,
		];
		
		return Validator::make($data, $rules, $this->stn_data->station_function_validation_msgs);
	}
	
	public function getFunction(string $uuid){
		try{
			$function = Func::withUuid($uuid)->first();
			if(is_null($function)){
				throw new Exception('AIS function has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $function;
	}
	
	public function deleteStationFunction(Station $station, Func $function){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Remove station\'s function</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-middle w3-large">Your are about to remove a function from a station:</p>
						<p><strong>Station\'s name:</strong> '.$station->name.'</p>
						<p><strong>Function\'s name:</strong> '.$function->name.'</p>
						<p>'.(($function->description)? '<strong>Function description:</strong>'.$function->description : null).'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-station-function').'/'.$station->uuid.'/'.$function->uuid.'" 
								title="Remove user\'s role">Remove&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
							</div>
						</div>
					</footer>
				</div>';
	}
	
	public function getUnaddedRecipients(Station $station){
		try{
			$users = DB::table('users')
								->join('account_user', 'users.id', '=', 'account_user.user_id')
								->join('accounts', 'account_user.account_id', '=', 'accounts.id')
								->join('account_station', 'accounts.id', '=', 'account_station.account_id')
								->whereNotIn('users.id', function($query)use($station){
									$query->select(DB::raw('user_id'))
											->from('recipients')
											->whereRaw('recipients.station_id='.$station->id)
											->where('recipients.deleted_at', null);
								})
								->where('account_station.station_id', $station->id)
								->where('users.deleted_at', null)
								->select('users.*')
								->get();
								
			if(is_null($users)){
				throw new Exception('AIS recipients have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $users;
	}
	
	public function validateStationRecipientData(array $data){
		$rules = [
			$this->stn_data->user_id_key => $this->stn_data->user_id_req,
		];
		
		return Validator::make($data, $rules, $this->stn_data->station_user_validation_msgs);
	}
	
	public function getUser(string $uuid){
		try{
			$user = User::withUuid($uuid)->first();
			if(is_null($user)){
				throw new Exception('User has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $user;
	}
	
	public function deleteStationRecipient($station, $user){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Remove station\'s error notification recipient</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-middle w3-large">Your are about to remove a notification recipient from a station:</p>
						<p><strong>Station\'s name:</strong> '.$station->name.'</p>
						<p><strong>User\'s name:</strong> '.$user->name.'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-station-recipient').'/'.$station->uuid.'/'.$user->uuid.'" 
								title="Remove user\'s role">Remove&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
							</div>
						</div>
					</footer>
				</div>';
	}
	
	public function getStationRecipient(Station $station, User $user){
		try{
			$recipient = $station->recipient()->first()->where('user_id', $user->id)->first();
			if(is_null($recipient)){
				throw new Exception('Recipient has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $recipient;
	}

	public function getUnaddedSupervisors(Station $station, $account=null){
		try{
			if($account){
				$accounts = DB::table('accounts')
								->join('account_station', 'accounts.id', '=', 'account_station.account_id')
								->join('stations', 'account_station.station_id', '=', 'stations.id')
								->whereNotIn('accounts.id', function($query) use ($station, $account){
									$query->select(DB::raw('account_id'))
										->from('supervisors')
										->where('supervisors.station_id', $station->id)
										->whereNull('supervisors.deleted_at')
										->where('supervisors.account_id', '<>', $account->id);
								})
								->where('account_station.station_id', $station->id)
								->whereNull('account_station.deleted_at')
								->select('accounts.*')
								->get();
			}else{
				$accounts = DB::table('accounts')
								->join('account_station', 'accounts.id', '=', 'account_station.account_id')
								->join('stations', 'account_station.station_id', '=', 'stations.id')
								->whereNotIn('accounts.id', function($query) use ($station){
									$query->select(DB::raw('account_id'))
										->from('supervisors')
										->whereRaw('supervisors.station_id='.$station->id)
										->whereNull('supervisors.deleted_at');
								})
								->where('account_station.station_id', $station->id)
								->whereNull('account_station.deleted_at')
								->select('accounts.*')
								->get();
			}
								
			if(is_null($accounts)){
				throw new Exception('Station officers have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $accounts;
	}

	public function validateStationSupervisorData(array $data){
		$rules = [
			$this->stn_data->account_id_key => $this->stn_data->user_id_req,
			$this->stn_data->status_key => $this->stn_data->status_req,
			$this->stn_data->from_key => $this->stn_data->from_req,
			$this->stn_data->to_key => $this->stn_data->to_req,
		];

		return Validator::make($data, $rules, $this->stn_data->station_supervisor_validation_msgs);		
	}

	public function getAccount($uuid){
		try{
			$account = Account::withUuid($uuid)->first();
			if(is_null($account)){
				throw new Exception('Account has not been retrieved successfully');				
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $account;
	}

	public function deleteStationStation(Station $station, Account $account){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Delete station\'s supervisor</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-middle w3-large">Your are about to delete a supervisor from a station:</p>
						<p><strong>Station\'s name:</strong> '.$station->name.'</p>
						<p><strong>User\'s name:</strong> '.$account->user()->first()->name.'</p>
						<p><strong>Status:</strong> '.(boolval(($station->supervisor()->where('account_id', $account->id)->first()->status)) ? 'Active' : 'Inactive').'</p>
						<p><strong>From:</strong> '
							.date_format(date_create($station->supervisor()->where('account_id', $account->id)->first()->from), 'd/m/Y H:i:s')
							.'</p>
						<p><strong>To:</strong> '
							.date_format(date_create($station->supervisor()->where('account_id', $account->id)->first()->to), 'd/m/Y H:i:s')
							.'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-station-supervisor').'/'.$station->uuid.'/'.$account->uuid.'" 
								title="Delete supervisor from the station">Delete&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
							</div>
						</div>
					</footer>
				</div>';
	}

	public function getStationSupervisor(Station $station, Account $account){
		try{
			$supervisor = $station->supervisor()->where('account_id', $account->id)->first();
			if(is_null($supervisor)){
				throw new Exception('Supervisor has not been retrieved successfully');
				
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $supervisor;
	}

	public function getSupervisor(string $uuid){
		try{
			$supervisor = Supervisor::withUuid($uuid)->first();
			if(is_null($supervisor)){
				throw new Exception('Supervisory has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $supervisor;
	}

	public function showStationSupervisor(Supervisor $supervisor){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-theme"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Station\'s supervisor</h2>
					</header>
					<div class="w3-container">
						
						<p><strong>User\'s name:</strong> '.$supervisor->account()->first()->user()->first()->name.'</p>
						<p><strong>Station:</strong> '.$supervisor->station()->distinct()->first()->name.'</p>
						<p><strong>From:</strong> '.date_format(date_create($supervisor->from), 'd/m/Y').'</p>
						<p><strong>To:</strong> '.(($supervisor->to)? date_format(date_create($supervisor->to), 'd/m/Y'): date_format(date_create(today()), 'd/m/Y')).'</p>
						<p><strong>Status:</strong> '.(($supervisor->status)? 'Active':'Inactive').'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<button class="w3-button w3-large w3-theme w3-hover-light-blue" title="Dismiss" onclick="document.getElementById(\'delete\').style.display=\'none\'">OK&nbsp;</button>
							</div>
						</div>
					</footer>
				</div>';
	}
}
?>