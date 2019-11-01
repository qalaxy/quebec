@extends('w3.layout.app')

@section('title')
<title>Permissions</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Permissions</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		<div class="w3-row">
			<a class="w3-button w3-blue w3-hover w3-hover-light-blue" href="{{asset('/create-error')}}">CREATE</a>
			<button class="w3-button w3-blue w3-hover w3-hover-light-blue w3-right" onclick="document.getElementById('id01').style.display='block'">SEARCH</button>
		</div>
		<div id="id01" class="w3-modal">
			<div class="w3-modal-content w3-animate-zoom w3-card-4">
				<header class="w3-container w3-theme"> 
					<span onclick="document.getElementById('id01').style.display='none'" 
					class="w3-button w3-display-topright">&times;</span>
					<h2>Search permissions</h2>
				</header>
				<div class="w3-container">
					<p>Some text...</p>
					<p>Some form here to insert query paramenters...</p>
				</div>
			</div>
		</div>
		@include('w3.components.notification')
		<div class="w3-responsive w3-white w3-padding-16 w3-text-dark-gray">
			<table class="w3-table-all w3-hoverable">
				<tr class="w3-theme w3-text-white">
					<th>Name</th>
					<th>Description</th>
					<th colspan="2"></th>
				</tr>
				<tr>
					<td>Create system data</td>
					<td>User can create system data</td>
					<td><a>Edit</a></td>
					<td><a>Delete</a></td>
				</tr>
				<tr>
					<td>View error messages</td>
					<td>User can view all error messages</td>
					<td><a>Edit</a></td>
					<td><a>Delete</a></td>
				</tr>
				<tr>
					<td>Edit users</td>
					<td>User should edit all user's information</td>
					<td><a>Edit</a></td>
					<td><a>Delete</a></td>
				</tr>
			</table>
		</div>
		<div class="w3-row w3-padding-16 w3-right">
			<div class="w3-bar w3-border">
			  <a href="#" class="w3-bar-item w3-button">&laquo;</a>
			  <a href="#" class="w3-bar-item w3-button">1</a>
			  <a href="#" class="w3-bar-item w3-button">2</a>
			  <a href="#" class="w3-bar-item w3-button">3</a>
			  <a href="#" class="w3-bar-item w3-button">...</a>
			  <a href="#" class="w3-bar-item w3-button">10</a>
			  <a href="#" class="w3-bar-item w3-button">&raquo;</a>
			</div>
		</div>
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

</script>


@endsection
