<footer class="bg-dark footer-one">
    <div class="container">
        <div class="row">

            <div class="col-md-2 text-center">
                <img src="{{ asset('images/nylo_logo.png') }}" alt="{{ config('app.name') }} logo" class="footer-logo" height="50"> 
            </div>

            <div class="col-md-3 col-sm-6">
                <h5>Documentation</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'installation']) }}">Installation</a></li>
                    <li><a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'requirements']) }}">Requirements</a></li>
                    <li><a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'router']) }}">Router</a></li>
                    <li><a href="https://github.com/nylo-core/framework/blob/master/CHANGELOG.md" target="_BLANK">Changelog</a></li>
                </ul>
            </div>

            <div class="col-md-3 col-sm-6">
                <h5>Resources</h5>
                <ul class="list-unstyled">
                    <li><a href="https://pub.dev/publishers/nylo.dev/packages" target="_BLANK">Flutter Packages</a></li>
                    <li><a href="{{ route('landing.contributions') }}">Contributions</a></li>
                    <li><a href="{{ route('landing.privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('landing.terms-and-conditions') }}">Terms &amp; Conditions</a></li>
                </ul>
            </div>

            <div class="col-lg-3">
                    <p class="about-text pt-1">{{ config('app.name') }} is a micro-framework for Flutter which is designed to help simplify developing apps. #nylo #flutter</p>
                    <ul class="list-unstyled">
                        <li class="list-inline-item"><a href="https://twitter.com/nylo_dev" target="_BLANK"><i class="ri-twitter-line h5"></i></a></li>
                        <li class="list-inline-item"><a href="https://github.com/nylo-core/nylo" target="_BLANK"><i class="ri-github-line h5"></i></a></li>
                    </ul>
            </div>
        </div>
    </div>
    <!-- end container -->

    <div class="footer-one-alt border-top">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <p class="font-13 copyright">&copy; {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved</p>
                </div>
                <div class="col-sm-6 text-right">
                    
                </div>
            </div>
        </div>
    </div>

</footer>