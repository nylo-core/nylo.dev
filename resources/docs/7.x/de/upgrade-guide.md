# Upgrade-Leitfaden

---

<a name="section-1"></a>
- [Was ist neu in v7](#whats-new "Was ist neu in v7")
- [Übersicht der Breaking Changes](#breaking-changes "Übersicht der Breaking Changes")
- [Empfohlener Migrations-Ansatz](#recommended-migration "Empfohlener Migrations-Ansatz")
- [Schnelle Migrations-Checkliste](#checklist "Schnelle Migrations-Checkliste")
- [Schritt-für-Schritt Migrationsleitfaden](#migration-guide "Schritt-für-Schritt Migrationsleitfaden")
  - [Schritt 1: Abhängigkeiten aktualisieren](#step-1-dependencies "Schritt 1: Abhängigkeiten aktualisieren")
  - [Schritt 2: Umgebungskonfiguration](#step-2-environment "Schritt 2: Umgebungskonfiguration")
  - [Schritt 3: main.dart aktualisieren](#step-3-main "Schritt 3: main.dart aktualisieren")
  - [Schritt 4: boot.dart aktualisieren](#step-4-boot "Schritt 4: boot.dart aktualisieren")
  - [Schritt 5: Konfigurationsdateien reorganisieren](#step-5-config "Schritt 5: Konfigurationsdateien reorganisieren")
  - [Schritt 6: AppProvider aktualisieren](#step-6-provider "Schritt 6: AppProvider aktualisieren")
  - [Schritt 7: Theme-Konfiguration aktualisieren](#step-7-theme "Schritt 7: Theme-Konfiguration aktualisieren")
  - [Schritt 10: Widgets migrieren](#step-10-widgets "Schritt 10: Widgets migrieren")
  - [Schritt 11: Asset-Pfade aktualisieren](#step-11-assets "Schritt 11: Asset-Pfade aktualisieren")
- [Entfernte Funktionen & Alternativen](#removed-features "Entfernte Funktionen & Alternativen")
- [Gelöschte Klassen Referenz](#deleted-classes "Gelöschte Klassen Referenz")
- [Widget-Migrations-Referenz](#widget-reference "Widget-Migrations-Referenz")
- [Fehlerbehebung](#troubleshooting "Fehlerbehebung")


<div id="whats-new"></div>

## Was ist neu in v7

{{ config('app.name') }} v7 ist ein Major Release mit bedeutenden Verbesserungen für die Entwicklererfahrung:

### Verschlüsselte Umgebungskonfiguration
- Umgebungsvariablen werden jetzt zur Build-Zeit XOR-verschlüsselt für mehr Sicherheit
- Neuer Befehl `metro make:key` generiert Ihren APP_KEY
- Neuer Befehl `metro make:env` generiert die verschlüsselte `env.g.dart`
- Unterstützung für `--dart-define` APP_KEY-Injektion für CI/CD-Pipelines

### Vereinfachter Boot-Prozess
- Neues `BootConfig`-Muster ersetzt separate setup/finished-Callbacks
- Saubereres `Nylo.init()` mit `env`-Parameter für verschlüsselte Umgebung
- App-Lifecycle-Hooks direkt in main.dart

### Neue nylo.configure() API
- Eine einzelne Methode konsolidiert die gesamte App-Konfiguration
- Sauberere Syntax ersetzt einzelne `nylo.add*()`-Aufrufe
- Separate `setup()`- und `boot()`-Lifecycle-Methoden in Providern

### NyPage für Pages
- `NyPage` ersetzt `NyState` für Page-Widgets (sauberere Syntax)
- `view()` ersetzt die `build()`-Methode
- `get init =>`-Getter ersetzt `init()`- und `boot()`-Methoden
- `NyState` ist weiterhin verfügbar für Nicht-Page Stateful Widgets

### LoadingStyle-System
- Neues `LoadingStyle`-Enum für konsistente Ladezustände
- Optionen: `LoadingStyle.normal()`, `LoadingStyle.skeletonizer()`, `LoadingStyle.none()`
- Benutzerdefinierte Lade-Widgets über `LoadingStyle.normal(child: ...)`

### RouteView Typsicheres Routing
- `static RouteView path` ersetzt `static const path`
- Typsichere Route-Definitionen mit Widget-Factory

### Multi-Theme-Unterstützung
- Registrieren Sie mehrere dunkle und helle Themes
- Theme-IDs werden im Code statt in der `.env`-Datei definiert
- Neu: `NyThemeType.dark` / `NyThemeType.light` für Theme-Klassifizierung
- Bevorzugtes Theme API: `NyTheme.setPreferredDark()`, `NyTheme.setPreferredLight()`
- Theme-Aufzählung: `NyTheme.lightThemes()`, `NyTheme.darkThemes()`, `NyTheme.all()`

### Neue Metro-Befehle
- `make:key` - APP_KEY für Verschlüsselung generieren
- `make:env` - Verschlüsselte Umgebungsdatei generieren
- `make:bottom_sheet_modal` - Bottom Sheet Modals erstellen
- `make:button` - Benutzerdefinierte Buttons erstellen

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">Alle Änderungen auf GitHub ansehen</a>

<div id="breaking-changes"></div>

## Übersicht der Breaking Changes

| Änderung | v6 | v7 |
|--------|-----|-----|
| App Root Widget | `LocalizedApp(child: Main(nylo))` | `Main(nylo)` (verwendet `NyApp.materialApp()`) |
| Page State Klasse | `NyState` | `NyPage` für Pages |
| View-Methode | `build()` | `view()` |
| Init-Methode | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| Route-Pfad | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Provider Boot | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| Konfiguration | Einzelne `nylo.add*()`-Aufrufe | Ein einzelner `nylo.configure()`-Aufruf |
| Theme-IDs | `.env`-Datei (`LIGHT_THEME_ID`, `DARK_THEME_ID`) | Code (`type: NyThemeType.dark`) |
| Lade-Widget | `useSkeletonizer` + `loading()` | `LoadingStyle`-Getter |
| Konfig-Speicherort | `lib/config/` | `lib/bootstrap/` (decoders, events, providers, theme) |
| Asset-Speicherort | `public/` | `assets/` |

<div id="recommended-migration"></div>

## Empfohlener Migrations-Ansatz

Für größere Projekte empfehlen wir, ein neues v7-Projekt zu erstellen und Dateien zu migrieren:

1. Neues v7-Projekt erstellen: `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. Kopieren Sie Ihre Pages, Controller, Models und Services
3. Aktualisieren Sie die Syntax wie oben gezeigt
4. Testen Sie gründlich

Dies stellt sicher, dass Sie alle aktuellen Boilerplate-Strukturen und Konfigurationen haben.

Wenn Sie einen Diff der Änderungen zwischen v6 und v7 sehen möchten, können Sie den Vergleich auf GitHub ansehen: <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## Schnelle Migrations-Checkliste

Verwenden Sie diese Checkliste, um Ihren Migrationsfortschritt zu verfolgen:

- [ ] `pubspec.yaml` aktualisieren (Dart >=3.10.7, Flutter >=3.38.4, nylo_framework: ^7.0.0)
- [ ] `flutter pub get` ausführen
- [ ] `metro make:key` ausführen, um APP_KEY zu generieren
- [ ] `metro make:env` ausführen, um verschlüsselte Umgebung zu generieren
- [ ] `main.dart` mit env-Parameter und BootConfig aktualisieren
- [ ] `Boot`-Klasse auf `BootConfig`-Muster umstellen
- [ ] Konfigurationsdateien von `lib/config/` nach `lib/bootstrap/` verschieben
- [ ] Neue Konfigurationsdateien erstellen (`lib/config/app.dart`, `lib/config/storage_keys.dart`, `lib/config/toast_notification.dart`)
- [ ] `AppProvider` auf `nylo.configure()` aktualisieren
- [ ] `LIGHT_THEME_ID` und `DARK_THEME_ID` aus `.env` entfernen
- [ ] `type: NyThemeType.dark` zu Dark-Theme-Konfigurationen hinzufügen
- [ ] `NyState` zu `NyPage` für alle Page-Widgets umbenennen
- [ ] `build()` zu `view()` in allen Pages ändern
- [ ] `init()/boot()` zu `get init =>` in allen Pages ändern
- [ ] `static const path` zu `static RouteView path` aktualisieren
- [ ] `router.route()` zu `router.add()` in Routes ändern
- [ ] Widgets umbenennen (NyListView -> CollectionView, etc.)
- [ ] Assets von `public/` nach `assets/` verschieben
- [ ] Asset-Pfade in `pubspec.yaml` aktualisieren
- [ ] Firebase-Imports entfernen (falls verwendet - Packages direkt hinzufügen)
- [ ] NyDevPanel-Verwendung entfernen (Flutter DevTools verwenden)
- [ ] `flutter pub get` ausführen und testen

<div id="migration-guide"></div>

## Schritt-für-Schritt Migrationsleitfaden

<div id="step-1-dependencies"></div>

### Schritt 1: Abhängigkeiten aktualisieren

Aktualisieren Sie Ihre `pubspec.yaml`:

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.38.4"

dependencies:
  nylo_framework: ^7.0.0
  # ... weitere Abhängigkeiten
```

Führen Sie `flutter pub get` aus, um die Packages zu aktualisieren.

<div id="step-2-environment"></div>

### Schritt 2: Umgebungskonfiguration

v7 erfordert verschlüsselte Umgebungsvariablen für verbesserte Sicherheit.

**1. APP_KEY generieren:**

``` bash
metro make:key
```

Dies fügt `APP_KEY` zu Ihrer `.env`-Datei hinzu.

**2. Verschlüsselte env.g.dart generieren:**

``` bash
metro make:env
```

Dies erstellt `lib/bootstrap/env.g.dart` mit Ihren verschlüsselten Umgebungsvariablen.

**3. Veraltete Theme-Variablen aus .env entfernen:**

``` bash
# Entfernen Sie diese Zeilen aus Ihrer .env-Datei:
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### Schritt 3: main.dart aktualisieren

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
      // Optional: App-Lifecycle-Hooks hinzufügen
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**Wichtige Änderungen:**
- Importieren Sie die generierte `env.g.dart`
- Übergeben Sie `Env.get` an den `env`-Parameter
- `Boot.nylo` ist jetzt `Boot.nylo()` (gibt `BootConfig` zurück)
- `setupFinished` wurde entfernt (wird innerhalb von `BootConfig` behandelt)
- Optionale `appLifecycle`-Hooks für App-Zustandsänderungen

<div id="step-4-boot"></div>

### Schritt 4: boot.dart aktualisieren

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

**Wichtige Änderungen:**
- Gibt `BootConfig` zurück statt `Future<Nylo>`
- `setup` und `finished` werden in einem einzelnen `BootConfig`-Objekt kombiniert
- `getEnv('SHOW_SPLASH_SCREEN')` -> `AppConfig.showSplashScreen`
- `bootApplication` -> `setupApplication`

<div id="step-5-config"></div>

### Schritt 5: Konfigurationsdateien reorganisieren

v7 reorganisiert Konfigurationsdateien für eine bessere Struktur:

| v6 Speicherort | v7 Speicherort | Aktion |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | Verschieben |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | Verschieben |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | Verschieben |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | Verschieben |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | Umbenennen & Refaktorisieren |
| (neu) | `lib/config/app.dart` | Erstellen |
| (neu) | `lib/config/toast_notification.dart` | Erstellen |

**lib/config/app.dart erstellen:**

Referenz: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // Der Name der Anwendung.
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // Die Version der Anwendung.
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // Weitere App-Konfiguration hier hinzufügen
}
```

**lib/config/storage_keys.dart erstellen:**

Referenz: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // Definieren Sie die Keys, die beim Boot synchronisiert werden sollen
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // dem Benutzer standardmäßig 10 Coins geben
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// Fügen Sie hier Ihre Storage Keys hinzu...
}
```

**lib/config/toast_notification.dart erstellen:**

Referenz: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // Passen Sie hier die Toast-Styles an
  };
}
```

<div id="step-6-provider"></div>

### Schritt 6: AppProvider aktualisieren

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

**Wichtige Änderungen:**
- `boot()` ist jetzt `setup()` für die initiale Konfiguration
- `boot()` wird jetzt für Post-Setup-Logik verwendet (früher `afterBoot`)
- Alle `nylo.add*()`-Aufrufe werden in einem einzelnen `nylo.configure()` konsolidiert
- Lokalisierung verwendet das `NyLocalizationConfig`-Objekt

<div id="step-7-theme"></div>

### Schritt 7: Theme-Konfiguration aktualisieren

**v6 (.env-Datei):**
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

**Wichtige Änderungen:**
- Entfernen Sie `LIGHT_THEME_ID` und `DARK_THEME_ID` aus `.env`
- Definieren Sie Theme-IDs direkt im Code
- Fügen Sie `type: NyThemeType.dark` zu allen Dark-Theme-Konfigurationen hinzu
- Light Themes verwenden standardmäßig `NyThemeType.light`

**Neue Theme-API-Methoden (v7):**
``` dart
// Bevorzugtes Theme setzen und merken
NyTheme.set(context, id: 'dark_theme', remember: true);

// Bevorzugte Themes für Systemverfolgung setzen
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// Bevorzugte Theme-IDs abrufen
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// Theme-Aufzählung
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// Gespeicherte Einstellungen löschen
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### Schritt 10: Widgets migrieren

#### NyListView -> CollectionView

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

// Mit Paginierung (Pull to Refresh):
CollectionView<User>.pullable(
  data: (page) async => await api.getUsers(page: page),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
)
```

#### NyFutureBuilder -> FutureWidget

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

#### NyTextField -> InputField

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

#### NyRichText -> StyledText

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

#### NyLanguageSwitcher -> LanguageSwitcher

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

### Schritt 11: Asset-Pfade aktualisieren

v7 ändert das Asset-Verzeichnis von `public/` zu `assets/`:

**1. Verschieben Sie Ihre Asset-Ordner:**
``` bash
# Verzeichnisse verschieben
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. pubspec.yaml aktualisieren:**

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

**3. Asset-Referenzen im Code aktualisieren:**

**v6:**
``` dart
Image.asset('public/images/logo.png')
```

**v7:**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### LocalizedApp Widget - Entfernt

Referenz: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Migration:** Verwenden Sie `Main(nylo)` direkt. Das `NyApp.materialApp()` behandelt die Lokalisierung intern.

**v6:**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7:**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## Gelöschte Klassen Referenz

| Gelöschte Klasse | Alternative |
|---------------|-------------|
| `NyTextStyle` | Verwenden Sie Flutters `TextStyle` direkt |
| `NyBaseApiService` | Verwenden Sie `DioApiService` |
| `BaseColorStyles` | Verwenden Sie `ThemeColor` |
| `LocalizedApp` | Verwenden Sie `Main(nylo)` direkt |
| `NyException` | Verwenden Sie Standard-Dart-Exceptions |
| `PushNotification` | Verwenden Sie `flutter_local_notifications` direkt |
| `PushNotificationAttachments` | Verwenden Sie `flutter_local_notifications` direkt |

<div id="widget-reference"></div>

## Widget-Migrations-Referenz

### Umbenannte Widgets

| v6 Widget | v7 Widget | Hinweise |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | Neue API mit `builder` statt `child` |
| `NyFutureBuilder` | `FutureWidget` | Vereinfachtes Async-Widget |
| `NyTextField` | `InputField` | Verwendet `FormValidator` |
| `NyLanguageSwitcher` | `LanguageSwitcher` | Gleiche API |
| `NyRichText` | `StyledText` | Gleiche API |
| `NyFader` | `FadeOverlay` | Gleiche API |

### Gelöschte Widgets (Kein direkter Ersatz)

| Gelöschtes Widget | Alternative |
|----------------|-------------|
| `NyPullToRefresh` | Verwenden Sie `CollectionView.pullable()` |

### Widget-Migrations-Beispiele

**NyPullToRefresh -> CollectionView.pullable():**

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

**NyFader -> FadeOverlay:**

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

## Fehlerbehebung

### "Env.get not found" oder "Env is not defined"

**Lösung:** Führen Sie die Befehle zur Umgebungsgenerierung aus:
``` bash
metro make:key
metro make:env
```
Importieren Sie dann die generierte Datei in `main.dart`:
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" oder "Dark theme not working"

**Lösung:** Stellen Sie sicher, dass Dark Themes `type: NyThemeType.dark` haben:
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // Diese Zeile hinzufügen
),
```

### "LocalizedApp not found"

Referenz: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Lösung:** `LocalizedApp` wurde entfernt. Ändern Sie:
``` dart
// Von:
runApp(LocalizedApp(child: Main(nylo)));

// Zu:
runApp(Main(nylo));
```

### "router.route is not defined"

**Lösung:** Verwenden Sie `router.add()` stattdessen:
``` dart
// Von:
router.route(HomePage.path, (context) => HomePage());

// Zu:
router.add(HomePage.path);
```

### "NyListView not found"

**Lösung:** `NyListView` ist jetzt `CollectionView`:
``` dart
// Von:
NyListView(...)

// Zu:
CollectionView<MyModel>(...)
```

### Assets werden nicht geladen (Bilder, Schriftarten)

**Lösung:** Aktualisieren Sie Asset-Pfade von `public/` zu `assets/`:
1. Dateien verschieben: `mv public/* assets/`
2. `pubspec.yaml`-Pfade aktualisieren
3. Code-Referenzen aktualisieren

### "init() must return a value of type Future"

**Lösung:** Wechseln Sie zur Getter-Syntax:
``` dart
// Von:
@override
init() async { ... }

// Zu:
@override
get init => () async { ... };
```

---

**Benötigen Sie Hilfe?** Schauen Sie in die [Nylo-Dokumentation](https://nylo.dev/docs/7.x) oder eröffnen Sie ein Issue auf [GitHub](https://github.com/nylo-core/nylo/issues).
