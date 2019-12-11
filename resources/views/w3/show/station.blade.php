@extends('w3.layout.app')

@section('title')
<title>Station</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Station</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		<div id="delete" class="w3-modal">
			
		</div>
		@include('w3.components.notification')
		<div class="w3-container w3-text-dark-gray">
			<div class="w3-row">
				<div class="w3-dropdown-hover w3-right w3-white">
					<button class="w3-button w3-xlarge"><i class="fa fa-bars"></i></button>
					<div class="w3-dropdown-content w3-bar-block w3-border" style="right:0; width:200px;">
					  <a href="{{url('/edit-station/'.$station->uuid)}}" class="w3-bar-item w3-button">Edit</a>
					  <a href="javascript:void(0)" onclick="deleteStation('{{$station->uuid}}');" class="w3-bar-item w3-button">Delete</a>
					  @if(is_null($station->func()->first()))
					  <a href="{{url('/add-station-function/'.$station->uuid)}}" class="w3-bar-item w3-button">Functions</a>
						@endif
					  @if(is_null($station->recipient()->first()))
						<a href="{{url('/add-station-recipient/'.$station->uuid)}}" class="w3-bar-item w3-button">Notification recipients</a>
					  @endif
					</div>
				  </div>
			</div>
			<div class="w3-light-gray w3-topbar w3-border-gray">
				@if($station->name)
				<div class="w3-row w3-padding-small">
					<div class="w3-col s12 m12 l2 w3-left">
						<span class=""><strong>Name: </strong></span>
					</div>
					<div class="w3-col s12 m12 l10 w3-left">
						<span class="">{{$station->name}}</span>
					</div>
				</div>
				@endif
				@if($station->abbreviation)
				<div class="w3-row w3-padding-small">
					<div class="w3-col s12 m12 l2 w3-left">
						<span class=""><strong>Code: </strong></span>
					</div>
					<div class="w3-col s12 m12 l10 w3-left">
						<span class="">{{$station->abbreviation}}</span>
					</div>
				</div>
				@endif
			</div>
			@if($station->func()->first())
			<div class="w3-row ">
				<div class="w3-dropdown-hover w3-right w3-white">
					<button class="w3-button w3-xlarge"><i class="fa fa-bars"></i></button>
					<div class="w3-dropdown-content w3-bar-block w3-border" style="right:0; width:200px;">
					  <a href="{{($station)?url('/add-station-function/'.$station->uuid):null}}" class="w3-bar-item w3-button">Add a function</a>
					</div>
				  </div>
			</div>
			<div class="w3-light-gray w3-topbar w3-border-gray">
				@if($station)
				<div class="w3-row w3-padding-small">
					<div class="w3-col s12 m12 l2 w3-left">
						<span class=""><strong>Function(s): </strong></span>
					</div>
					<div class="w3-col s12 m12 l10 w3-left">
						@foreach($station->func()->get() as $func)
							<span class="w3-hover-text-blue" onmouseover="document.getElementById('{{$func->uuid}}').style.display='inline';  this.style.cursor='pointer';" 
												onmouseout="document.getElementById('{{$func->uuid}}').style.display='none'">
												<span onmousedown="loadStationFunction(event, '{{$func->uuid}}')" title="Press ALT + left click on function">
													{{$func->name}}
												</span>
								<span id="{{$func->uuid}}" style="display:none;">
									<a class="w3-hover-blue" onclick="deleteFunction('{{$station->uuid}}', '{{$func->uuid}}');"><i class="fa fa-close fa-lg"></i></a>
								</span>,
							</span>
						@endforeach
					</div>
				</div>
				@endif
			</div>
			@endif
			@if($station->recipient()->first())
			<div class="w3-row ">
				<div class="w3-dropdown-hover w3-right w3-white">
					<button class="w3-button w3-xlarge"><i class="fa fa-bars"></i></button>
					<div class="w3-dropdown-content w3-bar-block w3-border" style="right:0; width:200px;">
					  <a href="{{($station)?url('/add-station-recipient/'.$station->uuid):null}}" class="w3-bar-item w3-button">Add a notification recipient</a>
					</div>
				  </div>
			</div>
			<div class="w3-light-gray w3-topbar w3-border-gray">
				@if($station)
				<div class="w3-row w3-padding-small">
					<div class="w3-col s12 m12 l2 w3-left">
						<span class=""><strong>Recipient(s): </strong></span>
					</div>
					<div class="w3-col s12 m12 l10 w3-left">
						@foreach($station->recipient()->get() as $recipient)
							<span class="w3-hover-text-blue" onmouseover="document.getElementById('{{$recipient->uuid}}').style.display='inline';  this.style.cursor='pointer';" 
												onmouseout="document.getElementById('{{$recipient->uuid}}').style.display='none'">
												<span onmousedown="loadStationFunction(event, '{{$recipient->uuid}}')" title="Press ALT + left click on function">
													{{$recipient->user()->first()->name}}
												</span>
								<span id="{{$recipient->uuid}}" style="display:none;">
									<a class="w3-hover-blue" onclick="deleteRecipient('{{$station->uuid}}', '{{$recipient->user()->first()->uuid}}');">
										<i class="fa fa-close fa-lg"></i>
									</a>
								</span>,
							</span>
						@endforeach
					</div>
				</div>
				@endif
			</div>
			@endif
		</div>
	</div>
</div>

<div class="row" style="max-width:75%;">

</div>
@endsection

@section('scripts')
<script>

document.getElementById('stations').className += " w3-text-blue";
document.getElementById('menu-administration').className += " w3-text-blue";
menuAcc('administration');
w3_show_nav('menuQMS');



function deleteAccount(uuid){
	let xhr = new XMLHttpRequest();
	
	xhr.open("GET", "{{url('delete-account')}}/"+uuid);
	xhr.send();
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById("delete").innerHTML = xhr.responseText;
			document.getElementById('delete').style.display='block';
			
		}
	}
}

function deleteFunction(station,func){ 
	let xhr = new XMLHttpRequest();
	
	xhr.open("GET", "{{url('delete-station-function')}}/"+station+"/"+func);
	xhr.send();
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById("delete").innerHTML = xhr.responseText;
			document.getElementById('delete').style.display='block'
			
		}
	}
}

function loadAccountStation(event, station){
	if(event.altKey){
		let xhr = new XMLHttpRequest();
		xhr.open("GET", "{{url('account-station')}}/"+station);
		xhr.send();
		
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				document.getElementById("delete").innerHTML = xhr.responseText;
				document.getElementById('delete').style.display='block'
				
			}
		}
	}
	
}

function deleteRecipient(station, user){
	let xhr = new XMLHttpRequest();
	
	xhr.open("GET", "{{url('delete-station-recipient')}}/"+station+"/"+user);
	xhr.send();
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById('delete').innerHTML = xhr.responseText;
			document.getElementById('delete').style.display='block';
		}
	}
}

function loadAccountSupervisory(event, supervisory){
	if(event.altKey){
		let xhr = new XMLHttpRequest();
		xhr.open("GET", "{{url('account-supervisory')}}/"+supervisory);
		xhr.send();
		
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				document.getElementById("delete").innerHTML = xhr.responseText;
				document.getElementById('delete').style.display='block'
				
			}
		}
	}
	
}

function deleteSupervisory(account, supervisory){
	let xhr = new XMLHttpRequest();

	xhr.open("GET", "{{url('delete-account-supervisory')}}/"+account+"/"+supervisory);
	xhr.send();
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById('delete').innerHTML = xhr.responseText;
			document.getElementById('delete').style.display='block';
		}
	}
}

function deleteRole(account, role){
	let xhr = new XMLHttpRequest();

	xhr.open("GET", "{{url('delete-account-role')}}/"+account+"/"+role);
	xhr.send();
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById('delete').innerHTML = xhr.responseText;
			document.getElementById('delete').style.display='block';
		}
	}
}

function loadAccountRole(event, role){
	if(event.altKey){
		let xhr = new XMLHttpRequest();
		xhr.open("GET", "{{url('account-role')}}/"+role);
		xhr.send();
		
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200){
				document.getElementById("delete").innerHTML = xhr.responseText;
				document.getElementById('delete').style.display='block'
				
			}
		}
	}
}
</script>


@endsection
