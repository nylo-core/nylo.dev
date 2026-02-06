# Networking

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- Membuat Permintaan HTTP
  - [Method Praktis](#convenience-methods "Method Praktis")
  - [Helper Network](#network-helper "Helper Network")
  - [Helper networkResponse](#network-response-helper "Helper networkResponse")
  - [NyResponse](#ny-response "NyResponse")
  - [Opsi Dasar](#base-options "Opsi Dasar")
  - [Menambahkan Header](#adding-headers "Menambahkan Header")
- Operasi File
  - [Mengunggah File](#uploading-files "Mengunggah File")
  - [Mengunduh File](#downloading-files "Mengunduh File")
- [Interceptor](#interceptors "Interceptor")
  - [Network Logger](#network-logger "Network Logger")
- [Menggunakan API Service](#using-an-api-service "Menggunakan API Service")
- [Membuat API Service](#create-an-api-service "Membuat API Service")
- [Mengubah JSON ke Model](#morphing-json-payloads-to-models "Mengubah JSON ke Model")
- Caching
  - [Menyimpan Cache Response](#caching-responses "Menyimpan Cache Response")
  - [Kebijakan Cache](#cache-policies "Kebijakan Cache")
- Penanganan Error
  - [Mencoba Ulang Permintaan Gagal](#retrying-failed-requests "Mencoba Ulang Permintaan Gagal")
  - [Pemeriksaan Konektivitas](#connectivity-checks "Pemeriksaan Konektivitas")
  - [Cancel Token](#cancel-tokens "Cancel Token")
- Autentikasi
  - [Mengatur Header Auth](#setting-auth-headers "Mengatur Header Auth")
  - [Memperbarui Token](#refreshing-tokens "Memperbarui Token")
- [Singleton API Service](#singleton-api-service "Singleton API Service")
- [Konfigurasi Lanjutan](#advanced-configuration "Konfigurasi Lanjutan")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} membuat networking menjadi sederhana. Anda mendefinisikan endpoint API di kelas service yang meng-extend `NyApiService`, kemudian memanggilnya dari halaman Anda. Framework ini menangani dekoding JSON, penanganan error, caching, dan konversi otomatis response ke kelas model Anda (disebut "morphing").

API service Anda berada di `lib/app/networking/`. Proyek baru menyertakan `ApiService` default:

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
        );

  @override
  String get baseUrl => getEnv('API_BASE_URL');

  @override
  Map<Type, Interceptor> get interceptors => {
    ...super.interceptors,
  };

  Future fetchUsers() async {
    return await network(
      request: (request) => request.get("/users"),
    );
  }
}
```

Ada tiga cara untuk membuat permintaan HTTP:

| Pendekatan | Mengembalikan | Cocok Untuk |
|------------|---------------|-------------|
| Method praktis (`get`, `post`, dll.) | `T?` | Operasi CRUD sederhana |
| `network()` | `T?` | Permintaan yang membutuhkan caching, retry, atau header kustom |
| `networkResponse()` | `NyResponse<T>` | Ketika Anda membutuhkan kode status, header, atau detail error |

Di balik layar, {{ config('app.name') }} menggunakan <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>, klien HTTP yang powerful.


<div id="convenience-methods"></div>

## Method Praktis

`NyApiService` menyediakan method singkat untuk operasi HTTP umum. Method ini memanggil `network()` secara internal.

### Permintaan GET

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### Permintaan POST

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### Permintaan PUT

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### Permintaan DELETE

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### Permintaan PATCH

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### Permintaan HEAD

Gunakan HEAD untuk memeriksa keberadaan resource atau mendapatkan header tanpa mengunduh body:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Helper Network

Method `network` memberi Anda lebih banyak kontrol daripada method praktis. Method ini mengembalikan data yang sudah diubah (`T?`) secara langsung.

```dart
class ApiService extends NyApiService {
  ...

  Future<User?> fetchUser(int id) async {
    return await network<User>(
      request: (request) => request.get("/users/$id"),
    );
  }

  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }

  Future<User?> createUser(Map<String, dynamic> data) async {
    return await network<User>(
      request: (request) => request.post("/users", data: data),
    );
  }
}
```

Callback `request` menerima instance <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> dengan base URL dan interceptor Anda yang sudah dikonfigurasi.

### Parameter network

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `request` | `Function(Dio)` | Permintaan HTTP yang akan dilakukan (wajib) |
| `bearerToken` | `String?` | Bearer token untuk permintaan ini |
| `baseUrl` | `String?` | Override base URL service |
| `headers` | `Map<String, dynamic>?` | Header tambahan |
| `retry` | `int?` | Jumlah percobaan retry |
| `retryDelay` | `Duration?` | Jeda antar retry |
| `retryIf` | `bool Function(DioException)?` | Kondisi untuk retry |
| `connectionTimeout` | `Duration?` | Timeout koneksi |
| `receiveTimeout` | `Duration?` | Timeout penerimaan |
| `sendTimeout` | `Duration?` | Timeout pengiriman |
| `cacheKey` | `String?` | Key cache |
| `cacheDuration` | `Duration?` | Durasi cache |
| `cachePolicy` | `CachePolicy?` | Strategi cache |
| `checkConnectivity` | `bool?` | Periksa konektivitas sebelum permintaan |
| `handleSuccess` | `Function(NyResponse<T>)?` | Callback sukses |
| `handleFailure` | `Function(NyResponse<T>)?` | Callback gagal |


<div id="network-response-helper"></div>

## Helper networkResponse

Gunakan `networkResponse` ketika Anda membutuhkan akses ke response lengkap -- kode status, header, pesan error -- bukan hanya datanya. Method ini mengembalikan `NyResponse<T>` alih-alih `T?`.

Gunakan `networkResponse` ketika Anda perlu:
- Memeriksa kode status HTTP untuk penanganan spesifik
- Mengakses header response
- Mendapatkan pesan error detail untuk umpan balik pengguna
- Mengimplementasikan logika penanganan error kustom

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

Kemudian gunakan response di halaman Anda:

```dart
NyResponse<User> response = await _apiService.fetchUser(1);

if (response.isSuccessful) {
  User? user = response.data;
  print('Status: ${response.statusCode}');
} else {
  print('Error: ${response.errorMessage}');
  print('Status: ${response.statusCode}');
}
```

### network vs networkResponse

```dart
// network() — mengembalikan data secara langsung
User? user = await network<User>(
  request: (request) => request.get("/users/1"),
);

// networkResponse() — mengembalikan response lengkap
NyResponse<User> response = await networkResponse<User>(
  request: (request) => request.get("/users/1"),
);
User? user = response.data;
int? status = response.statusCode;
```

Kedua method menerima parameter yang sama. Pilih `networkResponse` ketika Anda perlu memeriksa response lebih dari sekadar datanya.


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` membungkus response Dio dengan data yang sudah diubah dan helper status.

### Properti

| Properti | Tipe | Deskripsi |
|----------|------|-----------|
| `response` | `Response?` | Response Dio asli |
| `data` | `T?` | Data yang sudah diubah/didekode |
| `rawData` | `dynamic` | Data response mentah |
| `headers` | `Headers?` | Header response |
| `statusCode` | `int?` | Kode status HTTP |
| `statusMessage` | `String?` | Pesan status HTTP |
| `contentType` | `String?` | Content type dari header |
| `errorMessage` | `String?` | Pesan error yang diekstrak |

### Pemeriksaan Status

| Getter | Deskripsi |
|--------|-----------|
| `isSuccessful` | Status 200-299 |
| `isClientError` | Status 400-499 |
| `isServerError` | Status 500-599 |
| `isRedirect` | Status 300-399 |
| `hasData` | Data tidak null |
| `isUnauthorized` | Status 401 |
| `isForbidden` | Status 403 |
| `isNotFound` | Status 404 |
| `isTimeout` | Status 408 |
| `isConflict` | Status 409 |
| `isRateLimited` | Status 429 |

### Helper Data

```dart
NyResponse<User> response = await apiService.fetchUser(1);

// Dapatkan data atau throw jika null
User user = response.dataOrThrow('User not found');

// Dapatkan data atau gunakan fallback
User user = response.dataOr(User.guest());

// Jalankan callback hanya jika berhasil
String? greeting = response.ifSuccessful((user) => 'Hello ${user.name}');

// Pattern match pada sukses/gagal
String result = response.when(
  success: (user) => 'Welcome, ${user.name}!',
  failure: (response) => 'Error: ${response.statusMessage}',
);

// Dapatkan header tertentu
String? authHeader = response.getHeader('Authorization');
```


<div id="base-options"></div>

## Opsi Dasar

Konfigurasikan opsi Dio default untuk API service Anda menggunakan parameter `baseOptions`:

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(
    buildContext,
    decoders: modelDecoders,
    baseOptions: (BaseOptions baseOptions) {
      return baseOptions
        ..connectTimeout = Duration(seconds: 5)
        ..sendTimeout = Duration(seconds: 5)
        ..receiveTimeout = Duration(seconds: 5);
    },
  );
  ...
}
```

Anda juga dapat mengkonfigurasi opsi secara dinamis pada instance:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

Klik <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">di sini</a> untuk melihat semua opsi dasar yang dapat Anda atur.


<div id="adding-headers"></div>

## Menambahkan Header

### Header Per-Permintaan

```dart
Future fetchWithHeaders() async => await network(
  request: (request) => request.get("/test"),
  headers: {
    "Authorization": "Bearer aToken123",
    "Device": "iPhone"
  }
);
```

### Bearer Token

```dart
Future fetchUser() async => await network(
  request: (request) => request.get("/user"),
  bearerToken: "hello-world-123",
);
```

### Header Level Service

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### Extension RequestHeaders

Tipe `RequestHeaders` (typedef `Map<String, dynamic>`) menyediakan method helper:

```dart
@override
Future<RequestHeaders> setAuthHeaders(RequestHeaders headers) async {
  String? token = Auth.data(field: 'token');
  if (token != null) {
    headers.addBearerToken(token);
  }
  headers.addHeader('X-App-Version', '1.0.0');
  return headers;
}
```

| Method | Deskripsi |
|--------|-----------|
| `addBearerToken(token)` | Mengatur header `Authorization: Bearer` |
| `getBearerToken()` | Membaca bearer token dari header |
| `addHeader(key, value)` | Menambahkan header kustom |
| `hasHeader(key)` | Memeriksa apakah header ada |


<div id="uploading-files"></div>

## Mengunggah File

### Unggah File Tunggal

```dart
Future<UploadResponse?> uploadAvatar(String filePath) async {
  return await upload<UploadResponse>(
    '/upload',
    filePath: filePath,
    fieldName: 'avatar',
    additionalFields: {'userId': '123'},
    onProgress: (sent, total) {
      double progress = sent / total * 100;
      print('Progress: ${progress.toStringAsFixed(0)}%');
    },
  );
}
```

### Unggah Beberapa File

```dart
Future<UploadResponse?> uploadDocuments() async {
  return await uploadMultiple<UploadResponse>(
    '/upload',
    files: {
      'avatar': '/path/to/avatar.jpg',
      'document': '/path/to/doc.pdf',
    },
    additionalFields: {'userId': '123'},
    onProgress: (sent, total) {
      print('Progress: ${(sent / total * 100).toStringAsFixed(0)}%');
    },
  );
}
```


<div id="downloading-files"></div>

## Mengunduh File

```dart
Future<void> downloadFile(String url, String savePath) async {
  await download(
    url,
    savePath: savePath,
    onProgress: (received, total) {
      if (total != -1) {
        print('Progress: ${(received / total * 100).toStringAsFixed(0)}%');
      }
    },
    deleteOnError: true,
  );
}
```


<div id="interceptors"></div>

## Interceptor

Interceptor memungkinkan Anda memodifikasi permintaan sebelum dikirim, menangani response, dan mengelola error. Mereka dijalankan pada setiap permintaan yang dibuat melalui API service.

Gunakan interceptor ketika Anda perlu:
- Menambahkan header autentikasi ke semua permintaan
- Mencatat log permintaan dan response untuk debugging
- Mentransformasi data permintaan/response secara global
- Menangani kode error tertentu (misal, memperbarui token saat 401)

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  Map<Type, Interceptor> get interceptors => {
    ...super.interceptors,
    BearerAuthInterceptor: BearerAuthInterceptor(),
    LoggingInterceptor: LoggingInterceptor(),
  };
  ...
}
```

### Membuat Interceptor Kustom

```bash
metro make:interceptor logging
```

**File:** `app/networking/dio/interceptors/logging_interceptor.dart`

```dart
import 'package:nylo_framework/nylo_framework.dart';

class LoggingInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    print('REQUEST[${options.method}] => PATH: ${options.path}');
    return super.onRequest(options, handler);
  }

  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) {
    print('RESPONSE[${response.statusCode}] => PATH: ${response.requestOptions.path}');
    handler.next(response);
  }

  @override
  void onError(DioException dioException, ErrorInterceptorHandler handler) {
    print('ERROR[${dioException.response?.statusCode}] => PATH: ${dioException.requestOptions.path}');
    handler.next(dioException);
  }
}
```


<div id="network-logger"></div>

## Network Logger

{{ config('app.name') }} menyertakan interceptor `NetworkLogger` bawaan. Secara default aktif ketika `APP_DEBUG` bernilai `true` di environment Anda.

### Konfigurasi

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(
    buildContext,
    decoders: modelDecoders,
    useNetworkLogger: true,
    networkLogger: NetworkLogger(
      logLevel: LogLevelType.verbose,
      request: true,
      requestHeader: true,
      requestBody: true,
      responseBody: true,
      responseHeader: false,
      error: true,
    ),
  );
}
```

Anda dapat menonaktifkannya dengan mengatur `useNetworkLogger: false`.

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- Nonaktifkan logger
        );
```

### Level Log

| Level | Deskripsi |
|-------|-----------|
| `LogLevelType.verbose` | Cetak semua detail permintaan/response |
| `LogLevelType.minimal` | Cetak method, URL, status, dan waktu saja |
| `LogLevelType.none` | Tidak ada output logging |

### Memfilter Log

```dart
NetworkLogger(
  filter: (options, args) {
    // Only log requests to specific endpoints
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## Menggunakan API Service

Ada dua cara untuk memanggil API service Anda dari halaman.

### Instansiasi Langsung

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  ApiService _apiService = ApiService();

  @override
  get init => () async {
    List<User>? users = await _apiService.fetchUsers();
    print(users);
  };
}
```

### Menggunakan Helper api()

Helper `api` membuat instance menggunakan `apiDecoders` dari `config/decoders.dart`:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

Dengan callback:

```dart
await api<ApiService>(
  (request) => request.fetchUser(),
  onSuccess: (response, data) {
    // data is the morphed User? instance
  },
  onError: (DioException dioException) {
    // Handle the error
  },
);
```

### Parameter Helper api()

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `request` | `Function(T)` | Fungsi permintaan API |
| `context` | `BuildContext?` | Build context |
| `headers` | `Map<String, dynamic>` | Header tambahan |
| `bearerToken` | `String?` | Bearer token |
| `baseUrl` | `String?` | Override base URL |
| `page` | `int?` | Halaman paginasi |
| `perPage` | `int?` | Item per halaman |
| `retry` | `int` | Percobaan retry |
| `retryDelay` | `Duration?` | Jeda antar retry |
| `onSuccess` | `Function(Response, dynamic)?` | Callback sukses |
| `onError` | `Function(DioException)?` | Callback error |
| `cacheKey` | `String?` | Key cache |
| `cacheDuration` | `Duration?` | Durasi cache |


<div id="create-an-api-service"></div>

## Membuat API Service

Untuk membuat API service baru:

```bash
metro make:api_service user
```

Dengan model:

```bash
metro make:api_service user --model="User"
```

Ini membuat API service dengan method CRUD:

```dart
class UserApiService extends NyApiService {
  ...

  Future<List<User>?> fetchAll({dynamic query}) async {
    return await network<List<User>>(
      request: (request) => request.get("/endpoint-path", queryParameters: query),
    );
  }

  Future<User?> find({required int id}) async {
    return await network<User>(
      request: (request) => request.get("/endpoint-path/$id"),
    );
  }

  Future<User?> create({required dynamic data}) async {
    return await network<User>(
      request: (request) => request.post("/endpoint-path", data: data),
    );
  }

  Future<User?> update({dynamic query}) async {
    return await network<User>(
      request: (request) => request.put("/endpoint-path", queryParameters: query),
    );
  }

  Future<bool?> delete({required int id}) async {
    return await network<bool>(
      request: (request) => request.delete("/endpoint-path/$id"),
    );
  }
}
```


<div id="morphing-json-payloads-to-models"></div>

## Mengubah JSON ke Model

"Morphing" adalah istilah {{ config('app.name') }} untuk konversi otomatis response JSON ke kelas model Dart Anda. Ketika Anda menggunakan `network<User>(...)`, JSON response dilewatkan melalui decoder Anda untuk membuat instance `User` -- tanpa perlu parsing manual.

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  // Mengembalikan satu User
  Future<User?> fetchUser() async {
    return await network<User>(
      request: (request) => request.get("/user/1"),
    );
  }

  // Mengembalikan List User
  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }
}
```

Decoder didefinisikan di `lib/bootstrap/decoders.dart`:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

Parameter tipe yang Anda berikan ke `network<T>()` dicocokkan dengan map `modelDecoders` Anda untuk menemukan decoder yang tepat.

**Lihat juga:** [Decoders](/docs/{{$version}}/decoders#model-decoders) untuk detail tentang mendaftarkan model decoder.


<div id="caching-responses"></div>

## Menyimpan Cache Response

Simpan cache response untuk mengurangi panggilan API dan meningkatkan performa. Caching berguna untuk data yang tidak sering berubah, seperti daftar negara, kategori, atau konfigurasi.

Berikan `cacheKey` dan opsional `cacheDuration`:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### Menghapus Cache

```dart
// Clear a specific cache key
await apiService.clearCache("app_countries");

// Clear all API cache
await apiService.clearAllCache();
```

### Caching dengan Helper api()

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## Kebijakan Cache

Gunakan `CachePolicy` untuk kontrol yang lebih detail atas perilaku caching:

| Kebijakan | Deskripsi |
|-----------|-----------|
| `CachePolicy.networkOnly` | Selalu ambil dari jaringan (default) |
| `CachePolicy.cacheFirst` | Coba cache dulu, fallback ke jaringan |
| `CachePolicy.networkFirst` | Coba jaringan dulu, fallback ke cache |
| `CachePolicy.cacheOnly` | Hanya gunakan cache, error jika kosong |
| `CachePolicy.staleWhileRevalidate` | Kembalikan cache langsung, perbarui di background |

### Penggunaan

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
    cachePolicy: CachePolicy.staleWhileRevalidate,
  ) ?? [];
}
```

### Kapan Menggunakan Setiap Kebijakan

- **cacheFirst** -- Data yang jarang berubah. Mengembalikan data cache langsung, hanya mengambil dari jaringan jika cache kosong.
- **networkFirst** -- Data yang seharusnya segar jika memungkinkan. Mencoba jaringan dulu, fallback ke cache saat gagal.
- **staleWhileRevalidate** -- UI yang membutuhkan response langsung tetapi harus tetap diperbarui. Mengembalikan data cache, kemudian menyegarkan di background.
- **cacheOnly** -- Mode offline. Melempar error jika tidak ada data cache.

> **Catatan:** Jika Anda memberikan `cacheKey` atau `cacheDuration` tanpa menentukan `cachePolicy`, kebijakan default adalah `cacheFirst`.


<div id="retrying-failed-requests"></div>

## Mencoba Ulang Permintaan Gagal

Secara otomatis mencoba ulang permintaan yang gagal.

### Retry Dasar

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### Retry dengan Jeda

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### Retry Bersyarat

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryIf: (DioException dioException) {
      // Only retry on server errors
      return dioException.response?.statusCode == 500;
    },
  );
}
```

### Retry Level Service

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## Pemeriksaan Konektivitas

Gagal cepat ketika perangkat offline alih-alih menunggu timeout.

### Level Service

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### Per-Permintaan

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### Dinamis

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

Ketika diaktifkan dan perangkat offline:
- Kebijakan `networkFirst` fallback ke cache
- Kebijakan lain melempar `DioExceptionType.connectionError` langsung


<div id="cancel-tokens"></div>

## Cancel Token

Kelola dan batalkan permintaan yang tertunda.

```dart
// Create a managed cancel token
final token = apiService.createCancelToken();
await apiService.get('/endpoint', cancelToken: token);

// Cancel all pending requests (e.g., on logout)
apiService.cancelAllRequests('User logged out');

// Check active request count
int count = apiService.activeRequestCount;

// Clean up a specific token when done
apiService.removeCancelToken(token);
```


<div id="setting-auth-headers"></div>

## Mengatur Header Auth

Override `setAuthHeaders` untuk melampirkan header autentikasi ke setiap permintaan. Method ini dipanggil sebelum setiap permintaan ketika `shouldSetAuthHeaders` bernilai `true` (default).

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<RequestHeaders> setAuthHeaders(RequestHeaders headers) async {
    String? myAuthToken = Auth.data(field: 'token');
    if (myAuthToken != null) {
      headers.addBearerToken(myAuthToken);
    }
    return headers;
  }
}
```

### Menonaktifkan Header Auth

Untuk endpoint publik yang tidak membutuhkan autentikasi:

```dart
// Per-request
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// Service-level
apiService.setShouldSetAuthHeaders(false);
```

**Lihat juga:** [Autentikasi](/docs/{{ $version }}/authentication) untuk detail tentang mengautentikasi pengguna dan menyimpan token.


<div id="refreshing-tokens"></div>

## Memperbarui Token

Override `shouldRefreshToken` dan `refreshToken` untuk menangani kedaluwarsa token. Method ini dipanggil sebelum setiap permintaan.

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<bool> shouldRefreshToken() async {
    // Check if the token needs refreshing
    return false;
  }

  @override
  Future<void> refreshToken(Dio dio) async {
    // Use the fresh Dio instance (no interceptors) to refresh the token
    dynamic response = (await dio.post("https://example.com/refresh-token")).data;

    // Save the new token to storage
    await Auth.set((data) {
      data['token'] = response['token'];
      return data;
    });
  }
}
```

Parameter `dio` di `refreshToken` adalah instance Dio baru, terpisah dari instance utama service, untuk menghindari loop interceptor.


<div id="singleton-api-service"></div>

## Singleton API Service

Secara default, helper `api` membuat instance baru setiap kali. Untuk menggunakan singleton, berikan instance alih-alih factory di `config/decoders.dart`:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // New instance each time

  ApiService: ApiService(), // Singleton — same instance always
};
```


<div id="advanced-configuration"></div>

## Konfigurasi Lanjutan

### Inisialisasi Dio Kustom

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(
    buildContext,
    decoders: modelDecoders,
    initDio: (Dio dio) {
      dio.options.validateStatus = (status) => status! < 500;
      return dio;
    },
  );
}
```

### Mengakses Instance Dio

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### Helper Paginasi

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### Callback Event

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### Properti yang Dapat Di-override

| Properti | Tipe | Default | Deskripsi |
|----------|------|---------|-----------|
| `baseUrl` | `String` | `""` | Base URL untuk semua permintaan |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Interceptor Dio |
| `decoders` | `Map<Type, dynamic>?` | `{}` | Decoder model untuk pengubahan JSON |
| `shouldSetAuthHeaders` | `bool` | `true` | Apakah memanggil `setAuthHeaders` sebelum permintaan |
| `retry` | `int` | `0` | Percobaan retry default |
| `retryDelay` | `Duration` | `1 second` | Jeda default antar retry |
| `checkConnectivityBeforeRequest` | `bool` | `false` | Periksa konektivitas sebelum permintaan |
