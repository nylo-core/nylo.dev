@props(['title', 'items' => null])

{{--
    Bordered panel with a titled header.
    Pass `items` (array or newline-separated string) for the arrow grid,
    or leave it out and put markdown in the slot.
--}}
@php
    $rows = is_string($items)
        ? array_values(array_filter(array_map('trim', preg_split('/\R/u', $items))))
        : ($items ?? []);
@endphp

<div class="ny-doc-panel">
<div class="ny-doc-panel-head">
<img src="{{ asset('images/nylo_logo.png') }}" alt="" class="ny-mark h-[16px]">
<span>{{ $title }}</span>
</div>
<div class="ny-doc-panel-body">
@if(count($rows))
<div class="ny-doc-panel-grid">
@foreach($rows as $row)
<div class="ny-doc-panel-item">{{ $row }}</div>
@endforeach
</div>
@else

{{ $slot }}

@endif
</div>
</div>
