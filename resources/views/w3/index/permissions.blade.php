@extends('w3.layout.app')

@section('title')
<title>Permissions</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Permissions</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		<div class="w3-row">
			<a class="w3-button w3-blue w3-hover w3-hover-light-blue" href="{{asset('/create-permission')}}">CREATE</a>
			<button class="w3-button w3-blue w3-hover w3-hover-light-blue w3-right" onclick="document.getElementById('search').style.display='block'">SEARCH</button>
		</div>
		<div id="search" class="w3-modal">
			<div class="w3-modal-content w3-animate-zoom w3-card-4">
				<header class="w3-container w3-theme"> 
					<span onclick="document.getElementById('search').style.display='none'" 
					class="w3-button w3-display-topright">&times;</span>
					<h2>Search permissions</h2>
				</header>
				<div class="w3-container w3-padding-24">
					<form class="w3-container" method="POST" action="{{url('/permissions')}}">
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
								<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Search permission">Go&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
							</div>
						</div>
					</form>
				</div>
				<!--<footer class="w3-container ">
					<div class="w3-row w3-padding-16">
						<div class="w3-col">
							<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Search permission">Search&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>	
						</div>
					</div>
				</footer>-->
			</div>
		</div>
		<div id="delete" class="w3-modal">
			
		</div>
		@include('w3.components.notification')
		@isset($permissions)
		<div class="w3-responsive w3-white w3-padding-16 w3-text-dark-gray">
			<table class="w3-table-all w3-hoverable">
				<tr class="w3-theme w3-text-white">
					<th>Name</th>
					<th>Description</th>
					<th colspan="2"></th>
				</tr>
				@foreach($permissions as $permission)
				<tr>
					<td><a href="{{url('permission/'.$permission->uuid)}}" style="text-decoration:none;">{{$permission->display_name}}</a></td>
					<td><a href="{{url('permission/'.$permission->uuid)}}" style="text-decoration:none;">{{$permission->description}}</a></td>
					<td><a class="w3-button" href="{{url('edit-permission/'.$permission->uuid)}}" title="Edit {{$permission->display_name}}"><i class="fa fa-edit fa-lg"></i></a></td>
					<td><button class="w3-button" onclick="deletePerm('{{$permission->uuid}}');" title="Delete {{$permission->display_name}}">
						<i class="fa fa-trash fa-lg"></i>
						</button>
					</td>
				</tr>
				@endforeach
			</table>
		</div>
		{{$permissions->links('vendor.pagination.paginator')}}
		@endisset
	</div>
</div>

<div class="row" style="max-width:75%;">

</div>
@endsection

@section('scripts')
<script>

document.getElementById('permissions').className += " w3-text-blue";
document.getElementById('menu-administration').className += " w3-text-blue";
menuAcc('administration');
w3_show_nav('menuQMS');

function deletePerm(uuid){
	let xhr = new XMLHttpRequest();
	
	xhr.open("GET", "{{url('delete-permission')}}/"+uuid);
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
