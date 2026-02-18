# App Usage

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Configuracion](#setup "Configurar el uso de la aplicacion")
- Monitoreo
    - [Lanzamientos de la aplicacion](#monitoring-app-launches "Monitorear lanzamientos de la aplicacion")
    - [Fecha del primer lanzamiento](#monitoring-app-first-launch-date "Monitorear la fecha del primer lanzamiento")
    - [Total de dias desde el primer lanzamiento](#monitoring-app-total-days-since-first-launch "Monitorear el total de dias desde el primer lanzamiento")

<div id="introduction"></div>

## Introduccion

Nylo te permite monitorear el uso de tu aplicacion de forma inmediata, pero primero necesitas habilitar la funcion en uno de los proveedores de tu aplicacion.

Actualmente, Nylo puede monitorear lo siguiente:

- Lanzamientos de la aplicacion
- Fecha del primer lanzamiento

Despues de leer esta documentacion, aprenderas como monitorear el uso de tu aplicacion.

<div id="setup"></div>

## Configuracion

Abre tu archivo `app/providers/app_provider.dart`.

Luego, agrega el siguiente codigo a tu metodo `boot`.

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // Enable app usage monitoring
    );
```

Esto habilitara el monitoreo de uso de la aplicacion en tu app. Si alguna vez necesitas verificar si el monitoreo de uso esta habilitado, puedes usar el metodo `Nylo.instance.shouldMonitorAppUsage()`.

<div id="monitoring-app-launches"></div>

## Monitorear lanzamientos de la aplicacion

Puedes monitorear el numero de veces que tu aplicacion ha sido lanzada usando el metodo `Nylo.appLaunchCount`.

> Los lanzamientos de la aplicacion se cuentan cada vez que la app se abre desde un estado cerrado.

Un ejemplo simple de como usar este metodo:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## Monitorear la fecha del primer lanzamiento

Puedes monitorear la fecha en que tu aplicacion fue lanzada por primera vez usando el metodo `Nylo.appFirstLaunchDate`.

Aqui tienes un ejemplo de como usar este metodo:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## Monitorear el total de dias desde el primer lanzamiento

Puedes monitorear el total de dias desde que tu aplicacion fue lanzada por primera vez usando el metodo `Nylo.appTotalDaysSinceFirstLaunch`.

Aqui tienes un ejemplo de como usar este metodo:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
