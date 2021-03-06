<header class="transparent" style="position: relative;background: #fafbff;">
    <nav class="navbar navbar-expand-lg navbar-default navbar-light navbar-custom sticky">
        <div class="container">
            <!-- LOGO -->
            <a class="navbar-brand logo" href="{{ route('landing.index') }}">
                <img src="{{ asset('images/nylo_logo_filled.png') }}" style="height: 50px;" alt="{{ config('app.name') }} logo">
            </a>
            
            
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="nav navbar-nav ml-auto navbar-center" id="mySidenav">
                    <li class="nav-item">
                        <a href="/docs" class="nav-link scroll">Documentation</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('resources.index') }}" class="nav-link scroll">Resources</a>
                    </li>
                    <li class="nav-item">
                        <a href="https://github.com/nylo-core/framework/blob/{{ $latestVersionOfNylo }}/CHANGELOG.md" target="_BLANK" class="nav-link scroll">Changelog</a>
                    </li>
                    <li class="nav-item">
                        <a href="https://pub.dev/publishers/nylo.dev/packages" target="_BLANK" class="nav-link scroll">Packages</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>