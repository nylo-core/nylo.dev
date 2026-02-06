# Planificateur

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Executer une fois](#schedule-once "Planifier une tache a executer une fois")
- [Executer une fois apres une date](#schedule-once-after-date "Planifier une tache a executer une fois apres une date specifique")
- [Executer une fois par jour](#schedule-once-daily "Planifier une tache a executer une fois par jour")

<div id="introduction"></div>

## Introduction

Nylo vous permet de planifier des taches dans votre application pour qu'elles s'executent une fois, quotidiennement ou apres une date specifique.

Apres avoir lu cette documentation, vous saurez comment planifier des taches dans votre application.

<div id="schedule-once"></div>

## Executer une fois

Vous pouvez planifier une tache pour qu'elle s'execute une seule fois en utilisant la methode `Nylo.scheduleOnce`.

Un exemple simple d'utilisation de cette methode :

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("Perform code here to run once");
});
```

<div id="schedule-once-after-date"></div>

## Executer une fois apres une date

Vous pouvez planifier une tache pour qu'elle s'execute une seule fois apres une date specifique en utilisant la methode `Nylo.scheduleOnceAfterDate`.

Un exemple simple d'utilisation de cette methode :

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('Perform code to run once after DateTime(2025, 04, 10)');
}, date: DateTime(2025, 04, 10));
```

<div id="schedule-once-daily"></div>

## Executer une fois par jour

Vous pouvez planifier une tache pour qu'elle s'execute une fois par jour en utilisant la methode `Nylo.scheduleOnceDaily`.

Un exemple simple d'utilisation de cette methode :

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("Perform code to run once daily");
});
```
