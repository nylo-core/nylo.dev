<nav class="text-sm leading-6 font-semibold text-slate-700 dark:text-slate-200">
    <ul class="flex space-x-8">
        <li><a class="hover:text-sky-500 dark:hover:text-sky-400" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $nyloDocVersion, 'page' => 'installation']) }}">Docs</a></li>
        <li><a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK" class="hover:text-sky-500 dark:hover:text-sky-400">Community</a></li>
        @if(array_key_exists($version, config('project.doc-tutorials.versions', [])))
        <li><a class="hover:text-sky-500 dark:hover:text-sky-400" href="{{ route('tutorials.index', ['version' => $version]) }}">Tutorials</a></li>
        @endif
    </ul>
</nav>
<div class="flex items-center border-l border-slate-200 ml-6 dark:border-slate-800">
    <a href="https://youtube.com/@nylo_dev" target="__BLANK" class="ml-6 block text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
        <span class="sr-only">{{ config('app.name') }} on YouTube</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5" fill="currentColor" viewBox="-35.20005 -41.33325 305.0671 247.9995"><path d="M229.763 25.817c-2.699-10.162-10.65-18.165-20.747-20.881C190.716 0 117.333 0 117.333 0S43.951 0 25.651 4.936C15.554 7.652 7.602 15.655 4.904 25.817 0 44.237 0 82.667 0 82.667s0 38.43 4.904 56.85c2.698 10.162 10.65 18.164 20.747 20.881 18.3 4.935 91.682 4.935 91.682 4.935s73.383 0 91.683-4.935c10.097-2.717 18.048-10.72 20.747-20.88 4.904-18.422 4.904-56.851 4.904-56.851s0-38.43-4.904-56.85" fill="currentColor"/><path d="M93.333 117.558l61.334-34.89-61.334-34.893z" fill="#fff"/></svg>
    </a>
    <a href="https://github.com/nylo-core/nylo" target="__BLANK" class="ml-6 block text-slate-400 hover:text-slate-500 dark:hover:text-slate-300">
        <span class="sr-only">{{ config('app.name') }} on GitHub</span>
        <svg viewBox="0 0 16 16" class="w-5 h-5" fill="currentColor" aria-hidden="true">
            <path
            d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"
            ></path>
        </svg>
    </a>
    <button @click="$store.darkMode.toggle()" class="ml-6 p-2 rounded-lg text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 transition-colors" aria-label="Toggle dark mode">
        {{-- Sun icon (shown in dark mode) --}}
        <svg x-show="$store.darkMode.on" x-cloak class="w-5 h-5 text-slate-400 hover:text-slate-500 dark:hover:text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
        </svg>
        {{-- Moon icon (shown in light mode) --}}
        <svg x-show="!$store.darkMode.on" class="w-5 h-5 text-slate-400 hover:text-slate-500 dark:hover:text-slate-300" fill="currentColor" viewBox="0 0 20 20">
            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
        </svg>
    </button>
</div>
