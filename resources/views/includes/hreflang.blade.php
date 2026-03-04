{{-- Hreflang tags for localized pages --}}
@php
    $routeName = Route::currentRouteName();
    $isDocPage = in_array($routeName, ['landing.docs', 'landing.docs.default']);
    $routeParams = Route::current()->parameters();
@endphp

@if($routeName && (str_starts_with($routeName, 'landing.') || $isDocPage))
    @if($isDocPage)
        {{-- Doc pages: use non-localized /docs/ route as English + x-default --}}
        @php
            $docParams = ['version' => $routeParams['version'] ?? '', 'page' => $routeParams['page'] ?? 'installation'];
        @endphp
        <link rel="alternate" hreflang="en" href="{{ route('landing.docs.default', $docParams) }}" />
        <link rel="alternate" hreflang="x-default" href="{{ route('landing.docs.default', $docParams) }}" />
        @foreach(config('localization.supported_locales') as $code => $locale)
            @if($code !== 'en')
                <link rel="alternate" hreflang="{{ $code }}" href="{{ route('landing.docs', array_merge($docParams, ['locale' => $code])) }}" />
            @endif
        @endforeach
    @else
        {{-- Non-doc landing pages: use localized routes --}}
        @foreach(config('localization.supported_locales') as $code => $locale)
            <link rel="alternate" hreflang="{{ $code }}" href="{{ route($routeName, array_merge($routeParams, ['locale' => $code])) }}" />
        @endforeach
        <link rel="alternate" hreflang="x-default" href="{{ route($routeName, array_merge($routeParams, ['locale' => 'en'])) }}" />
    @endif
@endif
