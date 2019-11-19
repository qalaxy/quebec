<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;


use App\Shell\Web\Extension\RoleExt;
use App\Shell\Web\Monitor\RoleMnt;

class RoleController extends Controller
{
    private $ext;
    private $mnt;
	
	
	public function __construct(){
		$this->ext = new RoleExt();
		$this->mnt = new RoleMnt();
		$this->middleware('auth');
	}
	
	public function roles(Request $request){
		if(Auth::user()->can('view_roles')){
			if(count($request->all())){
				$roles = $this->ext->searchRoles($request->all());
			}else{				
				$roles = $this->ext->getPaginatedRoles();
			}
			if(is_object($roles)){
				if(View::exists('w3.index.roles'))
					if(count($roles))
						return view('w3.index.roles')->with(compact('roles'));
					else
						return view('w3.index.roles')->with(compact('roles'))
								->with('notification', array('indicator'=>'warning', 'message'=>'Role(s) could not found'));
				else{
					return back()->with('notification', $this->ext->missing_view);
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$roles));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view roles'));
		}
	}
	
	public function createRole(){
		if(Auth::user()->can('create_roles')){
			if(View::exists('w3.create.role'))
				return view('w3.create.role')->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create roles'));
		}
	}
	
	public function storeRole(Request $request){
		if(Auth::user()->can('create_roles')){
			$validation = $this->ext->validateRoleData($request->all());
			if($validation->fails()){
				return redirect('create-role')
							->withErrors($validation)
							->withInput();
			}
			$notification = $this->mnt->createRole($request->all()); //return var_dump($notification['uuid']);
			if(in_array('success', $notification)){
				if(View::exists('w3.show.role')){
					return redirect('role/'.$notification['uuid'])
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('create-role')
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create a role'));
		}
	}
	
	public function showRole($uuid){
		if(Auth::user()->can('view_roles')){
			$role = $this->ext->getRole($uuid);
			if(is_object($role)){
				if(View::exists('w3.show.role')){
					return view('w3.show.role')->with(compact('role'));
				}else{
					return back()->with('notification', array('indicator'=>'danger', 'message'=>$this->ext->missing_view));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $role));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view a role'));
		}
	}
	
	public function editRole($uuid){
		if(Auth::user()->can('edit_roles')){
			$role = $this->ext->getRole($uuid);
			if(is_object($role)){
				if(View::exists('w3.edit.role')){
					return view('w3.edit.role')->with(compact('role'))
							->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
				}else{
					return back()->with('notification', $this->ext->missing_view);
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $role));
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit a role'));
		}
	}
	
	public function updateRole(Request $request, $uuid){
		if(Auth::user()->can('edit_roles')){
			$validation = $this->ext->validateRoleData($request->all());
			if($validation->fails()){
				return redirect('edit-role/'.$uuid)
							->withErrors($validation)
							->withInput();
			}
			
			//$role = $this->ext->getRole($uuid);
			
			$notification = $this->mnt->editRole($request->all(), $uuid);
			if(in_array('success', $notification)){
				//$role = $this->ext->archiveRole($perm);
				if(View::exists('w3.show.role')){
					return redirect('role/'.$uuid)
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return redirect('edit-role/'.$uuid)
							->with(compact('notification'))
							->withInput();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit a role'));
		}
	}
	
	public function deleteRole($uuid){
		if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('delete_roles')){
			
			$role = $this->ext->getRole($uuid);
			if(is_object($role)){
				return $this->ext->deleteRole($role);
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete a role'));
		}
	}
	
	public function destroyRole($uuid){
		if(Auth::user()->can('delete_roles')){
			$role = $this->ext->getRole($uuid);
			if(is_object($role)){
				$notification = $this->mnt->deleteRole($role);
				if(in_array('success', $notification)){
					if(View::exists('w3.index.roles')){
						return redirect('roles')
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
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit a role'));
		}
	}
	
	public function rolePermissions(Request $request, $uuid){
		if(Auth::user()->can('view_role_permissions')){
			$role = $this->ext->getRole($uuid);
			if(!is_object($role)){
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$role));
			}
			if(count($request->all())){
				$permissions = $this->ext->searchRolePermissions($request->all(), $role);
			}else{				
				$permissions = $this->ext->getPaginatedRolePermissions($role);
			}
			if(is_object($permissions) && is_object($role)){
				if(View::exists('w3.index.role_permissions'))
					if(count($permissions))
						return view('w3.index.role_permissions')->with(compact('permissions', 'role'));
					else
						return view('w3.index.role_permissions')->with(compact('permissions', 'role'))
								->with('notification', array('indicator'=>'warning', 'message'=>'Permissions for the role could not be found'));
				else{
					return back()->with('notification', $this->ext->missing_view);
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$permissions));
			}
		}else{
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to view permissions belonging to a role'));
		}
	}
	
	public function addRolePermission($uuid){
		if(Auth::user()->can('add_role_permission')){
			$role = $this->ext->getRole($uuid);
			if(is_object($role)){
				$permissions = $this->ext->getPermNotInRole($role);
				if(is_object($permissions)){
					if(View::exists('w3.create.role_permission')){
						if(count($permissions))
							return view('w3.create.role_permission')->with(compact('role', 'permissions'));
						else
							return view('w3.create.role_permission')->with(compact('role', 'permissions'))
										->with('notification', array('indicator'=>'information', 'message'=>'All permissions have been added. No permission to add now'));
					}else{
						return back()->with('notification', $this->ext->missing_view);
					}
				}else{
					return back()->with('notification', array('indicator'=>'warning', 'message'=>$permissions));
				}
			}else{
				return back()-with('notification', array('indicator'=>'warning', 'message'=>$role));
			}
		}else{
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to add a permission for a role'));
		}
	}
	
	public function storeRolePermission(Request $request, $uuid){
		if(Auth::user()->can('add_role_permission')){ 
			$validation = $this->ext->validateRolePermData($request->all());
			if($validation->fails()){
				return redirect('add-role-permission/'.$uuid)
							->withErrors($validation)
							->withInput();
			}
			$permission = $this->ext->getPermission($request['permission']); //return $permission;
			if(!is_object($permission)){
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$permission));
			}
			$role = $this->ext->getRole($uuid);
			if(is_object($role)){
				$notification = $this->mnt->addRolePerm($role, $permission);
				if(in_array('success', $notification)){
					if(View::exists('w3.index.role_permissions'))
						return redirect('role-permissions/'.$uuid)
									->with(compact('notification'));
				}else{
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$role));
			}
			
		}else{
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to add a permission for a role'));
		}
	}
	
	public function deleteRolePermission($role_uuid, $perm_uuid){
		if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('delete_role_permissions')){
			$permission = $this->ext->getPermission($perm_uuid);
			
			$role = $this->ext->getRole($role_uuid);
			if(is_object($permission) &&(is_object($role))){
				return $this->ext->deleteRolePermission($role, $permission);
				
			}else{
				return $this->ext->invalidDeletion();
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete a role\'s permission'));
		}
	}
	
	public function destroyRolePermission($role_uuid, $perm_uuid){
		if(Auth::user()->can('delete_roles')){
			$role = $this->ext->getRole($role_uuid);
			$permission = $this->ext->getPermission($perm_uuid);
			if(is_object($role) && is_object($permission)){
				$notification = $this->mnt->deleteRolePermission($role, $permission);
				if(in_array('success', $notification)){
					if(View::exists('w3.index.role_permissions')){
						return redirect('role-permissions/'.$role->uuid)
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
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete a role\'s permission'));
		}
	}
}
