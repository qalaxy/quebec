@extends('w3.layout.app')

@section('title')
<title>Directorates</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Directorates</h1>
	
	<!--<div class=" ">-->
		<div class="w3-row w3-panel" style="max-width:100%;">
			<a class="w3-button w3-blue w3-hover w3-hover-light-blue" href="{{asset('/create-directorate')}}">CREATE</a >
			<button class="w3-button w3-blue w3-hover w3-hover-light-blue">SEARCH</button>
			@include('w3.components.notification')
			<div class="w3-responsive w3-white w3-padding-16">
				<table class="w3-table-all w3-hoverable">
					<tr class="w3-blue w3-text-white w3-large">
						<th>Name</th>
						<th>Abbreviation</th>
						<th colspan="2"></th>
					</tr>
					<tr>
						<td>Air navigation Services</td>
						<td>ANS</td>
						<td><a>Edit</a></td>
						<td><a>Delete</a></td>
					</tr>
					<tr>
						<td>East Africa School of Aviation</td>
						<td>EASA</td>
						<td><a>Edit</a></td>
						<td><a>Delete</a></td>
					</tr>
					<tr>
						<td>Coporate Services</td>
						<td>CS</td>
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

	<!--</div>-->
</div>

<div class="row" style="max-width:75%;">

</div>
@endsection

@section('scripts')
<script>

document.getElementById('directorates').className += " w3-text-blue";
document.getElementById('menu-administration').className += " w3-light-gray";
menuAcc('administration');
w3_show_nav('menuQMS');

</script>


@endsection
