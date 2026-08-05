@props(['number', 'title' => null])

{{-- One step in an <x-doc-steps> timeline. Slot content is markdown. --}}
<div class="ny-doc-step">
<span class="ny-doc-step-num">{{ $number }}</span>
@if($title)
<h3 class="ny-doc-step-title">{{ $title }}</h3>
@endif

{{ $slot }}

</div>
