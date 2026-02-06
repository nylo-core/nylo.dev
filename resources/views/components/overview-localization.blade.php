@props(['latestVersionOfNylo'])

<small class="font-[sora] text-gray-500">{{ __('Step 1') }}</small>
<p class="mb-1 font-medium dark:text-white">{{ __('Add your language files') }}</p>

<x-code-highlighter language="json" title="lang/en.json" class="col-span-1 mb-5">
{
    "welcome": "Welcome",
    "greeting": "Hello @{{name}}",
    "navigation": {
        "home": "Home",
        "profile": "Profile"
    }
}
</x-code-highlighter>

<small class="font-[sora] text-gray-500">{{ __('Step 2') }}</small>
<p class="mb-1 font-medium dark:text-white">{{ __('Translate text in your widgets') }}</p>

<x-code-highlighter language="dart" header="false" class="col-span-1">
// Simple translation
Text("welcome".tr())  // "Welcome"

// With arguments
Text("greeting".tr(arguments: {"name": "Anthony"}))
// "Hello Anthony"

// Nested keys
Text("navigation.home".tr())  // "Home"
</x-code-highlighter>

                        <p class="text-[18px] text-[#979DA2] mt-2" style="letter-spacing: -0.02em;">{{ __('Multi-language support with JSON files, arguments, and RTL.') }}</p>
                        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'localization']) }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
                            {{ __('Learn more') }} <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
                        </a>

                        <div class="grid grid-cols-2 gap-5 mt-5">
                            @foreach([
                                [
                                    'title' => __('Translations'),
                                    'link' => 'localizing-text'
                                ],
                                [
                                    'title' => __('Arguments'),
                                    'link' => 'arguments'
                                ],
                                [
                                    'title' => __('Locale Switching'),
                                    'link' => 'updating-the-locale'
                                ],
                                [
                                    'title' => __('RTL Support'),
                                    'link' => 'rtl-support'
                                ],
                                ] as $item)
                                <a class="bg-[#f9f9f9] font-medium border border-slate-200 hover hover:bg-[#ffffff] py-2 rounded-lg text-center transition-all" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'localization']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
                            @endforeach
                        </div>
