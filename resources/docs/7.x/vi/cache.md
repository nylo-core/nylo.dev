# Cache

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- Cơ bản
  - [Lưu với thời hạn](#save-with-expiration "Lưu với thời hạn")
  - [Lưu vĩnh viễn](#save-forever "Lưu vĩnh viễn")
  - [Lấy dữ liệu](#retrieve-data "Lấy dữ liệu")
  - [Lưu trữ dữ liệu trực tiếp](#store-data-directly "Lưu trữ dữ liệu trực tiếp")
  - [Xóa dữ liệu](#remove-data "Xóa dữ liệu")
  - [Kiểm tra cache](#check-cache "Kiểm tra cache")
- Mạng
  - [Cache phản hồi API](#caching-api-responses "Cache phản hồi API")
- [Hỗ trợ nền tảng](#platform-support "Hỗ trợ nền tảng")
- [Tham chiếu API](#api-reference "Tham chiếu API")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} v7 cung cấp hệ thống cache dựa trên file để lưu trữ và truy xuất dữ liệu hiệu quả. Cache hữu ích cho việc lưu trữ dữ liệu tốn kém như phản hồi API hoặc kết quả tính toán.

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

## Lưu với thời hạn

Sử dụng `saveRemember` để cache giá trị với thời hạn hết hạn:

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

Trong các lần gọi tiếp theo trong cửa sổ hết hạn, giá trị đã cache được trả về mà không thực thi callback.

<div id="save-forever"></div>

## Lưu vĩnh viễn

Sử dụng `saveForever` để cache dữ liệu vô thời hạn:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

Dữ liệu vẫn được cache cho đến khi bị xóa rõ ràng hoặc cache của ứng dụng bị xóa.

<div id="retrieve-data"></div>

## Lấy dữ liệu

Lấy giá trị đã cache trực tiếp:

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

Nếu mục đã cache đã hết hạn, `get()` tự động xóa nó và trả về `null`.

<div id="store-data-directly"></div>

## Lưu trữ dữ liệu trực tiếp

Sử dụng `put` để lưu giá trị trực tiếp mà không cần callback:

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## Xóa dữ liệu

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## Kiểm tra cache

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

## Cache phản hồi API

### Sử dụng helper api()

Cache phản hồi API trực tiếp:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### Sử dụng NyApiService

Định nghĩa cache trong các phương thức API service:

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

Sau đó gọi phương thức:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## Hỗ trợ nền tảng

Cache của {{ config('app.name') }} sử dụng lưu trữ dựa trên file và có hỗ trợ nền tảng sau:

| Nền tảng | Hỗ trợ |
|----------|---------|
| iOS | Hỗ trợ đầy đủ |
| Android | Hỗ trợ đầy đủ |
| macOS | Hỗ trợ đầy đủ |
| Windows | Hỗ trợ đầy đủ |
| Linux | Hỗ trợ đầy đủ |
| Web | Không khả dụng |

Trên nền tảng web, cache giảm cấp nhẹ nhàng - các callback luôn được thực thi và cache bị bỏ qua.

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## Tham chiếu API

### Phương thức

| Phương thức | Mô tả |
|--------|-------------|
| `saveRemember<T>(key, seconds, callback)` | Cache giá trị với thời hạn. Trả về giá trị đã cache hoặc kết quả callback. |
| `saveForever<T>(key, callback)` | Cache giá trị vô thời hạn. Trả về giá trị đã cache hoặc kết quả callback. |
| `get<T>(key)` | Lấy giá trị đã cache. Trả về `null` nếu không tìm thấy hoặc đã hết hạn. |
| `put<T>(key, value, {seconds})` | Lưu giá trị trực tiếp. Thời hạn tùy chọn tính bằng giây. |
| `clear(key)` | Xóa một mục cache cụ thể. |
| `flush()` | Xóa tất cả các mục cache. |
| `has(key)` | Kiểm tra xem khóa có tồn tại trong cache không. Trả về `bool`. |
| `documents()` | Lấy danh sách tất cả các khóa cache. Trả về `List<String>`. |
| `size()` | Lấy tổng kích thước cache tính bằng byte. Trả về `int`. |

### Thuộc tính

| Thuộc tính | Kiểu | Mô tả |
|----------|------|-------------|
| `isAvailable` | `bool` | Liệu cache có khả dụng trên nền tảng hiện tại không. |
