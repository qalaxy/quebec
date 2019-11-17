<?php 
namespace App\Shell\Web\Monitor;

use Illuminate\Support\Facades\DB;
use App\Shell\Web\Executor\AccountExe;

class AccountMnt extends AccountExe{
	public function createAccount(array $data){
		$this->data = $data;
		DB::beginTransaction();
		$user = $this->storeUser();
		if(is_null($user)){
			DB::rollback();
			return $this->error;
		}
		
		$account = $this->storeAccount($user);
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
	
	public function editAccount(array $data, object $account){
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
	
	public function deleteAccount(object $account){
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
		
		DB::rollback();
		return $this->success;		
	}
	
	public function addEmail(array $data, object $account){
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
	
	public function editEmail(array $data, object $email){
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
	
	public function deleteEmail(object $email){
		DB::beginTransaction();
		
		$email = $this->destroyEmail($email);
		if(is_null($email)){
			DB::rollback();
			return $this->error;
		}
		
		DB::rollback();
		return $this->success;
	}
	
	public function addStation(array $data, object $account, object $station){
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
	
	public function editAccountStation(array $data, object $stn, $station){
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
	
	public function deleteAccountStation(object $stn){
		DB::beginTransaction();
		$stn = $this->destroyAccountStation($stn);
		
		if(is_null($stn)){
			DB::rollback();
			return $this->error;
		}
		DB::commit();
		return $this->success;
	}
	
}

?>