<div x-data="{ activeTab: 'routing' }" class="w-full max-w-2xl">
    {{-- Tab Buttons --}}
    <div class="flex gap-1 p-1 mb-0 bg-slate-800/50 rounded-t-xl backdrop-blur">
        <button 
            @click="activeTab = 'routing'" 
            :class="{ 'bg-slate-700 text-white': activeTab === 'routing', 'text-slate-400 hover:text-slate-200': activeTab !== 'routing' }"
            class="flex-1 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200"
        >
            Routing
        </button>
        <button 
            @click="activeTab = 'api'" 
            :class="{ 'bg-slate-700 text-white': activeTab === 'api', 'text-slate-400 hover:text-slate-200': activeTab !== 'api' }"
            class="flex-1 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200"
        >
            API
        </button>
        <button 
            @click="activeTab = 'forms'" 
            :class="{ 'bg-slate-700 text-white': activeTab === 'forms', 'text-slate-400 hover:text-slate-200': activeTab !== 'forms' }"
            class="flex-1 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200"
        >
            Forms
        </button>
        <button 
            @click="activeTab = 'state'" 
            :class="{ 'bg-slate-700 text-white': activeTab === 'state', 'text-slate-400 hover:text-slate-200': activeTab !== 'state' }"
            class="flex-1 px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200"
        >
            State
        </button>
    </div>

    {{-- Tab Content --}}
    <div class="relative">
        {{-- Routing Tab --}}
        <div x-show="activeTab === 'routing'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <x-code-highlighter language="dart" header="false" title="routes/router.dart" class="rounded-t-none">
appRouter() => nyRoutes((router) {
  router.route(HomePage.path, (context) => HomePage());
  
  router.route(ProfilePage.path, (context) => ProfilePage(), 
    authPage: true // Protected route
  );
});
            </x-code-highlighter>
        </div>

        {{-- API Tab --}}
        <div x-show="activeTab === 'api'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <x-code-highlighter language="dart" header="false" title="app/networking/api_service.dart" class="rounded-t-none">
class ApiService extends NyApiService {
  @override
  String get baseUrl => "https://api.example.com/v1";

  Future<User> fetchUser(int id) async {
    return await network(
      request: (request) => request.get("/users/$id"),
    );
  }
}
            </x-code-highlighter>
        </div>

        {{-- Forms Tab --}}
        <div x-show="activeTab === 'forms'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <x-code-highlighter language="dart" header="false" title="resources/forms/login_form.dart" class="rounded-t-none">
class LoginForm extends NyFormData {
  LoginForm() : super(name: "login_form");

  @override
  fields() => [
    Field("Email", cast: FormCast.email()),
    Field("Password", cast: FormCast.password()),
  ];
}
            </x-code-highlighter>
        </div>

        {{-- State Tab --}}
        <div x-show="activeTab === 'state'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <x-code-highlighter language="dart" header="false" title="app/controllers/home_controller.dart" class="rounded-t-none">
class HomeController extends NyController {
  
  int counter = 0;
  
  void increment() {
    counter++;
    setState(() {}); // Reactive update
  }
}
            </x-code-highlighter>
        </div>
    </div>

    {{-- Learn More Link --}}
    <div class="mt-4 text-center">
        <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo]) }}" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-primary-blue transition-colors">
            Explore the documentation
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</div>