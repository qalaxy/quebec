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

    public function createFunction(){
    	if(Auth::user()->can('create_functions')){
    		if(View::exists('w3.create.function')){
    			return view('w3.create.function')->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
    		}else{
    			return back()->with('notification', $this->ext->missing_view);
    		}
    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create functions'));
    	}
    }


    public function storeFunction(Request $request){
    	if(Auth::user()->can('create_functions')){
    		$validation = $this->ext->validateFunctionData($request->all());
    		if($validation->fails()){
    			return back()->withErrors($validation)->withInput()->with('notification', $this->ext->validation);
    		}

    		$notification = $this->mnt->createFunction($request->all());
    		if(in_array('success', $notification)){
    			if(View::exists('w3.show.function')){
    				return redirect('function/'.$notification['uuid'])->with(compact('notification'));
    			}else{
    				return back()->withInput()->with(compact('notification'));
    			}
    		}else{
    			return back()->withInput()->with(compact('notification'));
    		}

    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create functions'));
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

    public function editFunction($uuid){
    	if(Auth::user()->can('edit_functions')){
    		$func = $this->ext->getFunction($uuid);
    		if(!is_object($func))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=>$func));

    		if(View::exists('w3.edit.function')){
    			return view('w3.edit.function')->with(compact('func'))->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
    		}else{
    			return back()->with('notification', $this->ext->missing_view);
    		}
    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit functions'));
    	}    		
    }

    public function updateFunction(Request $request, $uuid){
    	if(Auth::user()->can('edit_functions')){
    		$validation = $this->ext->validateFunctionData($request->all(), $uuid);
    		if($validation->fails()){
    			return back()->withErrors($validation)->withInput()->with('notification', $this->ext->validation);
    		}

    		$func = $this->ext->getFunction($uuid);
    		if(!is_object($func))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=>$func));

    		$notification = $this->mnt->editFunction($request->all(), $func);
    		if(in_array('success', $notification)){
    			if(View::exists('w3.show.function')){
    				return redirect('function/'.$uuid)->with(compact('notification'));
    			}else{
    				return back()->withInput()->with(compact('notification'));
    			}
    		}else{
    			return back()->withInput()->with(compact('notification'));
    		}

    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit functions'));
    	}
    }

    public function deleteFunction($uuid){
    	if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('delete_functions')){
			
			$func = $this->ext->getFunction($uuid);
			if(is_object($func)){
				return $this->ext->deleteFunction($func);
			}else{
				return $this->ext->invalidDeletion('Error occurred while deleting the AIM function. '.$func, 'w3-orange');
			}
		}else{
			return $this->ext->invalidDeletion('You are not allowed to delete an AIM function', 'w3-orange');
		}
    }

    public function destroyFunction($uuid){
    	if(Auth::user()->can('delete_functions')){
    		$function = $this->ext->getFunction($uuid);
			if(is_object($function)){
				$notification = $this->mnt->deleteFunction($function);
				if(in_array('success', $notification)){
					if(View::exists('w3.index.functions')){
						return redirect('functions')
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $function));
			}
    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete an AIM function'));
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

    public function createFunctionProduct($uuid){
    	if(Auth::user()->can('create_functions')){
    		$func = $this->ext->getFunction($uuid); 
    		if(!is_object($func))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=> $func));

    		$products = $this->ext->getUnaddedProducts($func);
    		if(!is_object($products))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=> $products));

    		if(View::exists('w3.create.function-product')){
    			if(count($products)){
    				return view('w3.create.function-product')->with(compact('products', 'func'))
    						->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
    			}else{
    				return view('w3.create.function-product')->with(compact('products', 'func'))
    						->with('notification', array('indicator'=>'information', 'message'=>'Currently no product to add to the function'));
    			}    			
    		}else{
    			return back()->with('notification', $this->ext->missing_view);
    		}

    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view functions'));
    	}
    }

    public function storeFunctionProduct(Request $request, $uuid){
    	if(Auth::user()->can('create_functions')){
    		$validation = $this->ext->validateFunctionProductData($request->all());
    		if($validation->fails()){
    			$e = '';
    			foreach($validation->errors()->all() as $error){
    				$e .= $error. '. ';
    			}
    			$this->ext->validation['message'] = $e;
    			return back()->withErrors($validation)->withInput()
    					->with('notification', $this->ext->validation);
    		}

    		$func = $this->ext->getFunction($uuid); 
    		if(!is_object($func))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=> $func));

    		$product = $this->ext->getProduct($request['product_id']);
    		if(!is_object($product))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=> $product));

    		$notification = $this->mnt->createFunctionProduct($func, $product);
    		if(in_array('success', $notification)){
    			if(View::exists('w3.index.function-products')){
    				return redirect('function-products/'.$uuid)->with(compact('notification'));
    			}else{
    				return back()->withInput()->with(compact('notification'));
    			}
    		}else{
    			return back()->withInput()->with(compact('notification'));
    		}

    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view functions'));
    	}
    }

    public function deleteFunctionProduct($function, $product){
    	if(session()->has('params')) session()->reflash();
    	if(Auth::user()->can('delete_functions')){
    		$func = $this->ext->getFunction($function); 
    		if(!is_object($func))
    			return $this->ext->invalidDeletion($func, 'w3-orange');

    		$product = $this->ext->getProduct($product);
    		if(!is_object($product))
    			return $this->ext->invalidDeletion($product, 'w3-orange');

    		return $this->ext->deleteFunctionProduct($func, $product);

    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view functions'));
    	}
    }

    public function destroyFunctionProduct($func, $product){
    	if(Auth::user()->can('delete_functions')){

    		$func = $this->ext->getFunction($func); 
    		if(!is_object($func))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=> $func));

    		$product = $this->ext->getProduct($product);
    		if(!is_object($product))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=> $product));

    		$notification = $this->mnt->deleteFunctionProduct($func, $product);
    		if(in_array('success', $notification)){
				if(View::exists('w3.index.function-products')){
					return redirect('function-products/'.$func->uuid)
								->with(compact('notification'));
				}else
					return back()->with(compact('notification'));
			}else{
				return back()->with(compact('notification'));
			}
    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete an AIM function'));
    	}
    }
}
