<ul class="border-r-2 border-gray-50">

    @foreach(config('project.doc-index.versions')[$version] as $key => $docLinks)
    <li class="mt-12 lg:mt-8">
        <h5 class="mb-8 lg:mb-3 font-semibold text-slate-900 dark:text-slate-200">{{ str($key)->headline() }}</h5>

        <ul class="space-y-6 lg:space-y-2 border-l border-slate-100 dark:border-slate-800">
            @foreach($docLinks as $dockLink)
            <li>
                <a
                class="block border-l pl-4 -ml-px border-transparent hover:border-slate-400 dark:hover:border-slate-500 text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 {!! Request::is('docs/3.x/' . $dockLink) ? 'text-blue-600' : 'hover:text-slate-900' !!}"
                href="{{ route('landing.docs', ['page' => $dockLink, 'version' => $version]) }}"
                >
                {{ str($dockLink)->headline() }}
            </a>
        </li>
        @endforeach
    </ul>
</li>
    @endforeach

</ul>