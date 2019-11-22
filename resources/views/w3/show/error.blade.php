@extends('w3.layout.app')

@section('title')
<title>Error</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Error</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		<div id="delete" class="w3-modal">
			
		</div>
		@include('w3.components.notification')
		<div class="w3-container w3-text-dark-gray">
		<div class="w3-row">
			<div class="w3-col l8">
				<div class="w3-row">
					<div class="w3-dropdown-hover w3-right w3-white">
						<button class="w3-button w3-xlarge"><i class="fa fa-bars"></i></button>
						<div class="w3-dropdown-content w3-bar-block w3-border" style="right:0; width:200px;">
						  <a href="{{url('/edit-error/'.$error->uuid)}}" class="w3-bar-item w3-button">Edit</a>
						  <a href="javascript:void(0)" onclick="deleteError('{{$error->uuid}}');" class="w3-bar-item w3-button">Delete</a>
						  <a href="{{url('/corrective-action/'.$error->uuid)}}" class="w3-bar-item w3-button">Corrective action</a>
						  <a href="{{url('/add-error-affected-product/'.$error->uuid)}}" class="w3-bar-item w3-button">Affected product</a>
						
						</div>
					  </div>
				</div>
				<div class="w3-light-gray w3-topbar w3-border-gray">
					@if($error->number)
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Number: </strong></span>
						</div>
						<div class="w3-col s12 m12 l9 w3-left">
							<span class="">
				{{$error->station()->first()->abbreviation}}/{{$error->func()->first()->abbreviation}}/{{$error->number}}/{{date_format(date_create($error->date_time_created), 'y')}}
							</span>
						</div>
					</div>
					@endif
					@if($error->func()->first()->name)
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Function: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{$error->func()->first()->name}}</span>
						</div>
					</div>
					@endif
					@if($error->description)
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Description: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{$error->description}}</span>
						</div>
					</div>
					@endif
					@if($error->impact)
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Impact: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{$error->impact}}</span>
						</div>
					</div>
					@endif
					@if($error->station()->first()->name)
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Station of origin: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{$error->station()->first()->name}}</span>
						</div>
					</div>
					@endif
					@if($error->date_time_created)
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Date reported: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{date_format(date_create($error->date_time_created), 'd/m/Y H:i:s')}}</span>
						</div>
					</div>
					@endif
					@if($error->responsibility || !$error->responsibility)
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Responsibility: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{($error->responsibility == 1)? $error->user()->first()->name : $error->station()->first()->name}}</span>
						</div>
					</div>
					@endif
					@if($error->remarks)
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Remarks: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{$error->remarks}}</span>
						</div>
					</div>
					@endif
					
					@if($error->user_id)
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Done by: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{$error->user()->first()->name}}</span>
						</div>
					</div>
					@endif
				</div>
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

document.getElementById('errors').className += " w3-text-blue";
document.getElementById('menu-error').className += " w3-text-blue";
menuAcc('error');
w3_show_nav('menuQMS');



function deleteError(uuid){
	let xhr = new XMLHttpRequest();
	
	xhr.open("GET", "{{url('delete-error')}}/"+uuid);
	xhr.send();
	
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			document.getElementById("delete").innerHTML = xhr.responseText;
			document.getElementById('delete').style.display='block';
			
		}
	}
}
</script>


@endsection
