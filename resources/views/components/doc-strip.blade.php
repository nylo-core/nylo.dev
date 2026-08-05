@props(['label', 'items', 'linkText' => null, 'linkHref' => null])

{{--
    Inline meta strip, e.g. "BEFORE YOU START · Flutter SDK installed · Dart 3".
    `items` is a comma-separated list.
--}}
@php
    $entries = array_values(array_filter(array_map('trim', explode(',', (string) $items))));
@endphp

<div class="ny-doc-strip">
<span class="ny-doc-strip-label">{{ $label }}</span>
@foreach($entries as $entry)
@if(! $loop->first)
<span class="ny-doc-strip-dot"></span>
@endif
<span class="ny-doc-strip-item">{{ $entry }}</span>
@endforeach
@if($linkText && $linkHref)
<span class="ny-doc-strip-dot"></span>
<a href="{{ $linkHref }}">{{ $linkText }} →</a>
@endif
</div>
