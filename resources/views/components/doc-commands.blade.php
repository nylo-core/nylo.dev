@props(['title', 'rows'])

{{--
    Command reference table. `rows` is one "command | description" pair per
    line, e.g.

    <x-doc-commands title="Widget commands" rows="
    metro make:page      | Creates a new page
    metro make:model     | Creates a new model
    " />
--}}
@php
    $entries = [];

    foreach (preg_split('/\R/u', (string) $rows) as $line) {
        $line = trim($line);

        if ($line === '') {
            continue;
        }

        [$command, $description] = array_pad(explode('|', $line, 2), 2, '');
        $entries[] = ['cmd' => trim($command), 'desc' => trim($description)];
    }
@endphp

<div class="ny-doc-commands">
<div class="ny-doc-commands-head">{{ $title }}</div>
@foreach($entries as $entry)
<div class="ny-doc-command">
<code>{{ $entry['cmd'] }}</code>
<span>{{ $entry['desc'] }}</span>
</div>
@endforeach
</div>
