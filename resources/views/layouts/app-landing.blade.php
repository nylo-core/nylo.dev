<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" x-data x-bind:class="{ 'dark': $store.darkMode.on }">
@include('includes.head')
<body class="bg-white dark:bg-slate-900 transition-colors duration-300">
	@include('includes.header')
	@include('components.global-search-modal')

	<main>
	@yield('content')
	@include('includes.footer')
	</main>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
        // Clipboard write with a fallback for non-secure contexts (http:// previews).
        window.nyCopyText = function (text) {
            if (navigator.clipboard && window.isSecureContext) {
                return navigator.clipboard.writeText(text);
            }

            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
            } finally {
                textarea.remove();
            }
        };

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
