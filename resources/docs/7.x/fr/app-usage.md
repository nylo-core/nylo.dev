# Utilisation de l'application

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Configuration](#setup "Configuration de l'utilisation de l'application")
- Surveillance
    - [Lancements de l'application](#monitoring-app-launches "Surveillance des lancements de l'application")
    - [Date du premier lancement](#monitoring-app-first-launch-date "Surveillance de la date du premier lancement")
    - [Nombre total de jours depuis le premier lancement](#monitoring-app-total-days-since-first-launch "Surveillance du nombre total de jours depuis le premier lancement")

<div id="introduction"></div>

## Introduction

Nylo vous permet de surveiller l'utilisation de votre application de maniere native, mais vous devez d'abord activer la fonctionnalite dans l'un de vos fournisseurs d'application.

Actuellement, Nylo peut surveiller les elements suivants :

- Lancements de l'application
- Date du premier lancement

Apres avoir lu cette documentation, vous saurez comment surveiller l'utilisation de votre application.

<div id="setup"></div>

## Configuration

Ouvrez votre fichier `app/providers/app_provider.dart`.

Ensuite, ajoutez le code suivant a votre methode `boot`.

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // Enable app usage monitoring
    );
```

Cela activera la surveillance de l'utilisation de l'application. Si vous avez besoin de verifier si la surveillance est activee, vous pouvez utiliser la methode `Nylo.instance.shouldMonitorAppUsage()`.

<div id="monitoring-app-launches"></div>

## Surveillance des lancements de l'application

Vous pouvez surveiller le nombre de fois que votre application a ete lancee en utilisant la methode `Nylo.appLaunchCount`.

> Les lancements de l'application sont comptes chaque fois que l'application est ouverte depuis un etat ferme.

Un exemple simple d'utilisation de cette methode :

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## Surveillance de la date du premier lancement

Vous pouvez surveiller la date a laquelle votre application a ete lancee pour la premiere fois en utilisant la methode `Nylo.appFirstLaunchDate`.

Voici un exemple d'utilisation de cette methode :

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## Surveillance du nombre total de jours depuis le premier lancement

Vous pouvez surveiller le nombre total de jours depuis le premier lancement de votre application en utilisant la methode `Nylo.appTotalDaysSinceFirstLaunch`.

Voici un exemple d'utilisation de cette methode :

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
