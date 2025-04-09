# Upgrade Guide

---

<a name="section-1"></a>
- [What's changed in {{ $version }}](#whats-changed-in-nylo-6 "What's Changed in {{ $version }}")
- [Migration Guide](#migration-guide "Migration Guide")
- [How to upgrade](#how-to-upgrade "How to upgrade")


<a name="whats-changed-in-nylo-6"></a>
<br>

## What's Changed in Nylo {{ $version }}

You can understand all the changes by clicking the below link.

<a name="View {{ $version }} changes" href="https://github.com/nylo-core/nylo/compare/5.x...6.x#diff" target="_BLANK">View changes</a>

<div id="migration-guide"></div>
<br>

## Migration Guide

Upgrading from Nylo 5.x to Nylo 6.x will take roughly 1 hour. You can follow the below steps to upgrade your Nylo project.

### Step 1: Update the Nylo pubspec.yaml file

You'll need to ensure that your `pubspec.yaml` file contains the following dependencies:

```yaml
dependencies:
  url_launcher: ^6.2.6
  google_fonts: ^6.2.1
  analyzer: ^6.7.0
  intl: ^0.19.0
  nylo_framework: ^6.1.2
  pretty_dio_logger: ^1.4.0
  cupertino_icons: ^1.0.8
  path_provider: ^2.1.4
  flutter_local_notifications: ^18.0.0
  font_awesome_flutter: ^10.8.0
  scaffold_ui: ^1.1.2
  ...
```

Remove `win32` if it's present in your `pubspec.yaml` file.

And your dev dependacies should look like this:

```yaml
dev_dependencies:
  rename: ^3.0.2
  flutter_launcher_icons: ^0.14.1
```

Your environment should look like this:

```yaml
environment:
  sdk: '>=3.4.0 <4.0.0'
  flutter: ">=3.24.0 <4.0.0"
```

### Step 2: Update the Nylo `main.dart` file

You'll need to update your `main.dart` file to look like this:

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

Next, copy this [file](https://github.com/nylo-core/nylo/blob/6.x/lib/resources/widgets/splash_screen.dart) from GitHub to **lib/resources/widgets/splash_screen.dart**.

Next, you'll need to update your `bootstrap/boot.dart` file to look like this:

```dart
import 'package:flutter/material.dart';
import '/resources/widgets/splash_screen.dart';
import '/bootstrap/app.dart';
import '/config/providers.dart';
import 'package:nylo_framework/nylo_framework.dart';

/* Boot
|--------------------------------------------------------------------------
| The boot class is used to initialize your application.
| Providers are booted in the order they are defined.
|-------------------------------------------------------------------------- */

class Boot {
  /// This method is called to initialize Nylo.
  static Future<Nylo> nylo() async {
    WidgetsFlutterBinding.ensureInitialized();

    if (getEnv('SHOW_SPLASH_SCREEN', defaultValue: false)) {
      runApp(SplashScreen.app());
    }

    await _setup();
    return await bootApplication(providers);
  }

  /// This method is called after Nylo is initialized.
  static Future<void> finished(Nylo nylo) async {
    await bootFinished(nylo, providers);

    runApp(Main(nylo));
  }
}

/* Setup
|--------------------------------------------------------------------------
| You can use _setup to initialize classes, variables, etc.
| It's run before your app providers are booted.
|-------------------------------------------------------------------------- */

_setup() async {
  /// Example: Initializing StorageConfig
  // StorageConfig.init(
  //   androidOptions: AndroidOptions(
  //     resetOnError: true,
  //     encryptedSharedPreferences: false
  //   )
  // );
}
```

### Step 3: Updates to routes

The 'routes.dart' file has been updated.

From...
```dart
appRouter() => nyRoutes((router) {
    router.route(HomePage.path, (context) => HomePage(), initialRoute: true);

    router.route(SecondPage.path, (context) => SecondPage());

    router.route(ThirdPage.path, (context) => ThirdPage());
    ...
```

To this...
```dart
appRouter() => nyRoutes((router) {
    router.add(HomePage.path).initialRoute();

    router.add(SecondPage.path);

    router.add(ThirdPage.path);
    ...
```

### Step 4: Updating your Pages

You'll need to update your pages to use new syntax.

From...
```dart
...
class HomePage extends NyStatefulWidget<HomeController> {
  static const path = '/home';

  HomePage({super.key}) : super(path, child: () => _HomePageState());
}

class _HomePageState extends NyState<HomePage> {

  @override
   init() async {
     
   }

   @override
   boot() async {
     
   }

 bool get useSkeletonizer => true;

@override
 Widget loading(BuildContext context) {
   return Scaffold(
       body: Center(child: Text("Loading..."))
   );
 }

  /// The [view] method should display your page.
  @override
  Widget build(BuildContext context) {
    ...
  }
```

to this...
```dart
...
class HomePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/home", (_) => HomePage());

  HomePage() : super(child: () => _HomePageState());
}

class _HomePageState extends NyPage<HomePage> {
  
  @override
  get init => () async {
    
  };

  /// Define the Loading style for the page.
  /// Options: LoadingStyle.normal(), LoadingStyle.skeletonizer(), LoadingStyle.none()
  /// uncomment the code below.
  LoadingStyle get loadingStyle => LoadingStyle.normal();

  /// The [view] method displays your page.
  @override
  Widget view(BuildContext context) {
  }
```

The important changes are:
- `init() async` is now `get init => () async`
- `boot` has been replaced with init
- `useSkeletonizer` is now `LoadingStyle`
- `loading` can now done using `loadingStyle`. E.g. `LoadingStyle.normal(child: Text("Loading..."))`
- The page path `static const path = '/home';` is now `static RouteView path = ("/home", (_) => HomePage());`

### Step 5: Updating Storage Keys

You'll need to update your storage keys to use the new syntax.

From...
```dart
class StorageKey {
  static String userToken = "USER_TOKEN";
  static String authUser = getEnv('AUTH_USER_KEY', defaultValue: 'AUTH_USER');

  /// Add your storage keys here...
}
```

to this...
```dart
class Keys {
  // Define the keys you want to be synced on boot
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // give the user 10 coins by default
        ];
      };

  static StorageKey auth = getEnv('SK_USER', defaultValue: 'SK_USER');

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';
```

### Step 6: Updating your Providers

The AppProvider has been updated to the following:

```dart
import '/config/keys.dart';
import '/app/forms/style/form_style.dart';
import '/config/form_casts.dart';
import '/config/decoders.dart';
import '/config/design.dart';
import '/config/theme.dart';
import '/config/validation_rules.dart';
import '/config/localization.dart';
import 'package:nylo_framework/nylo_framework.dart';

class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    await NyLocalization.instance.init(
      localeType: localeType,
      languageCode: languageCode,
      assetsDirectory: assetsDirectory,
    );

    FormStyle formStyle = FormStyle();

    nylo.addLoader(loader);
    nylo.addLogo(logo);
    nylo.addThemes(appThemes);
    nylo.addToastNotification(getToastNotificationWidget);
    nylo.addValidationRules(validationRules);
    nylo.addModelDecoders(modelDecoders);
    nylo.addControllers(controllers);
    nylo.addApiDecoders(apiDecoders);
    nylo.addFormCasts(formCasts);
    nylo.useErrorStack();
    nylo.addFormStyle(formStyle);
    nylo.addAuthKey(Keys.auth);
    await nylo.syncKeys(Keys.syncedOnBoot);

    // Optional
    // nylo.showDateTimeInLogs(); // Show date time in logs
    // nylo.monitorAppUsage(); // Monitor the app usage

    return nylo;
  }

  @override
  afterBoot(Nylo nylo) async {}
}
```

Those are the main changes you need to make to upgrade your Nylo project from 5.x to 6.x.

We'd suggest creating a new project in v6 and then copying over your files to the new project.

Example: 
- Pages
- Providers
- Models
- Controllers
- Config files

This will ensure you have a clean project with all the new changes.

<div id="how-to-upgrade"></div>
<br>

## How to upgrade

You can check the changes in {{ $version }} by clicking the above link "**View changes**" and then implement all the changes into your {{ config('app.name') }} project.
