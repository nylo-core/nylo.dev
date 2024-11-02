@props(['latestVersionOfNylo'])

<p>Authenticate a user</p>

<x-code-highlighter language="dart" header="false" class="col-span-1 mb-5">
String userToken = "eyJhbG123...";

await Auth.authenticate(data: {"token": userToken});
</x-code-highlighter>

<p class="mb-1">Now, when your user opens the app they will be authenticated.</p>

<x-code-highlighter language="dart" header="false" class="col-span-1 mb-5">

final userData = Auth.data();
// {"token": "eyJhbG123..."}

bool isAuthenticated = await Auth.isAuthenticated();
// true
</x-code-highlighter>

<p class="mb-1">If you've set an <span class="font-medium">authenticatedRoute</span> in your router, then it will present this page when the user opens the app again.</p>

<x-code-highlighter language="dart" title="routes/router.dart" class="col-span-1 mb-5">
appRouter() => nyRoutes((router) {
    ...
    router.add(LandingPage.path).initialRoute();

    router.add(DashboardPage.path).authenticatedRoute();
    // overrides the initial route when a user is authenticated
</x-code-highlighter>

<p>Logout the user</p>

<x-code-highlighter language="dart" header="false" class="col-span-1 mb-5">
await Auth.logout();
</x-code-highlighter>
                        <p class="text-[18px] text-[#979DA2] mt-2" style="letter-spacing: -0.02em;">Authenticate users in your Flutter application.</p>
                        <a href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'authentication']) }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
                            Learn more <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
                        </a>

                        <div class="grid grid-cols-2 gap-5 mt-5">
                            @foreach([
                                [
                                    'title' => 'Authentication Page',
                                    'link' => 'authentication-page'
                                ],
                                [
                                    'title' => 'Adding an auth user',
                                    'link' => 'adding-an-auth-user'
                                ],
                                [
                                    'title' => 'Retrieve an auth user',
                                    'link' => 'retrieve-an-auth-user'
                                ],
                                [
                                    'title' => 'Logout',
                                    'link' => 'removing-an-auth-user'
                                ],
                                ] as $item)
                                <a class="bg-[#f9f9f9] font-medium border border-slate-200 hover hover:bg-[#ffffff] py-2 rounded-lg text-center transition-all" href="{{ route('landing.docs', ['version' => $latestVersionOfNylo, 'page' => 'authentication']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
                            @endforeach
                        </div>
