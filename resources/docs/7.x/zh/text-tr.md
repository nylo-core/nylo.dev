# TextTr

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [基本用法](#basic-usage "基本用法")
- [字符串插值](#string-interpolation "字符串插值")
- [样式构造函数](#styled-constructors "样式构造函数")
- [参数](#parameters "参数")


<div id="introduction"></div>

## 简介

**TextTr** 组件是 Flutter `Text` 组件的便捷封装，它使用 {{ config('app.name') }} 的本地化系统自动翻译其内容。

无需编写：

``` dart
Text("hello_world".tr())
```

您可以编写：

``` dart
TextTr("hello_world")
```

这使您的代码更简洁、可读性更强，尤其是在处理大量翻译字符串时。

<div id="basic-usage"></div>

## 基本用法

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    children: [
      TextTr("welcome_message"),

      TextTr(
        "app_title",
        style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
        textAlign: TextAlign.center,
      ),
    ],
  );
}
```

该组件将在您的语言文件（例如 `/lang/en.json`）中查找翻译键：

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## 字符串插值

使用 `arguments` 参数将动态值注入翻译中：

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

在您的语言文件中：

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

输出：**Hello, John!**

### 多个参数

``` dart
TextTr(
  "order_summary",
  arguments: {
    "item": "Coffee",
    "quantity": "2",
    "total": "\$8.50",
  },
)
```

``` json
{
  "order_summary": "You ordered @{{quantity}}x @{{item}} for @{{total}}"
}
```

输出：**You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## 样式构造函数

`TextTr` 提供了命名构造函数，自动从主题中应用文本样式：

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

使用 `Theme.of(context).textTheme.displayLarge` 样式。

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

使用 `Theme.of(context).textTheme.headlineLarge` 样式。

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

使用 `Theme.of(context).textTheme.bodyLarge` 样式。

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

使用 `Theme.of(context).textTheme.labelLarge` 样式。

### 样式构造函数示例

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
      TextTr.headlineLarge("welcome_title"),
      SizedBox(height: 16),
      TextTr.bodyLarge(
        "welcome_description",
        arguments: {"app_name": "MyApp"},
      ),
      SizedBox(height: 24),
      TextTr.labelLarge("get_started"),
    ],
  );
}
```

<div id="parameters"></div>

## 参数

`TextTr` 支持所有标准的 `Text` 组件参数：

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `data` | `String` | 要查找的翻译键 |
| `arguments` | `Map<String, String>?` | 用于字符串插值的键值对 |
| `style` | `TextStyle?` | 文本样式 |
| `textAlign` | `TextAlign?` | 文本对齐方式 |
| `maxLines` | `int?` | 最大行数 |
| `overflow` | `TextOverflow?` | 溢出处理方式 |
| `softWrap` | `bool?` | 是否在软换行处自动换行 |
| `textDirection` | `TextDirection?` | 文本方向 |
| `locale` | `Locale?` | 文本渲染的区域设置 |
| `semanticsLabel` | `String?` | 无障碍标签 |

## 对比

| 方式 | 代码 |
|----------|------|
| 传统方式 | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| 带参数 | `TextTr("hello", arguments: {"name": "John"})` |
| 带样式 | `TextTr.headlineLarge("title")` |
