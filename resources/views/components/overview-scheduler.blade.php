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

                        <p class="ny-tab-blurb">{{ __('Schedule tasks to run once or daily in your Flutter application.') }}</p>
                        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'scheduler']) }}" target="_BLANK" class="ny-tab-link">
                            {{ __('Learn more') }} <span aria-hidden="true">↗</span>
                        </a>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-5">
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
                                <a class="ny-chip justify-center py-2.5" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'scheduler']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
                            @endforeach
                        </div>
