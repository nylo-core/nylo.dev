@extends('layouts.app-landing')

@section('content')

<section class="prose lg:prose-lg mx-auto mt-[80px] md:px-0 px-6 dark:prose-invert">

    <div>
        <div class="mb-4">
        <span class="bg-[#ECF5FC] px-2 text-primary-blue-deep rounded-3xl py-1 font-semibold dark:bg-slate-700">{{ __('Updated:') }} 16 May, 2023</span>
        </div>

        <div class="prose-headings:text-gray-900 dark:prose-headings:text-white prose-p:text-gray-600 dark:prose-p:text-slate-300">
            {!! $content !!}
        </div>
    </div>
</section>

@endsection
