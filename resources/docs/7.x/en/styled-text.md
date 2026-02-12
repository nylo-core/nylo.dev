# Styled Text

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
- [Children Mode](#children-mode "Children Mode")
- [Template Mode](#template-mode "Template Mode")
  - [Styling Placeholders](#styling-placeholders "Styling Placeholders")
  - [Tap Callbacks](#tap-callbacks "Tap Callbacks")
  - [Pipe-Separated Keys](#pipe-keys "Pipe-Separated Keys")
  - [Localization Keys](#localization-keys "Localization Keys")
- [Parameters](#parameters "Parameters")
- [Text Extensions](#text-extensions "Text Extensions")
  - [Typography Styles](#typography-styles "Typography Styles")
  - [Utility Methods](#utility-methods "Utility Methods")
- [Examples](#examples "Practical Examples")

<div id="introduction"></div>

## Introduction

**StyledText** is a widget for displaying rich text with mixed styles, tap callbacks, and pointer events. It renders as a `RichText` widget with multiple `TextSpan` children, giving you fine-grained control over each segment of text.

StyledText supports two modes:

1. **Children mode** -- pass a list of `Text` widgets, each with their own style
2. **Template mode** -- use `@{{placeholder}}` syntax in a string and map placeholders to styles and actions

<div id="basic-usage"></div>

## Basic Usage

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

## Children Mode

Pass a list of `Text` widgets to compose styled text:

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

The base `style` is applied to any child that doesn't have its own style.

### Pointer Events

Detect when the pointer enters or exits a text segment:

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

## Template Mode

Use `StyledText.template()` with `@{{placeholder}}` syntax:

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

The text between `@{{ }}` is both the **display text** and the **key** used to look up styles and tap callbacks.

<div id="styling-placeholders"></div>

### Styling Placeholders

Map placeholder names to `TextStyle` objects:

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

### Tap Callbacks

Map placeholder names to tap handlers:

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

### Pipe-Separated Keys

Apply the same style or callback to multiple placeholders using pipe `|` separated keys:

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

This maps the same style and callback to all three placeholders.

<div id="localization-keys"></div>

### Localization Keys

Use `@{{key:text}}` syntax to separate the **lookup key** from the **display text**. This is useful for localization — the key stays the same across all locales while the display text changes per language.

``` dart
// In your locale files:
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
// EN renders: "Learn Languages, Reading and Speaking in AppName"
// ES renders: "Aprende Idiomas, Lectura y Habla en AppName"
```

The part before `:` is the **key** used to look up styles and tap callbacks. The part after `:` is the **display text** that renders on screen. Without `:`, the placeholder behaves exactly as before — fully backward compatible.

This works with all existing features including [pipe-separated keys](#pipe-keys) and [tap callbacks](#tap-callbacks).

<div id="parameters"></div>

## Parameters

### StyledText (Children Mode)

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `children` | `List<Text>` | required | List of Text widgets |
| `style` | `TextStyle?` | null | Base style for all children |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | Pointer enter callback |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | Pointer exit callback |
| `spellOut` | `bool?` | null | Spell out text character by character |
| `softWrap` | `bool` | `true` | Enable soft wrapping |
| `textAlign` | `TextAlign` | `TextAlign.start` | Text alignment |
| `textDirection` | `TextDirection?` | null | Text direction |
| `maxLines` | `int?` | null | Maximum lines |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | Overflow behavior |
| `locale` | `Locale?` | null | Text locale |
| `strutStyle` | `StrutStyle?` | null | Strut style |
| `textScaler` | `TextScaler?` | null | Text scaler |
| `selectionColor` | `Color?` | null | Selection highlight color |

### StyledText.template (Template Mode)

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `text` | `String` | required | Template text with `@{{placeholder}}` syntax |
| `styles` | `Map<String, TextStyle>?` | null | Map of placeholder names to styles |
| `onTap` | `Map<String, VoidCallback>?` | null | Map of placeholder names to tap callbacks |
| `style` | `TextStyle?` | null | Base style for non-placeholder text |

All other parameters (`softWrap`, `textAlign`, `maxLines`, etc.) are the same as the children constructor.

<div id="text-extensions"></div>

## Text Extensions

{{ config('app.name') }} extends Flutter's `Text` widget with typography and utility methods.

<div id="typography-styles"></div>

### Typography Styles

Apply Material Design typography styles to any `Text` widget:

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

Each accepts optional overrides:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**Available overrides** (same for all typography methods):

| Parameter | Type | Description |
|-----------|------|-------------|
| `color` | `Color?` | Text color |
| `fontSize` | `double?` | Font size |
| `fontWeight` | `FontWeight?` | Font weight |
| `fontStyle` | `FontStyle?` | Italic/normal |
| `letterSpacing` | `double?` | Letter spacing |
| `wordSpacing` | `double?` | Word spacing |
| `height` | `double?` | Line height |
| `decoration` | `TextDecoration?` | Text decoration |
| `decorationColor` | `Color?` | Decoration color |
| `decorationStyle` | `TextDecorationStyle?` | Decoration style |
| `decorationThickness` | `double?` | Decoration thickness |
| `fontFamily` | `String?` | Font family |
| `shadows` | `List<Shadow>?` | Text shadows |
| `overflow` | `TextOverflow?` | Overflow behavior |

<div id="utility-methods"></div>

### Utility Methods

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

## Examples

### Terms and Conditions Link

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

### Version Display

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

### Mixed Style Paragraph

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

### Typography Chain

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
