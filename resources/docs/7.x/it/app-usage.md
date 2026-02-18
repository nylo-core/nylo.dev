# Utilizzo dell'App

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Configurazione](#setup "Configurazione")
- Monitoraggio
    - [Avvii dell'app](#monitoring-app-launches "Avvii dell'app")
    - [Data del primo avvio](#monitoring-app-first-launch-date "Data del primo avvio")
    - [Giorni totali dal primo avvio](#monitoring-app-total-days-since-first-launch "Giorni totali dal primo avvio")

<div id="introduction"></div>

## Introduzione

Nylo ti permette di monitorare l'utilizzo della tua app in modo immediato, ma prima devi abilitare la funzionalita' in uno dei tuoi provider dell'app.

Attualmente, Nylo puo' monitorare quanto segue:

- Avvii dell'app
- Data del primo avvio

Dopo aver letto questa documentazione, imparerai come monitorare l'utilizzo della tua app.

<div id="setup"></div>

## Configurazione

Apri il file `app/providers/app_provider.dart`.

Quindi, aggiungi il seguente codice al tuo metodo `boot`.

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // Enable app usage monitoring
    );
```

Questo abilitera' il monitoraggio dell'utilizzo dell'app. Se hai bisogno di verificare se il monitoraggio dell'utilizzo dell'app e' abilitato, puoi usare il metodo `Nylo.instance.shouldMonitorAppUsage()`.

<div id="monitoring-app-launches"></div>

## Monitoraggio degli Avvii dell'App

Puoi monitorare il numero di volte in cui la tua app e' stata avviata usando il metodo `Nylo.appLaunchCount`.

> Gli avvii dell'app vengono conteggiati ogni volta che l'app viene aperta da uno stato chiuso.

Un semplice esempio di come usare questo metodo:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## Monitoraggio della Data del Primo Avvio dell'App

Puoi monitorare la data in cui la tua app e' stata avviata per la prima volta usando il metodo `Nylo.appFirstLaunchDate`.

Ecco un esempio di come usare questo metodo:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## Monitoraggio dei Giorni Totali dal Primo Avvio dell'App

Puoi monitorare il numero totale di giorni trascorsi dal primo avvio della tua app usando il metodo `Nylo.appTotalDaysSinceFirstLaunch`.

Ecco un esempio di come usare questo metodo:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
