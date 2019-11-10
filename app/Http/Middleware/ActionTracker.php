<?php

namespace App\Http\Middleware;

use Closure;
use App\Tracker;
use Illuminate\Support\Facades\Auth;
use Uuid;
class ActionTracker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $action)
    {
        if(!Tracker::create(array('uuid'=>Uuid::generate(),
							'user_id'=>Auth::id(),
							'action'=>$action,
							'date'=>date('Y-m-d'),
							'time'=>date('H:i:s'))
						))
			return back()->with('notification', array('indicator'=>'danger', 'message'=>'Sorry, your actions cannot be tracked'));
		else
			return $next($request);
    }
}
