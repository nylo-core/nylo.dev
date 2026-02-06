# FutureWidget

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
- [Personalizzazione dello Stato di Caricamento](#customizing-loading "Personalizzazione dello Stato di Caricamento")
    - [Stile di Caricamento Normale](#normal-loading "Stile di Caricamento Normale")
    - [Stile di Caricamento Skeletonizer](#skeletonizer-loading "Stile di Caricamento Skeletonizer")
    - [Nessuno Stile di Caricamento](#no-loading "Nessuno Stile di Caricamento")
- [Gestione degli Errori](#error-handling "Gestione degli Errori")


<div id="introduction"></div>

## Introduzione

Il **FutureWidget** e' un modo semplice per renderizzare `Future` nei tuoi progetti {{ config('app.name') }}. Avvolge il `FutureBuilder` di Flutter e fornisce un'API piu' pulita con stati di caricamento integrati.

Quando il tuo Future e' in corso, mostrera' un loader. Una volta completato il Future, i dati vengono restituiti tramite il callback `child`.

<div id="basic-usage"></div>

## Utilizzo Base

Ecco un semplice esempio di utilizzo di `FutureWidget`:

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

Il widget gestira' automaticamente lo stato di caricamento per i tuoi utenti fino al completamento del Future.

<div id="customizing-loading"></div>

## Personalizzazione dello Stato di Caricamento

Puoi personalizzare l'aspetto dello stato di caricamento usando il parametro `loadingStyle`.

<div id="normal-loading"></div>

### Stile di Caricamento Normale

Usa `LoadingStyle.normal()` per mostrare un widget di caricamento standard. Puoi opzionalmente fornire un widget figlio personalizzato:

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

Se non viene fornito alcun widget figlio, verra' mostrato il loader predefinito dell'app {{ config('app.name') }}.

<div id="skeletonizer-loading"></div>

### Stile di Caricamento Skeletonizer

Usa `LoadingStyle.skeletonizer()` per mostrare un effetto di caricamento a scheletro. Questo e' ottimo per mostrare un'interfaccia segnaposto che corrisponde al layout del tuo contenuto:

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

Effetti scheletro disponibili:
- `SkeletonizerEffect.shimmer` - Effetto shimmer animato (predefinito)
- `SkeletonizerEffect.pulse` - Effetto animazione a pulsazione
- `SkeletonizerEffect.solid` - Effetto colore solido

<div id="no-loading"></div>

### Nessuno Stile di Caricamento

Usa `LoadingStyle.none()` per nascondere completamente l'indicatore di caricamento:

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

## Gestione degli Errori

Puoi gestire gli errori dal tuo Future usando il callback `onError`:

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

Se non viene fornito alcun callback `onError` e si verifica un errore, verra' mostrato un `SizedBox.shrink()` vuoto.

### Parametri

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `future` | `Future<T>?` | Il Future da attendere |
| `child` | `Widget Function(BuildContext, T?)` | Funzione builder chiamata quando il Future e' completato |
| `loadingStyle` | `LoadingStyle?` | Personalizza l'indicatore di caricamento |
| `onError` | `Widget Function(AsyncSnapshot)?` | Funzione builder chiamata quando il Future ha un errore |
