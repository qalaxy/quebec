<?php 
namespace App\Shell\Web\Monitor;

use Illuminate\Support\Facades\DB;
use App\Shell\Web\Executor\AccountExe;

class AccountMnt extends AccountExe{
	
	public function createFirstLogin(array $data, $user){
		$this->data = $data;
		DB::beginTransaction();
		$user = $this->updateFirstLogin($user);
		if(is_null($user)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function createAccount(array $data){
		$this->data = $data;
		DB::beginTransaction();
		
		$acc_user = $this->storeUser();
		if(is_null($acc_user)){
			DB::rollback();
			return $this->error;
		}
		
		$account = $this->storeAccount($acc_user);
		if(is_null($account)){
			DB::rollback();
			return $this->error;
		}
		
		$phone_number = $this->storePhoneNumber($account);
		if(is_null($phone_number)){
			DB::rollback();
			return $this->error;
		}
		
		$email = $this->storeEmail($account);
		if(is_null($email)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}

	public function editAccountCredentials(array $data, $account){
		$this->data = $data;
		DB::beginTransaction();
		$user = $this->updateAccountCredentials($account);
		if(is_null($user)){
			DB::rollback();
			return $this->error;
		}

		DB::commit();
		return $this->success;
	}
	
	public function editAccount(array $data, $account){
		$this->data = $data;
		
		DB::beginTransaction();
		$user = $this->updateUser($account);
		if(is_null($user)){
			DB::rollback();
			return $this->error;
		}
		
		$acc = $this->updateAccount($account);
		if(is_null($acc)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}
	
	public function deleteAccount($account){
		DB::beginTransaction();
		$user = $this->destroyUser($account);
		if(is_null($user)){
			DB::rollback();
			return $this->error;
		}
		
		$acc = $this->destroyAccount($account);
		if(is_null($acc)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;		
	}
	
	public function addEmail(array $data, $account){
		$this->data = $data;
		
		DB::beginTransaction();
		$email = $this->saveEmail($account);
		if(is_null($email)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function editEmail(array $data, $email){
		$this->data = $data;
		
		DB::beginTransaction();
		$u_email = $this->updateEmail($email);
		if(is_null($u_email)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function deleteEmail($email){
		DB::beginTransaction();
		
		$email = $this->destroyEmail($email);
		if(is_null($email)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}
	
	public function addStation(array $data, $account, $station){
		$this->data = $data;
		
		DB::beginTransaction();
		$station = $this->storeStation($account, $station);
		if(is_null($station)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function editAccountStation(array $data, $stn, $station){
		$this->data = $data;
		DB::beginTransaction();
		$stn = $this->updateAccountStation($stn, $station);
		if(is_null($stn)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function deleteAccountStation($stn){
		DB::beginTransaction();
		$stn = $this->destroyAccountStation($stn);
		
		if(is_null($stn)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function addAccountSupervisory(array $data, $account, $station){
		$this->data = $data;
		
		DB::beginTransaction();
		$supervisory = $this->storeAccountSupervisory($account, $station);
		if(is_null($supervisory)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function editAccountSupervisory(array $data, $supervisory, $station){
		$this->data = $data;
		DB::beginTransaction();
		$supervisory = $this->updateAccountSupervisory($supervisory, $station);
		if(is_null($supervisory)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function deleteAccountSupervisory($supervisory){
		DB::beginTransaction();
		$supervisory = $this->destroyAccountSupervisory($supervisory);
		if(is_null($supervisory)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function createAccountRole($user, $role){
		DB::beginTransaction();
		$role = $this->storeAccountRole($user, $role);
		if(is_null($role)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
	public function deleteAccountRole($user, $role){
		DB::beginTransaction();
		$del_role = $this->destroyAccountRole($user, $role);
		if(is_null($del_role)){
			DB::rollback();
			return $this->error;
		}
		
		DB::commit();
		return $this->success;
	}
}

?>