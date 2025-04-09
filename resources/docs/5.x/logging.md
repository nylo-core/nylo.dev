# Logging

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Log Levels](#log-levels "Log Levels")
- [Helpers](#helpers "Helpers")


<div id="introduction"></div>
<br>

## Introduction

When you need to know what's happening in your application, use the `NyLogger` class. **{{ config('app.name') }}** provides a reliable logging tool you can use to **print** information to the console.

Example using `NyLogger`


``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

String name = 'Anthony';
int age = 18;

NyLogger.info(name); // Anthony

NyLogger.debug(age); // 18
```

#### Why use NyLogger?

NyLogger may appear similar to `print` in Flutter, however, there's more to it. 

If your application's **.env** variable `APP_DEBUG` is set to false, NyLogger will **not** print to the console.

In some scenarios you may want to print while your application's APP_DEBUG is false, the `showNextLog` helper can be used for that.

``` dart
// .env
APP_DEBUG=false

// usage for showNextLog
String name = 'Anthony';
String country = 'UK';
List<String> favouriteCountries = ['Spain', 'USA', 'Canada'];

NyLogger.info(name);

showNextLog();
NyLogger.debug(country); // UK

NyLogger.debug(favouriteCountries);
```

<div id="log-levels"></div>
<br>

## Log Levels

You can use the following log levels:

- NyLogger.info(dynamic message)
- NyLogger.debug(dynamic message)
- NyLogger.dump(dynamic message)
- NyLogger.error(dynamic message)
- NyLogger.json(dynamic message)


<div id="helpers"></div>
<br>

## Helpers

You can print data easily using the `dump` or `dd` extension helpers.
They can be called from your objects, like in the below example.

``` dart
String project = 'Nylo';
List<String> seasons = ['Spring', 'Summer', 'Fall', 'Winter'];

project.dump(); // 'Nylo'
seasons.dump(); // ['Spring', 'Summer', 'Fall', 'Winter']

String code = 'Dart';

code.dd(); // Prints: 'Dart' and exits the code
```