@php
    $indentClass = '';
    if ($level == 1) {
        $indentClass = 'pl-3';
    } elseif ($level == 2) {
        $indentClass = 'pl-6';
    } elseif ($level > 2) {
        $indentClass = 'pl-9';
    }
@endphp

<li class="{{ $indentClass }}">
    @if($item['anchor'])
        <a href="#{{ $item['anchor'] }}" 
           class="block py-1 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-300 transition-colors duration-200 border-l-2 border-transparent hover:border-slate-300 dark:hover:border-slate-600 pl-3 -ml-3"
           title="{{ $item['title'] }}">
            {{ $item['text'] }}
        </a>
    @else
        <span class="block py-1 text-slate-700 dark:text-slate-300 font-medium">
            {{ $item['text'] }}
        </span>
    @endif
    
    @if(count($item['children']) > 0)
        <ul class="space-y-2 mt-2">
            @foreach($item['children'] as $child)
                @include('docs.partials.toc-item', ['item' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    @endif
</li>
