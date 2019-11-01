
@isset($notification)
	@switch($notification['indicator'])
		@case('danger')
			@php $color = 'w3-pale-red'; $border = 'w3-border-pink'; @endphp
			@break
		@case('warning')
			@php $color = 'w3-pale-yellow'; $border = 'w3-border-orange'; @endphp
			@break
		@case('information')
			@php $color = 'w3-pale-blue'; $border = 'w3-border-blue'; @endphp
			@break
		@case('success')
			@php $color = 'w3-pale-green'; $border = 'w3-border-green'; @endphp
			@break
		@default
			@php $color = 'w3-light-gray'; $border = 'w3-border-gray'; @endphp
			@break
	
	@endswitch
	<div class="w3-panel w3-display-container w3-leftbar w3-text-brown {{$color}} {{$border}}">
	  <span onclick="this.parentElement.style.display='none'"
	  class="w3-button {{$color}} w3-large w3-display-topright">&times;</span>
	  <p>{{$notification['message']}}</p>
	</div>
	

@endisset