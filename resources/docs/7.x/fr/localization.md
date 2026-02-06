# Localisation

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction a la localisation")
- [Configuration](#configuration "Configuration")
- [Ajouter des fichiers localises](#adding-localized-files "Ajouter des fichiers localises")
- Les bases
  - [Localiser du texte](#localizing-text "Localiser du texte")
    - [Arguments](#arguments "Arguments")
  - [Mettre a jour la locale](#updating-the-locale "Mettre a jour la locale")
  - [Definir une locale par defaut](#setting-a-default-locale "Definir une locale par defaut")
- Avance
  - [Locales prises en charge](#supported-locales "Locales prises en charge")
  - [Langue de secours](#fallback-language "Langue de secours")
  - [Support RTL](#rtl-support "Support RTL")
  - [Debugger les cles manquantes](#debug-missing-keys "Debugger les cles manquantes")
  - [API NyLocalization](#nylocalization-api "API NyLocalization")
  - [NyLocaleHelper](#nylocalehelper "Classe utilitaire NyLocaleHelper")
  - [Changer la langue depuis un controleur](#changing-language-from-controller "Changer la langue depuis un controleur")


<div id="introduction"></div>

## Introduction

La localisation vous permet de fournir votre application en plusieurs langues. {{ config('app.name') }} v7 facilite la localisation du texte en utilisant des fichiers de langue JSON.

Voici un exemple rapide :

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**Dans votre widget :**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## Configuration

La localisation est configuree dans `lib/config/localization.dart` :

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

## Ajouter des fichiers localises

Ajoutez vos fichiers JSON de langue dans le repertoire `lang/` :

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

### Enregistrer dans pubspec.yaml

Assurez-vous que vos fichiers de langue sont inclus dans votre `pubspec.yaml` :

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## Localiser du texte

Utilisez l'extension `.tr()` ou le helper `trans()` pour traduire des chaines :

``` dart
// Using the .tr() extension
"welcome".tr()

// Using the trans() helper
trans("welcome")
```

### Cles imbriquees

Accedez aux cles JSON imbriquees en utilisant la notation par points :

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

Passez des valeurs dynamiques dans vos traductions en utilisant la syntaxe `@{{key}}` :

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

## Mettre a jour la locale

Changez la langue de l'application au moment de l'execution :

``` dart
// Using NyLocalization directly
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Must match your JSON filename (es.json)
);
```

Si votre widget etend `NyPage`, utilisez le helper `changeLanguage` :

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

## Definir une locale par defaut

Definissez la langue par defaut dans votre fichier `.env` :

``` bash
DEFAULT_LOCALE="en"
```

Ou utilisez la locale de l'appareil en definissant :

``` bash
LOCALE_TYPE="device"
```

Apres avoir modifie `.env`, regenerez votre configuration d'environnement :

``` bash
metro make:env --force
```

<div id="supported-locales"></div>

## Locales prises en charge

Definissez quelles locales votre application prend en charge dans `LocalizationConfig` :

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

Cette liste est utilisee par `MaterialApp.supportedLocales` de Flutter.

<div id="fallback-language"></div>

## Langue de secours

Lorsqu'une cle de traduction n'est pas trouvee dans la locale active, {{ config('app.name') }} se rabat sur la langue specifiee :

``` dart
static const String fallbackLanguageCode = 'en';
```

Cela garantit que votre application n'affiche jamais de cles brutes si une traduction est manquante.

<div id="rtl-support"></div>

## Support RTL

{{ config('app.name') }} v7 inclut un support integre pour les langues de droite a gauche (RTL) :

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Check if current language is RTL
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // Handle RTL layout
}
```

<div id="debug-missing-keys"></div>

## Debugger les cles manquantes

Activez les avertissements pour les cles de traduction manquantes pendant le developpement :

Dans votre fichier `.env` :
``` bash
DEBUG_TRANSLATIONS="true"
```

Cela journalise les avertissements lorsque `.tr()` ne trouve pas une cle, vous aidant a reperer les chaines non traduites.

<div id="nylocalization-api"></div>

## API NyLocalization

`NyLocalization` est un singleton qui gere toute la localisation. Au-dela de la methode de base `translate()`, il fournit plusieurs methodes supplementaires :

### Verifier si une traduction existe

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true if the key exists in the current language file

// Also works with nested keys
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### Obtenir toutes les cles de traduction

Utile pour le debogage pour voir quelles cles sont chargees :

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### Changer la locale sans redemarrage

Si vous souhaitez changer la locale silencieusement (sans redemarrer l'application) :

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

Cela charge le nouveau fichier de langue mais ne redemarre **pas** l'application. Utile lorsque vous souhaitez gerer les mises a jour de l'interface manuellement.

### Verifier la direction RTL

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### Acceder a la locale actuelle

``` dart
// Get the current language code
String code = NyLocalization.instance.languageCode;  // e.g., 'en'

// Get the current Locale object
Locale currentLocale = NyLocalization.instance.locale;

// Get Flutter localization delegates (used in MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### Reference API complete

| Methode / Propriete | Retour | Description |
|---------------------|--------|-------------|
| `instance` | `NyLocalization` | Instance singleton |
| `translate(key, [arguments])` | `String` | Traduire une cle avec des arguments optionnels |
| `hasTranslation(key)` | `bool` | Verifier si une cle de traduction existe |
| `getAllKeys()` | `List<String>` | Obtenir toutes les cles de traduction chargees |
| `setLanguage(context, {language, restart})` | `Future<void>` | Changer la langue, optionnellement redemarrer |
| `setLocale({locale})` | `Future<void>` | Changer la locale sans redemarrage |
| `setDebugMissingKeys(enabled)` | `void` | Activer/desactiver la journalisation des cles manquantes |
| `isDirectionRTL(context)` | `bool` | Verifier si la direction actuelle est RTL |
| `restart(context)` | `void` | Redemarrer l'application |
| `languageCode` | `String` | Code de langue actuel |
| `locale` | `Locale` | Objet Locale actuel |
| `delegates` | `Iterable<LocalizationsDelegate>` | Delegues de localisation Flutter |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` est une classe utilitaire statique pour les operations de locale. Elle fournit des methodes pour detecter la locale actuelle, verifier le support RTL et creer des objets Locale.

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

### Reference API complete

| Methode | Retour | Description |
|---------|--------|-------------|
| `getCurrentLocale({context})` | `Locale` | Obtenir la locale systeme actuelle |
| `getLanguageCode({context})` | `String` | Obtenir le code de langue actuel |
| `getCountryCode({context})` | `String?` | Obtenir le code pays actuel |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | Verifier si la locale actuelle correspond |
| `isRtlLanguage(languageCode)` | `bool` | Verifier si un code de langue est RTL |
| `isCurrentLocaleRtl({context})` | `bool` | Verifier si la locale actuelle est RTL |
| `getTextDirection(languageCode)` | `TextDirection` | Obtenir la TextDirection pour une langue |
| `getCurrentTextDirection({context})` | `TextDirection` | Obtenir la TextDirection pour la locale actuelle |
| `toLocale(languageCode, [countryCode])` | `Locale` | Creer une Locale a partir de chaines |

La constante `rtlLanguages` contient : `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`.

<div id="changing-language-from-controller"></div>

## Changer la langue depuis un controleur

Si vous utilisez des controleurs avec vos pages, vous pouvez changer la langue depuis `NyController` :

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

Le parametre `restartState` controle si l'application redemarre apres le changement de langue. Definissez-le a `false` si vous souhaitez gerer la mise a jour de l'interface vous-meme.
