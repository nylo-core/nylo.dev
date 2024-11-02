<ul class="border-r-2 border-gray-50 dark:border-gray-800">
    <button id="close-menu" class="flex hidden mb-8 mt-8">
        <svg
        width="24"
        height="24"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        fill="#3e4757"
        >
        <path
            d="M6.2253 4.81108C5.83477 4.42056 5.20161 4.42056 4.81108 4.81108C4.42056 5.20161 4.42056 5.83477 4.81108 6.2253L10.5858 12L4.81114 17.7747C4.42062 18.1652 4.42062 18.7984 4.81114 19.1889C5.20167 19.5794 5.83483 19.5794 6.22535 19.1889L12 13.4142L17.7747 19.1889C18.1652 19.5794 18.7984 19.5794 19.1889 19.1889C19.5794 18.7984 19.5794 18.1652 19.1889 17.7747L13.4142 12L19.189 6.2253C19.5795 5.83477 19.5795 5.20161 19.189 4.81108C18.7985 4.42056 18.1653 4.42056 17.7748 4.81108L12 10.5858L6.2253 4.81108Z"
            fill="currentColor"
        />
        </svg>
        <span class="self-center ml-1">Close Menu</span>
    </button>
    @foreach(config('project.doc-index.versions')[$version] as $key => $docLinks)
    <li {!! $loop->first ? '' : 'class="mt-12 lg:mt-8"' !!}>
        <h5 class="mb-8 lg:mb-3 font-semibold text-slate-900 dark:text-slate-200">{{ str($key)->headline() }}</h5>

        <ul class="space-y-6 lg:space-y-2 border-l border-slate-100 dark:border-slate-800">
            @foreach($docLinks as $docLink)
            <li>
                <a class="block border-l pl-4 -ml-px border-transparent hover:border-slate-400 dark:hover:border-slate-500 dark:text-slate-400 dark:hover:text-slate-300 {!! Request::is('docs/' . $version . '/' . $docLink) ? 'text-[#1f74c1]' : 'text-slate-700 hover:text-slate-900' !!}"
                    href="{{ route('landing.docs', ['page' => $docLink, 'version' => $version]) }}"
                    >
                    @if (str($docLink)->startsWith('ny'))
                    {{ str($docLink)->headline()->replace(" ", "") }}
                    @else
                    {{ str($docLink)->headline() }}
                    @endif
                </a>
            </li>
            @endforeach
        </ul>
    </li>
    @endforeach


    <li class="mt-12 lg:mt-8">
        <h5 class="mb-8 lg:mb-3 font-semibold text-slate-900 dark:text-slate-200">Packages</h5>

        <ul class="space-y-6 lg:space-y-2 border-l border-slate-100 dark:border-slate-800">
            @foreach(config('project.packages-index') as $packageLink)
            <li>
                <a target="_BLANK" class="block border-l pl-4 -ml-px border-transparent hover:border-slate-400 dark:hover:border-slate-500 text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 hover:text-slate-900"
                    href="https://pub.dev/packages/{{ str($packageLink['link'])->replace('-', '_') }}"
                    >
                    {{ $packageLink['label'] }}
                </a>
            </li>
            @endforeach
        </ul>
    </li>

</ul>
