# Scheduler

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Pianifica una volta](#schedule-once "Pianifica una volta")
- [Pianifica una volta dopo una data](#schedule-once-after-date "Pianifica una volta dopo una data")
- [Pianifica una volta al giorno](#schedule-once-daily "Pianifica una volta al giorno")

<div id="introduction"></div>

## Introduzione

Nylo ti permette di pianificare attivita' nella tua app da eseguire una volta, giornalmente o dopo una data specifica.

Dopo aver letto questa documentazione, imparerai come pianificare attivita' nella tua app.

<div id="schedule-once"></div>

## Pianifica una Volta

Puoi pianificare un'attivita' da eseguire una sola volta usando il metodo `Nylo.scheduleOnce`.

Un semplice esempio di come usare questo metodo:

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("Perform code here to run once");
});
```

<div id="schedule-once-after-date"></div>

## Pianifica una Volta dopo una Data

Puoi pianificare un'attivita' da eseguire una sola volta dopo una data specifica usando il metodo `Nylo.scheduleOnceAfterDate`.

Un semplice esempio di come usare questo metodo:

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('Perform code to run once after DateTime(2025, 04, 10)');
}, date: DateTime(2025, 04, 10));
```

<div id="schedule-once-daily"></div>

## Pianifica una Volta al Giorno

Puoi pianificare un'attivita' da eseguire una volta al giorno usando il metodo `Nylo.scheduleOnceDaily`.

Un semplice esempio di come usare questo metodo:

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("Perform code to run once daily");
});
```
