# Localization

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to localization")
- [Configuration](#configuration "Configuration")
- [Adding Localized Files](#adding-localized-files "Adding localized files")
- Basics
  - [Localizing Text](#localizing-text "Localizing text")
    - [Arguments](#arguments "Arguments")
  - [Updating the Locale](#updating-the-locale "Updating the locale")
  - [Setting a Default Locale](#setting-a-default-locale "Setting a default locale")
- Advanced
  - [Supported Locales](#supported-locales "Supported locales")
  - [Fallback Language](#fallback-language "Fallback language")
  - [RTL Support](#rtl-support "RTL support")
  - [Debug Missing Keys](#debug-missing-keys "Debug missing keys")
  - [NyLocalization API](#nylocalization-api "NyLocalization API")
  - [NyLocaleHelper](#nylocalehelper "NyLocaleHelper utility class")
  - [Changing Language from a Controller](#changing-language-from-controller "Changing language from a controller")


<div id="introduction"></div>

## Introduction

Localization allows you to provide your app in multiple languages. {{ config('app.name') }} v7 makes it easy to localize text using JSON language files.

Here's a quick example:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**In your widget:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## Configuration

Localization is configured in `lib/config/localization.dart`:

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

## Adding Localized Files

Add your language JSON files to the `lang/` directory:

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

### Register in pubspec.yaml

Make sure your language files are included in your `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## Localizing Text

Use the `.tr()` extension or the `trans()` helper to translate strings:

``` dart
// Using the .tr() extension
"welcome".tr()

// Using the trans() helper
trans("welcome")
```

### Nested Keys

Access nested JSON keys using dot notation:

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

### Arguments

Pass dynamic values into your translations using the `@{{key}}` syntax:

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

## Updating the Locale

Change the app's language at runtime:

``` dart
// Using NyLocalization directly
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Must match your JSON filename (es.json)
);
```

If your widget extends `NyPage`, use the `changeLanguage` helper:

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

## Setting a Default Locale

Set the default language in your `.env` file:

``` bash
DEFAULT_LOCALE="en"
```

Or use the device's locale by setting:

``` bash
LOCALE_TYPE="device"
```

After changing `.env`, regenerate your environment config:

``` bash
metro make:env --force
```

<div id="supported-locales"></div>

## Supported Locales

Define which locales your app supports in `LocalizationConfig`:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

This list is used by Flutter's `MaterialApp.supportedLocales`.

<div id="fallback-language"></div>

## Fallback Language

When a translation key is not found in the active locale, {{ config('app.name') }} falls back to the specified language:

``` dart
static const String fallbackLanguageCode = 'en';
```

This ensures your app never shows raw keys if a translation is missing.

<div id="rtl-support"></div>

## RTL Support

{{ config('app.name') }} v7 includes built-in support for right-to-left (RTL) languages:

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Check if current language is RTL
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // Handle RTL layout
}
```

<div id="debug-missing-keys"></div>

## Debug Missing Keys

Enable warnings for missing translation keys during development:

In your `.env` file:
``` bash
DEBUG_TRANSLATIONS="true"
```

This logs warnings when `.tr()` cannot find a key, helping you catch untranslated strings.

<div id="nylocalization-api"></div>

## NyLocalization API

`NyLocalization` is a singleton that manages all localization. Beyond the basic `translate()` method, it provides several additional methods:

### Check if a Translation Exists

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true if the key exists in the current language file

// Also works with nested keys
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### Get All Translation Keys

Useful for debugging to see which keys are loaded:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### Change Locale Without Restart

If you want to change the locale silently (without restarting the app):

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

This loads the new language file but does **not** restart the app. Useful when you want to handle UI updates manually.

### Check RTL Direction

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### Access Current Locale

``` dart
// Get the current language code
String code = NyLocalization.instance.languageCode;  // e.g., 'en'

// Get the current Locale object
Locale currentLocale = NyLocalization.instance.locale;

// Get Flutter localization delegates (used in MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### Full API Reference

| Method / Property | Returns | Description |
|-------------------|---------|-------------|
| `instance` | `NyLocalization` | Singleton instance |
| `translate(key, [arguments])` | `String` | Translate a key with optional arguments |
| `hasTranslation(key)` | `bool` | Check if a translation key exists |
| `getAllKeys()` | `List<String>` | Get all loaded translation keys |
| `setLanguage(context, {language, restart})` | `Future<void>` | Change language, optionally restart |
| `setLocale({locale})` | `Future<void>` | Change locale without restart |
| `setDebugMissingKeys(enabled)` | `void` | Enable/disable missing key logging |
| `isDirectionRTL(context)` | `bool` | Check if current direction is RTL |
| `restart(context)` | `void` | Restart the app |
| `languageCode` | `String` | Current language code |
| `locale` | `Locale` | Current Locale object |
| `delegates` | `Iterable<LocalizationsDelegate>` | Flutter localization delegates |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` is a static utility class for locale operations. It provides methods for detecting the current locale, checking RTL support, and creating Locale objects.

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

### Full API Reference

| Method | Returns | Description |
|--------|---------|-------------|
| `getCurrentLocale({context})` | `Locale` | Get current system locale |
| `getLanguageCode({context})` | `String` | Get current language code |
| `getCountryCode({context})` | `String?` | Get current country code |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | Check if current locale matches |
| `isRtlLanguage(languageCode)` | `bool` | Check if a language code is RTL |
| `isCurrentLocaleRtl({context})` | `bool` | Check if current locale is RTL |
| `getTextDirection(languageCode)` | `TextDirection` | Get TextDirection for a language |
| `getCurrentTextDirection({context})` | `TextDirection` | Get TextDirection for current locale |
| `toLocale(languageCode, [countryCode])` | `Locale` | Create a Locale from strings |

The `rtlLanguages` constant contains: `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`.

<div id="changing-language-from-controller"></div>

## Changing Language from a Controller

If you use controllers with your pages, you can change the language from `NyController`:

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

The `restartState` parameter controls whether the app restarts after changing the language. Set it to `false` if you want to handle the UI update yourself.
