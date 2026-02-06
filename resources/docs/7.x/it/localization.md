# Localizzazione

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione alla localizzazione")
- [Configurazione](#configuration "Configurazione")
- [Aggiungere File Localizzati](#adding-localized-files "Aggiungere file localizzati")
- Fondamenti
  - [Localizzare il Testo](#localizing-text "Localizzare il testo")
    - [Argomenti](#arguments "Argomenti")
  - [Aggiornare la Lingua](#updating-the-locale "Aggiornare la lingua")
  - [Impostare una Lingua Predefinita](#setting-a-default-locale "Impostare una lingua predefinita")
- Avanzato
  - [Lingue Supportate](#supported-locales "Lingue supportate")
  - [Lingua di Fallback](#fallback-language "Lingua di fallback")
  - [Supporto RTL](#rtl-support "Supporto RTL")
  - [Debug Chiavi Mancanti](#debug-missing-keys "Debug chiavi mancanti")
  - [API NyLocalization](#nylocalization-api "API NyLocalization")
  - [NyLocaleHelper](#nylocalehelper "Classe utility NyLocaleHelper")
  - [Cambiare Lingua da un Controller](#changing-language-from-controller "Cambiare lingua da un controller")


<div id="introduction"></div>

## Introduzione

La localizzazione ti permette di fornire la tua app in più lingue. {{ config('app.name') }} v7 rende facile localizzare il testo utilizzando file JSON di lingua.

Ecco un esempio rapido:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**Nel tuo widget:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## Configurazione

La localizzazione è configurata in `lib/config/localization.dart`:

``` dart
final class LocalizationConfig {
  // Default language code (matches your JSON file, e.g., 'en' for lang/en.json)
  static final String languageCode =
      getEnv('DEFAULT_LOCALE', defaultValue: "en");

  // LocaleType.device - Use device's language setting
  // LocaleType.asDefined - Use languageCode above
  static final LocaleType localeType =
      getEnv('LOCALE_TYPE', defaultValue: 'asDefined') == 'device'
          ? LocaleType.device
          : LocaleType.asDefined;

  // Directory containing language JSON files
  static const String assetsDirectory = 'lang/';

  // List of supported locales
  static const List<Locale> supportedLocales = [
    Locale('en'),
    Locale('es'),
    // Add more locales as needed
  ];

  // Fallback when a key is not found in the active locale
  static const String fallbackLanguageCode = 'en';

  // RTL language codes
  static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

  // Log warnings for missing translation keys
  static final bool debugMissingKeys =
      getEnv('DEBUG_TRANSLATIONS', defaultValue: 'false') == 'true';
}
```

<div id="adding-localized-files"></div>

## Aggiungere File Localizzati

Aggiungi i tuoi file JSON di lingua alla directory `lang/`:

```
lang/
├── en.json   # English
├── es.json   # Spanish
├── fr.json   # French
└── ...
```

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "settings": "Settings",
  "navigation": {
    "home": "Home",
    "profile": "Profile"
  }
}
```

**lang/es.json**
``` json
{
  "welcome": "Bienvenido",
  "settings": "Configuración",
  "navigation": {
    "home": "Inicio",
    "profile": "Perfil"
  }
}
```

### Registrazione nel pubspec.yaml

Assicurati che i tuoi file di lingua siano inclusi nel tuo `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## Localizzare il Testo

Usa l'estensione `.tr()` o l'helper `trans()` per tradurre le stringhe:

``` dart
// Using the .tr() extension
"welcome".tr()

// Using the trans() helper
trans("welcome")
```

### Chiavi Annidate

Accedi alle chiavi JSON annidate utilizzando la notazione a punto:

**lang/en.json**
``` json
{
  "navigation": {
    "home": "Home",
    "profile": "Profile"
  }
}
```

``` dart
"navigation.home".tr()       // "Home"
trans("navigation.profile")  // "Profile"
```

<div id="arguments"></div>

### Argomenti

Passa valori dinamici nelle tue traduzioni utilizzando la sintassi `@{{key}}`:

**lang/en.json**
``` json
{
  "greeting": "Hello @{{name}}",
  "items_count": "You have @{{count}} items"
}
```

``` dart
"greeting".tr(arguments: {"name": "Anthony"})
// "Hello Anthony"

trans("items_count", arguments: {"count": "5"})
// "You have 5 items"
```

<div id="updating-the-locale"></div>

## Aggiornare la Lingua

Cambia la lingua dell'app in fase di esecuzione:

``` dart
// Using NyLocalization directly
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Must match your JSON filename (es.json)
);
```

Se il tuo widget estende `NyPage`, usa l'helper `changeLanguage`:

``` dart
class _SettingsPageState extends NyPage<SettingsPage> {

  @override
  Widget view(BuildContext context) {
    return ListView(
      children: [
        ListTile(
          title: Text("English"),
          onTap: () => changeLanguage('en'),
        ),
        ListTile(
          title: Text("Español"),
          onTap: () => changeLanguage('es'),
        ),
      ],
    );
  }
}
```

<div id="setting-a-default-locale"></div>

## Impostare una Lingua Predefinita

Imposta la lingua predefinita nel tuo file `.env`:

``` bash
DEFAULT_LOCALE="en"
```

Oppure usa la lingua del dispositivo impostando:

``` bash
LOCALE_TYPE="device"
```

Dopo aver modificato `.env`, rigenera la configurazione dell'ambiente:

``` bash
metro make:env --force
```

<div id="supported-locales"></div>

## Lingue Supportate

Definisci quali lingue supporta la tua app in `LocalizationConfig`:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

Questa lista viene utilizzata da `MaterialApp.supportedLocales` di Flutter.

<div id="fallback-language"></div>

## Lingua di Fallback

Quando una chiave di traduzione non viene trovata nella lingua attiva, {{ config('app.name') }} ricorre alla lingua specificata:

``` dart
static const String fallbackLanguageCode = 'en';
```

Questo assicura che la tua app non mostri mai chiavi grezze se una traduzione manca.

<div id="rtl-support"></div>

## Supporto RTL

{{ config('app.name') }} v7 include il supporto integrato per le lingue da destra a sinistra (RTL):

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Check if current language is RTL
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // Handle RTL layout
}
```

<div id="debug-missing-keys"></div>

## Debug Chiavi Mancanti

Abilita gli avvisi per le chiavi di traduzione mancanti durante lo sviluppo:

Nel tuo file `.env`:
``` bash
DEBUG_TRANSLATIONS="true"
```

Questo registra avvisi quando `.tr()` non riesce a trovare una chiave, aiutandoti a individuare stringhe non tradotte.

<div id="nylocalization-api"></div>

## API NyLocalization

`NyLocalization` è un singleton che gestisce tutta la localizzazione. Oltre al metodo base `translate()`, fornisce diversi metodi aggiuntivi:

### Verificare se una Traduzione Esiste

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true if the key exists in the current language file

// Also works with nested keys
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### Ottenere Tutte le Chiavi di Traduzione

Utile per il debug per vedere quali chiavi sono caricate:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### Cambiare Lingua Senza Riavvio

Se vuoi cambiare la lingua silenziosamente (senza riavviare l'app):

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

Questo carica il nuovo file di lingua ma **non** riavvia l'app. Utile quando vuoi gestire gli aggiornamenti dell'interfaccia manualmente.

### Verificare la Direzione RTL

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### Accedere alla Lingua Corrente

``` dart
// Get the current language code
String code = NyLocalization.instance.languageCode;  // e.g., 'en'

// Get the current Locale object
Locale currentLocale = NyLocalization.instance.locale;

// Get Flutter localization delegates (used in MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### Riferimento Completo dell'API

| Metodo / Proprietà | Restituisce | Descrizione |
|---------------------|-------------|-------------|
| `instance` | `NyLocalization` | Istanza singleton |
| `translate(key, [arguments])` | `String` | Traduce una chiave con argomenti opzionali |
| `hasTranslation(key)` | `bool` | Verifica se una chiave di traduzione esiste |
| `getAllKeys()` | `List<String>` | Ottiene tutte le chiavi di traduzione caricate |
| `setLanguage(context, {language, restart})` | `Future<void>` | Cambia lingua, opzionalmente riavvia |
| `setLocale({locale})` | `Future<void>` | Cambia lingua senza riavvio |
| `setDebugMissingKeys(enabled)` | `void` | Abilita/disabilita il log delle chiavi mancanti |
| `isDirectionRTL(context)` | `bool` | Verifica se la direzione corrente è RTL |
| `restart(context)` | `void` | Riavvia l'app |
| `languageCode` | `String` | Codice lingua corrente |
| `locale` | `Locale` | Oggetto Locale corrente |
| `delegates` | `Iterable<LocalizationsDelegate>` | Delegati di localizzazione Flutter |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` è una classe utility statica per le operazioni sulle lingue. Fornisce metodi per rilevare la lingua corrente, verificare il supporto RTL e creare oggetti Locale.

``` dart
// Get the current system locale
Locale locale = NyLocaleHelper.getCurrentLocale(context: context);

// Get language and country codes
String langCode = NyLocaleHelper.getLanguageCode(context: context);  // 'en'
String? countryCode = NyLocaleHelper.getCountryCode(context: context);  // 'US' or null

// Check if current locale matches
bool isEnglish = NyLocaleHelper.matchesLocale(context, 'en');
bool isUsEnglish = NyLocaleHelper.matchesLocale(context, 'en', 'US');

// RTL detection
bool isRtl = NyLocaleHelper.isRtlLanguage('ar');  // true
bool currentIsRtl = NyLocaleHelper.isCurrentLocaleRtl(context: context);

// Get text direction
TextDirection direction = NyLocaleHelper.getTextDirection('ar');  // TextDirection.rtl
TextDirection currentDir = NyLocaleHelper.getCurrentTextDirection(context: context);

// Create a Locale from strings
Locale newLocale = NyLocaleHelper.toLocale('en', 'US');
```

### Riferimento Completo dell'API

| Metodo | Restituisce | Descrizione |
|--------|-------------|-------------|
| `getCurrentLocale({context})` | `Locale` | Ottiene la lingua di sistema corrente |
| `getLanguageCode({context})` | `String` | Ottiene il codice lingua corrente |
| `getCountryCode({context})` | `String?` | Ottiene il codice paese corrente |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | Verifica se la lingua corrente corrisponde |
| `isRtlLanguage(languageCode)` | `bool` | Verifica se un codice lingua è RTL |
| `isCurrentLocaleRtl({context})` | `bool` | Verifica se la lingua corrente è RTL |
| `getTextDirection(languageCode)` | `TextDirection` | Ottiene la TextDirection per una lingua |
| `getCurrentTextDirection({context})` | `TextDirection` | Ottiene la TextDirection per la lingua corrente |
| `toLocale(languageCode, [countryCode])` | `Locale` | Crea un Locale da stringhe |

La costante `rtlLanguages` contiene: `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`.

<div id="changing-language-from-controller"></div>

## Cambiare Lingua da un Controller

Se utilizzi controller con le tue pagine, puoi cambiare la lingua da `NyController`:

``` dart
class SettingsController extends NyController {

  void switchToSpanish() {
    changeLanguage('es');
  }

  void switchToEnglishNoRestart() {
    changeLanguage('en', restartState: false);
  }
}
```

Il parametro `restartState` controlla se l'app si riavvia dopo aver cambiato la lingua. Impostalo su `false` se vuoi gestire l'aggiornamento dell'interfaccia da solo.
