# TextTr

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
- [String Interpolation](#string-interpolation "String Interpolation")
- [Styled Constructors](#styled-constructors "Styled Constructors")
- [Parameters](#parameters "Parameters")


<div id="introduction"></div>

## Introduction

The **TextTr** widget is a convenience wrapper around Flutter's `Text` widget that automatically translates its content using {{ config('app.name') }}'s localization system.

Instead of writing:

``` dart
Text("hello_world".tr())
```

You can write:

``` dart
TextTr("hello_world")
```

This makes your code cleaner and more readable, especially when dealing with many translated strings.

<div id="basic-usage"></div>

## Basic Usage

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

The widget will look up the translation key in your language files (e.g., `/lang/en.json`):

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## String Interpolation

Use the `arguments` parameter to inject dynamic values into your translations:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

In your language file:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

Output: **Hello, John!**

### Multiple Arguments

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

Output: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## Styled Constructors

`TextTr` provides named constructors that automatically apply text styles from your theme:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

Uses `Theme.of(context).textTheme.displayLarge` style.

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

Uses `Theme.of(context).textTheme.headlineLarge` style.

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

Uses `Theme.of(context).textTheme.bodyLarge` style.

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

Uses `Theme.of(context).textTheme.labelLarge` style.

### Example with Styled Constructors

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

## Parameters

`TextTr` supports all standard `Text` widget parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| `data` | `String` | The translation key to look up |
| `arguments` | `Map<String, String>?` | Key-value pairs for string interpolation |
| `style` | `TextStyle?` | Text styling |
| `textAlign` | `TextAlign?` | How the text should be aligned |
| `maxLines` | `int?` | Maximum number of lines |
| `overflow` | `TextOverflow?` | How to handle overflow |
| `softWrap` | `bool?` | Whether to wrap text at soft breaks |
| `textDirection` | `TextDirection?` | Direction of the text |
| `locale` | `Locale?` | Locale for text rendering |
| `semanticsLabel` | `String?` | Accessibility label |

## Comparison

| Approach | Code |
|----------|------|
| Traditional | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| With arguments | `TextTr("hello", arguments: {"name": "John"})` |
| Styled | `TextTr.headlineLarge("title")` |
