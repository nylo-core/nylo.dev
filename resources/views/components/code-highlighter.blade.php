@props(['language', 'class', 'title', 'header'])

<div class="code-highlighter max-w-full {!! $class !!}">
    @if($header != 'false')
    <div class="code-header">
        <span>{{ $title }}</span>
    </div>
    @endif
<pre><code>{!! $highlightCode($slot) !!}</code></pre>
</div>
