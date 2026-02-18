# App Usage

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Configuração](#setup "Configurando o uso do app")
- Monitoramento
    - [Inicializações do app](#monitoring-app-launches "Monitorando inicializações do app")
    - [Data da primeira inicialização](#monitoring-app-first-launch-date "Monitorando a data da primeira inicialização do app")
    - [Total de dias desde a primeira inicialização](#monitoring-app-total-days-since-first-launch "Monitorando o total de dias desde a primeira inicialização do app")

<div id="introduction"></div>

## Introdução

Nylo permite que você monitore o uso do seu app de forma nativa, mas primeiro você precisa habilitar o recurso em um dos seus app providers.

Atualmente, Nylo pode monitorar o seguinte:

- Inicializações do app
- Data da primeira inicialização

Após ler esta documentação, você aprenderá como monitorar o uso do seu app.

<div id="setup"></div>

## Configuração

Abra o arquivo `app/providers/app_provider.dart`.

Em seguida, adicione o seguinte código ao seu método `boot`.

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // Enable app usage monitoring
    );
```

Isso habilitará o monitoramento de uso do app na sua aplicação. Se você precisar verificar se o monitoramento de uso do app está habilitado, pode usar o método `Nylo.instance.shouldMonitorAppUsage()`.

<div id="monitoring-app-launches"></div>

## Monitorando Inicializações do App

Você pode monitorar o número de vezes que seu app foi inicializado usando o método `Nylo.appLaunchCount`.

> As inicializações do app são contadas cada vez que o app é aberto a partir de um estado fechado.

Um exemplo simples de como usar este método:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## Monitorando a Data da Primeira Inicialização do App

Você pode monitorar a data em que seu app foi inicializado pela primeira vez usando o método `Nylo.appFirstLaunchDate`.

Aqui está um exemplo de como usar este método:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## Monitorando o Total de Dias Desde a Primeira Inicialização do App

Você pode monitorar o total de dias desde que seu app foi inicializado pela primeira vez usando o método `Nylo.appTotalDaysSinceFirstLaunch`.

Aqui está um exemplo de como usar este método:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
