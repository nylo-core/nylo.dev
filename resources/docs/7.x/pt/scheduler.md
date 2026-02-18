# Scheduler

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Agendar Uma Vez](#schedule-once "Agendar uma tarefa para executar uma vez")
- [Agendar Uma Vez Após uma Data](#schedule-once-after-date "Agendar uma tarefa para executar uma vez após uma data específica")
- [Agendar Uma Vez por Dia](#schedule-once-daily "Agendar uma tarefa para executar uma vez por dia")

<div id="introduction"></div>

## Introdução

O Nylo permite que você agende tarefas no seu app para acontecerem uma vez, diariamente ou após uma data específica.

Após ler esta documentação, você aprenderá como agendar tarefas no seu app.

<div id="schedule-once"></div>

## Agendar Uma Vez

Você pode agendar uma tarefa para executar uma vez usando o método `Nylo.scheduleOnce`.

Um exemplo simples de como usar este método:

```dart
Nylo.scheduleOnce('onboarding_info', () {
    print("Perform code here to run once");
});
```

<div id="schedule-once-after-date"></div>

## Agendar Uma Vez Após uma Data

Você pode agendar uma tarefa para executar uma vez após uma data específica usando o método `Nylo.scheduleOnceAfterDate`.

Um exemplo simples de como usar este método:

```dart
Nylo.scheduleOnceAfterDate('app_review_rating', () {
    print('Perform code to run once after DateTime(2025, 04, 10)');
}, date: DateTime(2025, 04, 10));
```

<div id="schedule-once-daily"></div>

## Agendar Uma Vez por Dia

Você pode agendar uma tarefa para executar uma vez por dia usando o método `Nylo.scheduleOnceDaily`.

Um exemplo simples de como usar este método:

```dart
Nylo.scheduleOnceDaily('free_daily_coins', () {
    print("Perform code to run once daily");
});
```
