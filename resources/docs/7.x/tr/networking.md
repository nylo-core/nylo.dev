# Networking

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- HTTP İstekleri Yapma
  - [Kısa Yol Metotları](#convenience-methods "Kısa yol metotları")
  - [Network Yardımcısı](#network-helper "Network yardımcısı")
  - [networkResponse Yardımcısı](#network-response-helper "networkResponse yardımcısı")
  - [NyResponse](#ny-response "NyResponse")
  - [Temel Seçenekler](#base-options "Temel seçenekler")
  - [Başlık Ekleme](#adding-headers "Başlık ekleme")
- Dosya İşlemleri
  - [Dosya Yükleme](#uploading-files "Dosya yükleme")
  - [Dosya İndirme](#downloading-files "Dosya indirme")
- [Interceptor'lar](#interceptors "Interceptor'lar")
  - [Ağ Günlükçüsü](#network-logger "Ağ günlükçüsü")
- [API Servisi Kullanma](#using-an-api-service "API servisi kullanma")
- [API Servisi Oluşturma](#create-an-api-service "API servisi oluşturma")
- [JSON'u Modellere Dönüştürme](#morphing-json-payloads-to-models "JSON'u modellere dönüştürme")
- Önbellekleme
  - [Yanıtları Önbellekleme](#caching-responses "Yanıtları önbellekleme")
  - [Önbellek Politikaları](#cache-policies "Önbellek politikaları")
- Hata Yönetimi
  - [Başarısız İstekleri Yeniden Deneme](#retrying-failed-requests "Başarısız istekleri yeniden deneme")
  - [Bağlantı Kontrolleri](#connectivity-checks "Bağlantı kontrolleri")
  - [İptal Token'ları](#cancel-tokens "İptal token'ları")
- Kimlik Doğrulama
  - [Kimlik Doğrulama Başlıkları Ayarlama](#setting-auth-headers "Kimlik doğrulama başlıkları ayarlama")
  - [Token Yenileme](#refreshing-tokens "Token yenileme")
- [Singleton API Servisi](#singleton-api-service "Singleton API servisi")
- [Gelişmiş Yapılandırma](#advanced-configuration "Gelişmiş yapılandırma")

<div id="introduction"></div>

## Giriş

{{ config('app.name') }} ağ işlemlerini basit hale getirir. API uç noktalarını `NyApiService`'i genişleten servis sınıflarında tanımlarsınız, ardından bunları sayfalarınızdan çağırırsınız. Framework, JSON çözümleme, hata yönetimi, önbellekleme ve yanıtların model sınıflarınıza otomatik dönüştürülmesini ("morphing" olarak adlandırılır) yönetir.

API servisleriniz `lib/app/networking/` dizininde bulunur. Yeni bir proje varsayılan bir `ApiService` içerir:

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

HTTP istekleri yapmanın üç yolu vardır:

| Yaklaşım | Döndürür | En İyi Kullanım |
|----------|---------|----------|
| Kısa yol metotları (`get`, `post`, vb.) | `T?` | Basit CRUD işlemleri |
| `network()` | `T?` | Önbellekleme, yeniden deneme veya özel başlıklar gerektiren istekler |
| `networkResponse()` | `NyResponse<T>` | Durum kodları, başlıklar veya hata ayrıntılarına ihtiyaç duyduğunuzda |

Arka planda, {{ config('app.name') }} güçlü bir HTTP istemcisi olan <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>'yu kullanır.


<div id="convenience-methods"></div>

## Kısa Yol Metotları

`NyApiService`, yaygın HTTP işlemleri için kısa yol metotları sağlar. Bunlar dahili olarak `network()`'ü çağırır.

### GET İsteği

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### POST İsteği

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### PUT İsteği

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### DELETE İsteği

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### PATCH İsteği

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### HEAD İsteği

Kaynak varlığını kontrol etmek veya gövdeyi indirmeden başlıkları almak için HEAD kullanın:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Network Yardımcısı

`network` metodu, kısa yol metotlarından daha fazla kontrol sağlar. Dönüştürülmüş veriyi (`T?`) doğrudan döndürür.

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

`request` callback'i, temel URL'niz ve interceptor'larınız önceden yapılandırılmış bir <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> örneği alır.

### network Parametreleri

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `request` | `Function(Dio)` | Gerçekleştirilecek HTTP isteği (zorunlu) |
| `bearerToken` | `String?` | Bu istek için Bearer token |
| `baseUrl` | `String?` | Servis temel URL'sini geçersiz kıl |
| `headers` | `Map<String, dynamic>?` | Ek başlıklar |
| `retry` | `int?` | Yeniden deneme sayısı |
| `retryDelay` | `Duration?` | Yeniden denemeler arası gecikme |
| `retryIf` | `bool Function(DioException)?` | Yeniden deneme koşulu |
| `connectionTimeout` | `Duration?` | Bağlantı zaman aşımı |
| `receiveTimeout` | `Duration?` | Alma zaman aşımı |
| `sendTimeout` | `Duration?` | Gönderme zaman aşımı |
| `cacheKey` | `String?` | Önbellek anahtarı |
| `cacheDuration` | `Duration?` | Önbellek süresi |
| `cachePolicy` | `CachePolicy?` | Önbellek stratejisi |
| `checkConnectivity` | `bool?` | İstekten önce bağlantıyı kontrol et |
| `handleSuccess` | `Function(NyResponse<T>)?` | Başarı callback'i |
| `handleFailure` | `Function(NyResponse<T>)?` | Hata callback'i |


<div id="network-response-helper"></div>

## networkResponse Yardımcısı

Tam yanıta -- durum kodları, başlıklar, hata mesajları -- erişmeniz gerektiğinde `networkResponse` kullanın; yalnızca veriye değil. `T?` yerine `NyResponse<T>` döndürür.

`networkResponse`'u şu durumlarda kullanın:
- Belirli bir işleme için HTTP durum kodlarını kontrol etmeniz gerektiğinde
- Yanıt başlıklarına erişmeniz gerektiğinde
- Kullanıcı geri bildirimi için ayrıntılı hata mesajları almanız gerektiğinde
- Özel hata yönetimi mantığı uygulamanız gerektiğinde

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

Ardından yanıtı sayfanızda kullanın:

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

### network ile networkResponse Karşılaştırması

```dart
// network() — returns the data directly
User? user = await network<User>(
  request: (request) => request.get("/users/1"),
);

// networkResponse() — returns the full response
NyResponse<User> response = await networkResponse<User>(
  request: (request) => request.get("/users/1"),
);
User? user = response.data;
int? status = response.statusCode;
```

Her iki metot da aynı parametreleri kabul eder. Yanıtı verinin ötesinde incelemeniz gerektiğinde `networkResponse`'u seçin.


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>`, Dio yanıtını dönüştürülmüş veri ve durum yardımcılarıyla sarar.

### Özellikler

| Özellik | Tür | Açıklama |
|----------|------|-------------|
| `response` | `Response?` | Orijinal Dio Response |
| `data` | `T?` | Dönüştürülmüş/çözümlenmiş veri |
| `rawData` | `dynamic` | Ham yanıt verisi |
| `headers` | `Headers?` | Yanıt başlıkları |
| `statusCode` | `int?` | HTTP durum kodu |
| `statusMessage` | `String?` | HTTP durum mesajı |
| `contentType` | `String?` | Başlıklardan içerik türü |
| `errorMessage` | `String?` | Çıkarılan hata mesajı |

### Durum Kontrolleri

| Getter | Açıklama |
|--------|-------------|
| `isSuccessful` | Durum 200-299 |
| `isClientError` | Durum 400-499 |
| `isServerError` | Durum 500-599 |
| `isRedirect` | Durum 300-399 |
| `hasData` | Veri null değil |
| `isUnauthorized` | Durum 401 |
| `isForbidden` | Durum 403 |
| `isNotFound` | Durum 404 |
| `isTimeout` | Durum 408 |
| `isConflict` | Durum 409 |
| `isRateLimited` | Durum 429 |

### Veri Yardımcıları

```dart
NyResponse<User> response = await apiService.fetchUser(1);

// Get data or throw if null
User user = response.dataOrThrow('User not found');

// Get data or use a fallback
User user = response.dataOr(User.guest());

// Run callback only if successful
String? greeting = response.ifSuccessful((user) => 'Hello ${user.name}');

// Pattern match on success/failure
String result = response.when(
  success: (user) => 'Welcome, ${user.name}!',
  failure: (response) => 'Error: ${response.statusMessage}',
);

// Get a specific header
String? authHeader = response.getHeader('Authorization');
```


<div id="base-options"></div>

## Temel Seçenekler

API servisiniz için varsayılan Dio seçeneklerini `baseOptions` parametresini kullanarak yapılandırın:

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

Seçenekleri bir örnek üzerinde dinamik olarak da yapılandırabilirsiniz:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

Ayarlayabileceğiniz tüm temel seçenekleri görmek için <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">buraya</a> tıklayın.


<div id="adding-headers"></div>

## Başlık Ekleme

### İstek Başına Başlıklar

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

### Servis Düzeyinde Başlıklar

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### RequestHeaders Uzantısı

`RequestHeaders` türü (bir `Map<String, dynamic>` typedef'i) yardımcı metotlar sağlar:

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

| Metot | Açıklama |
|--------|-------------|
| `addBearerToken(token)` | `Authorization: Bearer` başlığını ayarla |
| `getBearerToken()` | Başlıklardan bearer token'ı oku |
| `addHeader(key, value)` | Özel bir başlık ekle |
| `hasHeader(key)` | Bir başlığın var olup olmadığını kontrol et |


<div id="uploading-files"></div>

## Dosya Yükleme

### Tekli Dosya Yükleme

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

### Çoklu Dosya Yükleme

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

## Dosya İndirme

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

## Interceptor'lar

Interceptor'lar, istekleri gönderilmeden önce değiştirmenize, yanıtları yönetmenize ve hataları ele almanıza olanak tanır. API servisi üzerinden yapılan her istekte çalışırlar.

Interceptor'ları şu durumlarda kullanın:
- Tüm isteklere kimlik doğrulama başlıkları eklemeniz gerektiğinde
- Hata ayıklama için istek ve yanıtları günlüğe kaydetmeniz gerektiğinde
- İstek/yanıt verilerini global olarak dönüştürmeniz gerektiğinde
- Belirli hata kodlarını ele almanız gerektiğinde (örn. 401'de token yenileme)

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

### Özel Interceptor Oluşturma

```bash
metro make:interceptor logging
```

**Dosya:** `app/networking/dio/interceptors/logging_interceptor.dart`

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

## Ağ Günlükçüsü

{{ config('app.name') }}, yerleşik bir `NetworkLogger` interceptor'u içerir. Ortamınızda `APP_DEBUG` `true` olduğunda varsayılan olarak etkindir.

### Yapılandırma

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

`useNetworkLogger: false` ayarlayarak devre dışı bırakabilirsiniz.

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- Günlükçüyü devre dışı bırak
        );
```

### Günlük Seviyeleri

| Seviye | Açıklama |
|-------|-------------|
| `LogLevelType.verbose` | Tüm istek/yanıt ayrıntılarını yazdır |
| `LogLevelType.minimal` | Yalnızca metot, URL, durum ve süreyi yazdır |
| `LogLevelType.none` | Günlük çıktısı yok |

### Günlükleri Filtreleme

```dart
NetworkLogger(
  filter: (options, args) {
    // Only log requests to specific endpoints
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## API Servisi Kullanma

API servisinizi bir sayfadan çağırmanın iki yolu vardır.

### Doğrudan Örnekleme

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

### api() Yardımcısını Kullanma

`api` yardımcısı, `config/decoders.dart`'taki `apiDecoders`'ınızı kullanarak örnekler oluşturur:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

Callback'lerle birlikte:

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

### api() Yardımcısı Parametreleri

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `request` | `Function(T)` | API istek fonksiyonu |
| `context` | `BuildContext?` | Build bağlamı |
| `headers` | `Map<String, dynamic>` | Ek başlıklar |
| `bearerToken` | `String?` | Bearer token |
| `baseUrl` | `String?` | Temel URL'yi geçersiz kıl |
| `page` | `int?` | Sayfalandırma sayfası |
| `perPage` | `int?` | Sayfa başına öğe |
| `retry` | `int` | Yeniden deneme sayısı |
| `retryDelay` | `Duration?` | Yeniden denemeler arası gecikme |
| `onSuccess` | `Function(Response, dynamic)?` | Başarı callback'i |
| `onError` | `Function(DioException)?` | Hata callback'i |
| `cacheKey` | `String?` | Önbellek anahtarı |
| `cacheDuration` | `Duration?` | Önbellek süresi |


<div id="create-an-api-service"></div>

## API Servisi Oluşturma

Yeni bir API servisi oluşturmak için:

```bash
metro make:api_service user
```

Model ile birlikte:

```bash
metro make:api_service user --model="User"
```

Bu, CRUD metotlarına sahip bir API servisi oluşturur:

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

## JSON'u Modellere Dönüştürme

"Morphing", {{ config('app.name') }}'in JSON yanıtlarını Dart model sınıflarınıza otomatik olarak dönüştürmesi için kullandığı terimdir. `network<User>(...)` kullandığınızda, yanıt JSON'u bir `User` örneği oluşturmak için decoder'ınızdan geçirilir -- manuel ayrıştırma gerekmez.

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  // Returns a single User
  Future<User?> fetchUser() async {
    return await network<User>(
      request: (request) => request.get("/user/1"),
    );
  }

  // Returns a List of Users
  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }
}
```

Decoder'lar `lib/bootstrap/decoders.dart` dosyasında tanımlanır:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

`network<T>()`'ye geçtiğiniz tür parametresi, doğru decoder'ı bulmak için `modelDecoders` haritanızla eşleştirilir.

**Ayrıca bakınız:** [Decoders](/docs/{{$version}}/decoders#model-decoders) model decoder'larını kaydetme hakkında ayrıntılar için.


<div id="caching-responses"></div>

## Yanıtları Önbellekleme

API çağrılarını azaltmak ve performansı artırmak için yanıtları önbelleğe alın. Önbellekleme, ülke listeleri, kategoriler veya yapılandırma gibi sık değişmeyen veriler için kullanışlıdır.

Bir `cacheKey` ve isteğe bağlı `cacheDuration` sağlayın:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### Önbelleği Temizleme

```dart
// Clear a specific cache key
await apiService.clearCache("app_countries");

// Clear all API cache
await apiService.clearAllCache();
```

### api() Yardımcısı ile Önbellekleme

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## Önbellek Politikaları

Önbellekleme davranışı üzerinde ince ayarlı kontrol için `CachePolicy` kullanın:

| Politika | Açıklama |
|--------|-------------|
| `CachePolicy.networkOnly` | Her zaman ağdan getir (varsayılan) |
| `CachePolicy.cacheFirst` | Önce önbelleği dene, ağa geri dön |
| `CachePolicy.networkFirst` | Önce ağı dene, önbelleğe geri dön |
| `CachePolicy.cacheOnly` | Yalnızca önbellek kullan, boşsa hata ver |
| `CachePolicy.staleWhileRevalidate` | Önbelleği hemen döndür, arka planda güncelle |

### Kullanım

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

### Her Politikayı Ne Zaman Kullanmalı

- **cacheFirst** -- Nadiren değişen veriler. Önbelleğe alınmış veriyi anında döndürür, yalnızca önbellek boşsa ağdan getirir.
- **networkFirst** -- Mümkün olduğunda güncel olması gereken veriler. Önce ağı dener, başarısız olursa önbelleğe döner.
- **staleWhileRevalidate** -- Anında yanıt vermesi gereken ancak güncel kalması gereken arayüzler. Önbelleğe alınmış veriyi döndürür, ardından arka planda yeniler.
- **cacheOnly** -- Çevrimdışı mod. Önbelleğe alınmış veri yoksa hata verir.

> **Not:** Bir `cachePolicy` belirtmeden `cacheKey` veya `cacheDuration` sağlarsanız, varsayılan politika `cacheFirst`'tir.


<div id="retrying-failed-requests"></div>

## Başarısız İstekleri Yeniden Deneme

Başarısız olan istekleri otomatik olarak yeniden deneyin.

### Temel Yeniden Deneme

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### Gecikmeli Yeniden Deneme

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### Koşullu Yeniden Deneme

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

### Servis Düzeyinde Yeniden Deneme

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## Bağlantı Kontrolleri

Cihaz çevrimdışı olduğunda zaman aşımı beklemek yerine hızlıca başarısız olun.

### Servis Düzeyinde

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### İstek Başına

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### Dinamik

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

Etkinleştirildiğinde ve cihaz çevrimdışı olduğunda:
- `networkFirst` politikası önbelleğe geri döner
- Diğer politikalar hemen `DioExceptionType.connectionError` fırlatır


<div id="cancel-tokens"></div>

## İptal Token'ları

Bekleyen istekleri yönetin ve iptal edin.

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

## Kimlik Doğrulama Başlıkları Ayarlama

Her isteğe kimlik doğrulama başlıkları eklemek için `setAuthHeaders`'ı geçersiz kılın. Bu metot, `shouldSetAuthHeaders` `true` olduğunda (varsayılan) her istekten önce çağrılır.

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

### Kimlik Doğrulama Başlıklarını Devre Dışı Bırakma

Kimlik doğrulama gerektirmeyen genel uç noktalar için:

```dart
// Per-request
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// Service-level
apiService.setShouldSetAuthHeaders(false);
```

**Ayrıca bakınız:** [Kimlik Doğrulama](/docs/{{ $version }}/authentication) kullanıcıları doğrulama ve token saklama hakkında ayrıntılar için.


<div id="refreshing-tokens"></div>

## Token Yenileme

Token süresinin dolmasını yönetmek için `shouldRefreshToken` ve `refreshToken`'ı geçersiz kılın. Bunlar her istekten önce çağrılır.

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

`refreshToken`'daki `dio` parametresi, interceptor döngülerini önlemek için servisin ana örneğinden ayrı yeni bir Dio örneğidir.


<div id="singleton-api-service"></div>

## Singleton API Servisi

Varsayılan olarak, `api` yardımcısı her seferinde yeni bir örnek oluşturur. Singleton kullanmak için, `config/decoders.dart`'ta fabrika yerine bir örnek geçirin:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // New instance each time

  ApiService: ApiService(), // Singleton — same instance always
};
```


<div id="advanced-configuration"></div>

## Gelişmiş Yapılandırma

### Özel Dio Başlatma

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

### Dio Örneğine Erişim

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### Sayfalandırma Yardımcısı

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### Olay Callback'leri

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### Geçersiz Kılınabilir Özellikler

| Özellik | Tür | Varsayılan | Açıklama |
|----------|------|---------|-------------|
| `baseUrl` | `String` | `""` | Tüm istekler için temel URL |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Dio interceptor'ları |
| `decoders` | `Map<Type, dynamic>?` | `{}` | JSON dönüştürme için model decoder'ları |
| `shouldSetAuthHeaders` | `bool` | `true` | İsteklerden önce `setAuthHeaders`'ın çağrılıp çağrılmayacağı |
| `retry` | `int` | `0` | Varsayılan yeniden deneme sayısı |
| `retryDelay` | `Duration` | `1 second` | Yeniden denemeler arası varsayılan gecikme |
| `checkConnectivityBeforeRequest` | `bool` | `false` | İsteklerden önce bağlantıyı kontrol et |
