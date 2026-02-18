# App-Nutzung

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Einrichtung](#setup "Einrichtung")
- Überwachung
    - [App-Starts](#monitoring-app-launches "App-Starts")
    - [Datum des ersten Starts](#monitoring-app-first-launch-date "Datum des ersten Starts")
    - [Gesamttage seit dem ersten Start](#monitoring-app-total-days-since-first-launch "Gesamttage seit dem ersten Start")

<div id="introduction"></div>

## Einleitung

Nylo ermöglicht es Ihnen, die Nutzung Ihrer App direkt zu überwachen, aber zunächst müssen Sie die Funktion in einem Ihrer App-Provider aktivieren.

Derzeit kann Nylo Folgendes überwachen:

- App-Starts
- Datum des ersten Starts

Nach dem Lesen dieser Dokumentation werden Sie wissen, wie Sie die Nutzung Ihrer App überwachen können.

<div id="setup"></div>

## Einrichtung

Öffnen Sie Ihre Datei `app/providers/app_provider.dart`.

Fügen Sie dann den folgenden Code zu Ihrer `boot`-Methode hinzu.

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // Enable app usage monitoring
    );
```

Dies aktiviert die App-Nutzungsüberwachung in Ihrer App. Wenn Sie jemals prüfen müssen, ob die App-Nutzungsüberwachung aktiviert ist, können Sie die Methode `Nylo.instance.shouldMonitorAppUsage()` verwenden.

<div id="monitoring-app-launches"></div>

## App-Starts überwachen

Sie können die Anzahl der App-Starts mit der Methode `Nylo.appLaunchCount` überwachen.

> App-Starts werden jedes Mal gezählt, wenn die App aus einem geschlossenen Zustand geöffnet wird.

Ein einfaches Beispiel zur Verwendung dieser Methode:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## Datum des ersten App-Starts überwachen

Sie können das Datum des ersten App-Starts mit der Methode `Nylo.appFirstLaunchDate` überwachen.

Hier ist ein Beispiel zur Verwendung dieser Methode:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## Gesamttage seit dem ersten App-Start überwachen

Sie können die Gesamtanzahl der Tage seit dem ersten App-Start mit der Methode `Nylo.appTotalDaysSinceFirstLaunch` überwachen.

Hier ist ein Beispiel zur Verwendung dieser Methode:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
