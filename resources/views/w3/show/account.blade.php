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
			
			<div class="w3-row w3-padding-small">
				<div class="w3-col s12 m12 l1 w3-left w3-padding-16">
					<a class="w3-button w3-theme" href="{{($account->uuid)? url('edit-account').'/'.$account->uuid:null}}">Edit</a>
				</div>
				<div class="w3-col s12 m12 l1 w3-left w3-padding-16">
					<a class="w3-button w3-theme" onclick="deleteAccount('{{($account->uuid)?$account->uuid : null}}');">Delete</a>
				</div>
				<div class="w3-col s12 m12 l1 w3-left w3-padding-16">
					<a class="w3-button w3-theme" href="{{($account->uuid)? url('account-emails').'/'.$account->uuid:null}}">Email(s)</a>
				</div>
				<div class="w3-col s12 m12 l2 w3-left w3-padding-16">
					<a class="w3-button w3-theme" href="{{($account->uuid)? url('account-emails').'/'.$account->uuid:null}}">Phone number(s)</a>
				</div>
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
			document.getElementById('delete').style.display='block'
			
		}
	}
}

</script>


@endsection
