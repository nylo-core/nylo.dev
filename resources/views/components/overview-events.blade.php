@props(['latestVersionOfNylo'])

<small class="font-[sora] text-gray-500">{{ __('Step 1') }}</small>
<p class="mb-1 font-medium dark:text-white">{{ __('Create your event') }}</p>

<x-code-highlighter language="bash" title="terminal" class="col-span-1 mb-5">
metro make:event Logout
</x-code-highlighter>

<x-code-highlighter language="dart" title="app/events/logout_event.dart" class="col-span-1 mb-5">
class LogoutEvent implements NyEvent {
    @override
    final listeners = {
        DefaultListener: DefaultListener(),
    };
}

class DefaultListener extends NyListener {
    @override
    handle(dynamic event) async {

        // logout user
        await Auth.logout();

        // redirect to home page
        routeTo(HomePage.path,
            navigationType: NavigationType.pushAndForgetAll
        );
    }
}
</x-code-highlighter>

<small class="font-[sora] text-gray-500">{{ __('Step 2') }}</small>
<p class="mb-1 font-medium dark:text-white">{{ __('Dispatch the event') }}</p>

<x-code-highlighter language="dart" header="false" class="col-span-1">
MaterialButton(child: Text("Logout"),
    onPressed: () {
        event<LogoutEvent>();
    },
)
</x-code-highlighter>

                        <p class="text-[18px] text-[#979DA2] mt-2" style="letter-spacing: -0.02em;">{{ __('Dispatch events and listen for them in your application.') }}</p>
                        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'events']) }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
                            {{ __('Learn more') }} <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
                        </a>

                        <div class="grid grid-cols-2 gap-5 mt-5">
                            @foreach([
                                [
                                    'title' => __('Dispatching Events'),
                                    'link' => 'dispatching-events'
                                ],
                                [
                                    'title' => __('Listening to Events'),
                                    'link' => 'listening-to-events'
                                ],
                                ] as $item)
                                <a class="bg-[#f9f9f9] font-medium border border-slate-200 hover hover:bg-[#ffffff] py-2 rounded-lg text-center transition-all" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'events']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
                            @endforeach
                        </div>
