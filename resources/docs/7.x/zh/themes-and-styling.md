# 主题与样式

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- 主题
  - [明暗主题](#light-and-dark-themes "明暗主题")
  - [创建主题](#creating-a-theme "创建主题")
- 配置
  - [主题颜色](#theme-colors "主题颜色")
  - [使用颜色](#using-colors "使用颜色")
  - [基础样式](#base-styles "基础样式")
  - [切换主题](#switching-theme "切换主题")
  - [字体](#fonts "字体")
  - [设计](#design "设计")
- [文本扩展](#text-extensions "文本扩展")


<div id="introduction"></div>

## 简介

您可以使用主题来管理应用的 UI 样式。主题允许我们更改例如文本的字体大小、按钮的显示方式以及应用的整体外观。

如果您是主题新手，Flutter 官方网站上的示例将帮助您入门，请参阅<a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">这里</a>。

{{ config('app.name') }} 开箱即用地包含了`明亮模式`和`暗黑模式`的预配置主题。

当设备进入<b>"明亮/暗黑"</b>模式时，主题也会自动更新。

<div id="light-and-dark-themes"></div>

## 明暗主题

- 明亮主题 - `lib/resources/themes/light_theme.dart`
- 暗黑主题 - `lib/resources/themes/dark_theme.dart`

在这些文件中，您将找到预定义的 ThemeData 和 ThemeStyle。



<div id="creating-a-theme"></div>

## 创建主题

如果您想为应用设置多个主题，我们为您提供了一种简单的方式。如果您是主题新手，请跟随操作。

首先，在终端运行以下命令

``` bash
metro make:theme bright_theme
```

<b>注意：</b>将 **bright_theme** 替换为您新主题的名称。

这将在 `/resources/themes/` 目录中创建一个新主题，同时在 `/resources/themes/styles/` 中创建一个主题颜色文件。

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

  BaseThemeConfig<ColorStyles>( // new theme automatically added
    id: 'Bright Theme',
    description: "Bright Theme",
    theme: brightTheme,
    colors: BrightThemeColors(),
  ),
];
```

您可以在 **/resources/themes/styles/bright_theme_colors.dart** 文件中修改新主题的颜色。

<div id="theme-colors"></div>

## 主题颜色

要管理项目中的主题颜色，请查看 `lib/resources/themes/styles` 目录。
该目录包含 light_theme_colors.dart 和 dark_theme_colors.dart 的样式颜色。

在该文件中，您应该看到类似以下的内容。

``` dart
// e.g Light Theme colors
class LightThemeColors implements ColorStyles {
  // general
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

  // app bar
  @override
  Color get appBarBackground => Colors.blue;
  @override
  Color get appBarPrimaryContent => Colors.white;

  // buttons
  @override
  Color get buttonBackground => Colors.blue;
  @override
  Color get buttonContent => Colors.white;

  @override
  Color get buttonSecondaryBackground => const Color(0xff151925);
  @override
  Color get buttonSecondaryContent => Colors.white.withAlpha((255.0 * 0.9).round());

  // bottom tab bar
  @override
  Color get bottomTabBarBackground => Colors.white;

  // bottom tab bar - icons
  @override
  Color get bottomTabBarIconSelected => Colors.blue;
  @override
  Color get bottomTabBarIconUnselected => Colors.black54;

  // bottom tab bar - label
  @override
  Color get bottomTabBarLabelUnselected => Colors.black45;
  @override
  Color get bottomTabBarLabelSelected => Colors.black;

  // toast notification
  @override
  Color get toastNotificationBackground => Colors.white;
}
```

<div id="using-colors"></div>

## 在组件中使用颜色

``` dart
import 'package:flutter_app/config/theme.dart';
...

// gets the light/dark background colour depending on the theme
ThemeColor.get(context).background

// e.g. of using the "ThemeColor" class
Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeColor.get(context).content // Color - content
  ),
),

// or

Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeConfig.light().colors.content // Light theme colors - primary content
  ),
),
```

<div id="base-styles"></div>

## 基础样式

基础样式允许您从代码中的一个位置自定义各种组件颜色。

{{ config('app.name') }} 附带了预配置的基础样式，位于 `lib/resources/themes/styles/color_styles.dart`。

这些样式为 `light_theme_colors.dart` 和 `dart_theme_colors.dart` 中的主题颜色提供接口。

<br>

文件 `lib/resources/themes/styles/color_styles.dart`

``` dart
abstract class ColorStyles {

  // general
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

  // app bar
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

  // bottom tab bar
  @override
  Color get bottomTabBarBackground;

  // bottom tab bar - icons
  @override
  Color get bottomTabBarIconSelected;
  @override
  Color get bottomTabBarIconUnselected;

  // bottom tab bar - label
  @override
  Color get bottomTabBarLabelUnselected;
  @override
  Color get bottomTabBarLabelSelected;

  // toast notification
  Color get toastNotificationBackground;
}
```

您可以在此处添加额外的样式，然后在主题中实现颜色。

<div id="switching-theme"></div>

## 切换主题

{{ config('app.name') }} 支持动态切换主题的功能。

例如，如果您需要在用户点击按钮激活"暗黑主题"时切换主题。

您可以通过以下方式实现：

``` dart
import 'package:nylo_framework/theme/helper/ny_theme.dart';
...

TextButton(onPressed: () {

    // set theme to use the "dark theme"
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// or

TextButton(onPressed: () {

    // set theme to use the "light theme"
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## 字体

在 {{ config('app.name') }} 中，更新整个应用的主要字体很简单。打开 `lib/config/design.dart` 文件并更新以下内容。

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

我们在仓库中包含了 <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> 库，因此您可以轻松开始使用所有字体。
要更新为其他字体，您可以执行以下操作：
``` dart
// OLD
// final TextStyle appThemeFont = GoogleFonts.lato();

// NEW
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

查看官方 <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> 库上的字体以了解更多信息

需要使用自定义字体？请查看此指南 - https://flutter.dev/docs/cookbook/design/fonts

添加字体后，像下面的示例一样更改变量。

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## 设计

**config/design.dart** 文件用于管理应用的设计元素。

`appFont` 变量包含应用的字体。

`logo` 变量用于显示应用的 Logo。

您可以修改 **resources/widgets/logo_widget.dart** 来自定义 Logo 的显示方式。

`loader` 变量用于显示加载器。{{ config('app.name') }} 将在某些辅助方法中使用此变量作为默认加载器组件。

您可以修改 **resources/widgets/loader_widget.dart** 来自定义加载器的显示方式。

<div id="text-extensions"></div>

## 文本扩展

以下是在 {{ config('app.name') }} 中可用的文本扩展。

| 规则名称   | 用法 | 信息 |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | 应用 **displayLarge** textTheme |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | 应用 **displayMedium** textTheme |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | 应用 **displaySmall** textTheme |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | 应用 **headingLarge** textTheme |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | 应用 **headingMedium** textTheme |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | 应用 **headingSmall** textTheme |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | 应用 **titleLarge** textTheme |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | 应用 **titleMedium** textTheme |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | 应用 **titleSmall** textTheme |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | 应用 **bodyLarge** textTheme |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | 应用 **bodyMedium** textTheme |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | 应用 **bodySmall** textTheme |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | 应用 **labelLarge** textTheme |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | 应用 **labelMedium** textTheme |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | 应用 **labelSmall** textTheme |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | 对 Text 组件应用粗体字重 |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | 对 Text 组件应用细体字重 |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | 在 Text 组件上设置不同的文本颜色 |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | 将字体左对齐 |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | 将字体右对齐 |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | 将字体居中对齐 |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | 设置文本组件的最大行数 |

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

#### 字重加粗

``` dart
Text("Hello World").fontWeightBold()
```

<div id="text-extension-font-weight-light"></div>

#### 字重细体

``` dart
Text("Hello World").fontWeightLight()
```

<div id="text-extension-set-color"></div>

#### 设置颜色

``` dart
Text("Hello World").setColor(context, (color) => colors.content)
// Color from your colorStyles
```

<div id="text-extension-align-left"></div>

#### 左对齐

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>

#### 右对齐

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>

#### 居中对齐

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>

#### 设置最大行数

``` dart
Text("Hello World").setMaxLines(5)
```
