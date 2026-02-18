# スケジューラー

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [一度だけ実行するスケジュール](#schedule-once "一度だけ実行するスケジュール")
- [特定日付以降に一度だけ実行するスケジュール](#schedule-once-after-date "特定日付以降に一度だけ実行するスケジュール")
- [毎日一度実行するスケジュール](#schedule-once-daily "毎日一度実行するスケジュール")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} では、アプリ内でタスクを一度だけ、毎日、または特定の日付以降に実行するようにスケジュールできます。

このドキュメントを読むことで、アプリ内でタスクをスケジュールする方法を学べます。

<div id="schedule-once"></div>

## 一度だけ実行するスケジュール

`Nylo.scheduleOnce` メソッドを使用して、タスクを一度だけ実行するようにスケジュールできます。

このメソッドの簡単な使用例:

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("ここのコードは一度だけ実行されます");
});
```

<div id="schedule-once-after-date"></div>

## 特定日付以降に一度だけ実行するスケジュール

`Nylo.scheduleOnceAfterDate` メソッドを使用して、特定の日付以降にタスクを一度だけ実行するようにスケジュールできます。

このメソッドの簡単な使用例:

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('DateTime(2025, 04, 10) 以降に一度だけ実行されるコード');
}, date: DateTime(2025, 04, 10));
```

<div id="schedule-once-daily"></div>

## 毎日一度実行するスケジュール

`Nylo.scheduleOnceDaily` メソッドを使用して、タスクを毎日一度実行するようにスケジュールできます。

このメソッドの簡単な使用例:

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("毎日一度実行されるコード");
});
```
