# App Usage

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Setup](#setup "Setting up app usage")
    - Monitoring
        - [App launches](#monitoring-app-launches "Monitoring app launches")
        - [First launch date](#monitoring-app-first-launch-date "Monitoring app first launch date")
        - [Total days since first launch](#monitoring-app-total-days-since-first-launch "Monitoring app total days since first launch")

<div id="introduction"></div>
<br>

## Introduction

Nylo allows you to monitor your app usage out of the box but first you need to enable the feature in one of your app providers.

Currently, Nylo can monitor the following:

- App launches
- First launch date

After reading this documentation, you will learn how to monitor your app usage.

<div id="setup"></div>
<br>

## Setup

Open your `app/providers/app_provider.dart` file.

Then, add the following code to your `boot` method.

```dart
class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    ...

    nylo.monitorAppUsage(); // Add this line to monitor app usage
```

This will enable app usage monitoring in your app. If you ever need to check if app usage monitoring is enabled, you can use the `Nylo.instance.shouldMonitorAppUsage()` method.

<div id="monitoring-app-launches"></div>
<br>

## Monitoring App Launches

You can monitor the number of times your app has been launched using the `Nylo.appLaunchCount` method.

> App launches are counted each time the app is opened from a closed state.

A simple example of how to use this method:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>
<br>

## Monitoring App First Launch Date

You can monitor the date your app was first launched using the `Nylo.appFirstLaunchDate` method.

Here's an example of how to use this method:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>
<br>

## Monitoring App Total Days Since First Launch

You can monitor the total days since your app was first launched using the `Nylo.appTotalDaysSinceFirstLaunch` method.

Here's an example of how to use this method:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
