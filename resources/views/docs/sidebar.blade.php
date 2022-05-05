<ul class="border-r-2 border-gray-50">
    <button id="close-menu" class="flex hidden">
        <?xml version="1.0"?><svg fill="#3e4757" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 30 30" width="30px" height="30px">    <path d="M 7 4 C 6.744125 4 6.4879687 4.0974687 6.2929688 4.2929688 L 4.2929688 6.2929688 C 3.9019687 6.6839688 3.9019687 7.3170313 4.2929688 7.7070312 L 11.585938 15 L 4.2929688 22.292969 C 3.9019687 22.683969 3.9019687 23.317031 4.2929688 23.707031 L 6.2929688 25.707031 C 6.6839688 26.098031 7.3170313 26.098031 7.7070312 25.707031 L 15 18.414062 L 22.292969 25.707031 C 22.682969 26.098031 23.317031 26.098031 23.707031 25.707031 L 25.707031 23.707031 C 26.098031 23.316031 26.098031 22.682969 25.707031 22.292969 L 18.414062 15 L 25.707031 7.7070312 C 26.098031 7.3170312 26.098031 6.6829688 25.707031 6.2929688 L 23.707031 4.2929688 C 23.316031 3.9019687 22.682969 3.9019687 22.292969 4.2929688 L 15 11.585938 L 7.7070312 4.2929688 C 7.5115312 4.0974687 7.255875 4 7 4 z"/></svg> <span class="self-center ml-1">Close Menu</span>
    </button>
    @foreach(config('project.doc-index.versions')[$version] as $key => $docLinks)
    <li class="mt-12 lg:mt-8">
        <h5 class="mb-8 lg:mb-3 font-semibold text-slate-900 dark:text-slate-200">{{ str($key)->headline() }}</h5>

        <ul class="space-y-6 lg:space-y-2 border-l border-slate-100 dark:border-slate-800">
            @foreach($docLinks as $dockLink)
            <li>
                <a
                class="block border-l pl-4 -ml-px border-transparent hover:border-slate-400 dark:hover:border-slate-500 text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 {!! Request::is('docs/' . $version . '/' . $dockLink) ? 'text-blue-600' : 'hover:text-slate-900' !!}"
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