@extends('w3.layout.app')

@section('title')
<title>Error affected product</title>
@endsection


@section('content')	
<div class="w3-panel w3-padding-small w3-card-4 w3-white w3-leftbar w3-border-light-blue" style="min-height:700px;">
  <h1 class="w3-xlarge">Add affected product for error number: <a class="w3-hover-text-blue" href="{{url('error/'.$func_error->uuid)}}" style="text-decoration:none;">
{{$func_error->station()->first()->abbreviation}}/{{$func_error->func()->first()->abbreviation}}/{{$func_error->number}}/{{date_format(date_create($func_error->date_time_created), 'y')}} </a>
  </h1>
	<div class="w3-row w3-panel" style="max-width:100%;">
		@include('w3.components.notification')
		<form class="w3-container" method="POST" action="{{url('/store-error-affected-product/'.$func_error->uuid)}}">
			@csrf
			<div class="w3-row">
				<div class="w3-col s12 m6 l6">
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Product<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border {{($errors->has('product_id')) ? 'w3-border-red' : 'w3-border-dark-gray'}}" 
									name="product_id"
									onchange="document.getElementById('product_identification').value = null;">
								<option value="" disabled selected>Select a functional unit</option>
								@if($func_error)
									@foreach($func_error->func()->first()->product()->get() as $product)
										@foreach($func_error->affectedProduct()->get() as $affected_product)
											@if($affected_product->product()->first()->uuid == $product->uuid)
												@php continue 2; @endphp
											@endif
										@endforeach
										<option value="{{$product->uuid}}" {{(old('product_id') == $product->uuid)? 'selected':null}}>{{$product->name}}</option>
									@endforeach
								@endif
							 </select>
							@if($errors->has('product_id'))
								<span class="w3-small w3-text-red">{{$errors->first('product_id')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
					<div class="w3-row w3-padding-small">
						<div class="w3-col s12 m10 l10 w3-left">
							<label class="w3-text-dark-gray">Product identification</label>
							<textarea class="w3-input w3-border-dark-gray w3-border" 
										placeholder="Identify the product itself" 
										id="product_identification"
										name="product_identification"
										rows="3">{{old('product_identification')}}</textarea>
							@if($errors->has('product_identification'))
								<span class="w3-small w3-text-red">{{$errors->first('product_identification')}}</span>
							@else
								<span>&nbsp;</span>
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="w3-row">
				<div class="w3-col w3-padding-small">
					<button class="w3-button w3-large w3-theme w3-hover-light-blue" type="submit" title="Save the affected product">Save&nbsp;<i class="fa fa-angle-right fa-lg"></i></button>
				</div>
			</div>
		</form>
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

</script>


@endsection
