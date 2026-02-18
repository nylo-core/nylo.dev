# Upgrade Guide

---

<a name="section-1"></a>
- [Co nowego w v7](#whats-new "Co nowego w v7")
- [Przegląd przełomowych zmian](#breaking-changes "Przegląd przełomowych zmian")
- [Zalecane podejście do migracji](#recommended-migration "Zalecane podejście do migracji")
- [Szybka lista kontrolna migracji](#checklist "Szybka lista kontrolna migracji")
- [Przewodnik migracji krok po kroku](#migration-guide "Przewodnik migracji")
  - [Krok 1: Aktualizacja zależności](#step-1-dependencies "Aktualizacja zależności")
  - [Krok 2: Konfiguracja środowiska](#step-2-environment "Konfiguracja środowiska")
  - [Krok 3: Aktualizacja main.dart](#step-3-main "Aktualizacja main.dart")
  - [Krok 4: Aktualizacja boot.dart](#step-4-boot "Aktualizacja boot.dart")
  - [Krok 5: Reorganizacja plików konfiguracyjnych](#step-5-config "Reorganizacja plików konfiguracyjnych")
  - [Krok 6: Aktualizacja AppProvider](#step-6-provider "Aktualizacja AppProvider")
  - [Krok 7: Aktualizacja konfiguracji motywów](#step-7-theme "Aktualizacja konfiguracji motywów")
  - [Krok 10: Migracja widgetów](#step-10-widgets "Migracja widgetów")
  - [Krok 11: Aktualizacja ścieżek zasobów](#step-11-assets "Aktualizacja ścieżek zasobów")
- [Usunięte funkcje i alternatywy](#removed-features "Usunięte funkcje i alternatywy")
- [Referencja usuniętych klas](#deleted-classes "Referencja usuniętych klas")
- [Referencja migracji widgetów](#widget-reference "Referencja migracji widgetów")
- [Rozwiązywanie problemów](#troubleshooting "Rozwiązywanie problemów")


<div id="whats-new"></div>

## Co nowego w v7

{{ config('app.name') }} v7 to duże wydanie ze znacznymi usprawnieniami doświadczenia programisty:

### Szyfrowana konfiguracja środowiska
- Zmienne środowiskowe są teraz szyfrowane XOR w czasie kompilacji dla bezpieczeństwa
- Nowe polecenie `metro make:key` generuje APP_KEY
- Nowe polecenie `metro make:env` generuje zaszyfrowany `env.g.dart`
- Obsługa wstrzykiwania APP_KEY przez `--dart-define` dla pipeline'ów CI/CD

### Uproszczony proces uruchamiania
- Nowy wzorzec `BootConfig` zastępuje oddzielne callbacki setup/finished
- Czystszy `Nylo.init()` z parametrem `env` dla zaszyfrowanego środowiska
- Hooki cyklu życia aplikacji bezpośrednio w main.dart

### Nowe API nylo.configure()
- Pojedyncza metoda konsoliduje całą konfigurację aplikacji
- Czystsza składnia zastępuje indywidualne wywołania `nylo.add*()`
- Oddzielne metody cyklu życia `setup()` i `boot()` w providerach

### NyPage dla stron
- `NyPage` zastępuje `NyState` dla widgetów stron (czystsza składnia)
- `view()` zastępuje metodę `build()`
- Getter `get init =>` zastępuje metody `init()` i `boot()`
- `NyState` nadal dostępny dla widgetów stanowych niebędących stronami

### System LoadingStyle
- Nowy enum `LoadingStyle` dla spójnych stanów ładowania
- Opcje: `LoadingStyle.normal()`, `LoadingStyle.skeletonizer()`, `LoadingStyle.none()`
- Niestandardowe widgety ładowania przez `LoadingStyle.normal(child: ...)`

### Typowane trasowanie RouteView
- `static RouteView path` zastępuje `static const path`
- Typowane definicje tras z fabryką widgetów

### Obsługa wielu motywów
- Rejestracja wielu ciemnych i jasnych motywów
- ID motywów definiowane w kodzie zamiast w pliku `.env`
- Nowe `NyThemeType.dark` / `NyThemeType.light` do klasyfikacji motywów
- API preferowanego motywu: `NyTheme.setPreferredDark()`, `NyTheme.setPreferredLight()`
- Enumeracja motywów: `NyTheme.lightThemes()`, `NyTheme.darkThemes()`, `NyTheme.all()`

### Nowe polecenia Metro
- `make:key` - Generowanie APP_KEY do szyfrowania
- `make:env` - Generowanie zaszyfrowanego pliku środowiska
- `make:bottom_sheet_modal` - Tworzenie modali dolnego arkusza
- `make:button` - Tworzenie niestandardowych przycisków

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">Zobacz wszystkie zmiany na GitHub</a>

<div id="breaking-changes"></div>

## Przegląd przełomowych zmian

| Zmiana | v6 | v7 |
|--------|-----|-----|
| Główny widget aplikacji | `LocalizedApp(child: Main(nylo))` | `Main(nylo)` (używa `NyApp.materialApp()`) |
| Klasa stanu strony | `NyState` | `NyPage` dla stron |
| Metoda widoku | `build()` | `view()` |
| Metoda init | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| Ścieżka trasy | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Boot providera | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| Konfiguracja | Indywidualne wywołania `nylo.add*()` | Pojedyncze wywołanie `nylo.configure()` |
| ID motywów | Plik `.env` (`LIGHT_THEME_ID`, `DARK_THEME_ID`) | Kod (`type: NyThemeType.dark`) |
| Widget ładowania | `useSkeletonizer` + `loading()` | Getter `LoadingStyle` |
| Lokalizacja konfiguracji | `lib/config/` | `lib/bootstrap/` (decoders, events, providers, theme) |
| Lokalizacja zasobów | `public/` | `assets/` |

<div id="recommended-migration"></div>

## Zalecane podejście do migracji

Dla większych projektów zalecamy utworzenie świeżego projektu v7 i migrację plików:

1. Utwórz nowy projekt v7: `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. Skopiuj swoje strony, kontrolery, modele i serwisy
3. Zaktualizuj składnię jak pokazano powyżej
4. Dokładnie przetestuj

To zapewnia posiadanie najnowszej struktury szablonu i konfiguracji.

Jeśli chcesz zobaczyć diff zmian między v6 a v7, możesz wyświetlić porównanie na GitHub: <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## Szybka lista kontrolna migracji

Użyj tej listy kontrolnej do śledzenia postępu migracji:

- [ ] Zaktualizuj `pubspec.yaml` (Dart >=3.10.7, Flutter >=3.24.0, nylo_framework: ^7.0.0)
- [ ] Uruchom `flutter pub get`
- [ ] Uruchom `metro make:key` aby wygenerować APP_KEY
- [ ] Uruchom `metro make:env` aby wygenerować zaszyfrowane środowisko
- [ ] Zaktualizuj `main.dart` z parametrem env i BootConfig
- [ ] Przekonwertuj klasę `Boot` na wzorzec `BootConfig`
- [ ] Przenieś pliki konfiguracyjne z `lib/config/` do `lib/bootstrap/`
- [ ] Utwórz nowe pliki konfiguracyjne (`lib/config/app.dart`, `lib/config/storage_keys.dart`, `lib/config/toast_notification.dart`)
- [ ] Zaktualizuj `AppProvider` aby używał `nylo.configure()`
- [ ] Usuń `LIGHT_THEME_ID` i `DARK_THEME_ID` z `.env`
- [ ] Dodaj `type: NyThemeType.dark` do konfiguracji ciemnych motywów
- [ ] Zmień nazwę `NyState` na `NyPage` dla wszystkich widgetów stron
- [ ] Zmień `build()` na `view()` we wszystkich stronach
- [ ] Zmień `init()/boot()` na `get init =>` we wszystkich stronach
- [ ] Zaktualizuj `static const path` na `static RouteView path`
- [ ] Zmień `router.route()` na `router.add()` w trasach
- [ ] Zmień nazwy widgetów (NyListView na CollectionView, itp.)
- [ ] Przenieś zasoby z `public/` do `assets/`
- [ ] Zaktualizuj ścieżki zasobów w `pubspec.yaml`
- [ ] Usuń importy Firebase (jeśli używasz - dodaj pakiety bezpośrednio)
- [ ] Usuń użycie NyDevPanel (użyj Flutter DevTools)
- [ ] Uruchom `flutter pub get` i przetestuj

<div id="migration-guide"></div>

## Przewodnik migracji krok po kroku

<div id="step-1-dependencies"></div>

### Krok 1: Aktualizacja zależności

Zaktualizuj plik `pubspec.yaml`:

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... other dependencies
```

Uruchom `flutter pub get` aby zaktualizować pakiety.

<div id="step-2-environment"></div>

### Krok 2: Konfiguracja środowiska

v7 wymaga zaszyfrowanych zmiennych środowiskowych dla zwiększonego bezpieczeństwa.

**1. Wygeneruj APP_KEY:**

``` bash
metro make:key
```

To doda `APP_KEY` do Twojego pliku `.env`.

**2. Wygeneruj zaszyfrowany env.g.dart:**

``` bash
metro make:env
```

To utworzy `lib/bootstrap/env.g.dart` zawierający zaszyfrowane zmienne środowiskowe.

**3. Usuń przestarzałe zmienne motywów z .env:**

``` bash
# Remove these lines from your .env file:
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### Krok 3: Aktualizacja main.dart

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

**Kluczowe zmiany:**
- Import wygenerowanego `env.g.dart`
- Przekazanie `Env.get` do parametru `env`
- `Boot.nylo` to teraz `Boot.nylo()` (zwraca `BootConfig`)
- `setupFinished` usunięty (obsługiwany wewnątrz `BootConfig`)
- Opcjonalne hooki `appLifecycle` dla zmian stanu aplikacji

<div id="step-4-boot"></div>

### Krok 4: Aktualizacja boot.dart

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

**Kluczowe zmiany:**
- Zwraca `BootConfig` zamiast `Future<Nylo>`
- `setup` i `finished` połączone w pojedynczy obiekt `BootConfig`
- `getEnv('SHOW_SPLASH_SCREEN')` zmieniony na `AppConfig.showSplashScreen`
- `bootApplication` zmieniony na `setupApplication`

<div id="step-5-config"></div>

### Krok 5: Reorganizacja plików konfiguracyjnych

v7 reorganizuje pliki konfiguracyjne dla lepszej struktury:

| Lokalizacja v6 | Lokalizacja v7 | Akcja |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | Przenieś |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | Przenieś |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | Przenieś |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | Przenieś |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | Zmień nazwę i zrefaktoryzuj |
| (nowy) | `lib/config/app.dart` | Utwórz |
| (nowy) | `lib/config/toast_notification.dart` | Utwórz |

**Utwórz lib/config/app.dart:**

Referencja: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // The name of the application.
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // The version of the application.
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // Add other app configuration here
}
```

**Utwórz lib/config/storage_keys.dart:**

Referencja: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

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

**Utwórz lib/config/toast_notification.dart:**

Referencja: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // Customize toast styles here
  };
}
```

<div id="step-6-provider"></div>

### Krok 6: Aktualizacja AppProvider

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

**Kluczowe zmiany:**
- `boot()` to teraz `setup()` dla początkowej konfiguracji
- `boot()` jest teraz używany dla logiki po konfiguracji (wcześniej `afterBoot`)
- Wszystkie wywołania `nylo.add*()` skonsolidowane w pojedyncze `nylo.configure()`
- Lokalizacja używa obiektu `NyLocalizationConfig`

<div id="step-7-theme"></div>

### Krok 7: Aktualizacja konfiguracji motywów

**v6 (plik .env):**
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

**Kluczowe zmiany:**
- Usuń `LIGHT_THEME_ID` i `DARK_THEME_ID` z `.env`
- Definiuj ID motywów bezpośrednio w kodzie
- Dodaj `type: NyThemeType.dark` do wszystkich konfiguracji ciemnych motywów
- Jasne motywy domyślnie używają `NyThemeType.light`

**Nowe metody API motywów (v7):**
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

### Krok 10: Migracja widgetów

#### NyListView na CollectionView

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

#### NyFutureBuilder na FutureWidget

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

#### NyTextField na InputField

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

#### NyRichText na StyledText

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

#### NyLanguageSwitcher na LanguageSwitcher

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

### Krok 11: Aktualizacja ścieżek zasobów

v7 zmienia katalog zasobów z `public/` na `assets/`:

**1. Przenieś foldery zasobów:**
``` bash
# Move directories
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. Zaktualizuj pubspec.yaml:**

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

**3. Zaktualizuj odwołania do zasobów w kodzie:**

**v6:**
``` dart
Image.asset('public/images/logo.png')
```

**v7:**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### Widget LocalizedApp - Usunięty

Referencja: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Migracja:** Użyj `Main(nylo)` bezpośrednio. `NyApp.materialApp()` obsługuje lokalizację wewnętrznie.

**v6:**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7:**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## Referencja usuniętych klas

| Usunięta klasa | Alternatywa |
|---------------|-------------|
| `NyTextStyle` | Użyj bezpośrednio `TextStyle` z Flutter |
| `NyBaseApiService` | Użyj `DioApiService` |
| `BaseColorStyles` | Użyj `ThemeColor` |
| `LocalizedApp` | Użyj `Main(nylo)` bezpośrednio |
| `NyException` | Użyj standardowych wyjątków Dart |
| `PushNotification` | Użyj bezpośrednio `flutter_local_notifications` |
| `PushNotificationAttachments` | Użyj bezpośrednio `flutter_local_notifications` |

<div id="widget-reference"></div>

## Referencja migracji widgetów

### Przemianowane widgety

| Widget v6 | Widget v7 | Uwagi |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | Nowe API z `builder` zamiast `child` |
| `NyFutureBuilder` | `FutureWidget` | Uproszczony widget asynchroniczny |
| `NyTextField` | `InputField` | Używa `FormValidator` |
| `NyLanguageSwitcher` | `LanguageSwitcher` | To samo API |
| `NyRichText` | `StyledText` | To samo API |
| `NyFader` | `FadeOverlay` | To samo API |

### Usunięte widgety (brak bezpośredniego zamiennika)

| Usunięty widget | Alternatywa |
|----------------|-------------|
| `NyPullToRefresh` | Użyj `CollectionView.pullable()` |

### Przykłady migracji widgetów

**NyPullToRefresh na CollectionView.pullable():**

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

**NyFader na AnimatedOpacity:**

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

## Rozwiązywanie problemów

### "Env.get not found" lub "Env is not defined"

**Rozwiązanie:** Uruchom polecenia generowania środowiska:
``` bash
metro make:key
metro make:env
```
Następnie zaimportuj wygenerowany plik w `main.dart`:
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" lub "Dark theme not working"

**Rozwiązanie:** Upewnij się, że ciemne motywy mają `type: NyThemeType.dark`:
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

Referencja: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Rozwiązanie:** `LocalizedApp` został usunięty. Zmień:
``` dart
// From:
runApp(LocalizedApp(child: Main(nylo)));

// To:
runApp(Main(nylo));
```

### "router.route is not defined"

**Rozwiązanie:** Użyj `router.add()` zamiast:
``` dart
// From:
router.route(HomePage.path, (context) => HomePage());

// To:
router.add(HomePage.path);
```

### "NyListView not found"

**Rozwiązanie:** `NyListView` to teraz `CollectionView`:
``` dart
// From:
NyListView(...)

// To:
CollectionView<MyModel>(...)
```

### Zasoby nie ładują się (obrazy, czcionki)

**Rozwiązanie:** Zaktualizuj ścieżki zasobów z `public/` na `assets/`:
1. Przenieś pliki: `mv public/* assets/`
2. Zaktualizuj ścieżki w `pubspec.yaml`
3. Zaktualizuj odwołania w kodzie

### "init() must return a value of type Future"

**Rozwiązanie:** Zmień na składnię gettera:
``` dart
// From:
@override
init() async { ... }

// To:
@override
get init => () async { ... };
```

---

**Potrzebujesz pomocy?** Sprawdź [dokumentację Nylo](https://nylo.dev/docs/7.x) lub otwórz zgłoszenie na [GitHub](https://github.com/nylo-core/nylo/issues).
