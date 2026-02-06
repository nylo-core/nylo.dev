# Providers

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Create a provider](#create-a-provider "Create a provider")
- [Provider object](#provider-object "Provider object")


<div id="introduction"></div>

## Introduction to Providers

In Nylo, providers are booted initially from your <b>main.dart</b> file when your application runs. All your providers reside in `/lib/app/providers/*`, you can modify these files or create your providers using <a href="/docs/{{$version}}/metro#make-provider" target="_BLANK">Metro</a>.

Providers can be used when you need to initialize a class, package or create something before the app initially loads. I.e. the `route_provider.dart` class is responsible for adding all the routes to Nylo.

### Deep dive

```dart
import 'package:flutter/material.dart';
import 'package:flutter_app/bootstrap/app.dart';
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  Nylo nylo = await Nylo.init(setup: Boot.nylo, setupFinished: Boot.finished); // This is where providers are booted

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

- `Boot.Nylo` will loop through your registered providers inside <b>config/providers.dart</b> file and boot them.

- `Boot.Finished` is called straight after **"Boot.Nylo"** is finished, this method will bind the Nylo instance to `Backpack` with the value 'nylo'.

E.g. Backpack.instance.read('nylo'); // Nylo instance


<div id="create-a-provider"></div>

## Create a new Provider

You can create new providers by running the below command in the terminal.

```dart
flutter pub run nylo_framework:main make:provider cache_provider
```

<div id="provider-object"></div>

## Provider Object

Your provider will have one method `boot(Nylo nylo)`, in this method you can call any logic that needs to be run before Flutter runs your application.

Example: `lib/app/providers/app_provider.dart`

```dart
class AppProvider implements NyProvider {

  boot(Nylo nylo) async {
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

The boot method also provides an instance of the Nylo object as an argument.

> Inside the `boot` method, you must **return** an instance of `Nylo` or `null` like the above.
