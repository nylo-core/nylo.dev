# Networking

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- การส่ง HTTP Request
  - [เมธอดลัด](#convenience-methods "เมธอดลัด")
  - [Network Helper](#network-helper "Network Helper")
  - [networkResponse Helper](#network-response-helper "networkResponse Helper")
  - [NyResponse](#ny-response "NyResponse")
  - [ตัวเลือกพื้นฐาน](#base-options "ตัวเลือกพื้นฐาน")
  - [การเพิ่ม Headers](#adding-headers "การเพิ่ม Headers")
- การดำเนินการกับไฟล์
  - [การอัปโหลดไฟล์](#uploading-files "การอัปโหลดไฟล์")
  - [การดาวน์โหลดไฟล์](#downloading-files "การดาวน์โหลดไฟล์")
- [Interceptors](#interceptors "Interceptors")
  - [Network Logger](#network-logger "Network Logger")
- [การใช้ API Service](#using-an-api-service "การใช้ API Service")
- [สร้าง API Service](#create-an-api-service "สร้าง API Service")
- [การแปลง JSON เป็น Models](#morphing-json-payloads-to-models "การแปลง JSON เป็น Models")
- การแคช
  - [การแคช Response](#caching-responses "การแคช Response")
  - [นโยบายแคช](#cache-policies "นโยบายแคช")
- การจัดการข้อผิดพลาด
  - [การลองส่ง Request ใหม่](#retrying-failed-requests "การลองส่ง Request ใหม่")
  - [การตรวจสอบการเชื่อมต่อ](#connectivity-checks "การตรวจสอบการเชื่อมต่อ")
  - [Cancel Tokens](#cancel-tokens "Cancel Tokens")
- การยืนยันตัวตน
  - [การตั้งค่า Auth Headers](#setting-auth-headers "การตั้งค่า Auth Headers")
  - [การรีเฟรช Tokens](#refreshing-tokens "การรีเฟรช Tokens")
- [Singleton API Service](#singleton-api-service "Singleton API Service")
- [การตั้งค่าขั้นสูง](#advanced-configuration "การตั้งค่าขั้นสูง")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} ทำให้การเชื่อมต่อเครือข่ายเป็นเรื่องง่าย คุณกำหนด API endpoint ในคลาส service ที่ extend จาก `NyApiService` จากนั้นเรียกใช้จากหน้าของคุณ framework จัดการการถอดรหัส JSON, การจัดการข้อผิดพลาด, การแคช และการแปลง response เป็นคลาส model ของคุณโดยอัตโนมัติ (เรียกว่า "morphing")

API services ของคุณอยู่ใน `lib/app/networking/` โปรเจกต์ใหม่มี `ApiService` เริ่มต้น:

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

มีสามวิธีในการส่ง HTTP request:

| วิธี | คืนค่า | เหมาะสำหรับ |
|----------|---------|----------|
| เมธอดลัด (`get`, `post`, ฯลฯ) | `T?` | การดำเนินการ CRUD ง่ายๆ |
| `network()` | `T?` | Request ที่ต้องการแคช, retry หรือ headers แบบกำหนดเอง |
| `networkResponse()` | `NyResponse<T>` | เมื่อคุณต้องการ status codes, headers หรือรายละเอียดข้อผิดพลาด |

ภายใน {{ config('app.name') }} ใช้ <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> ซึ่งเป็น HTTP client ที่ทรงพลัง


<div id="convenience-methods"></div>

## เมธอดลัด

`NyApiService` มีเมธอดลัดสำหรับการดำเนินการ HTTP ทั่วไป สิ่งเหล่านี้เรียก `network()` ภายใน

### GET Request

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### POST Request

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### PUT Request

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### DELETE Request

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### PATCH Request

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### HEAD Request

ใช้ HEAD เพื่อตรวจสอบการมีอยู่ของ resource หรือดึง headers โดยไม่ดาวน์โหลด body:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Network Helper

เมธอด `network` ให้คุณควบคุมได้มากกว่าเมธอดลัด มันคืนค่าข้อมูลที่ morph แล้ว (`T?`) โดยตรง

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

callback `request` รับ instance <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> ที่มี base URL และ interceptors ที่ตั้งค่าไว้แล้ว

### พารามิเตอร์ network

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `request` | `Function(Dio)` | HTTP request ที่จะดำเนินการ (จำเป็น) |
| `bearerToken` | `String?` | Bearer token สำหรับ request นี้ |
| `baseUrl` | `String?` | แทนที่ base URL ของ service |
| `headers` | `Map<String, dynamic>?` | headers เพิ่มเติม |
| `retry` | `int?` | จำนวนครั้งที่ลองใหม่ |
| `retryDelay` | `Duration?` | ระยะเวลาระหว่างการลองใหม่ |
| `retryIf` | `bool Function(DioException)?` | เงื่อนไขสำหรับการลองใหม่ |
| `connectionTimeout` | `Duration?` | หมดเวลาการเชื่อมต่อ |
| `receiveTimeout` | `Duration?` | หมดเวลาการรับ |
| `sendTimeout` | `Duration?` | หมดเวลาการส่ง |
| `cacheKey` | `String?` | key แคช |
| `cacheDuration` | `Duration?` | ระยะเวลาแคช |
| `cachePolicy` | `CachePolicy?` | กลยุทธ์แคช |
| `checkConnectivity` | `bool?` | ตรวจสอบการเชื่อมต่อก่อน request |
| `handleSuccess` | `Function(NyResponse<T>)?` | callback สำเร็จ |
| `handleFailure` | `Function(NyResponse<T>)?` | callback ล้มเหลว |


<div id="network-response-helper"></div>

## networkResponse Helper

ใช้ `networkResponse` เมื่อคุณต้องการเข้าถึง response เต็มรูปแบบ - status codes, headers, ข้อความผิดพลาด - ไม่ใช่แค่ข้อมูล มันคืนค่า `NyResponse<T>` แทน `T?`

ใช้ `networkResponse` เมื่อคุณต้องการ:
- ตรวจสอบ HTTP status codes สำหรับการจัดการเฉพาะ
- เข้าถึง response headers
- รับข้อความผิดพลาดโดยละเอียดสำหรับ feedback ผู้ใช้
- ใช้ลอจิกการจัดการข้อผิดพลาดแบบกำหนดเอง

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

จากนั้นใช้ response ในหน้าของคุณ:

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

### network เทียบกับ networkResponse

```dart
// network() — คืนค่าข้อมูลโดยตรง
User? user = await network<User>(
  request: (request) => request.get("/users/1"),
);

// networkResponse() — คืนค่า response เต็มรูปแบบ
NyResponse<User> response = await networkResponse<User>(
  request: (request) => request.get("/users/1"),
);
User? user = response.data;
int? status = response.statusCode;
```

ทั้งสองเมธอดรับพารามิเตอร์เดียวกัน เลือก `networkResponse` เมื่อคุณต้องการตรวจสอบ response นอกเหนือจากแค่ข้อมูล


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` ครอบ Dio response พร้อมข้อมูลที่ morph แล้วและตัวช่วยสถานะ

### คุณสมบัติ

| คุณสมบัติ | ชนิด | คำอธิบาย |
|----------|------|-------------|
| `response` | `Response?` | Dio Response ดั้งเดิม |
| `data` | `T?` | ข้อมูลที่ morph/decode แล้ว |
| `rawData` | `dynamic` | ข้อมูล response ดิบ |
| `headers` | `Headers?` | Response headers |
| `statusCode` | `int?` | HTTP status code |
| `statusMessage` | `String?` | HTTP status message |
| `contentType` | `String?` | Content type จาก headers |
| `errorMessage` | `String?` | ข้อความผิดพลาดที่แยกออกมา |

### การตรวจสอบสถานะ

| Getter | คำอธิบาย |
|--------|-------------|
| `isSuccessful` | Status 200-299 |
| `isClientError` | Status 400-499 |
| `isServerError` | Status 500-599 |
| `isRedirect` | Status 300-399 |
| `hasData` | ข้อมูลไม่เป็น null |
| `isUnauthorized` | Status 401 |
| `isForbidden` | Status 403 |
| `isNotFound` | Status 404 |
| `isTimeout` | Status 408 |
| `isConflict` | Status 409 |
| `isRateLimited` | Status 429 |

### ตัวช่วยข้อมูล

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

## ตัวเลือกพื้นฐาน

ตั้งค่าตัวเลือก Dio เริ่มต้นสำหรับ API service ของคุณโดยใช้พารามิเตอร์ `baseOptions`:

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

คุณยังสามารถตั้งค่าตัวเลือกแบบไดนามิกบน instance:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

คลิก <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">ที่นี่</a> เพื่อดูตัวเลือกพื้นฐานทั้งหมดที่คุณสามารถตั้งค่าได้


<div id="adding-headers"></div>

## การเพิ่ม Headers

### Headers ต่อ Request

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

### Headers ระดับ Service

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### RequestHeaders Extension

ชนิด `RequestHeaders` (typedef ของ `Map<String, dynamic>`) มี method ตัวช่วย:

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

| เมธอด | คำอธิบาย |
|--------|-------------|
| `addBearerToken(token)` | ตั้งค่า header `Authorization: Bearer` |
| `getBearerToken()` | อ่าน bearer token จาก headers |
| `addHeader(key, value)` | เพิ่ม header แบบกำหนดเอง |
| `hasHeader(key)` | ตรวจสอบว่า header มีอยู่หรือไม่ |


<div id="uploading-files"></div>

## การอัปโหลดไฟล์

### อัปโหลดไฟล์เดียว

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

### อัปโหลดหลายไฟล์

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

## การดาวน์โหลดไฟล์

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

## Interceptors

Interceptors ช่วยให้คุณแก้ไข request ก่อนส่ง จัดการ response และจัดการข้อผิดพลาด พวกมันทำงานกับทุก request ที่ส่งผ่าน API service

ใช้ interceptors เมื่อคุณต้องการ:
- เพิ่ม authentication headers ให้กับทุก request
- บันทึก request และ response สำหรับการ debug
- แปลงข้อมูล request/response ทั่วไป
- จัดการ error code เฉพาะ (เช่น refresh token เมื่อได้รับ 401)

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

### สร้าง Interceptor แบบกำหนดเอง

```bash
metro make:interceptor logging
```

**ไฟล์:** `app/networking/dio/interceptors/logging_interceptor.dart`

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

{{ config('app.name') }} มี interceptor `NetworkLogger` ในตัว มันถูกเปิดใช้งานโดยค่าเริ่มต้นเมื่อ `APP_DEBUG` เป็น `true` ใน environment ของคุณ

### การตั้งค่า

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

คุณสามารถปิดได้โดยตั้งค่า `useNetworkLogger: false`

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- ปิด logger
        );
```

### ระดับ Log

| ระดับ | คำอธิบาย |
|-------|-------------|
| `LogLevelType.verbose` | พิมพ์รายละเอียด request/response ทั้งหมด |
| `LogLevelType.minimal` | พิมพ์เฉพาะ method, URL, status และเวลา |
| `LogLevelType.none` | ไม่มี output |

### การกรอง Logs

```dart
NetworkLogger(
  filter: (options, args) {
    // บันทึกเฉพาะ request ไปยัง endpoint เฉพาะ
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## การใช้ API Service

มีสองวิธีในการเรียก API service จากหน้า

### สร้าง Instance โดยตรง

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

### ใช้ตัวช่วย api()

ตัวช่วย `api` สร้าง instance โดยใช้ `apiDecoders` จาก `config/decoders.dart`:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

พร้อม callbacks:

```dart
await api<ApiService>(
  (request) => request.fetchUser(),
  onSuccess: (response, data) {
    // data คือ instance User? ที่ morph แล้ว
  },
  onError: (DioException dioException) {
    // จัดการข้อผิดพลาด
  },
);
```

### พารามิเตอร์ตัวช่วย api()

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `request` | `Function(T)` | ฟังก์ชัน API request |
| `context` | `BuildContext?` | Build context |
| `headers` | `Map<String, dynamic>` | headers เพิ่มเติม |
| `bearerToken` | `String?` | Bearer token |
| `baseUrl` | `String?` | แทนที่ base URL |
| `page` | `int?` | หน้าสำหรับ pagination |
| `perPage` | `int?` | จำนวนรายการต่อหน้า |
| `retry` | `int` | จำนวนครั้งที่ลองใหม่ |
| `retryDelay` | `Duration?` | ระยะเวลาระหว่างการลองใหม่ |
| `onSuccess` | `Function(Response, dynamic)?` | callback สำเร็จ |
| `onError` | `Function(DioException)?` | callback ข้อผิดพลาด |
| `cacheKey` | `String?` | key แคช |
| `cacheDuration` | `Duration?` | ระยะเวลาแคช |


<div id="create-an-api-service"></div>

## สร้าง API Service

เพื่อสร้าง API service ใหม่:

```bash
metro make:api_service user
```

พร้อม model:

```bash
metro make:api_service user --model="User"
```

สิ่งนี้สร้าง API service พร้อมเมธอด CRUD:

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

## การแปลง JSON เป็น Models

"Morphing" คือคำที่ {{ config('app.name') }} ใช้สำหรับการแปลง JSON response เป็นคลาส Dart model ของคุณโดยอัตโนมัติ เมื่อคุณใช้ `network<User>(...)` JSON response จะถูกส่งผ่าน decoder ของคุณเพื่อสร้าง instance `User` - ไม่ต้อง parse เอง

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  // คืนค่า User เดียว
  Future<User?> fetchUser() async {
    return await network<User>(
      request: (request) => request.get("/user/1"),
    );
  }

  // คืนค่า List ของ Users
  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }
}
```

Decoders ถูกกำหนดใน `lib/bootstrap/decoders.dart`:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

พารามิเตอร์ type ที่คุณส่งไปยัง `network<T>()` จะถูกจับคู่กับ map `modelDecoders` ของคุณเพื่อหา decoder ที่ถูกต้อง

**ดูเพิ่มเติม:** [Decoders](/docs/{{$version}}/decoders#model-decoders) สำหรับรายละเอียดเกี่ยวกับการลงทะเบียน model decoders


<div id="caching-responses"></div>

## การแคช Response

แคช response เพื่อลดการเรียก API และปรับปรุงประสิทธิภาพ การแคชมีประโยชน์สำหรับข้อมูลที่ไม่เปลี่ยนบ่อย เช่น รายชื่อประเทศ หมวดหมู่ หรือการตั้งค่า

ระบุ `cacheKey` และ `cacheDuration` ที่เป็นตัวเลือก:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### การล้างแคช

```dart
// ล้าง cache key เฉพาะ
await apiService.clearCache("app_countries");

// ล้างแคช API ทั้งหมด
await apiService.clearAllCache();
```

### การแคชด้วยตัวช่วย api()

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## นโยบายแคช

ใช้ `CachePolicy` สำหรับการควบคุมพฤติกรรมแคชอย่างละเอียด:

| นโยบาย | คำอธิบาย |
|--------|-------------|
| `CachePolicy.networkOnly` | ดึงจากเครือข่ายเสมอ (ค่าเริ่มต้น) |
| `CachePolicy.cacheFirst` | ลองแคชก่อน fallback ไปเครือข่าย |
| `CachePolicy.networkFirst` | ลองเครือข่ายก่อน fallback ไปแคช |
| `CachePolicy.cacheOnly` | ใช้เฉพาะแคช error ถ้าว่าง |
| `CachePolicy.staleWhileRevalidate` | คืนค่าแคชทันที อัปเดตในพื้นหลัง |

### การใช้งาน

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

### เมื่อใดควรใช้แต่ละนโยบาย

- **cacheFirst** — ข้อมูลที่เปลี่ยนน้อย คืนค่าข้อมูลแคชทันที ดึงจากเครือข่ายเฉพาะเมื่อแคชว่าง
- **networkFirst** — ข้อมูลที่ควรเป็นปัจจุบันเมื่อเป็นไปได้ ลองเครือข่ายก่อน fall back ไปแคชเมื่อล้มเหลว
- **staleWhileRevalidate** — UI ที่ต้องการ response ทันทีแต่ควรอัปเดตอยู่ คืนค่าข้อมูลแคช จากนั้นรีเฟรชในพื้นหลัง
- **cacheOnly** — โหมดออฟไลน์ throw error ถ้าไม่มีข้อมูลแคช

> **หมายเหตุ:** หากคุณระบุ `cacheKey` หรือ `cacheDuration` โดยไม่ระบุ `cachePolicy` นโยบายเริ่มต้นคือ `cacheFirst`


<div id="retrying-failed-requests"></div>

## การลองส่ง Request ใหม่

ลองส่ง request ที่ล้มเหลวใหม่โดยอัตโนมัติ

### Retry พื้นฐาน

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### Retry พร้อม Delay

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### Retry แบบมีเงื่อนไข

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryIf: (DioException dioException) {
      // ลองใหม่เฉพาะเมื่อเกิด server error
      return dioException.response?.statusCode == 500;
    },
  );
}
```

### Retry ระดับ Service

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## การตรวจสอบการเชื่อมต่อ

ล้มเหลวอย่างรวดเร็วเมื่ออุปกรณ์ออฟไลน์แทนที่จะรอหมดเวลา

### ระดับ Service

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### ต่อ Request

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### แบบไดนามิก

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

เมื่อเปิดใช้งานและอุปกรณ์ออฟไลน์:
- นโยบาย `networkFirst` จะ fall back ไปแคช
- นโยบายอื่นจะ throw `DioExceptionType.connectionError` ทันที


<div id="cancel-tokens"></div>

## Cancel Tokens

จัดการและยกเลิก request ที่รอดำเนินการ

```dart
// สร้าง cancel token ที่จัดการ
final token = apiService.createCancelToken();
await apiService.get('/endpoint', cancelToken: token);

// ยกเลิก request ที่รอดำเนินการทั้งหมด (เช่น เมื่อ logout)
apiService.cancelAllRequests('User logged out');

// ตรวจสอบจำนวน request ที่กำลังทำงาน
int count = apiService.activeRequestCount;

// ล้าง token เฉพาะเมื่อเสร็จ
apiService.removeCancelToken(token);
```


<div id="setting-auth-headers"></div>

## การตั้งค่า Auth Headers

แทนที่ `setAuthHeaders` เพื่อแนบ authentication headers กับทุก request เมธอดนี้จะถูกเรียกก่อนแต่ละ request เมื่อ `shouldSetAuthHeaders` เป็น `true` (ค่าเริ่มต้น)

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

### ปิด Auth Headers

สำหรับ endpoint สาธารณะที่ไม่ต้องการ authentication:

```dart
// ต่อ request
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// ระดับ service
apiService.setShouldSetAuthHeaders(false);
```

**ดูเพิ่มเติม:** [Authentication](/docs/{{ $version }}/authentication) สำหรับรายละเอียดเกี่ยวกับการยืนยันตัวตนผู้ใช้และการจัดเก็บ tokens


<div id="refreshing-tokens"></div>

## การรีเฟรช Tokens

แทนที่ `shouldRefreshToken` และ `refreshToken` เพื่อจัดการ token หมดอายุ สิ่งเหล่านี้จะถูกเรียกก่อนทุก request

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<bool> shouldRefreshToken() async {
    // ตรวจสอบว่า token ต้องรีเฟรชหรือไม่
    return false;
  }

  @override
  Future<void> refreshToken(Dio dio) async {
    // ใช้ Dio instance ใหม่ (ไม่มี interceptors) เพื่อรีเฟรช token
    dynamic response = (await dio.post("https://example.com/refresh-token")).data;

    // บันทึก token ใหม่ไปยัง storage
    await Auth.set((data) {
      data['token'] = response['token'];
      return data;
    });
  }
}
```

พารามิเตอร์ `dio` ใน `refreshToken` เป็น Dio instance ใหม่ แยกจาก instance หลักของ service เพื่อหลีกเลี่ยงลูป interceptor


<div id="singleton-api-service"></div>

## Singleton API Service

โดยค่าเริ่มต้น ตัวช่วย `api` สร้าง instance ใหม่ทุกครั้ง เพื่อใช้ singleton ให้ส่ง instance แทน factory ใน `config/decoders.dart`:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // Instance ใหม่ทุกครั้ง

  ApiService: ApiService(), // Singleton — instance เดียวกันเสมอ
};
```


<div id="advanced-configuration"></div>

## การตั้งค่าขั้นสูง

### การเริ่มต้น Dio แบบกำหนดเอง

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

### การเข้าถึง Dio Instance

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### ตัวช่วย Pagination

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### Event Callbacks

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### คุณสมบัติที่แทนที่ได้

| คุณสมบัติ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|----------|------|---------|-------------|
| `baseUrl` | `String` | `""` | Base URL สำหรับทุก request |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Dio interceptors |
| `decoders` | `Map<Type, dynamic>?` | `{}` | Model decoders สำหรับ JSON morphing |
| `shouldSetAuthHeaders` | `bool` | `true` | จะเรียก `setAuthHeaders` ก่อน request หรือไม่ |
| `retry` | `int` | `0` | จำนวนครั้งที่ลองใหม่เริ่มต้น |
| `retryDelay` | `Duration` | `1 second` | ระยะเวลาเริ่มต้นระหว่างการลองใหม่ |
| `checkConnectivityBeforeRequest` | `bool` | `false` | ตรวจสอบการเชื่อมต่อก่อน request |
