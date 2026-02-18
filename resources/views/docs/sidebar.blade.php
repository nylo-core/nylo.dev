<ul class="space-y-1">
    <button id="close-menu" class="flex hidden mb-8 mt-8 items-center text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors duration-150">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.2253 4.81108C5.83477 4.42056 5.20161 4.42056 4.81108 4.81108C4.42056 5.20161 4.42056 5.83477 4.81108 6.2253L10.5858 12L4.81114 17.7747C4.42062 18.1652 4.42062 18.7984 4.81114 19.1889C5.20167 19.5794 5.83483 19.5794 6.22535 19.1889L12 13.4142L17.7747 19.1889C18.1652 19.5794 18.7984 19.5794 19.1889 19.1889C19.5794 18.7984 19.5794 18.1652 19.1889 17.7747L13.4142 12L19.189 6.2253C19.5795 5.83477 19.5795 5.20161 19.189 4.81108C18.7985 4.42056 18.1653 4.42056 17.7748 4.81108L12 10.5858L6.2253 4.81108Z" fill="currentColor"/>
        </svg>
        <span class="self-center ml-1">{{ __('Close Menu') }}</span>
    </button>
    
    @foreach(config('project.doc-index.versions')[$version] as $key => $docLinks)
    @php
        $sectionHasActiveLink = collect($docLinks)->contains(fn($link) => Request::is('*/docs/' . $version . '/' . $link));
    @endphp
    <li x-data="{ open: {{ $sectionHasActiveLink ? 'true' : 'false' }} }" {!! $loop->first ? '' : 'class="mt-2"' !!}>
        <button 
            @click="open = !open" 
            class="flex items-center justify-between w-full py-2 px-3 rounded-lg text-left font-semibold text-sm text-slate-900 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors duration-150"
        >
            <span>{{ __(str($key)->headline()->toString()) }}</span>
            <svg 
                class="w-4 h-4 text-slate-400 transition-transform duration-200" 
                :class="{ 'rotate-90': open }"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <ul 
            x-show="open" 
            x-collapse
            class="mt-1 space-y-0.5 pl-3"
        >
            @foreach($docLinks as $docLink)
            <li>
                @php
                    $isActive = Request::is('*/docs/' . $version . '/' . $docLink);
                @endphp
                <a class="block py-1.5 px-3 rounded-lg text-sm transition-all duration-150 {!! $isActive ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200' !!}"
                    href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'page' => $docLink, 'version' => $version]) }}"
                >
                    @if (str($docLink)->startsWith('ny'))
                        {{ str($docLink)->headline()->replace(" ", "") }}
                    @else
                        {{ __(str($docLink)->headline()->toString()) }}
                    @endif
                </a>
            </li>
            @endforeach
        </ul>
    </li>
    @endforeach

    <li x-data="{ open: false }" class="mt-2">
        <button 
            @click="open = !open" 
            class="flex items-center justify-between w-full py-2 px-3 rounded-lg text-left font-semibold text-sm text-slate-900 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors duration-150"
        >
            <span>{{ __('Packages') }}</span>
            <svg 
                class="w-4 h-4 text-slate-400 transition-transform duration-200" 
                :class="{ 'rotate-90': open }"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <ul 
            x-show="open" 
            x-collapse
            class="mt-1 space-y-0.5 pl-3"
        >
            @foreach(config('project.packages-index') as $packageLink)
            <li>
                <a target="_BLANK" class="block py-1.5 px-3 rounded-lg text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200 transition-all duration-150"
                    href="https://pub.dev/packages/{{ str($packageLink['link'])->replace('-', '_') }}"
                >
                    {{ $packageLink['label'] }}
                    <svg class="inline-block w-3 h-3 ml-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
            </li>
            @endforeach
        </ul>
    </li>
</ul>
