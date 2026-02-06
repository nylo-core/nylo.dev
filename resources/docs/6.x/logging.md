# Logging

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Log Levels](#log-levels "Log Levels")
- [Helpers](#helpers "Helpers")


<div id="introduction"></div>

## Introduction

To print information to the console, you can use one of the following:

- `printInfo(dynamic message)`
- `printDebug(dynamic message)`
- `printError(dynamic message)`

The above helpers will only print to the console if the `APP_DEBUG` variable in your **.env** file is set to `true`.

``` dart
// .env
APP_DEBUG=true
```

This is useful when you want to print information to the console during development, but not in production.

Here's an example using the helpers:


``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

String name = 'Anthony';
String city = 'London';
int age = 18;

printInfo(name); // [info] Anthony
printDebug(age); // [debug] 18
printError(city); // [error] London
```

You can also use the `dump` helper to print data to the console.

``` dart
String city = 'London';
int age = 18;

age.dump(); // 18

dump(city); // London
```

#### Why use NyLogger?

NyLogger may appear similar to `print` in Flutter, however, there's more to it. 

If your application's **.env** variable `APP_DEBUG` is set to false, NyLogger will **not** print to the console.

In some scenarios you may still want to print while your application's APP_DEBUG is false, the `showNextLog` helper can be used for that.

``` dart
// .env
APP_DEBUG=false

// usage for showNextLog
String name = 'Anthony';
String country = 'UK';
List<String> favouriteCountries = ['Spain', 'USA', 'Canada'];

printInfo(name); // Will not print
printInfo(country); // Will not print

showNextLog();
printInfo(country); // UK

printDebug(favouriteCountries); // Will not print
```

<div id="log-levels"></div>

## Log Levels

You can use the following log levels:

- [info] - `printInfo(dynamic message)`
- [debug] - `printDebug(dynamic message)`
- [error] - `printError(dynamic message)`


<div id="helpers"></div>

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
