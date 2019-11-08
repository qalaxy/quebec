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
		$this->middleware('auth');
	}
	
	public function permissions(){
		if(Auth::user()->hasRole('super_admin') && Auth::user()->can('view_permissions')){
			$permissions = $this->ext->getPaginatedPermissions();
			if(is_object($permissions)){
				if(View::exists('w3.index.permissions'))
					return view('w3.index.permissions')->with(compact('permissions'));
				else{
					$notification = $this->missing_view;
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$permissions));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view permissions'));
		}
	}
	
	public function createPermission(){
		if(Auth::user()->hasRole('super_admin')){
			if(View::exists('w3.create.permission'))
				return view('w3.create.permission')->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			else{
				return back()->with('notification', $this->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create permissions'));
		}
	}
	
	public function storePermission(Request $request){
		if(Auth::user()->hasRole('super_admin') && Auth::user()->can('create_permissions')){
			$validation = $this->ext->validatePermData($request->all());
			if($validation->fails()){
				return redirect('create-permission')
							->withErrors($validation)
							->withInput();
			}
			$notification = $this->mnt->createPerm($request->all());
			if(in_array('success', $notification)){
				if(View::exists('w3.index.permissions')){
					return redirect('permissions')
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('create-permission')
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create a permission'));
		}
	}
	
	public function showPermission($uuid){
		if(Auth::user()->hasRole('super_admin') && Auth::user()->can('view_permissions')){
			$permission = $this->ext->getPermission($uuid);
			if(is_object($permission)){
				if(View::exists('w3.show.permission')){
					return view('w3.show.permission')->with(compact('permission'));
				}else{
					return back()->with('notification', array('indicator'=>'danger', 'message'=>$this->missing_view));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $permission));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view permissions'));
		}
	}
	
	public function editPermission($uuid){
		if(Auth::user()->hasRole('super_admin') && Auth::user()->can('edit_permissions')){
			//middleware to prevent update
			
			$permission = $this->ext->getPermission($uuid);
			if(is_object($permission)){
				if(View::exists('w3.edit.permission')){
					return view('w3.edit.permission')->with(compact('permission'))
							->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
				}else{
					return back()->with('notification', $this->missing_view);
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
		if(Auth::user()->hasRole('super_admin') && Auth::user()->can('edit_permissions')){
			$validation = $this->ext->validatePermData($request->all());
			if($validation->fails()){
				return redirect('edit-permission/'.$uuid)
							->withErrors($validation)
							->withInput();
			}
			
			$perm = $this->ext->getPermission($uuid);
			
			$notification = $this->mnt->editPerm($request->all(), $uuid);
			if(in_array('success', $notification)){
				//$perm = $this->ext->archivePerm($perm);
				if(View::exists('w3.show.permission')){
					return redirect('permission/'.$uuid)
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('edit-permission/'.$uuid)
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit a permission'));
		}
	}
	
	public function deletePermission($uuid){
		
		if(Auth::user()->hasRole('super_admin') && Auth::user()->can('delete_permissions')){
			
			$permission = $this->ext->getPermission($uuid); //var_dump($permission->description); return;
			if(is_object($permission)){
				return $this->ext->deletePerm($permission);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			$notification = array('indicator'=>'danger', 'message'=>'You are not allowed to delete a permission');
			return back()->with(compact('notification'));
		}
	}
	
	public function destroyPermission($uuid){
		if(Auth::user()->hasRole('super_admin') && Auth::user()->can('delete_permissions')){
			//middleware to prevent delete
			$permission = $this->ext->getPermission($uuid);
			if(is_object($permission)){
				$notification = $this->mnt->deletePerm($permission);
				if(in_array('success', $notification)){
					if(View::exists('w3.index.permissions')){
						return redirect('permissions')
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return redirect('w3.show.permission')
								->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $permission));
			}
		}else{
			$notification = array('indicator'=>'danger', 'message'=>'You are not allowed to edit a permission');
			return back()->with(compact('notification'));
		}
	}
	
	public function searchPermission(Request $request){
		if(Auth::user()->hasRole('super_admin') && Auth::user()->can('view_permissions')){
			$permissions = $this->ext->searchPermission($request->all());
			if(is_object($permissions)){
				if(View::exists('w3.index.permissions'))
					return view('w3.index.permissions')->with(compact('permissions'));
				else{
					return back()->with('notification', $this->missing_view);
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$permissions));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view permissions'));
		}
	}
}
