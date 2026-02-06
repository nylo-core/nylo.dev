# Providers

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Create a provider](#create-a-provider "Create a provider")
- [Provider object](#provider-object "Provider object")


<div id="introduction"></div>

## Introduction to Providers

In {{ config('app.name') }}, providers are booted initially from your <b>main.dart</b> file when your application runs. All your providers reside in `/lib/app/providers/*`, you can modify these files or create your providers using <a href="/docs/{{$version}}/metro#make-provider" target="_BLANK">Metro</a>.

Providers can be used when you need to initialize a class, package or create something before the app initially loads. I.e. the `route_provider.dart` class is responsible for adding all the routes to {{ config('app.name') }}.

### Deep dive

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/6.x

/// Main entry point for the application.
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // Uncomment showSplashScreen to show the splash screen
    // File: lib/resources/widgets/splash_screen.dart
  );
}
```

### Lifecycle

- `Boot.{{ config('app.name') }}` will loop through your registered providers inside <b>config/providers.dart</b> file and boot them.

- `Boot.Finished` is called straight after **"Boot.{{ config('app.name') }}"** is finished, this method will bind the {{ config('app.name') }} instance to `Backpack` with the value 'nylo'.

E.g. Backpack.instance.read('nylo'); // {{ config('app.name') }} instance


<div id="create-a-provider"></div>

## Create a new Provider

You can create new providers by running the below command in the terminal.

```dart
dart run nylo_framework:main make:provider cache_provider
```

<div id="provider-object"></div>

## Provider Object

Your provider will have two methods, `boot({{ config('app.name') }} nylo)` and `afterBoot({{ config('app.name') }} nylo)`. 

When the app runs for the first time, any code inside your **boot** method will be executed first. You can also manipulate the `Nylo` object like in the below example.

Example: `lib/app/providers/app_provider.dart`

```dart
class AppProvider implements NyProvider {

  boot({{ config('app.name') }} nylo) async {
    await NyLocalization.instance.init(
        localeType: localeType,
        languageCode: languageCode,
        languagesList: languagesList,
        assetsDirectory: assetsDirectory,
        valuesAsMap: valuesAsMap);

    return nylo;
  }

  afterBoot({{ config('app.name') }} nylo) async {
    User user = await Auth.user();
    if (!user.isSubscribed) {
      await Auth.remove();
    }
  }
}
```

The `boot` method provides an instance of {{ config('app.name') }} as a parameter. 
The `afterBoot` method is called after {{ config('app.name') }} has finished booting.

> Inside the `boot` method, you must **return** an instance of `{{ config('app.name') }}` or `null` like the above.
