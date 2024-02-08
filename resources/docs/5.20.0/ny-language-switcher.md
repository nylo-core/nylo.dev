# NyLanguageSwitcher

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Usage
    - [NyLanguageSwitcher](#usage-nylanguageswitcher "Usage NyLanguageSwitcher")
    - [NyLanguageSwitcher.showBottomModal](#usage-nylanguageswitcher-show-bottom-modal "Usage NyLanguageSwitcher.showBottomModal")
- [Parameters](#parameters "Parameters")
- [Methods](#methods "Methods")


<a name="introduction"></a>
<br>

## Introduction

In this section, we will learn about the `NyLanguageSwitcher` widget.

The `NyLanguageSwitcher` widget is a helpful widget for handling language switching in your Flutter projects. This widget will automatically detect the languages you have in your `/lang` directory and display them to the user.

> **Note**: If your app isn't localized yet, learn how to do so [here](/docs/5.20.0/localization) before using this Widget.

#### What does NyLanguageSwitcher do?

If the user selects a language, the app will automatically switch to that language and update the UI accordingly.

When the user opens the app again, it will remember the language they selected and display the app in that language.

Let's take a look at some code.

<a name="usage-nylanguageswitcher"></a>
<br>

## Usage NyLanguageSwitcher

The `NyLanguageSwitcher` widget is a helpful widget for handling language switching in your Flutter projects.

Here's how you can start using the `NyLanguageSwitcher` widget.

``` dart
@override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          title: Text("Test Page"),
          actions: [
            NyLanguageSwitcher() // Add the NyLanguageSwitcher widget to the app bar
          ],
        )
    );
  }
```

When the user taps the `NyLanguageSwitcher` widget, a dropdown option will appear with the languages available in your `/lang` directory.

After the user selects a language, the app will automatically switch to that language and update the UI accordingly.


<a name="usage-nylanguageswitcher-show-bottom-modal"></a>
<br>

## Usage NyLanguageSwitcher Show Bottom Modal

The `NyLanguageSwitcher.showBottomModal` widget is a helpful widget for handling language switching in your Flutter projects.

Here's how you can start using the `NyLanguageSwitcher.showBottomModal` widget.

``` dart
@override
  Widget build(BuildContext context) {
    return Scaffold(
        body: Center(
          child: Text("Change Language").onTap(() {
            NyLanguageSwitcher.showBottomModal(context);
            /// This will show a bottom modal with the languages available in your `/lang` directory
          }),
        )
    );
  }
```

When the user taps the `NyLanguageSwitcher.showBottomModal` widget, a bottom modal will appear with the languages available in your `/lang` directory.

<a name="parameters"></a>
<br>

## Parameters

Here are some parameters you should know about before using the `NyLanguageSwitcher` widget.

| Property | Type | Description |
| --- | --- | --- |
| icon | Widget? | The icon for the `DropdownButton`. |
| iconEnabledColor | Color? | The icon enabled color for the `DropdownButton`. |
| dropdownBgColor | Color? | The background color for the `DropdownButton`. |
| onLanguageChange | Function(String language)? | The function to call when the language is changed. |
| hint | Widget? | The hint for the `DropdownButton`. |
| itemHeight | double | The height of each item in the `DropdownButton`. |
| dropdownBuilder | Widget Function(Map<String, dynamic> language)? | The builder for the `DropdownButton`. |
| dropdownAlignment | AlignmentGeometry | The alignment for the `DropdownButton`. |
| dropdownOnTap | Function()? | The function to call when the `DropdownButton` is tapped. |
| padding | EdgeInsetsGeometry? | The padding for the `DropdownButton`. |
| onTap | Function()? | The function to call when the `DropdownButton` is tapped. |
| borderRadius | BorderRadius? | The border radius for the `DropdownButton`. |
| iconSize | int? | The size of the icon for the `DropdownButton`. |
| elevation | int? | The elevation for the `DropdownButton`. |
| langPath | String | The path to the language files. |
| textStyle | TextStyle | The text style for the `DropdownButton`. |

<a name="methods"></a>
<br>

## Methods

Here are some method you should know about before using the `NyLanguageSwitcher` widget.

| Method | Description |
| --- | --- |
| NyLanguageSwitcher.showBottomModal(context) | This method will show a bottom modal with the languages available in your `/lang` directory. |
| NyLanguageSwitcher.clearLanguage() | This method will clear the language from the app. |
| NyLanguageSwitcher.getLanguageData(String localeCode) | This method will get the language data from the app. |
| NyLanguageSwitcher.currentLanguage() | This method will get the current language from the app. |
| NyLanguageSwitcher.storeLanguage(object: {"en": "English"}) | This method will store the language in the app. |
