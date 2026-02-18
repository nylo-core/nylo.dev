# Cache

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- Podstawy
  - [Zapisywanie z terminem waznosci](#save-with-expiration "Zapisywanie z terminem waznosci")
  - [Zapisywanie na stale](#save-forever "Zapisywanie na stale")
  - [Pobieranie danych](#retrieve-data "Pobieranie danych")
  - [Bezposrednie przechowywanie danych](#store-data-directly "Bezposrednie przechowywanie danych")
  - [Usuwanie danych](#remove-data "Usuwanie danych")
  - [Sprawdzanie cache](#check-cache "Sprawdzanie cache")
- Siec
  - [Cachowanie odpowiedzi API](#caching-api-responses "Cachowanie odpowiedzi API")
- [Obsluga platform](#platform-support "Obsluga platform")
- [Referencja API](#api-reference "Referencja API")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} v7 udostepnia plikowy system cache do wydajnego przechowywania i pobierania danych. Cachowanie jest przydatne do przechowywania kosztownych danych, takich jak odpowiedzi API czy obliczone wyniki.

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

## Zapisywanie z terminem waznosci

Uzyj `saveRemember`, aby zapisac wartosc w cache z terminem waznosci:

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

Przy kolejnych wywolaniach w oknie waznosci zwracana jest wartosc z cache bez wykonywania callbacku.

<div id="save-forever"></div>

## Zapisywanie na stale

Uzyj `saveForever`, aby zapisac dane w cache na czas nieokreslony:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

Dane pozostaja w cache do momentu jawnego usuniecia lub wyczyszczenia cache aplikacji.

<div id="retrieve-data"></div>

## Pobieranie danych

Pobierz wartosc z cache bezposrednio:

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

Jesli element w cache wygasl, `get()` automatycznie go usuwa i zwraca `null`.

<div id="store-data-directly"></div>

## Bezposrednie przechowywanie danych

Uzyj `put`, aby przechowac wartosc bezposrednio bez callbacku:

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## Usuwanie danych

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## Sprawdzanie cache

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

## Cachowanie odpowiedzi API

### Uzycie helpera api()

Cachuj odpowiedzi API bezposrednio:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### Uzycie NyApiService

Zdefiniuj cachowanie w metodach serwisu API:

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

Nastepnie wywolaj metode:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## Obsluga platform

System cache {{ config('app.name') }} uzywa przechowywania plikowego i obsluguje nastepujace platformy:

| Platforma | Obsluga |
|-----------|---------|
| iOS | Pelna obsluga |
| Android | Pelna obsluga |
| macOS | Pelna obsluga |
| Windows | Pelna obsluga |
| Linux | Pelna obsluga |
| Web | Niedostepne |

Na platformie webowej cache degraduje sie elegancko -- callbacki sa zawsze wykonywane, a cachowanie jest pomijane.

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## Referencja API

### Metody

| Metoda | Opis |
|--------|------|
| `saveRemember<T>(key, seconds, callback)` | Cachuj wartosc z terminem waznosci. Zwraca wartosc z cache lub wynik callbacku. |
| `saveForever<T>(key, callback)` | Cachuj wartosc na czas nieokreslony. Zwraca wartosc z cache lub wynik callbacku. |
| `get<T>(key)` | Pobierz wartosc z cache. Zwraca `null`, jesli nie znaleziono lub wygasla. |
| `put<T>(key, value, {seconds})` | Przechowaj wartosc bezposrednio. Opcjonalny termin waznosci w sekundach. |
| `clear(key)` | Usun konkretny element z cache. |
| `flush()` | Usun wszystkie elementy z cache. |
| `has(key)` | Sprawdz, czy klucz istnieje w cache. Zwraca `bool`. |
| `documents()` | Pobierz liste wszystkich kluczy cache. Zwraca `List<String>`. |
| `size()` | Pobierz calkowity rozmiar cache w bajtach. Zwraca `int`. |

### Wlasciwosci

| Wlasciwosc | Typ | Opis |
|-------------|-----|------|
| `isAvailable` | `bool` | Czy cachowanie jest dostepne na biezacej platformie. |
