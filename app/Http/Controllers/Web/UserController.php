<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
	private $ext;
	private $mnt;
	
    public function users(Request $request){
		if(Auth::user()->can('view_users')){
			if(count($request->all())){
				$accounts = $this->ext->searchAccounts($request->all());
			}else{				
				$accounts = $this->ext->getPaginatedAccounts();
			}
			if(is_object($accounts)){
				if(View::exists('w3.index.accounts'))
					if(count($users))
						return view('w3.index.accounts')->with(compact('users'));
					else
						return view('w3.index.users')->with(compact('users'))
								->with('notification', array('indicator'=>'warning', 'message'=>'User(s) not found'));
				else{
					return back()->with('notification', $this->ext->missing_view);
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$users));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view roles'));
		}
	}
	
	public function createUser(){
		//
	}
	
	public function storeUser(Request $request){
		//
	}
	
	public function editUser($uuid){
		//
	}
	
	public function updateUser(Request $request, $uuid){
		//
	}
	
	public function deleteUser($uuid){
		//
	}
	
	public function destroyUser($uuid){
		//
	}
	
	/*To be done on individual user account*/ 
	
	public function addEmail(Request $request, $uuid){
		//
	}
	
	public function editEmail($user_uuid, $email_uuid){
		//
	}
	
	public function updateEmail(Request $request, $user_uuid, $email_uuid){
		//
	}
	
	public function deleteEmail($uuid){
		//
	}
	
	public function destroyEmail($uuid){
		//
	}
	
	public function addPhoneNumber(Request $request, $uuid){
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
