# Providers

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Create a provider](#create-a-provider "Create a provider")
- [Provider object](#provider-object "Provider object")


<a name="introduction"></a>
<br>

## Introduction to Providers

In {{ config('app.name') }}, providers are booted initially from your <b>main.dart</b> file when your application runs. All your providers reside in `/lib/app/providers/*`, you can modify these files or create your providers using <a href="/docs/3.x/metro#make-provider" target="_BLANK">Metro</a>.

Providers can be used when you need to initialize a class, package or create something before the app initially loads. I.e. the `route_provider.dart` class is responsible for adding all the routes to {{ config('app.name') }}.

### Deep dive

```dart
import 'package:flutter/material.dart';
import 'package:flutter_app/bootstrap/app.dart';
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  {{ config('app.name') }} nylo = await {{ config('app.name') }}.init(setup: Boot.nylo, setupFinished: Boot.finished); // This is where providers are booted

  runApp(
    AppBuild(
      navigatorKey: NyNavigator.instance.router.navigatorKey,
      onGenerateRoute: nylo.router!.generator(),
      debugShowCheckedModeBanner: false,
    ),
  );
}
```

### Lifecycle

- `Boot.{{ config('app.name') }}` will loop through your registered providers inside <b>config/providers.dart</b> file and boot them.

- `Boot.Finished` is called straight after **"Boot.{{ config('app.name') }}"** is finished, this method will bind the {{ config('app.name') }} instance to `Backpack` with the value 'nylo'.

E.g. Backpack.instance.read('nylo'); // {{ config('app.name') }} instance


<a name="create-a-provider"></a>
<br>

## Create a new Provider

You can create new providers by running the below command in the terminal.

```dart
flutter pub run nylo_framework:main make:provider cache_provider
```

<a name="provider-object"></a>
<br>

## Provider Object

Your provider will have one method `boot({{ config('app.name') }} nylo)`, in this method you can call any logic that needs to be run before Flutter runs your application.

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
}
```

The boot method also provides an instance of the {{ config('app.name') }} object as an argument.

> Inside the `boot` method, you must **return** an instance of `{{ config('app.name') }}` or `null` like the above.
