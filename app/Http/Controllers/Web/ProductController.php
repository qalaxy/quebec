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
}
