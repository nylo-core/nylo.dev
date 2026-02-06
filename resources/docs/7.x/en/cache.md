# Cache

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Basics
  - [Save with Expiration](#save-with-expiration "Save with expiration")
  - [Save Forever](#save-forever "Save forever")
  - [Retrieve Data](#retrieve-data "Retrieve data")
  - [Store Data Directly](#store-data-directly "Store data directly")
  - [Remove Data](#remove-data "Remove data")
  - [Check Cache](#check-cache "Check cache")
- Networking
  - [Caching API Responses](#caching-api-responses "Caching API responses")
- [Platform Support](#platform-support "Platform support")
- [API Reference](#api-reference "API reference")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 provides a file-based cache system for storing and retrieving data efficiently. Caching is useful for storing expensive data like API responses or computed results.

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

## Save with Expiration

Use `saveRemember` to cache a value with an expiration time:

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

On subsequent calls within the expiration window, the cached value is returned without executing the callback.

<div id="save-forever"></div>

## Save Forever

Use `saveForever` to cache data indefinitely:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

The data remains cached until explicitly removed or the app's cache is cleared.

<div id="retrieve-data"></div>

## Retrieve Data

Get a cached value directly:

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

If the cached item has expired, `get()` automatically removes it and returns `null`.

<div id="store-data-directly"></div>

## Store Data Directly

Use `put` to store a value directly without a callback:

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## Remove Data

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## Check Cache

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

## Caching API Responses

### Using the api() Helper

Cache API responses directly:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### Using NyApiService

Define caching in your API service methods:

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

Then call the method:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## Platform Support

{{ config('app.name') }}'s cache uses file-based storage and has the following platform support:

| Platform | Support |
|----------|---------|
| iOS | Full support |
| Android | Full support |
| macOS | Full support |
| Windows | Full support |
| Linux | Full support |
| Web | Not available |

On web platform, the cache gracefully degrades - callbacks are always executed and caching is bypassed.

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## API Reference

### Methods

| Method | Description |
|--------|-------------|
| `saveRemember<T>(key, seconds, callback)` | Cache a value with expiration. Returns cached value or callback result. |
| `saveForever<T>(key, callback)` | Cache a value indefinitely. Returns cached value or callback result. |
| `get<T>(key)` | Retrieve a cached value. Returns `null` if not found or expired. |
| `put<T>(key, value, {seconds})` | Store a value directly. Optional expiration in seconds. |
| `clear(key)` | Remove a specific cached item. |
| `flush()` | Remove all cached items. |
| `has(key)` | Check if a key exists in cache. Returns `bool`. |
| `documents()` | Get list of all cache keys. Returns `List<String>`. |
| `size()` | Get total cache size in bytes. Returns `int`. |

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `isAvailable` | `bool` | Whether caching is available on current platform. |

