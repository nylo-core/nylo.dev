# Configuration

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to configuration in Nylo")
- Environment
  - [Configuration](#environment-configuration "Environment configuration")
  - [Variable Types](#environment-variable-types "Environment variable types")
  - [Retriving Values](#retrieving-environment-values "Retriving environment values")
- [Environment flavours](#environment-flavours "Environment flavours")
<a name="introduction"></a>
<br>
## Introduction

Nylo provides a `.env` file which contains global configuration variables like the app name, default locale and your App's environment.

This file is located at the root of your project named <b>".env"</b>.

``` env
APP_NAME=Nylo
APP_ENV=local
APP_DEBUG=true
APP_URL=https://nylo.dev

ASSET_PATH_PUBLIC=public/assets/
ASSET_PATH_IMAGES=public/assets/images
TIMEZONE=UTC
DEFAULT_LOCALE=en
```

You can add new variables here and then fetch them using the `getEnv()` helper.

<br>

#### Accessing values from the .env file

You can access your `.env` values anywhere in the app using the below helper.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

String appName = getEnv('APP_NAME');
```

<a name="environment-configuration"></a>
<br>

## Environment Configuration

Configuring your applications enviroment is simple. 

First open your `.env` file and then update the keys in the enviroment file.

You can also add addtional keys here e.g. `SHOW_ADS="false"`.

<b>Your .env file</b>
``` dart
APP_NAME="My Super App"
```

<b>Your Text widget</b>

``` dart
Text(
  "Hello, my app's name is " + getEnv('APP_NAME'),
  textAlign: TextAlign.center,
  overflow: TextOverflow.ellipsis,
  style: TextStyle(fontWeight: FontWeight.bold),
)
```

---

Best practises:

- Don't store anything sensitive or large.

- Don't commit your `.env` file to a (public/private) repository.

<a name="environment-variable-types"></a>
<br>

## Environment Variable Types

The values in your <b>.env</b> file are defined as `String`'s but Nylo will return them if they appear to be `Booleans` or `null` values.

| `.env` file | Return type |
|---|---|
| APP\_NAME="MySuper App" | `String` |
| DEBUG\_MODE=true | `Boolean`  |
| URL_TERMS=null | `null` |


<a name="retrieving-environment-values"></a>
<br>

## Retrieving Environment Values

Fetching values from your `.env` file is simple in Nylo, you can call the `getEnv(String key)` helper. 

``` dart
String appName = getEnv('APP_NAME');
```

<br>
You can also provide a <b>defaultValue</b> if the key doesn't exists in the .env file.

``` dart
String locale = getEnv('DEFAULT_LOCALE', defaultValue: "en");

int httpConnectionTimeout = getEnv('HTTP_CONNECTION_TIMEOUT', defaultValue: (60 * 1000));
```

<a name="environment-flavours"></a>
<br>

## Environment flavours

In Nylo, you can build your application from different environment 'flavours'. This allows you to **create** separate `.env` files e.g. 'production', 'staging' or 'developing' and then build your app from the configuration.

### Creating an environment flavour

First, make a new file at the root level of your project, e.g. `.env.production`. Then, you can define all your env variables in this file.

Next, add the new env file to your **'assets'** in the `pubspec.yaml` file.

**pubspec.yaml**

``` dart
  assets:
    ...
    - .env
    - .env.production // -- new
```

Then, from the terminal, build your flutter app by specifying the **env** file like in the below example. 

``` bash
flutter run --dart-define="ENV_FILE=.env.production"

flutter run --dart-define="ENV_FILE=.env.developing"

// or build

flutter build ios --dart-define="ENV_FILE=.env.developing"

flutter build appbundle --dart-define="ENV_FILE=.env.developing"
```

> The `ENV_FILE` will default to `.env`.
