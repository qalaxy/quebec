<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Shell\Web\Extension\ProductExt;
use App\Shell\Web\Monitor\ProductMnt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use PDF;


class ProductController extends Controller
{
    public $ext;
    public $mnt;

    public function __construct(){
    	$this->ext = new ProductExt();
    	$this->mnt = new ProductMnt();
    }

    public function products(Request $request){
    	if(Auth::user()->can('view_products')){
    		if(count($request->all())){
				$products = $this->ext->searchProducts($request->all());
			}else{				
				$products = $this->ext->getPaginatedProducts();
			}
			if(is_object($products)){
				if(View::exists('w3.index.products'))
					if(count($products))
						return view('w3.index.products')->with(compact('products'));
					else
						return view('w3.index.products')->with(compact('products'))
								->with('notification', array('indicator'=>'warning', 'message'=>'Products(s) not found'));
				else{
					return back()->with('notification', $this->ext->missing_view);
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=>$products));
			}

    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view products'));
    	}
    }

    public function createProduct(){
    	if(Auth::user()->can('view_products')){
    		if(View::exists('w3.create.product')){
    			return view('w3.create.product')->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
    		}else{
    			return back()->with('notification', $this->ext->missing_view);
    		}
    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create a product'));
    	}
    }

    public function storeProduct(Request $request){
    	if(Auth::user()->can('view_products')){
    		$validation = $this->ext->validateProductData($request->all());
    		if($validation->fails()){
    			return back()->withErrors($validation)->withInput()
    					->with('notification', $this->ext->validation);
    		}

    		$notification = $this->mnt->createProduct($request->all());
    		if(in_array('success', $notification)){
    			if(View::exists('w3.show.product')){
    				return redirect('product/'.$notification['uuid'])->with(compact('notification'));
    			}else{
    				return back()->with(compact('notification'))->withInput();
    			}
    		}else{
    			return back()->with(compact('notification'))->withInput();
    		}
    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to create a product'));
    	}
    }

    public function product($uuid){
    	if(Auth::user()->can('view_products')){
    		$product = $this->ext->getProduct($uuid);
    		if(!is_object($product))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=>$product));

    		if(View::exists('w3.show.product')){
    			return view('w3.show.product')->with(compact('product'));
    		}else{
    			return back()->with('notification', $this->ext->missing_view);
    		}
    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to view a product'));
    	}
    }

    public function editProduct($uuid){
    	if(Auth::user()->can('edit_products')){
    		$product = $this->ext->getProduct($uuid);
    		if(!is_object($product))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=>$product));

    		if(View::exists('w3.edit.product')){
    			return view('w3.edit.product')->with(compact('product'))->with('notification', array('indicator'=>'information', 'message'=>'All fields with * should not be left blank'));
    		}else{
    			return back()->with('notification', $this->ext->missing_view);
    		}
    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit a product'));
    	}
    }

    public function updateProduct(Request $request, $uuid){
    	if(Auth::user()->can('edit_products')){
    		$validation = $this->ext->validateProductData($request->all(), $uuid);
    		if($validation->fails()){
    			return back()->withErrors($validation)->withInput()
    					->with('notification', $this->ext->validation);
    		}

    		$product = $this->ext->getProduct($uuid);
    		if(!is_object($product))
    			return back()->with('notification', array('indicator'=>'warning', 'message'=>$product));

    		$notification = $this->mnt->editProduct($request->all(), $product); //return $notification;
    		if(in_array('success', $notification)){
    			if(View::exists('w3.show.product')){
    				return redirect('product/'.$uuid)->with(compact('notification'));
    			}else{
    				return back()->with(compact('notification'))->withInput();
    			}
    		}else{
    			return back()->with(compact('notification'))->withInput();
    		}

    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to edit a product'));
    	}
    }

    public function deleteProduct($uuid){
    	if(session()->has('params')) session()->reflash();
		
		if(Auth::user()->can('delete_products')){
			
			$product = $this->ext->getProduct($uuid);
			if(is_object($product)){
				return $this->ext->deleteProduct($product);
			}else{
				return $this->ext->invalidDeletion('Error occurred while deleting the product', 'w3-orange');
			}
		}else{
			return $this->ext->invalidDeletion('You are not allowed to delete an AIM product', 'w3-orange');
		}
    }

    public function destroyProduct($uuid){
    	if(Auth::user()->can('delete_products')){
    		$product = $this->ext->getProduct($uuid);
			if(is_object($product)){
				$notification = $this->mnt->deleteProduct($product);
				if(in_array('success', $notification)){
					if(View::exists('w3.index.products')){
						return redirect('products')
									->with(compact('notification'));
					}else
						return back()->with(compact('notification'));
				}else{
					return back()->with(compact('notification'));
				}
			}else{
				return back()->with('notification', array('indicator'=>'warning', 'message'=> $product));
			}
    	}else{
    		return back()->with('notification', array('indicator'=>'danger', 'message'=>'You are not allowed to delete a product'));
    	}
    }
}
