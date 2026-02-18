# Guida all'Aggiornamento

---

<a name="section-1"></a>
- [Novita nella v7](#whats-new "Novita nella v7")
- [Panoramica delle Modifiche Incompatibili](#breaking-changes "Panoramica delle Modifiche Incompatibili")
- [Approccio Raccomandato per la Migrazione](#recommended-migration "Approccio Raccomandato per la Migrazione")
- [Checklist Rapida per la Migrazione](#checklist "Checklist Rapida per la Migrazione")
- [Guida alla Migrazione Passo per Passo](#migration-guide "Guida alla Migrazione Passo per Passo")
  - [Passo 1: Aggiornare le Dipendenze](#step-1-dependencies "Passo 1: Aggiornare le Dipendenze")
  - [Passo 2: Configurazione dell'Ambiente](#step-2-environment "Passo 2: Configurazione dell'Ambiente")
  - [Passo 3: Aggiornare main.dart](#step-3-main "Passo 3: Aggiornare main.dart")
  - [Passo 4: Aggiornare boot.dart](#step-4-boot "Passo 4: Aggiornare boot.dart")
  - [Passo 5: Riorganizzare i File di Configurazione](#step-5-config "Passo 5: Riorganizzare i File di Configurazione")
  - [Passo 6: Aggiornare AppProvider](#step-6-provider "Passo 6: Aggiornare AppProvider")
  - [Passo 7: Aggiornare la Configurazione del Theme](#step-7-theme "Passo 7: Aggiornare la Configurazione del Theme")
  - [Passo 10: Migrare i Widget](#step-10-widgets "Passo 10: Migrare i Widget")
  - [Passo 11: Aggiornare i Percorsi degli Asset](#step-11-assets "Passo 11: Aggiornare i Percorsi degli Asset")
- [Funzionalita Rimosse e Alternative](#removed-features "Funzionalita Rimosse e Alternative")
- [Riferimento delle Classi Eliminate](#deleted-classes "Riferimento delle Classi Eliminate")
- [Riferimento per la Migrazione dei Widget](#widget-reference "Riferimento per la Migrazione dei Widget")
- [Risoluzione dei Problemi](#troubleshooting "Risoluzione dei Problemi")


<div id="whats-new"></div>

## Novita nella v7

{{ config('app.name') }} v7 e una release principale con miglioramenti significativi all'esperienza dello sviluppatore:

### Configurazione dell'Ambiente Crittografata
- Le variabili d'ambiente sono ora crittografate con XOR al momento della compilazione per maggiore sicurezza
- Il nuovo comando `metro make:key` genera la Sua APP_KEY
- Il nuovo comando `metro make:env` genera il file crittografato `env.g.dart`
- Supporto per l'iniezione di APP_KEY tramite `--dart-define` per le pipeline CI/CD

### Processo di Avvio Semplificato
- Il nuovo pattern `BootConfig` sostituisce i callback separati setup/finished
- `Nylo.init()` piu pulito con il parametro `env` per l'ambiente crittografato
- Hook del ciclo di vita dell'app direttamente in main.dart

### Nuova API nylo.configure()
- Un singolo metodo consolida tutta la configurazione dell'app
- Sintassi piu pulita che sostituisce le chiamate individuali `nylo.add*()`
- Metodi separati `setup()` e `boot()` per il ciclo di vita nei provider

### NyPage per le Pagine
- `NyPage` sostituisce `NyState` per i widget delle pagine (sintassi piu pulita)
- `view()` sostituisce il metodo `build()`
- Il getter `get init =>` sostituisce i metodi `init()` e `boot()`
- `NyState` e ancora disponibile per i widget stateful che non sono pagine

### Sistema LoadingStyle
- Nuovo enum `LoadingStyle` per stati di caricamento consistenti
- Opzioni: `LoadingStyle.normal()`, `LoadingStyle.skeletonizer()`, `LoadingStyle.none()`
- Widget di caricamento personalizzati tramite `LoadingStyle.normal(child: ...)`

### Routing Type-Safe con RouteView
- `static RouteView path` sostituisce `static const path`
- Definizioni delle route type-safe con widget factory

### Supporto Multi-Theme
- Registrazione di piu theme scuri e chiari
- ID dei theme definiti nel codice invece che nel file `.env`
- Nuovi `NyThemeType.dark` / `NyThemeType.light` per la classificazione dei theme
- API per theme preferiti: `NyTheme.setPreferredDark()`, `NyTheme.setPreferredLight()`
- Enumerazione dei theme: `NyTheme.lightThemes()`, `NyTheme.darkThemes()`, `NyTheme.all()`

### Nuovi Comandi Metro
- `make:key` - Genera APP_KEY per la crittografia
- `make:env` - Genera il file d'ambiente crittografato
- `make:bottom_sheet_modal` - Crea bottom sheet modal
- `make:button` - Crea pulsanti personalizzati

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">Visualizza tutte le modifiche su GitHub</a>

<div id="breaking-changes"></div>

## Panoramica delle Modifiche Incompatibili

| Modifica | v6 | v7 |
|--------|-----|-----|
| Widget Root dell'App | `LocalizedApp(child: Main(nylo))` | `Main(nylo)` (usa `NyApp.materialApp()`) |
| Classe State della Pagina | `NyState` | `NyPage` per le pagine |
| Metodo View | `build()` | `view()` |
| Metodo Init | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| Percorso Route | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Boot del Provider | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| Configurazione | Chiamate individuali `nylo.add*()` | Singola chiamata `nylo.configure()` |
| ID dei Theme | File `.env` (`LIGHT_THEME_ID`, `DARK_THEME_ID`) | Codice (`type: NyThemeType.dark`) |
| Widget di Caricamento | `useSkeletonizer` + `loading()` | Getter `LoadingStyle` |
| Posizione Config | `lib/config/` | `lib/bootstrap/` (decoders, events, providers, theme) |
| Posizione Asset | `public/` | `assets/` |

<div id="recommended-migration"></div>

## Approccio Raccomandato per la Migrazione

Per progetti piu grandi, si consiglia di creare un nuovo progetto v7 e migrare i file:

1. Creare un nuovo progetto v7: `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. Copiare le Sue pagine, controller, model e service
3. Aggiornare la sintassi come mostrato sopra
4. Testare accuratamente

Questo garantisce di avere tutta la struttura boilerplate e le configurazioni piu recenti.

Se e interessato a visualizzare un diff delle modifiche tra v6 e v7, puo consultare il confronto su GitHub: <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## Checklist Rapida per la Migrazione

Utilizzi questa checklist per monitorare il progresso della migrazione:

- [ ] Aggiornare `pubspec.yaml` (Dart >=3.10.7, Flutter >=3.24.0, nylo_framework: ^7.0.0)
- [ ] Eseguire `flutter pub get`
- [ ] Eseguire `metro make:key` per generare APP_KEY
- [ ] Eseguire `metro make:env` per generare l'ambiente crittografato
- [ ] Aggiornare `main.dart` con il parametro env e BootConfig
- [ ] Convertire la classe `Boot` per utilizzare il pattern `BootConfig`
- [ ] Spostare i file di config da `lib/config/` a `lib/bootstrap/`
- [ ] Creare nuovi file di config (`lib/config/app.dart`, `lib/config/storage_keys.dart`, `lib/config/toast_notification.dart`)
- [ ] Aggiornare `AppProvider` per utilizzare `nylo.configure()`
- [ ] Rimuovere `LIGHT_THEME_ID` e `DARK_THEME_ID` da `.env`
- [ ] Aggiungere `type: NyThemeType.dark` alle configurazioni dei theme scuri
- [ ] Rinominare `NyState` in `NyPage` per tutti i widget delle pagine
- [ ] Cambiare `build()` in `view()` in tutte le pagine
- [ ] Cambiare `init()/boot()` in `get init =>` in tutte le pagine
- [ ] Aggiornare `static const path` in `static RouteView path`
- [ ] Cambiare `router.route()` in `router.add()` nelle route
- [ ] Rinominare i widget (NyListView -> CollectionView, ecc.)
- [ ] Spostare gli asset da `public/` a `assets/`
- [ ] Aggiornare i percorsi degli asset in `pubspec.yaml`
- [ ] Rimuovere gli import di Firebase (se utilizzati - aggiungere i package direttamente)
- [ ] Rimuovere l'utilizzo di NyDevPanel (utilizzare Flutter DevTools)
- [ ] Eseguire `flutter pub get` e testare

<div id="migration-guide"></div>

## Guida alla Migrazione Passo per Passo

<div id="step-1-dependencies"></div>

### Passo 1: Aggiornare le Dipendenze

Aggiorni il Suo `pubspec.yaml`:

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... altre dipendenze
```

Esegua `flutter pub get` per aggiornare i package.

<div id="step-2-environment"></div>

### Passo 2: Configurazione dell'Ambiente

La v7 richiede variabili d'ambiente crittografate per una maggiore sicurezza.

**1. Generare APP_KEY:**

``` bash
metro make:key
```

Questo aggiunge `APP_KEY` al Suo file `.env`.

**2. Generare env.g.dart crittografato:**

``` bash
metro make:env
```

Questo crea `lib/bootstrap/env.g.dart` contenente le Sue variabili d'ambiente crittografate.

**3. Rimuovere le variabili del theme deprecate da .env:**

``` bash
# Rimuova queste righe dal Suo file .env:
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### Passo 3: Aggiornare main.dart

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
      // Opzionale: Aggiungere hook del ciclo di vita dell'app
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**Modifiche Principali:**
- Importare il file generato `env.g.dart`
- Passare `Env.get` al parametro `env`
- `Boot.nylo` e ora `Boot.nylo()` (restituisce `BootConfig`)
- `setupFinished` e stato rimosso (gestito all'interno di `BootConfig`)
- Hook `appLifecycle` opzionali per i cambiamenti di stato dell'app

<div id="step-4-boot"></div>

### Passo 4: Aggiornare boot.dart

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

**Modifiche Principali:**
- Restituisce `BootConfig` invece di `Future<Nylo>`
- `setup` e `finished` combinati in un singolo oggetto `BootConfig`
- `getEnv('SHOW_SPLASH_SCREEN')` -> `AppConfig.showSplashScreen`
- `bootApplication` -> `setupApplication`

<div id="step-5-config"></div>

### Passo 5: Riorganizzare i File di Configurazione

La v7 riorganizza i file di configurazione per una struttura migliore:

| Posizione v6 | Posizione v7 | Azione |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | Spostare |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | Spostare |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | Spostare |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | Spostare |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | Rinominare e Rifattorizzare |
| (nuovo) | `lib/config/app.dart` | Creare |
| (nuovo) | `lib/config/toast_notification.dart` | Creare |

**Creare lib/config/app.dart:**

Riferimento: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // Il nome dell'applicazione.
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // La versione dell'applicazione.
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // Aggiungere qui altre configurazioni dell'app
}
```

**Creare lib/config/storage_keys.dart:**

Riferimento: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // Definire le chiavi da sincronizzare all'avvio
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // dare all'utente 10 monete per default
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// Aggiungere qui le Sue storage key...
}
```

**Creare lib/config/toast_notification.dart:**

Riferimento: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // Personalizzare gli stili dei toast qui
  };
}
```

<div id="step-6-provider"></div>

### Passo 6: Aggiornare AppProvider

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

**Modifiche Principali:**
- `boot()` e ora `setup()` per la configurazione iniziale
- `boot()` e ora utilizzato per la logica post-setup (precedentemente `afterBoot`)
- Tutte le chiamate `nylo.add*()` consolidate in un singolo `nylo.configure()`
- La localizzazione utilizza l'oggetto `NyLocalizationConfig`

<div id="step-7-theme"></div>

### Passo 7: Aggiornare la Configurazione del Theme

**v6 (file .env):**
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

**Modifiche Principali:**
- Rimuovere `LIGHT_THEME_ID` e `DARK_THEME_ID` da `.env`
- Definire gli ID dei theme direttamente nel codice
- Aggiungere `type: NyThemeType.dark` a tutte le configurazioni dei theme scuri
- I theme chiari hanno come predefinito `NyThemeType.light`

**Nuovi Metodi API per i Theme (v7):**
``` dart
// Impostare e ricordare il theme preferito
NyTheme.set(context, id: 'dark_theme', remember: true);

// Impostare i theme preferiti per seguire il sistema
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// Ottenere gli ID dei theme preferiti
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// Enumerazione dei theme
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// Cancellare le preferenze salvate
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### Passo 10: Migrare i Widget

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

// Con paginazione (pull to refresh):
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

### Passo 11: Aggiornare i Percorsi degli Asset

La v7 cambia la directory degli asset da `public/` ad `assets/`:

**1. Spostare le cartelle degli asset:**
``` bash
# Spostare le directory
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. Aggiornare pubspec.yaml:**

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

**3. Aggiornare qualsiasi riferimento agli asset nel codice:**

**v6:**
``` dart
Image.asset('public/images/logo.png')
```

**v7:**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### Widget LocalizedApp - Rimosso

Riferimento: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Migrazione:** Utilizzi direttamente `Main(nylo)`. `NyApp.materialApp()` gestisce la localizzazione internamente.

**v6:**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7:**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## Riferimento delle Classi Eliminate

| Classe Eliminata | Alternativa |
|---------------|-------------|
| `NyTextStyle` | Utilizzare direttamente `TextStyle` di Flutter |
| `NyBaseApiService` | Utilizzare `DioApiService` |
| `BaseColorStyles` | Utilizzare `ThemeColor` |
| `LocalizedApp` | Utilizzare direttamente `Main(nylo)` |
| `NyException` | Utilizzare le eccezioni standard di Dart |
| `PushNotification` | Utilizzare direttamente `flutter_local_notifications` |
| `PushNotificationAttachments` | Utilizzare direttamente `flutter_local_notifications` |

<div id="widget-reference"></div>

## Riferimento per la Migrazione dei Widget

### Widget Rinominati

| Widget v6 | Widget v7 | Note |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | Nuova API con `builder` invece di `child` |
| `NyFutureBuilder` | `FutureWidget` | Widget asincrono semplificato |
| `NyTextField` | `InputField` | Utilizza `FormValidator` |
| `NyLanguageSwitcher` | `LanguageSwitcher` | Stessa API |
| `NyRichText` | `StyledText` | Stessa API |
| `NyFader` | `FadeOverlay` | Stessa API |

### Widget Eliminati (Nessuna Sostituzione Diretta)

| Widget Eliminato | Alternativa |
|----------------|-------------|
| `NyPullToRefresh` | Utilizzare `CollectionView.pullable()` |

### Esempi di Migrazione dei Widget

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

## Risoluzione dei Problemi

### "Env.get not found" o "Env is not defined"

**Soluzione:** Esegua i comandi di generazione dell'ambiente:
``` bash
metro make:key
metro make:env
```
Poi importi il file generato in `main.dart`:
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" o "Dark theme not working"

**Soluzione:** Assicurarsi che i theme scuri abbiano `type: NyThemeType.dark`:
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // Aggiungere questa riga
),
```

### "LocalizedApp not found"

Riferimento: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Soluzione:** `LocalizedApp` e stato rimosso. Cambiare:
``` dart
// Da:
runApp(LocalizedApp(child: Main(nylo)));

// A:
runApp(Main(nylo));
```

### "router.route is not defined"

**Soluzione:** Utilizzare `router.add()` invece:
``` dart
// Da:
router.route(HomePage.path, (context) => HomePage());

// A:
router.add(HomePage.path);
```

### "NyListView not found"

**Soluzione:** `NyListView` e ora `CollectionView`:
``` dart
// Da:
NyListView(...)

// A:
CollectionView<MyModel>(...)
```

### Gli asset non si caricano (immagini, font)

**Soluzione:** Aggiornare i percorsi degli asset da `public/` ad `assets/`:
1. Spostare i file: `mv public/* assets/`
2. Aggiornare i percorsi in `pubspec.yaml`
3. Aggiornare i riferimenti nel codice

### "init() must return a value of type Future"

**Soluzione:** Cambiare alla sintassi getter:
``` dart
// Da:
@override
init() async { ... }

// A:
@override
get init => () async { ... };
```

---

**Ha bisogno di aiuto?** Consulti la [Documentazione di Nylo](https://nylo.dev/docs/7.x) o apra una issue su [GitHub](https://github.com/nylo-core/nylo/issues).
