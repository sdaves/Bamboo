@foreach($columns as $column => $structure)
	<tr>
		<td>{{ RobGordijn\Bamboo\BambooController::label($column, $structure) }}</td>
		<td>{{ RobGordijn\Bamboo\BambooController::field($column, $structure) }}</td>
	</tr>
@endforeach