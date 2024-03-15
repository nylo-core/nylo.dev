# Scheduler

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Schedule Once](#schedule-once "Schedule a task to run once")
- [Schedule Once After Date](#schedule-once-after-date "Schedule a task to run once after a specific date")
- [Schedule Once Daily](#schedule-once-daily "Schedule a task to run once daily")

<a name="introduction"></a>
<br>

## Introduction

Nylo allows you to schedule tasks in your app to happen once, daily, or after a specific date.

After reading this documentation, you will learn how to schedule tasks in your app.

<a name="schedule-once"></a>
<br>

## Schedule Once

You can schedule a task to run once using the `Nylo.scheduleOnce` method.

A simple example of how to use this method:

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("Perform code here to run once");
});
```

<a name="schedule-once-after-date"></a>
<br>

## Schedule Once After Date

You can schedule a task to run once after a specific date using the `Nylo.scheduleOnceAfterDate` method.

A simple example of how to use this method:

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('Perform code to run once after DateTime(2025, 04, 10)');
}, date: DateTime(2025, 04, 10));
```

<a name="schedule-once-daily"></a>
<br>

## Schedule Once Daily

You can schedule a task to run once daily using the `Nylo.scheduleOnceDaily` method.

A simple example of how to use this method:

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("Perform code to run once daily");
});
```
