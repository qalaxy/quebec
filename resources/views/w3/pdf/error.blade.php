<!DOCTYPE html>
<html>
	<head>
		<title>Error report</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="{{asset('public/css/w3.css')}}">
		<link rel="icon" href="{{asset('public/images/logo/kcaa.png')}}">
	</head>
	<body>
		<div class="w3-container">
			<table class="w3-table" border="0" width="100%" style="width:100%">
				<tr>
					<td style="width:25%;"><img src="{{asset('/public/images/logo/kcaa.png')}}" class="" alt="KCAA" style="width:150px;height:100px;"></td>
					<td class="w3-padding w3-small">Kenya Civil Aviation Authority<br /> 
									Aeronautical Information Management<br />
									P. O. Box 30163<br />
									Nairobi, Kenya<br />
									Tel: +254 20 6827470-5</td>
				</tr>
			</table>
			<p class="w3-text-dark-grey" style="letter-spacing: 2px; font-size:19px; text-decoration: underline;">Aeronautical Information Management Non-Conformity Report</p>
			<p class="w3-text-dark-grey w3-large">Reported error</p>
			
			<table class="w3-table w3-border" border="0" width="100%" style="width:100%">
				<tr>
					<th class="w3-padding" style="width:25%; font-weight: normal;">Number: </td>
					<th class="w3-padding" style="text-align: justify; font-weight: normal;">{{$reported_error['number']}}</td>
				</tr>
				<tr>
					<td class="w3-padding" >Function: </td>
					<td class="w3-padding" style="text-align: justify;">{{$reported_error['function']}}</td>
				</tr>
				<tr>
					<td class="w3-padding">Description: </td>
					<td class="w3-padding" style="text-align: justify;">{{$reported_error['description']}}</td>
				</tr>
				<tr>
					<td class="w3-padding">Impact: </td>
					<td class="w3-padding" style="text-align: justify;">{{$reported_error['impact']}}</td>
				</tr>
				<tr>
					<td class="w3-padding">Station of origin: </td>
					<td class="w3-padding" style="text-align: justify;">{{$reported_error['station']}}</td>
				</tr>
				<tr>
					<td class="w3-padding">Date reported: </td>
					<td class="w3-padding" style="text-align: justify;">{{$reported_error['date_reported']}}</td>
				</tr>
				<tr>
					<td class="w3-padding">Reported by: </td>
					<td class="w3-padding" style="text-align: justify;">{{$reported_error['user']}}</td>
				</tr>
				<tr>
					<td class="w3-padding">Station reporting: </td>
					<td class="w3-padding" style="text-align: justify;">{{$reported_error['reporting_station']}}</td>
				</tr>
				<tr>
					<td class="w3-padding">Remarks: </td>
					<td class="w3-padding" style="text-align: justify;">{{$reported_error['remarks']}}</td>
				</tr>
			</table>
			@if(count($products))
			<p class="w3-text-dark-grey w3-large">Affected products</p>
			
			<table class="w3-table-all" border="0" width="100%" style="width:100%">
				<tr>
					<th class="w3-padding" style="width:40%; border: 1px solid #708090;">Product </td>
					<th class="w3-padding" style="text-align: justify; border: 1px solid #708090;">Identification</td>
				</tr>
				
				@foreach($products as $product)
					<tr>
						<td class="w3-padding" style="text-align: justify; border-left: 0.5px solid #708090; border-bottom: 0.5px solid #708090;">{{$product['product']}}</td>
						<td class="w3-padding" 
							style="text-align: justify; border-left: 0.5px solid #708090; border-bottom: 0.5px solid #708090;border-right: 0.5px solid #708090;">{{$product['identification']}}</td>
					</tr>
				@endforeach
			</table>
			@else
				<p class="w3-text-dark-grey w3-large">Affected products: <span class="w3-medium">Nill</span></p>
			@endif
			
			@if(count($correction))
			<p class="w3-text-dark-grey w3-large">Error correction</p>
			
			<table class="w3-table w3-border" border="0" width="100%" style="width:100%">
				<tr>
					<th class="w3-padding" style="width:25%; font-weight: normal;">Cause: </td>
					<th class="w3-padding" style="text-align: justify; font-weight: normal;">{{$correction['cause']}}</td>
				</tr>
				<tr>
					<td class="w3-padding" >Corrective action: </td>
					<td class="w3-padding" style="text-align: justify;">{{$correction['corrective_action']}}</td>
				</tr>
				<tr>
					<td class="w3-padding">Remarks: </td>
					<td class="w3-padding" style="text-align: justify;">{{$correction['remarks']}}</td>
				</tr>
				<tr>
					<td class="w3-padding">Source of error: </td>
					<td class="w3-padding" style="text-align: justify;">{{$correction['source']}}</td>
				</tr>
				<tr>
					<td class="w3-padding">Response from: </td>
					<td class="w3-padding" style="text-align: justify;">{{$correction['corrector']}}</td>
				</tr>
				<tr>
					<td class="w3-padding">Date of response: </td>
					<td class="w3-padding" style="text-align: justify;">{{$correction['date_responded']}}</td>
				</tr>
			</table>
			@endif
			
			@if(count($originator_reaction))
			<p class="w3-text-dark-grey w3-large">Originator's opinion</p>
			
			<table class="w3-table w3-border" border="0" width="100%" style="width:100%">
				<tr>
					<th class="w3-padding" style="width:25%; font-weight: normal;">Opinion: </td>
					<th class="w3-padding" style="text-align: justify; font-weight: normal; text-decoration: {{($originator_reaction['sts'])? 'none': 'line-through'}};">{{$originator_reaction['status']}}</td>
				</tr>
				<tr>
					<td class="w3-padding">Remarks: </td>
					<td class="w3-padding" style="text-align: justify; text-decoration: {{($originator_reaction['sts'])? 'none': 'line-through'}};">{{$originator_reaction['remarks']}}</td>
				</tr>
			</table>
			@endif
			
			@if(count($supervisor_reaction))
			<p class="w3-text-dark-grey w3-large">Supervisor's opinion</p>
			
			<table class="w3-table w3-border" border="0" width="100%" style="width:100%">
				<tr>
					<th class="w3-padding" style="width:25%; font-weight: normal; text-decoration: {{($supervisor_reaction['sts'])? 'none': 'line-through'}};">Opinion: </td>
					<th class="w3-padding" style="text-align: justify; font-weight: normal;">{{$supervisor_reaction['status']}}</td>
				</tr>
				<tr>
					<td class="w3-padding">Remarks: </td>
					<td class="w3-padding" style="text-align: justify; text-decoration: {{($supervisor_reaction['sts'])? 'none': 'line-through'}};">{{$supervisor_reaction['remarks']}}</td>
				</tr><!-- supervisor -->
				<tr>
					<td class="w3-padding">Supervisor: </td>
					<td class="w3-padding" style="text-align: justify;">{{$supervisor_reaction['supervisor']}}</td>
				</tr>
			</table>
			@endif
		</div>
	</body>
</html>