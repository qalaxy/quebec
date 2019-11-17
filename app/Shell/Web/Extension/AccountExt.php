<?php 
namespace App\Shell\Web\Extension;

use Exception;
use App\Account;
use App\AccountStation;
use App\Station;
use App\Email;

use Illuminate\Support\Facades\Validator;
use App\Shell\Web\Base;
use App\Shell\Data\AccountData;

class AccountExt extends Base{
	private $acc_data;
	
	public function __construct(){
		$this->acc_data = new AccountData();
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
				$this->acc_data->p_number_key => $this->acc_data->p_number_req,
				$this->acc_data->phone_number_key => $this->acc_data->phone_number_req,
				$this->acc_data->email_key => $this->acc_data->email_req,
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
	
	public function deleteAccount(object $account){
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
		
		return Validator::make($data, $rules, $this->acc_data->validation_msgs);
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
	
	public function showAccountStation(object $station){
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
	
	public function searchAccountStations(array $data, object $account){
		$data['station_id'] = (isset($data['station_id'])) ? $this->getStation($data['station_id']) : null;
		
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
	
	public function getAccountStations(object $account){
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
	
}

?>