# Localization

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do lokalizacji")
- [Konfiguracja](#configuration "Konfiguracja")
- [Dodawanie zlokalizowanych plików](#adding-localized-files "Dodawanie zlokalizowanych plików")
- Podstawy
  - [Lokalizowanie tekstu](#localizing-text "Lokalizowanie tekstu")
    - [Argumenty](#arguments "Argumenty")
    - [Symbole zastępcze StyledText](#styled-text-placeholders "Symbole zastępcze StyledText")
  - [Aktualizowanie lokalizacji](#updating-the-locale "Aktualizowanie lokalizacji")
  - [Ustawianie domyślnej lokalizacji](#setting-a-default-locale "Ustawianie domyślnej lokalizacji")
- Zaawansowane
  - [Obsługiwane lokalizacje](#supported-locales "Obsługiwane lokalizacje")
  - [Język zapasowy](#fallback-language "Język zapasowy")
  - [Obsługa RTL](#rtl-support "Obsługa RTL")
  - [Debugowanie brakujących kluczy](#debug-missing-keys "Debugowanie brakujących kluczy")
  - [API NyLocalization](#nylocalization-api "API NyLocalization")
  - [NyLocaleHelper](#nylocalehelper "Klasa narzędziowa NyLocaleHelper")
  - [Zmiana języka z kontrolera](#changing-language-from-controller "Zmiana języka z kontrolera")


<div id="introduction"></div>

## Wprowadzenie

Lokalizacja pozwala udostępniać aplikację w wielu językach. {{ config('app.name') }} v7 ułatwia lokalizowanie tekstu za pomocą plików JSON z tłumaczeniami.

Oto szybki przykład:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**W widgecie:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## Konfiguracja

Lokalizacja jest konfigurowana w pliku `lib/config/localization.dart`:

``` dart
final class LocalizationConfig {
  // Domyślny kod języka (odpowiada plikowi JSON, np. 'en' dla lang/en.json)
  static final String languageCode =
      getEnv('DEFAULT_LOCALE', defaultValue: "en");

  // LocaleType.device - Używa ustawienia języka urządzenia
  // LocaleType.asDefined - Używa languageCode powyżej
  static final LocaleType localeType =
      getEnv('LOCALE_TYPE', defaultValue: 'asDefined') == 'device'
          ? LocaleType.device
          : LocaleType.asDefined;

  // Katalog zawierający pliki JSON z językami
  static const String assetsDirectory = 'lang/';

  // Lista obsługiwanych lokalizacji
  static const List<Locale> supportedLocales = [
    Locale('en'),
    Locale('es'),
    // Dodaj więcej lokalizacji w razie potrzeby
  ];

  // Język zapasowy gdy klucz nie zostanie znaleziony w aktywnej lokalizacji
  static const String fallbackLanguageCode = 'en';

  // Kody języków RTL
  static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

  // Loguj ostrzeżenia o brakujących kluczach tłumaczeń
  static final bool debugMissingKeys =
      getEnv('DEBUG_TRANSLATIONS', defaultValue: 'false') == 'true';
}
```

<div id="adding-localized-files"></div>

## Dodawanie zlokalizowanych plików

Dodaj pliki JSON z tłumaczeniami do katalogu `lang/`:

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

### Rejestracja w pubspec.yaml

Upewnij się, że pliki językowe są uwzględnione w pliku `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## Lokalizowanie tekstu

Użyj rozszerzenia `.tr()` lub helpera `trans()`, aby tłumaczyć ciągi znaków:

``` dart
// Używanie rozszerzenia .tr()
"welcome".tr()

// Używanie helpera trans()
trans("welcome")
```

### Zagnieżdżone klucze

Dostęp do zagnieżdżonych kluczy JSON za pomocą notacji z kropką:

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

### Argumenty

Przekazuj dynamiczne wartości do tłumaczeń za pomocą składni `@{{key}}`:

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

### Symbole zastępcze StyledText

Przy użyciu `StyledText.template` z zlokalizowanymi ciągami znaków, możesz użyć składni `@{{key:text}}`. Pozwala to zachować stabilność **klucza** we wszystkich lokalizacjach (dzięki czemu style i obsługa dotknięć zawsze pasują), podczas gdy **tekst** jest tłumaczony dla każdej lokalizacji.

**lang/en.json**
``` json
{
  "learn_skills": "Learn @{{lang:Languages}}, @{{read:Reading}} and @{{speak:Speaking}} skills",
  "already_have_account": "Already have an account? @{{login:Login}}"
}
```

**lang/es.json**
``` json
{
  "learn_skills": "Aprende @{{lang:Idiomas}}, @{{read:Lectura}} y @{{speak:Habla}}",
  "already_have_account": "¿Ya tienes una cuenta? @{{login:Iniciar sesión}}"
}
```

**W widgecie:**
``` dart
StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(fontWeight: FontWeight.bold),
  },
)
```

Klucze `lang`, `read` i `speak` są takie same w każdym pliku lokalizacji, więc mapa stylów działa dla wszystkich języków. Tekst wyświetlany po `:` to to, co widzi użytkownik — "Languages" po angielsku, "Idiomas" po hiszpańsku itp.

Możesz tego również użyć z `onTap`:

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

> **Uwaga:** Składnia `@{{key}}` (z prefiksem `@`) służy do argumentów zastępowanych przez `.tr(arguments:)` w czasie tłumaczenia. Składnia `@{{key:text}}` (bez `@`) służy do symboli zastępczych `StyledText` parsowanych w czasie renderowania. Nie mieszaj ich — używaj `@{{}}` dla wartości dynamicznych i `@{{}}` dla stylizowanych fragmentów.

<div id="updating-the-locale"></div>

## Aktualizowanie lokalizacji

Zmień język aplikacji w czasie działania:

``` dart
// Używanie NyLocalization bezpośrednio
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Musi odpowiadać nazwie pliku JSON (es.json)
);
```

Jeśli Twój widget rozszerza `NyPage`, użyj helpera `changeLanguage`:

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

## Ustawianie domyślnej lokalizacji

Ustaw domyślny język w pliku `.env`:

``` bash
DEFAULT_LOCALE="en"
```

Lub użyj lokalizacji urządzenia, ustawiając:

``` bash
LOCALE_TYPE="device"
```

Po zmianie `.env` zregeneruj konfigurację środowiska:

``` bash
metro make:env
```

<div id="supported-locales"></div>

## Obsługiwane lokalizacje

Zdefiniuj, które lokalizacje obsługuje Twoja aplikacja w `LocalizationConfig`:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

Ta lista jest używana przez `MaterialApp.supportedLocales` we Flutterze.

<div id="fallback-language"></div>

## Język zapasowy

Gdy klucz tłumaczenia nie zostanie znaleziony w aktywnej lokalizacji, {{ config('app.name') }} automatycznie wyszukuje go w języku zapasowym przed zwróceniem surowego klucza. Język zapasowy jest konfigurowany w `lib/config/localization.dart`:

``` dart
static const String fallbackLanguageCode = 'en';
```

Ta dwuetapowa resolucja dotyczy zarówno kluczy najwyższego poziomu, jak i zagnieżdżonych kluczy z notacją kropkową:

1. Wyszukaj klucz w aktywnym pliku lokalizacji.
2. Jeśli nie znaleziono, wyszukaj go w pliku lokalizacji zapasowej.
3. Jeśli nadal nie znaleziono, zwróć ciąg surowego klucza.

Na przykład, jeśli w pliku lokalizacji francuskiej brakuje klucza `settings.privacy`, logika zapasowa szuka `settings.privacy` w pliku lokalizacji angielskiej przed zwróceniem `"settings.privacy"` bez zmian.

Zapewnia to, że Twoja aplikacja nigdy nie wyświetli surowych kluczy, jeśli tłumaczenie jest tylko częściowo kompletne.

<div id="rtl-support"></div>

## Obsługa RTL

{{ config('app.name') }} v7 zawiera wbudowaną obsługę języków pisanych od prawej do lewej (RTL):

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Sprawdź, czy aktualny język jest RTL
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // Obsłuż układ RTL
}
```

<div id="debug-missing-keys"></div>

## Debugowanie brakujących kluczy

Włącz ostrzeżenia o brakujących kluczach tłumaczeń podczas rozwoju:

W pliku `.env`:
``` bash
DEBUG_TRANSLATIONS="true"
```

To loguje ostrzeżenia, gdy `.tr()` nie może znaleźć klucza, pomagając wychwycić nieprzetłumaczone ciągi znaków.

<div id="nylocalization-api"></div>

## API NyLocalization

`NyLocalization` to singleton zarządzający całą lokalizacją. Oprócz podstawowej metody `translate()` udostępnia kilka dodatkowych metod:

### Sprawdzanie istnienia tłumaczenia

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true jeśli klucz istnieje w bieżącym pliku językowym

// Działa też z zagnieżdżonymi kluczami
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### Pobieranie wszystkich kluczy tłumaczeń

Przydatne do debugowania, aby zobaczyć, które klucze są załadowane:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]  (przykładowe klucze)
```

### Zmiana lokalizacji bez restartu

Jeśli chcesz zmienić lokalizację cicho (bez restartowania aplikacji):

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

To ładuje nowy plik językowy, ale **nie** restartuje aplikacji. Przydatne, gdy chcesz samodzielnie obsłużyć aktualizacje interfejsu.

### Sprawdzanie kierunku RTL

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### Dostęp do aktualnej lokalizacji

``` dart
// Pobierz aktualny kod języka
String code = NyLocalization.instance.languageCode;  // np. 'en'

// Pobierz aktualny obiekt Locale
Locale currentLocale = NyLocalization.instance.locale;

// Pobierz delegaty lokalizacji Fluttera (używane w MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### Pełna referencja API

| Metoda / Właściwość | Zwraca | Opis |
|---------------------|--------|------|
| `instance` | `NyLocalization` | Instancja singletona |
| `translate(key, [arguments])` | `String` | Tłumaczenie klucza z opcjonalnymi argumentami |
| `hasTranslation(key)` | `bool` | Sprawdzenie, czy klucz tłumaczenia istnieje |
| `getAllKeys()` | `List<String>` | Pobranie wszystkich załadowanych kluczy tłumaczeń |
| `setLanguage(context, {language, restart})` | `Future<void>` | Zmiana języka, opcjonalny restart |
| `setLocale({locale})` | `Future<void>` | Zmiana lokalizacji bez restartu |
| `setDebugMissingKeys(enabled)` | `void` | Włączenie/wyłączenie logowania brakujących kluczy |
| `isDirectionRTL(context)` | `bool` | Sprawdzenie, czy aktualny kierunek to RTL |
| `restart(context)` | `void` | Restart aplikacji |
| `languageCode` | `String` | Aktualny kod języka |
| `locale` | `Locale` | Aktualny obiekt Locale |
| `delegates` | `Iterable<LocalizationsDelegate>` | Delegaty lokalizacji Fluttera |
| `setValuesForTesting({values, fallbackValues})` | `void` | Wstrzyknięcie map tłumaczeń bezpośrednio do testów jednostkowych |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` to statyczna klasa narzędziowa do operacji na lokalizacjach. Udostępnia metody do wykrywania aktualnej lokalizacji, sprawdzania obsługi RTL i tworzenia obiektów Locale.

``` dart
// Pobierz aktualną lokalizację systemową
Locale locale = NyLocaleHelper.getCurrentLocale(context: context);

// Pobierz kody języka i kraju
String langCode = NyLocaleHelper.getLanguageCode(context: context);  // 'en'
String? countryCode = NyLocaleHelper.getCountryCode(context: context);  // 'US' lub null

// Sprawdź, czy aktualna lokalizacja pasuje
bool isEnglish = NyLocaleHelper.matchesLocale(context, 'en');
bool isUsEnglish = NyLocaleHelper.matchesLocale(context, 'en', 'US');

// Wykrywanie RTL
bool isRtl = NyLocaleHelper.isRtlLanguage('ar');  // true
bool currentIsRtl = NyLocaleHelper.isCurrentLocaleRtl(context: context);

// Pobierz kierunek tekstu
TextDirection direction = NyLocaleHelper.getTextDirection('ar');  // TextDirection.rtl
TextDirection currentDir = NyLocaleHelper.getCurrentTextDirection(context: context);

// Utwórz Locale z ciągów znaków
Locale newLocale = NyLocaleHelper.toLocale('en', 'US');
```

### Pełna referencja API

| Metoda | Zwraca | Opis |
|--------|--------|------|
| `getCurrentLocale({context})` | `Locale` | Pobranie aktualnej lokalizacji systemowej |
| `getLanguageCode({context})` | `String` | Pobranie aktualnego kodu języka |
| `getCountryCode({context})` | `String?` | Pobranie aktualnego kodu kraju |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | Sprawdzenie, czy aktualna lokalizacja pasuje |
| `isRtlLanguage(languageCode)` | `bool` | Sprawdzenie, czy kod języka jest RTL |
| `isCurrentLocaleRtl({context})` | `bool` | Sprawdzenie, czy aktualna lokalizacja jest RTL |
| `getTextDirection(languageCode)` | `TextDirection` | Pobranie TextDirection dla języka |
| `getCurrentTextDirection({context})` | `TextDirection` | Pobranie TextDirection dla aktualnej lokalizacji |
| `toLocale(languageCode, [countryCode])` | `Locale` | Utworzenie Locale z ciągów znaków |

Stała `rtlLanguages` zawiera: `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`.

<div id="changing-language-from-controller"></div>

## Zmiana języka z kontrolera

Jeśli używasz kontrolerów ze swoimi stronami, możesz zmienić język z `NyController`:

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

Parametr `restartState` kontroluje, czy aplikacja restartuje się po zmianie języka. Ustaw go na `false`, jeśli chcesz samodzielnie obsłużyć aktualizację interfejsu.
