# &#214;nbellek

---

<a name="section-1"></a>
- [Giri&#351;](#introduction "Giri&#351;")
- Temel Bilgiler
  - [S&#252;reli Kaydetme](#save-with-expiration "S&#252;reli kaydetme")
  - [S&#252;resiz Kaydetme](#save-forever "S&#252;resiz kaydetme")
  - [Veri Alma](#retrieve-data "Veri alma")
  - [Do&#287;rudan Veri Depolama](#store-data-directly "Do&#287;rudan veri depolama")
  - [Veri Silme](#remove-data "Veri silme")
  - [&#214;nbellek Kontrol&#252;](#check-cache "&#214;nbellek kontrol&#252;")
- A&#287; &#304;&#351;lemleri
  - [API Yan&#305;tlar&#305;n&#305; &#214;nbellekleme](#caching-api-responses "API yan&#305;tlar&#305;n&#305; &#246;nbellekleme")
- [Platform Deste&#287;i](#platform-support "Platform deste&#287;i")
- [API Referans&#305;](#api-reference "API referans&#305;")

<div id="introduction"></div>

## Giri&#351;

{{ config('app.name') }} v7, verileri verimli bir &#351;ekilde depolamak ve almak i&#231;in dosya tabanl&#305; bir &#246;nbellek sistemi sa&#287;lar. &#214;nbellekleme, API yan&#305;tlar&#305; veya hesaplanm&#305;&#351; sonu&#231;lar gibi maliyetli verileri depolamak i&#231;in kullan&#305;&#351;l&#305;d&#305;r.

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

## S&#252;reli Kaydetme

Bir de&#287;eri s&#252;re s&#305;n&#305;r&#305; ile &#246;nbellelememek i&#231;in `saveRemember` kullan&#305;n:

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

S&#252;re s&#305;n&#305;r&#305; i&#231;indeki sonraki &#231;a&#287;r&#305;larda, callback &#231;al&#305;&#351;t&#305;r&#305;lmadan &#246;nbelleteki de&#287;er d&#246;nd&#252;r&#252;l&#252;r.

<div id="save-forever"></div>

## S&#252;resiz Kaydetme

Verileri s&#252;resiz olarak &#246;nbellelememek i&#231;in `saveForever` kullan&#305;n:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

Veriler, a&#231;&#305;k&#231;a kald&#305;r&#305;lana veya uygulaman&#305;n &#246;nbelle&#287;i temizlenene kadar &#246;nbellekte kal&#305;r.

<div id="retrieve-data"></div>

## Veri Alma

&#214;nbelleteki bir de&#287;eri do&#287;rudan al&#305;n:

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

&#214;nbelleteki &#246;&#287;enin s&#252;resi dolmu&#351;sa, `get()` otomatik olarak kald&#305;r&#305;r ve `null` d&#246;nd&#252;r&#252;r.

<div id="store-data-directly"></div>

## Do&#287;rudan Veri Depolama

Callback olmadan do&#287;rudan bir de&#287;er depolamak i&#231;in `put` kullan&#305;n:

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## Veri Silme

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## &#214;nbellek Kontrol&#252;

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

## API Yan&#305;tlar&#305;n&#305; &#214;nbellekleme

### api() Yard&#305;mc&#305;s&#305;n&#305; Kullanma

API yan&#305;tlar&#305;n&#305; do&#287;rudan &#246;nbelleyin:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### NyApiService Kullanma

API servis metotlar&#305;n&#305;zda &#246;nbelleklemeyi tan&#305;mlay&#305;n:

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

Ard&#305;ndan metodu &#231;a&#287;&#305;r&#305;n:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## Platform Deste&#287;i

{{ config('app.name') }}'nun &#246;nbelle&#287;i dosya tabanl&#305; depolama kullan&#305;r ve a&#351;a&#287;&#305;daki platform deste&#287;ine sahiptir:

| Platform | Destek |
|----------|---------|
| iOS | Tam destek |
| Android | Tam destek |
| macOS | Tam destek |
| Windows | Tam destek |
| Linux | Tam destek |
| Web | Mevcut de&#287;il |

Web platformunda &#246;nbellek sorunsuz bir &#351;ekilde devre d&#305;&#351;&#305; kal&#305;r &#8212; callback'ler her zaman &#231;al&#305;&#351;t&#305;r&#305;l&#305;r ve &#246;nbellekleme atlan&#305;r.

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## API Referans&#305;

### Metotlar

| Metot | A&#231;&#305;klama |
|--------|-------------|
| `saveRemember<T>(key, seconds, callback)` | Bir de&#287;eri s&#252;re s&#305;n&#305;r&#305; ile &#246;nbelleye kaydeder. &#214;nbelleteki de&#287;eri veya callback sonucunu d&#246;nd&#252;r&#252;r. |
| `saveForever<T>(key, callback)` | Bir de&#287;eri s&#252;resiz olarak &#246;nbelleye kaydeder. &#214;nbelleteki de&#287;eri veya callback sonucunu d&#246;nd&#252;r&#252;r. |
| `get<T>(key)` | &#214;nbelleteki bir de&#287;eri al&#305;r. Bulunamazsa veya s&#252;resi dolmu&#351;sa `null` d&#246;nd&#252;r&#252;r. |
| `put<T>(key, value, {seconds})` | Bir de&#287;eri do&#287;rudan depolar. &#304;ste&#287;e ba&#287;l&#305; s&#252;re s&#305;n&#305;r&#305; (saniye). |
| `clear(key)` | Belirli bir &#246;nbellek &#246;&#287;esini kald&#305;r&#305;r. |
| `flush()` | T&#252;m &#246;nbellek &#246;&#287;elerini kald&#305;r&#305;r. |
| `has(key)` | &#214;nbellekte bir anahtar&#305;n var olup olmad&#305;&#287;&#305;n&#305; kontrol eder. `bool` d&#246;nd&#252;r&#252;r. |
| `documents()` | T&#252;m &#246;nbellek anahtarlar&#305;n&#305;n listesini al&#305;r. `List<String>` d&#246;nd&#252;r&#252;r. |
| `size()` | Toplam &#246;nbellek boyutunu bayt cinsinden al&#305;r. `int` d&#246;nd&#252;r&#252;r. |

### &#214;zellikler

| &#214;zellik | T&#252;r | A&#231;&#305;klama |
|----------|------|-------------|
| `isAvailable` | `bool` | &#214;nbelleklemenin mevcut platformda kullan&#305;l&#305;p kullan&#305;lamayaca&#287;&#305;. |

