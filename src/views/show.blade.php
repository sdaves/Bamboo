@extends($bladeLayout)

@section('main')
<table class="table table-striped table-bordered">
<tbody>
	@foreach($columns as $column => $structure)
		<tr>
			<td>{{ Form::label($column) }}</td>
			<td>{{ $record->$column }}</td>
		</tr>
	@endforeach
	<tr>
		<td></td>
		<td>
			{{ Form::open(array(
				 'method' => 'DELETE'
				,'url' => URL::route($routeName.'destroy', $record->getKey())
			)) }}
			{{ link_to_route($routeName.'edit', trans($translateHint . 'bamboo.action.edit'), $record->getKey(), array(
				'class' => 'btn btn-small btn-info'
			)) }}
			{{ Form::submit(trans($translateHint . 'bamboo.action.destroy'), array(
				 'class' => 'btn btn-small btn-danger'
				,'onclick' => 'javascript: return confirm("Are you sure?");'
			)) }}
			{{ Form::close() }}
		</td>
	</tr>
</tbody>
</table>
@stop