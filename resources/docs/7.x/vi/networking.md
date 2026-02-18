# Networking

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- Thực hiện yêu cầu HTTP
  - [Các phương thức tiện ích](#convenience-methods "Các phương thức tiện ích")
  - [Network Helper](#network-helper "Network Helper")
  - [networkResponse Helper](#network-response-helper "networkResponse Helper")
  - [NyResponse](#ny-response "NyResponse")
  - [Tùy chọn cơ bản](#base-options "Tùy chọn cơ bản")
  - [Thêm Headers](#adding-headers "Thêm Headers")
- Thao tác tệp
  - [Tải tệp lên](#uploading-files "Tải tệp lên")
  - [Tải tệp xuống](#downloading-files "Tải tệp xuống")
- [Interceptors](#interceptors "Interceptors")
  - [Network Logger](#network-logger "Network Logger")
- [Sử dụng API Service](#using-an-api-service "Sử dụng API Service")
- [Tạo API Service](#create-an-api-service "Tạo API Service")
- [Chuyển đổi JSON thành Model](#morphing-json-payloads-to-models "Chuyển đổi JSON thành Model")
- Bộ nhớ đệm
  - [Bộ nhớ đệm phản hồi](#caching-responses "Bộ nhớ đệm phản hồi")
  - [Chính sách bộ nhớ đệm](#cache-policies "Chính sách bộ nhớ đệm")
- Xử lý lỗi
  - [Thử lại yêu cầu thất bại](#retrying-failed-requests "Thử lại yêu cầu thất bại")
  - [Kiểm tra kết nối](#connectivity-checks "Kiểm tra kết nối")
  - [Cancel Tokens](#cancel-tokens "Cancel Tokens")
- Xác thực
  - [Thiết lập Auth Headers](#setting-auth-headers "Thiết lập Auth Headers")
  - [Làm mới Token](#refreshing-tokens "Làm mới Token")
- [Singleton API Service](#singleton-api-service "Singleton API Service")
- [Cấu hình nâng cao](#advanced-configuration "Cấu hình nâng cao")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} giúp việc kết nối mạng trở nên đơn giản. Bạn định nghĩa các endpoint API trong các class dịch vụ kế thừa từ `NyApiService`, sau đó gọi chúng từ các trang của bạn. Framework xử lý việc giải mã JSON, xử lý lỗi, bộ nhớ đệm và tự động chuyển đổi phản hồi thành các class model của bạn (gọi là "morphing").

Các API service của bạn nằm trong `lib/app/networking/`. Một dự án mới bao gồm một `ApiService` mặc định:

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

Có ba cách để thực hiện yêu cầu HTTP:

| Cách tiếp cận | Trả về | Phù hợp cho |
|----------|---------|----------|
| Phương thức tiện ích (`get`, `post`, v.v.) | `T?` | Các thao tác CRUD đơn giản |
| `network()` | `T?` | Yêu cầu cần bộ nhớ đệm, thử lại hoặc headers tùy chỉnh |
| `networkResponse()` | `NyResponse<T>` | Khi bạn cần mã trạng thái, headers hoặc chi tiết lỗi |

Bên dưới, {{ config('app.name') }} sử dụng <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>, một HTTP client mạnh mẽ.


<div id="convenience-methods"></div>

## Các phương thức tiện ích

`NyApiService` cung cấp các phương thức tắt cho các thao tác HTTP phổ biến. Chúng gọi `network()` bên trong.

### Yêu cầu GET

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### Yêu cầu POST

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### Yêu cầu PUT

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### Yêu cầu DELETE

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### Yêu cầu PATCH

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### Yêu cầu HEAD

Sử dụng HEAD để kiểm tra sự tồn tại của tài nguyên hoặc lấy headers mà không tải nội dung:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Network Helper

Phương thức `network` cung cấp cho bạn nhiều quyền kiểm soát hơn so với các phương thức tiện ích. Nó trả về dữ liệu đã được chuyển đổi (`T?`) trực tiếp.

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

Callback `request` nhận một thể hiện <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> với base URL và interceptors đã được cấu hình sẵn.

### Tham số network

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `request` | `Function(Dio)` | Yêu cầu HTTP cần thực hiện (bắt buộc) |
| `bearerToken` | `String?` | Bearer token cho yêu cầu này |
| `baseUrl` | `String?` | Ghi đè base URL của dịch vụ |
| `headers` | `Map<String, dynamic>?` | Headers bổ sung |
| `retry` | `int?` | Số lần thử lại |
| `retryDelay` | `Duration?` | Khoảng cách giữa các lần thử lại |
| `retryIf` | `bool Function(DioException)?` | Điều kiện để thử lại |
| `connectionTimeout` | `Duration?` | Thời gian chờ kết nối |
| `receiveTimeout` | `Duration?` | Thời gian chờ nhận |
| `sendTimeout` | `Duration?` | Thời gian chờ gửi |
| `cacheKey` | `String?` | Khóa bộ nhớ đệm |
| `cacheDuration` | `Duration?` | Thời lượng bộ nhớ đệm |
| `cachePolicy` | `CachePolicy?` | Chiến lược bộ nhớ đệm |
| `checkConnectivity` | `bool?` | Kiểm tra kết nối trước khi gửi yêu cầu |
| `handleSuccess` | `Function(NyResponse<T>)?` | Callback khi thành công |
| `handleFailure` | `Function(NyResponse<T>)?` | Callback khi thất bại |


<div id="network-response-helper"></div>

## networkResponse Helper

Sử dụng `networkResponse` khi bạn cần truy cập toàn bộ phản hồi -- mã trạng thái, headers, thông báo lỗi -- không chỉ dữ liệu. Nó trả về `NyResponse<T>` thay vì `T?`.

Sử dụng `networkResponse` khi bạn cần:
- Kiểm tra mã trạng thái HTTP cho xử lý cụ thể
- Truy cập headers phản hồi
- Lấy thông báo lỗi chi tiết cho phản hồi người dùng
- Triển khai logic xử lý lỗi tùy chỉnh

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

Sau đó sử dụng phản hồi trong trang của bạn:

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
// network() — trả về dữ liệu trực tiếp
User? user = await network<User>(
  request: (request) => request.get("/users/1"),
);

// networkResponse() — trả về toàn bộ phản hồi
NyResponse<User> response = await networkResponse<User>(
  request: (request) => request.get("/users/1"),
);
User? user = response.data;
int? status = response.statusCode;
```

Cả hai phương thức đều chấp nhận cùng các tham số. Chọn `networkResponse` khi bạn cần kiểm tra phản hồi ngoài dữ liệu.


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` bọc phản hồi Dio với dữ liệu đã chuyển đổi và các helper trạng thái.

### Thuộc tính

| Thuộc tính | Kiểu | Mô tả |
|----------|------|-------------|
| `response` | `Response?` | Phản hồi Dio gốc |
| `data` | `T?` | Dữ liệu đã chuyển đổi/giải mã |
| `rawData` | `dynamic` | Dữ liệu phản hồi thô |
| `headers` | `Headers?` | Headers phản hồi |
| `statusCode` | `int?` | Mã trạng thái HTTP |
| `statusMessage` | `String?` | Thông báo trạng thái HTTP |
| `contentType` | `String?` | Loại nội dung từ headers |
| `errorMessage` | `String?` | Thông báo lỗi được trích xuất |

### Kiểm tra trạng thái

| Getter | Mô tả |
|--------|-------------|
| `isSuccessful` | Trạng thái 200-299 |
| `isClientError` | Trạng thái 400-499 |
| `isServerError` | Trạng thái 500-599 |
| `isRedirect` | Trạng thái 300-399 |
| `hasData` | Dữ liệu không null |
| `isUnauthorized` | Trạng thái 401 |
| `isForbidden` | Trạng thái 403 |
| `isNotFound` | Trạng thái 404 |
| `isTimeout` | Trạng thái 408 |
| `isConflict` | Trạng thái 409 |
| `isRateLimited` | Trạng thái 429 |

### Các helper dữ liệu

```dart
NyResponse<User> response = await apiService.fetchUser(1);

// Lấy dữ liệu hoặc ném lỗi nếu null
User user = response.dataOrThrow('User not found');

// Lấy dữ liệu hoặc dùng giá trị dự phòng
User user = response.dataOr(User.guest());

// Chạy callback chỉ khi thành công
String? greeting = response.ifSuccessful((user) => 'Hello ${user.name}');

// Khớp mẫu theo thành công/thất bại
String result = response.when(
  success: (user) => 'Welcome, ${user.name}!',
  failure: (response) => 'Error: ${response.statusMessage}',
);

// Lấy một header cụ thể
String? authHeader = response.getHeader('Authorization');
```


<div id="base-options"></div>

## Tùy chọn cơ bản

Cấu hình các tùy chọn Dio mặc định cho API service của bạn bằng tham số `baseOptions`:

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

Bạn cũng có thể cấu hình các tùy chọn động trên một thể hiện:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

Nhấn <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">vào đây</a> để xem tất cả các tùy chọn cơ bản bạn có thể thiết lập.


<div id="adding-headers"></div>

## Thêm Headers

### Headers theo yêu cầu

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

### Headers cấp dịch vụ

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### Extension RequestHeaders

Kiểu `RequestHeaders` (một typedef `Map<String, dynamic>`) cung cấp các phương thức helper:

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

| Phương thức | Mô tả |
|--------|-------------|
| `addBearerToken(token)` | Thiết lập header `Authorization: Bearer` |
| `getBearerToken()` | Đọc bearer token từ headers |
| `addHeader(key, value)` | Thêm header tùy chỉnh |
| `hasHeader(key)` | Kiểm tra header có tồn tại không |


<div id="uploading-files"></div>

## Tải tệp lên

### Tải lên một tệp

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

### Tải lên nhiều tệp

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

## Tải tệp xuống

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

Interceptors cho phép bạn chỉnh sửa yêu cầu trước khi gửi, xử lý phản hồi và quản lý lỗi. Chúng chạy trên mỗi yêu cầu được thực hiện thông qua API service.

Sử dụng interceptors khi bạn cần:
- Thêm headers xác thực vào tất cả các yêu cầu
- Ghi log yêu cầu và phản hồi để gỡ lỗi
- Chuyển đổi dữ liệu yêu cầu/phản hồi toàn cục
- Xử lý mã lỗi cụ thể (ví dụ: làm mới token khi nhận 401)

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

### Tạo Interceptor tùy chỉnh

```bash
metro make:interceptor logging
```

**Tệp:** `app/networking/dio/interceptors/logging_interceptor.dart`

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

{{ config('app.name') }} bao gồm một interceptor `NetworkLogger` tích hợp sẵn. Nó được bật mặc định khi `APP_DEBUG` là `true` trong môi trường của bạn.

### Cấu hình

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

Bạn có thể tắt nó bằng cách thiết lập `useNetworkLogger: false`.

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- Tắt logger
        );
```

### Các cấp độ log

| Cấp độ | Mô tả |
|-------|-------------|
| `LogLevelType.verbose` | In tất cả chi tiết yêu cầu/phản hồi |
| `LogLevelType.minimal` | Chỉ in phương thức, URL, trạng thái và thời gian |
| `LogLevelType.none` | Không có đầu ra log |

### Lọc log

```dart
NetworkLogger(
  filter: (options, args) {
    // Chỉ log các yêu cầu đến endpoint cụ thể
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## Sử dụng API Service

Có hai cách để gọi API service từ một trang.

### Khởi tạo trực tiếp

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

### Sử dụng helper api()

Helper `api` tạo các thể hiện sử dụng `apiDecoders` từ `config/decoders.dart`:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

Với callbacks:

```dart
await api<ApiService>(
  (request) => request.fetchUser(),
  onSuccess: (response, data) {
    // data là thể hiện User? đã được chuyển đổi
  },
  onError: (DioException dioException) {
    // Xử lý lỗi
  },
);
```

### Tham số helper api()

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `request` | `Function(T)` | Hàm yêu cầu API |
| `context` | `BuildContext?` | Build context |
| `headers` | `Map<String, dynamic>` | Headers bổ sung |
| `bearerToken` | `String?` | Bearer token |
| `baseUrl` | `String?` | Ghi đè base URL |
| `page` | `int?` | Trang phân trang |
| `perPage` | `int?` | Số mục mỗi trang |
| `retry` | `int` | Số lần thử lại |
| `retryDelay` | `Duration?` | Khoảng cách giữa các lần thử lại |
| `onSuccess` | `Function(Response, dynamic)?` | Callback khi thành công |
| `onError` | `Function(DioException)?` | Callback khi lỗi |
| `cacheKey` | `String?` | Khóa bộ nhớ đệm |
| `cacheDuration` | `Duration?` | Thời lượng bộ nhớ đệm |


<div id="create-an-api-service"></div>

## Tạo API Service

Để tạo một API service mới:

```bash
metro make:api_service user
```

Với một model:

```bash
metro make:api_service user --model="User"
```

Lệnh này tạo một API service với các phương thức CRUD:

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

## Chuyển đổi JSON thành Model

"Morphing" là thuật ngữ của {{ config('app.name') }} để chỉ việc tự động chuyển đổi phản hồi JSON thành các class model Dart. Khi bạn sử dụng `network<User>(...)`, JSON phản hồi được đưa qua decoder để tạo một thể hiện `User` -- không cần phân tích thủ công.

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  // Trả về một User
  Future<User?> fetchUser() async {
    return await network<User>(
      request: (request) => request.get("/user/1"),
    );
  }

  // Trả về một danh sách User
  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }
}
```

Các decoders được định nghĩa trong `lib/bootstrap/decoders.dart`:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

Tham số kiểu bạn truyền vào `network<T>()` được khớp với map `modelDecoders` để tìm decoder phù hợp.

**Xem thêm:** [Decoders](/docs/{{$version}}/decoders#model-decoders) để biết chi tiết về việc đăng ký model decoders.


<div id="caching-responses"></div>

## Bộ nhớ đệm phản hồi

Bộ nhớ đệm phản hồi giúp giảm số lượng lệnh gọi API và cải thiện hiệu suất. Bộ nhớ đệm hữu ích cho dữ liệu không thay đổi thường xuyên, như danh sách quốc gia, danh mục hoặc cấu hình.

Cung cấp một `cacheKey` và tùy chọn `cacheDuration`:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### Xóa bộ nhớ đệm

```dart
// Xóa một khóa bộ nhớ đệm cụ thể
await apiService.clearCache("app_countries");

// Xóa toàn bộ bộ nhớ đệm API
await apiService.clearAllCache();
```

### Bộ nhớ đệm với helper api()

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## Chính sách bộ nhớ đệm

Sử dụng `CachePolicy` để kiểm soát chi tiết hành vi bộ nhớ đệm:

| Chính sách | Mô tả |
|--------|-------------|
| `CachePolicy.networkOnly` | Luôn lấy từ mạng (mặc định) |
| `CachePolicy.cacheFirst` | Thử bộ nhớ đệm trước, dự phòng mạng |
| `CachePolicy.networkFirst` | Thử mạng trước, dự phòng bộ nhớ đệm |
| `CachePolicy.cacheOnly` | Chỉ dùng bộ nhớ đệm, lỗi nếu trống |
| `CachePolicy.staleWhileRevalidate` | Trả về bộ nhớ đệm ngay, cập nhật ở nền |

### Cách sử dụng

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

### Khi nào sử dụng mỗi chính sách

- **cacheFirst** -- Dữ liệu hiếm khi thay đổi. Trả về dữ liệu đệm ngay, chỉ lấy từ mạng khi bộ nhớ đệm trống.
- **networkFirst** -- Dữ liệu cần mới nhất khi có thể. Thử mạng trước, dự phòng bộ nhớ đệm khi thất bại.
- **staleWhileRevalidate** -- Giao diện cần phản hồi ngay nhưng vẫn cần cập nhật. Trả về dữ liệu đệm, sau đó làm mới ở nền.
- **cacheOnly** -- Chế độ ngoại tuyến. Ném lỗi nếu không có dữ liệu đệm.

> **Lưu ý:** Nếu bạn cung cấp `cacheKey` hoặc `cacheDuration` mà không chỉ định `cachePolicy`, chính sách mặc định là `cacheFirst`.


<div id="retrying-failed-requests"></div>

## Thử lại yêu cầu thất bại

Tự động thử lại các yêu cầu thất bại.

### Thử lại cơ bản

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### Thử lại với độ trễ

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### Thử lại có điều kiện

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryIf: (DioException dioException) {
      // Chỉ thử lại khi lỗi server
      return dioException.response?.statusCode == 500;
    },
  );
}
```

### Thử lại cấp dịch vụ

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## Kiểm tra kết nối

Thất bại nhanh khi thiết bị ngoại tuyến thay vì chờ hết thời gian chờ.

### Cấp dịch vụ

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### Theo yêu cầu

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### Động

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

Khi được bật và thiết bị ngoại tuyến:
- Chính sách `networkFirst` dự phòng bộ nhớ đệm
- Các chính sách khác ném `DioExceptionType.connectionError` ngay lập tức


<div id="cancel-tokens"></div>

## Cancel Tokens

Quản lý và hủy các yêu cầu đang chờ.

```dart
// Tạo cancel token được quản lý
final token = apiService.createCancelToken();
await apiService.get('/endpoint', cancelToken: token);

// Hủy tất cả yêu cầu đang chờ (ví dụ: khi đăng xuất)
apiService.cancelAllRequests('User logged out');

// Kiểm tra số lượng yêu cầu đang hoạt động
int count = apiService.activeRequestCount;

// Dọn dẹp token cụ thể khi hoàn thành
apiService.removeCancelToken(token);
```


<div id="setting-auth-headers"></div>

## Thiết lập Auth Headers

Ghi đè `setAuthHeaders` để đính kèm headers xác thực vào mọi yêu cầu. Phương thức này được gọi trước mỗi yêu cầu khi `shouldSetAuthHeaders` là `true` (mặc định).

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

### Tắt Auth Headers

Cho các endpoint công khai không cần xác thực:

```dart
// Theo yêu cầu
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// Cấp dịch vụ
apiService.setShouldSetAuthHeaders(false);
```

**Xem thêm:** [Authentication](/docs/{{ $version }}/authentication) để biết chi tiết về xác thực người dùng và lưu trữ token.


<div id="refreshing-tokens"></div>

## Làm mới Token

Ghi đè `shouldRefreshToken` và `refreshToken` để xử lý hết hạn token. Chúng được gọi trước mỗi yêu cầu.

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<bool> shouldRefreshToken() async {
    // Kiểm tra xem token có cần làm mới không
    return false;
  }

  @override
  Future<void> refreshToken(Dio dio) async {
    // Sử dụng thể hiện Dio mới (không có interceptors) để làm mới token
    dynamic response = (await dio.post("https://example.com/refresh-token")).data;

    // Lưu token mới vào bộ nhớ
    await Auth.set((data) {
      data['token'] = response['token'];
      return data;
    });
  }
}
```

Tham số `dio` trong `refreshToken` là một thể hiện Dio mới, tách biệt với thể hiện chính của dịch vụ, để tránh vòng lặp interceptor.


<div id="singleton-api-service"></div>

## Singleton API Service

Mặc định, helper `api` tạo một thể hiện mới mỗi lần. Để sử dụng singleton, truyền một thể hiện thay vì một factory trong `config/decoders.dart`:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // Thể hiện mới mỗi lần

  ApiService: ApiService(), // Singleton — luôn cùng một thể hiện
};
```


<div id="advanced-configuration"></div>

## Cấu hình nâng cao

### Khởi tạo Dio tùy chỉnh

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

### Truy cập thể hiện Dio

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### Helper phân trang

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### Callback sự kiện

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### Thuộc tính có thể ghi đè

| Thuộc tính | Kiểu | Mặc định | Mô tả |
|----------|------|---------|-------------|
| `baseUrl` | `String` | `""` | Base URL cho tất cả yêu cầu |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Các interceptor Dio |
| `decoders` | `Map<Type, dynamic>?` | `{}` | Model decoders cho chuyển đổi JSON |
| `shouldSetAuthHeaders` | `bool` | `true` | Có gọi `setAuthHeaders` trước yêu cầu không |
| `retry` | `int` | `0` | Số lần thử lại mặc định |
| `retryDelay` | `Duration` | `1 giây` | Khoảng cách mặc định giữa các lần thử lại |
| `checkConnectivityBeforeRequest` | `bool` | `false` | Kiểm tra kết nối trước yêu cầu |
