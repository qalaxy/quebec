@extends('w3.layout.app')

@section('title')
<title>QMS-Error logs</title>
@endsection

@section('style')
<style>
	#home{
		padding:100px 100px 100px 100px; 
		background: url({{url('public/images/kcaa.jpg')}}); 
		background-repeat: no-repeat; 
		background-size: 100% 100%; 
		min-height:1000px;
	}

	.home{
	  		padding-top:300px;
	  	}

	.home-btn:hover{
		background: #00CED1;
		border-color: #0000FF;
		color:white;
		text-shadow: 5px 5px 5px #000000;
	}

	.home-btn{
		color:#ffffff; 
		/*text-shadow: 10px 10px 10px #000000;*/
		background: #00BFFF;
	}
	@media only screen and (max-width: 600px) {
	  	.home-btn {
	    	background-color: #00BFFF;
	  	}

	  	#home{
	  		padding: 50px 50px 50px 50px;
	  		/*background: url({{url('public/images/kcaa.jpg')}}); */
			background-repeat: no-repeat; 
			background-size: 100% 100%; 
			min-height:100px;
	  	}

	  	.home{
	  		padding-top:50px;
	  	}
	}
</style>
@endsection

@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:1000px;">
	@include('w3.components.notification')
  	<div id="home" class="w3-container">
  		<div class="w3-cell-row w3-section home" style="">
  			<div class="w3-container w3-cell w3-cell-middle w3-mobile w3-center">
    			<a href="{{url('/errors')}}" class="w3-btn w3-border w3-border-white w3-round-xlarge w3-xlarge home-btn">ERRORS LIST&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
  			</div>
  			<div class="w3-container w3-cell w3-cell-middle w3-mobile">
    			<a id="home-create-error" href="{{url('create-error')}}" class="w3-btn w3-border w3-border-white w3-round-xlarge w3-xlarge home-btn">REPORT ERROR&nbsp;<i class="fa fa-angle-right fa-lg"></i></a>
 			</div>
  		</div>
  	</div>

  	<div class="w3-container w3-center w3-padding-64">
  		<h2 id="home-about" class="w3-text-blue">About</h2>
  		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br />Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
  	</div>
	
</div>

<div class="row" style="max-width:75%;">

</div>
@endsection

@section('scripts')
<script>

document.getElementById('logs').className += " w3-text-blue";
document.getElementById('menu-error').className += " w3-text-blue";
menuAcc('error');
w3_show_nav('menuQMS');

</script>


@endsection
