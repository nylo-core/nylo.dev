@props(['latestVersionOfNylo'])

<small class="font-[sora] text-gray-500">{{ __('Step 1') }}</small>
<p class="mb-1 font-medium dark:text-white">{{ __('Save data securely') }}</p>

<x-code-highlighter language="dart" title="saving_data.dart" class="col-span-1 mb-5">
// Save values to secure storage
await NyStorage.save("coins", 100);
await NyStorage.save("username", "Anthony");
await NyStorage.save("isPremium", true);

// Save with TTL (auto-expires)
await NyStorage.save("session", "abc123",
    expiry: Duration(hours: 1),
);
</x-code-highlighter>

<small class="font-[sora] text-gray-500">{{ __('Step 2') }}</small>
<p class="mb-1 font-medium dark:text-white">{{ __('Read with type casting') }}</p>

<x-code-highlighter language="dart" header="false" class="col-span-1">
// Automatic type casting
String? username = await NyStorage.read("username");
int? coins = await NyStorage.read<int>("coins");
bool? isPremium = await NyStorage.read<bool>("isPremium");

// Delete a value
await NyStorage.delete("coins");
</x-code-highlighter>

                        <p class="text-[18px] text-[#979DA2] mt-2" style="letter-spacing: -0.02em;">{{ __('Secure local storage with type casting, TTL expiry, and collections.') }}</p>
                        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'storage']) }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
                            {{ __('Learn more') }} <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
                        </a>

                        <div class="grid grid-cols-2 gap-5 mt-5">
                            @foreach([
                                [
                                    'title' => __('Save Data'),
                                    'link' => 'save-values'
                                ],
                                [
                                    'title' => __('Read Data'),
                                    'link' => 'read-values'
                                ],
                                [
                                    'title' => __('Collections'),
                                    'link' => 'introduction-to-collections'
                                ],
                                [
                                    'title' => __('Backpack'),
                                    'link' => 'backpack-storage'
                                ],
                                ] as $item)
                                <a class="bg-[#f9f9f9] font-medium border border-slate-200 hover hover:bg-[#ffffff] py-2 rounded-lg text-center transition-all" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'storage']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
                            @endforeach
                        </div>
