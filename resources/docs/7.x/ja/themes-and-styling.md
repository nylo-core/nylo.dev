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
  - [カラースタイルの拡張](#extending-color-styles "カラースタイルの拡張")
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

各テーマは `lib/resources/themes/` 以下の独自のサブディレクトリに配置されます：

- ライトテーマ – `lib/resources/themes/light/light_theme.dart`
- ライトカラー – `lib/resources/themes/light/light_theme_colors.dart`
- ダークテーマ – `lib/resources/themes/dark/dark_theme.dart`
- ダークカラー – `lib/resources/themes/dark/dark_theme_colors.dart`

両テーマは `lib/resources/themes/base_theme.dart` の共通ビルダーと、`lib/resources/themes/color_styles.dart` の `ColorStyles` インターフェースを共有します。



<div id="creating-a-theme"></div>

## テーマの作成

アプリに複数のテーマを持たせたい場合、`lib/resources/themes/` 以下に手動でテーマファイルを作成してください。以下の手順では `bright` を例として使用しています。テーマ名はご自身のものに置き換えてください。

**ステップ 1:** `lib/resources/themes/bright/bright_theme.dart` にテーマファイルを作成します:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/base_theme.dart';
import '/resources/themes/color_styles.dart';

ThemeData brightTheme(ColorStyles color) =>
    buildAppTheme(color, brightness: Brightness.light);
```

**ステップ 2:** `lib/resources/themes/bright/bright_theme_colors.dart` にカラーファイルを作成します:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/color_styles.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BrightThemeColors extends ColorStyles {
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFDE7),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFFFBC02D),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  @override
  AppBarColors get appBar => const AppBarColors(
        background: Color(0xFFFBC02D),
        content: Colors.white,
      );

  @override
  BottomTabBarColors get bottomTabBar => const BottomTabBarColors(
        background: Colors.white,
        iconSelected: Color(0xFFFBC02D),
        iconUnselected: Colors.black54,
        labelSelected: Colors.black,
        labelUnselected: Colors.black45,
      );
}
```

**ステップ 3:** `lib/bootstrap/theme.dart` に新しいテーマを登録します。

``` dart
// lib/bootstrap/theme.dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),

  BaseThemeConfig<ColorStyles>(
    id: 'bright_theme',
    theme: brightTheme,
    colors: BrightThemeColors(),
    type: NyThemeType.light,
  ),
];
```

`bright_theme_colors.dart` のカラーはデザインに合わせて変更できます。

<div id="theme-colors"></div>

## テーマカラー

プロジェクトのテーマカラーを管理するには、`lib/resources/themes/light/` と `lib/resources/themes/dark/` ディレクトリを確認してください。それぞれにテーマのカラーファイル（`light_theme_colors.dart` と `dark_theme_colors.dart`）が含まれています。

カラー値は、フレームワークによって定義されたグループ（`general`、`appBar`、`bottomTabBar`）に整理されています。テーマのカラークラスは `ColorStyles` を継承し、各グループのインスタンスを提供します：

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// 汎用カラー
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// アプリバーのカラー
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// ボトムタブバーのカラー
  @override
  BottomTabBarColors get bottomTabBar => const BottomTabBarColors(
        background: Colors.white,
        iconSelected: Colors.blue,
        iconUnselected: Colors.black54,
        labelSelected: Colors.black,
        labelUnselected: Colors.black45,
      );
}
```

<div id="using-colors"></div>

## ウィジェットでのカラーの使用

`nyColorStyle<T>(context)` ヘルパーを使用して、アクティブなテーマのカラーを読み取ります。完全な型付けを行うために、プロジェクトの `ColorStyles` 型を渡します：

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// ウィジェットのビルド内:
final colors = nyColorStyle<ColorStyles>(context);

// アクティブなテーマの背景色
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// 特定のテーマのカラーを読み取る（アクティブなテーマに関わらず）:
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## ベーススタイル

ベーススタイルにより、すべてのテーマを単一のインターフェースで記述できます。{{ config('app.name') }} には `lib/resources/themes/color_styles.dart` が含まれており、これは `light_theme_colors.dart` と `dark_theme_colors.dart` の両方が実装するコントラクトです。

`ColorStyles` はフレームワークの `ThemeColor` を継承し、3 つの事前定義されたカラーグループ（`GeneralColors`、`AppBarColors`、`BottomTabBarColors`）を公開します。ベーステーマビルダー（`lib/resources/themes/base_theme.dart`）は `ThemeData` を構築する際にこれらのグループを読み取るため、これらに追加したカラーは対応するウィジェットに自動的に適用されます。

<br>

ファイル `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// 汎用カラー
  @override
  GeneralColors get general;

  /// アプリバーのカラー
  @override
  AppBarColors get appBar;

  /// ボトムタブバーのカラー
  @override
  BottomTabBarColors get bottomTabBar;
}
```

3 つのグループは以下のフィールドを公開します：

- `GeneralColors` – `background`、`content`、`primaryAccent`、`surface`、`surfaceContent`
- `AppBarColors` – `background`、`content`
- `BottomTabBarColors` – `background`、`iconSelected`、`iconUnselected`、`labelSelected`、`labelUnselected`

これらのデフォルト以外のフィールド（ボタン、アイコン、バッジなど）を追加するには、[カラースタイルの拡張](#extending-color-styles)をご覧ください。

<div id="extending-color-styles"></div>

## カラースタイルの拡張

3 つのデフォルトグループ（`general`、`appBar`、`bottomTabBar`）は出発点であり、制限ではありません。`lib/resources/themes/color_styles.dart` は自由に変更できます。デフォルトの上に新しいカラーグループ（または単一のフィールド）を追加し、各テーマのカラークラスで実装してください。

**1. カスタムカラーグループの定義**

関連するカラーを小さなイミュータブルクラスにまとめます：

``` dart
import 'package:flutter/material.dart';

class IconColors {
  final Color iconBackground;
  final Color iconBackground1;

  const IconColors({
    required this.iconBackground,
    required this.iconBackground1,
  });
}
```

**2. `ColorStyles` に追加**

``` dart
// lib/resources/themes/color_styles.dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  @override
  GeneralColors get general;
  @override
  AppBarColors get appBar;
  @override
  BottomTabBarColors get bottomTabBar;

  // カスタムグループ
  IconColors get icons;
}
```

**3. 各テーマのカラークラスで実装**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...既存のオーバーライド...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

`dark_theme_colors.dart` でも同じ `icons` オーバーライドをダークモードの値で実装してください。

**4. ウィジェットで使用**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## テーマの切り替え

{{ config('app.name') }} はテーマをリアルタイムで切り替える機能をサポートしています。

例えば、ユーザーがボタンをタップして「ダークテーマ」を有効にする場合のテーマ切り替えに対応できます。

以下のようにして対応できます:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
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

{{ config('app.name') }} では、アプリ全体のプライマリフォントの更新が簡単です。`lib/config/design.dart` を開いて `DesignConfig.appFont` を更新してください。

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

リポジトリには <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> ライブラリが含まれているので、すべてのフォントを簡単に使い始めることができます。別の Google フォントに切り替えるには、呼び出しを変更するだけです：

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

詳しくは公式 <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> ライブラリをご覧ください。

カスタムフォントを使用する必要がありますか？このガイドをご確認ください - https://flutter.dev/docs/cookbook/design/fonts

フォントを追加したら、以下の例のように変数を変更してください。

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo はカスタムフォントの例として使用
```

<div id="design"></div>

## デザイン

**lib/config/design.dart** ファイルは、アプリのデザイン要素を管理するために使用されます。すべては `DesignConfig` クラスを通じて公開されます：

`DesignConfig.appFont` にはアプリのフォントが含まれています。

`DesignConfig.logo` はアプリのロゴを表示するために使用されます。

**lib/resources/widgets/logo_widget.dart** を変更してロゴの表示方法をカスタマイズできます。

`DesignConfig.loader` はローダーを表示するために使用されます。{{ config('app.name') }} は一部のヘルパーメソッドでこの変数をデフォルトのローダーウィジェットとして使用します。

**lib/resources/widgets/loader_widget.dart** を変更してローダーの表示方法をカスタマイズできます。

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
