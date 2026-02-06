# 网络

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- 发起 HTTP 请求
  - [便捷方法](#convenience-methods "便捷方法")
  - [Network 辅助方法](#network-helper "Network 辅助方法")
  - [networkResponse 辅助方法](#network-response-helper "networkResponse 辅助方法")
  - [NyResponse](#ny-response "NyResponse")
  - [基本选项](#base-options "基本选项")
  - [添加请求头](#adding-headers "添加请求头")
- 文件操作
  - [上传文件](#uploading-files "上传文件")
  - [下载文件](#downloading-files "下载文件")
- [拦截器](#interceptors "拦截器")
  - [网络日志记录器](#network-logger "网络日志记录器")
- [使用 API 服务](#using-an-api-service "使用 API 服务")
- [创建 API 服务](#create-an-api-service "创建 API 服务")
- [将 JSON 转换为模型](#morphing-json-payloads-to-models "将 JSON 转换为模型")
- 缓存
  - [缓存响应](#caching-responses "缓存响应")
  - [缓存策略](#cache-policies "缓存策略")
- 错误处理
  - [重试失败的请求](#retrying-failed-requests "重试失败的请求")
  - [连接检查](#connectivity-checks "连接检查")
  - [取消令牌](#cancel-tokens "取消令牌")
- 认证
  - [设置认证请求头](#setting-auth-headers "设置认证请求头")
  - [刷新令牌](#refreshing-tokens "刷新令牌")
- [单例 API 服务](#singleton-api-service "单例 API 服务")
- [高级配置](#advanced-configuration "高级配置")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} 使网络请求变得简单。您在继承 `NyApiService` 的服务类中定义 API 端点，然后从页面中调用它们。框架负责处理 JSON 解码、错误处理、缓存以及将响应自动转换为模型类（称为"morphing"）。

您的 API 服务位于 `lib/app/networking/`。新项目包含一个默认的 `ApiService`：

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

有三种方式发起 HTTP 请求：

| 方式 | 返回值 | 最适合 |
|----------|---------|----------|
| 便捷方法（`get`、`post` 等） | `T?` | 简单的 CRUD 操作 |
| `network()` | `T?` | 需要缓存、重试或自定义请求头的请求 |
| `networkResponse()` | `NyResponse<T>` | 需要状态码、请求头或错误详情时 |

底层 {{ config('app.name') }} 使用 <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>，一个强大的 HTTP 客户端。


<div id="convenience-methods"></div>

## 便捷方法

`NyApiService` 为常见的 HTTP 操作提供了简写方法。这些方法内部调用 `network()`。

### GET 请求

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### POST 请求

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### PUT 请求

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### DELETE 请求

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### PATCH 请求

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### HEAD 请求

使用 HEAD 检查资源是否存在或在不下载正文的情况下获取请求头：

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Network 辅助方法

`network` 方法比便捷方法提供更多控制。它直接返回转换后的数据（`T?`）。

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

`request` 回调接收一个已配置好基础 URL 和拦截器的 <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> 实例。

### network 参数

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `request` | `Function(Dio)` | 要执行的 HTTP 请求（必需） |
| `bearerToken` | `String?` | 此请求的 Bearer 令牌 |
| `baseUrl` | `String?` | 覆盖服务基础 URL |
| `headers` | `Map<String, dynamic>?` | 附加请求头 |
| `retry` | `int?` | 重试次数 |
| `retryDelay` | `Duration?` | 重试之间的延迟 |
| `retryIf` | `bool Function(DioException)?` | 重试条件 |
| `connectionTimeout` | `Duration?` | 连接超时 |
| `receiveTimeout` | `Duration?` | 接收超时 |
| `sendTimeout` | `Duration?` | 发送超时 |
| `cacheKey` | `String?` | 缓存键 |
| `cacheDuration` | `Duration?` | 缓存时长 |
| `cachePolicy` | `CachePolicy?` | 缓存策略 |
| `checkConnectivity` | `bool?` | 请求前检查连接 |
| `handleSuccess` | `Function(NyResponse<T>)?` | 成功回调 |
| `handleFailure` | `Function(NyResponse<T>)?` | 失败回调 |


<div id="network-response-helper"></div>

## networkResponse 辅助方法

当您需要访问完整响应（状态码、请求头、错误消息），而不仅仅是数据时，使用 `networkResponse`。它返回 `NyResponse<T>` 而不是 `T?`。

使用 `networkResponse` 的场景：
- 需要检查 HTTP 状态码进行特定处理
- 需要访问响应头
- 需要获取详细的错误消息用于用户反馈
- 需要实现自定义错误处理逻辑

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

然后在页面中使用响应：

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

### network 与 networkResponse 对比

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

两种方法接受相同的参数。当您需要检查数据以外的响应内容时，选择 `networkResponse`。


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` 使用转换后的数据和状态辅助方法包装 Dio 响应。

### 属性

| 属性 | 类型 | 描述 |
|----------|------|-------------|
| `response` | `Response?` | 原始 Dio Response |
| `data` | `T?` | 转换/解码后的数据 |
| `rawData` | `dynamic` | 原始响应数据 |
| `headers` | `Headers?` | 响应头 |
| `statusCode` | `int?` | HTTP 状态码 |
| `statusMessage` | `String?` | HTTP 状态消息 |
| `contentType` | `String?` | 请求头中的内容类型 |
| `errorMessage` | `String?` | 提取的错误消息 |

### 状态检查

| Getter | 描述 |
|--------|-------------|
| `isSuccessful` | 状态码 200-299 |
| `isClientError` | 状态码 400-499 |
| `isServerError` | 状态码 500-599 |
| `isRedirect` | 状态码 300-399 |
| `hasData` | 数据不为 null |
| `isUnauthorized` | 状态码 401 |
| `isForbidden` | 状态码 403 |
| `isNotFound` | 状态码 404 |
| `isTimeout` | 状态码 408 |
| `isConflict` | 状态码 409 |
| `isRateLimited` | 状态码 429 |

### 数据辅助方法

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

## 基本选项

使用 `baseOptions` 参数为 API 服务配置默认的 Dio 选项：

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

您也可以在实例上动态配置选项：

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

点击<a href="https://pub.dev/packages/dio#request-options" target="_BLANK">此处</a>查看所有可设置的基本选项。


<div id="adding-headers"></div>

## 添加请求头

### 每个请求的请求头

```dart
Future fetchWithHeaders() async => await network(
  request: (request) => request.get("/test"),
  headers: {
    "Authorization": "Bearer aToken123",
    "Device": "iPhone"
  }
);
```

### Bearer 令牌

```dart
Future fetchUser() async => await network(
  request: (request) => request.get("/user"),
  bearerToken: "hello-world-123",
);
```

### 服务级别请求头

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### RequestHeaders 扩展

`RequestHeaders` 类型（一个 `Map<String, dynamic>` 类型别名）提供了辅助方法：

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

| 方法 | 描述 |
|--------|-------------|
| `addBearerToken(token)` | 设置 `Authorization: Bearer` 请求头 |
| `getBearerToken()` | 从请求头中读取 bearer 令牌 |
| `addHeader(key, value)` | 添加自定义请求头 |
| `hasHeader(key)` | 检查请求头是否存在 |


<div id="uploading-files"></div>

## 上传文件

### 单文件上传

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

### 多文件上传

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

## 下载文件

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

## 拦截器

拦截器允许您在发送请求之前修改请求、处理响应和管理错误。它们在通过 API 服务发出的每个请求上运行。

使用拦截器的场景：
- 需要为所有请求添加认证请求头
- 需要记录请求和响应用于调试
- 需要全局转换请求/响应数据
- 需要处理特定错误码（例如，在 401 时刷新令牌）

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

### 创建自定义拦截器

```bash
metro make:interceptor logging
```

**文件：** `app/networking/dio/interceptors/logging_interceptor.dart`

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

## 网络日志记录器

{{ config('app.name') }} 包含内置的 `NetworkLogger` 拦截器。当环境中 `APP_DEBUG` 为 `true` 时默认启用。

### 配置

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

您可以通过设置 `useNetworkLogger: false` 来禁用它。

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- 禁用日志记录器
        );
```

### 日志级别

| 级别 | 描述 |
|-------|-------------|
| `LogLevelType.verbose` | 打印所有请求/响应详情 |
| `LogLevelType.minimal` | 仅打印方法、URL、状态和时间 |
| `LogLevelType.none` | 无日志输出 |

### 过滤日志

```dart
NetworkLogger(
  filter: (options, args) {
    // Only log requests to specific endpoints
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## 使用 API 服务

有两种方式从页面调用 API 服务。

### 直接实例化

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

### 使用 api() 辅助方法

`api` 辅助方法使用 `config/decoders.dart` 中的 `apiDecoders` 创建实例：

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

带回调：

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

### api() 辅助方法参数

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `request` | `Function(T)` | API 请求函数 |
| `context` | `BuildContext?` | 构建上下文 |
| `headers` | `Map<String, dynamic>` | 附加请求头 |
| `bearerToken` | `String?` | Bearer 令牌 |
| `baseUrl` | `String?` | 覆盖基础 URL |
| `page` | `int?` | 分页页码 |
| `perPage` | `int?` | 每页项目数 |
| `retry` | `int` | 重试次数 |
| `retryDelay` | `Duration?` | 重试之间的延迟 |
| `onSuccess` | `Function(Response, dynamic)?` | 成功回调 |
| `onError` | `Function(DioException)?` | 错误回调 |
| `cacheKey` | `String?` | 缓存键 |
| `cacheDuration` | `Duration?` | 缓存时长 |


<div id="create-an-api-service"></div>

## 创建 API 服务

要创建新的 API 服务：

```bash
metro make:api_service user
```

带模型：

```bash
metro make:api_service user --model="User"
```

这将创建一个带有 CRUD 方法的 API 服务：

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

## 将 JSON 转换为模型

"Morphing" 是 {{ config('app.name') }} 用于自动将 JSON 响应转换为 Dart 模型类的术语。当您使用 `network<User>(...)` 时，响应 JSON 会通过解码器创建 `User` 实例——无需手动解析。

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

解码器在 `lib/bootstrap/decoders.dart` 中定义：

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

您传递给 `network<T>()` 的类型参数会与 `modelDecoders` 映射匹配以找到正确的解码器。

**另请参阅：** [解码器](/docs/{{$version}}/decoders#model-decoders) 了解注册模型解码器的详细信息。


<div id="caching-responses"></div>

## 缓存响应

缓存响应以减少 API 调用并提高性能。缓存适用于不经常变化的数据，如国家列表、分类或配置。

提供 `cacheKey` 和可选的 `cacheDuration`：

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### 清除缓存

```dart
// Clear a specific cache key
await apiService.clearCache("app_countries");

// Clear all API cache
await apiService.clearAllCache();
```

### 使用 api() 辅助方法进行缓存

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## 缓存策略

使用 `CachePolicy` 对缓存行为进行细粒度控制：

| 策略 | 描述 |
|--------|-------------|
| `CachePolicy.networkOnly` | 始终从网络获取（默认） |
| `CachePolicy.cacheFirst` | 先尝试缓存，回退到网络 |
| `CachePolicy.networkFirst` | 先尝试网络，回退到缓存 |
| `CachePolicy.cacheOnly` | 仅使用缓存，为空则报错 |
| `CachePolicy.staleWhileRevalidate` | 立即返回缓存，后台更新 |

### 用法

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

### 何时使用每种策略

- **cacheFirst** -- 很少变化的数据。立即返回缓存数据，仅在缓存为空时从网络获取。
- **networkFirst** -- 应尽可能保持最新的数据。先尝试网络，失败时回退到缓存。
- **staleWhileRevalidate** -- 需要即时响应但应保持更新的 UI。返回缓存数据，然后在后台刷新。
- **cacheOnly** -- 离线模式。如果没有缓存数据则抛出错误。

> **注意：** 如果您提供了 `cacheKey` 或 `cacheDuration` 但未指定 `cachePolicy`，默认策略为 `cacheFirst`。


<div id="retrying-failed-requests"></div>

## 重试失败的请求

自动重试失败的请求。

### 基本重试

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### 带延迟的重试

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### 条件重试

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

### 服务级别重试

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## 连接检查

在设备离线时快速失败，而不是等待超时。

### 服务级别

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### 每个请求

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### 动态设置

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

启用后，当设备离线时：
- `networkFirst` 策略回退到缓存
- 其他策略立即抛出 `DioExceptionType.connectionError`


<div id="cancel-tokens"></div>

## 取消令牌

管理和取消待处理的请求。

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

## 设置认证请求头

重写 `setAuthHeaders` 以在每个请求上附加认证请求头。当 `shouldSetAuthHeaders` 为 `true`（默认值）时，此方法在每个请求之前调用。

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

### 禁用认证请求头

对于不需要认证的公共端点：

```dart
// Per-request
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// Service-level
apiService.setShouldSetAuthHeaders(false);
```

**另请参阅：** [认证](/docs/{{ $version }}/authentication) 了解用户认证和令牌存储的详细信息。


<div id="refreshing-tokens"></div>

## 刷新令牌

重写 `shouldRefreshToken` 和 `refreshToken` 以处理令牌过期。这些方法在每个请求之前调用。

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

`refreshToken` 中的 `dio` 参数是一个新的 Dio 实例，与服务的主实例分离，以避免拦截器循环。


<div id="singleton-api-service"></div>

## 单例 API 服务

默认情况下，`api` 辅助方法每次都会创建新实例。要使用单例，在 `config/decoders.dart` 中传递实例而不是工厂：

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // New instance each time

  ApiService: ApiService(), // Singleton — same instance always
};
```


<div id="advanced-configuration"></div>

## 高级配置

### 自定义 Dio 初始化

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

### 访问 Dio 实例

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### 分页辅助方法

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### 事件回调

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### 可重写的属性

| 属性 | 类型 | 默认值 | 描述 |
|----------|------|---------|-------------|
| `baseUrl` | `String` | `""` | 所有请求的基础 URL |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Dio 拦截器 |
| `decoders` | `Map<Type, dynamic>?` | `{}` | JSON 转换的模型解码器 |
| `shouldSetAuthHeaders` | `bool` | `true` | 是否在请求前调用 `setAuthHeaders` |
| `retry` | `int` | `0` | 默认重试次数 |
| `retryDelay` | `Duration` | `1 second` | 默认重试延迟 |
| `checkConnectivityBeforeRequest` | `bool` | `false` | 请求前检查连接 |
