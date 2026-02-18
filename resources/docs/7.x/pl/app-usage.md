# App Usage

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Konfiguracja](#setup "Konfiguracja monitorowania uzycia aplikacji")
- Monitorowanie
    - [Uruchomienia aplikacji](#monitoring-app-launches "Monitorowanie uruchomien aplikacji")
    - [Data pierwszego uruchomienia](#monitoring-app-first-launch-date "Monitorowanie daty pierwszego uruchomienia aplikacji")
    - [Calkowita liczba dni od pierwszego uruchomienia](#monitoring-app-total-days-since-first-launch "Monitorowanie calkowitej liczby dni od pierwszego uruchomienia aplikacji")

<div id="introduction"></div>

## Wprowadzenie

Nylo umozliwia monitorowanie uzycia aplikacji od razu po instalacji, ale najpierw musisz wlaczyc te funkcje w jednym z providerow aplikacji.

Obecnie Nylo moze monitorowac:

- Uruchomienia aplikacji
- Date pierwszego uruchomienia

Po przeczytaniu tej dokumentacji dowiesz sie, jak monitorowac uzycie swojej aplikacji.

<div id="setup"></div>

## Konfiguracja

Otworz plik `app/providers/app_provider.dart`.

Nastepnie dodaj ponizszy kod do metody `boot`.

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // Enable app usage monitoring
    );
```

To wlaczy monitorowanie uzycia aplikacji. Jesli kiedykolwiek bedziesz potrzebowal sprawdzic, czy monitorowanie uzycia aplikacji jest wlaczone, mozesz uzyc metody `Nylo.instance.shouldMonitorAppUsage()`.

<div id="monitoring-app-launches"></div>

## Monitorowanie uruchomien aplikacji

Mozesz monitorowac liczbe uruchomien aplikacji za pomoca metody `Nylo.appLaunchCount`.

> Uruchomienia aplikacji sa liczone za kazdym razem, gdy aplikacja jest otwierana ze stanu zamknietego.

Prosty przyklad uzycia tej metody:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## Monitorowanie daty pierwszego uruchomienia aplikacji

Mozesz monitorowac date pierwszego uruchomienia aplikacji za pomoca metody `Nylo.appFirstLaunchDate`.

Oto przyklad uzycia tej metody:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## Monitorowanie calkowitej liczby dni od pierwszego uruchomienia

Mozesz monitorowac calkowita liczbe dni od pierwszego uruchomienia aplikacji za pomoca metody `Nylo.appTotalDaysSinceFirstLaunch`.

Oto przyklad uzycia tej metody:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
