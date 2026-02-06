# Backpack

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
- [Lettura dei Dati](#reading-data "Lettura dei Dati")
- [Salvataggio dei Dati](#saving-data "Salvataggio dei Dati")
- [Eliminazione dei Dati](#deleting-data "Eliminazione dei Dati")
- [Sessioni](#sessions "Sessioni")
- [Accesso all'Istanza Nylo](#nylo-instance "Accesso all'Istanza Nylo")
- [Funzioni Helper](#helper-functions "Funzioni Helper")
- [Integrazione con NyStorage](#integration-with-nystorage "Integrazione con NyStorage")
- [Esempi](#examples "Esempi Pratici")

<div id="introduction"></div>

## Introduzione

**Backpack** e' un sistema di archiviazione singleton in memoria in {{ config('app.name') }}. Fornisce un accesso rapido e sincrono ai dati durante l'esecuzione della tua app. A differenza di `NyStorage` che persiste i dati sul dispositivo, Backpack memorizza i dati in memoria e vengono cancellati quando l'app viene chiusa.

Backpack viene utilizzato internamente dal framework per memorizzare istanze critiche come l'oggetto app `Nylo`, `EventBus` e i dati di autenticazione. Puoi anche usarlo per memorizzare i tuoi dati che devono essere accessibili rapidamente senza chiamate asincrone.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Save a value
Backpack.instance.save("user_name", "Anthony");

// Read a value (synchronous)
String? name = Backpack.instance.read("user_name");

// Delete a value
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## Utilizzo Base

Backpack utilizza il **pattern singleton** -- accedi tramite `Backpack.instance`:

``` dart
// Save data
Backpack.instance.save("theme", "dark");

// Read data
String? theme = Backpack.instance.read("theme"); // "dark"

// Check if data exists
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Lettura dei Dati

Leggi i valori da Backpack usando il metodo `read<T>()`. Supporta tipi generici e un valore predefinito opzionale:

``` dart
// Read a String
String? name = Backpack.instance.read<String>("name");

// Read with a default value
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Read an int
int? score = Backpack.instance.read<int>("score");
```

Backpack deserializza automaticamente le stringhe JSON in oggetti modello quando viene fornito un tipo:

``` dart
// If a User model is stored as JSON, it will be deserialized
User? user = Backpack.instance.read<User>("current_user");
```

<div id="saving-data"></div>

## Salvataggio dei Dati

Salva i valori usando il metodo `save()`:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### Aggiunta di Dati

Usa `append()` per aggiungere valori a una lista memorizzata con una chiave:

``` dart
// Append to a list
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Append with a limit (keeps only the last N items)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## Eliminazione dei Dati

### Eliminare una Singola Chiave

``` dart
Backpack.instance.delete("api_token");
```

### Eliminare Tutti i Dati

Il metodo `deleteAll()` rimuove tutti i valori **eccetto** le chiavi riservate del framework (`nylo` e `event_bus`):

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## Sessioni

Backpack fornisce la gestione delle sessioni per organizzare i dati in gruppi con nome. Questo e' utile per memorizzare dati correlati insieme.

### Aggiornare un Valore di Sessione

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### Ottenere un Valore di Sessione

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### Rimuovere una Chiave di Sessione

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### Svuotare un'Intera Sessione

``` dart
Backpack.instance.sessionFlush("cart");
```

### Ottenere Tutti i Dati di Sessione

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Accesso all'Istanza Nylo

Backpack memorizza l'istanza dell'applicazione `Nylo`. Puoi recuperarla usando:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

Verifica se l'istanza Nylo e' stata inizializzata:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## Funzioni Helper

{{ config('app.name') }} fornisce funzioni helper globali per le operazioni comuni di Backpack:

| Funzione | Descrizione |
|----------|-------------|
| `backpackRead<T>(key)` | Legge un valore da Backpack |
| `backpackSave(key, value)` | Salva un valore in Backpack |
| `backpackDelete(key)` | Elimina un valore da Backpack |
| `backpackDeleteAll()` | Elimina tutti i valori (preserva le chiavi del framework) |
| `backpackNylo()` | Ottiene l'istanza Nylo da Backpack |

### Esempio

``` dart
// Using helper functions
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Access the Nylo instance
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## Integrazione con NyStorage

Backpack si integra con `NyStorage` per un'archiviazione combinata persistente + in memoria:

``` dart
// Save to both NyStorage (persistent) and Backpack (in-memory)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read("auth_token");

// When deleting from NyStorage, also clear from Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

Questo pattern e' utile per dati come i token di autenticazione che necessitano sia della persistenza che di un accesso sincrono rapido (ad esempio, negli interceptor HTTP).

<div id="examples"></div>

## Esempi

### Memorizzazione dei Token di Autenticazione per le Richieste API

``` dart
// In your auth interceptor
class BearerAuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    String? userToken = Backpack.instance.read(StorageKeysConfig.auth);

    if (userToken != null) {
      options.headers.addAll({"Authorization": "Bearer $userToken"});
    }

    return super.onRequest(options, handler);
  }
}
```

### Gestione del Carrello Basata su Sessioni

``` dart
// Add items to a cart session
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Read cart data
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Clear the cart
Backpack.instance.sessionFlush("cart");
```

### Flag di Funzionalita' Rapidi

``` dart
// Store feature flags in Backpack for fast access
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Check a feature flag
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
