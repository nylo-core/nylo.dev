@extends('layouts.app-landing')

@section('content')

<section class="prose lg:prose-lg dark:prose-invert prose-headings:text-gray-900 dark:prose-headings:text-white prose-code:text-blue-600 dark:prose-code:text-white dark:prose-code:bg-transparent mx-auto mt-[80px] md:px-0 px-6 pb-16 font-[outfit] max-w-4xl">

    <h1 class="text-h1-learn-more mb-0 pb-0 dark:text-white">Nylo v7</h1>
    <span class="block text-xl text-gray-500 dark:text-gray-400 pt-0 mt-0">A leap forward in Flutter development</span>

    <p class="text-xl text-gray-600 dark:text-gray-300">
        Building Flutter apps should feel intuitive, not overwhelming. Nylo v7 brings a cleaner architecture,
        encrypted environment variables, and a streamlined developer experience that lets you focus on what matters: your app.
    </p>

    {{-- What is Nylo --}}
    <h2>What is Nylo?</h2>
    <p>
        Nylo is a micro-framework for Flutter that provides structure and conventions for building mobile apps.
        Think of it as the missing architecture layer for Flutter &mdash; it handles routing, state management,
        API networking, theming, and localization so you can build production-ready apps without reinventing the wheel.
    </p>
    <p>
        If you've ever felt lost deciding how to organize a Flutter project, Nylo gives you a clear path forward.
    </p>

    {{-- Security First --}}
    <h2>Security First: Encrypted Environment Variables</h2>
    <p>
        One of the biggest changes in v7 is how environment variables are handled. Previously, sensitive values like API keys
        sat in plain text. Now, Nylo encrypts your environment at build time using XOR encryption.
    </p>
    <p>Getting started is simple:</p>
    <pre><code class="language-bash"># Generate your encryption key
metro make:key

# Encrypt your environment variables
metro make:env</code></pre>
    <p>
        This generates a secure <code>env.g.dart</code> file. Your API keys, secrets, and configuration are now
        protected &mdash; and it works seamlessly with CI/CD pipelines through <code>--dart-define</code>.
    </p>

    {{-- Configure API --}}
    <h2>One Method to Configure Everything</h2>
    <p>
        App configuration used to require multiple method calls scattered across your provider.
        Now, <code>nylo.configure()</code> consolidates everything into a single, readable block.
    </p>
    <pre><code class="language-dart">await nylo.configure(
  localization: NyLocalizationConfig(
    languageCode: 'en',
    assetsDirectory: 'assets/lang',
  ),
  themes: appThemes,
  initialThemeId: 'light_theme',
  modelDecoders: modelDecoders,
  controllers: controllers,
  apiDecoders: apiDecoders,
  useErrorStack: true,
);</code></pre>
    <p>
        Everything your app needs is defined in one place. No more hunting through multiple files to understand your app's setup.
    </p>

    {{-- Multi-Theme --}}
    <h2>Multi-Theme Support</h2>
    <p>
        Dark mode is expected in modern apps, but what about offering multiple light or dark theme variants?
        Nylo v7 makes this straightforward.
    </p>
    <pre><code class="language-dart">final List&lt;BaseThemeConfig&lt;ColorStyles&gt;&gt; appThemes = [
  BaseThemeConfig&lt;ColorStyles&gt;(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig&lt;ColorStyles&gt;(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),
  BaseThemeConfig&lt;ColorStyles&gt;(
    id: 'midnight_theme',
    theme: midnightTheme,
    colors: MidnightThemeColors(),
    type: NyThemeType.dark,
  ),
];</code></pre>
    <p>
        Users can pick their preferred theme, and your app remembers their choice.
        The new API makes theme switching and enumeration simple:
    </p>
    <pre><code class="language-dart">// Switch themes
NyTheme.set(context, id: 'midnight_theme');

// Get all available dark themes
List&lt;BaseThemeConfig&gt; darkOptions = NyTheme.darkThemes();

// Let the system decide, with preferred fallbacks
NyTheme.setPreferredDark('midnight_theme');
NyTheme.setPreferredLight('light_theme');</code></pre>

    {{-- Loading Styles --}}
    <h2>Consistent Loading States</h2>
    <p>
        Loading indicators should be consistent across your app. The new <code>LoadingStyle</code> system
        provides three options out of the box:
    </p>
    <pre><code class="language-dart">// Standard loading indicator
loadingStyle: LoadingStyle.normal()

// Skeleton loading effect
loadingStyle: LoadingStyle.skeletonizer()

// No loading indicator
loadingStyle: LoadingStyle.none()

// Custom loading widget
loadingStyle: LoadingStyle.normal(child: MyCustomLoader())</code></pre>

    {{-- Widgets --}}
    <h2>Refreshed Widget Library</h2>
    <p>
        Several widgets have been renamed for clarity and given improved APIs:
    </p>
    <ul>
        <li><code>NyListView</code> &rarr; <code>CollectionView</code> &mdash; with built-in pagination support</li>
        <li><code>NyFutureBuilder</code> &rarr; <code>FutureWidget</code> &mdash; simplified async data handling</li>
        <li><code>NyTextField</code> &rarr; <code>InputField</code> &mdash; with the new <code>FormValidator</code> API</li>
        <li><code>NyRichText</code> &rarr; <code>StyledText</code> &mdash; template-based text styling</li>
    </ul>
    <p>Here's <code>CollectionView</code> with pull-to-refresh pagination:</p>
    <pre><code class="language-dart">CollectionView&lt;User&gt;.pullable(
  data: (page) async => await api.getUsers(page: page),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
)</code></pre>

    {{-- Metro Commands --}}
    <h2>New Metro Commands</h2>
    <p>
        The Metro CLI continues to grow. New commands in v7 include:
    </p>
    <ul>
        <li><code>metro make:key</code> &mdash; Generate your APP_KEY for encryption</li>
        <li><code>metro make:env</code> &mdash; Generate encrypted environment file</li>
        <li><code>metro make:bottom_sheet_modal</code> &mdash; Scaffold bottom sheet modals</li>
        <li><code>metro make:button</code> &mdash; Create custom button widgets</li>
    </ul>

    {{-- Getting Started --}}
    <h2>Ready to Try Nylo?</h2>
    <p>
        Getting started with Nylo v7 takes just a few commands:
    </p>
    <pre><code class="language-bash"># Install the Nylo CLI globally
dart pub global activate nylo_installer

# Create a new project
nylo new my_app

# Set up Metro CLI
cd my_app
nylo init

# Run the app
flutter run</code></pre>
    <p>
        That's it. The installer configures everything automatically &mdash; routing, API services, theming, localization,
        and the Metro CLI for code generation.
    </p>

    <p class="mb-16">
        Migrating from v6? Our <a href="{{ route('landing.docs', ['locale' => app()->getLocale(), 'version' => $latestVersionOfNylo, 'page' => 'upgrade-guide']) }}">Upgrade Guide</a>
        walks you through every change with before/after examples.
    </p>


</section>

@endsection
