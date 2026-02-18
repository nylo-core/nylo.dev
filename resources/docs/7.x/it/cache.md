# Cache

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- Fondamenti
  - [Salvare con Scadenza](#save-with-expiration "Salvare con Scadenza")
  - [Salvare per Sempre](#save-forever "Salvare per Sempre")
  - [Recuperare Dati](#retrieve-data "Recuperare Dati")
  - [Memorizzare Dati Direttamente](#store-data-directly "Memorizzare Dati Direttamente")
  - [Rimuovere Dati](#remove-data "Rimuovere Dati")
  - [Controllare la Cache](#check-cache "Controllare la Cache")
- Networking
  - [Caching delle Risposte API](#caching-api-responses "Caching delle Risposte API")
- [Supporto Piattaforma](#platform-support "Supporto Piattaforma")
- [Riferimento API](#api-reference "Riferimento API")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} v7 fornisce un sistema di cache basato su file per memorizzare e recuperare dati in modo efficiente. La cache e' utile per memorizzare dati costosi come risposte API o risultati calcolati.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Cache a value for 60 seconds
String? value = await cache().saveRemember("my_key", 60, () {
  return "Hello World";
});

// Retrieve the cached value
String? cached = await cache().get("my_key");

// Remove from cache
await cache().clear("my_key");
```

<div id="save-with-expiration"></div>

## Salvare con Scadenza

Usa `saveRemember` per memorizzare in cache un valore con un tempo di scadenza:

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

Nelle chiamate successive all'interno della finestra di scadenza, il valore memorizzato in cache viene restituito senza eseguire il callback.

<div id="save-forever"></div>

## Salvare per Sempre

Usa `saveForever` per memorizzare dati in cache a tempo indeterminato:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

I dati rimangono in cache fino a quando non vengono esplicitamente rimossi o la cache dell'app viene svuotata.

<div id="retrieve-data"></div>

## Recuperare Dati

Ottieni un valore memorizzato in cache direttamente:

``` dart
// Retrieve cached value
String? value = await cache().get<String>("my_key");

// With type casting
Map<String, dynamic>? data = await cache().get<Map<String, dynamic>>("user_data");

// Returns null if not found or expired
if (value == null) {
  print("Cache miss or expired");
}
```

Se l'elemento in cache e' scaduto, `get()` lo rimuove automaticamente e restituisce `null`.

<div id="store-data-directly"></div>

## Memorizzare Dati Direttamente

Usa `put` per memorizzare un valore direttamente senza un callback:

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## Rimuovere Dati

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## Controllare la Cache

``` dart
// Check if a key exists
bool exists = await cache().has("my_key");

// Get all cached keys
List<String> keys = await cache().documents();

// Get total cache size in bytes
int sizeInBytes = await cache().size();
print("Cache size: ${sizeInBytes / 1024} KB");
```

<div id="caching-api-responses"></div>

## Caching delle Risposte API

### Utilizzando l'Helper api()

Memorizza in cache le risposte API direttamente:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### Utilizzando NyApiService

Definisci il caching nei metodi del tuo servizio API:

``` dart
class ApiService extends NyApiService {

  Future<Map<String, dynamic>?> getRepoInfo() async {
    return await network(
      request: (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
      cacheKey: "github_repo_info",
      cacheDuration: const Duration(hours: 1),
    );
  }

  Future<List<User>?> getUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
      cacheKey: "users_list",
      cacheDuration: const Duration(minutes: 10),
    );
  }
}
```

Poi chiama il metodo:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## Supporto Piattaforma

La cache di {{ config('app.name') }} utilizza lo storage basato su file e ha il seguente supporto per le piattaforme:

| Piattaforma | Supporto |
|-------------|----------|
| iOS | Supporto completo |
| Android | Supporto completo |
| macOS | Supporto completo |
| Windows | Supporto completo |
| Linux | Supporto completo |
| Web | Non disponibile |

Sulla piattaforma web, la cache degrada con grazia - i callback vengono sempre eseguiti e il caching viene bypassato.

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## Riferimento API

### Metodi

| Metodo | Descrizione |
|--------|-------------|
| `saveRemember<T>(key, seconds, callback)` | Memorizza un valore in cache con scadenza. Restituisce il valore in cache o il risultato del callback. |
| `saveForever<T>(key, callback)` | Memorizza un valore in cache a tempo indeterminato. Restituisce il valore in cache o il risultato del callback. |
| `get<T>(key)` | Recupera un valore dalla cache. Restituisce `null` se non trovato o scaduto. |
| `put<T>(key, value, {seconds})` | Memorizza un valore direttamente. Scadenza opzionale in secondi. |
| `clear(key)` | Rimuove un elemento specifico dalla cache. |
| `flush()` | Rimuove tutti gli elementi dalla cache. |
| `has(key)` | Controlla se una chiave esiste nella cache. Restituisce `bool`. |
| `documents()` | Ottiene la lista di tutte le chiavi in cache. Restituisce `List<String>`. |
| `size()` | Ottiene la dimensione totale della cache in byte. Restituisce `int`. |

### Proprieta'

| Proprieta' | Tipo | Descrizione |
|------------|------|-------------|
| `isAvailable` | `bool` | Se il caching e' disponibile sulla piattaforma corrente. |

