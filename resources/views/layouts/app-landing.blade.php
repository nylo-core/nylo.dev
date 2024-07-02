<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
@include('includes.head')
<body>
	@if(Route::current() != null && Route::current()->getName() == 'landing.index')
	<div class="flex flex-col h-screen">
	@endif
	@include('includes.header')

	<main class="flex-1 overflow-y-auto mt-[-82px] pt-[82px]">
	@yield('content')
	</main>
</div>
	@if(Route::current() != null && Route::current()->getName() != 'landing.index')
	@include('includes.footer')
	@endif

    <script src="{{ asset('js/jquery.min.js') }}"></script>
	@yield('scripts')
</body>
</html>
