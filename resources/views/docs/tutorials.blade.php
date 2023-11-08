@extends('layouts.app-tutorials')

@section('content')

<span class="text-gray-400"><span class="text-slate-400 font-light">Tutorials</span> | {{ str($section)->headline() }}</span>
<h1 class="text-2xl pb-[20px]">{{ str($page)->headline() }}</h1>
<article>

@include('docs.embed-video')

</article>

@endsection
