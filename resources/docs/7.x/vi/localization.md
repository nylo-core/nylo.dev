# Localization

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về bản địa hóa")
- [Cấu hình](#configuration "Cấu hình")
- [Thêm tệp bản địa hóa](#adding-localized-files "Thêm tệp bản địa hóa")
- Cơ bản
  - [Bản địa hóa văn bản](#localizing-text "Bản địa hóa văn bản")
    - [Đối số](#arguments "Đối số")
    - [Placeholder StyledText](#styled-text-placeholders "Placeholder StyledText")
  - [Cập nhật ngôn ngữ](#updating-the-locale "Cập nhật ngôn ngữ")
  - [Đặt ngôn ngữ mặc định](#setting-a-default-locale "Đặt ngôn ngữ mặc định")
- Nâng cao
  - [Ngôn ngữ được hỗ trợ](#supported-locales "Ngôn ngữ được hỗ trợ")
  - [Ngôn ngữ dự phòng](#fallback-language "Ngôn ngữ dự phòng")
  - [Hỗ trợ RTL](#rtl-support "Hỗ trợ RTL")
  - [Gỡ lỗi khóa thiếu](#debug-missing-keys "Gỡ lỗi khóa thiếu")
  - [API NyLocalization](#nylocalization-api "API NyLocalization")
  - [NyLocaleHelper](#nylocalehelper "Class tiện ích NyLocaleHelper")
  - [Thay đổi ngôn ngữ từ Controller](#changing-language-from-controller "Thay đổi ngôn ngữ từ Controller")


<div id="introduction"></div>

## Giới thiệu

Bản địa hóa cho phép bạn cung cấp ứng dụng bằng nhiều ngôn ngữ. {{ config('app.name') }} v7 giúp bạn dễ dàng bản địa hóa văn bản bằng các tệp ngôn ngữ JSON.

Đây là một ví dụ nhanh:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**Trong widget của bạn:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## Cấu hình

Bản địa hóa được cấu hình trong `lib/config/localization.dart`:

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

## Thêm tệp bản địa hóa

Thêm các tệp JSON ngôn ngữ vào thư mục `lang/`:

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

### Đăng ký trong pubspec.yaml

Đảm bảo các tệp ngôn ngữ được bao gồm trong `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## Bản địa hóa văn bản

Sử dụng extension `.tr()` hoặc helper `trans()` để dịch chuỗi:

``` dart
// Using the .tr() extension
"welcome".tr()

// Using the trans() helper
trans("welcome")
```

### Khóa lồng nhau

Truy cập khóa JSON lồng nhau bằng ký hiệu dấu chấm:

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

### Đối số

Truyền giá trị động vào bản dịch bằng cú pháp `@{{key}}`:

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

### Placeholder StyledText

Khi sử dụng `StyledText.template` với chuỗi đã bản địa hóa, bạn có thể sử dụng cú pháp `@{{key:text}}`. Điều này giữ **khóa** ổn định trên tất cả ngôn ngữ (để kiểu dáng và handler nhấn luôn khớp), trong khi **văn bản** được dịch theo từng ngôn ngữ.

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

**Trong widget của bạn:**
``` dart
StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(fontWeight: FontWeight.bold),
  },
)
```

Các khóa `lang`, `read`, và `speak` giống nhau trong mọi tệp ngôn ngữ, nên map kiểu dáng hoạt động cho tất cả ngôn ngữ. Văn bản hiển thị sau dấu `:` là những gì người dùng thấy -- "Languages" trong tiếng Anh, "Idiomas" trong tiếng Tây Ban Nha, v.v.

Bạn cũng có thể sử dụng với `onTap`:

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

> **Lưu ý:** Cú pháp `@{{key}}` (với tiền tố `@`) dành cho đối số được thay thế bởi `.tr(arguments:)` tại thời điểm dịch. Cú pháp `@{{key:text}}` (không có `@`) dành cho placeholder `StyledText` được phân tích tại thời điểm render. Không nên nhầm lẫn -- sử dụng `@{{}}` cho giá trị động và `@{{}}` cho các span có kiểu dáng.

<div id="updating-the-locale"></div>

## Cập nhật ngôn ngữ

Thay đổi ngôn ngữ ứng dụng lúc chạy:

``` dart
// Using NyLocalization directly
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Must match your JSON filename (es.json)
);
```

Nếu widget của bạn kế thừa `NyPage`, sử dụng helper `changeLanguage`:

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

## Đặt ngôn ngữ mặc định

Đặt ngôn ngữ mặc định trong tệp `.env`:

``` bash
DEFAULT_LOCALE="en"
```

Hoặc sử dụng ngôn ngữ của thiết bị bằng cách đặt:

``` bash
LOCALE_TYPE="device"
```

Sau khi thay đổi `.env`, tạo lại cấu hình môi trường:

``` bash
metro make:env --force
```

<div id="supported-locales"></div>

## Ngôn ngữ được hỗ trợ

Định nghĩa các ngôn ngữ ứng dụng hỗ trợ trong `LocalizationConfig`:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

Danh sách này được sử dụng bởi `MaterialApp.supportedLocales` của Flutter.

<div id="fallback-language"></div>

## Ngôn ngữ dự phòng

Khi không tìm thấy khóa dịch trong ngôn ngữ đang hoạt động, {{ config('app.name') }} chuyển sang ngôn ngữ dự phòng:

``` dart
static const String fallbackLanguageCode = 'en';
```

Điều này đảm bảo ứng dụng không bao giờ hiển thị khóa thô nếu thiếu bản dịch.

<div id="rtl-support"></div>

## Hỗ trợ RTL

{{ config('app.name') }} v7 bao gồm hỗ trợ dựng sẵn cho ngôn ngữ viết từ phải sang trái (RTL):

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Check if current language is RTL
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // Handle RTL layout
}
```

<div id="debug-missing-keys"></div>

## Gỡ lỗi khóa thiếu

Bật cảnh báo cho các khóa dịch thiếu trong quá trình phát triển:

Trong tệp `.env`:
``` bash
DEBUG_TRANSLATIONS="true"
```

Điều này ghi lại cảnh báo khi `.tr()` không tìm thấy khóa, giúp bạn phát hiện các chuỗi chưa dịch.

<div id="nylocalization-api"></div>

## API NyLocalization

`NyLocalization` là singleton quản lý toàn bộ bản địa hóa. Ngoài phương thức `translate()` cơ bản, nó cung cấp nhiều phương thức bổ sung:

### Kiểm tra bản dịch có tồn tại

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true if the key exists in the current language file

// Also works with nested keys
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### Lấy tất cả khóa dịch

Hữu ích cho gỡ lỗi để xem các khóa nào đã được tải:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### Thay đổi ngôn ngữ mà không khởi động lại

Nếu bạn muốn thay đổi ngôn ngữ một cách im lặng (không khởi động lại ứng dụng):

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

Lệnh này tải tệp ngôn ngữ mới nhưng **không** khởi động lại ứng dụng. Hữu ích khi bạn muốn xử lý cập nhật giao diện thủ công.

### Kiểm tra hướng RTL

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### Truy cập ngôn ngữ hiện tại

``` dart
// Get the current language code
String code = NyLocalization.instance.languageCode;  // e.g., 'en'

// Get the current Locale object
Locale currentLocale = NyLocalization.instance.locale;

// Get Flutter localization delegates (used in MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### Tham chiếu API đầy đủ

| Phương thức / Thuộc tính | Trả về | Mô tả |
|-------------------|---------|-------------|
| `instance` | `NyLocalization` | Thể hiện singleton |
| `translate(key, [arguments])` | `String` | Dịch một khóa với đối số tùy chọn |
| `hasTranslation(key)` | `bool` | Kiểm tra xem khóa dịch có tồn tại không |
| `getAllKeys()` | `List<String>` | Lấy tất cả khóa dịch đã tải |
| `setLanguage(context, {language, restart})` | `Future<void>` | Thay đổi ngôn ngữ, tùy chọn khởi động lại |
| `setLocale({locale})` | `Future<void>` | Thay đổi ngôn ngữ mà không khởi động lại |
| `setDebugMissingKeys(enabled)` | `void` | Bật/tắt ghi log khóa thiếu |
| `isDirectionRTL(context)` | `bool` | Kiểm tra xem hướng hiện tại có phải RTL không |
| `restart(context)` | `void` | Khởi động lại ứng dụng |
| `languageCode` | `String` | Mã ngôn ngữ hiện tại |
| `locale` | `Locale` | Đối tượng Locale hiện tại |
| `delegates` | `Iterable<LocalizationsDelegate>` | Delegate bản địa hóa Flutter |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` là class tiện ích static cho các thao tác ngôn ngữ. Nó cung cấp các phương thức để phát hiện ngôn ngữ hiện tại, kiểm tra hỗ trợ RTL, và tạo đối tượng Locale.

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

### Tham chiếu API đầy đủ

| Phương thức | Trả về | Mô tả |
|--------|---------|-------------|
| `getCurrentLocale({context})` | `Locale` | Lấy ngôn ngữ hệ thống hiện tại |
| `getLanguageCode({context})` | `String` | Lấy mã ngôn ngữ hiện tại |
| `getCountryCode({context})` | `String?` | Lấy mã quốc gia hiện tại |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | Kiểm tra xem ngôn ngữ hiện tại có khớp không |
| `isRtlLanguage(languageCode)` | `bool` | Kiểm tra xem mã ngôn ngữ có phải RTL không |
| `isCurrentLocaleRtl({context})` | `bool` | Kiểm tra xem ngôn ngữ hiện tại có phải RTL không |
| `getTextDirection(languageCode)` | `TextDirection` | Lấy TextDirection cho một ngôn ngữ |
| `getCurrentTextDirection({context})` | `TextDirection` | Lấy TextDirection cho ngôn ngữ hiện tại |
| `toLocale(languageCode, [countryCode])` | `Locale` | Tạo Locale từ chuỗi |

Hằng số `rtlLanguages` chứa: `ar`, `he`, `fa`, `ur`, `yi`, `ps`, `ku`, `sd`, `dv`.

<div id="changing-language-from-controller"></div>

## Thay đổi ngôn ngữ từ Controller

Nếu bạn sử dụng controller với các trang, bạn có thể thay đổi ngôn ngữ từ `NyController`:

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

Tham số `restartState` kiểm soát việc ứng dụng có khởi động lại sau khi thay đổi ngôn ngữ hay không. Đặt thành `false` nếu bạn muốn tự xử lý cập nhật giao diện.
