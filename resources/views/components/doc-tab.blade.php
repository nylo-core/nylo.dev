@props(['label'])

{{-- One panel inside <x-doc-tabs>. Slot content is markdown. --}}
<div class="ny-doc-tabpanel" role="tabpanel" x-show="tab === @js($label)" x-cloak>

{{ $slot }}

</div>
