@extends($bladeLayout)

@section('main')
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th style="width:40px">#</th>
				@foreach($columns as $column)
					<th>{{ $column }}</th>
				@endforeach
				<th class="action">{{ trans($translateHint . 'bamboo.action.show') }}</th>
				<th class="action">{{ trans($translateHint . 'bamboo.action.edit') }}</th>
				<th class="action">{{ trans($translateHint . 'bamboo.action.destroy') }}</th>
			</tr>
		</thead>
		<tbody>
			@foreach($records as $record)
			<tr>
				<td>{{ $record->getKey() }}</td>
				@foreach($columns as $column)
					<td>{{ $record->$column }}</td>
				@endforeach
				<td class="action"><a class="btn btn-small btn-success" href="{{ route($routeName.'show', $record->getKey()) }}">{{ trans($translateHint . 'bamboo.action.show') }}</a></td>
				<td class="action"><a class="btn btn-small btn-info" href="{{ route($routeName.'edit', $record->getKey()) }}">{{ trans($translateHint . 'bamboo.action.edit') }}</a></td>
				<td class="action">
					{{ Form::open(array(
						 'method' => 'DELETE'
						,'url' => URL::route($routeName.'destroy', $record->getKey())
					)) }}
					{{ Form::submit(trans($translateHint . 'bamboo.action.destroy'), array(
						 'class' => 'btn btn-small btn-danger'
						,'onclick' => 'javascript: return confirm("' . trans($translateHint . 'bamboo.confirm.destroy') . '");'
					)) }}
					{{ Form::close() }}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	{{ $links }}
@stop