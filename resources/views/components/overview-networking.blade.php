@props(['latestVersionOfNylo'])

<small class="font-[sora] text-gray-500">{{ __('Step 1') }}</small>
<p class="mb-1 font-medium dark:text-white">{{ __('Create an API Service') }}</p>

<x-code-highlighter language="bash" title="terminal" class="col-span-1 mb-5">
metro make:api_service User
</x-code-highlighter>

<x-code-highlighter language="dart" title="app/networking/user_api_service.dart" class="col-span-1 mb-5">
class UserApiService extends NyApiService {
    @override
    String get baseUrl => getEnv("API_BASE_URL");

    Future<User?> fetchUser(int id) async {
        return await get<User>(
            "/users/$id",
            queryParameters: {"include": "profile"},
        );
    }

    Future<User?> createUser(Map<String, dynamic> data) async {
        return await post<User>("/users", data: data);
    }
}
</x-code-highlighter>

<small class="font-[sora] text-gray-500">{{ __('Step 2') }}</small>
<p class="mb-1 font-medium dark:text-white">{{ __('Call the API from your page') }}</p>

<x-code-highlighter language="dart" header="false" class="col-span-1">
User? user = await api<UserApiService>(
    (request) => request.fetchUser(1),
);
</x-code-highlighter>

                        <p class="ny-tab-blurb">{{ __('Elegant API services with automatic JSON parsing, caching, and interceptors.') }}</p>
                        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'networking']) }}" target="_BLANK" class="ny-tab-link">
                            {{ __('Learn more') }} <span aria-hidden="true">↗</span>
                        </a>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-5">
                            @foreach([
                                [
                                    'title' => __('GET Requests'),
                                    'link' => 'convenience-methods'
                                ],
                                [
                                    'title' => __('Interceptors'),
                                    'link' => 'interceptors'
                                ],
                                [
                                    'title' => __('Morphing JSON'),
                                    'link' => 'morphing-json-payloads-to-models'
                                ],
                                [
                                    'title' => __('Caching'),
                                    'link' => 'caching-responses'
                                ],
                                ] as $item)
                                <a class="ny-chip justify-center py-2.5" href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'networking']) }}#{{ $item['link'] }}" target="_BLANK">{{ $item['title'] }}</a>
                            @endforeach
                        </div>
