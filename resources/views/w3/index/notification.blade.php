@extends('w3.layout.app')

@section('title')
<title>Notified errors</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Notified errors</h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		<div class="w3-row">
			<button class="w3-button w3-blue w3-hover w3-hover-light-blue w3-right" onclick="document.getElementById('id01').style.display='block'">SEARCH</button>
		</div>
		<div id="id01" class="w3-modal">
			<div class="w3-modal-content w3-animate-zoom w3-card-4">
				<header class="w3-container w3-theme"> 
					<span onclick="document.getElementById('id01').style.display='none'" 
					class="w3-button w3-display-topright">&times;</span>
					<h2>Search error logs</h2>
				</header>
				<div class="w3-container">
					<p>Some text..</p>
					<p>Some form here to insert query paramenters...</p>
				</div>
			</div>
	  </div>
		
		@include('w3.components.notification')
		<div class="w3-responsive w3-white w3-padding-16">
			<table class="w3-table-all w3-hoverable">
				<tr class="w3-blue w3-text-white w3-large">
					<th>Serial No</th>
					<th>Description</th>
					<th colspan="2"></th>
				</tr>
				<tr>
					<td>1</td>
					<td>Wrong route for flight JMA8641</td>
					<td><a>Message</a></td>
					<td><a>View</a></td>
				</tr>
				<tr>
					<td>2</td>
					<td>Missing Aircraft registration for flight SLR631 to HKLU</td>
					<td><a>Message</a></td>
					<td><a>View</a></td>
				</tr>
				<tr>
					<td>3</td>
					<td>Wrong coordinate in NTM proposal about works on bay 2</td>
					<td><a>Message</a></td>
					<td><a>View</a></td>
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

document.getElementById('notified').className += " w3-text-blue";
document.getElementById('menu-error').className += " w3-text-blue";
menuAcc('error');
w3_show_nav('menuQMS');

</script>


@endsection
