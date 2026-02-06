# Cache

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- Grundlagen
  - [Mit Ablaufzeit speichern](#save-with-expiration "Mit Ablaufzeit speichern")
  - [Dauerhaft speichern](#save-forever "Dauerhaft speichern")
  - [Daten abrufen](#retrieve-data "Daten abrufen")
  - [Daten direkt speichern](#store-data-directly "Daten direkt speichern")
  - [Daten entfernen](#remove-data "Daten entfernen")
  - [Cache prüfen](#check-cache "Cache prüfen")
- Netzwerk
  - [API-Antworten cachen](#caching-api-responses "API-Antworten cachen")
- [Plattformunterstützung](#platform-support "Plattformunterstützung")
- [API-Referenz](#api-reference "API-Referenz")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} v7 bietet ein dateibasiertes Cache-System zum effizienten Speichern und Abrufen von Daten. Caching ist nützlich zum Speichern aufwändiger Daten wie API-Antworten oder berechneter Ergebnisse.

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

## Mit Ablaufzeit speichern

Verwenden Sie `saveRemember`, um einen Wert mit einer Ablaufzeit zu cachen:

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

Bei nachfolgenden Aufrufen innerhalb des Ablaufzeitraums wird der gecachte Wert zurückgegeben, ohne den Callback auszuführen.

<div id="save-forever"></div>

## Dauerhaft speichern

Verwenden Sie `saveForever`, um Daten unbegrenzt zu cachen:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

Die Daten bleiben gecacht, bis sie explizit entfernt werden oder der Cache der App geleert wird.

<div id="retrieve-data"></div>

## Daten abrufen

Einen gecachten Wert direkt abrufen:

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

Wenn das gecachte Element abgelaufen ist, entfernt `get()` es automatisch und gibt `null` zurück.

<div id="store-data-directly"></div>

## Daten direkt speichern

Verwenden Sie `put`, um einen Wert direkt ohne Callback zu speichern:

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## Daten entfernen

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## Cache prüfen

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

## API-Antworten cachen

### Verwendung des api()-Helpers

API-Antworten direkt cachen:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### Verwendung von NyApiService

Caching in Ihren API-Service-Methoden definieren:

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

Dann die Methode aufrufen:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## Plattformunterstützung

Der Cache von {{ config('app.name') }} verwendet dateibasierte Speicherung und bietet folgende Plattformunterstützung:

| Plattform | Unterstützung |
|-----------|---------------|
| iOS | Volle Unterstützung |
| Android | Volle Unterstützung |
| macOS | Volle Unterstützung |
| Windows | Volle Unterstützung |
| Linux | Volle Unterstützung |
| Web | Nicht verfügbar |

Auf der Web-Plattform degradiert der Cache elegant - Callbacks werden immer ausgeführt und das Caching wird umgangen.

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## API-Referenz

### Methoden

| Methode | Beschreibung |
|---------|-------------|
| `saveRemember<T>(key, seconds, callback)` | Wert mit Ablaufzeit cachen. Gibt gecachten Wert oder Callback-Ergebnis zurück. |
| `saveForever<T>(key, callback)` | Wert unbegrenzt cachen. Gibt gecachten Wert oder Callback-Ergebnis zurück. |
| `get<T>(key)` | Gecachten Wert abrufen. Gibt `null` zurück, wenn nicht gefunden oder abgelaufen. |
| `put<T>(key, value, {seconds})` | Wert direkt speichern. Optionale Ablaufzeit in Sekunden. |
| `clear(key)` | Bestimmtes gecachtes Element entfernen. |
| `flush()` | Alle gecachten Elemente entfernen. |
| `has(key)` | Prüfen, ob ein Schlüssel im Cache existiert. Gibt `bool` zurück. |
| `documents()` | Liste aller Cache-Schlüssel abrufen. Gibt `List<String>` zurück. |
| `size()` | Gesamte Cache-Größe in Bytes abrufen. Gibt `int` zurück. |

### Eigenschaften

| Eigenschaft | Typ | Beschreibung |
|-------------|-----|-------------|
| `isAvailable` | `bool` | Ob Caching auf der aktuellen Plattform verfügbar ist. |

