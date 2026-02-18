# Zamanlayıcı

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Bir Kez Zamanla](#schedule-once "Bir Kez Zamanla")
- [Tarihten Sonra Bir Kez Zamanla](#schedule-once-after-date "Tarihten Sonra Bir Kez Zamanla")
- [Günlük Bir Kez Zamanla](#schedule-once-daily "Günlük Bir Kez Zamanla")

<div id="introduction"></div>

## Giriş

Nylo, uygulamanızda görevleri bir kez, günlük veya belirli bir tarihten sonra gerçekleşecek şekilde zamanlamanıza olanak tanır.

Bu dokümantasyonu okuduktan sonra, uygulamanızda görevleri nasıl zamanlayacağınızı öğreneceksiniz.

<div id="schedule-once"></div>

## Bir Kez Zamanla

`Nylo.scheduleOnce` metodunu kullanarak bir görevi bir kez çalıştıracak şekilde zamanlayabilirsiniz.

Bu metodun nasıl kullanılacağına dair basit bir örnek:

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("Perform code here to run once");
});
```

<div id="schedule-once-after-date"></div>

## Tarihten Sonra Bir Kez Zamanla

`Nylo.scheduleOnceAfterDate` metodunu kullanarak belirli bir tarihten sonra bir kez çalıştıracak şekilde bir görevi zamanlayabilirsiniz.

Bu metodun nasıl kullanılacağına dair basit bir örnek:

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('Perform code to run once after DateTime(2025, 04, 10)');
}, date: DateTime(2025, 04, 10));
```

<div id="schedule-once-daily"></div>

## Günlük Bir Kez Zamanla

`Nylo.scheduleOnceDaily` metodunu kullanarak günlük bir kez çalıştıracak şekilde bir görevi zamanlayabilirsiniz.

Bu metodun nasıl kullanılacağına dair basit bir örnek:

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("Perform code to run once daily");
});
```
