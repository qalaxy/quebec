<?php 
namespace App\Shell\Web\Extension;

use Exception;
use App\Func;
use App\Station;
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
	
	public function getUnaddedFunctions(object $station){
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
	
	public function deleteStationFunction(object $station, object $function){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Remove station\' function</h2>
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
	
	public function getUnaddedRecipients(object $station){
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
	
	public function getStationRecipient(object $station, object $user){
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
}
?>