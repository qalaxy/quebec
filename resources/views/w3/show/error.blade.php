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
		
		<div id="confirmation" class="w3-modal">
			<div class="w3-modal-content w3-animate-top w3-card-4">
				<header class="w3-container w3-theme"> 
					<span onclick="document.getElementById('confirmation').style.display='none'" 
					class="w3-button w3-display-topright">&times;</span>
					<h2>Confirmation</h2>
				</header>
				<div class="w3-container">
					<p>Are you confirming this?</p>
				</div>
				<footer class="w3-container ">
					<div class="w3-row w3-padding-16">
						<div class="w3-col">
							<a class="w3-button w3-large w3-theme w3-hover-aqua" 
								href="" 
								title="Delete role permission">YES&nbsp;
								<i class="fa fa-angle-right fa-lg"></i>
							</a>
							<button class="w3-button w3-large w3-theme w3-hover-light-blue" 
								title="Dismiss" 
								onclick="document.getElementById('confirmation').style.display='none'">NO&nbsp;
							</button>
						</div>
					</div>
				</footer>
			</div>
		</div>
		@include('w3.components.notification')
		<div class="w3-container w3-text-dark-gray">
		<div class="w3-row">
			<div class="w3-col l8">
				<div class="w3-row">
					<div class="w3-dropdown-hover w3-right w3-white">
						<button class="w3-button w3-xlarge"><i class="fa fa-bars"></i></button>
						<div class="w3-dropdown-content w3-bar-block w3-border w3-small" style="right:0; width:200px;">
							@if(is_null($error->errorCorrection()->first()))
								@foreach(Auth::user()->account()->first()->accountStation()->get() as $account_station)
									@if($account_station->station()->first()->id == $error->station()->first()->id)
										<a href="{{url('/error-corrective-action/'.$error->uuid)}}" class="w3-bar-item w3-button w3-hover-light-blue">Corrective action</a>
										@php continue; @endphp
									@endif
								@endforeach
							@endif
							@if(is_null($error->affectedProduct()->first()))
								@foreach(Auth::user()->account()->first()->accountStation()->get() as $account_station)
									@if($account_station->station()->first()->id == $error->station()->first()->id)
										<a href="{{url('/add-error-affected-product/'.$error->uuid)}}" class="w3-bar-item w3-button w3-hover-light-blue">Affected product</a>
										@php continue; @endphp
									@endif
								@endforeach
							@endif
							@foreach(Auth::user()->account()->first()->accountStation()->get() as $account_station)
								@if($account_station->station()->first()->id == $error->station()->first()->id)
									@if($error->status()->first()->state()->first()->code == 2)
										<a href="{{url('error-reject/'.$error->uuid)}}"  class="w3-bar-item w3-button w3-hover-light-blue">Open</a>
									@else
										<a href="{{url('error-reject/'.$error->uuid)}}"  class="w3-bar-item w3-button w3-hover-light-blue">Reject</a>
									@endif
									@php continue; @endphp
								@endif
							@endforeach
							
							<a href="{{url('error-pdf/'.$error->uuid)}}"  target="_blank" class="w3-bar-item w3-button w3-hover-light-blue">PDF format</a>
							
							@if($error->user()->first()->id == Auth::id())
							<a href="{{url('/edit-error/'.$error->uuid)}}" class="w3-bar-item w3-button w3-hover-light-blue">Edit</a>
							<a href="javascript:void(0)" onclick="deleteError('{{$error->uuid}}');" class="w3-bar-item w3-button w3-hover-light-blue">Delete</a>
							@endif
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
				{{$error->station()->first()->abbreviation}}/{{$error->func()->first()->abbreviation}}/{{$error->number}}/{{date_format(date_create($error->created_at), 'y')}}
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
					@if($error->created_at)
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Date reported: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{date_format(date_create($error->created_at), 'd/m/Y H:i:s')}}</span>
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
							<span class=""><strong>Reported by: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{$error->user()->first()->name}}</span>
						</div>
					</div>
					@endif
				</div>
				@if($error->affectedProduct()->first())
				<div class="w3-row">
					<div class="w3-dropdown-hover w3-right w3-white">
						<button class="w3-button w3-xlarge"><i class="fa fa-bars"></i></button>
						<div class="w3-dropdown-content w3-bar-block w3-border w3-small" style="right:0; width:200px;">
							@foreach(Auth::user()->account()->first()->accountStation()->get() as $account_station)
								@if($account_station->station()->first()->id == $error->station()->first()->id)
									<a href="{{url('/add-error-affected-product/'.$error->uuid)}}" class="w3-bar-item w3-button w3-hover-light-blue">Add a product</a>
									@php continue; @endphp
								@endif
							@endforeach
													
						</div>
					  </div>
				</div>
				@if($error->affectedProduct()->get())
				<div class="w3-light-gray w3-topbar w3-border-gray">
					
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Affected product(s): </strong></span>
						</div>
						<div class="w3-col s12 m12 l9 w3-left">
							@foreach($error->affectedProduct()->distinct()->get() as $affected_product)
							<span class="w3-hover-text-blue" 
									onmouseover="document.getElementById('{{$affected_product->uuid}}').style.display='inline'; this.style.cursor='pointer';" 
												onmouseout="document.getElementById('{{$affected_product->uuid}}').style.display='none'">
												<span onclick="loadAffectedProduct('{{$affected_product->uuid}}');">{{$affected_product->product()->first()->name}}</span>
								<span id="{{$affected_product->uuid}}" style="display:none;">
									<a class="w3-hover-blue" 
										onclick="deleteAffectedProduct('{{$error->uuid}}', '{{$affected_product->uuid}}');"
										title="Remove {{$affected_product->product()->first()->name}}">
										<i class="fa fa-close fa-lg"></i>
									</a>
								</span>,
							</span>
						@endforeach
						</div>
					</div>
					
				</div>
				@endif
				@endif
				@if($error->errorCorrection()->first())
				<div class="w3-row">
					
					<div class="w3-dropdown-hover w3-right w3-white">
						<button class="w3-button w3-xlarge">
						@if($error->errorCorrection()->first() && $error->errorCorrection()->first()->status()->first()->state()->first()->code == 3)
								
							@if(($error->errorCorrection()->first()->aioError()->first() 
								&& $error->errorCorrection()->first()->aioError()->first()->errorOriginator()->first()->id == Auth::id()
								&& is_null($error->errorCorrection()->first()->originatorReaction()->first()))
								|| ($error->station()->first()->supervisor()->first() 
									&& $error->station()->first()->supervisor()->first()->account()->first()->user()->first()->id == Auth::id()))
								<i class="fa fa-bell w3-text-red"></i>
							@else
								<i class="fa fa-bars"></i>
							@endif
							
						@else
							<i class="fa fa-bars"></i>
						@endif
						</button>
						<div class="w3-dropdown-content w3-bar-block w3-border w3-small" style="right:0; width:200px;">		
							@if(($error->errorCorrection()->first()->aioError()->first() 
								&& $error->errorCorrection()->first()->aioError()->first()->errorOriginator()->first()->id == Auth::id()
								&& is_null($error->errorCorrection()->first()->originatorReaction()->first())))
							<a href="{{url('/create-error-originator-reaction/'.$error->uuid)}}" 
								class="w3-bar-item w3-button w3-hover-light-blue">
								Originator comments<sup class="w3-text-red"><i class="fa fa-bell"></i></sup>
								
							</a>
							@elseif($error->station()->first()->supervisor()->first() 
									&& $error->station()->first()->supervisor()->first()->account()->first()->user()->first()->id == Auth::id())
								@if($error->errorCorrection()->first()->originatorReaction()->first())
									<a href="{{url('/create-error-supervisor-reaction/'.$error->uuid)}}" 
										class="w3-bar-item w3-button w3-hover-light-blue">
										Supervisor comments<sup class="w3-text-red"><i class="fa fa-bell"></i></sup>
									</a>
								@else
									<button class="w3-bar-item w3-button w3-hover-light-blue" 
											onclick="confirmSupervisorReaction('{{url('/create-error-supervisor-reaction/'.$error->uuid)}}')">
										Supervisor comments<sup class="w3-text-red"><i class="fa fa-bell"></i></sup>
									</button>
								@endif
							@endif
							@if($error->errorCorrection()->first()->status()->first()->state()->first()->code != 3)
								@foreach(Auth::user()->account()->first()->accountStation()->get() as $account_station)
									@if($account_station->station()->first()->id == $error->station()->first()->id)
										<a href="{{url('/edit-error-corrective-action/'.$error->uuid)}}" class="w3-bar-item w3-button w3-hover-light-blue">Edit</a>						
										<span class="w3-bar-item w3-button w3-hover-light-blue" onclick="">Delete</span>
										@php continue; @endphp
									@endif
								@endforeach
							@endif						
						</div>
					  </div>
				</div>
				<div class="w3-light-gray w3-topbar w3-border-gray">
					@if($error->errorCorrection()->first())
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Cause: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{$error->errorCorrection()->first()->cause}}</span>
						</div>
					</div>
					@endif
					@if($error->errorCorrection()->first())
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Corrective action: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{$error->errorCorrection()->first()->corrective_action}}</span>
						</div>
					</div>
					@endif
					@if($error->errorCorrection()->first())
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Station: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{$error->errorCorrection()->first()->station()->first()->name}}</span>
						</div>
					</div>
					@endif
					@if($error->errorCorrection()->first())
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Officer: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{$error->errorCorrection()->first()->user()->first()->name}}</span>
						</div>
					</div>
					@endif
					
					@if($error->errorCorrection()->first()->aioError()->first() || $error->externalError()->first())
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Source: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							@if($error->errorCorrection()->first()->aioError()->first())
								<span class="">
								{{$error->errorCorrection()->first()->aioError()->first()->errorOriginator()->first()->name}}
								</span>
							@elseif($error->externalError()->first())
								<span class="">{{$error->externalError()->first()->description}}</span>
							@endif
						</div>
					</div>
					@endif
					@if($error->errorCorrection()->first())
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Remarks: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{$error->errorCorrection()->first()->remarks}}</span>
						</div>
					</div>
					@endif
					@if($error->errorCorrection()->first())
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Date of response: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{date_format(date_create($error->errorCorrection()->first()->created_at), 'd/m/Y H:i:s')}}</span>
						</div>
					</div>
					@endif
				</div>
				@endif
				@if($error->errorCorrection()->first() && $error->errorCorrection()->first()->originatorReaction()->first())
					@if($error->errorCorrection->first()->aioError()->first()->errorOriginator()->first()->id == Auth::id())
					<div class="w3-row">
						<div class="w3-dropdown-hover w3-right w3-white">
							<button class="w3-button w3-xlarge"><i class="fa fa-bars"></i></button>
							<div class="w3-dropdown-content w3-bar-block w3-border w3-small" style="right:0; width:200px;">
								<a href="{{url('/edit-error-originator-reaction/'.$error->uuid)}}" class="w3-bar-item w3-button w3-hover-light-blue">Edit</a>
								<a href="{{url('/delete-error-originator-reaction/'.$error->uuid)}}" class="w3-bar-item w3-button w3-hover-light-blue">Delete</a>						
							</div>
						  </div>
					</div>
					@else
						<br /><br />
					@endif
				<div class="w3-light-gray w3-topbar w3-border-gray">
					
					@if($error->errorCorrection()->first()->originatorReaction()->first())
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Source's opinion: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">
								{{(boolval($error->errorCorrection()->first()->originatorReaction()->first()->status))
								?'I agree with the corrective action'
								:'I disagree with the corrective action'}}
							</span>
						</div>
					</div>
					@endif
					@if($error->errorCorrection()->first()->originatorReaction()->first())
					<div class="w3-row w3-padding">
						<div class="w3-col s12 m12 l2 w3-left">
							<span class=""><strong>Remarks: </strong></span>
						</div>
						<div class="w3-col s12 m12 l10 w3-left">
							<span class="">{{$error->errorCorrection()->first()->originatorReaction()->first()->remarks}}</span>
						</div>
					</div>
					@endif
					
				</div>
				@endif
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

function confirmSupervisorReaction(url){
	let confirmation = document.getElementById('confirmation');
	confirmation.children[0].children[1].children[0].innerHTML = 'Do you want to proceed with this action without error originator\'s reaction?';
	confirmation.children[0].children[2].children[0].children[0].children[0].setAttribute("href", url) ;
	confirmation.style.display='block';
}


</script>

<script src="{{url('public/js/error.js')}}"></script>


@endsection
