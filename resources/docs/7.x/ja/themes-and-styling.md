# テーマとスタイリング

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- テーマ
  - [ライト＆ダークテーマ](#light-and-dark-themes "ライト＆ダークテーマ")
  - [テーマの作成](#creating-a-theme "テーマの作成")
- 設定
  - [テーマカラー](#theme-colors "テーマカラー")
  - [カラーの使用](#using-colors "カラーの使用")
  - [ベーススタイル](#base-styles "ベーススタイル")
  - [テーマの切り替え](#switching-theme "テーマの切り替え")
  - [フォント](#fonts "フォント")
  - [デザイン](#design "デザイン")
- [テキストエクステンション](#text-extensions "テキストエクステンション")


<div id="introduction"></div>

## はじめに

テーマを使用してアプリケーションの UI スタイルを管理できます。テーマにより、テキストのフォントサイズ、ボタンの表示方法、アプリケーションの全体的な外観を変更できます。

テーマが初めての方は、Flutter ウェブサイトの例が参考になります。<a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">こちら</a>をご覧ください。

{{ config('app.name') }} には、`ライトモード` と `ダークモード` 用の事前設定されたテーマが含まれています。

デバイスが<b>「ライト/ダーク」</b>モードに入ると、テーマも自動的に更新されます。

<div id="light-and-dark-themes"></div>

## ライト＆ダークテーマ

- ライトテーマ - `lib/resources/themes/light_theme.dart`
- ダークテーマ - `lib/resources/themes/dark_theme.dart`

これらのファイル内に、ThemeData と ThemeStyle が事前定義されています。



<div id="creating-a-theme"></div>

## テーマの作成

アプリに複数のテーマを持たせたい場合、簡単に作成できます。テーマが初めての方は、以下の手順に従ってください。

まず、ターミナルで以下のコマンドを実行します

``` bash
metro make:theme bright_theme
```

<b>注意:</b> **bright_theme** を新しいテーマの名前に置き換えてください。

これにより `/resources/themes/` ディレクトリに新しいテーマが作成され、`/resources/themes/styles/` にテーマカラーファイルも作成されます。

``` dart
// App Themes
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light theme",
    theme: lightTheme,
    colors: LightThemeColors(),
  ),
  BaseThemeConfig<ColorStyles>(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark theme",
    theme: darkTheme,
    colors: DarkThemeColors(),
  ),

  BaseThemeConfig<ColorStyles>( // 新しいテーマが自動的に追加
    id: 'Bright Theme',
    description: "Bright Theme",
    theme: brightTheme,
    colors: BrightThemeColors(),
  ),
];
```

新しいテーマのカラーは **/resources/themes/styles/bright_theme_colors.dart** ファイルで変更できます。

<div id="theme-colors"></div>

## テーマカラー

プロジェクトのテーマカラーを管理するには、`lib/resources/themes/styles` ディレクトリを確認してください。
このディレクトリには light_theme_colors.dart と dark_theme_colors.dart のスタイルカラーが含まれています。

このファイルでは、以下のような内容があります。

``` dart
// 例: ライトテーマのカラー
class LightThemeColors implements ColorStyles {
  // 全般
  @override
  Color get background => const Color(0xFFFFFFFF);

  @override
  Color get content => const Color(0xFF000000);
  @override
  Color get primaryAccent => const Color(0xFF0045a0);

  @override
  Color get surfaceBackground => Colors.white;
  @override
  Color get surfaceContent => Colors.black;

  // アプリバー
  @override
  Color get appBarBackground => Colors.blue;
  @override
  Color get appBarPrimaryContent => Colors.white;

  // ボタン
  @override
  Color get buttonBackground => Colors.blue;
  @override
  Color get buttonContent => Colors.white;

  @override
  Color get buttonSecondaryBackground => const Color(0xff151925);
  @override
  Color get buttonSecondaryContent => Colors.white.withAlpha((255.0 * 0.9).round());

  // ボトムタブバー
  @override
  Color get bottomTabBarBackground => Colors.white;

  // ボトムタブバー - アイコン
  @override
  Color get bottomTabBarIconSelected => Colors.blue;
  @override
  Color get bottomTabBarIconUnselected => Colors.black54;

  // ボトムタブバー - ラベル
  @override
  Color get bottomTabBarLabelUnselected => Colors.black45;
  @override
  Color get bottomTabBarLabelSelected => Colors.black;

  // トースト通知
  @override
  Color get toastNotificationBackground => Colors.white;
}
```

<div id="using-colors"></div>

## ウィジェットでのカラーの使用

``` dart
import 'package:flutter_app/config/theme.dart';
...

// テーマに応じてライト/ダークの背景色を取得
ThemeColor.get(context).background

// "ThemeColor" クラスの使用例
Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeColor.get(context).content // Color - content
  ),
),

// または

Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeConfig.light().colors.content // ライトテーマのカラー - プライマリコンテンツ
  ),
),
```

<div id="base-styles"></div>

## ベーススタイル

ベーススタイルにより、コードの一箇所から様々なウィジェットのカラーをカスタマイズできます。

{{ config('app.name') }} にはプロジェクト用の事前設定されたベーススタイルが `lib/resources/themes/styles/color_styles.dart` にあります。

これらのスタイルは、`light_theme_colors.dart` と `dart_theme_colors.dart` のテーマカラーのインターフェースを提供します。

<br>

ファイル `lib/resources/themes/styles/color_styles.dart`

``` dart
abstract class ColorStyles {

  // 全般
  @override
  Color get background;
  @override
  Color get content;
  @override
  Color get primaryAccent;

  @override
  Color get surfaceBackground;
  @override
  Color get surfaceContent;

  // アプリバー
  @override
  Color get appBarBackground;
  @override
  Color get appBarPrimaryContent;

  @override
  Color get buttonBackground;
  @override
  Color get buttonContent;

  @override
  Color get buttonSecondaryBackground;
  @override
  Color get buttonSecondaryContent;

  // ボトムタブバー
  @override
  Color get bottomTabBarBackground;

  // ボトムタブバー - アイコン
  @override
  Color get bottomTabBarIconSelected;
  @override
  Color get bottomTabBarIconUnselected;

  // ボトムタブバー - ラベル
  @override
  Color get bottomTabBarLabelUnselected;
  @override
  Color get bottomTabBarLabelSelected;

  // トースト通知
  Color get toastNotificationBackground;
}
```

ここにスタイルを追加し、テーマにカラーを実装できます。

<div id="switching-theme"></div>

## テーマの切り替え

{{ config('app.name') }} はテーマをリアルタイムで切り替える機能をサポートしています。

例えば、ユーザーがボタンをタップして「ダークテーマ」を有効にする場合のテーマ切り替えに対応できます。

以下のようにして対応できます:

``` dart
import 'package:nylo_framework/theme/helper/ny_theme.dart';
...

TextButton(onPressed: () {

    // 「ダークテーマ」を使用するように設定
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// または

TextButton(onPressed: () {

    // 「ライトテーマ」を使用するように設定
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## フォント

{{ config('app.name') }} では、アプリ全体のプライマリフォントの更新が簡単です。`lib/config/design.dart` ファイルを開いて以下を更新してください。

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

リポジトリには <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> ライブラリが含まれているので、すべてのフォントを簡単に使い始めることができます。
フォントを別のものに更新するには、以下のようにします:
``` dart
// 旧
// final TextStyle appThemeFont = GoogleFonts.lato();

// 新
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

詳しくは公式 <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> ライブラリをご覧ください。

カスタムフォントを使用する必要がありますか？このガイドをご確認ください - https://flutter.dev/docs/cookbook/design/fonts

フォントを追加したら、以下の例のように変数を変更してください。

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo はカスタムフォントの例として使用
```

<div id="design"></div>

## デザイン

**config/design.dart** ファイルは、アプリのデザイン要素を管理するために使用されます。

`appFont` 変数にはアプリのフォントが含まれています。

`logo` 変数はアプリのロゴを表示するために使用されます。

**resources/widgets/logo_widget.dart** を変更してロゴの表示方法をカスタマイズできます。

`loader` 変数はローダーを表示するために使用されます。{{ config('app.name') }} は一部のヘルパーメソッドでこの変数をデフォルトのローダーウィジェットとして使用します。

**resources/widgets/loader_widget.dart** を変更してローダーの表示方法をカスタマイズできます。

<div id="text-extensions"></div>

## テキストエクステンション

{{ config('app.name') }} で使用できるテキストエクステンションは以下の通りです。

| ルール名 | 使い方 | 情報 |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | **displayLarge** textTheme を適用 |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | **displayMedium** textTheme を適用 |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | **displaySmall** textTheme を適用 |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | **headingLarge** textTheme を適用 |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | **headingMedium** textTheme を適用 |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | **headingSmall** textTheme を適用 |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | **titleLarge** textTheme を適用 |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | **titleMedium** textTheme を適用 |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | **titleSmall** textTheme を適用 |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | **bodyLarge** textTheme を適用 |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | **bodyMedium** textTheme を適用 |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | **bodySmall** textTheme を適用 |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | **labelLarge** textTheme を適用 |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | **labelMedium** textTheme を適用 |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | **labelSmall** textTheme を適用 |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | Text ウィジェットにボールドフォントウェイトを適用 |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | Text ウィジェットにライトフォントウェイトを適用 |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | Text ウィジェットに別のテキストカラーを設定 |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | フォントを左揃えに |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | フォントを右揃えに |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | フォントを中央揃えに |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | テキストウィジェットの最大行数を設定 |

<br>


<div id="text-extension-display-large"></div>

#### Display large

``` dart
Text("Hello World").displayLarge()
```

<div id="text-extension-display-medium"></div>

#### Display medium

``` dart
Text("Hello World").displayMedium()
```

<div id="text-extension-display-small"></div>

#### Display small

``` dart
Text("Hello World").displaySmall()
```

<div id="text-extension-heading-large"></div>

#### Heading large

``` dart
Text("Hello World").headingLarge()
```

<div id="text-extension-heading-medium"></div>

#### Heading medium

``` dart
Text("Hello World").headingMedium()
```

<div id="text-extension-heading-small"></div>

#### Heading small

``` dart
Text("Hello World").headingSmall()
```

<div id="text-extension-title-large"></div>

#### Title large

``` dart
Text("Hello World").titleLarge()
```

<div id="text-extension-title-medium"></div>

#### Title medium

``` dart
Text("Hello World").titleMedium()
```

<div id="text-extension-title-small"></div>

#### Title small

``` dart
Text("Hello World").titleSmall()
```

<div id="text-extension-body-large"></div>

#### Body large

``` dart
Text("Hello World").bodyLarge()
```

<div id="text-extension-body-medium"></div>

#### Body medium

``` dart
Text("Hello World").bodyMedium()
```

<div id="text-extension-body-small"></div>

#### Body small

``` dart
Text("Hello World").bodySmall()
```

<div id="text-extension-label-large"></div>

#### Label large

``` dart
Text("Hello World").labelLarge()
```

<div id="text-extension-label-medium"></div>

#### Label medium

``` dart
Text("Hello World").labelMedium()
```

<div id="text-extension-label-small"></div>

#### Label small

``` dart
Text("Hello World").labelSmall()
```

<div id="text-extension-font-weight-bold"></div>

#### Font weight bold

``` dart
Text("Hello World").fontWeightBold()
```

<div id="text-extension-font-weight-light"></div>

#### Font weight light

``` dart
Text("Hello World").fontWeightLight()
```

<div id="text-extension-set-color"></div>

#### Set color

``` dart
Text("Hello World").setColor(context, (color) => colors.content)
// colorStyles からのカラー
```

<div id="text-extension-align-left"></div>

#### Align left

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>

#### Align right

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>

#### Align center

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>

#### Set max lines

``` dart
Text("Hello World").setMaxLines(5)
```
