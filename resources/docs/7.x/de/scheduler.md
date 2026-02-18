# Scheduler

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Einmalig planen](#schedule-once "Einmalig planen")
- [Einmalig nach Datum planen](#schedule-once-after-date "Einmalig nach Datum planen")
- [Einmalig taeglich planen](#schedule-once-daily "Einmalig taeglich planen")

<div id="introduction"></div>

## Einleitung

Nylo ermoeglicht es Ihnen, Aufgaben in Ihrer App zu planen, die einmalig, taeglich oder nach einem bestimmten Datum ausgefuehrt werden.

Nach dem Lesen dieser Dokumentation werden Sie wissen, wie Sie Aufgaben in Ihrer App planen koennen.

<div id="schedule-once"></div>

## Einmalig planen

Sie koennen eine Aufgabe zur einmaligen Ausfuehrung mit der Methode `Nylo.scheduleOnce` planen.

Ein einfaches Beispiel fuer die Verwendung dieser Methode:

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("Perform code here to run once");
});
```

<div id="schedule-once-after-date"></div>

## Einmalig nach Datum planen

Sie koennen eine Aufgabe zur einmaligen Ausfuehrung nach einem bestimmten Datum mit der Methode `Nylo.scheduleOnceAfterDate` planen.

Ein einfaches Beispiel fuer die Verwendung dieser Methode:

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('Perform code to run once after DateTime(2025, 04, 10)');
}, date: DateTime(2025, 04, 10));
```

<div id="schedule-once-daily"></div>

## Einmalig taeglich planen

Sie koennen eine Aufgabe zur einmal taeglichen Ausfuehrung mit der Methode `Nylo.scheduleOnceDaily` planen.

Ein einfaches Beispiel fuer die Verwendung dieser Methode:

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("Perform code to run once daily");
});
```
