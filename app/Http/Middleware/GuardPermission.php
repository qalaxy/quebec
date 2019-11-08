<?php

namespace App\Http\Middleware;

use Closure;
use App\Permission;

class GuardPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$permission = Permission::withUuid($request->route('uuid'))->first();
		
		//return back()->with('notification', array('indicator'=>'warning', 'message'=>$permission));
		
		if($permission->name == 'view_permissions' 
			|| $permission->name == 'create_permissions' 
			|| $permission->name == 'edit_permissions' 
			|| $permission->name == 'delete_permissions'){
			return back()->with('notification', array('indicator'=>'warning', 'message'=>'You are not allowed to edit or delete \''.$permission->display_name.'\''));
		}
        return $next($request);
    }
}
