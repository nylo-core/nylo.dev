# 本地化

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [配置](#configuration "配置")
- [添加本地化文件](#adding-localized-files "添加本地化文件")
- 基础
  - [文本本地化](#localizing-text "文本本地化")
    - [参数](#arguments "参数")
    - [StyledText 占位符](#styled-text-placeholders "StyledText 占位符")
  - [更新区域设置](#updating-the-locale "更新区域设置")
  - [设置默认区域设置](#setting-a-default-locale "设置默认区域设置")
- 高级
  - [支持的区域设置](#supported-locales "支持的区域设置")
  - [备用语言](#fallback-language "备用语言")
  - [RTL 支持](#rtl-support "RTL 支持")
  - [调试缺失键](#debug-missing-keys "调试缺失键")
  - [NyLocalization API](#nylocalization-api "NyLocalization API")
  - [NyLocaleHelper](#nylocalehelper "NyLocaleHelper")
  - [从控制器更改语言](#changing-language-from-controller "从控制器更改语言")


<div id="introduction"></div>

## 简介

本地化允许您以多种语言提供应用。{{ config('app.name') }} v7 使用 JSON 语言文件简化了文本本地化。

以下是一个简单示例：

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**在您的组件中：**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## 配置

本地化在 `lib/config/localization.dart` 中配置：

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

## 添加本地化文件

将语言 JSON 文件添加到 `lang/` 目录：

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

### 在 pubspec.yaml 中注册

确保您的语言文件包含在 `pubspec.yaml` 中：

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## 文本本地化

使用 `.tr()` 扩展或 `trans()` 辅助函数来翻译字符串：

``` dart
// Using the .tr() extension
"welcome".tr()

// Using the trans() helper
trans("welcome")
```

### 嵌套键

使用点符号访问嵌套的 JSON 键：

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

### 参数

使用 `@{{key}}` 语法将动态值传递到翻译中：

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

### StyledText 占位符

当您将 `StyledText.template` 与本地化字符串一起使用时，可以使用 `@{{key:text}}` 语法。这使 **key** 在所有区域设置中保持稳定（因此您的样式和点击处理程序始终匹配），而 **text** 则按区域设置进行翻译。

**lang/zh.json**
``` json
{
  "learn_skills": "学习 @{{lang:语言}}、@{{read:阅读}} 和 @{{speak:口语}} 技能",
  "already_have_account": "已有账户？@{{login:登录}}"
}
```

**lang/es.json**
``` json
{
  "learn_skills": "Aprende @{{lang:Idiomas}}, @{{read:Lectura}} y @{{speak:Habla}}",
  "already_have_account": "¿Ya tienes una cuenta? @{{login:Iniciar sesión}}"
}
```

**在您的组件中：**
``` dart
StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(fontWeight: FontWeight.bold),
  },
)
```

键 `lang`、`read` 和 `speak` 在每个区域文件中都相同，因此样式映射适用于所有语言。`:` 后面显示的文本是用户看到的内容 — 中文中是"语言"，西班牙语中是"Idiomas"，等等。

您还可以将此与 `onTap` 一起使用：

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

> **注意：** `@{{key}}` 语法（带 `@` 前缀）用于在翻译时由 `.tr(arguments:)` 替换的参数。`@{{key:text}}` 语法（不带 `@`）用于在渲染时解析的 `StyledText` 占位符。不要混淆它们 — 使用 `@{{}}` 表示动态值，使用 `@{{}}` 表示样式化区间。

<div id="updating-the-locale"></div>

## 更新区域设置

在运行时更改应用的语言：

``` dart
// Using NyLocalization directly
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // Must match your JSON filename (es.json)
);
```

如果您的组件继承 `NyPage`，请使用 `changeLanguage` 辅助函数：

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

## 设置默认区域设置

在您的 `.env` 文件中设置默认语言：

``` bash
DEFAULT_LOCALE="en"
```

或者通过设置以下内容使用设备的区域设置：

``` bash
LOCALE_TYPE="device"
```

更改 `.env` 后，重新生成环境配置：

``` bash
metro make:env --force
```

<div id="supported-locales"></div>

## 支持的区域设置

在 `LocalizationConfig` 中定义您的应用支持的区域设置：

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

此列表由 Flutter 的 `MaterialApp.supportedLocales` 使用。

<div id="fallback-language"></div>

## 备用语言

当在当前区域中找不到翻译键时，{{ config('app.name') }} 会回退到指定的语言：

``` dart
static const String fallbackLanguageCode = 'en';
```

这确保您的应用在翻译缺失时永远不会显示原始键。

<div id="rtl-support"></div>

## RTL 支持

{{ config('app.name') }} v7 包含对从右到左（RTL）语言的内置支持：

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// Check if current language is RTL
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // Handle RTL layout
}
```

<div id="debug-missing-keys"></div>

## 调试缺失键

在开发期间启用缺失翻译键的警告：

在您的 `.env` 文件中：
``` bash
DEBUG_TRANSLATIONS="true"
```

当 `.tr()` 找不到键时会记录警告，帮助您捕获未翻译的字符串。

<div id="nylocalization-api"></div>

## NyLocalization API

`NyLocalization` 是管理所有本地化的单例。除了基本的 `translate()` 方法外，它还提供了几个额外的方法：

### 检查翻译是否存在

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// true if the key exists in the current language file

// Also works with nested keys
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### 获取所有翻译键

对于调试以查看加载了哪些键很有用：

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### 不重启更改区域设置

如果您想静默更改区域设置（不重启应用）：

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

这会加载新的语言文件但**不会**重启应用。当您想手动处理 UI 更新时很有用。

### 检查 RTL 方向

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### 访问当前区域设置

``` dart
// Get the current language code
String code = NyLocalization.instance.languageCode;  // e.g., 'en'

// Get the current Locale object
Locale currentLocale = NyLocalization.instance.locale;

// Get Flutter localization delegates (used in MaterialApp)
var delegates = NyLocalization.instance.delegates;
```

### 完整 API 参考

| 方法/属性 | 返回值 | 描述 |
|-------------------|---------|-------------|
| `instance` | `NyLocalization` | 单例实例 |
| `translate(key, [arguments])` | `String` | 翻译键，可选参数 |
| `hasTranslation(key)` | `bool` | 检查翻译键是否存在 |
| `getAllKeys()` | `List<String>` | 获取所有已加载的翻译键 |
| `setLanguage(context, {language, restart})` | `Future<void>` | 更改语言，可选重启 |
| `setLocale({locale})` | `Future<void>` | 不重启更改区域设置 |
| `setDebugMissingKeys(enabled)` | `void` | 启用/禁用缺失键日志 |
| `isDirectionRTL(context)` | `bool` | 检查当前方向是否为 RTL |
| `restart(context)` | `void` | 重启应用 |
| `languageCode` | `String` | 当前语言代码 |
| `locale` | `Locale` | 当前 Locale 对象 |
| `delegates` | `Iterable<LocalizationsDelegate>` | Flutter 本地化代理 |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` 是一个用于区域操作的静态工具类。它提供了检测当前区域、检查 RTL 支持和创建 Locale 对象的方法。

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

### 完整 API 参考

| 方法 | 返回值 | 描述 |
|--------|---------|-------------|
| `getCurrentLocale({context})` | `Locale` | 获取当前系统区域设置 |
| `getLanguageCode({context})` | `String` | 获取当前语言代码 |
| `getCountryCode({context})` | `String?` | 获取当前国家代码 |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | 检查当前区域是否匹配 |
| `isRtlLanguage(languageCode)` | `bool` | 检查语言代码是否为 RTL |
| `isCurrentLocaleRtl({context})` | `bool` | 检查当前区域是否为 RTL |
| `getTextDirection(languageCode)` | `TextDirection` | 获取语言的 TextDirection |
| `getCurrentTextDirection({context})` | `TextDirection` | 获取当前区域的 TextDirection |
| `toLocale(languageCode, [countryCode])` | `Locale` | 从字符串创建 Locale |

`rtlLanguages` 常量包含：`ar`、`he`、`fa`、`ur`、`yi`、`ps`、`ku`、`sd`、`dv`。

<div id="changing-language-from-controller"></div>

## 从控制器更改语言

如果您在页面中使用控制器，可以从 `NyController` 更改语言：

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

`restartState` 参数控制更改语言后是否重启应用。如果您想自己处理 UI 更新，将其设置为 `false`。
