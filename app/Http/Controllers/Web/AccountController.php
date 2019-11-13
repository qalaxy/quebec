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
				return view('w3.create.account')->with(array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
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
							->withInput();
			}
			$notification = $this->mnt->createAccount($request->all()); //return var_dump($notification['uuid']);
			if(in_array('success', $notification)){
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
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to create user\'s account'));
		}
	}
	
	public function showAccount($uuid){
		if(Auth::user()->can('view_users')){
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
			$validation = $this->ext->validateAccountData($request->all());
			if($validation->fails()){
				return redirect('create-account')
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
				return redirect('create-account')
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
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete a user\'s account'));
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
	
	/*To be done on individual user account*/ 
	public function emails(){
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
		if(Auth::user()->can('create_users')){
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			if(View::exists('w3.create.email')){
				return view('w3.create.email')->with(compact('account'))
						->with(array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to add user\'s email'));
		}
	}
	
	public function storeEmail(Request $request, $uuid){
		if(Auth::user()->can('create_users')){
			$validation = $this->ext->validateEmailData($request->all());
			if($validation->fails()){
				return redirect('create-email')
							->withErrors($validation)
							->withInput();
			}
			$account = $this->ext->getAccount($uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			$notification = $this->mnt->createEmail($request->all(), $account);
			if(in_array('success', $notification)){
				if(View::exists('w3.index.emails')){
					return redirect('emails/'.$account_uuid)
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('create-email')
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to add user\'s email'));
		}
	}
	
	public function editEmail($account_uuid, $email_uuid){
		if(Auth::user()->can('edit_users')){
			$account = $this->ext->getAccount($account_uuid);
			if(!is_object($account))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$account));
			
			$email = $this->ext->getEmail($email_uuid);
			if(!is_object($email))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$email));
			
			if(View::exists('w3.edit.email')){
				return view('w3.edit.email')->with(compact('account', 'email'))
						->with(array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			}else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit user\'s email'));
		}
	}
	
	public function updateEmail(Request $request, $account_uuid, $email_uuid){
		if(Auth::user()->can('edit_users')){
			$validation = $this->ext->validateEmailData($request->all());
			if($validation->fails()){
				return redirect('create-email')
							->withErrors($validation)
							->withInput();
			}
			$email = $this->ext->getEmail($email_uuid);
			if(!is_object($email))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$email));
			
			$notification = $this->mnt->editEmail($request->all(), $email);
			if(in_array('success', $notification)){
				if(View::exists('w3.index.emails')){
					return redirect('emails/'.$account_uuid)
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('create-email')
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to edit user\'s email'));
		}
	}
	
	public function deleteEmail($account_uuid, $email_uuid){
		if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('delete_users')){
			
			$account = $this->ext->getAccount($account_uuid);
			$email = $this->ext->getAccount($email_uuid);
			
			if(is_object($account) && is_object($email)){
				return $this->ext->deleteAccount($account, $email);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete a user\'s email'));
		}
	}
	
	public function destroyEmail($uuid){
		if(Auth::user()->can('delete_users')){
			$email = $this->ext->getEmail($uuid);
			if(is_object($email)){
				$notification = $this->mnt->deleteEmail($email);
				if(in_array('success', $notification)){
					if(View::exists('w3.index.emails')){
						return redirect('emails')
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
}
