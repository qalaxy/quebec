<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use App\Shell\Web\Extension\AdminExt;
use App\Shell\Web\Monitor\AdminMnt;

class AdminController extends Controller
{
	private $ext;
	private $mnt;
	private $missing_view = array('indicator'=>'information', 'message'=>'The web application interface is missing');
	private $rec_num = 15;
	
    public function __construct(){
		$this->ext = new AdminExt();
		$this->mnt = new AdminMnt();
		//$this->middleware('auth');
	}
	
	public permissions(){
		if(Auth::user()->hasRole('supper_admin') && Auth::user()->can('view_permissions')){
			$permissions = $this->ext->getPermissions();
			if(is_object($permissions)){
				$permissions = $permissions->paginate($this->rec_num);
				if(View::exists('w3.index.permission'))
					return view('w3.index.permission')->with(compact('permissions'));
				else{
					$notification = $this->missing_view;
					return back()->with(compact('notification'));
				}
			}else{
				$notification = array('indicator'=>'warning', 'message'=>$permissions);
				return back()->with(compact('notification'));
			}
		}else{
			$notification = array('indicator'=>'danger', 'message'=>'You are not allowed to view permissions');
			return back()->with(compact('notification'));
		}
	}
	
	public function createPermission(){
		if(Auth::user()->hasRole('supper_admin') && Auth::user()->can('create_permissions')){
			if(View::exists('w3.create.permission'))
				return view('w3.create.permission');
			else{
				$notification = $this->missing_view;
				return back()->with(compact('notification'));
			}
		}else{
			$notification = array('indicator'=>'danger', 'message'=>'You do not have permission to create permissions');
			return back()->with(compact('notification'));
		}
	}
	
	public function storePermission(Request $request){
		if(Auth::user()->hasRole('supper_admin') && Auth::user()->can('create_permission')){
			$validation = $this->ext->validatePermData($request->all());
			if($validation->fails()){
				return redirect('create-perm')
							->withErrors($validation)
							->withInput();
			}
			
			$notification = $this->mnt->createPerm($request->all());
			if(in_array('success', $notification)){
				if(View::exists('w3.index.permission')){
					return redirect('w3.index.permission')
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('w3.create.permission')
							->with(compact('notification'))
							->withInput();
			}
		}else{
			$notification = array('indicator'=>'danger', 'message'=>'You are not allowed to create a permission');
			return back()->with(compact('notification'));
		}
	}
	
	public function showPermission($uuid){
		if(Auth::user()->hasRole('supper_admin') && Auth::user()->can('view_permissions')){
			$permission = $this->ext->getPerm($uuid);
			if(is_object($permission)){
				if(View::exists('w3.show.permission')){
					return view('w3.show.permission')->with(compact('permission'));
				}else{
					$notification = $this->missing_view;
					return back()->with(compact('notification'));
				}
			}else{
				$notification = array('indicator'=>'warning', 'message'=> $permission);
				return back()->with(compact('notification'));
			}
		}else{
			$notification = array('indicator'=>'danger', 'message'=>'You are not allowed to view permissions');
			return back()->with(compact('notification'));
		}
	}
	
	public function editPermission($uuid){
		if(Auth::user()->hasRole('supper_admin') && Auth::user()->can('edit_permission')){
			$permission = $this->ext->getPerm($uuid);
			if(is_object($permission)){
				if(View::exists('w3.edit.permission')){
					return view('w3.edit.permission')->with(compact('permission'));
				}else{
					$notification = $this->missing_view;
					return back()->with(compact('notification'));
				}
			}else{
				$notification = array('indicator'=>'warning', 'message'=> $permission);
				return back()->with(compact('notification'));
			}
		}else{
			$notification = array('indicator'=>'danger', 'message'=>'You are not allowed to edit a permission');
			return back()->with(compact('notification'));
		}
	}
	
	public function updatePermission(Request $request, $uuid){
		if(Auth::user()->hasRole('supper_admin') && Auth::user()->can('edit_permission')){
			$validation = $this->ext->validatePermData($request->all());
			if($validation->fails()){
				return redirect('edit-perm')
							->withErrors($validation)
							->withInput();
			}
			$perm = $this->ext->getPerm($uuid);
			
			$notification = $this->mnt->editPerm($request->all());
			if(in_array('success', $notification)){
				$perm = $this->ext->archivePerm($perm);
				if(View::exists('w3.show.permission')){
					return redirect('w3.show.permission')
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('w3.edit.permission')
							->with(compact('notification'))
							->withInput();
			}
		}else{
			$notification = array('indicator'=>'danger', 'message'=>'You are not allowed to edit a permission');
			return back()->with(compact('notification'));
		}
	}
	
	public function deletePermission($uuid){
		if(Auth::user()->hasRole('supper_admin') && Auth::user()->can('delete_permission')){
			$permission = $this->ext->getPerm($uuid);
			if(is_object($permission)){
				return $this->ext->htmlentities(deletePerm($permission), ENT_QUOTES);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			$notification = array('indicator'=>'danger', 'message'=>'You are not allowed to delete a permission');
			return back()->with(compact('notification'));
		}
	}
	
	public function destroyPermission($uuid){
		if(Auth::user()->hasRole('supper_admin') && Auth::user()->can('delete_permission')){
			$permission = $this->ext->getPerm($uuid);
			if(is_object($permission)){
				$notification = $this->mnt->deletePerm($request->all());
				if(in_array('success', $notification)){
					if(View::exists('w3.index.permission')){
						return redirect('w3.index.permission')
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return redirect('w3.show.permission')
								->with(compact('notification'));
				}
			}else{
				$notification = array('indicator'=>'warning', 'message'=> $permission);
				return back()->with(compact('notification'));
			}
		}else{
			$notification = array('indicator'=>'danger', 'message'=>'You are not allowed to edit a permission');
			return back()->with(compact('notification'));
		}
	}
}
