@extends('layouts.app-landing')

@section('content')
<section class="section home" id="home">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="home-wrapper text-center">
                    <h1>Supporting {{ config('app.name') }}</h1>
                    <p>Any contributes help in a big way, our goal is to provide regular maintenance, patchs and delivery some cool features for the framework.</p>
                    <span><a href="#submit-a-pr">Submit a PR</a></span>
                </div>
            </div>
        </div>
   </div>
</section>

<div class="position-relative">
    <div class="shape overflow-hidden text-white">
        <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
        </svg>
    </div>
</div>

<section class="section" id="features">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6 text-left">
                <h2 id="submit-a-pr" class="title">Submitting a PR</h2>
                <p class="title-alt">We welcome PR's for ideas/improvements to the framework.</p>
                <p>To help us understand your PR quicker, try to format it like the below</p>
                <ul>
                    <li>Issue: the issue your PR fixes</li>
                    <li>Areas: the code areas that your PR might effect</li>
                </ul>
                <a href="https://github.com/nylo-core/framework">{{ config('app.name') }} Framework Repository</a>
                <a href="https://github.com/nylo-core/support">{{ config('app.name') }} Support Repository</a>
            </div>
            <div class="col-sm-2 text-center align-content-center">
                <i class="ri-service-fill h1"></i>
            </div>
        </div>

    </div>
</section>

<div class="position-relative">
    <div class="shape overflow-hidden text-footer">
        <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="#343a40"></path>
        </svg>
    </div>
</div>
@endsection