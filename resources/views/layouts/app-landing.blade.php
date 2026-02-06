<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" x-data x-bind:class="{ 'dark': $store.darkMode.on }">
@include('includes.head')
<body class="bg-white dark:bg-slate-900 transition-colors duration-300">
	@if(Route::current() != null && Route::current()->getName() == 'landing.index')
	<div class="flex flex-col h-screen">
	@endif
	@include('includes.header')
	@include('components.global-search-modal')

	<main class="flex-1 overflow-y-auto mt-[-82px] pt-[82px]">
	@yield('content')
	@include('includes.footer')
	</main>
</div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
        function copyToClipboard() {
            const code = this.$el.querySelector('code').innerText;
            navigator.clipboard.writeText(code).then(() => {
                this.copySuccess = true;
                setTimeout(() => this.copySuccess = false, 2000);
            });
        }
    </script>
	@yield('scripts')

    {{-- Global Search Keyboard Shortcut --}}
    <script>
        document.addEventListener('keydown', function(e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                Alpine.store('search').toggle();
            }
        });
    </script>
</body>
</html>
