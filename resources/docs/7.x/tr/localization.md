# Yerelleştirme

---

<a name="section-1"></a>
- [Giriş](#introduction "Yerelleştirmeye giriş")
- [Yapılandırma](#configuration "Yapılandırma")
- [Yerelleştirilmiş Dosyalar Ekleme](#adding-localized-files "Yerelleştirilmiş dosyalar ekleme")
- Temel Bilgiler
  - [Metni Yerelleştirme](#localizing-text "Metni yerelleştirme")
    - [Argümanlar](#arguments "Argümanlar")
  - [Yerel Ayarı Güncelleme](#updating-the-locale "Yerel ayarı güncelleme")
  - [Varsayılan Yerel Ayar Belirleme](#setting-a-default-locale "Varsayılan yerel ayar belirleme")
- Gelişmiş
  - [Desteklenen Yerel Ayarlar](#supported-locales "Desteklenen yerel ayarlar")
  - [Yedek Dil](#fallback-language "Yedek dil")
  - [RTL Desteği](#rtl-support "RTL desteği")
  - [Eksik Anahtarları Hata Ayıklama](#debug-missing-keys "Eksik anahtarları hata ayıklama")
  - [NyLocalization API](#nylocalization-api "NyLocalization API")
  - [NyLocaleHelper](#nylocalehelper "NyLocaleHelper yardımcı sınıfı")
  - [Controller'dan Dil Değiştirme](#changing-language-from-controller "Controller'dan dil değiştirme")


<div id="introduction"></div>

## Giriş

Yerelleştirme, uygulamanızı birden fazla dilde sunmanıza olanak tanır. {{ config('app.name') }} v7, JSON dil dosyaları kullanarak metni yerelleştirmeyi kolaylaştırır.

İşte hızlı bir örnek:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**Widget'ınızda:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## Yapılandırma

Yerelleştirme `lib/config/localization.dart` dosyasında yapılandırılır:

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

## Yerelleştirilmiş Dosyalar Ekleme

Dil JSON dosyalarınızı `lang/` dizinine ekleyin:

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

### pubspec.yaml'da Kaydetme

Dil dosyalarınızın `pubspec.yaml` dosyanızda dahil edildiğinden emin olun:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## Metni Yerelleştirme

Dizeleri çevirmek için `.tr()` uzantısını veya `trans()` yardımcısını kullanın:

``` dart
// Using the .tr() extension
"welcome".tr()

// Using the trans() helper
trans("welcome")
```

### İç İçe Anahtarlar

Nokta gösterimi kullanarak iç içe JSON anahtarlarına erişin:

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

### Argümanlar

`@{{key}}` sözdizimini kullanarak çevirilerinize dinamik değerler geçirin:

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

## Yerel Ayarı Güncelleme

Uygulamanın dilini çalışma zamanında değiştirin:

``` dart
// Using NyLocalization directly
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Must match your JSON filename (es.json)
);
```

Widget'ınız `NyPage` sınıfını genişletiyorsa, `changeLanguage` yardımcısını kullanın:

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

## Varsayılan Yerel Ayar Belirleme

`.env` dosyanızda varsayılan dili ayarlayın:

``` bash
DEFAULT_LOCALE="en"
```

Veya cihazın yerel ayarını kullanmak için şunu ayarlayın:

``` bash
LOCALE_TYPE="device"
```

`.env` dosyasını değiştirdikten sonra ortam yapılandırmanızı yeniden oluşturun:

``` bash
metro make:env --force
```

<div id="supported-locales"></div>

## Desteklenen Yerel Ayarlar

Uygulamanızın hangi yerel ayarları desteklediğini `LocalizationConfig` içinde tanımlayın:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

Bu liste, Flutter'ın `MaterialApp.supportedLocales` tarafından kullanılır.

<div id="fallback-language"></div>

## Yedek Dil

Aktif yerel ayarda bir çeviri anahtarı bulunamadığında, {{ config('app.name') }} belirtilen dile geri döner:

``` dart
static const String fallbackLanguageCode = 'en';
```

Bu, bir çeviri eksik olduğunda uygulamanızın asla ham anahtarları göstermemesini sağlar.

<div id="rtl-support"></div>

## RTL Desteği

{{ config('app.name') }} v7, sağdan sola (RTL) diller için yerleşik destek içerir:

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Check if current language is RTL
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // Handle RTL layout
}
```

<div id="debug-missing-keys"></div>

## Eksik Anahtarları Hata Ayıklama

Geliştirme sırasında eksik çeviri anahtarları için uyarıları etkinleştirin:

`.env` dosyanızda:
``` bash
DEBUG_TRANSLATIONS="true"
```

Bu, `.tr()` bir anahtar bulamadığında uyarıları günlüğe kaydeder ve çevrilmemiş dizeleri yakalamanıza yardımcı olur.

<div id="nylocalization-api"></div>

## NyLocalization API

`NyLocalization`, tüm yerelleştirmeyi yöneten bir singleton'dır. Temel `translate()` metodunun ötesinde, birkaç ek metot sağlar:

### Bir Çevirinin Var Olup Olmadığını Kontrol Etme

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true if the key exists in the current language file

// Also works with nested keys
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### Tüm Çeviri Anahtarlarını Alma

Hangi anahtarların yüklendiğini görmek için hata ayıklamada kullanışlıdır:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### Yeniden Başlatmadan Yerel Ayar Değiştirme

Yerel ayarı sessizce değiştirmek istiyorsanız (uygulamayı yeniden başlatmadan):

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

Bu, yeni dil dosyasını yükler ancak uygulamayı yeniden **başlatmaz**. Arayüz güncellemelerini manuel olarak yönetmek istediğinizde kullanışlıdır.

### RTL Yönünü Kontrol Etme

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### Mevcut Yerel Ayara Erişim

``` dart
// Get the current language code
String code = NyLocalization.instance.languageCode;  // e.g., 'en'

// Get the current Locale object
Locale currentLocale = NyLocalization.instance.locale;

// Get Flutter localization delegates (used in MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### Tam API Referansı

| Metot / Özellik | Döndürür | Açıklama |
|-------------------|---------|-------------|
| `instance` | `NyLocalization` | Singleton örneği |
| `translate(key, [arguments])` | `String` | İsteğe bağlı argümanlarla bir anahtarı çevir |
| `hasTranslation(key)` | `bool` | Bir çeviri anahtarının var olup olmadığını kontrol et |
| `getAllKeys()` | `List<String>` | Tüm yüklenen çeviri anahtarlarını al |
| `setLanguage(context, {language, restart})` | `Future<void>` | Dili değiştir, isteğe bağlı yeniden başlat |
| `setLocale({locale})` | `Future<void>` | Yeniden başlatmadan yerel ayarı değiştir |
| `setDebugMissingKeys(enabled)` | `void` | Eksik anahtar günlüğünü etkinleştir/devre dışı bırak |
| `isDirectionRTL(context)` | `bool` | Mevcut yönün RTL olup olmadığını kontrol et |
| `restart(context)` | `void` | Uygulamayı yeniden başlat |
| `languageCode` | `String` | Mevcut dil kodu |
| `locale` | `Locale` | Mevcut Locale nesnesi |
| `delegates` | `Iterable<LocalizationsDelegate>` | Flutter yerelleştirme delegeleri |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper`, yerel ayar işlemleri için statik bir yardımcı sınıftır. Mevcut yerel ayarı algılama, RTL desteğini kontrol etme ve Locale nesneleri oluşturma metotları sağlar.

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

### Tam API Referansı

| Metot | Döndürür | Açıklama |
|--------|---------|-------------|
| `getCurrentLocale({context})` | `Locale` | Mevcut sistem yerel ayarını al |
| `getLanguageCode({context})` | `String` | Mevcut dil kodunu al |
| `getCountryCode({context})` | `String?` | Mevcut ülke kodunu al |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | Mevcut yerel ayarın eşleşip eşleşmediğini kontrol et |
| `isRtlLanguage(languageCode)` | `bool` | Bir dil kodunun RTL olup olmadığını kontrol et |
| `isCurrentLocaleRtl({context})` | `bool` | Mevcut yerel ayarın RTL olup olmadığını kontrol et |
| `getTextDirection(languageCode)` | `TextDirection` | Bir dil için TextDirection al |
| `getCurrentTextDirection({context})` | `TextDirection` | Mevcut yerel ayar için TextDirection al |
| `toLocale(languageCode, [countryCode])` | `Locale` | Dizelerden bir Locale oluştur |

`rtlLanguages` sabiti şunları içerir: `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`.

<div id="changing-language-from-controller"></div>

## Controller'dan Dil Değiştirme

Sayfalarınızla controller kullanıyorsanız, `NyController`'dan dili değiştirebilirsiniz:

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

`restartState` parametresi, dil değiştirildikten sonra uygulamanın yeniden başlayıp başlamayacağını kontrol eder. Arayüz güncellemesini kendiniz yönetmek istiyorsanız `false` olarak ayarlayın.
