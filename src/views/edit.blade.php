@extends($bladeLayout)

@section('main')
{{ Form::model($record, array(
	 'url' 		=> URL::route($routeName . 'update', $record->getKey())
	,'method' 	=> 'PUT'
)) }}
@include($viewDir . '/errors')
<table class="table table-striped table-bordered">
<tbody>
	@include($viewDir . 'recordRow')
	<tr>
		<td></td>
		<td>{{ Form::submit(trans($translateHint . 'bamboo.action.update'), array('class' => 'btn btn-success')) }}</td>
	</tr>
</tbody>
</table>
{{ Form::close() }}
@stop