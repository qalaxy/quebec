<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use App\Shell\Web\Extension\AccountExt;
use App\Shell\Web\Monitor\AccountMnt;

class AccountController extends Controller
{
    private $ext;
	private $mnt;
	
	public function __construct(){
		$this->ext = new AccountExt();
		$this->mnt = new AccountMnt();
	}
	
	public function accountFirstLogin($uuid){
		$user = $this->ext->getUser(decrypt($uuid)); //return var_dump(decrypt($uuid));
		if(is_object($user)){
			if($user->status == 1)
				abort(403);
			
			if(View::exists('auth.passwords.first')){
				return view('auth.passwords.first')->with(compact('user'));
			}
		}else{
			abort(403);
		}
	}
	
	
	public function accountFirstAuth(Request $request, $uuid){
		$request['uuid'] = decrypt($uuid);
		$validation = $this->ext->validateFirstLoginData($request->all());
		if($validation->fails()){
			return redirect('first-login/'.$uuid)
					->withErrors($validation)
					->withInput();
		}
		$user = $this->ext->getUser(decrypt($uuid)); 
		if(!is_object($user)) return $user;
		
		$notification = $this->mnt->createFirstLogin($request->all(), $user); 
		
		if(in_array('success', $notification)){
			$credentials = $request->only('email', 'password');
			if (Auth::attempt($credentials)) {
				return redirect()->intended('/')->with(compact('notification'));
			}
		}
		
	}
	
    public function accounts(Request $request){
		if(Auth::user()->can('view_users')){
			if(count($request->all())){
				$accounts = $this->ext->searchAccounts($request->all());
			}else{				
				$accounts = $this->ext->getPaginatedAccounts();
			}
			if(is_object($accounts)){
				if(View::exists('w3.index.accounts'))
					if(count($accounts))
						return view('w3.index.accounts')->with(compact('accounts'));
					else
						return view('w3.index.accounts')->with(compact('accounts'))
								->with('notification', array('indicator'=>'warning', 'message'=>'Account(s) not found'));
				else{
					return back()->with('notification', $this->ext->missing_view);
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$accounts));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view users'));
		}
	}
	
	public function createAccount(){
		if(Auth::user()->can('create_users')){
			if(View::exists('w3.create.account')){
				return view('w3.create.account')->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create user\'s account'));
		}
	}
	
	public function storeAccount(Request $request){
		if(Auth::user()->can('create_users')){
			$validation = $this->ext->validateAccountData($request->all());
			if($validation->fails()){
				return redirect('create-account')
							->withErrors($validation)
							->withInput()
							->with('notification', $this->ext->validation);
			}
			$notification = $this->mnt->createAccount($request->all()); 
			if(in_array('success', $notification)){
				//Send email to user to log in for the first time
				$first_login = $this->ext->sendFirstLoginEmail($notification['uuid']);
				$first_login = null;
				
				if(is_string($first_login)) $notification['message'] .= '. '.$first_login;
				
				if(View::exists('w3.show.account')){
					return redirect('account/'.$notification['uuid'])
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('create-account')
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create user\'s account'));
		}
	}
	
	public function showAccount($uuid){
		if(Auth::user()->can('view_users') || Auth::user()->account()->first()->uuid == $uuid){
			$account = $this->ext->getAccount($uuid);
			if(is_object($account)){
				if(View::exists('w3.show.account')){
					return view('w3.show.account')->with(compact('account'));
				}else{
					return back()->with('notification', array('indicator'=>'danger', 'message'=>$this->ext->missing_view));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $account));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view a account'));
		}
	}
	
	public function editAccount($uuid){
		if(Auth::user()->can('edit_users')){
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account)){
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			}
			if(View::exists('w3.edit.account')){
				return view('w3.edit.account')->with(compact('account'))
						->with(array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit user\'s account'));
		}
	}
	
	public function updateAccount(Request $request, $uuid){
		if(Auth::user()->can('edit_users')){
			$request['account_id'] = $uuid;
			$validation = $this->ext->validateAccountData($request->all());
			if($validation->fails()){
				return redirect('edit-account/'.$uuid)
							->withErrors($validation)
							->withInput();
			}
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			$notification = $this->mnt->editAccount($request->all(), $account);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.account')){
					return redirect('account/'.$notification['uuid'])
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('edit-account/'.$uuid)
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to edit user\'s account'));
		}
	}
	
	public function deleteAccount($uuid){
		if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('delete_users')){
			
			$account = $this->ext->getAccount($uuid);
			if(is_object($account)){
				return $this->ext->deleteAccount($account);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return $this->ext->invalidDeletion('You are not allowed to delete a user\'s account', 'w3-red');
		}
	}
	
	public function destroyAccount($uuid){
		if(Auth::user()->can('delete_users')){
			$account = $this->ext->getAccount($uuid);
			if(is_object($account)){
				$notification = $this->mnt->deleteAccount($account);
				if(in_array('success', $notification)){
					if(View::exists('w3.index.accounts')){
						return redirect('accounts')
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $account));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete a user\'s account'));
		}
	}

	public function editAccountCredentials($uuid){
		if(Auth::user()->can('edit_users') || Auth::user()->account()->first()->uuid == $uuid){
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $account));

			if(View::exists('w3.edit.account-credentials')){
				return view('w3.edit.account-credentials')->with(compact('account'))
						->with(array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}

		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to change user\'s account credentials'));
		}
	}

	public function updateAccountCredentials(Request $request, $uuid){ //return $uuid .'  '.Auth::user()->account()->first()->uuid;
		if(Auth::user()->can('edit_users') || Auth::user()->account()->first()->uuid == $uuid){
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $account));

			$request['uuid'] = $account->user()->first()->uuid;
			$validation = $this->ext->validateAccountCredentialsData($request->all());
			if($validation->fails()){
				return back()->withErrors($validation)
							->withInput()
							->with('notification', $this->ext->validation);
			}

			$compare_password = $this->ext->comparePassword($request->all(), $account->user()->first());
			if($compare_password)
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $compare_password));

			$notification = $this->mnt->editAccountCredentials($request->all(), $account);
			if(in_array('success', $notification)){
				//Consider sending email to user to verify the email address

				if(View::exists('w3.show.account')){
					return redirect('account/'.$notification['uuid'])
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return back()->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to change user\'s account credentials'));
		}
	}
	
	/*To be done on individual user account*/ 
	public function emails(Request $request, $account_uuid){
		if(Auth::user()->can('view_users')){
			if(count($request->all())){
				$emails = $this->ext->searchEmails($request->all());
			}else{				
				$emails = $this->ext->getPaginatedEmails();
			}
			if(is_object($emails)){
				if(View::exists('w3.index.emails')){
					if(count($emails))
						return view('w3.index.emails')->with(compact('emails'));
					else
						return view('w3.index.emails')->with(compact('emails'))
								->with('notification', array('indicator'=>'warning', 'message'=>'Email(s) could not found'));
				}else{
					return back()->with('notification', $this->ext->missing_view);
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$emails));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view users'));
		}
	}

	public function addEmail($uuid){
		if(Auth::user()->can('create_users') || Auth::user()->account()->first()->uuid == $uuid){
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			if(View::exists('w3.create.email')){
				return view('w3.create.email')->with(compact('account'))
						->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add user\'s email'));
		}
	}
	
	public function storeEmail(Request $request, $uuid){
		if(Auth::user()->can('create_users') || Auth::user()->account()->first()->uuid == $uuid){
			$validation = $this->ext->validateEmailData($request->all());
			if($validation->fails()){
				return redirect('add-email/'.$uuid)
							->withErrors($validation)
							->withInput();
			}
			
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			$notification = $this->mnt->addEmail($request->all(), $account);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.account')){
					return redirect('account/'.$uuid)
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('add-email/'.$uuid)
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to add user\'s email'));
		}
	}
	
	public function editEmail($account_uuid, $email_uuid){
		if(Auth::user()->can('edit_users') || Auth::user()->account()->first()->uuid == $account_uuid){
			$account = $this->ext->getAccount($account_uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			$email = $this->ext->getEmail($email_uuid);
			if(!is_object($email))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$email));
			
			if(View::exists('w3.edit.email')){
				return view('w3.edit.email')->with(compact('account', 'email'))
						->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit user\'s email'));
		}
	}
	
	public function updateEmail(Request $request, $account_uuid, $email_uuid){
		if(Auth::user()->can('edit_users') || Auth::user()->account()->first()->uuid == $account_uuid){
			$validation = $this->ext->validateEmailData($request->all());
			if($validation->fails()){
				return redirect('edit-email/'.$account_uuid.'/'.$email_uuid)
							->withErrors($validation)
							->withInput();
			}
			
			$email = $this->ext->getEmail($email_uuid);
			if(!is_object($email))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$email));
			
			$notification = $this->mnt->editEmail($request->all(), $email);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.account')){
					return redirect('account/'.$account_uuid)
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('add-email/'.$account_uuid)
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to edit user\'s email'));
		}
	}
	
	public function deleteEmail($account_uuid, $email_uuid){
		if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('delete_users') || Auth::user()->account()->first()->uuid == $account_uuid){
			
			$account = $this->ext->getAccount($account_uuid);
			$email = $this->ext->getEmail($email_uuid);
			if(is_object($account) && is_object($email)){
				return $this->ext->deleteEmail($account, $email);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return $this->ext->invalidDeletion('You are not allowed to delete a user\'s email', 'w3-red');
		}
	}
	
	public function destroyEmail($account_uuid, $email_uuid){
		if(Auth::user()->can('delete_users') || Auth::user()->account()->first()->uuid == $account_uuid){
			$email = $this->ext->getEmail($email_uuid);
			if(is_object($email)){
				$notification = $this->mnt->deleteEmail($email);
				if(in_array('success', $notification)){
					if(View::exists('w3.show.account')){
						return redirect('account/'.$account_uuid)
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $email));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete a user\'s email'));
		}
	}
	
	public function addPhoneNumber(Request $request, $uuid){
		//
	}
	
	public function storePhoneNumber(Request $request, $uuid){
		//
	}
	
	public function editPhoneNumber($user_uuid, $phone_uuid){
		//
	}
	
	public function updatePhoneNumber(Request $request, $user_uuid, $phone_uuid){
		//
	}
	
	public function deletePhoneNumber(){
		//
	}
	
	public function destroyPhoneNumber(){
		//
	}
	
	public function accountStations(Request $request, $uuid){
		if(Auth::user()->can('view_users') || Auth::user()->account()->first()->uuid == $uuid){
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			if(count($request->all())){
				$stns = $this->ext->searchAccountStations($request->all(), $account);
			}else{
				$stns = $this->ext->getAccountStations($account);
			}
			
			if(!is_object($stns))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stns));
			
			if(View::exists('w3.index.account-stations')){
				if(count($stns))
						return view('w3.index.account-stations')->with(compact('account', 'stns'));
					else
						return view('w3.index.account-stations')->with(compact('account', 'stns'))
								->with('notification', array('indicator'=>'warning', 'message'=>'Account station(s) not found'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view user\'s station'));
		}
	}
	
	public function addAccountStation($uuid){
		if(Auth::user()->can('create_users')){
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			$stations = $this->ext->getStations();
			if(!is_object($stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));
			
			if(View::exists('w3.create.account-station')){
				return view('w3.create.account-station')->with(compact('account', 'stations'))
						->with('notification',array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add user\'s station'));
		}
	}
	
	public function storeAccountStation(Request $request, $uuid){
		if(Auth::user()->can('create_users')){			
			$validation = $this->ext->validateAccountStationData($request->all());
			if($validation->fails()){
				return redirect('add-station/'.$uuid)
							->withErrors($validation)
							->withInput();
			}
			
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			$station = $this->ext->getStation($request->station_id); 
			if(!is_object($station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$station));
			
			$notification = $this->mnt->addStation($request->all(), $account, $station);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.account')){
					return redirect('account/'.$uuid)
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('add-station/'.$uuid)
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to add user\'s station'));
		}
	}
	
	public function editAccountStation($account_uuid, $stn_uuid){
		if(Auth::user()->can('edit_users')){
			$account = $this->ext->getAccount($account_uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			$stn = $this->ext->getAccountStation($stn_uuid);
			if(!is_object($stn))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stn));
			
			$stn->from = date_format(date_create($stn->from), 'Y-m-d'); 
			if(isset($stn->to)) $stn->to = date_format(date_create($stn->to), 'Y-m-d');
			
			$stations = $this->ext->getStations();
			if(!is_object($stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));
			
			if(View::exists('w3.edit.account-station')){
				return view('w3.edit.account-station')->with(compact('account', 'stn', 'stations'))
						->with('notification',array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit user\'s station'));
		}
	}
	
	public function updateAccountStation(Request $request, $account_uuid, $stn_uuid){
		if(Auth::user()->can('edit_users')){
			$validation = $this->ext->validateAccountStationData($request->all());
			if($validation->fails()){
				return redirect('edit-account-station/'.$account_uuid.'/'.$stn_uuid)
							->withErrors($validation)
							->withInput();
			}
			
			$stn = $this->ext->getAccountStation($stn_uuid);
			if(!is_object($stn))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stn));
			
			$station = $this->ext->getStation($request->station_id); 
			if(!is_object($station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$station));
			
			$notification = $this->mnt->editAccountStation($request->all(), $stn, $station);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.account')){
					return redirect('account/'.$account_uuid)
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('edit-account-station/'.$account_uuid.'/'.$stn_uuid)
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to edit user\'s station'));
		}
	}
	
	public function deleteAccountStation($account_uuid, $stn_uuid){
		if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('delete_users')){
			
			$account = $this->ext->getAccount($account_uuid);
			$stn = $this->ext->getAccountStation($stn_uuid);
			if(is_object($account) && is_object($stn)){
				return $this->ext->deleteAccountStation($account, $stn);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return $this->ext->invalidDeletion('You are not allowed to delete a user\'s station', 'w3-red');
		}
	}
	
	public function destroyAccountStation($account_uuid, $stn_uuid){
		if(Auth::user()->can('delete_users')){
			$stn = $this->ext->getAccountStation($stn_uuid);
			if(is_object($stn)){
				$notification = $this->mnt->deleteAccountStation($stn);
				if(in_array('success', $notification)){
					if(View::exists('w3.show.account')){
						return redirect('account/'.$account_uuid)
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $stn));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete a user\'s station'));
		}
	}
	
	public function getAccountStation($uuid){
		if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('view_users')){
			
			$stn = $this->ext->getAccountStation($uuid);
			if(is_object($stn)){
				return $this->ext->showAccountStation($stn);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete a user\'s station'));
		}
	}
	
	public function accountSupervisories(Request $request, $uuid){
		if(Auth::user()->can('view_users')){
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			if(count($request->all())){
				$supervisories = $this->ext->searchAccountSupervisories($request->all(), $account);
			}else{
				$supervisories = $this->ext->getAccountSupervisories($account);
			}
			
			if(!is_object($supervisories))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$supervisories));
			
			if(View::exists('w3.index.account-supervisories')){
				if(count($supervisories))
						return view('w3.index.account-supervisories')->with(compact('account', 'supervisories'));
					else
						return view('w3.index.account-supervisories')->with(compact('account', 'supervisories'))
								->with('notification', array('indicator'=>'warning', 'message'=>'Account station(s) not found'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view user\'s stations in supervision'));
		}
	}
	
	public function addAccountSupervisory($uuid){
		if(Auth::user()->can('create_users')){
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			$stations = $this->ext->getStations();
			if(!is_object($stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));
			
			if(View::exists('w3.create.account-supervisory')){
				return view('w3.create.account-supervisory')->with(compact('account', 'stations'))
						->with('notification',array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add user\'s supervisory'));
		}
	}
	
	public function storeAccountSupervisory(Request $request, $uuid){
		if(Auth::user()->can('create_users')){			
			$validation = $this->ext->validateAccountSupervisoryData($request->all());
			if($validation->fails()){
				return redirect('add-account-supervisory/'.$uuid)
							->withErrors($validation)
							->withInput()
							->with('notification', $this->ext->validation);
			}
			
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			$station = $this->ext->getStation($request->station_id); 
			if(!is_object($station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$station));
			
			$notification = $this->mnt->addAccountSupervisory($request->all(), $account, $station);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.account')){
					return redirect('account/'.$uuid)
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('add-station/'.$uuid)
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to add user\'s station'));
		}
	}
	
	public function editAccountSupervisory($account_uuid, $sup_uuid){
		if(Auth::user()->can('edit_users')){
			$account = $this->ext->getAccount($account_uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			$supervisory = $this->ext->getAccountSupervisory($sup_uuid);
			if(!is_object($supervisory))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$supervisory));
			
			$supervisory->from = date_format(date_create($supervisory->from), 'Y-m-d'); 
			if(isset($supervisory->to)) $supervisory->to = date_format(date_create($supervisory->to), 'Y-m-d');
			
			$stations = $this->ext->getStations();
			if(!is_object($stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));
			
			if(View::exists('w3.edit.account-supervisory')){
				return view('w3.edit.account-supervisory')->with(compact('account', 'supervisory', 'stations'))
						->with('notification',array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit user\'s supervisory'));
		}
	}
	
	public function updateAccountSupervisory(Request $request, $account_uuid, $sup_uuid){
		if(Auth::user()->can('edit_users')){
			$request['account_id'] = $account_uuid;
			$validation = $this->ext->validateAccountSupervisoryData($request->all());
			if($validation->fails()){
				return redirect('edit-account-supervisory/'.$account_uuid.'/'.$sup_uuid)
							->withErrors($validation)
							->withInput();
			}
			
			$supervisory = $this->ext->getAccountSupervisory($sup_uuid);
			if(!is_object($supervisory))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$supervisory));
			
			$station = $this->ext->getStation($request->station_id); 
			if(!is_object($station))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$station));
			
			$notification = $this->mnt->editAccountSupervisory($request->all(), $supervisory, $station);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.account')){
					return redirect('account/'.$account_uuid)
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('edit-account-supervisory/'.$account_uuid.'/'.$sup_uuid)
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to edit user\'s supervisory'));
		}
	}
	
	public function accountSupervisory($uuid){
		if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('view_users')){
			
			$supervisory = $this->ext->getAccountSupervisory($uuid);
			if(is_object($supervisory)){
				return $this->ext->showAccountSupervisory($supervisory);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete a user\'s supervisory'));
		}
	}
	
	public function deleteAccountSupervisory($account_uuid, $sup_uuid){
		if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('delete_users')){
			
			$account = $this->ext->getAccount($account_uuid);
			$supervisory = $this->ext->getAccountSupervisory($sup_uuid);
			if(is_object($account) && is_object($supervisory)){
				return $this->ext->deleteAccountSupervisory($account, $supervisory);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return $this->ext->invalidDeletion('You are not allowed to delete a user\'s supervisory', 'w3-red');
		}
	}
	
	public function destroyAccountSupervisory($account_uuid, $sup_uuid){
		if(Auth::user()->can('delete_users')){
			$supervisory = $this->ext->getAccountSupervisory($sup_uuid);
			if(is_object($supervisory)){
				$notification = $this->mnt->deleteAccountSupervisory($supervisory);
				if(in_array('success', $notification)){
					if(View::exists('w3.show.account')){
						return redirect('account/'.$account_uuid)
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $supervisory));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete a user\'s supervisory'));
		}
	}
	
	public function addRole($uuid){
		if(Auth::user()->can('create_users')){ 
			
			$account = $this->ext->getAccount($uuid);  
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			$user_stations = $this->ext->getUserStations($account->user()->first()); 
			if(!is_object($user_stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$user_stations));
			
			if(count($user_stations) < 1)
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'User has not been assigned a station'));
			
			$roles = $this->ext->getUnaddedRoles($user_stations, $account->user()->first());
			if(!is_object($roles))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$roles));
			
			if(View::exists('w3.create.account-role')){
				return view('w3.create.account-role')->with(compact('account', 'roles'))
						->with('notification',array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add user\'s roles'));
		}
	}
	
	public function storeAccountRole(Request $request, $uuid){
		if(Auth::user()->can('create_users')){ 
			$validation = $this->ext->validateAccountRoleData($request->all());
			if($validation->fails()){
				return back()->withErrors($validation)
						->withInput()
						->with('notification', $this->ext->validation);
			}
			$account = $this->ext->getAccount($uuid);  
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account))->withInput();
			
			$role = $this->ext->getRole($request['role_id']);
			if(!is_object($role))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$role))->withInput();
			
			$notification = $this->mnt->createAccountRole($account->user()->first(), $role);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.account'))
					return redirect('account/'.$uuid)
							->with(compact('notification'));
				else
					return back()->with('notification', $this->ext->missing_view);
			}else{
				return back()->with(compact('notification'))->withInput();
			}
			
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add user\'s role'));
		}
		
	}
	
	public function deleteAccountRole($account_uuid, $role_uuid){
		if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('delete_users')){
			
			$account = $this->ext->getAccount($account_uuid);
			
			$role = $this->ext->getRole($role_uuid);
			if(is_object($account) && is_object($role)){
				return $this->ext->deleteAccountrole($account, $role);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return $this->ext->invalidDeletion('You are not allowed to delete a user\'s role', 'w3-red');
		}
	}
	
	public function destroyAccountRole($account_uuid, $role_uuid){
		if(Auth::user()->can('delete_users')){
			$account = $this->ext->getAccount($account_uuid);  
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account))->withInput();
			
			$role = $this->ext->getRole($role_uuid);
			if(is_object($role)){
				$notification = $this->mnt->deleteAccountRole($account->user()->first(), $role);
				if(in_array('success', $notification)){
					if(View::exists('w3.show.account')){
						return redirect('account/'.$account_uuid)
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $role));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete a user\'s role'));
		}
	}
	
	public function accountRole($uuid){ 
		if(session()->has('params')) session()->reflash();
		abort(404);
		if(Auth::user()->can('view_users')){
			
			$role = $this->ext->getRole($uuid);
			if(is_object($role)){
				return $this->ext->showAccountRole($role);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view a user\'s role'));
		}
	}
	
}
