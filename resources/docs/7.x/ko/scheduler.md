# Scheduler

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [한 번 예약](#schedule-once "한 번 예약")
- [특정 날짜 이후 한 번 예약](#schedule-once-after-date "특정 날짜 이후 한 번 예약")
- [매일 한 번 예약](#schedule-once-daily "매일 한 번 예약")

<div id="introduction"></div>

## 소개

Nylo를 사용하면 앱에서 한 번, 매일, 또는 특정 날짜 이후에 실행되도록 작업을 예약할 수 있습니다.

이 문서를 읽은 후, 앱에서 작업을 예약하는 방법을 배우게 됩니다.

<div id="schedule-once"></div>

## 한 번 예약

`Nylo.scheduleOnce` 메서드를 사용하여 작업을 한 번만 실행하도록 예약할 수 있습니다.

이 메서드를 사용하는 간단한 예시:

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("Perform code here to run once");
});
```

<div id="schedule-once-after-date"></div>

## 특정 날짜 이후 한 번 예약

`Nylo.scheduleOnceAfterDate` 메서드를 사용하여 특정 날짜 이후에 작업을 한 번만 실행하도록 예약할 수 있습니다.

이 메서드를 사용하는 간단한 예시:

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('Perform code to run once after DateTime(2025, 04, 10)');
}, date: DateTime(2025, 04, 10));
```

<div id="schedule-once-daily"></div>

## 매일 한 번 예약

`Nylo.scheduleOnceDaily` 메서드를 사용하여 매일 한 번 실행되도록 작업을 예약할 수 있습니다.

이 메서드를 사용하는 간단한 예시:

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("Perform code to run once daily");
});
```
