<?php 
namespace App\Shell\Web\Extension;

use Exception;
use App\Account;
use App\AccountStation;
use App\Role;
use App\Station;
use App\Supervisor;
use App\Email;
use App\User;

use App\Mail\FirstLogin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Shell\Web\Base;
use App\Shell\Data\AccountData;

class AccountExt extends Base{
	private $acc_data;
	
	public function __construct(){
		$this->acc_data = new AccountData();
	}
	
	public function sendFirstLoginEmail($uuid){
		try{
			$user = $this->getAccount($uuid)->user()->first();
			$email = Mail::to($user->email)->send(new FirstLogin($user));
			if($email){
				throw new Exception('First login email has not been sent successfully');
			}
			
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $email;
	}
	
	public function getUser($uuid){
		try{
			$user = User::withUuid($uuid)->first();
			if(is_null($user)){
				throw new Exception('User could not be found');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $user;
	}
	
	public function validateFirstLoginData(array $data){
		
		return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 
        					Rule::unique('users')
        						->where(function ($query) use($data) { 
        							$query->where('uuid', '<>',$data['uuid'])->whereNull('deleted_at');
        						})],

            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
	}
	
	public function searchAccounts(array $data){
		try{
			$accounts = Account::where($this->prepareSearchParam($data, ['first_name', 'middle_name', 'last_name', 'p_number']))->paginate($this->acc_data->rows);
			if(is_null($accounts)){
				throw new Exception('Accounts have not been retrieved successfully');
			}
			
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $accounts;
	}
	
	public function getPaginatedAccounts(){
		try{
			$accounts = Account::paginate($this->acc_data->rows);
			if(is_null($accounts)){
				throw new Exception('Accounts have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $accounts;
	}
	
	public function getStations(){
		try{
			$stations = Station::all();
			if(is_null($stations)){
				throw new Exception('Stations have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $stations;
	}
	
	public function validateAccountData(array $data){		
		$rules = [
				$this->acc_data->first_name_key => $this->acc_data->name_req,
				$this->acc_data->middle_name_key => $this->acc_data->optional_name_req,
				$this->acc_data->last_name_key => $this->acc_data->name_req,
				$this->acc_data->p_number_key => ['required', 'digits:9', 
						(isset($data['account_id'])? Rule::unique('accounts')
						->where(function($query) use($data){ 
									$query->where('uuid', '<>',$data['account_id'])->whereNull('deleted_at');//Magical query
								}) : Rule::unique('accounts')->where(function($query){
																	$query->whereNull('deleted_at');
																}))],
				$this->acc_data->phone_number_key => (isset($data['account_id'])?'':$this->acc_data->phone_number_req),
				$this->acc_data->email_key => (isset($data['account_id'])?'':['required', 'email', 
																Rule::unique('users')->where(function($query){
																	$query->whereNull('deleted_at');
																})]),
		];
		
		return Validator::make($data, $rules, $this->acc_data->validation_msgs);
	}
	
	public function getAccount($uuid){
		try{
			$account = Account::withUuid($uuid)->first();
			if(is_null($account)){
				throw new Exception('User account has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $account;
	}
	
	public function deleteAccount(Account $account){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Delete user account</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-8 w3-large">Your are about to delete a user\'s account:</p>
						<p><strong>Name:</strong> '.$account->first_name.' '
						.((strlen($account->middle_name) < 1) ? '' : $account->description).' '.$account->last_name
						.'<br /><strong>Personal Number:</strong> '.$account->p_number.'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-account').'/'.$account->uuid.'" 
								title="Delete user account">Delete&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
							</div>
						</div>
					</footer>
				</div>';
	}

	public function validateAccountCredentialsData(array $data){
		return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255',
            				Rule::unique('users')
        						->where(function ($query) use($data) { 
        							$query->where('uuid', '<>',$data['uuid'])->whereNull('deleted_at');
        						})
        				],
            'old_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], $this->acc_data->account_credentials_validation_msgs);

		return Validator::make($data, $rules, $this->acc_data->account_credentials_validation_msgs);
	}

	public function comparePassword(array $data, User $user){
		if($data['old_password'] == $data['password'])
			return 'The old and new passwords are the same. Enter a new password different from the old one.';

		if(!Hash::check($data['old_password'], $user->password))
			return 'You have entered wrong old password';
	}
	
	public function searchEmails(){
		try{
			$emails = Email::where($this->prepareSearchParam($data, ['first_name', 'middle_name', 'last_name', 'p_number']))->paginate($this->acc_data->rows);
			if(is_null($emails)){
				throw new Exception('Emails have not been retrieved successfully');
			}
			
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $emails;
	}
	
	public function getPaginatedEmails(){
		try{
			$emails = Email::paginate($this->acc_data->rows);
			if(is_null($emails)){
				throw new Exception('Emails have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $emails;
	}
	
	public function validateEmailData(array $data){
		$rules = [$this->acc_data->email_key => $this->acc_data->email_req];
		
		return Validator::make($data, $rules, $this->acc_data->validation_msgs);
	}
	
	public function getEmail($uuid){
		try{
			$email = Email::withUuid($uuid)->first();
			if(is_null($email)){
				throw new Exception('Email has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $email;
	}
	
	public function deleteEmail($account, $email){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Delete user\' email</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-middle w3-large">Your are about to delete a user\'s email:</p>
						<p><strong>User\'s name:</strong> '.$account->first_name.' '
						.((strlen($account->middle_name) < 1) ? '' : $account->middle_name).' '.$account->last_name
						.'</p><p><strong>Email address:</strong> '.$email->address.'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-email').'/'.$account->uuid.'/'.$email->uuid.'" 
								title="Delete user account">Delete&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
							</div>
						</div>
					</footer>
				</div>';
	}
	
	public function validateAccountStationData(array $data){
		$rules = [$this->acc_data->station_id_key => $this->acc_data->station_id_req,
				$this->acc_data->from_key => $this->acc_data->from_req,
				$this->acc_data->to_key => $this->acc_data->to_req,
				$this->acc_data->status_key => $this->acc_data->status_req,
		];
		
		return Validator::make($data, $rules, $this->acc_data->station_validation_msgs);
	}
	
	public function getStation($uuid){
		try{
			$station = Station::withUuid($uuid)->first();
			if(is_null($station)){
				throw new Exception('Station could not be retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $station;
	}
	
	public function getAccountStation($uuid){
		try{
			$acc_stn = AccountStation::withUuid($uuid)->first();
			if(is_null($acc_stn)){
				throw new Exception('Account station could not be retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $acc_stn;
	}
	
	public function deleteAccountStation($account, $stn){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Delete user\' station</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-middle w3-large">Your are about to delete a user\'s station:</p>
						<p><strong>User\'s name:</strong> '.$account->first_name.' '
						.((strlen($account->middle_name) < 1) ? '' : $account->middle_name).' '.$account->last_name
						.'</p><p><strong>Station:</strong> '.$stn->station()->distinct()->first()->name.'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-account-station').'/'.$account->uuid.'/'.$stn->uuid.'" 
								title="Delete user station">Delete&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
							</div>
						</div>
					</footer>
				</div>';
	}
	
	public function showAccountStation(Station $station){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-theme"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>User\' station</h2>
					</header>
					<div class="w3-container">
						
						<p><strong>User\'s name:</strong> '.$station->account()->first()->first_name.' '
						.((strlen($station->account()->first()->middle_name) < 1) ? '' : $station->account()->first()->middle_name).' '.$station->account()->first()->last_name
						.'</p>
						<p><strong>Station:</strong> '.$station->station()->distinct()->first()->name.'</p>
						<p><strong>From:</strong> '.date_format(date_create($station->from), 'd/m/Y').'</p>
						<p><strong>To:</strong> '.(($station->to)? date_format(date_create($station->to), 'd/m/Y'): date_format(date_create(today()), 'd/m/Y')).'</p>
						<p><strong>Status:</strong> '.(($station->status)? 'Active':'Inactive').'</p>
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
	
	public function searchAccountStations(array $data, Account $account){
		$data['station_id'] = (isset($data['station_id'])) ? $this->getStation($data['station_id'])->id : null;
		
		try{
			$accounts = $account->accountStation()->where($this->prepareSearchParam($data, ['station_id', 'from', 'to', 'status']))->paginate($this->acc_data->rows);
			if(is_null($accounts)){
				throw new Exception('Account stations have not been retrieved successfully');
			}
			
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $accounts;
	}
	
	public function getAccountStations(Account $account){
		try{
			$stations = $account->accountStation()->paginate($this->acc_data->rows);
			if(is_null($stations)){
				throw new Exception('Account stations have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $stations;
	}
	
	public function validateAccountSupervisoryData(array $data){
		$rules = [$this->acc_data->station_id_key => $this->acc_data->station_id_req,
				$this->acc_data->from_key => $this->acc_data->from_req,
				$this->acc_data->to_key => $this->acc_data->to_req,
				$this->acc_data->status_key => $this->acc_data->status_req,
		];
		
		return Validator::make($data, $rules, $this->acc_data->supervisory_validation_msgs);
	}
	
	public function getAccountSupervisory($uuid){
		try{
			$supervisory = Supervisor::withUuid($uuid)->first();
			if(is_null($supervisory)){
				throw new Exception('Supervisory has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $supervisory;
	}
	
	public function showAccountSupervisory($supervisory){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-theme"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>User\' station in supervision</h2>
					</header>
					<div class="w3-container">
						
						<p><strong>User\'s name:</strong> '.$supervisory->account()->first()->first_name.' '
						.((strlen($supervisory->account()->first()->middle_name) < 1) ? '' : $supervisory->account()->first()->middle_name)
						.' '.$supervisory->account()->first()->last_name.'</p>
						<p><strong>Station:</strong> '.$supervisory->station()->distinct()->first()->name.'</p>
						<p><strong>From:</strong> '.date_format(date_create($supervisory->from), 'd/m/Y').'</p>
						<p><strong>To:</strong> '.(($supervisory->to)? date_format(date_create($supervisory->to), 'd/m/Y'): date_format(date_create(today()), 'd/m/Y')).'</p>
						<p><strong>Status:</strong> '.(($supervisory->status)? 'Active':'Inactive').'</p>
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
	
	public function deleteAccountSupervisory(Account $account, Supervisor $supervisory){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Delete user\' station in supervision</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-middle w3-large">Your are about to delete a user\'s station in supervision:</p>
						<p><strong>User\'s name:</strong> '.$account->first_name.' '
						.((strlen($account->middle_name) < 1) ? '' : $account->middle_name).' '.$account->last_name
						.'</p><p><strong>Station:</strong> '.$supervisory->station()->distinct()->first()->name.'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-account-supervisory').'/'.$account->uuid.'/'.$supervisory->uuid.'" 
								title="Delete user station">Delete&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
							</div>
						</div>
					</footer>
				</div>';
	}
	
	public function searchAccountSupervisories(array $data, Account $account){
		$data['station_id'] = (isset($data['station_id'])) ? $this->getStation($data['station_id'])->id : null;
		
		try{
			$supervisories = $account->supervisor()->where($this->prepareSearchParam($data, ['station_id', 'from', 'to', 'status']))->paginate($this->acc_data->rows);
			if(is_null($supervisories)){
				throw new Exception('Account stations in supervision have not been retrieved successfully');
			}
			
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $supervisories;
	}
	
	public function getAccountSupervisories($account){
		try{
			$supervisories = $account->supervisor()->paginate($this->acc_data->rows);
			
			if(is_null($supervisories)){
				throw new Exception('Account stations in supervision have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $supervisories;
	}
	
	public function getUserStations($user){
		try{
			$stations = DB::table('users')
							->join('account_user', 'users.id', '=', 'account_user.user_id')
							->join('accounts', 'account_user.account_id', '=', 'accounts.id')
							->join('account_station', 'accounts.id', '=', 'account_station.account_id')
							->join('stations', 'account_station.station_id', '=', 'stations.id')
							->where('users.id', $user->id)
							->where('stations.deleted_at', null)
							->select('stations.*')
							->get();
							
			if(is_null($stations)){
				throw new Exception('Stations have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $stations;
	}
	
	public function getUnaddedRoles($user_stations, $user){
		try{
			if(Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('system_admin')){
				$first = DB::table('roles')
							->whereNotIn('roles.id', function ($query){
								$query->select(DB::raw('role_id'))
									  ->from('role_station');
							})
							->whereNotIn('roles.id', function ($query) use($user){
								$query->select(DB::raw('role_id'))
									  ->from('role_user')
									  ->whereRaw('role_user.user_id='.$user->id);
							})
							->where('roles.deleted_at', null)
							->select('roles.*');
				
				$roles = DB::table('roles')
							->join('role_station', 'roles.id', '=', 'role_station.role_id')
							->whereExists(function ($query) use($user_stations){
								$query->select(DB::raw('*'))
									  ->from('stations')
									  ->orWhere('role_station.station_id', $this->stationIds($user_stations));
							})
							->whereNotIn('roles.id', function ($query) use($user){
								$query->select(DB::raw('role_id'))
									  ->from('role_user')
									  ->whereRaw('role_user.user_id='.$user->id);
							})
							->where('roles.deleted_at', null)
							->select('roles.*')
							->union($first)
							->orderBy('id', 'desc')
							->get();
			}else{
				$roles = DB::table('roles')
							->join('role_station', 'roles.id', '=', 'role_station.role_id')
							->whereExists(function ($query) use($user_stations){
								$query->select(DB::raw('*'))
									  ->from('stations')
									  ->orWhere('role_station.station_id', $this->stationIds($user_stations));
							})->whereNotIn('roles.id', function ($query) use($user){
								$query->select(DB::raw('role_id'))
									  ->from('role_user')
									  ->where('role_user.user_id', $user->id);
							})
							->where('roles.deleted_at', null)
							->select('roles.*')
							->get();
			}
			if(is_null($roles)){
				throw new Exception('Roles have not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $roles;
	}
	
	public function validateAccountRoleData(array $data){
		$rules = [
			$this->acc_data->role_id_key => $this->acc_data->role_id_req,
		];
		
		return Validator::make($data, $rules, $this->acc_data->role_validation_msgs);
	}
	
	public function getRole($uuid){
		try{
			$role = Role::withUuid($uuid)->first();
			if(is_null($role)){
				throw new Exception('Role has not been retrieved successfully');
			}
		}catch(Exception $e){
			return $e->getMessage();
		}
		return $role;
	}
	
	public function deleteAccountrole($account, $role){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-red"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>Remove user\' role</h2>
					</header>
					<div class="w3-container">
						<p class="w3-padding-middle w3-large">Your are about to remove a user\'s role:</p>
						<p><strong>User\'s name:</strong> '.$account->first_name.' '
						.((strlen($account->middle_name) < 1) ? '' : $account->middle_name).' '.$account->last_name
						.'</p><p><strong>Role\'s name:</strong> '.$role->display_name.'</p>
					</div>
					<footer class="w3-container ">
						<div class="w3-row w3-padding-16">
							<div class="w3-col">
								<a class="w3-button w3-large w3-theme w3-hover-deep-orange" href="'.url('destroy-account-role').'/'.$account->uuid.'/'.$role->uuid.'" 
								title="Remove user\'s role">Remove&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
							</div>
						</div>
					</footer>
				</div>';
	}
	
	public function showAccountRole($role){
		return '<div class="w3-modal-content w3-animate-zoom w3-card-4">
					<header class="w3-container w3-theme"> 
						<span onclick="document.getElementById(\'delete\').style.display=\'none\'" 
						class="w3-button w3-display-topright">&times;</span>
						<h2>User\' role</h2>
					</header>
					<div class="w3-container">
						<p><strong>Name:</strong> '.$role->display_name.'</p>
						<p><strong>Description:</strong> '.$role->description.'</p>
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