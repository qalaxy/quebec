@extends('w3.layout.app')

@section('title')
<title>Error notifications</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Error notifications</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		<div class="w3-row">
			<div class="w3-dropdown-hover w3-right w3-white">
				<button class="w3-button w3-xlarge"><i class="fa fa-bars"></i></button>
				<div class="w3-dropdown-content w3-bar-block w3-border" style="right:0; width:200px;">
					<button href="javascript:void(0)" class="w3-bar-item w3-button  w3-hover-light-blue" 
									onclick="document.getElementById('search').style.display='block'">Search</button>
					<a href="{{url('/error-notifications-pdf')}}"  target="_blank" class="w3-bar-item w3-button  w3-hover-light-blue">PDF format</a>
						
				</div>
			</div>
		</div>
		<div id="search" class="w3-modal">
			<div class="w3-modal-content w3-animate-zoom w3-card-4">
				<header class="w3-container w3-theme"> 
					<span onclick="document.getElementById('search').style.display='none'" 
					class="w3-button w3-display-topright">&times;</span>
					<h2>Search accounts</h2>
				</header>
				<div class="w3-container w3-padding-24">
					<form class="w3-container" method="POST" action="{{url('/error-notifications')}}">
						@csrf
						<div class="w3-row">
							<div class="w3-col s12 m6 l6">
								<div class="w3-row w3-padding-small">
									<div class="w3-col s12 m10 l10 w3-left">
										<label class="w3-text-dark-gray">Name</label>
										<input class="w3-input w3-border {{($errors->has('name')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
												name="name"
												type="text"
												autocomplete="off"
												placeholder="Search by name" 
												value="{{old('name')}}" />
										@if($errors->has('name'))
											<span class="w3-small w3-text-red">{{$errors->first('name')}}</span>
										@else
											<span>&nbsp;</span>
										@endif
									</div>
								</div>
							</div>
							<div class="w3-col s12 m6 l6">
								<div class="w3-row w3-padding-small">
									<div class="w3-col s12 m10 l10 w3-left">
										<label class="w3-text-dark-gray">Display name</label>
										<input class="w3-input w3-border {{($errors->has('display_name')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
												name="display_name"
												type="text"
												autocomplete="off"
												placeholder="Search by display name" 
												value="{{old('display_name')}}" />
										@if($errors->has('display_name'))
											<span class="w3-small w3-text-red">{{$errors->first('display_name')}}</span>
										@else
											<span>&nbsp;</span>
										@endif
									</div>
								</div>	
							</div>
						</div>
						<div class="w3-row">
							<div class="w3-col w3-padding-small">
								<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Search accounts">Go&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div id="delete" class="w3-modal">
			
		</div>
		@include('w3.components.notification')
		@isset($error_notifications)
		<div class="w3-responsive w3-white w3-padding-16 w3-text-dark-gray">
			<table class="w3-table-all w3-hoverable">
				<tr class="w3-theme w3-text-white">
					<th>Number</th>
					<th>Station</th>
					<th>Description</th>
					<th>Status</th>
					<th>Date time created</th>
				</tr>
				@foreach($error_notifications as $error_notification)
				<tr>
					<td class="{{($error_notification->error()->first()->errorStatus()->first()->code == 1)?'w3-text-blue':'w3-text-black'}}">
						<a href="{{url('error/'.$error_notification->first()->error()->first()->uuid)}}" style="text-decoration:none;">
		{{$error_notification->error()->first()->station()->first()->abbreviation}}/
		{{$error_notification->error()->first()->func()->first()->abbreviation}}/
		{{$error_notification->error()->first()->number}}/
		{{date_format(date_create($error_notification->error()->first()->date_time_created), 'y')}}
						</a>
					</td>
					<td class="{{($error_notification->error()->first()->errorStatus()->first()->code == 1)?'w3-text-blue':'w3-text-black'}}">
						{{$error_notification->error()->first()->station()->first()->name}}
					</td>
					<td class="{{($error_notification->error()->first()->errorStatus()->first()->code == 1)?'w3-text-blue':'w3-text-black'}}">
						{{$error_notification->error()->first()->description}}
					</td>
					<td class="{{($error_notification->error()->first()->errorStatus()->first()->code == 1)?'w3-text-blue':'w3-text-black'}}">
						{{$error_notification->error()->first()->errorStatus()->first()->name}}
					</td>
					<td class="{{($error_notification->error()->first()->errorStatus()->first()->code == 1)?'w3-text-blue':'w3-text-black'}}">
						{{date_format(date_create($error_notification->error()->first()->date_time_created), 'd/m/Y H:i:s')}}
					</td>
					
				</tr>
				@endforeach
			</table>
		</div>
		{{$error_notifications->links('vendor.pagination.paginator')}}
		@endisset
	</div>
</div>

<div class="row" style="max-width:75%;">

</div>
@endsection

@section('scripts')
<script>

document.getElementById('notifications').className += " w3-text-blue";
document.getElementById('menu-error').className += " w3-text-blue";
menuAcc('error');
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
