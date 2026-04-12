# 样式文本

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [基本用法](#basic-usage "基本用法")
- [子组件模式](#children-mode "子组件模式")
- [模板模式](#template-mode "模板模式")
  - [样式占位符](#styling-placeholders "样式占位符")
  - [点击回调](#tap-callbacks "点击回调")
  - [管道分隔键](#pipe-keys "管道分隔键")
  - [通配符样式](#wildcard-styles "通配符样式")
  - [本地化键](#localization-keys "本地化键")
- [参数](#parameters "参数")
- [文本扩展](#text-extensions "文本扩展")
  - [排版样式](#typography-styles "排版样式")
  - [实用方法](#utility-methods "实用方法")
- [示例](#examples "示例")

<div id="introduction"></div>

## 简介

**StyledText** 是一个用于显示富文本的组件，支持混合样式、点击回调和指针事件。它渲染为一个包含多个 `TextSpan` 子项的 `RichText` 组件，让你可以精细控制文本的每个片段。

StyledText 支持两种模式：

1. **子组件模式** -- 传入一个 `Text` 组件列表，每个组件有自己的样式
2. **模板模式** -- 在字符串中使用 `@{{placeholder}}` 语法，将占位符映射到样式和操作

<div id="basic-usage"></div>

## 基本用法

``` dart
// 子组件模式 - Text 组件列表
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// 模板模式 - 占位符语法
StyledText.template(
  "Welcome to @{{Nylo}}!",
  styles: {
    "Nylo": TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
  },
)
```

<div id="children-mode"></div>

## 子组件模式

传入 `Text` 组件列表来组合样式文本：

``` dart
StyledText(
  style: TextStyle(fontSize: 16, color: Colors.black),
  children: [
    Text("Your order "),
    Text("#1234", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" has been "),
    Text("confirmed", style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold)),
    Text("."),
  ],
)
```

基础 `style` 会应用于没有自定义样式的子组件。

### 指针事件

检测指针进入或离开文本片段：

``` dart
StyledText(
  onEnter: (Text text, PointerEnterEvent event) {
    print("Hovering over: ${text.data}");
  },
  onExit: (Text text, PointerExitEvent event) {
    print("Left: ${text.data}");
  },
  children: [
    Text("Hover me", style: TextStyle(color: Colors.blue)),
    Text(" or "),
    Text("me", style: TextStyle(color: Colors.red)),
  ],
)
```

<div id="template-mode"></div>

## 模板模式

使用 `StyledText.template()` 配合 `@{{placeholder}}` 语法：

``` dart
StyledText.template(
  "By continuing, you agree to our @{{Terms of Service}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey),
  styles: {
    "Terms of Service": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
    "Privacy Policy": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
  },
  onTap: {
    "Terms of Service": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

`@{{ }}` 之间的文本既是**显示文本**，也是用于查找样式和点击回调的**键**。

<div id="styling-placeholders"></div>

### 样式占位符

将占位符名称映射到 `TextStyle` 对象：

``` dart
StyledText.template(
  "Framework Version: @{{$version}}",
  style: textTheme.bodyMedium,
  styles: {
    version: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

<div id="tap-callbacks"></div>

### 点击回调

将占位符名称映射到点击处理函数：

``` dart
StyledText.template(
  "@{{Sign up}} or @{{Log in}} to continue.",
  onTap: {
    "Sign up": () => routeTo(SignUpPage.path),
    "Log in": () => routeTo(LoginPage.path),
  },
  styles: {
    "Sign up": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
    "Log in": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
  },
)
```

<div id="pipe-keys"></div>

### 管道分隔键

使用管道符 `|` 分隔键，将相同的样式或回调应用于多个占位符：

``` dart
StyledText.template(
  "Available in @{{English}}, @{{French}}, and @{{German}}.",
  styles: {
    "English|French|German": TextStyle(
      fontWeight: FontWeight.bold,
      color: Colors.blue,
    ),
  },
  onTap: {
    "English|French|German": () => showLanguagePicker(),
  },
)
```

这会将相同的样式和回调映射到所有三个占位符。

<div id="wildcard-styles"></div>

### 通配符样式

使用 `"*"` 作为键，可以将样式或点击回调应用于所有没有自定义键的占位符：

``` dart
StyledText.template(
  "Hello @{{name}}, welcome to @{{app}}!",
  styles: {
    "*": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
  },
)
```

`name` 和 `app` 都会接收通配符样式。如果占位符也有明确的键，明确的键优先于 `"*"`。

``` dart
StyledText.template(
  "Click @{{here}} or @{{cancel}}.",
  styles: {
    "here": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
    "*": TextStyle(color: Colors.grey), // 仅应用于 "cancel"
  },
  onTap: {
    "*": () => Navigator.pop(context), // 点击任意未匹配的占位符
  },
)
```

<div id="localization-keys"></div>

### 本地化键

使用 `@{{key:text}}` 语法将**查找键**与**显示文本**分开。这对本地化很有用 —— 键在所有语言环境中保持不变，而显示文本随语言变化。

``` dart
// 在语言环境文件中：
// en.json → "learn_skills": "Learn @{{lang:Languages}}, @{{read:Reading}} and @{{speak:Speaking}} in @{{app:AppName}}"
// es.json → "learn_skills": "Aprende @{{lang:Idiomas}}, @{{read:Lectura}} y @{{speak:Habla}} en @{{app:AppName}}"

StyledText.template(
  "learn_skills".tr(),
  styles: {
    "lang|read|speak": TextStyle(
      color: Colors.blue,
      fontWeight: FontWeight.bold,
    ),
    "app": TextStyle(color: Colors.green),
  },
  onTap: {
    "app": () => routeTo("/about"),
  },
)
// EN 渲染：「Learn Languages, Reading and Speaking in AppName」
// ES 渲染：「Aprende Idiomas, Lectura y Habla en AppName」
```

`:` 前面的部分是用于查找样式和点击回调的**键**。`:` 后面的部分是屏幕上渲染的**显示文本**。没有 `:` 时，占位符的行为与以前完全相同 —— 完全向后兼容。

这与所有现有功能兼容，包括[管道分隔键](#pipe-keys)和[点击回调](#tap-callbacks)。

<div id="parameters"></div>

## 参数

### StyledText（子组件模式）

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `children` | `List<Text>` | 必填 | Text 组件列表 |
| `style` | `TextStyle?` | null | 所有子组件的基础样式 |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | 指针进入回调 |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | 指针离开回调 |
| `spellOut` | `bool?` | null | 逐字符拼写文本 |
| `softWrap` | `bool` | `true` | 启用软换行 |
| `textAlign` | `TextAlign` | `TextAlign.start` | 文本对齐方式 |
| `textDirection` | `TextDirection?` | null | 文本方向 |
| `maxLines` | `int?` | null | 最大行数 |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | 溢出行为 |
| `locale` | `Locale?` | null | 文本语言环境 |
| `strutStyle` | `StrutStyle?` | null | 支撑样式 |
| `textScaler` | `TextScaler?` | null | 文本缩放器 |
| `selectionColor` | `Color?` | null | 选中高亮颜色 |

### StyledText.template（模板模式）

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `text` | `String` | 必填 | 使用 `@{{placeholder}}` 语法的模板文本 |
| `styles` | `Map<String, TextStyle>?` | null | 占位符名称到样式的映射 |
| `onTap` | `Map<String, VoidCallback>?` | null | 占位符名称到点击回调的映射 |
| `style` | `TextStyle?` | null | 非占位符文本的基础样式 |

所有其他参数（`softWrap`、`textAlign`、`maxLines` 等）与子组件构造函数相同。

<div id="text-extensions"></div>

## 文本扩展

{{ config('app.name') }} 为 Flutter 的 `Text` 组件扩展了排版和实用方法。

<div id="typography-styles"></div>

### 排版样式

将 Material Design 排版样式应用于任何 `Text` 组件：

``` dart
Text("Hello").displayLarge()
Text("Hello").displayMedium()
Text("Hello").displaySmall()
Text("Hello").headingLarge()
Text("Hello").headingMedium()
Text("Hello").headingSmall()
Text("Hello").titleLarge()
Text("Hello").titleMedium()
Text("Hello").titleSmall()
Text("Hello").bodyLarge()
Text("Hello").bodyMedium()
Text("Hello").bodySmall()
Text("Hello").labelLarge()
Text("Hello").labelMedium()
Text("Hello").labelSmall()
```

每个方法都接受可选的覆盖参数：

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**可用的覆盖参数**（所有排版方法通用）：

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `color` | `Color?` | 文本颜色 |
| `fontSize` | `double?` | 字体大小 |
| `fontWeight` | `FontWeight?` | 字体粗细 |
| `fontStyle` | `FontStyle?` | 斜体/正常 |
| `letterSpacing` | `double?` | 字母间距 |
| `wordSpacing` | `double?` | 单词间距 |
| `height` | `double?` | 行高 |
| `decoration` | `TextDecoration?` | 文本装饰 |
| `decorationColor` | `Color?` | 装饰颜色 |
| `decorationStyle` | `TextDecorationStyle?` | 装饰样式 |
| `decorationThickness` | `double?` | 装饰粗细 |
| `fontFamily` | `String?` | 字体系列 |
| `shadows` | `List<Shadow>?` | 文本阴影 |
| `overflow` | `TextOverflow?` | 溢出行为 |

<div id="utility-methods"></div>

### 实用方法

``` dart
// 字体粗细
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// 对齐
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// 最大行数
Text("Long text...").setMaxLines(2)

// 字体系列
Text("Custom font").setFontFamily("Roboto")

// 字体大小
Text("Big text").setFontSize(24)

// 自定义样式
Text("Styled").setStyle(TextStyle(color: Colors.red))

// 内边距
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// 携带修改内容复制
Text("Original").copyWith(
  textAlign: TextAlign.center,
  maxLines: 2,
  style: TextStyle(fontSize: 18),
)
```

<div id="examples"></div>

## 示例

### 条款和条件链接

``` dart
StyledText.template(
  "By signing up, you agree to our @{{Terms}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey.shade600, fontSize: 14),
  styles: {
    "Terms|Privacy Policy": TextStyle(
      color: Colors.blue,
      decoration: TextDecoration.underline,
    ),
  },
  onTap: {
    "Terms": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

### 版本显示

``` dart
StyledText.template(
  "Framework Version: @{{$nyloVersion}}",
  style: textTheme.bodyMedium!.copyWith(
    color: NyColor(
      light: Colors.grey.shade800,
      dark: Colors.grey.shade200,
    ).toColor(context),
  ),
  styles: {
    nyloVersion: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

### 混合样式段落

``` dart
StyledText(
  style: TextStyle(fontSize: 16, height: 1.5),
  children: [
    Text("Flutter "),
    Text("makes it easy", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" to build beautiful apps. With "),
    Text("Nylo", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
    Text(", it's even faster."),
  ],
)
```

### 排版链式调用

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
