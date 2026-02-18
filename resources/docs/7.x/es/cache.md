# Cache

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- Conceptos basicos
  - [Guardar con expiracion](#save-with-expiration "Guardar con expiracion")
  - [Guardar permanentemente](#save-forever "Guardar permanentemente")
  - [Recuperar datos](#retrieve-data "Recuperar datos")
  - [Almacenar datos directamente](#store-data-directly "Almacenar datos directamente")
  - [Eliminar datos](#remove-data "Eliminar datos")
  - [Verificar cache](#check-cache "Verificar cache")
- Red
  - [Cachear respuestas API](#caching-api-responses "Cachear respuestas API")
- [Soporte de plataformas](#platform-support "Soporte de plataformas")
- [Referencia de API](#api-reference "Referencia de API")

<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} v7 proporciona un sistema de cache basado en archivos para almacenar y recuperar datos de manera eficiente. El cache es util para almacenar datos costosos como respuestas de API o resultados calculados.

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

## Guardar con expiracion

Usa `saveRemember` para cachear un valor con un tiempo de expiracion:

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

En llamadas posteriores dentro de la ventana de expiracion, se devuelve el valor cacheado sin ejecutar el callback.

<div id="save-forever"></div>

## Guardar permanentemente

Usa `saveForever` para cachear datos indefinidamente:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

Los datos permanecen cacheados hasta que se eliminan explicitamente o se limpia el cache de la aplicacion.

<div id="retrieve-data"></div>

## Recuperar datos

Obtiene un valor cacheado directamente:

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

Si el elemento cacheado ha expirado, `get()` lo elimina automaticamente y retorna `null`.

<div id="store-data-directly"></div>

## Almacenar datos directamente

Usa `put` para almacenar un valor directamente sin un callback:

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## Eliminar datos

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## Verificar cache

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

## Cachear respuestas API

### Usando el helper api()

Cachea respuestas API directamente:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### Usando NyApiService

Define el cache en los metodos de tu servicio API:

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

Luego llama al metodo:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## Soporte de plataformas

El cache de {{ config('app.name') }} usa almacenamiento basado en archivos y tiene el siguiente soporte de plataformas:

| Plataforma | Soporte |
|----------|---------|
| iOS | Soporte completo |
| Android | Soporte completo |
| macOS | Soporte completo |
| Windows | Soporte completo |
| Linux | Soporte completo |
| Web | No disponible |

En la plataforma web, el cache se degrada graciosamente -- los callbacks siempre se ejecutan y el cache se omite.

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## Referencia de API

### Metodos

| Metodo | Descripcion |
|--------|-------------|
| `saveRemember<T>(key, seconds, callback)` | Cachea un valor con expiracion. Retorna el valor cacheado o el resultado del callback. |
| `saveForever<T>(key, callback)` | Cachea un valor indefinidamente. Retorna el valor cacheado o el resultado del callback. |
| `get<T>(key)` | Recupera un valor cacheado. Retorna `null` si no se encuentra o ha expirado. |
| `put<T>(key, value, {seconds})` | Almacena un valor directamente. Expiracion opcional en segundos. |
| `clear(key)` | Elimina un elemento cacheado especifico. |
| `flush()` | Elimina todos los elementos cacheados. |
| `has(key)` | Verifica si una clave existe en cache. Retorna `bool`. |
| `documents()` | Obtiene la lista de todas las claves de cache. Retorna `List<String>`. |
| `size()` | Obtiene el tamano total del cache en bytes. Retorna `int`. |

### Propiedades

| Propiedad | Tipo | Descripcion |
|----------|------|-------------|
| `isAvailable` | `bool` | Si el cache esta disponible en la plataforma actual. |
