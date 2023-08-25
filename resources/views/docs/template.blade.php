@extends('layouts.app-docs')

@section('content')

@if($viewingOldDocs)
<div class="p-3 bg-gray-50 mb-4 block rounded border-red-400 border-l-2">
	<span>
		<b>Notice:</b> You're viewing an old version of the {{ config('app.name') }} documentation.<br>Consider upgrading your project to {{ config('app.name') }} <a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => $docsContainPage ? $page : 'installation']) }}">{{ $latestVersionOfNylo }}</a>.
	</span>
</div>
@endif

<span class="text-gray-400">{{ str($section)->headline() }}</span>
<article>

{!! str( Blade::render(file_get_contents($mdDocPage), ['version' => $version]) )->markdown() !!}
</article>

@endsection
