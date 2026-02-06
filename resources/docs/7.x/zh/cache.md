# 缓存

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- 基础
  - [带过期时间保存](#save-with-expiration "带过期时间保存")
  - [永久保存](#save-forever "永久保存")
  - [检索数据](#retrieve-data "检索数据")
  - [直接存储数据](#store-data-directly "直接存储数据")
  - [删除数据](#remove-data "删除数据")
  - [检查缓存](#check-cache "检查缓存")
- 网络
  - [缓存 API 响应](#caching-api-responses "缓存 API 响应")
- [平台支持](#platform-support "平台支持")
- [API 参考](#api-reference "API 参考")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} v7 提供了基于文件的缓存系统，用于高效地存储和检索数据。缓存适用于存储昂贵的数据，如 API 响应或计算结果。

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

## 带过期时间保存

使用 `saveRemember` 缓存一个带有过期时间的值：

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

在过期时间窗口内的后续调用会返回缓存的值，而不执行回调。

<div id="save-forever"></div>

## 永久保存

使用 `saveForever` 无限期地缓存数据：

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

数据将保持缓存状态，直到被显式删除或应用的缓存被清除。

<div id="retrieve-data"></div>

## 检索数据

直接获取缓存的值：

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

如果缓存项已过期，`get()` 会自动删除它并返回 `null`。

<div id="store-data-directly"></div>

## 直接存储数据

使用 `put` 直接存储一个值而不使用回调：

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## 删除数据

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## 检查缓存

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

## 缓存 API 响应

### 使用 api() 辅助函数

直接缓存 API 响应：

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### 使用 NyApiService

在您的 API 服务方法中定义缓存：

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

然后调用该方法：

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## 平台支持

{{ config('app.name') }} 的缓存使用基于文件的存储，以下是平台支持情况：

| 平台 | 支持情况 |
|----------|---------|
| iOS | 完全支持 |
| Android | 完全支持 |
| macOS | 完全支持 |
| Windows | 完全支持 |
| Linux | 完全支持 |
| Web | 不可用 |

在 Web 平台上，缓存会优雅降级 - 回调始终被执行，缓存被绕过。

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## API 参考

### 方法

| 方法 | 描述 |
|--------|-------------|
| `saveRemember<T>(key, seconds, callback)` | 缓存一个带有过期时间的值。返回缓存值或回调结果。 |
| `saveForever<T>(key, callback)` | 无限期缓存一个值。返回缓存值或回调结果。 |
| `get<T>(key)` | 检索缓存的值。未找到或已过期时返回 `null`。 |
| `put<T>(key, value, {seconds})` | 直接存储一个值。可选的过期时间（秒）。 |
| `clear(key)` | 删除特定的缓存项。 |
| `flush()` | 删除所有缓存项。 |
| `has(key)` | 检查缓存中是否存在某个键。返回 `bool`。 |
| `documents()` | 获取所有缓存键的列表。返回 `List<String>`。 |
| `size()` | 获取缓存总大小（字节）。返回 `int`。 |

### 属性

| 属性 | 类型 | 描述 |
|----------|------|-------------|
| `isAvailable` | `bool` | 当前平台是否支持缓存。 |

