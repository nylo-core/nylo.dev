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
  - [扩展颜色样式](#extending-color-styles "扩展颜色样式")
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

每个主题位于 `lib/resources/themes/` 下各自的子目录中：

- 明亮主题 – `lib/resources/themes/light/light_theme.dart`
- 明亮颜色 – `lib/resources/themes/light/light_theme_colors.dart`
- 暗黑主题 – `lib/resources/themes/dark/dark_theme.dart`
- 暗黑颜色 – `lib/resources/themes/dark/dark_theme_colors.dart`

两个主题共享 `lib/resources/themes/base_theme.dart` 的公共构建器以及 `lib/resources/themes/color_styles.dart` 的 `ColorStyles` 接口。



<div id="creating-a-theme"></div>

## 创建主题

如果您想为应用设置多个主题，请在 `lib/resources/themes/` 下手动创建主题文件。以下步骤以 `bright` 为例——请替换为您的主题名称。

**Step 1:** 在 `lib/resources/themes/bright/bright_theme.dart` 创建主题文件：

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/base_theme.dart';
import '/resources/themes/color_styles.dart';

ThemeData brightTheme(ColorStyles color) =>
    buildAppTheme(color, brightness: Brightness.light);
```

**Step 2:** 在 `lib/resources/themes/bright/bright_theme_colors.dart` 创建颜色文件：

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

**Step 3:** 在 `lib/bootstrap/theme.dart` 中注册新主题。

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

您可以调整 `bright_theme_colors.dart` 中的颜色以匹配您的设计。

<div id="theme-colors"></div>

## 主题颜色

要管理项目中的主题颜色，请查看 `lib/resources/themes/light/` 和 `lib/resources/themes/dark/` 目录。每个目录包含其主题的颜色文件——`light_theme_colors.dart` 和 `dark_theme_colors.dart`。

颜色值按框架定义的组（`general`、`appBar`、`bottomTabBar`）进行组织。主题的颜色类扩展 `ColorStyles` 并提供每个组的实例：

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// 通用颜色
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// 应用栏颜色
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// 底部标签栏颜色
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

## 在组件中使用颜色

使用 `nyColorStyle<T>(context)` 帮助函数读取活动主题的颜色。传入项目的 `ColorStyles` 类型以实现完整的类型推导：

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// 在 widget 构建内部:
final colors = nyColorStyle<ColorStyles>(context);

// 活动主题的背景颜色
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// 读取特定主题的颜色（无论哪个主题处于活动状态）:
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## 基础样式

基础样式允许您通过单一接口描述所有主题。{{ config('app.name') }} 附带 `lib/resources/themes/color_styles.dart`，这是 `light_theme_colors.dart` 和 `dark_theme_colors.dart` 都实现的契约。

`ColorStyles` 扩展自框架的 `ThemeColor`，公开了三个预定义的颜色组：`GeneralColors`、`AppBarColors` 和 `BottomTabBarColors`。基础主题构建器（`lib/resources/themes/base_theme.dart`）在构建 `ThemeData` 时读取这些组，因此您放入其中的任何内容都会自动连接到对应的 widget。

<br>

文件 `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// 通用颜色
  @override
  GeneralColors get general;

  /// 应用栏颜色
  @override
  AppBarColors get appBar;

  /// 底部标签栏颜色
  @override
  BottomTabBarColors get bottomTabBar;
}
```

三个组公开以下字段：

- `GeneralColors` – `background`、`content`、`primaryAccent`、`surface`、`surfaceContent`
- `AppBarColors` – `background`、`content`
- `BottomTabBarColors` – `background`、`iconSelected`、`iconUnselected`、`labelSelected`、`labelUnselected`

要添加这些默认值以外的字段——您自己的按钮、图标、徽章等——请参阅[扩展颜色样式](#extending-color-styles)。

<div id="extending-color-styles"></div>

## 扩展颜色样式

三个默认组（`general`、`appBar`、`bottomTabBar`）是起点，而非硬性限制。`lib/resources/themes/color_styles.dart` 由您自由修改——在默认值之上添加新的颜色组（或单个字段），然后在每个主题的颜色类中实现它们。

**1. 定义自定义颜色组**

将相关颜色组织成一个小型不可变类：

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

**2. 将其添加到 `ColorStyles`**

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

  // 自定义组
  IconColors get icons;
}
```

**3. 在每个主题的颜色类中实现**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...现有的覆盖...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

在 `dark_theme_colors.dart` 中使用暗黑模式值重复相同的 `icons` 覆盖。

**4. 在 widget 中使用**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## 切换主题

{{ config('app.name') }} 支持动态切换主题的功能。

例如，如果您需要在用户点击按钮激活"暗黑主题"时切换主题。

您可以通过以下方式实现：

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

TextButton(onPressed: () {

    // 将主题设置为使用"暗黑主题"
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// 或

TextButton(onPressed: () {

    // 将主题设置为使用"明亮主题"
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## 字体

在 {{ config('app.name') }} 中，更新整个应用的主要字体很简单。打开 `lib/config/design.dart` 并更新 `DesignConfig.appFont`。

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

我们在仓库中包含了 <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> 库，因此您可以轻松开始使用所有字体。要切换到不同的 Google 字体，只需更改调用：

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

查看官方 <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> 库上的字体以了解更多信息。

需要使用自定义字体？请查看此指南 - https://flutter.dev/docs/cookbook/design/fonts

添加字体后，像下面的示例一样更改变量。

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo 作为自定义字体的示例使用
```

<div id="design"></div>

## 设计

**lib/config/design.dart** 文件用于管理应用的设计元素。所有内容通过 `DesignConfig` 类公开：

`DesignConfig.appFont` 包含应用的字体。

`DesignConfig.logo` 用于显示应用的 Logo。

您可以修改 **lib/resources/widgets/logo_widget.dart** 来自定义 Logo 的显示方式。

`DesignConfig.loader` 用于显示加载器。{{ config('app.name') }} 将在某些辅助方法中使用此变量作为默认加载器组件。

您可以修改 **lib/resources/widgets/loader_widget.dart** 来自定义加载器的显示方式。

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
// 来自您的 colorStyles 的颜色
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
