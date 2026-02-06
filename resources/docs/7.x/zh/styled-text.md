# 样式文本

---

<a name="section-1"></a>
- [简介](#introduction "Introduction")
- [基本用法](#basic-usage "Basic Usage")
- [子组件模式](#children-mode "Children Mode")
- [模板模式](#template-mode "Template Mode")
  - [样式占位符](#styling-placeholders "Styling Placeholders")
  - [点击回调](#tap-callbacks "Tap Callbacks")
  - [管道分隔键](#pipe-keys "Pipe-Separated Keys")
- [参数](#parameters "Parameters")
- [文本扩展](#text-extensions "Text Extensions")
  - [排版样式](#typography-styles "Typography Styles")
  - [实用方法](#utility-methods "Utility Methods")
- [示例](#examples "Practical Examples")

<div id="introduction"></div>

## 简介

**StyledText** 是一个用于显示富文本的组件，支持混合样式、点击回调和指针事件。它渲染为一个包含多个 `TextSpan` 子项的 `RichText` 组件，让你可以精细控制文本的每个片段。

StyledText 支持两种模式：

1. **子组件模式** -- 传入一个 `Text` 组件列表，每个组件有自己的样式
2. **模板模式** -- 在字符串中使用 `@{{placeholder}}` 语法，将占位符映射到样式和操作

<div id="basic-usage"></div>

## 基本用法

``` dart
// Children mode - list of Text widgets
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// Template mode - placeholder syntax
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
// Font weight
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// Alignment
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// Max lines
Text("Long text...").setMaxLines(2)

// Font family
Text("Custom font").setFontFamily("Roboto")

// Font size
Text("Big text").setFontSize(24)

// Custom style
Text("Styled").setStyle(TextStyle(color: Colors.red))

// Padding
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// Copy with modifications
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
