<?php 
namespace App\Shell\Web\Extension;

use Exception;
use App\Account;
use App\Station;

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
						.((strlen($account->middle_name) < 1) ? '' : $role->description).' '.$account->last_name
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
	
	public function validateEmailData(){
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
						<p class="w3-padding-8 w3-large">Your are about to delete a user\'s email:</p>
						<p><strong>User\'s name:</strong> '.$account->first_name.' '
						.((strlen($account->middle_name) < 1) ? '' : $role->description).' '.$account->last_name
						.'<br /><strong>Email address:</strong> '.$email->address.'</p>
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
	
}

?>