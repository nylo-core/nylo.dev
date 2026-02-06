@extends('layouts.app-docs')

@section('content')

        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                {!! str( '# ' . $docContents['title'] )->markdown() !!}
            </div>
            <div x-data="{ copied: false }" class="flex-shrink-0 mt-1">
                <button
                    @click="
                        const markdown = $refs.rawMarkdown.value;
                        if (navigator.clipboard && window.isSecureContext) {
                            navigator.clipboard.writeText(markdown);
                        } else {
                            $refs.rawMarkdown.select();
                            document.execCommand('copy');
                        }
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                    "
                    class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition-colors duration-200"
                    :class="{ 'border-green-300 dark:border-green-700 text-green-600 dark:text-green-400': copied }"
                    title="Copy page markdown for LLMs">
                    <template x-if="!copied">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </template>
                    <template x-if="copied">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </template>
                    <span x-text="copied ? 'Copied!' : 'Copy markdown'"></span>
                </button>
                <textarea x-ref="rawMarkdown" class="sr-only" aria-hidden="true" tabindex="-1" readonly>{{ $docContents['rawMarkdown'] }}</textarea>
            </div>
        </div>

        {{-- Mobile table of contents --}}
        @if(!empty($docContents['on-this-page']))
            <nav class="lg:hidden mt-6 mb-8" x-data>
                @foreach($docContents['on-this-page'] as $item)
                    <div class="mb-4">
                        @if($item['anchor'])
                            <a href="#{{ $item['anchor'] }}"
                               @click.prevent="(() => { const el = document.getElementById('{{ $item['anchor'] }}'); if (el) { document.body.scrollTo({ top: el.offsetTop - 100, behavior: 'smooth' }); } })()"
                               class="flex items-start gap-1 font-medium" style="border: none !important;">
                                <span class="text-blue-300">#</span>
                                <span class="text-[#1f1f1f] dark:text-slate-200">{{ $item['text'] }}</span>
                            </a>
                        @else
                            <div class="flex items-start gap-1 font-medium">
                                <span class="text-blue-300">#</span>
                                <span class="text-[#1f1f1f] dark:text-slate-200">{{ $item['text'] }}</span>
                            </div>
                        @endif

                        @if(!empty($item['children']))
                            <div class="ml-6 mt-2 space-y-2">
                                @foreach($item['children'] as $child)
                                    @if($child['anchor'])
                                        <a href="#{{ $child['anchor'] }}"
                                           @click.prevent="(() => { const el = document.getElementById('{{ $child['anchor'] }}'); if (el) { document.body.scrollTo({ top: el.offsetTop - 100, behavior: 'smooth' }); } })()"
                                           class="flex items-start gap-1" style="border: none !important;">
                                            <span class="text-blue-300">#</span>
                                            <span class="text-[#1f1f1f] dark:text-slate-200">{{ $child['text'] }}</span>
                                        </a>
                                    @else
                                        <div class="flex items-start gap-1">
                                            <span class="text-blue-300">#</span>
                                            <span class="text-[#1f1f1f] dark:text-slate-200">{{ $child['text'] }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </nav>
        @endif


        {!! str( $docContents['contents'] )->markdown() !!}

@endsection