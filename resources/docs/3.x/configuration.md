# Configuration

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to configuration in Nylo")
- Environment
  - [Configuration](#environment-configuration "Environment configuration")
  - [Variable Types](#environment-variable-types "Environment variable types")
  - [Retrieving Values](#retrieving-environment-values "Retrieving environment values")

<div id="introduction"></div>
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

<div id="environment-configuration"></div>
<br>

## Environment Configuration

Configuring your applications environment is simple. 

First open your `.env` file and then update the keys in the environment file.

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

<div id="environment-variable-types"></div>
<br>

## Environment Variable Types

The values in your <b>.env</b> file are defined as `String`'s but Nylo will return them if they appear to be `Booleans` or `null` values.

| `.env` file | Return type |
|---|---|
| APP\_NAME="MySuper App" | `String` |
| DEBUG\_MODE=true | `Boolean`  |
| URL_TERMS=null | `null` |


<div id="retrieving-environment-values"></div>
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
