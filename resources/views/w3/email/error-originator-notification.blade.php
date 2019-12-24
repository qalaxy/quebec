<!DOCTYPE html>
<html>
	<head>
		<title>AIM Email</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="{{asset('public/css/w3.css')}}">
		<style>
			p{
				color:#000000;
			}
		</style>
	</head>
	<body>
		<div class="w3-container">
			<h2 class="w3-blue">Error tracking</h2>
			<p class="w3-leftbar">You have been mentioned as error originator.</p>
			<p class="w3-leftbar">Click on the link below to respond to the error correction.</p>
			<a class="w3-button w3-blue" href="{{config('app.url').'/error/'.encrypt($error->uuid)}}">
				Error number: {{$error->station()->first()->abbreviation}}/{{$error->func()->first()->abbreviation}}/{{$error->number}}/{{date_format(date_create($error->created_at), 'y')}}
			<a>
		</div>
	</body>
</html>