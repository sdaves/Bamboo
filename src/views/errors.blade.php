@if($errors->any())
	@foreach($errors->all() as $error)
		<p class="text-error">{{ $error }}</p>
	@endforeach
@endif