# Localization

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to localization")
- [Adding localised files](#adding-localised-files "Adding localised files")
- Basics
  - [Localizing text](#localizing-text "Localizing text")
    - [Updating the locale](#updating-the-locale "Updating the locale")
  - [Setting a default locale](#setting-a-default-locale "Settings a default locale")
- [Locale support](#locale-support "Locale support")


<div id="introduction"></div>
## Introduction

Localizing our projects provides us with an easy way to change the language or experience for users in different countries. 

Here's an example. 

If your apps primary Locale was en (English) and you wanted to also provide users in Spain with a Spanish version, localising the app would be your best option.


<div id="adding-localised-files"></div>
## Adding Localized files

We include a `lang` directory in the project that can be found at the root of the project. Inside here, you'll be able to include `.json` files for each locale. E.g. es.json for Spanish or pt.json for Portuguese.

#### Example: en.json
``` json
{
  "documentation": "documentation",
  "changelog": "changelog"
}
```

#### Example: es.json
``` json
{
  "documentation": "documentación",
  "changelog": "registro de cambios"
}
```


Once you’ve added the  `.json` files, you’ll then need to include them within your pubspec.yaml file.

Go to your **pubspec.yaml** file and then at the `assets` section, add the new files.

> You can include as many locale files here but make sure you also include them within your pubspec.yaml assets.


<div id="localizing-text"></div>

## Localizing text

You can localize any text with a `BuildContext` and key from a lang `.json` file.

``` dart 
trans(context, "documentation");
```

This will return the value of the key depending on the current Locale assigned.


<div id="updating-the-locale"></div>

## Updating the locale

Updating the locale in the app is simple in Nylo, you can use the below method in your widget.

``` dart
setState({
  AppLocale.instance.updateLocale(context, Locale('es'));
})
```

What this will do is update the locale to (Spanish which is) **es**. Any reference to `trans()` keys will be automatically updated to use the `es.json`.

This can be extremely useful if you wanted to provide users with an options menu to select a language to use in the app. E.g. if they navigated to a settings screen with language flags and selected Spanish. 


<div id="setting-a-default-locale"></div>

## Setting a default locale

You may want to update the default locale when users open your app for the first time, follow the steps below to see how.
1. First, open the `main.dart` file.
2. Next update the `AppLocale.instance.locale` property to your Locale like the below example.

``` dart
...
// updated locale
AppLocale.instance.locale = Locale('es');

runApp(
  AppBuild(
    navigatorKey: router.navigatorKey,
    onGenerateRoute: router.generator(),
    darkTheme: darkTheme(),
    themeData: defaultTheme(),
    locale: AppLocale.instance.locale,
  ),
);
```

<div id="locale-support"></div>

## Locale support

Nylo currently supports these [locales](https://github.com/datasets/language-codes/blob/master/data/language-codes.csv).