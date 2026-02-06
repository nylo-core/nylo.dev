# Lokalisasi

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar tentang lokalisasi")
- [Konfigurasi](#configuration "Konfigurasi")
- [Menambahkan File Lokal](#adding-localized-files "Menambahkan file lokal")
- Dasar
  - [Melokalkan Teks](#localizing-text "Melokalkan teks")
    - [Argumen](#arguments "Argumen")
  - [Memperbarui Locale](#updating-the-locale "Memperbarui locale")
  - [Mengatur Locale Default](#setting-a-default-locale "Mengatur locale default")
- Lanjutan
  - [Locale yang Didukung](#supported-locales "Locale yang didukung")
  - [Bahasa Fallback](#fallback-language "Bahasa fallback")
  - [Dukungan RTL](#rtl-support "Dukungan RTL")
  - [Debug Key yang Hilang](#debug-missing-keys "Debug key yang hilang")
  - [API NyLocalization](#nylocalization-api "API NyLocalization")
  - [NyLocaleHelper](#nylocalehelper "Kelas utilitas NyLocaleHelper")
  - [Mengubah Bahasa dari Controller](#changing-language-from-controller "Mengubah bahasa dari controller")


<div id="introduction"></div>

## Pengantar

Lokalisasi memungkinkan Anda menyediakan aplikasi dalam beberapa bahasa. {{ config('app.name') }} v7 memudahkan pelokalan teks menggunakan file bahasa JSON.

Berikut contoh singkatnya:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**Di widget Anda:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## Konfigurasi

Lokalisasi dikonfigurasi di `lib/config/localization.dart`:

``` dart
final class LocalizationConfig {
  // Kode bahasa default (sesuai dengan file JSON Anda, contoh: 'en' untuk lang/en.json)
  static final String languageCode =
      getEnv('DEFAULT_LOCALE', defaultValue: "en");

  // LocaleType.device - Gunakan pengaturan bahasa perangkat
  // LocaleType.asDefined - Gunakan languageCode di atas
  static final LocaleType localeType =
      getEnv('LOCALE_TYPE', defaultValue: 'asDefined') == 'device'
          ? LocaleType.device
          : LocaleType.asDefined;

  // Direktori yang berisi file JSON bahasa
  static const String assetsDirectory = 'lang/';

  // Daftar locale yang didukung
  static const List<Locale> supportedLocales = [
    Locale('en'),
    Locale('es'),
    // Tambahkan lebih banyak locale sesuai kebutuhan
  ];

  // Fallback ketika key tidak ditemukan di locale aktif
  static const String fallbackLanguageCode = 'en';

  // Kode bahasa RTL
  static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

  // Log peringatan untuk key terjemahan yang hilang
  static final bool debugMissingKeys =
      getEnv('DEBUG_TRANSLATIONS', defaultValue: 'false') == 'true';
}
```

<div id="adding-localized-files"></div>

## Menambahkan File Lokal

Tambahkan file JSON bahasa Anda ke direktori `lang/`:

```
lang/
├── en.json   # Inggris
├── es.json   # Spanyol
├── fr.json   # Prancis
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

### Daftarkan di pubspec.yaml

Pastikan file bahasa Anda disertakan di `pubspec.yaml` Anda:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## Melokalkan Teks

Gunakan extension `.tr()` atau helper `trans()` untuk menerjemahkan string:

``` dart
// Menggunakan extension .tr()
"welcome".tr()

// Menggunakan helper trans()
trans("welcome")
```

### Key Bersarang

Akses key JSON bersarang menggunakan notasi titik:

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

### Argumen

Sisipkan nilai dinamis ke dalam terjemahan Anda menggunakan sintaks `@{{key}}`:

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

## Memperbarui Locale

Ubah bahasa aplikasi saat runtime:

``` dart
// Menggunakan NyLocalization secara langsung
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Harus sesuai dengan nama file JSON Anda (es.json)
);
```

Jika widget Anda meng-extend `NyPage`, gunakan helper `changeLanguage`:

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

## Mengatur Locale Default

Atur bahasa default di file `.env` Anda:

``` bash
DEFAULT_LOCALE="en"
```

Atau gunakan locale perangkat dengan mengatur:

``` bash
LOCALE_TYPE="device"
```

Setelah mengubah `.env`, regenerate konfigurasi environment Anda:

``` bash
metro make:env --force
```

<div id="supported-locales"></div>

## Locale yang Didukung

Tentukan locale mana yang didukung aplikasi Anda di `LocalizationConfig`:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

Daftar ini digunakan oleh `MaterialApp.supportedLocales` Flutter.

<div id="fallback-language"></div>

## Bahasa Fallback

Ketika key terjemahan tidak ditemukan di locale aktif, {{ config('app.name') }} akan kembali ke bahasa yang ditentukan:

``` dart
static const String fallbackLanguageCode = 'en';
```

Ini memastikan aplikasi Anda tidak pernah menampilkan key mentah jika terjemahan hilang.

<div id="rtl-support"></div>

## Dukungan RTL

{{ config('app.name') }} v7 menyertakan dukungan bawaan untuk bahasa kanan-ke-kiri (RTL):

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Periksa apakah bahasa saat ini adalah RTL
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // Tangani tata letak RTL
}
```

<div id="debug-missing-keys"></div>

## Debug Key yang Hilang

Aktifkan peringatan untuk key terjemahan yang hilang selama pengembangan:

Di file `.env` Anda:
``` bash
DEBUG_TRANSLATIONS="true"
```

Ini mencatat peringatan saat `.tr()` tidak dapat menemukan key, membantu Anda menemukan string yang belum diterjemahkan.

<div id="nylocalization-api"></div>

## API NyLocalization

`NyLocalization` adalah singleton yang mengelola semua lokalisasi. Selain method dasar `translate()`, ia menyediakan beberapa method tambahan:

### Memeriksa Apakah Terjemahan Ada

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true jika key ada di file bahasa saat ini

// Juga bekerja dengan key bersarang
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### Mendapatkan Semua Key Terjemahan

Berguna untuk debugging untuk melihat key mana yang dimuat:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### Mengubah Locale Tanpa Restart

Jika Anda ingin mengubah locale secara diam-diam (tanpa me-restart aplikasi):

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

Ini memuat file bahasa baru tetapi **tidak** me-restart aplikasi. Berguna saat Anda ingin menangani pembaruan UI secara manual.

### Memeriksa Arah RTL

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### Mengakses Locale Saat Ini

``` dart
// Mendapatkan kode bahasa saat ini
String code = NyLocalization.instance.languageCode;  // contoh: 'en'

// Mendapatkan objek Locale saat ini
Locale currentLocale = NyLocalization.instance.locale;

// Mendapatkan delegate lokalisasi Flutter (digunakan di MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### Referensi API Lengkap

| Method / Properti | Mengembalikan | Deskripsi |
|-------------------|---------------|-----------|
| `instance` | `NyLocalization` | Instance singleton |
| `translate(key, [arguments])` | `String` | Menerjemahkan key dengan argumen opsional |
| `hasTranslation(key)` | `bool` | Memeriksa apakah key terjemahan ada |
| `getAllKeys()` | `List<String>` | Mendapatkan semua key terjemahan yang dimuat |
| `setLanguage(context, {language, restart})` | `Future<void>` | Mengubah bahasa, opsional restart |
| `setLocale({locale})` | `Future<void>` | Mengubah locale tanpa restart |
| `setDebugMissingKeys(enabled)` | `void` | Mengaktifkan/menonaktifkan logging key yang hilang |
| `isDirectionRTL(context)` | `bool` | Memeriksa apakah arah saat ini RTL |
| `restart(context)` | `void` | Me-restart aplikasi |
| `languageCode` | `String` | Kode bahasa saat ini |
| `locale` | `Locale` | Objek Locale saat ini |
| `delegates` | `Iterable<LocalizationsDelegate>` | Delegate lokalisasi Flutter |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` adalah kelas utilitas statis untuk operasi locale. Ia menyediakan method untuk mendeteksi locale saat ini, memeriksa dukungan RTL, dan membuat objek Locale.

``` dart
// Mendapatkan locale sistem saat ini
Locale locale = NyLocaleHelper.getCurrentLocale(context: context);

// Mendapatkan kode bahasa dan negara
String langCode = NyLocaleHelper.getLanguageCode(context: context);  // 'en'
String? countryCode = NyLocaleHelper.getCountryCode(context: context);  // 'US' atau null

// Memeriksa apakah locale saat ini cocok
bool isEnglish = NyLocaleHelper.matchesLocale(context, 'en');
bool isUsEnglish = NyLocaleHelper.matchesLocale(context, 'en', 'US');

// Deteksi RTL
bool isRtl = NyLocaleHelper.isRtlLanguage('ar');  // true
bool currentIsRtl = NyLocaleHelper.isCurrentLocaleRtl(context: context);

// Mendapatkan arah teks
TextDirection direction = NyLocaleHelper.getTextDirection('ar');  // TextDirection.rtl
TextDirection currentDir = NyLocaleHelper.getCurrentTextDirection(context: context);

// Membuat Locale dari string
Locale newLocale = NyLocaleHelper.toLocale('en', 'US');
```

### Referensi API Lengkap

| Method | Mengembalikan | Deskripsi |
|--------|---------------|-----------|
| `getCurrentLocale({context})` | `Locale` | Mendapatkan locale sistem saat ini |
| `getLanguageCode({context})` | `String` | Mendapatkan kode bahasa saat ini |
| `getCountryCode({context})` | `String?` | Mendapatkan kode negara saat ini |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | Memeriksa apakah locale saat ini cocok |
| `isRtlLanguage(languageCode)` | `bool` | Memeriksa apakah kode bahasa adalah RTL |
| `isCurrentLocaleRtl({context})` | `bool` | Memeriksa apakah locale saat ini RTL |
| `getTextDirection(languageCode)` | `TextDirection` | Mendapatkan TextDirection untuk suatu bahasa |
| `getCurrentTextDirection({context})` | `TextDirection` | Mendapatkan TextDirection untuk locale saat ini |
| `toLocale(languageCode, [countryCode])` | `Locale` | Membuat Locale dari string |

Konstanta `rtlLanguages` berisi: `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`.

<div id="changing-language-from-controller"></div>

## Mengubah Bahasa dari Controller

Jika Anda menggunakan controller dengan halaman Anda, Anda dapat mengubah bahasa dari `NyController`:

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

Parameter `restartState` mengontrol apakah aplikasi akan restart setelah mengubah bahasa. Atur ke `false` jika Anda ingin menangani pembaruan UI sendiri.
