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
			<p class="w3-text-dark-grey w3-center w3-wide" style="text-decoration: underline; font-size:20px;">Aeronautical Information Management Non-Conformity Report</p>
			@if(count($errors))
				<table class="w3-table-all" style="width:95%;">
					<thead>
						<tr>
							<th class="w3-small" style="text-align: justify; border: 1px solid #708090;">Number</th>
							<th class="w3-small" style="text-align: justify; border: 1px solid #708090;">Date reported</th>
							<th class="w3-small" style="text-align: justify; border: 1px solid #708090;">Description</th>
							<th class="w3-small" style="text-align: justify; border: 1px solid #708090;">Impact</th>
							<th class="w3-small" style="text-align: justify; border: 1px solid #708090;">Affected products</th>
							<th class="w3-small" style="text-align: justify; border: 1px solid #708090;">Reported by</th>
							<th class="w3-small" style="text-align: justify; border: 1px solid #708090;">Cause</th>
							<th class="w3-small" style="text-align: justify; border: 1px solid #708090;">Corrective action</th>
							<th class="w3-small" style="text-align: justify; border: 1px solid #708090;">Correction date</th>
							<th class="w3-small" style="text-align: justify; border: 1px solid #708090;">Corrected by</th>
							<th class="w3-small" style="text-align: justify; border: 1px solid #708090;">Remarks</th>
						</tr>
					</thead>
					<tbody>
					@foreach($errors as $error)
						<tr>
							<td class="w3-small" style="text-align: justify; border: 0.5px solid #708090;">
								{{$error['reported_error']['number']}}
							</td>
							<td class="w3-small" style="text-align: justify; border: 0.5px solid #708090;">
								{{$error['reported_error']['date_reported']}}
							</td>
							<td class="w3-small" style="text-align: justify; border: 0.5px solid #708090;">
								{{$error['reported_error']['description']}}
							</td>
							<td class="w3-small" style="text-align: justify; border: 0.5px solid #708090;">
								{{$error['reported_error']['impact']}}
							</td>
							<td class="w3-small" style="text-align: justify; border: 0.5px solid #708090;">
								@if(count($error['products']))
									@foreach($error['products'] as $product)
										{{$product['product']}},
									@endforeach
								@endif
							</td>
							<td class="w3-small" style="text-align: justify; border: 0.5px solid #708090;">
								{{$error['reported_error']['user']}}
							</td>
							<td class="w3-small" style="text-align: justify; border: 0.5px solid #708090;">
								{{(count($error['correction']))?$error['correction']['cause']:null}}
							</td>
							<td class="w3-small" style="text-align: justify; border: 0.5px solid #708090;">
								{{(count($error['correction']))?$error['correction']['corrective_action']:null}}
							</td>
							<td class="w3-small" style="text-align: justify; border: 0.5px solid #708090;">
								{{(count($error['correction']))?$error['correction']['date_responded']:null}}
							</td>
							<td class="w3-small" style="text-align: justify; border: 0.5px solid #708090;">
								{{(count($error['correction']))?$error['correction']['corrector']:null}}
							</td>
							<td class="w3-small" style="text-align: justify; border: 0.5px solid #708090;">
								{{(count($error['correction']))?$error['correction']['remarks']:null}}
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			@endif
		</div>
	</body>
</html>