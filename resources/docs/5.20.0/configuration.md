# Configuration

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to configuration in {{ config('app.name') }}")
- Environment
  - [Configuration](#environment-configuration "Environment configuration")
  - [Variable Types](#environment-variable-types "Environment variable types")
  - [Retrieving Values](#retrieving-environment-values "Retrieving environment values")
- [Environment flavours](#environment-flavours "Environment flavours")
<div id="introduction"></div>
<br>
## Introduction

{{ config('app.name') }} provides a `.env` file which contains global configuration variables like the app name, default locale and your App's environment.

This file is located at the root of your project, named <b>".env"</b>.

```
APP_NAME={{ config('app.name') }}
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

<div id="environment-configuration"></div>
<br>

## Environment Configuration

Configuring your applications environment is simple. 

First open your `.env` file and then update the keys in the environment file.

You can also add additional keys here, e.g. `SHOW_ADS="false"`.

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

<div id="environment-variable-types"></div>
<br>

## Environment Variable Types

The values in your <b>.env</b> file are defined as `String`'s but {{ config('app.name') }} will return them differently if they match a type e.g. `Boolean` or `null`.

| `.env` file | Return type |
|---|---|
| APP\_NAME="MySuper App" | `String` |
| DEBUG\_MODE=true | `Boolean`  |
| URL_TERMS=null | `null` |


<div id="retrieving-environment-values"></div>
<br>

## Retrieving Environment Values

Fetching values from your `.env` file is simple in {{ config('app.name') }}, you can call the `getEnv(String key)` helper. 

``` dart
String appName = getEnv('APP_NAME');
```

<br>
You can also provide a <b>defaultValue</b> if the key doesn't exist in the .env file.

``` dart
String locale = getEnv('DEFAULT_LOCALE', defaultValue: "en");

int httpConnectionTimeout = getEnv('HTTP_CONNECTION_TIMEOUT', defaultValue: (60 * 1000));
```

<div id="environment-flavours"></div>
<br>

## Environment flavours

In {{ config('app.name') }}, you can build your application from different environment 'flavours'. This allows you to **create** separate `.env` files e.g. 'production', 'staging' or 'developing' and then build your app from the configuration.

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

Then, from the terminal, build your Flutter app by specifying the **env** file like in the below example. 

``` bash
flutter run --dart-define="ENV_FILE=.env.production"

flutter run --dart-define="ENV_FILE=.env.developing"

// or build

flutter build ios --dart-define="ENV_FILE=.env.developing"

flutter build appbundle --dart-define="ENV_FILE=.env.developing"
```

> The `ENV_FILE` will default to `.env`
