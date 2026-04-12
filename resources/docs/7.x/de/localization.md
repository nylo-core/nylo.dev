# Lokalisierung

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Konfiguration](#configuration "Konfiguration")
- [Lokalisierte Dateien hinzufügen](#adding-localized-files "Lokalisierte Dateien hinzufügen")
- Grundlagen
  - [Text lokalisieren](#localizing-text "Text lokalisieren")
    - [Argumente](#arguments "Argumente")
    - [StyledText-Platzhalter](#styled-text-placeholders "StyledText-Platzhalter")
  - [Locale aktualisieren](#updating-the-locale "Locale aktualisieren")
  - [Standard-Locale festlegen](#setting-a-default-locale "Standard-Locale festlegen")
- Fortgeschritten
  - [Unterstützte Locales](#supported-locales "Unterstützte Locales")
  - [Fallback-Sprache](#fallback-language "Fallback-Sprache")
  - [RTL-Unterstützung](#rtl-support "RTL-Unterstützung")
  - [Fehlende Schlüssel debuggen](#debug-missing-keys "Fehlende Schlüssel debuggen")
  - [NyLocalization-API](#nylocalization-api "NyLocalization-API")
  - [NyLocaleHelper](#nylocalehelper "NyLocaleHelper")
  - [Sprache über einen Controller ändern](#changing-language-from-controller "Sprache über einen Controller ändern")


<div id="introduction"></div>

## Einleitung

Lokalisierung ermöglicht es Ihnen, Ihre App in mehreren Sprachen bereitzustellen. {{ config('app.name') }} v7 macht es einfach, Text mithilfe von JSON-Sprachdateien zu lokalisieren.

Hier ist ein kurzes Beispiel:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**In Ihrem Widget:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## Konfiguration

Die Lokalisierung wird in `lib/config/localization.dart` konfiguriert:

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

## Lokalisierte Dateien hinzufügen

Fügen Sie Ihre Sprach-JSON-Dateien zum Verzeichnis `lang/` hinzu:

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

### In pubspec.yaml registrieren

Stellen Sie sicher, dass Ihre Sprachdateien in Ihrer `pubspec.yaml` enthalten sind:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## Text lokalisieren

Verwenden Sie die `.tr()`-Extension oder den `trans()`-Helfer, um Strings zu übersetzen:

``` dart
// Mit der .tr()-Extension
"welcome".tr()

// Mit dem trans()-Helfer
trans("welcome")
```

### Verschachtelte Schlüssel

Greifen Sie auf verschachtelte JSON-Schlüssel mit Punkt-Notation zu:

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

### Argumente

Übergeben Sie dynamische Werte in Ihre Übersetzungen mit der `@{{key}}`-Syntax:

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

<div id="styled-text-placeholders"></div>

### StyledText-Platzhalter

Wenn Sie `StyledText.template` mit lokalisierten Strings verwenden, können Sie die `@{{key:text}}`-Syntax nutzen. Dadurch bleibt der **key** über alle Locales hinweg stabil (sodass Ihre Styles und Tap-Handler immer übereinstimmen), während der **text** pro Locale übersetzt wird.

**lang/de.json**
``` json
{
  "learn_skills": "Lerne @{{lang:Sprachen}}, @{{read:Lesen}} und @{{speak:Sprechen}}",
  "already_have_account": "Bereits ein Konto? @{{login:Anmelden}}"
}
```

**lang/es.json**
``` json
{
  "learn_skills": "Aprende @{{lang:Idiomas}}, @{{read:Lectura}} y @{{speak:Habla}}",
  "already_have_account": "¿Ya tienes una cuenta? @{{login:Iniciar sesión}}"
}
```

**In Ihrem Widget:**
``` dart
StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(fontWeight: FontWeight.bold),
  },
)
```

Die Schlüssel `lang`, `read` und `speak` sind in jeder Locale-Datei identisch, sodass die Style-Map für alle Sprachen funktioniert. Der angezeigte Text nach dem `:` ist das, was der Benutzer sieht — "Sprachen" auf Deutsch, "Idiomas" auf Spanisch, usw.

Sie können dies auch mit `onTap` verwenden:

``` dart
StyledText.template(
  "already_have_account".tr(),
  styles: {
    "login": TextStyle(fontWeight: FontWeight.bold),
  },
  onTap: {
    "login": () => routeTo(LoginPage.path),
  },
)
```

> **Hinweis:** Die `@{{key}}`-Syntax (mit `@`-Präfix) ist für Argumente, die zur Übersetzungszeit durch `.tr(arguments:)` ersetzt werden. Die `@{{key:text}}`-Syntax (ohne `@`) ist für `StyledText`-Platzhalter, die zur Renderzeit geparst werden. Verwechseln Sie sie nicht — verwenden Sie `@{{}}` für dynamische Werte und `@{{}}` für gestylte Bereiche.

<div id="updating-the-locale"></div>

## Locale aktualisieren

Ändern Sie die Sprache der App zur Laufzeit:

``` dart
// NyLocalization direkt verwenden
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Muss dem JSON-Dateinamen entsprechen (es.json)
);
```

Wenn Ihr Widget `NyPage` erweitert, verwenden Sie den `changeLanguage`-Helfer:

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

## Standard-Locale festlegen

Legen Sie die Standardsprache in Ihrer `.env`-Datei fest:

``` bash
DEFAULT_LOCALE="en"
```

Oder verwenden Sie die Geräte-Locale, indem Sie Folgendes einstellen:

``` bash
LOCALE_TYPE="device"
```

Nach dem Ändern der `.env` generieren Sie Ihre Umgebungskonfiguration neu:

``` bash
metro make:env
```

<div id="supported-locales"></div>

## Unterstützte Locales

Definieren Sie, welche Locales Ihre App in `LocalizationConfig` unterstützt:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

Diese Liste wird von Flutters `MaterialApp.supportedLocales` verwendet.

<div id="fallback-language"></div>

## Fallback-Sprache

Wenn ein Übersetzungsschlüssel in der aktiven Locale nicht gefunden wird, sucht {{ config('app.name') }} automatisch in der Fallback-Sprache nach, bevor der Rohschlüssel zurückgegeben wird. Die Fallback-Sprache wird in `lib/config/localization.dart` konfiguriert:

``` dart
static const String fallbackLanguageCode = 'en';
```

Diese zweistufige Auflösung gilt sowohl für Top-Level-Schlüssel als auch für punkt-notierte verschachtelte Schlüssel:

1. Schlüssel in der aktiven Locale-Datei nachschlagen.
2. Falls nicht gefunden, in der Fallback-Locale-Datei nachschlagen.
3. Falls immer noch nicht gefunden, den Rohschlüssel zurückgeben.

Wenn beispielsweise der französischen Locale-Datei der Schlüssel `settings.privacy` fehlt, sucht die Fallback-Logik nach `settings.privacy` in der englischen Locale-Datei, bevor sie `"settings.privacy"` unverändert zurückgibt.

Dies stellt sicher, dass Ihre App niemals rohe Schlüssel anzeigt, wenn eine Übersetzung nur teilweise abgeschlossen ist.

<div id="rtl-support"></div>

## RTL-Unterstützung

{{ config('app.name') }} v7 enthält integrierte Unterstützung für Rechts-nach-Links-Sprachen (RTL):

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Pruefen, ob die aktuelle Sprache RTL ist
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // RTL-Layout behandeln
}
```

<div id="debug-missing-keys"></div>

## Fehlende Schlüssel debuggen

Aktivieren Sie Warnungen für fehlende Übersetzungsschlüssel während der Entwicklung:

In Ihrer `.env`-Datei:
``` bash
DEBUG_TRANSLATIONS="true"
```

Dies protokolliert Warnungen, wenn `.tr()` einen Schlüssel nicht finden kann, und hilft Ihnen, nicht übersetzte Strings zu erkennen.

<div id="nylocalization-api"></div>

## NyLocalization-API

`NyLocalization` ist ein Singleton, das die gesamte Lokalisierung verwaltet. Neben der grundlegenden `translate()`-Methode bietet es mehrere zusätzliche Methoden:

### Prüfen, ob eine Übersetzung existiert

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true, wenn der Schlüssel in der aktuellen Sprachdatei vorhanden ist

// Funktioniert auch mit verschachtelten Schlüsseln
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### Alle Übersetzungsschlüssel abrufen

Nützlich zum Debuggen, um zu sehen, welche Schlüssel geladen sind:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### Locale ohne Neustart ändern

Wenn Sie die Locale stillschweigend ändern möchten (ohne die App neu zu starten):

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

Dies lädt die neue Sprachdatei, startet die App aber **nicht** neu. Nützlich, wenn Sie UI-Updates manuell handhaben möchten.

### RTL-Richtung prüfen

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### Aktuelle Locale abrufen

``` dart
// Aktuellen Sprachcode abrufen
String code = NyLocalization.instance.languageCode;  // z.B. 'en'

// Aktuelles Locale-Objekt abrufen
Locale currentLocale = NyLocalization.instance.locale;

// Flutter-Lokalisierungs-Delegates abrufen (verwendet in MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### Vollständige API-Referenz

| Methode / Eigenschaft | Rückgabewert | Beschreibung |
|----------------------|-------------|-------------|
| `instance` | `NyLocalization` | Singleton-Instanz |
| `translate(key, [arguments])` | `String` | Einen Schlüssel mit optionalen Argumenten übersetzen |
| `hasTranslation(key)` | `bool` | Prüfen, ob ein Übersetzungsschlüssel existiert |
| `getAllKeys()` | `List<String>` | Alle geladenen Übersetzungsschlüssel abrufen |
| `setLanguage(context, {language, restart})` | `Future<void>` | Sprache ändern, optional neu starten |
| `setLocale({locale})` | `Future<void>` | Locale ohne Neustart ändern |
| `setDebugMissingKeys(enabled)` | `void` | Protokollierung fehlender Schlüssel aktivieren/deaktivieren |
| `isDirectionRTL(context)` | `bool` | Prüfen, ob die aktuelle Richtung RTL ist |
| `restart(context)` | `void` | Die App neu starten |
| `languageCode` | `String` | Aktueller Sprachcode |
| `locale` | `Locale` | Aktuelles Locale-Objekt |
| `delegates` | `Iterable<LocalizationsDelegate>` | Flutter-Lokalisierungs-Delegates |
| `setValuesForTesting({values, fallbackValues})` | `void` | Übersetzungs-Maps direkt fuer Unit-Tests einschleusen |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` ist eine statische Hilfsklasse für Locale-Operationen. Sie bietet Methoden zur Erkennung der aktuellen Locale, zur Prüfung der RTL-Unterstützung und zur Erstellung von Locale-Objekten.

``` dart
// Aktuelle System-Locale abrufen
Locale locale = NyLocaleHelper.getCurrentLocale(context: context);

// Sprach- und Laendercodes abrufen
String langCode = NyLocaleHelper.getLanguageCode(context: context);  // 'en'
String? countryCode = NyLocaleHelper.getCountryCode(context: context);  // 'US' oder null

// Pruefen, ob aktuelle Locale uebereinstimmt
bool isEnglish = NyLocaleHelper.matchesLocale(context, 'en');
bool isUsEnglish = NyLocaleHelper.matchesLocale(context, 'en', 'US');

// RTL-Erkennung
bool isRtl = NyLocaleHelper.isRtlLanguage('ar');  // true
bool currentIsRtl = NyLocaleHelper.isCurrentLocaleRtl(context: context);

// Textrichtung abrufen
TextDirection direction = NyLocaleHelper.getTextDirection('ar');  // TextDirection.rtl
TextDirection currentDir = NyLocaleHelper.getCurrentTextDirection(context: context);

// Locale aus Strings erstellen
Locale newLocale = NyLocaleHelper.toLocale('en', 'US');
```

### Vollständige API-Referenz

| Methode | Rückgabewert | Beschreibung |
|---------|-------------|-------------|
| `getCurrentLocale({context})` | `Locale` | Aktuelle System-Locale abrufen |
| `getLanguageCode({context})` | `String` | Aktuellen Sprachcode abrufen |
| `getCountryCode({context})` | `String?` | Aktuellen Ländercode abrufen |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | Prüfen, ob aktuelle Locale übereinstimmt |
| `isRtlLanguage(languageCode)` | `bool` | Prüfen, ob ein Sprachcode RTL ist |
| `isCurrentLocaleRtl({context})` | `bool` | Prüfen, ob aktuelle Locale RTL ist |
| `getTextDirection(languageCode)` | `TextDirection` | TextDirection für eine Sprache abrufen |
| `getCurrentTextDirection({context})` | `TextDirection` | TextDirection für aktuelle Locale abrufen |
| `toLocale(languageCode, [countryCode])` | `Locale` | Locale aus Strings erstellen |

Die Konstante `rtlLanguages` enthält: `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`.

<div id="changing-language-from-controller"></div>

## Sprache über einen Controller ändern

Wenn Sie Controller mit Ihren Seiten verwenden, können Sie die Sprache über `NyController` ändern:

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

Der Parameter `restartState` steuert, ob die App nach dem Sprachwechsel neu gestartet wird. Setzen Sie ihn auf `false`, wenn Sie das UI-Update selbst handhaben möchten.
