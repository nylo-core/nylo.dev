# Provider

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Creare un provider](#create-a-provider "Creare un provider")
- [Oggetto Provider](#provider-object "Oggetto Provider")


<div id="introduction"></div>

## Introduzione ai Provider

In {{ config('app.name') }}, i provider vengono avviati inizialmente dal tuo file <b>main.dart</b> quando l'applicazione viene eseguita. Tutti i tuoi provider risiedono in `/lib/app/providers/*`, puoi modificare questi file o creare i tuoi provider usando <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a>.

I provider possono essere usati quando hai bisogno di inizializzare una classe, un pacchetto o creare qualcosa prima che l'app venga caricata inizialmente. Ad esempio, la classe `route_provider.dart` e' responsabile dell'aggiunta di tutte le rotte a {{ config('app.name') }}.

### Approfondimento

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/7.x

/// Main entry point for the application.
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // Uncomment showSplashScreen to show the splash screen
    // File: lib/resources/widgets/splash_screen.dart
  );
}
```

### Ciclo di Vita

- `Boot.{{ config('app.name') }}` itererara' attraverso i tuoi provider registrati nel file <b>config/providers.dart</b> e li avviera'.

- `Boot.Finished` viene chiamato subito dopo che **"Boot.{{ config('app.name') }}"** e' terminato, questo metodo leghera' l'istanza di {{ config('app.name') }} a `Backpack` con il valore 'nylo'.

Es. Backpack.instance.read('nylo'); // istanza di {{ config('app.name') }}


<div id="create-a-provider"></div>

## Creare un nuovo Provider

Puoi creare nuovi provider eseguendo il comando seguente nel terminale.

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## Oggetto Provider

Il tuo provider avra' due metodi, `setup(Nylo nylo)` e `boot(Nylo nylo)`.

Quando l'app viene eseguita per la prima volta, qualsiasi codice all'interno del tuo metodo **setup** verra' eseguito per primo. Puoi anche manipolare l'oggetto `Nylo` come nell'esempio qui sotto.

Esempio: `lib/app/providers/app_provider.dart`

```dart
class AppProvider extends NyProvider {

  @override
  Future<Nylo?> setup(Nylo nylo) async {
    await NyLocalization.instance.init(
        localeType: localeType,
        languageCode: languageCode,
        languagesList: languagesList,
        assetsDirectory: assetsDirectory,
        valuesAsMap: valuesAsMap);

    return nylo;
  }

  @override
  Future<void> boot(Nylo nylo) async {
    User user = await Auth.user();
    if (!user.isSubscribed) {
      await Auth.remove();
    }
  }
}
```

### Ciclo di Vita

1. `setup(Nylo nylo)` - Inizializza il tuo provider. Restituisci l'istanza `Nylo` o `null`.
2. `boot(Nylo nylo)` - Chiamato dopo che tutti i provider hanno completato il setup. Usa questo per l'inizializzazione che dipende dalla prontezza di altri provider.

> All'interno del metodo `setup`, devi **restituire** un'istanza di `Nylo` o `null` come nell'esempio sopra.
