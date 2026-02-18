# Localization

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับการแปลภาษา")
- [การตั้งค่า](#configuration "การตั้งค่า")
- [เพิ่มไฟล์ภาษา](#adding-localized-files "เพิ่มไฟล์ภาษา")
- พื้นฐาน
  - [การแปลข้อความ](#localizing-text "การแปลข้อความ")
    - [อาร์กิวเมนต์](#arguments "อาร์กิวเมนต์")
    - [ตัวยึดตำแหน่ง StyledText](#styled-text-placeholders "ตัวยึดตำแหน่ง StyledText")
  - [อัปเดต Locale](#updating-the-locale "อัปเดต Locale")
  - [ตั้งค่า Locale เริ่มต้น](#setting-a-default-locale "ตั้งค่า Locale เริ่มต้น")
- ขั้นสูง
  - [Locales ที่รองรับ](#supported-locales "Locales ที่รองรับ")
  - [ภาษาสำรอง](#fallback-language "ภาษาสำรอง")
  - [รองรับ RTL](#rtl-support "รองรับ RTL")
  - [ดีบักคีย์ที่หายไป](#debug-missing-keys "ดีบักคีย์ที่หายไป")
  - [NyLocalization API](#nylocalization-api "NyLocalization API")
  - [NyLocaleHelper](#nylocalehelper "คลาสยูทิลิตี NyLocaleHelper")
  - [เปลี่ยนภาษาจาก Controller](#changing-language-from-controller "เปลี่ยนภาษาจาก controller")


<div id="introduction"></div>

## บทนำ

การแปลภาษาช่วยให้คุณให้บริการแอปของคุณในหลายภาษา {{ config('app.name') }} v7 ทำให้การแปลข้อความเป็นเรื่องง่ายโดยใช้ไฟล์ภาษา JSON

นี่คือตัวอย่างสั้นๆ:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**ใน widget ของคุณ:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## การตั้งค่า

การแปลภาษาถูกตั้งค่าใน `lib/config/localization.dart`:

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

## เพิ่มไฟล์ภาษา

เพิ่มไฟล์ภาษา JSON ของคุณลงในไดเรกทอรี `lang/`:

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

### ลงทะเบียนใน pubspec.yaml

ตรวจสอบให้แน่ใจว่าไฟล์ภาษาของคุณถูกรวมอยู่ใน `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## การแปลข้อความ

ใช้ส่วนขยาย `.tr()` หรือตัวช่วย `trans()` เพื่อแปลสตริง:

``` dart
// Using the .tr() extension
"welcome".tr()

// Using the trans() helper
trans("welcome")
```

### คีย์ซ้อน

เข้าถึงคีย์ JSON ที่ซ้อนกันโดยใช้สัญลักษณ์จุด:

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

### อาร์กิวเมนต์

ส่งค่าแบบไดนามิกเข้าไปในคำแปลโดยใช้ไวยากรณ์ `@{{key}}`:

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

### ตัวยึดตำแหน่ง StyledText

เมื่อใช้ `StyledText.template` กับสตริงที่แปลแล้ว คุณสามารถใช้ไวยากรณ์ `@{{key:text}}` สิ่งนี้ทำให้ **key** คงที่ในทุก locale (เพื่อให้สไตล์และตัวจัดการ tap ของคุณตรงกันเสมอ) ในขณะที่ **text** จะถูกแปลตามแต่ละ locale

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

**ใน widget ของคุณ:**
``` dart
StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(fontWeight: FontWeight.bold),
  },
)
```

คีย์ `lang`, `read` และ `speak` เหมือนกันในทุกไฟล์ locale ดังนั้นแมปสไตล์จะทำงานได้กับทุกภาษา ข้อความแสดงหลังเครื่องหมาย `:` คือสิ่งที่ผู้ใช้เห็น -- "Languages" ในภาษาอังกฤษ "Idiomas" ในภาษาสเปน เป็นต้น

คุณยังสามารถใช้สิ่งนี้กับ `onTap`:

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

> **หมายเหตุ:** ไวยากรณ์ `@{{key}}` (พร้อมคำนำหน้า `@`) ใช้สำหรับอาร์กิวเมนต์ที่ถูกแทนที่โดย `.tr(arguments:)` ในเวลาแปล ไวยากรณ์ `@{{key:text}}` (ไม่มี `@`) ใช้สำหรับตัวยึดตำแหน่ง `StyledText` ที่ถูกแยกวิเคราะห์ในเวลาเรนเดอร์ อย่าสับสน -- ใช้ `@{{}}` สำหรับค่าแบบไดนามิกและ `@{{}}` สำหรับ span ที่มีสไตล์

<div id="updating-the-locale"></div>

## อัปเดต Locale

เปลี่ยนภาษาของแอปในขณะรันไทม์:

``` dart
// Using NyLocalization directly
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Must match your JSON filename (es.json)
);
```

หาก widget ของคุณ extend `NyPage` ให้ใช้ตัวช่วย `changeLanguage`:

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

## ตั้งค่า Locale เริ่มต้น

ตั้งค่าภาษาเริ่มต้นในไฟล์ `.env` ของคุณ:

``` bash
DEFAULT_LOCALE="en"
```

หรือใช้ locale ของอุปกรณ์โดยตั้งค่า:

``` bash
LOCALE_TYPE="device"
```

หลังจากเปลี่ยน `.env` ให้สร้าง config สภาพแวดล้อมใหม่:

``` bash
metro make:env --force
```

<div id="supported-locales"></div>

## Locales ที่รองรับ

กำหนด locales ที่แอปของคุณรองรับใน `LocalizationConfig`:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

รายการนี้ถูกใช้โดย `MaterialApp.supportedLocales` ของ Flutter

<div id="fallback-language"></div>

## ภาษาสำรอง

เมื่อไม่พบคีย์คำแปลใน locale ที่ใช้งาน {{ config('app.name') }} จะใช้ภาษาสำรองที่ระบุ:

``` dart
static const String fallbackLanguageCode = 'en';
```

สิ่งนี้ทำให้แอปของคุณไม่แสดงคีย์ดิบหากคำแปลหายไป

<div id="rtl-support"></div>

## รองรับ RTL

{{ config('app.name') }} v7 มีการรองรับในตัวสำหรับภาษาที่เขียนจากขวาไปซ้าย (RTL):

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Check if current language is RTL
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // Handle RTL layout
}
```

<div id="debug-missing-keys"></div>

## ดีบักคีย์ที่หายไป

เปิดใช้คำเตือนสำหรับคีย์คำแปลที่หายไประหว่างการพัฒนา:

ในไฟล์ `.env` ของคุณ:
``` bash
DEBUG_TRANSLATIONS="true"
```

สิ่งนี้จะบันทึกคำเตือนเมื่อ `.tr()` ไม่พบคีย์ ช่วยให้คุณจับสตริงที่ยังไม่ได้แปล

<div id="nylocalization-api"></div>

## NyLocalization API

`NyLocalization` เป็น singleton ที่จัดการการแปลภาษาทั้งหมด นอกเหนือจากเมธอด `translate()` พื้นฐาน มันยังมีเมธอดเพิ่มเติมหลายตัว:

### ตรวจสอบว่ามีคำแปลหรือไม่

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true if the key exists in the current language file

// Also works with nested keys
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### ดึงคีย์คำแปลทั้งหมด

มีประโยชน์สำหรับการดีบักเพื่อดูว่าคีย์ใดถูกโหลด:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### เปลี่ยน Locale โดยไม่ต้องรีสตาร์ท

หากคุณต้องการเปลี่ยน locale แบบเงียบ (โดยไม่ต้องรีสตาร์ทแอป):

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

สิ่งนี้จะโหลดไฟล์ภาษาใหม่แต่ **ไม่** รีสตาร์ทแอป มีประโยชน์เมื่อคุณต้องการจัดการการอัปเดต UI ด้วยตนเอง

### ตรวจสอบทิศทาง RTL

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### เข้าถึง Locale ปัจจุบัน

``` dart
// Get the current language code
String code = NyLocalization.instance.languageCode;  // e.g., 'en'

// Get the current Locale object
Locale currentLocale = NyLocalization.instance.locale;

// Get Flutter localization delegates (used in MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### เอกสารอ้างอิง API ฉบับเต็ม

| เมธอด / คุณสมบัติ | ส่งคืน | คำอธิบาย |
|-------------------|---------|-------------|
| `instance` | `NyLocalization` | อินสแตนซ์ singleton |
| `translate(key, [arguments])` | `String` | แปลคีย์พร้อมอาร์กิวเมนต์ที่เป็นทางเลือก |
| `hasTranslation(key)` | `bool` | ตรวจสอบว่ามีคีย์คำแปลหรือไม่ |
| `getAllKeys()` | `List<String>` | ดึงคีย์คำแปลที่โหลดทั้งหมด |
| `setLanguage(context, {language, restart})` | `Future<void>` | เปลี่ยนภาษา เลือกรีสตาร์ทได้ |
| `setLocale({locale})` | `Future<void>` | เปลี่ยน locale โดยไม่ต้องรีสตาร์ท |
| `setDebugMissingKeys(enabled)` | `void` | เปิด/ปิดการบันทึกคีย์ที่หายไป |
| `isDirectionRTL(context)` | `bool` | ตรวจสอบว่าทิศทางปัจจุบันเป็น RTL หรือไม่ |
| `restart(context)` | `void` | รีสตาร์ทแอป |
| `languageCode` | `String` | รหัสภาษาปัจจุบัน |
| `locale` | `Locale` | อ็อบเจกต์ Locale ปัจจุบัน |
| `delegates` | `Iterable<LocalizationsDelegate>` | ตัวแทนการแปลภาษาของ Flutter |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` เป็นคลาสยูทิลิตีแบบ static สำหรับการดำเนินการเกี่ยวกับ locale มีเมธอดสำหรับตรวจจับ locale ปัจจุบัน ตรวจสอบการรองรับ RTL และสร้างอ็อบเจกต์ Locale

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

### เอกสารอ้างอิง API ฉบับเต็ม

| เมธอด | ส่งคืน | คำอธิบาย |
|--------|---------|-------------|
| `getCurrentLocale({context})` | `Locale` | ดึง locale ระบบปัจจุบัน |
| `getLanguageCode({context})` | `String` | ดึงรหัสภาษาปัจจุบัน |
| `getCountryCode({context})` | `String?` | ดึงรหัสประเทศปัจจุบัน |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | ตรวจสอบว่า locale ปัจจุบันตรงกันหรือไม่ |
| `isRtlLanguage(languageCode)` | `bool` | ตรวจสอบว่ารหัสภาษาเป็น RTL หรือไม่ |
| `isCurrentLocaleRtl({context})` | `bool` | ตรวจสอบว่า locale ปัจจุบันเป็น RTL หรือไม่ |
| `getTextDirection(languageCode)` | `TextDirection` | ดึง TextDirection สำหรับภาษา |
| `getCurrentTextDirection({context})` | `TextDirection` | ดึง TextDirection สำหรับ locale ปัจจุบัน |
| `toLocale(languageCode, [countryCode])` | `Locale` | สร้าง Locale จากสตริง |

ค่าคงที่ `rtlLanguages` ประกอบด้วย: `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`

<div id="changing-language-from-controller"></div>

## เปลี่ยนภาษาจาก Controller

หากคุณใช้ controllers กับหน้าของคุณ คุณสามารถเปลี่ยนภาษาจาก `NyController`:

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

พารามิเตอร์ `restartState` ควบคุมว่าแอปจะรีสตาร์ทหลังจากเปลี่ยนภาษาหรือไม่ ตั้งค่าเป็น `false` หากคุณต้องการจัดการการอัปเดต UI ด้วยตนเอง
