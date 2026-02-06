# FutureWidget

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
- [Ladezustand anpassen](#customizing-loading "Ladezustand anpassen")
    - [Normaler Ladestil](#normal-loading "Normaler Ladestil")
    - [Skeletonizer-Ladestil](#skeletonizer-loading "Skeletonizer-Ladestil")
    - [Kein Ladestil](#no-loading "Kein Ladestil")
- [Fehlerbehandlung](#error-handling "Fehlerbehandlung")


<div id="introduction"></div>

## Einleitung

Das **FutureWidget** ist eine einfache Möglichkeit, `Future`s in Ihren {{ config('app.name') }}-Projekten darzustellen. Es umschließt Flutters `FutureBuilder` und bietet eine übersichtlichere API mit integrierten Ladezuständen.

Während Ihr Future in Bearbeitung ist, wird ein Ladeindikator angezeigt. Sobald das Future abgeschlossen ist, werden die Daten über den `child`-Callback zurückgegeben.

<div id="basic-usage"></div>

## Grundlegende Verwendung

Hier ist ein einfaches Beispiel für die Verwendung von `FutureWidget`:

``` dart
// A Future that takes 3 seconds to complete
Future<String> _findUserName() async {
  await sleep(3); // wait for 3 seconds
  return "John Doe";
}

// Use the FutureWidget
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: FutureWidget<String>(
         future: _findUserName(),
         child: (context, data) {
           // data = "John Doe"
           return Text(data!);
         },
       ),
    ),
  );
}
```

Das Widget verarbeitet automatisch den Ladezustand für Ihre Benutzer, bis das Future abgeschlossen ist.

<div id="customizing-loading"></div>

## Ladezustand anpassen

Sie können das Erscheinungsbild des Ladezustands mit dem Parameter `loadingStyle` anpassen.

<div id="normal-loading"></div>

### Normaler Ladestil

Verwenden Sie `LoadingStyle.normal()`, um ein Standard-Lade-Widget anzuzeigen. Sie können optional ein benutzerdefiniertes Kind-Widget bereitstellen:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading..."), // custom loading widget
  ),
)
```

Wenn kein Kind-Widget angegeben wird, wird der Standard-App-Loader von {{ config('app.name') }} angezeigt.

<div id="skeletonizer-loading"></div>

### Skeletonizer-Ladestil

Verwenden Sie `LoadingStyle.skeletonizer()`, um einen Skeleton-Ladeeffekt anzuzeigen. Dies eignet sich hervorragend, um Platzhalter-UI anzuzeigen, die Ihrem Inhaltslayout entspricht:

``` dart
FutureWidget<User>(
  future: _fetchUser(),
  child: (context, user) {
    return UserCard(user: user!);
  },
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()), // skeleton placeholder
    effect: SkeletonizerEffect.shimmer, // shimmer, pulse, or solid
  ),
)
```

Verfügbare Skeleton-Effekte:
- `SkeletonizerEffect.shimmer` - Animierter Schimmer-Effekt (Standard)
- `SkeletonizerEffect.pulse` - Pulsierender Animationseffekt
- `SkeletonizerEffect.solid` - Einfarbiger Effekt

<div id="no-loading"></div>

### Kein Ladestil

Verwenden Sie `LoadingStyle.none()`, um den Ladeindikator vollständig auszublenden:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.none(),
)
```

<div id="error-handling"></div>

## Fehlerbehandlung

Sie können Fehler von Ihrem Future mit dem `onError`-Callback behandeln:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  onError: (AsyncSnapshot snapshot) {
    print(snapshot.error.toString());
    return Text("Something went wrong");
  },
)
```

Wenn kein `onError`-Callback angegeben wird und ein Fehler auftritt, wird ein leeres `SizedBox.shrink()` angezeigt.

### Parameter

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `future` | `Future<T>?` | Das abzuwartende Future |
| `child` | `Widget Function(BuildContext, T?)` | Builder-Funktion, die aufgerufen wird, wenn das Future abgeschlossen ist |
| `loadingStyle` | `LoadingStyle?` | Ladeindikator anpassen |
| `onError` | `Widget Function(AsyncSnapshot)?` | Builder-Funktion, die bei einem Fehler des Futures aufgerufen wird |
