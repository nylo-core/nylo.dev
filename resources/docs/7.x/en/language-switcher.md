# LanguageSwitcher

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Usage
    - [Dropdown Widget](#usage-dropdown "Dropdown Widget")
    - [Bottom Sheet Modal](#usage-bottom-modal "Bottom Sheet Modal")
- [Custom Dropdown Builder](#custom-builder "Custom Dropdown Builder")
- [Parameters](#parameters "Parameters")
- [Static Methods](#methods "Static Methods")


<div id="introduction"></div>

## Introduction

The **LanguageSwitcher** widget provides an easy way to handle language switching in your {{ config('app.name') }} projects. It automatically detects the languages available in your `/lang` directory and displays them to the user.

**What does LanguageSwitcher do?**

- Displays available languages from your `/lang` directory
- Switches the app language when the user selects one
- Persists the selected language across app restarts
- Automatically updates the UI when the language changes

> **Note**: If your app isn't localized yet, learn how to do so in the [Localization](/docs/7.x/localization) documentation before using this widget.

<div id="usage-dropdown"></div>

## Dropdown Widget

The simplest way to use `LanguageSwitcher` is as a dropdown in your app bar:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    appBar: AppBar(
      title: Text("Settings"),
      actions: [
        LanguageSwitcher(), // Add to the app bar
      ],
    ),
    body: Center(
      child: Text("Hello World".tr()),
    ),
  );
}
```

When the user taps the dropdown, they'll see a list of available languages. After selecting a language, the app will automatically switch and update the UI.

<div id="usage-bottom-modal"></div>

## Bottom Sheet Modal

You can also display languages in a bottom sheet modal:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: Center(
      child: MaterialButton(
        child: Text("Change Language"),
        onPressed: () {
          LanguageSwitcher.showBottomModal(context);
        },
      ),
    ),
  );
}
```

The bottom modal displays a list of languages with a checkmark next to the currently selected language.

### Customizing the Modal Height

``` dart
LanguageSwitcher.showBottomModal(
  context,
  height: 300, // Custom height
);
```

<div id="custom-builder"></div>

## Custom Dropdown Builder

Customize how each language option appears in the dropdown:

``` dart
LanguageSwitcher(
  dropdownBuilder: (Map<String, dynamic> language) {
    return Row(
      children: [
        Icon(Icons.language),
        SizedBox(width: 8),
        Text(language['name']), // e.g., "English"
        // language['locale'] contains the locale code, e.g., "en"
      ],
    );
  },
)
```

### Handling Language Changes

``` dart
LanguageSwitcher(
  onLanguageChange: (Map<String, dynamic> language) {
    print('Language changed to: ${language['name']}');
    // Perform additional actions when language changes
  },
)
```

<div id="parameters"></div>

## Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `icon` | `Widget?` | - | Custom icon for the dropdown button |
| `iconEnabledColor` | `Color?` | - | Color of the dropdown icon |
| `iconSize` | `double` | `24` | Size of the dropdown icon |
| `dropdownBgColor` | `Color?` | - | Background color of the dropdown menu |
| `hint` | `Widget?` | - | Hint widget when no language is selected |
| `itemHeight` | `double` | `kMinInteractiveDimension` | Height of each dropdown item |
| `elevation` | `int` | `8` | Elevation of the dropdown menu |
| `padding` | `EdgeInsetsGeometry?` | - | Padding around the dropdown |
| `borderRadius` | `BorderRadius?` | - | Border radius of the dropdown menu |
| `textStyle` | `TextStyle?` | - | Text style for dropdown items |
| `langPath` | `String` | `'lang'` | Path to language files in assets |
| `dropdownBuilder` | `Function(Map<String, dynamic>)?` | - | Custom builder for dropdown items |
| `dropdownAlignment` | `AlignmentGeometry` | `AlignmentDirectional.centerStart` | Alignment of dropdown items |
| `dropdownOnTap` | `Function()?` | - | Callback when dropdown item is tapped |
| `onTap` | `Function()?` | - | Callback when dropdown button is tapped |
| `onLanguageChange` | `Function(Map<String, dynamic>)?` | - | Callback when language is changed |

<div id="methods"></div>

## Static Methods

### Get Current Language

Retrieve the currently selected language:

``` dart
Map<String, dynamic>? lang = await LanguageSwitcher.currentLanguage();
// Returns: {"en": "English"} or null if not set
```

### Store Language

Manually store a language preference:

``` dart
await LanguageSwitcher.storeLanguage(
  object: {"fr": "French"},
);
```

### Clear Language

Remove the stored language preference:

``` dart
await LanguageSwitcher.clearLanguage();
```

### Get Language Data

Get language information from a locale code:

``` dart
Map<String, String>? langData = LanguageSwitcher.getLanguageData("en");
// Returns: {"en": "English"}

Map<String, String>? langData = LanguageSwitcher.getLanguageData("fr_CA");
// Returns: {"fr_CA": "French (Canada)"}
```

### Get Language List

Get all available languages from the `/lang` directory:

``` dart
List<Map<String, String>> languages = await LanguageSwitcher.getLanguageList();
// Returns: [{"en": "English"}, {"es": "Spanish"}, ...]
```

### Show Bottom Modal

Display the language selection modal:

``` dart
await LanguageSwitcher.showBottomModal(context);

// With custom height
await LanguageSwitcher.showBottomModal(context, height: 400);
```

## Supported Locales

The `LanguageSwitcher` widget supports hundreds of locale codes with human-readable names. Some examples:

| Locale Code | Language Name |
|-------------|---------------|
| `en` | English |
| `en_US` | English (United States) |
| `en_GB` | English (United Kingdom) |
| `es` | Spanish |
| `fr` | French |
| `de` | German |
| `zh` | Chinese |
| `zh_Hans` | Chinese (Simplified) |
| `ja` | Japanese |
| `ko` | Korean |
| `ar` | Arabic |
| `hi` | Hindi |
| `pt` | Portuguese |
| `ru` | Russian |

The full list includes regional variants for most languages.
