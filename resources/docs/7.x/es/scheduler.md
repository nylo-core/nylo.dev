# Scheduler

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Programar una Vez](#schedule-once "Programar una tarea para ejecutar una vez")
- [Programar una Vez Despues de una Fecha](#schedule-once-after-date "Programar una tarea para ejecutar una vez despues de una fecha especifica")
- [Programar una Vez al Dia](#schedule-once-daily "Programar una tarea para ejecutar una vez al dia")

<div id="introduction"></div>

## Introduccion

Nylo te permite programar tareas en tu aplicacion para que se ejecuten una vez, diariamente o despues de una fecha especifica.

Despues de leer esta documentacion, aprenderas como programar tareas en tu aplicacion.

<div id="schedule-once"></div>

## Programar una Vez

Puedes programar una tarea para que se ejecute una vez usando el metodo `Nylo.scheduleOnce`.

Un ejemplo simple de como usar este metodo:

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("Perform code here to run once");
});
```

<div id="schedule-once-after-date"></div>

## Programar una Vez Despues de una Fecha

Puedes programar una tarea para que se ejecute una vez despues de una fecha especifica usando el metodo `Nylo.scheduleOnceAfterDate`.

Un ejemplo simple de como usar este metodo:

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('Perform code to run once after DateTime(2025, 04, 10)');
}, date: DateTime(2025, 04, 10));
```

<div id="schedule-once-daily"></div>

## Programar una Vez al Dia

Puedes programar una tarea para que se ejecute una vez al dia usando el metodo `Nylo.scheduleOnceDaily`.

Un ejemplo simple de como usar este metodo:

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("Perform code to run once daily");
});
```
