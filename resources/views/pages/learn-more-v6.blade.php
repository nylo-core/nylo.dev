@extends('layouts.app-landing')

@section('content')

<section class="prose lg:prose-lg mx-auto mt-[80px] md:px-0 px-6 font-[outfit]">

        <div class="mb-4">
        <span class="bg-[#ECF5FC] border-2 border-gray-300 font-semibold px-3 py-1 rounded-2xl text-gray-600 text-sm">Nylo v6</span>
        </div>

        <h1 class="text-h1-learn-more mb-1">Nylo v6 is here!</h1>

        <p>
            The long-awaited Nylo v6 is finally here, and it's packed with a ton of new features and improvements.
        </p>

        <p>
            Highlights of Nylo v6 include:
        </p>

        <ul>
            <li>Improved state performance</li>
            <li>Router improvements</li>
            <li>Navigation Hub for managing bottom and top navigation</li>
            <li>New Scaffold UI package</li>
            <li>New Cache driver</li>
            <li>Local push notifications</li>
            <li>Authentication updates</li>
            <li>Big updates to Forms</li>
            <li>Tons of bug fixes and improvements</li>
        </ul>

        <p>
            We've also updated the documentation to reflect these changes. You can check out the updated documentation <a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo]) }}" class="underline hover:text-gray-700 transition-all">here</a>.
        </p>

        <p>
            Enjoy Nylo v6!
        </p>

        <a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo]) }}">Get Started</a>

</section>

@endsection
