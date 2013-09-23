@extends($bladeLayout)

@section('main')
{{ Form::open(array(
	 'url' 		=> URL::route($routeName . 'store')
	,'method' 	=> 'POST'
)) }}
@include($viewDir . '/errors')
<table class="table table-striped table-bordered">
<tbody>
	@include($viewDir . 'recordRow')
	<tr>
		<td></td>
		<td>{{ Form::submit(trans($translateHint . 'bamboo.action.store'), array('class' => 'btn btn-success')) }}</td>
	</tr>
</tbody>
</table>
{{ Form::close() }}
@stop