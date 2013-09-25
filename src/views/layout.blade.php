<!doctype html>
<html lang="en">
<head>
	<title>{{ get_class($Model) }} - {{ $title }}</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
	<style>
		table form { margin-bottom: 0; }
		form ul { margin-left: 0; list-style: none; }
		.error { color: red; font-style: italic; }
		body { padding-top: 20px; }
		table th.action { width: 70px; }
		table td.action { text-align: center; }
	</style>
</head>
<body>
	<div class="container">
		<h2>{{ $title }} - {{ get_class($Model) }}</h2>
		<ul class="nav nav-tabs">
			<li @if('index' === $viewName)class="active"@endif>
				<a href="{{ URL::route($routeName.'index') }}">{{ trans($translateHint . 'bamboo.action.index') }}</a>
			</li>
			<li @if('create' === $viewName)class="active"@endif>
				<a href="{{ URL::route($routeName.'create') }}">{{ trans($translateHint . 'bamboo.action.create') }}</a>
			</li>
		</ul>
		@yield('main')
	</div>
</body>
</html>