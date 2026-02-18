# ローカリゼーション

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [設定](#configuration "設定")
- [ローカライズファイルの追加](#adding-localized-files "ローカライズファイルの追加")
- 基本
  - [テキストのローカライズ](#localizing-text "テキストのローカライズ")
    - [引数](#arguments "引数")
    - [StyledText プレースホルダー](#styled-text-placeholders "StyledText プレースホルダー")
  - [ロケールの更新](#updating-the-locale "ロケールの更新")
  - [デフォルトロケールの設定](#setting-a-default-locale "デフォルトロケールの設定")
- 応用
  - [サポートされるロケール](#supported-locales "サポートされるロケール")
  - [フォールバック言語](#fallback-language "フォールバック言語")
  - [RTL サポート](#rtl-support "RTL サポート")
  - [欠落キーのデバッグ](#debug-missing-keys "欠落キーのデバッグ")
  - [NyLocalization API](#nylocalization-api "NyLocalization API")
  - [NyLocaleHelper](#nylocalehelper "NyLocaleHelper")
  - [コントローラからの言語変更](#changing-language-from-controller "コントローラからの言語変更")


<div id="introduction"></div>

## はじめに

ローカリゼーションにより、アプリを複数の言語で提供できます。{{ config('app.name') }} v7 は JSON 言語ファイルを使用してテキストを簡単にローカライズできます。

簡単な例:

**lang/en.json**
``` json
{
  "welcome": "Welcome",
  "greeting": "Hello @{{name}}"
}
```

**ウィジェット内:**
``` dart
Text("welcome".tr())              // "Welcome"
Text("greeting".tr(arguments: {"name": "Anthony"}))  // "Hello Anthony"
```

<div id="configuration"></div>

## 設定

ローカリゼーションは `lib/config/localization.dart` で設定します:

``` dart
final class LocalizationConfig {
  // デフォルト言語コード（JSON ファイルに対応、例: 'en' は lang/en.json）
  static final String languageCode =
      getEnv('DEFAULT_LOCALE', defaultValue: "en");

  // LocaleType.device - デバイスの言語設定を使用
  // LocaleType.asDefined - 上記の languageCode を使用
  static final LocaleType localeType =
      getEnv('LOCALE_TYPE', defaultValue: 'asDefined') == 'device'
          ? LocaleType.device
          : LocaleType.asDefined;

  // 言語 JSON ファイルを含むディレクトリ
  static const String assetsDirectory = 'lang/';

  // サポートされるロケールのリスト
  static const List<Locale> supportedLocales = [
    Locale('en'),
    Locale('es'),
    // 必要に応じてロケールを追加
  ];

  // アクティブなロケールでキーが見つからない場合のフォールバック
  static const String fallbackLanguageCode = 'en';

  // RTL 言語コード
  static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

  // 欠落した翻訳キーの警告をログ出力
  static final bool debugMissingKeys =
      getEnv('DEBUG_TRANSLATIONS', defaultValue: 'false') == 'true';
}
```

<div id="adding-localized-files"></div>

## ローカライズファイルの追加

`lang/` ディレクトリに言語 JSON ファイルを追加します:

```
lang/
├── en.json   # 英語
├── es.json   # スペイン語
├── fr.json   # フランス語
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

### pubspec.yaml への登録

`pubspec.yaml` に言語ファイルが含まれていることを確認してください:

``` yaml
flutter:
  assets:
    - lang/
```

<div id="localizing-text"></div>

## テキストのローカライズ

`.tr()` エクステンションまたは `trans()` ヘルパーを使用して文字列を翻訳します:

``` dart
// .tr() エクステンションの使用
"welcome".tr()

// trans() ヘルパーの使用
trans("welcome")
```

### ネストされたキー

ドット記法を使用してネストされた JSON キーにアクセスします:

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

### 引数

`@{{key}}` 構文を使用して翻訳に動的な値を渡します:

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

### StyledText プレースホルダー

`StyledText.template` でローカライズされた文字列を使用する場合、`@{{key:text}}` 構文を使用できます。これにより**キー**はすべてのロケールで安定した状態を保ち（スタイルとタップハンドラーが常に一致するように）、**テキスト**はロケールごとに翻訳されます。

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

**ウィジェット内:**
``` dart
StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(fontWeight: FontWeight.bold),
  },
)
```

キー `lang`、`read`、`speak` はすべてのロケールファイルで同じなので、スタイルマップはすべての言語で機能します。`:` の後の表示テキストはユーザーが目にするもの -- 英語では "Languages"、スペイン語では "Idiomas" などです。

`onTap` でも使用できます:

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

> **注意:** `@{{key}}` 構文（`@` プレフィックス付き）は翻訳時に `.tr(arguments:)` で置換される引数用です。`@{{key:text}}` 構文（`@` なし）はレンダリング時に解析される `StyledText` プレースホルダー用です。混同しないでください -- 動的な値には `@{{}}` を、スタイル付きスパンには `@{{}}` を使用してください。

<div id="updating-the-locale"></div>

## ロケールの更新

実行時にアプリの言語を変更します:

``` dart
// NyLocalization を直接使用
await NyLocalization.instance.setLanguage(
  context,
  language: 'es'  // JSON ファイル名と一致する必要があります（es.json）
);
```

ウィジェットが `NyPage` を継承している場合は、`changeLanguage` ヘルパーを使用します:

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

## デフォルトロケールの設定

`.env` ファイルでデフォルト言語を設定します:

``` bash
DEFAULT_LOCALE="en"
```

またはデバイスのロケールを使用するには:

``` bash
LOCALE_TYPE="device"
```

`.env` を変更した後、環境設定を再生成します:

``` bash
metro make:env --force
```

<div id="supported-locales"></div>

## サポートされるロケール

`LocalizationConfig` でアプリがサポートするロケールを定義します:

``` dart
static const List<Locale> supportedLocales = [
  Locale('en'),
  Locale('es'),
  Locale('fr'),
  Locale('de'),
  Locale('ar'),
];
```

このリストは Flutter の `MaterialApp.supportedLocales` で使用されます。

<div id="fallback-language"></div>

## フォールバック言語

アクティブなロケールで翻訳キーが見つからない場合、{{ config('app.name') }} は指定された言語にフォールバックします:

``` dart
static const String fallbackLanguageCode = 'en';
```

これにより、翻訳が欠落している場合でもアプリが生のキーを表示することはありません。

<div id="rtl-support"></div>

## RTL サポート

{{ config('app.name') }} v7 には右から左へ（RTL）の言語の組み込みサポートが含まれています:

``` dart
static const List<String> rtlLanguages = ['ar', 'he', 'fa', 'ur'];

// 現在の言語が RTL かどうかを確認
if (LocalizationConfig.isRtl(currentLanguageCode)) {
  // RTL レイアウトを処理
}
```

<div id="debug-missing-keys"></div>

## 欠落キーのデバッグ

開発中に欠落した翻訳キーの警告を有効にします:

`.env` ファイルで:
``` bash
DEBUG_TRANSLATIONS="true"
```

`.tr()` がキーを見つけられない場合に警告がログ出力され、未翻訳の文字列を検出するのに役立ちます。

<div id="nylocalization-api"></div>

## NyLocalization API

`NyLocalization` はすべてのローカリゼーションを管理するシングルトンです。基本的な `translate()` メソッドに加えて、いくつかの追加メソッドを提供します:

### 翻訳が存在するか確認

``` dart
bool exists = NyLocalization.instance.hasTranslation('welcome');
// 現在の言語ファイルにキーが存在する場合 true

// ネストされたキーでも動作
bool nestedExists = NyLocalization.instance.hasTranslation('navigation.home');
```

### すべての翻訳キーを取得

デバッグに便利で、読み込まれたキーを確認できます:

``` dart
List<String> keys = NyLocalization.instance.getAllKeys();
// ['welcome', 'settings', 'navigation', ...]
```

### 再起動なしでロケールを変更

アプリを再起動せずにロケールをサイレントに変更したい場合:

``` dart
await NyLocalization.instance.setLocale(locale: Locale('fr'));
```

新しい言語ファイルを読み込みますが、アプリの再起動は**行いません**。UI の更新を手動で処理したい場合に便利です。

### RTL 方向の確認

``` dart
bool isRtl = NyLocalization.instance.isDirectionRTL(context);
```

### 現在のロケールにアクセス

``` dart
// 現在の言語コードを取得
String code = NyLocalization.instance.languageCode;  // 例: 'en'

// 現在の Locale オブジェクトを取得
Locale currentLocale = NyLocalization.instance.locale;

// Flutter ローカリゼーションデリゲートを取得（MaterialApp で使用）
var delegates = NyLocalization.instance.delegates;
```

### 完全な API リファレンス

| メソッド / プロパティ | 戻り値 | 説明 |
|-------------------|---------|-------------|
| `instance` | `NyLocalization` | シングルトンインスタンス |
| `translate(key, [arguments])` | `String` | オプションの引数付きでキーを翻訳 |
| `hasTranslation(key)` | `bool` | 翻訳キーが存在するか確認 |
| `getAllKeys()` | `List<String>` | 読み込まれたすべての翻訳キーを取得 |
| `setLanguage(context, {language, restart})` | `Future<void>` | 言語を変更、オプションで再起動 |
| `setLocale({locale})` | `Future<void>` | 再起動なしでロケールを変更 |
| `setDebugMissingKeys(enabled)` | `void` | 欠落キーのログ出力を有効/無効 |
| `isDirectionRTL(context)` | `bool` | 現在の方向が RTL かどうか確認 |
| `restart(context)` | `void` | アプリを再起動 |
| `languageCode` | `String` | 現在の言語コード |
| `locale` | `Locale` | 現在の Locale オブジェクト |
| `delegates` | `Iterable<LocalizationsDelegate>` | Flutter ローカリゼーションデリゲート |

<div id="nylocalehelper"></div>

## NyLocaleHelper

`NyLocaleHelper` はロケール操作用の静的ユーティリティクラスです。現在のロケールの検出、RTL サポートの確認、Locale オブジェクトの作成のためのメソッドを提供します。

``` dart
// 現在のシステムロケールを取得
Locale locale = NyLocaleHelper.getCurrentLocale(context: context);

// 言語コードと国コードを取得
String langCode = NyLocaleHelper.getLanguageCode(context: context);  // 'en'
String? countryCode = NyLocaleHelper.getCountryCode(context: context);  // 'US' または null

// 現在のロケールが一致するか確認
bool isEnglish = NyLocaleHelper.matchesLocale(context, 'en');
bool isUsEnglish = NyLocaleHelper.matchesLocale(context, 'en', 'US');

// RTL 検出
bool isRtl = NyLocaleHelper.isRtlLanguage('ar');  // true
bool currentIsRtl = NyLocaleHelper.isCurrentLocaleRtl(context: context);

// テキスト方向を取得
TextDirection direction = NyLocaleHelper.getTextDirection('ar');  // TextDirection.rtl
TextDirection currentDir = NyLocaleHelper.getCurrentTextDirection(context: context);

// 文字列から Locale を作成
Locale newLocale = NyLocaleHelper.toLocale('en', 'US');
```

### 完全な API リファレンス

| メソッド | 戻り値 | 説明 |
|--------|---------|-------------|
| `getCurrentLocale({context})` | `Locale` | 現在のシステムロケールを取得 |
| `getLanguageCode({context})` | `String` | 現在の言語コードを取得 |
| `getCountryCode({context})` | `String?` | 現在の国コードを取得 |
| `matchesLocale(context, languageCode, [countryCode])` | `bool` | 現在のロケールが一致するか確認 |
| `isRtlLanguage(languageCode)` | `bool` | 言語コードが RTL かどうか確認 |
| `isCurrentLocaleRtl({context})` | `bool` | 現在のロケールが RTL かどうか確認 |
| `getTextDirection(languageCode)` | `TextDirection` | 言語の TextDirection を取得 |
| `getCurrentTextDirection({context})` | `TextDirection` | 現在のロケールの TextDirection を取得 |
| `toLocale(languageCode, [countryCode])` | `Locale` | 文字列から Locale を作成 |

`rtlLanguages` 定数には `ar`、`he`、`fa`、`ur`、`yi`、`ps`、`ku`、`sd`、`dv` が含まれます。

<div id="changing-language-from-controller"></div>

## コントローラからの言語変更

ページにコントローラを使用している場合、`NyController` から言語を変更できます:

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

`restartState` パラメータは、言語変更後にアプリを再起動するかどうかを制御します。UI の更新を自分で処理したい場合は `false` に設定してください。
