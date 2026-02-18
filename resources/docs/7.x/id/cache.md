# Cache

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- Dasar
  - [Simpan dengan Kedaluwarsa](#save-with-expiration "Simpan dengan Kedaluwarsa")
  - [Simpan Selamanya](#save-forever "Simpan Selamanya")
  - [Mengambil Data](#retrieve-data "Mengambil Data")
  - [Menyimpan Data Langsung](#store-data-directly "Menyimpan Data Langsung")
  - [Menghapus Data](#remove-data "Menghapus Data")
  - [Memeriksa Cache](#check-cache "Memeriksa Cache")
- Jaringan
  - [Meng-cache Respons API](#caching-api-responses "Meng-cache Respons API")
- [Dukungan Platform](#platform-support "Dukungan Platform")
- [Referensi API](#api-reference "Referensi API")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} v7 menyediakan sistem cache berbasis file untuk menyimpan dan mengambil data secara efisien. Caching berguna untuk menyimpan data yang mahal seperti respons API atau hasil komputasi.

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

## Simpan dengan Kedaluwarsa

Gunakan `saveRemember` untuk meng-cache nilai dengan waktu kedaluwarsa:

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

Pada pemanggilan berikutnya dalam jendela kedaluwarsa, nilai yang di-cache dikembalikan tanpa mengeksekusi callback.

<div id="save-forever"></div>

## Simpan Selamanya

Gunakan `saveForever` untuk meng-cache data tanpa batas waktu:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

Data tetap di-cache sampai dihapus secara eksplisit atau cache aplikasi dibersihkan.

<div id="retrieve-data"></div>

## Mengambil Data

Ambil nilai yang di-cache secara langsung:

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

Jika item yang di-cache telah kedaluwarsa, `get()` secara otomatis menghapusnya dan mengembalikan `null`.

<div id="store-data-directly"></div>

## Menyimpan Data Langsung

Gunakan `put` untuk menyimpan nilai secara langsung tanpa callback:

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## Menghapus Data

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## Memeriksa Cache

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

## Meng-cache Respons API

### Menggunakan Helper api()

Cache respons API secara langsung:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### Menggunakan NyApiService

Tentukan caching di method API service Anda:

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

Kemudian panggil method tersebut:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## Dukungan Platform

Cache {{ config('app.name') }} menggunakan penyimpanan berbasis file dan memiliki dukungan platform berikut:

| Platform | Dukungan |
|----------|----------|
| iOS | Dukungan penuh |
| Android | Dukungan penuh |
| macOS | Dukungan penuh |
| Windows | Dukungan penuh |
| Linux | Dukungan penuh |
| Web | Tidak tersedia |

Pada platform web, cache menurun secara halus - callback selalu dieksekusi dan caching dilewati.

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## Referensi API

### Method

| Method | Deskripsi |
|--------|-----------|
| `saveRemember<T>(key, seconds, callback)` | Meng-cache nilai dengan kedaluwarsa. Mengembalikan nilai yang di-cache atau hasil callback. |
| `saveForever<T>(key, callback)` | Meng-cache nilai tanpa batas waktu. Mengembalikan nilai yang di-cache atau hasil callback. |
| `get<T>(key)` | Mengambil nilai yang di-cache. Mengembalikan `null` jika tidak ditemukan atau kedaluwarsa. |
| `put<T>(key, value, {seconds})` | Menyimpan nilai secara langsung. Kedaluwarsa opsional dalam detik. |
| `clear(key)` | Menghapus item cache tertentu. |
| `flush()` | Menghapus semua item yang di-cache. |
| `has(key)` | Memeriksa apakah kunci ada di cache. Mengembalikan `bool`. |
| `documents()` | Mendapatkan daftar semua kunci cache. Mengembalikan `List<String>`. |
| `size()` | Mendapatkan total ukuran cache dalam byte. Mengembalikan `int`. |

### Properti

| Properti | Tipe | Deskripsi |
|----------|------|-----------|
| `isAvailable` | `bool` | Apakah caching tersedia di platform saat ini. |

