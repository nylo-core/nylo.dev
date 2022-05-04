# Configuration

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to configuration in Nylo")
- Environment
  - [Configuration](#environment-configuration "Environment configuration")
  - [Variable Types](#environment-variable-types "Environment variable types")
  - [Retriving Values](#retrieving-environment-values "Retriving environment values")

<a name="introduction"></a>
<br>
## Introduction

Nylo provides a `.env` file which contains global configuration variables like the App Name, default locale and API keys.

You can access these values anywhere in the app using the below helper.

``` dart
import 'package:nylo_support/helpers/helper.dart';
...

String appName = getEnv('APP_NAME');
```

The `.env` file can be found at the root of the project and should look similar to the below.

You can add new variables here such as strings and booleans.
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

<a name="environment-configuration"></a>
<br>
## Environment configuration

Environment variables are great for using anywhere in our applications. A quick example would be if we wanted to set the name of our application to e.g. `MySuper App` and then use the same value anywhere in our widgets.

<br>

#### Your .env file
``` dart
APP_NAME="My Super App"
```

#### Your Text widget

``` dart
Text(
  "Hello, my app's name is " + getEnv('APP_NAME'),
  textAlign: TextAlign.center,
  overflow: TextOverflow.ellipsis,
  style: TextStyle(fontWeight: FontWeight.bold),
)
```

---

To add global configuration variables to your project, you can use the `.env` file to store add any sensitive values too.
This file is useful for adding API keys and any information you consider sensitive. It could also be used for anything you might need to access globally throughout your app.

> {danger} It's important to note that this file should never be committed to your repository (if using version control). By default, it's included within your .gitignore file.

<a name="environment-variable-types"></a>
<br>

## Environment Variable Types

The `.env` values are set as type `string` when you are defining them but Nylo will return some values types in a more helpful way like `booleans` and `null` values.

| `.env` file | `.env` return type |
| : |   :-   |
| DEBUG\_MODE=true |   (bool) true   |
| SHOW\_ADS=false | (bool) false |
| APP\_NAME="MySuper App" | (string) "My Super App"   |
| MAPS\_API\_KEY=null | (null) null  |


<a name="retrieving-environment-values"></a>
<br>

## Retrieving environment values

Fetching values from your `.env` file is simple in Nylo, you can call the `getEnv(String key)` function. 

``` dart
String appName = getEnv('APP_NAME');
```

<br>
You can also provide a `defaultValue` if the key doesn't exists in the .env file.

``` dart
String locale = getEnv('DEFAULT_LOCALE', defaultValue: "en");

int httpConnectionTimeout = getEnv('HTTP_CONNECTION_TIMEOUT', defaultValue: (60 * 1000));
```
