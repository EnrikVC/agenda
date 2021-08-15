<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}" class="h-100">
<head>
	<title>{{ config('app.name', 'Laravel') }} @yield('title', 'Inserte el título')</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-4.6.0/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/pace-1.2.4/pace-theme-default.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/pace-1.2.4/loading-bar.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('plugins/sweetalert2-11.1.2/dist/sweetalert2.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
	@hasSection('links')
		@yield('links')
	@endif
</head>
<body class="app-bg position-relative h-100">
	<section class="page-header">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<h4 class="font-weight-normal">@yield('header', 'Inserte el header')</h4>
				</div>
				@hasSection('header_buttons')
				<div class="col-sm-6">
					@yield('header_buttons')
				</div>
				@endif
			</div>
		</div>
	</section>
	<div class="container">

		@yield('content')

	</div>

	<script type="text/javascript" src="{{ asset('plugins/jquery-3.6.0/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/bootstrap-4.6.0/js/bootstrap.bundle.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/pace-1.2.4/pace.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/sweetalert2-11.1.2/dist/sweetalert2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/util.js') }}"></script>
	<script src="https://use.fontawesome.com/45977f9a5c.js"></script>
	@hasSection('scripts')
		@yield('scripts')
	@endif
</body>
</html>