# 应用使用情况

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [设置](#setup "设置")
- 监控
    - [应用启动次数](#monitoring-app-launches "应用启动次数")
    - [首次启动日期](#monitoring-app-first-launch-date "首次启动日期")
    - [自首次启动以来的总天数](#monitoring-app-total-days-since-first-launch "自首次启动以来的总天数")

<div id="introduction"></div>

## 简介

Nylo 允许您开箱即用地监控应用使用情况，但首先您需要在其中一个应用 provider 中启用该功能。

目前，Nylo 可以监控以下内容：

- 应用启动次数
- 首次启动日期

阅读本文档后，您将了解如何监控应用使用情况。

<div id="setup"></div>

## 设置

打开您的 `app/providers/app_provider.dart` 文件。

然后，将以下代码添加到您的 `boot` 方法中。

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // Enable app usage monitoring
    );
```

这将在您的应用中启用应用使用监控。如果您需要检查应用使用监控是否已启用，可以使用 `Nylo.instance.shouldMonitorAppUsage()` 方法。

<div id="monitoring-app-launches"></div>

## 监控应用启动次数

您可以使用 `Nylo.appLaunchCount` 方法监控应用被启动的次数。

> 每次应用从关闭状态打开时，启动次数都会增加。

以下是使用此方法的简单示例：

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## 监控应用首次启动日期

您可以使用 `Nylo.appFirstLaunchDate` 方法监控应用首次启动的日期。

以下是使用此方法的示例：

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## 监控自首次启动以来的总天数

您可以使用 `Nylo.appTotalDaysSinceFirstLaunch` 方法监控自应用首次启动以来的总天数。

以下是使用此方法的示例：

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
