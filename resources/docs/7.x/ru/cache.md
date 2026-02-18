# Cache

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- Основы
  - [Сохранение с истечением срока](#save-with-expiration "Сохранение с истечением срока")
  - [Сохранение навсегда](#save-forever "Сохранение навсегда")
  - [Получение данных](#retrieve-data "Получение данных")
  - [Прямое сохранение данных](#store-data-directly "Прямое сохранение данных")
  - [Удаление данных](#remove-data "Удаление данных")
  - [Проверка кэша](#check-cache "Проверка кэша")
- Сеть
  - [Кэширование ответов API](#caching-api-responses "Кэширование ответов API")
- [Поддержка платформ](#platform-support "Поддержка платформ")
- [Справочник API](#api-reference "Справочник API")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} v7 предоставляет файловую систему кэширования для эффективного хранения и получения данных. Кэширование полезно для хранения затратных данных, таких как ответы API или вычисленные результаты.

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

## Сохранение с истечением срока

Используйте `saveRemember` для кэширования значения с временем истечения:

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

При последующих вызовах в пределах окна истечения возвращается кэшированное значение без выполнения обратного вызова.

<div id="save-forever"></div>

## Сохранение навсегда

Используйте `saveForever` для бессрочного кэширования данных:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

Данные остаются в кэше до явного удаления или очистки кэша приложения.

<div id="retrieve-data"></div>

## Получение данных

Получайте кэшированное значение напрямую:

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

Если срок действия кэшированного элемента истёк, `get()` автоматически удаляет его и возвращает `null`.

<div id="store-data-directly"></div>

## Прямое сохранение данных

Используйте `put` для прямого сохранения значения без обратного вызова:

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## Удаление данных

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## Проверка кэша

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

## Кэширование ответов API

### Использование помощника api()

Кэшируйте ответы API напрямую:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### Использование NyApiService

Определяйте кэширование в методах вашего API-сервиса:

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

Затем вызовите метод:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## Поддержка платформ

Кэш {{ config('app.name') }} использует файловое хранилище и имеет следующую поддержку платформ:

| Платформа | Поддержка |
|----------|---------|
| iOS | Полная поддержка |
| Android | Полная поддержка |
| macOS | Полная поддержка |
| Windows | Полная поддержка |
| Linux | Полная поддержка |
| Web | Недоступно |

На веб-платформе кэш корректно деградирует -- обратные вызовы всегда выполняются, а кэширование пропускается.

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## Справочник API

### Методы

| Метод | Описание |
|--------|-------------|
| `saveRemember<T>(key, seconds, callback)` | Кэширование значения с истечением срока. Возвращает кэшированное значение или результат обратного вызова. |
| `saveForever<T>(key, callback)` | Бессрочное кэширование значения. Возвращает кэшированное значение или результат обратного вызова. |
| `get<T>(key)` | Получение кэшированного значения. Возвращает `null`, если не найдено или истекло. |
| `put<T>(key, value, {seconds})` | Прямое сохранение значения. Необязательное истечение в секундах. |
| `clear(key)` | Удаление конкретного кэшированного элемента. |
| `flush()` | Удаление всех кэшированных элементов. |
| `has(key)` | Проверка существования ключа в кэше. Возвращает `bool`. |
| `documents()` | Получение списка всех ключей кэша. Возвращает `List<String>`. |
| `size()` | Получение общего размера кэша в байтах. Возвращает `int`. |

### Свойства

| Свойство | Тип | Описание |
|----------|------|-------------|
| `isAvailable` | `bool` | Доступно ли кэширование на текущей платформе. |
