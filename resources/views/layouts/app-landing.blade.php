<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('includes.head')
<body>
	
	@include('includes.header')

	@yield('content')

	@include('includes.footer')

    <script src="{{ asset('js/jquery.min.js') }}"></script>
	@yield('scripts')
</body>
</html>