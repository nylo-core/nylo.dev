# Scheduler

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Lên lịch một lần](#schedule-once "Lên lịch tác vụ chạy một lần")
- [Lên lịch một lần sau ngày](#schedule-once-after-date "Lên lịch tác vụ chạy một lần sau ngày cụ thể")
- [Lên lịch một lần mỗi ngày](#schedule-once-daily "Lên lịch tác vụ chạy một lần mỗi ngày")

<div id="introduction"></div>

## Giới thiệu

Nylo cho phép bạn lên lịch các tác vụ trong ứng dụng để thực hiện một lần, hàng ngày, hoặc sau một ngày cụ thể.

Sau khi đọc tài liệu này, bạn sẽ học được cách lên lịch các tác vụ trong ứng dụng của mình.

<div id="schedule-once"></div>

## Lên lịch một lần

Bạn có thể lên lịch một tác vụ chạy một lần bằng phương thức `Nylo.scheduleOnce`.

Một ví dụ đơn giản về cách sử dụng phương thức này:

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("Thực hiện mã ở đây để chạy một lần");
});
```

<div id="schedule-once-after-date"></div>

## Lên lịch một lần sau ngày

Bạn có thể lên lịch một tác vụ chạy một lần sau một ngày cụ thể bằng phương thức `Nylo.scheduleOnceAfterDate`.

Một ví dụ đơn giản về cách sử dụng phương thức này:

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('Thực hiện mã để chạy một lần sau DateTime(2025, 04, 10)');
}, date: DateTime(2025, 04, 10));
```

<div id="schedule-once-daily"></div>

## Lên lịch một lần mỗi ngày

Bạn có thể lên lịch một tác vụ chạy một lần mỗi ngày bằng phương thức `Nylo.scheduleOnceDaily`.

Một ví dụ đơn giản về cách sử dụng phương thức này:

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("Thực hiện mã để chạy một lần mỗi ngày");
});
```
