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
			$user_stations = $this->ext->getUserStations(Auth::id());
			if(!is_object($user_stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$user_stations));
			
			if(count($user_stations) < 1)
				return back()->with('notification', array('indicator'=>'warning', 'message'=>'You have not been assigned a station'));
			
			if(count($request->all())){
				$roles = $this->ext->searchRoles($request->all(), $user_stations);
			}else{				
				$roles = $this->ext->getPaginatedRoles($user_stations);
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
			
			$stations = $this->ext->getStations(); 
			if(!is_object($stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));
			
			
			
			if(View::exists('w3.create.role'))
				return view('w3.create.role')->with(compact('stations'))
						->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
			else{
				return back()->with('notification', $this->ext->missing_view);
			}
		}else{
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create roles'));
		}
	}
	
	public function getRoleStations(){
		if(Auth::user()->can('create_roles')){
			$stations = $this->ext->getStations(); 
			if(!is_object($stations))
				return null;
			
			return $this->ext->getRoleStations($stations);
		}else{
			return null;
		}
	}
	
	public function getRoleUserStations(){
		if(Auth::user()->can('create_roles')){
			if(is_null(Auth::user()->account()->first()))
				return null;
			
			if(is_null(Auth::user()->account()->first()->accountStation()->get()))
				return null;
			
			$stations = $this->ext->getStations(); 
			if(!is_object($stations))
				return null;
			
			return $this->ext->getRoleUserStations(Auth::user()->account()->first()->accountStation()->get(), $stations);
		}else{
			return null;
		}
	}
	
	public function storeRole(Request $request){
		if(Auth::user()->can('create_roles')){
			//return var_dump($request->all());
			
			$validation = $this->ext->validateRoleData($request->all());
			if($validation->fails()){
				return redirect('create-role')
							->withErrors($validation)
							->withInput()
							->with('notification', $this->ext->validation);
			}
			if($request['global'] == 3){
				$stations = $this->ext->getStations(); 
			}else if($request['global'] == 2){
				$stations = $this->ext->getSelectedStations($request['stations']); 
			}else if($request['global'] == 1){
				$stations = $this->ext->getUserStations(Auth::id());
			}
			
			if(!is_object($stations)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations))->withInput();
			
			//return back()->with('notification', $this->ext->under_construction)->withInput();
			
			$notification = $this->mnt->createRole($request->all(), $stations); //return var_dump($notification['uuid']);
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
			$stations = $this->ext->getStations(); 
			if(!is_object($stations))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations));
			
			$role = $this->ext->getRole($uuid); //return var_dump(count($role->station()->get()));
			
			/*foreach($role->station()->get() as $station){
				var_dump($station->name);
			}return;*/
			
			if(is_object($role)){
				if(View::exists('w3.edit.role')){
					return view('w3.edit.role')->with(compact('role', 'stations'))
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
		if(Auth::user()->can('edit_roles')){ //return var_dump($request->all());
			$request['role_id'] = $uuid;
			$validation = $this->ext->validateRoleData($request->all());
			if($validation->fails()){
				return redirect('edit-role/'.$uuid)
							->withErrors($validation)
							->withInput()
							->with('notification', $this->ext->validation);
			}
			
			if($request['global'] == 3){
				$stations = $this->ext->getStations(); 
			}else if($request['global'] == 2){
				$stations = $this->ext->getSelectedStations($request['stations']); 
			}else if($request['global'] == 1){
				$stations = $this->ext->getUserStations(Auth::id());
			}
			
			if(!is_object($stations)) 
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$stations))->withInput();
			
			//return var_dump(count($stations));
			
			$role = $this->ext->getRole($uuid);
			if(!is_object($role))
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$role));
			
			//return back()->with('notification', $this->ext->under_construction)->withInput();
			
			$notification = $this->mnt->editRole($request->all(), $stations, $role);
			if(in_array('success', $notification)){
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
				
				$level = $this->ext->getUserHighestLevel(Auth::user());
				if(!is_object($level))
					return back()->with('notification', array('indicator'=>'warning', 'message'=>$level));
				
				$permissions = $this->ext->getPermNotInRole($role, $level);
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
