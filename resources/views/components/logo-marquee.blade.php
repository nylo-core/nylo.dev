@props(['title' => 'Built with Nylo'])

@php
    $logos = [
        'baseline.png',
        'ipbpay.png',
        'woosignal.png',
        'the_pwer.png',
        'loco.png',
        'tudae.png',
        'pretalk.png',
        'bikebee.png',
    ];
@endphp

<div class="py-12">
    <p class="text-center text-sm text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-8 font-medium">{{ $title }}</p>
    
    <div class="marquee-container">
        <div class="marquee-content animate-marquee">
            @foreach ($logos as $logo)
            <div class="logo-placeholder bg-white">
                <img src="{{ asset('images/companies/' . $logo) }}" alt="{{ $logo }}" class="w-full h-full object-contain">
            </div>
            @endforeach
        </div>
    </div>
</div>