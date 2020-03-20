<!DOCTYPE html>
<html>
@yield('title')
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--<meta http-equiv="refresh" content="5"/>-->
<link rel="stylesheet" href="{{asset('public/css/w3.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('public/css/style.css')}}" >
<link rel="icon" href="{{asset('public/images/logo/kcaa.png')}}">
<body>

@yield('style')

<!-- Top -->
<div class="w3-top">
  <div class="w3-row w3-white w3-padding">
    <div class="w3-half" style="margin:4px 0 6px 0"><a href="{{asset('/')}}"><img src="{{asset('public/images/logo/aim.png')}}" alt="Quality Management System"></a></div>
    <div class="w3-half w3-margin-top w3-wide w3-hide-medium w3-hide-small"><div class="w3-right">EFFICIENTLY MANAGING AIR SAFETY</div></div>
  </div>
  <div class="w3-bar w3-theme w3-large" style="z-index:4;">
    <a class="w3-bar-item w3-button w3-left w3-hide-large w3-hover-light-blue w3-large w3-theme w3-padding-16" href="javascript:void(0)" onclick="w3_open()">&#9776;</a>
	<a class="w3-bar-item w3-button w3-hide-medium w3-hide-small w3-hover-light-blue w3-padding-16" href="javascript:void(0)" onclick="w3_show_nav('menuHome')">HOME</a>
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-small w3-hover-light-blue w3-padding-16" href="javascript:void(0)" 
		onclick="w3_show_nav('menuQMS')"
		id="qms-bar-item">QMS</a>
	
	<div class=" w3-bar-item w3-button w3-hover-light-blue w3-padding-16 w3-right w3-dropdown-hover">
	  <span ><i class="fa fa-user fa-lg"></i>&nbsp;{{(Auth::user()) ? Auth::user()->name : null}}&nbsp;<i class="fa fa-caret-down"></i></span>
	  <div id="user" class="w3-dropdown-content w3-bar-block w3-border" style="right:0">
		<a href="{{url('/account/'.Auth::user()->account()->first()->uuid)}}" class="w3-bar-item w3-button w3-hover-light-blue w3-small">Account</a>
    <a href="javascript:void(0)" class="w3-bar-item w3-button w3-hover-light-blue w3-small">Settings</a>
		<a href="{{ route('logout') }}" 
			class="w3-bar-item w3-button w3-hover-light-blue w3-small" 
			onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
		<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
	  </div>
	</div>
</div>
</div>

<!-- Sidebar -->
<div class="w3-sidebar w3-bar-block w3-collapse w3-animate-left" style="z-index:3;width:270px" id="sidebar">
  <div class="w3-bar w3-hide-large w3-large">
    <a href="javascript:void(0)" onclick="w3_show_nav('menuHome')" class="w3-bar-item w3-button w3-theme w3-hover-light-blue w3-padding-5" style="width:100%">Home</a>
	<a href="javascript:void(0)" onclick="w3_show_nav('menuQMS')" class="w3-bar-item w3-button w3-theme w3-hover-light-blue w3-padding-5" style="width:100%; font-size:18px;">QMS</a>
  </div>
    <a href="javascript:void(0)" onclick="w3_close()" class="w3-button w3-right w3-xlarge w3-hide-large" title="Close Menu">&times;</a>
  <div id="menuHome" class="myMenu">
  <div class="w3-container">
    <h3 style="color:#3498DB">Home</h3>
  </div>
  <a class="w3-bar-item w3-button w3-hover-light-blue w3-light-gray" href="{{url('/home#home')}}">Home</a>
  <a class="w3-bar-item w3-button w3-hover-light-blue" href="{{url('/home#home-about')}}">About</a>
  <a class="w3-bar-item w3-button w3-hover-light-blue" href="#">Help</a>
  <!--<a class="w3-bar-item w3-button w3-hover-light-blue" href="{{url('/#contacts')}}">Help/a>-->
  
  </div>
  <div id="menuQMS" class="myMenu" style="display:none">
  <div class="w3-container">
    <h4 style="color:#3498DB">QMS</h4>
  </div>
  
  <button id="menu-administration" class="w3-button w3-block w3-left-align w3-hover-light-blue" onclick="menuAcc('administration')">
	Administration <i class="fa fa-caret-down"></i>
  </button>
  <div id="administration" class="w3-hide w3-white w3-card w3-margin-left w3-leftbar w3-border-gray w3-text-blue-gray">
    <a id="roles" href="{{url('/roles')}}" class="w3-bar-item w3-button w3-hover-light-blue">Roles</a>
    <a id="stations" href="{{url('/stations')}}" class="w3-bar-item w3-button w3-hover-light-blue">Stations</a>
    <a id="products" href="{{url('/products')}}" class="w3-bar-item w3-button w3-hover-light-blue">Products</a>
    <a id="functions" href="{{url('/functions')}}" class="w3-bar-item w3-button w3-hover-light-blue">Functions</a>
    <a id="accounts" href="{{url('/accounts')}}" class="w3-bar-item w3-button w3-hover-light-blue">Users account</a>
  </div>
  <button id="menu-error" class="w3-button w3-block w3-left-align w3-hover-light-blue" onclick="menuAcc('error')">
	<span id="funtion-error">Function errors</span> <i class="fa fa-caret-down"></i>
  </button>
  <div id="error" class="w3-hide w3-white w3-card w3-margin-left w3-leftbar w3-border-gray w3-text-blue-gray">
    <a id="errors" href="{{url('errors')}}" class="w3-bar-item w3-button w3-hover-light-blue">Logs</a>
    <a id="notifications" href="{{asset('/error-notifications')}}" class="w3-bar-item w3-button w3-hover-light-blue">Notified 
		<span class="w3-badge w3-theme w3-small w3-right"></span>
	</a>
  </div>  
  <a id="system-errors" class="w3-bar-item w3-button w3-hover-light-blue" href="{{asset('/system-errors')}}">System errors</a>
  <a class="w3-bar-item w3-button w3-hover-light-blue" href="{{asset('/home')}}">Help</a>
  </div>
</div>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="overlay"></div>

<!-- Main content: shift it to the right by 270 pixels when the sidebar is visible -->
<div class="w3-main w3-container" style="margin-left:270px; margin-top:117px;">

@yield('content')

<footer class="w3-panel w3-padding-32 w3-card-4 w3-light-grey w3-center w3-opacity">
  <p>
	  <nav>
		  <span target="_blank">AIM Kenya</span> |
		  <span target="_top">&copy;2020</span>

      <!-- Developed by Elias Korir and John Njoroge
        AIM Officers at Kenya Civil Aviation Authority
      -->

	  </nav>
  </p>
</footer>

<!-- END MAIN -->
</div>

<script>
// Script to open and close the sidebar
function w3_open() {
    document.getElementById("sidebar").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}
 
function w3_close() {
    document.getElementById("sidebar").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}
function w3_show_nav(name) {
    document.getElementById("menuHome").style.display = "none";
	document.getElementById("menuQMS").style.display = "none";
    document.getElementById(name).style.display = "block";
    w3-open();
}

function menuAcc(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-light-gray";
    } else { 
        x.className = x.className.replace(" w3-show", "");
        x.previousElementSibling.className = 
        x.previousElementSibling.className.replace(" w3-light-gray", "");
    }
}

function myFunction() {
  var x = document.getElementById("user");
  if (x.className.indexOf("w3-show") == -1) { 
    x.className += " w3-show";
  } else {
    x.className = x.className.replace(" w3-show", "");
  }
}

var interval = setInterval(countErrorNotifications, 5000);
countErrorNotifications();

function countErrorNotifications(){
	
	let xhr = new XMLHttpRequest();
	xhr.open("GET", "{{url('/count-error-notifications')}}");
	xhr.send();
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById('notifications').children[0].innerHTML = xhr.responseText;
			if(xhr.responseText >= 1){
				let qms = document.getElementById('qms-bar-item');
				//qms.style.color = 'yellow';
				qms.innerHTML = 'QMS<span class="w3-badge w3-large w3-yellow">'+xhr.responseText+'</span>';
			}
		}
	}
}

</script>

@yield('scripts')

<script src="{{asset('public/js/w3codecolor.js')}}"></script>
<script>
w3CodeColor();
</script>
</body>
</html>