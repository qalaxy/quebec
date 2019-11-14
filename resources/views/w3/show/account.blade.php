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
					  <a href="{{url('/')}}" class="w3-bar-item w3-button">Roles</a>
					  <a href="{{url('/')}}" class="w3-bar-item w3-button">Supervisory</a>
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
							<span class="w3-hover-text-blue" onmouseover="document.getElementById('{{$email->uuid}}').style.display='inline'" 
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

function deleteAccount(uuid){
	let xhr = new XMLHttpRequest();
	
	xhr.open("GET", "{{url('delete-account')}}/"+uuid);
	xhr.send();
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById("delete").innerHTML = xhr.responseText;
			document.getElementById('delete').style.display='block'
			
		}
	}
}

</script>


@endsection
