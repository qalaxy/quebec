<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use PDF;

use App\Shell\Web\Extension\SystemErrorExt;
use App\Shell\Web\Monitor\SystemErrorMnt;

class SystemErrorController extends Controller
{
    private $ext;
    private $mnt;

    public function __construct(){
    	$this->ext = new SystemErrorExt();
    	$this->mnt = new SystemErrorMnt();
    }

    public function systemErrors(Request $request){
    	abort(503);
    }
}
