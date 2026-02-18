# App Usage

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Настройка](#setup "Настройка мониторинга использования приложения")
- Мониторинг
    - [Запуски приложения](#monitoring-app-launches "Мониторинг запусков приложения")
    - [Дата первого запуска](#monitoring-app-first-launch-date "Мониторинг даты первого запуска приложения")
    - [Общее количество дней с первого запуска](#monitoring-app-total-days-since-first-launch "Мониторинг общего количества дней с первого запуска приложения")

<div id="introduction"></div>

## Введение

Nylo позволяет отслеживать использование вашего приложения из коробки, но сначала необходимо включить эту функцию в одном из провайдеров приложения.

В настоящее время Nylo может отслеживать следующее:

- Запуски приложения
- Дату первого запуска

После прочтения этой документации вы узнаете, как отслеживать использование вашего приложения.

<div id="setup"></div>

## Настройка

Откройте файл `app/providers/app_provider.dart`.

Затем добавьте следующий код в метод `boot`.

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // Enable app usage monitoring
    );
```

Это включит мониторинг использования приложения. Если вам когда-либо понадобится проверить, включён ли мониторинг использования приложения, вы можете использовать метод `Nylo.instance.shouldMonitorAppUsage()`.

<div id="monitoring-app-launches"></div>

## Мониторинг запусков приложения

Вы можете отслеживать количество запусков вашего приложения с помощью метода `Nylo.appLaunchCount`.

> Запуски приложения подсчитываются каждый раз, когда приложение открывается из закрытого состояния.

Простой пример использования этого метода:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## Мониторинг даты первого запуска приложения

Вы можете отслеживать дату первого запуска вашего приложения с помощью метода `Nylo.appFirstLaunchDate`.

Вот пример использования этого метода:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## Мониторинг общего количества дней с первого запуска

Вы можете отслеживать общее количество дней с момента первого запуска вашего приложения с помощью метода `Nylo.appTotalDaysSinceFirstLaunch`.

Вот пример использования этого метода:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
