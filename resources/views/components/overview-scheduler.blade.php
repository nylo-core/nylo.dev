@props(['latestVersionOfNylo'])

<p class="mb-1 dark:text-white">{{ __('Schedule a task to run once') }}</p>
<x-code-highlighter language="dart" header="false" class="col-span-1 mb-5">
Nylo.scheduleOnce("onboarding_info", () {
    print("Perform code here to run once");
});
</x-code-highlighter>

<p class="mb-1 dark:text-white">{{ __('Schedule a task to run once after a specific date') }}</p>
<x-code-highlighter language="dart" header="false" class="col-span-1 mb-5">
Nylo.scheduleOnceAfterDate("app_review_rating", () {
    print("Perform code to run once after DateTime(2025, 04, 10)");
}, date: DateTime(2025, 04, 10));
</x-code-highlighter>

<p class="mb-1 dark:text-white">{{ __('Schedule a task to run once daily') }}</p>
<x-code-highlighter language="dart" header="false" class="col-span-1 mb-5">
Nylo.scheduleOnceDaily("free_daily_coins", () {
    print("Perform code to run once daily");
});
</x-code-highlighter>

                        <p class="text-[18px] text-[#979DA2] mt-2" style="letter-spacing: -0.02em;">{{ __('Schedule tasks to run once or daily in your Flutter application.') }}</p>
                        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'scheduler']) }}" target="_BLANK" class="inline-flex self-center text-[#6C7379]">
                            {{ __('Learn more') }} <img src="{{ asset('images/upper_right_arrow.png') }}" class="h-[20px] w-[20px] self-center">
                        </a>

                        <div class="grid grid-cols-2 gap-5 mt-5">
                            @foreach([
                                [
                                    'title' => __('Schedule Once After Date'),
                                    'link' => 'schedule-once-after-date'
                                ],
                                [
                                    'title' => __('Scheduling Once Daily'),
                                    'link' => 'schedule-once-daily'
                                ],
                                ] as $item)
                                <a class="bg-[#f9f9f9] font-medium border border-slate-200 hover hover:bg-[#ffffff] py-2 rounded-lg text-center transition-all" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'scheduler']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
                            @endforeach
                        </div>
