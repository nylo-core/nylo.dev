@props(['latestVersionOfNylo'])

<p>Authenticate a user</p>

<x-code-highlighter language="dart" header="false" class="col-span-1 mb-5">
User user = User();

await Auth.set(user);
</x-code-highlighter>

<p>Now, when your user opens the app they will be authenticated.</p>

<x-code-highlighter language="dart" header="false" class="col-span-1 mb-5">
User? user = await Auth.user<User>();

bool isAuthenticated = await Auth.loggedIn();
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
