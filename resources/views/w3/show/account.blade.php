@extends('w3.layout.app')

@section('title')
<title>User account</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">User account</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		<div id="delete" class="w3-modal">
			
		</div>
		@include('w3.components.notification')
		<div class="w3-container w3-text-dark-gray">
			<div class="w3-row">
				<div class="w3-dropdown-hover w3-right w3-white">
					<button class="w3-button w3-xlarge"><i class="fa fa-bars"></i></button>
					<div class="w3-dropdown-content w3-bar-block w3-border" style="right:0; width:200px;">
					  <a href="{{url('/')}}" class="w3-bar-item w3-button">Edit</a>
					  <a href="{{url('/')}}" class="w3-bar-item w3-button">Delete</a>
					  <a href="{{url('/add-')}}" class="w3-bar-item w3-button">Roles</a>
					  @if(!$account->accountStation()->get())
						<a href="{{url('/add-station/'.$account->uuid)}}" class="w3-bar-item w3-button">Station</a>
					  @endif
					  <a href="{{url('/add-supervisory/'.$account->uuid)}}" class="w3-bar-item w3-button">Supervisory</a>
					</div>
				  </div>
			</div>
			<div class="w3-light-gray w3-topbar w3-border-gray">
				@if($account->first_name)
				<div class="w3-row w3-padding-small">
					<div class="w3-col s12 m12 l2 w3-left">
						<span class=""><strong>First name: </strong></span>
					</div>
					<div class="w3-col s12 m12 l10 w3-left">
						<span class="">{{$account->first_name}}</span>
					</div>
				</div>
				@endif
				@if($account->middle_name)
				<div class="w3-row w3-padding-small">
					<div class="w3-col s12 m12 l2 w3-left">
						<span class=""><strong>Middle name: </strong></span>
					</div>
					<div class="w3-col s12 m12 l10 w3-left">
						<span class="">{{$account->middle_name}}</span>
					</div>
				</div>
				@endif
				@if($account->last_name)
				<div class="w3-row w3-padding-small">
					<div class="w3-col s12 m12 l2 w3-left">
						<span class=""><strong>Last name: </strong></span>
					</div>
					<div class="w3-col s12 m12 l10 w3-left">
						<span class="">{{$account->last_name}}</span>
					</div>
				</div>
				@endif
				@if($account->p_number)
				<div class="w3-row w3-padding-small">
					<div class="w3-col s12 m12 l2 w3-left">
						<span class=""><strong>Personal number: </strong></span>
					</div>
					<div class="w3-col s12 m12 l10 w3-left">
						<span class="">{{$account->p_number}}</span>
					</div>
				</div>
				@endif
			</div>
			<div class="w3-row ">
				<div class="w3-dropdown-hover w3-right w3-white">
					<button class="w3-button w3-xlarge"><i class="fa fa-bars"></i></button>
					<div class="w3-dropdown-content w3-bar-block w3-border" style="right:0; width:200px;">
					  <a href="{{url('/add-email/'.$account->uuid)}}" class="w3-bar-item w3-button">Add email</a>
					  <a href="{{url('/')}}" class="w3-bar-item w3-button">Add phone number</a>
					</div>
				  </div>
			</div>
			<div class="w3-light-gray w3-topbar w3-border-gray">
				@if($account)
				<div class="w3-row w3-padding-small">
					<div class="w3-col s12 m12 l2 w3-left">
						<span class=""><strong>Phone numbers: </strong></span>
					</div>
					<div class="w3-col s12 m12 l10 w3-left">
						@foreach($account->phoneNumber()->get() as $phone)
							<span class="">{{$phone->number}}</span>
						@endforeach
						
					</div>
				</div>
				@endif
				@if($account)
				<div class="w3-row w3-padding-small">
					<div class="w3-col s12 m12 l2 w3-left">
						<span class=""><strong>Email address(s): </strong></span>
					</div>
					<div class="w3-col s12 m12 l10 w3-left">
						@foreach($account->email()->distinct()->get() as $email)
							<span class="w3-hover-text-blue" onmouseover="document.getElementById('{{$email->uuid}}').style.display='inline';" 
												onmouseout="document.getElementById('{{$email->uuid}}').style.display='none'">{{$email->address}}
								<span id="{{$email->uuid}}" style="display:none;">
									<a href="{{url('/edit-email/'.$account->uuid.'/'.$email->uuid)}}" class="w3-hover-blue"><i class="fa fa-edit fa-lg"></i></a>
									<a class="w3-hover-blue" onclick="deleteEmail('{{$account->uuid}}', '{{$email->uuid}}');"><i class="fa fa-trash fa-lg"></i></a>
								</span>,
							</span>
						@endforeach
					</div>
				</div>
				@endif
			</div>
			<div class="w3-row ">
				<div class="w3-dropdown-hover w3-right w3-white">
					<button class="w3-button w3-xlarge"><i class="fa fa-bars"></i></button>
					<div class="w3-dropdown-content w3-bar-block w3-border" style="right:0; width:200px;">
					  <a href="{{($account)?url('/add-station/'.$account->uuid):null}}" class="w3-bar-item w3-button">Add a station</a>
					  <a href="{{($account)?url('/account-stations/'.$account->uuid):null}}" class="w3-bar-item w3-button">All stations</a>
					</div>
				  </div>
			</div>
			<div class="w3-light-gray w3-topbar w3-border-gray">
				@if($account)
				<div class="w3-row w3-padding-small">
					<div class="w3-col s12 m12 l2 w3-left">
						<span class=""><strong>Station(s): </strong></span>
					</div>
					<div class="w3-col s12 m12 l10 w3-left">
						@foreach($account->accountStation()->distinct()->get() as $station)
							@if(isset($station->to) && $station->to > date_format(today(),'Y-m-d') || $station->status)
							<span class="w3-hover-text-blue" onmouseover="document.getElementById('{{$station->uuid}}').style.display='inline'; this.style.cursor='pointer';" 
												onmouseout="document.getElementById('{{$station->uuid}}').style.display='none'">
												<span onmousedown="loadAccountStation(event, '{{$station->uuid}}')" title="Press ALT + left click on station to view station">{{$station->station()->first()->name}}</span>
								<span id="{{$station->uuid}}" style="display:none;">
									<a href="{{url('/edit-account-station/'.$account->uuid.'/'.$station->uuid)}}" class="w3-hover-blue"><i class="fa fa-edit fa-lg"></i></a>
									<a class="w3-hover-blue" onclick="deleteStation('{{$account->uuid}}', '{{$station->uuid}}');"><i class="fa fa-trash fa-lg"></i></a>
								</span>,
							</span>
							@endif
						@endforeach
					</div>
				</div>
				@endif
			</div>
		</div>
	</div>
</div>

<div class="row" style="max-width:75%;">

</div>
@endsection

@section('scripts')
<script>

document.getElementById('accounts').className += " w3-text-blue";
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

function deleteEmail(account,email){ 
	let xhr = new XMLHttpRequest();
	
	xhr.open("GET", "{{url('delete-email')}}/"+account+"/"+email);
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

function deleteStation(account, station){
	let xhr = new XMLHttpRequest();
	
	
	//http://127.0.0.1/quebec/delete-account-station/94982c10-0888-11ea-b3bc-b15b68e88e82/9fef47b0-0888-11ea-b70f-458597f48fbd
	xhr.open("GET", "{{url('delete-account-station')}}/"+account+"/"+station);
	xhr.send();
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById('delete').innerHTML = xhr.responseText;
			document.getElementById('delete').style.display='block';
		}
	}
}

</script>


@endsection
