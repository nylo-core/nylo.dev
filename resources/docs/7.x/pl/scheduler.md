# Scheduler

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Zaplanuj jednorazowo](#schedule-once "Zaplanuj zadanie do jednorazowego wykonania")
- [Zaplanuj jednorazowo po dacie](#schedule-once-after-date "Zaplanuj zadanie do jednorazowego wykonania po okreslonej dacie")
- [Zaplanuj raz dziennie](#schedule-once-daily "Zaplanuj zadanie do wykonania raz dziennie")

<div id="introduction"></div>

## Wprowadzenie

Nylo pozwala planowac zadania w Twojej aplikacji do jednorazowego wykonania, codziennego lub po okreslonej dacie.

Po przeczytaniu tej dokumentacji nauczysz sie, jak planowac zadania w swojej aplikacji.

<div id="schedule-once"></div>

## Zaplanuj jednorazowo

Mozesz zaplanowac zadanie do jednorazowego wykonania za pomoca metody `Nylo.scheduleOnce`.

Prosty przyklad uzycia tej metody:

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("Wykonaj kod tutaj do jednorazowego uruchomienia");
});
```

<div id="schedule-once-after-date"></div>

## Zaplanuj jednorazowo po dacie

Mozesz zaplanowac zadanie do jednorazowego wykonania po okreslonej dacie za pomoca metody `Nylo.scheduleOnceAfterDate`.

Prosty przyklad uzycia tej metody:

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('Wykonaj kod do jednorazowego uruchomienia po DateTime(2025, 04, 10)');
}, date: DateTime(2025, 04, 10));
```

<div id="schedule-once-daily"></div>

## Zaplanuj raz dziennie

Mozesz zaplanowac zadanie do wykonania raz dziennie za pomoca metody `Nylo.scheduleOnceDaily`.

Prosty przyklad uzycia tej metody:

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("Wykonaj kod do uruchomienia raz dziennie");
});
```