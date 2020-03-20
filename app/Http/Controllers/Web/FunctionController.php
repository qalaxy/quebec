<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use App\Shell\Web\Extension\FunctionExt;
use App\Shell\Web\Monitor\FunctionMnt;

class FunctionController extends Controller
{
    private $ext;
    private $mnt;

    public function __construct(){
    	$this->ext = new FunctionExt();
    	$this->mnt = new FunctionMnt();
    }

    public function functions(Request $request){
    	if(Auth::user()->can('view_functions')){
    		if(count($request->all())){
				$functions = $this->ext->searchFunctions($request->all());
			}else{				
				$functions = $this->ext->getPaginatedFunctions();
			}
			if(is_object($functions)){
				if(View::exists('w3.index.functions'))
					if(count($functions))
						return view('w3.index.functions')->with(compact('functions'));
					else
						return view('w3.index.functions')->with(compact('functions'))
								->with('notification', array('indicator'=>'warning', 'message'=>'Function(s) not found'));
				else{
					return back()->with('notification', $this->ext->missing_view);
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$functions));
			}

    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view functions'));
    	}
    }

    public function func($uuid){
    	if(Auth::user()->can('view_functions')){
    		$func = $this->ext->getFunction($uuid);

    		if(is_object($func)){
				if(View::exists('w3.show.function')){
					return view('w3.show.function')->with(compact('func'));
				}else{
					return back()->with('notification', array('indicator'=>'danger', 'message'=>$this->ext->missing_view));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $func));
			}

    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view functions'));
    	}
    }

    public function functionProducts($uuid){
    	if(Auth::user()->can('view_functions')){
    		$func = $this->ext->getFunction($uuid); 
    		if(!is_object($func))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=> $func));

    		$products = $this->ext->getFunctionProducts($func);

    		if(is_object($products)){
				if(View::exists('w3.index.function-products')){
					return view('w3.index.function-products')->with(compact('func', 'products'));
				}else{
					return back()->with('notification', array('indicator'=>'danger', 'message'=>$this->ext->missing_view));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $products));
			}

    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view functions'));
    	}
    }
}
