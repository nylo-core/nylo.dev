# Localization

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to localization")
- [Adding localised files](#adding-localised-files "Adding localised files")
- Basics
  - [Localizing text](#localizing-text "Localizing text")
    - [Arguments](#arguments "Arguments")
  - [Updating the locale](#updating-the-locale "Updating the locale")
  - [Setting a default locale](#setting-a-default-locale "Settings a default locale")


<div id="introduction"></div>
<br>

## Introduction

Localizing our projects provides us with an easy way to change the language for users in different countries. 

If your apps primary Locale was **en** (English) but you also wanted to provide users in Spain with a Spanish version, localising the app would be your best option.

Here's an example to localize text in your app using Nylo.

#### Example of a localized file: `lang/en.json`
``` json
{
  "documentation": "documentation",
  "changelog": "changelog"
}
```
#### Example widget
``` dart
...
ListView(
  children: [
    Text(
      "documentation".tr()
    ),
    // ... or
    Text(
      trans("documentation")
    )
  ]
)
```

The above will display the text from the <b>lang/en.json</b> file. If you support more than one locale, add another file like <b>lang/es.json</b> and copy the keys but change the values to match the locale.
Here's an example.
#### Example of a English localized file: `lang/en.json`
``` json
{
  "documentation": "documentation",
  "changelog": "changelog"
}
```
#### Example of a Spanish localized file: `lang/es.json`
``` json
{
  "documentation": "documentación",
  "changelog": "registro de cambios"
}
```

<div id="adding-localised-files"></div>
<br>

## Adding Localized files

Add all your localization files to the `lang/` directory. Inside here, you'll be able to include your different locales. E.g. <b>es.json</b> for Spanish or <b>pt.json</b> for Portuguese.

#### Example: `lang/en.json`
``` dart
{
  "documentation": "documentation",
  "changelog": "changelog",
  "intros": {
    "hello": "hello @{{first_name}}",
  }
}
```

#### Example: `lang/es.json`
``` dart
{
  "documentation": "documentación",
  "changelog": "registro de cambios",
  "intros": {
    "hello": "hola @{{first_name}}",
  }
}
```


Once you’ve added the  `.json` files, you’ll need to include them within your pubspec.yaml file.

Go to your **pubspec.yaml** file and then at the `assets` section, add the new files.

> You can include as many locale files here but make sure you also include them within your pubspec.yaml assets.


<div id="localizing-text"></div>
<br>

## Localizing text

You can localize any text with a key from your lang `.json` file.

``` dart 
"documentation".tr()
// or
trans("documentation");
```

You can also use nested keys in the json file. Here's an example below.

#### Example: `lang/en.json`
``` json
{
  "changelog": "changelog",
  "intros": {
    "hello": "hello"
  }
}
```

#### Example: `lang/es.json`
``` json
{
  "changelog": "registro de cambios",
  "intros": {
    "hello": "hola"    
  }
}
```
#### Example using nested JSON keys
``` dart 
"intros.hello".tr()
// or
trans("intros.hello");
```

<div id="arguments"></div>
<br>

### Arguments

You can supply arguments to fill in values for your keys. In the below example, we have a key named **"intros.hello_name"**. It has one fillable value named **"first_name"** to fill this value, pass a value to the method below.

#### Example: `lang/en.json`
``` json
{
  "changelog": "changelog",
  "intros": {
    "hello_name": "hello @{{first_name}}",
  }
}
```

#### Example: `lang/es.json`
``` json
{
  "changelog": "registro de cambios",
  "intros": {
    "hello_name": "hola @{{first_name}}"
  }
}
```

Example to fill arguments in your JSON file.
``` dart 
"intros.hello_name".tr(arguments: {"first_name": "Anthony"}) // Hello Anthony
// or
trans("intros.hello_name", arguments: {"first_name": "Anthony"}); // Hello Anthony
```

<div id="updating-the-locale"></div>
<br>

## Updating the locale

Updating the locale in the app is simple in Nylo, you can use the below method in your widget.

``` dart
String language = 'es'; // country code must match your json file e.g. pt.json would be 'pt

await NyLocalization.instance.setLanguage(context, language: language); // Switches language
```

This will update the locale to Spanish.

If you widget extends the `NyState` class, you can set the locale by calling the `changeLanguage` helper.

Example below.

```dart 
class _MyHomePageState extends NyState<MyHomePage> {
...
  InkWell(
    child: Text("Switch to Spanish"), 
    onTap: () async {
      await changeLanguage('es');
    },
  )
```

This is useful if you need to provide users with a menu to select a language to use in the app. 
E.g. if they navigated to a settings screen with language flags and selected Spanish. 


<div id="setting-a-default-locale"></div>
<br>

## Setting a default locale

You may want to update the default locale when users open your app for the first time, follow the steps below to see how.
1. First, open the `.env` file.
2. Next, update the `DEFAULT_LOCALE` property to your Locale like the below example.

``` dart
DEFAULT_LOCALE="es" // e.g. for Spanish and you'll then need to add your new .json file in /lang/es.json
```