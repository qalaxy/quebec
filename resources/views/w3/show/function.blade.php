@extends('w3.layout.app')

@section('title')
<title>Function</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">AIM function</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		<div id="delete" class="w3-modal">
			
		</div>
		@include('w3.components.notification')
		<div class="w3-container w3-text-dark-gray">
			
			
			@if($func->name)
			<div class="w3-row w3-padding-small">
				<div class="w3-col s12 m12 l2 w3-left">
					<span class=""><strong>Name: </strong></span>
				</div>
				<div class="w3-col s12 m12 l10 w3-left">
					<span class="">{{$func->name}}</span>
				</div>
			</div>
			@endif

			@if($func->abbreviation)
			<div class="w3-row w3-padding-small">
				<div class="w3-col s12 m12 l2 w3-left">
					<span class=""><strong>Abbreviation: </strong></span>
				</div>
				<div class="w3-col s12 m12 l10 w3-left">
					<span class="">{{$func->abbreviation}}</span>
				</div>
			</div>
			@endif
			
			@if($func->description)
			<div class="w3-row w3-padding-small">
				<div class="w3-col s12 m12 l2 w3-left">
					<span class=""><strong>Description: </strong></span>
				</div>
				<div class="w3-col s12 m12 l10 w3-left">
					<span class="">{{$func->description}}</span>
				</div>
			</div>
			@endif

			<div class="w3-row w3-padding-small">
				<div class="w3-col s12 m12 l1 w3-left">
					<a class="w3-button w3-theme" href="{{($func->uuid)? url('edit-function').'/'.$func->uuid:null}}">Edit</a>
				</div>
				<div class="w3-col s12 m12 l2 w3-left">
					<a class="w3-button w3-theme" onclick="deleteFunction('{{($func->uuid)?$func->uuid : null}}');">Delete</a>
				</div>
				<div class="w3-col s12 m12 l2 w3-left">
					<a class="w3-button w3-theme" href="{{($func->uuid)? url('function-products').'/'.$func->uuid:null}}">Function's products</a>
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

document.getElementById('functions').className += " w3-text-blue";
document.getElementById('menu-administration').className += " w3-text-blue";
menuAcc('administration');
w3_show_nav('menuQMS');

function deleteFunction(uuid){
	let xhr = new XMLHttpRequest();
	
	xhr.open("GET", "{{url('delete-function')}}/"+uuid);
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
