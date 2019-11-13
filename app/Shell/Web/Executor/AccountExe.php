<?php 
namespace App\Shell\Web\Executor;

use Exception;
use App\Account;
use App\Email;
use App\PhoneNumber;
use App\User;
use Uuid;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Shell\Web\Base;
use App\Shell\Data\AccountData;

class AccountExe extends Base{
	private $acc_data;
	protected $data = array();
	
	public function __construct(){
		$this->acc_data = new AccountData();
	}
	
	protected function storeUser(){
		try{
			$user = User::firstOrCreate(array($this->acc_data->email_key=>$this->data[$this->acc_data->email_key]), 
						array('uuid'=>Uuid::generate(),
							$this->acc_data->name_key=>$this->data[$this->acc_data->first_name_key],
							$this->acc_data->email_key=>$this->data[$this->acc_data->email_key],
							$this->acc_data->password_key=>Hash::make(Str::random(8)),
							$this->acc_data->status_key=>1,
						));
						
			if(is_null($user)){
				throw new Exception('User has not been created successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $user;
	}
	
	protected function storeAccount($user){
		try{
			$account = Account::firstOrCreate(array($this->acc_data->p_number_key => $this->data[$this->acc_data->p_number_key]), 
							array('uuid'=>Uuid::generate(),
									$this->acc_data->user_id_key => Auth::id(),
									$this->acc_data->first_name_key => $this->data[$this->acc_data->first_name_key],
									$this->acc_data->middle_name_key => $this->data[$this->acc_data->middle_name_key],
									$this->acc_data->last_name_key => $this->data[$this->acc_data->last_name_key],
									$this->acc_data->p_number_key => $this->data[$this->acc_data->p_number_key],
								)
							);
			if(is_null($account)){
				throw new Exception('Account has not been created successfully');
			}else{
				$user->account()->attach($account);
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $account;
	}
	
	protected function storePhoneNumber($account){
		try{
			$phone_number = PhoneNumber::firstOrCreate(array($this->acc_data->number_key=>$this->data[$this->acc_data->phone_number_key]), 
						array('uuid'=>Uuid::generate(),
								$this->acc_data->number_key => $this->data[$this->acc_data->phone_number_key]
							)
						);
			if(is_null($phone_number)){
				throw new Exception('Phone number has not been created successfully');
			}else{
				$account->phoneNumber()->attach($phone_number);
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $phone_number;
	}
	
	protected function storeEmail($account){
		try{
			$email = Email::firstOrCreate(array($this->acc_data->address_key=>$this->data[$this->acc_data->email_key]), 
						array('uuid'=>Uuid::generate(),
								$this->acc_data->address_key => $this->data[$this->acc_data->email_key],
							)
						);
						
			if(is_null($email)){
				throw new Exception('Email has not been created successfully');
			}else{
				$account->email()->attach($email);
				$this->success = array('indicator'=>'success', 'message'=>'User account has been created successfully', 'uuid'=>$account->uuid);
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $email;
	}
	
	protected function updateUser($account){
		try{
			$user = $account->update(array($this->acc_data->name_key=>$this->data[$this->acc_data->first_name_key]));
			if(is_null($user)){
				throw new Exception('User has not been edited successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $user;
	}
	
	protected function updateAccount($account){
		try{
			$acc = $account->update(array($this->acc_data->user_id_key => Auth::id(),
									$this->acc_data->first_name_key => $this->data[$this->acc_data->first_name_key],
									$this->acc_data->middle_name_key => $this->data[$this->acc_data->middle_name_key],
									$this->acc_data->last_name_key => $this->data[$this->acc_data->last_name_key],
									$this->acc_data->p_number_key => $this->data[$this->acc_data->p_number_key],
								)
							);
			if(is_null($acc)){
				throw new Exception ('Account has not been updated successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Account has been update successfully', 'uuid'=>$account->uuid);
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $acc;
	}
	
	protected function destroyUser($account){
		try{
			$user = $account->user()->delete();
			if(is_null($user)){
				throw new Exception('User has not been deleted successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $user;
	}
	
	protected function destroyAccount($account){
		try{
			$acc = $account->delete();
			if(is_null($acc)){
				throw new Exception('Account has not been deleted successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'User account has bben deleted successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $acc;
	}
	
	protected function saveEmail($account){
		try{
			$email = Email::firstOrCreate(array($this->acc_data->address_key => $this->data[$this->acc_data->email_key]), 
						array($this->acc_data->address_key => $this->data[$this->acc_data->email_key])
					);
			if(is_null($email)){
				throw new Exception('Email has not been created successfully');
			}else{
				$account->email()->attch($email);
				$this->success = array('indicator'=>'success', ''=>'Email has bee created successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $email;
	}
	
	
	protected function updateEmail($email){
		try{
			$u_email = $email->update(array($this->acc_data->address_key => $this->data[$this->acc_data->email_key]));
			if(is_null($u_email)){
				throw new Exception('Email has not been updated successfully');
			}
			else{
				$this->success = array('indicator'=>'warning', ''=>'Email has been updated successfully');
			}
		}catch(Exception $e){
			$this->success = array('indicator'=>'warning', 'message'=>$e->getMessage());
			return null;
		}
		return $u_email;
	}
	
	protected function destroyEmail($email){
		try{
			$email = $email->delete();
			if(is_null($email)){
				throw new Exception('Email has not been deleted successfully');
			}else{
				$this->success = array('indicator'=>'success', 'message'=>'Email has been deleted successfully');
			}
		}catch(Exception $e){
			$this->error = array('indicator'=>'success', 'message'=>'Email has not been deleted successfully');
			return null;
		}
		return $email;
	}
}

?>