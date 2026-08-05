@props(['tabs'])

{{--
    Tab panel. `tabs` is a comma-separated list of labels; the slot holds one
    <x-doc-tab label="..."> per label. Alpine keeps the panels in sync.
--}}
@php
    $labels = array_values(array_filter(array_map('trim', explode(',', $tabs))));
    $first = $labels[0] ?? '';
@endphp

<div class="ny-doc-tabs" x-data="{ tab: @js($first) }">
<div class="ny-doc-tabbar" role="tablist">
@foreach($labels as $label)
<button type="button" role="tab" class="ny-doc-tab" @click="tab = @js($label)" :class="tab === @js($label) ? 'is-active' : ''" :aria-selected="tab === @js($label)">{{ $label }}</button>
@endforeach
</div>

{{ $slot }}

</div>
