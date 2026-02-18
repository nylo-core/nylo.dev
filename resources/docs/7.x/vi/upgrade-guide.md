# Upgrade Guide

---

<a name="section-1"></a>
- [What's New in v7](#whats-new "What's New in v7")
- [Breaking Changes Overview](#breaking-changes "Breaking Changes Overview")
- [Recommended Migration Approach](#recommended-migration "Recommended Migration Approach")
- [Quick Migration Checklist](#checklist "Quick Migration Checklist")
- [Step-by-Step Migration Guide](#migration-guide "Migration Guide")
  - [Step 1: Update Dependencies](#step-1-dependencies "Update Dependencies")
  - [Step 2: Environment Configuration](#step-2-environment "Environment Configuration")
  - [Step 3: Update main.dart](#step-3-main "Update main.dart")
  - [Step 4: Update boot.dart](#step-4-boot "Update boot.dart")
  - [Step 5: Reorganize Configuration Files](#step-5-config "Reorganize Configuration Files")
  - [Step 6: Update AppProvider](#step-6-provider "Update AppProvider")
  - [Step 7: Update Theme Configuration](#step-7-theme "Update Theme Configuration")
  - [Step 10: Migrate Widgets](#step-10-widgets "Migrate Widgets")
  - [Step 11: Update Asset Paths](#step-11-assets "Update Asset Paths")
- [Removed Features & Alternatives](#removed-features "Removed Features & Alternatives")
- [Deleted Classes Reference](#deleted-classes "Deleted Classes Reference")
- [Widget Migration Reference](#widget-reference "Widget Migration Reference")
- [Troubleshooting](#troubleshooting "Troubleshooting")


<div id="whats-new"></div>

## What's New in v7

{{ config('app.name') }} v7 is a major release with significant improvements to the developer experience:

### Encrypted Environment Configuration
- Environment variables are now XOR-encrypted at build time for security
- New `metro make:key` generates your APP_KEY
- New `metro make:env` generates encrypted `env.g.dart`
- Support for `--dart-define` APP_KEY injection for CI/CD pipelines

### Simplified Boot Process
- New `BootConfig` pattern replaces separate setup/finished callbacks
- Cleaner `Nylo.init()` with `env` parameter for encrypted environment
- App lifecycle hooks directly in main.dart

### New nylo.configure() API
- Single method consolidates all app configuration
- Cleaner syntax replaces individual `nylo.add*()` calls
- Separate `setup()` and `boot()` lifecycle methods in providers

### NyPage for Pages
- `NyPage` replaces `NyState` for page widgets (cleaner syntax)
- `view()` replaces `build()` method
- `get init =>` getter replaces `init()` and `boot()` methods
- `NyState` is still available for non-page stateful widgets

### LoadingStyle System
- New `LoadingStyle` enum for consistent loading states
- Options: `LoadingStyle.normal()`, `LoadingStyle.skeletonizer()`, `LoadingStyle.none()`
- Custom loading widgets via `LoadingStyle.normal(child: ...)`

### RouteView Type-Safe Routing
- `static RouteView path` replaces `static const path`
- Type-safe route definitions with widget factory

### Multi-Theme Support
- Register multiple dark and light themes
- Theme IDs defined in code instead of `.env` file
- New `NyThemeType.dark` / `NyThemeType.light` for theme classification
- Preferred theme API: `NyTheme.setPreferredDark()`, `NyTheme.setPreferredLight()`
- Theme enumeration: `NyTheme.lightThemes()`, `NyTheme.darkThemes()`, `NyTheme.all()`

### New Metro Commands
- `make:key` - Generate APP_KEY for encryption
- `make:env` - Generate encrypted environment file
- `make:bottom_sheet_modal` - Create bottom sheet modals
- `make:button` - Create custom buttons

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">View all changes on GitHub</a>

<div id="breaking-changes"></div>

## Breaking Changes Overview

| Change | v6 | v7 |
|--------|-----|-----|
| App Root Widget | `LocalizedApp(child: Main(nylo))` | `Main(nylo)` (uses `NyApp.materialApp()`) |
| Page State Class | `NyState` | `NyPage` for pages |
| View Method | `build()` | `view()` |
| Init Method | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| Route Path | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Provider Boot | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| Configuration | Individual `nylo.add*()` calls | Single `nylo.configure()` call |
| Theme IDs | `.env` file (`LIGHT_THEME_ID`, `DARK_THEME_ID`) | Code (`type: NyThemeType.dark`) |
| Loading Widget | `useSkeletonizer` + `loading()` | `LoadingStyle` getter |
| Config Location | `lib/config/` | `lib/bootstrap/` (decoders, events, providers, theme) |
| Asset Location | `public/` | `assets/` |

<div id="recommended-migration"></div>

## Recommended Migration Approach

For larger projects, we recommend creating a fresh v7 project and migrating files:

1. Create new v7 project: `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. Copy your pages, controllers, models, and services
3. Update syntax as shown above
4. Test thoroughly

This ensures you have all the latest boilerplate structure and configurations.

If you are interested in seeing a diff of the changes between v6 and v7, you can view the comparison on GitHub: <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## Quick Migration Checklist

Use this checklist to track your migration progress:

- [ ] Update `pubspec.yaml` (Dart >=3.10.7, Flutter >=3.24.0, nylo_framework: ^7.0.0)
- [ ] Run `flutter pub get`
- [ ] Run `metro make:key` to generate APP_KEY
- [ ] Run `metro make:env` to generate encrypted environment
- [ ] Update `main.dart` with env parameter and BootConfig
- [ ] Convert `Boot` class to use `BootConfig` pattern
- [ ] Move config files from `lib/config/` to `lib/bootstrap/`
- [ ] Create new config files (`lib/config/app.dart`, `lib/config/storage_keys.dart`, `lib/config/toast_notification.dart`)
- [ ] Update `AppProvider` to use `nylo.configure()`
- [ ] Remove `LIGHT_THEME_ID` and `DARK_THEME_ID` from `.env`
- [ ] Add `type: NyThemeType.dark` to dark theme configurations
- [ ] Rename `NyState` to `NyPage` for all page widgets
- [ ] Change `build()` to `view()` in all pages
- [ ] Change `init()/boot()` to `get init =>` in all pages
- [ ] Update `static const path` to `static RouteView path`
- [ ] Change `router.route()` to `router.add()` in routes
- [ ] Rename widgets (NyListView → CollectionView, etc.)
- [ ] Move assets from `public/` to `assets/`
- [ ] Update `pubspec.yaml` asset paths
- [ ] Remove Firebase imports (if using - add packages directly)
- [ ] Remove NyDevPanel usage (use Flutter DevTools)
- [ ] Run `flutter pub get` and test

<div id="migration-guide"></div>

## Step-by-Step Migration Guide

<div id="step-1-dependencies"></div>

### Step 1: Update Dependencies

Update your `pubspec.yaml`:

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... other dependencies
```

Run `flutter pub get` to update packages.

<div id="step-2-environment"></div>

### Step 2: Environment Configuration

v7 requires encrypted environment variables for improved security.

**1. Generate APP_KEY:**

``` bash
metro make:key
```

This adds `APP_KEY` to your `.env` file.

**2. Generate encrypted env.g.dart:**

``` bash
metro make:env
```

This creates `lib/bootstrap/env.g.dart` containing your encrypted environment variables.

**3. Remove deprecated theme variables from .env:**

``` bash
# Remove these lines from your .env file:
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### Step 3: Update main.dart

**v6:**
``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,
  );
}
```

**v7:**
``` dart
import '/bootstrap/env.g.dart';
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    env: Env.get,
    setup: Boot.nylo(),
    appLifecycle: {
      // Optional: Add app lifecycle hooks
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**Key Changes:**
- Import the generated `env.g.dart`
- Pass `Env.get` to the `env` parameter
- `Boot.nylo` is now `Boot.nylo()` (returns `BootConfig`)
- `setupFinished` is removed (handled within `BootConfig`)
- Optional `appLifecycle` hooks for app state changes

<div id="step-4-boot"></div>

### Step 4: Update boot.dart

**v6:**
``` dart
class Boot {
  static Future<Nylo> nylo() async {
    WidgetsFlutterBinding.ensureInitialized();

    if (getEnv('SHOW_SPLASH_SCREEN', defaultValue: false)) {
      runApp(SplashScreen.app());
    }

    await _setup();
    return await bootApplication(providers);
  }

  static Future<void> finished(Nylo nylo) async {
    await bootFinished(nylo, providers);
    runApp(Main(nylo));
  }
}
```

**v7:**
``` dart
class Boot {
  static BootConfig nylo() => BootConfig(
        setup: () async {
          WidgetsFlutterBinding.ensureInitialized();

          if (AppConfig.showSplashScreen) {
            runApp(SplashScreen.app());
          }

          await _init();
          return await setupApplication(providers);
        },
        boot: (Nylo nylo) async {
          await bootFinished(nylo, providers);
          runApp(Main(nylo));
        },
      );
}
```

**Key Changes:**
- Returns `BootConfig` instead of `Future<Nylo>`
- `setup` and `finished` combined into single `BootConfig` object
- `getEnv('SHOW_SPLASH_SCREEN')` → `AppConfig.showSplashScreen`
- `bootApplication` → `setupApplication`

<div id="step-5-config"></div>

### Step 5: Reorganize Configuration Files

v7 reorganizes configuration files for better structure:

| v6 Location | v7 Location | Action |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | Move |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | Move |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | Move |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | Move |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | Rename & Refactor |
| (new) | `lib/config/app.dart` | Create |
| (new) | `lib/config/toast_notification.dart` | Create |

**Create lib/config/app.dart:**

Reference: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // The name of the application.
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // The version of the application.
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');
  
  // Add other app configuration here
}
```

**Create lib/config/storage_keys.dart:**

Reference: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // Define the keys you want to be synced on boot
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // give the user 10 coins by default
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// Add your storage keys here...
}
```

**Create lib/config/toast_notification.dart:**

Reference: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // Customize toast styles here
  };
}
```

<div id="step-6-provider"></div>

### Step 6: Update AppProvider

**v6:**
``` dart
class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    await NyLocalization.instance.init(
      localeType: localeType,
      languageCode: languageCode,
      assetsDirectory: assetsDirectory,
    );

    nylo.addLoader(loader);
    nylo.addLogo(logo);
    nylo.addThemes(appThemes);
    nylo.addToastNotification(getToastNotificationWidget);
    nylo.addValidationRules(validationRules);
    nylo.addModelDecoders(modelDecoders);
    nylo.addControllers(controllers);
    nylo.addApiDecoders(apiDecoders);
    nylo.useErrorStack();
    nylo.addAuthKey(Keys.auth);
    await nylo.syncKeys(Keys.syncedOnBoot);

    return nylo;
  }

  @override
  afterBoot(Nylo nylo) async {}
}
```

**v7:**
``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      localization: NyLocalizationConfig(
        languageCode: LocalizationConfig.languageCode,
        localeType: LocalizationConfig.localeType,
        assetsDirectory: LocalizationConfig.assetsDirectory,
      ),
      loader: DesignConfig.loader,
      logo: DesignConfig.logo,
      themes: appThemes,
      initialThemeId: 'light_theme',
      toastNotifications: ToastNotificationConfig.styles,
      modelDecoders: modelDecoders,
      controllers: controllers,
      apiDecoders: apiDecoders,
      authKey: StorageKeysConfig.auth,
      syncKeys: StorageKeysConfig.syncedOnBoot,
      useErrorStack: true,
    );

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

**Key Changes:**
- `boot()` is now `setup()` for initial configuration
- `boot()` is now used for post-setup logic (previously `afterBoot`)
- All `nylo.add*()` calls consolidated into single `nylo.configure()`
- Localization uses `NyLocalizationConfig` object

<div id="step-7-theme"></div>

### Step 7: Update Theme Configuration

**v6 (.env file):**
``` bash
LIGHT_THEME_ID=default_light_theme
DARK_THEME_ID=default_dark_theme
```

**v6 (theme.dart):**
``` dart
final List<BaseThemeConfig> appThemes = [
  BaseThemeConfig(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light Theme",
    theme: lightTheme(),
    colors: LightThemeColors(),
  ),
  BaseThemeConfig(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark Theme",
    theme: darkTheme(),
    colors: DarkThemeColors(),
  ),
];
```

**v7 (theme.dart):**
``` dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),
];
```

**Key Changes:**
- Remove `LIGHT_THEME_ID` and `DARK_THEME_ID` from `.env`
- Define theme IDs directly in code
- Add `type: NyThemeType.dark` to all dark theme configurations
- Light themes default to `NyThemeType.light`

**New Theme API Methods (v7):**
``` dart
// Set and remember preferred theme
NyTheme.set(context, id: 'dark_theme', remember: true);

// Set preferred themes for system following
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// Get preferred theme IDs
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// Theme enumeration
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// Clear saved preferences
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### Step 10: Migrate Widgets

#### NyListView → CollectionView

**v6:**
``` dart
NyListView(
  child: (context, data) {
    return ListTile(title: Text(data.name));
  },
  data: () async => await api.getUsers(),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
CollectionView<User>(
  data: () async => await api.getUsers(),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
  loadingStyle: LoadingStyle.normal(),
)

// With pagination (pull to refresh):
CollectionView<User>.pullable(
  data: (page) async => await api.getUsers(page: page),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
)
```

#### NyFutureBuilder → FutureWidget

**v6:**
``` dart
NyFutureBuilder(
  future: fetchData(),
  child: (context, data) => Text(data),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
FutureWidget<String>(
  future: fetchData(),
  child: (context, data) => Text(data ?? ''),
  loadingStyle: LoadingStyle.normal(),
)
```

#### NyTextField → InputField

**v6:**
``` dart
NyTextField(
  controller: _controller,
  validationRules: "not_empty|email",
)
```

**v7:**
``` dart
InputField(
  controller: _controller,
  formValidator: FormValidator
                  .notEmpty()
                  .email(),
),
```

#### NyRichText → StyledText

**v6:**
``` dart
NyRichText(children: [
	Text("Hello", style: TextStyle(color: Colors.yellow)),
	Text(" WORLD ", style: TextStyle(color: Colors.blue)),
	Text("!", style: TextStyle(color: Colors.red)),
]),
```

**v7:**
``` dart
StyledText.template(
  "@{{Hello}} @{{WORLD}}@{{!}}",
  styles: {
    "Hello": TextStyle(color: Colors.yellow),
    "WORLD": TextStyle(color: Colors.blue),
    "!": TextStyle(color: Colors.red),
  },
)
```

#### NyLanguageSwitcher → LanguageSwitcher

**v6:**
``` dart
NyLanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

**v7:**
``` dart
LanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

<div id="step-11-assets"></div>

### Step 11: Update Asset Paths

v7 changes the asset directory from `public/` to `assets/`:

**1. Move your asset folders:**
``` bash
# Move directories
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. Update pubspec.yaml:**

**v6:**
``` yaml
flutter:
  assets:
    - public/fonts/
    - public/images/
    - public/app_icon/
```

**v7:**
``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
```

**3. Update any asset references in code:**

**v6:**
``` dart
Image.asset('public/images/logo.png')
```

**v7:**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### LocalizedApp Widget - Removed

Reference: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Migration:** Use `Main(nylo)` directly. The `NyApp.materialApp()` handles localization internally.

**v6:**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7:**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## Deleted Classes Reference

| Deleted Class | Alternative |
|---------------|-------------|
| `NyTextStyle` | Use Flutter's `TextStyle` directly |
| `NyBaseApiService` | Use `DioApiService` |
| `BaseColorStyles` | Use `ThemeColor` |
| `LocalizedApp` | Use `Main(nylo)` directly |
| `NyException` | Use standard Dart exceptions |
| `PushNotification` | Use `flutter_local_notifications` directly |
| `PushNotificationAttachments` | Use `flutter_local_notifications` directly |

<div id="widget-reference"></div>

## Widget Migration Reference

### Renamed Widgets

| v6 Widget | v7 Widget | Notes |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | New API with `builder` instead of `child` |
| `NyFutureBuilder` | `FutureWidget` | Simplified async widget |
| `NyTextField` | `InputField` | Uses `FormValidator` |
| `NyLanguageSwitcher` | `LanguageSwitcher` | Same API |
| `NyRichText` | `StyledText` | Same API |
| `NyFader` | `FadeOverlay` | Same API |

### Deleted Widgets (No Direct Replacement)

| Deleted Widget | Alternative |
|----------------|-------------|
| `NyPullToRefresh` | Use `CollectionView.pullable()` |

### Widget Migration Examples

**NyPullToRefresh → CollectionView.pullable():**

**v6:**
``` dart
NyPullToRefresh(
  child: (context, data) => ListTile(title: Text(data.name)),
  data: (page) async => await fetchData(page),
)
```

**v7:**
``` dart
CollectionView<MyModel>.pullable(
  data: (page) async => await fetchData(page),
  builder: (context, item) => ListTile(title: Text(item.data.name)),
)
```

**NyFader → AnimatedOpacity:**

**v6:**
``` dart
NyFader(
  child: MyWidget(),
)
```

**v7:**
``` dart
FadeOverlay.bottom(
  child: MyWidget(),
);
```

<div id="troubleshooting"></div>

## Troubleshooting

### "Env.get not found" or "Env is not defined"

**Solution:** Run the environment generation commands:
``` bash
metro make:key
metro make:env
```
Then import the generated file in `main.dart`:
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" or "Dark theme not working"

**Solution:** Ensure dark themes have `type: NyThemeType.dark`:
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // Add this line
),
```

### "LocalizedApp not found"

Reference: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Solution:** `LocalizedApp` has been removed. Change:
``` dart
// From:
runApp(LocalizedApp(child: Main(nylo)));

// To:
runApp(Main(nylo));
```

### "router.route is not defined"

**Solution:** Use `router.add()` instead:
``` dart
// From:
router.route(HomePage.path, (context) => HomePage());

// To:
router.add(HomePage.path);
```

### "NyListView not found"

**Solution:** `NyListView` is now `CollectionView`:
``` dart
// From:
NyListView(...)

// To:
CollectionView<MyModel>(...)
```

### Assets not loading (images, fonts)

**Solution:** Update asset paths from `public/` to `assets/`:
1. Move files: `mv public/* assets/`
2. Update `pubspec.yaml` paths
3. Update code references

### "init() must return a value of type Future"

**Solution:** Change to the getter syntax:
``` dart
// From:
@override
init() async { ... }

// To:
@override
get init => () async { ... };
```

---

**Need help?** Check the [Nylo Documentation](https://nylo.dev/docs/7.x) or open an issue on [GitHub](https://github.com/nylo-core/nylo/issues).
