# 调度器

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [一次性调度](#schedule-once "调度任务只运行一次")
- [指定日期后一次性调度](#schedule-once-after-date "调度任务在特定日期后运行一次")
- [每日一次调度](#schedule-once-daily "调度任务每天运行一次")

<div id="introduction"></div>

## 简介

Nylo 允许您在应用中调度任务，使其只执行一次、每天执行或在特定日期后执行。

阅读本文档后，您将学会如何在应用中调度任务。

<div id="schedule-once"></div>

## 一次性调度

您可以使用 `Nylo.scheduleOnce` 方法调度任务只运行一次。

以下是如何使用此方法的简单示例：

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("Perform code here to run once");
});
```

<div id="schedule-once-after-date"></div>

## 指定日期后一次性调度

您可以使用 `Nylo.scheduleOnceAfterDate` 方法调度任务在特定日期后运行一次。

以下是如何使用此方法的简单示例：

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('Perform code to run once after DateTime(2025, 04, 10)');
}, date: DateTime(2025, 04, 10));
```

<div id="schedule-once-daily"></div>

## 每日一次调度

您可以使用 `Nylo.scheduleOnceDaily` 方法调度任务每天运行一次。

以下是如何使用此方法的简单示例：

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("Perform code to run once daily");
});
```
