# Networking

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Making HTTP Requests
  - [Convenience Methods](#convenience-methods "Convenience Methods")
  - [Network Helper](#network-helper "Network Helper")
  - [networkResponse Helper](#network-response-helper "networkResponse Helper")
  - [NyResponse](#ny-response "NyResponse")
  - [Base Options](#base-options "Base Options")
  - [Adding Headers](#adding-headers "Adding Headers")
- File Operations
  - [Uploading Files](#uploading-files "Uploading Files")
  - [Downloading Files](#downloading-files "Downloading Files")
- [Interceptors](#interceptors "Interceptors")
  - [Network Logger](#network-logger "Network Logger")
- [Using an API Service](#using-an-api-service "Using an API Service")
- [Create an API Service](#create-an-api-service "Create an API Service")
- [Morphing JSON to Models](#morphing-json-payloads-to-models "Morphing JSON to Models")
- Caching
  - [Caching Responses](#caching-responses "Caching Responses")
  - [Cache Policies](#cache-policies "Cache Policies")
- Error Handling
  - [Retrying Failed Requests](#retrying-failed-requests "Retrying Failed Requests")
  - [Connectivity Checks](#connectivity-checks "Connectivity Checks")
  - [Cancel Tokens](#cancel-tokens "Cancel Tokens")
- Authentication
  - [Setting Auth Headers](#setting-auth-headers "Setting Auth Headers")
  - [Refreshing Tokens](#refreshing-tokens "Refreshing Tokens")
- [Singleton API Service](#singleton-api-service "Singleton API Service")
- [Advanced Configuration](#advanced-configuration "Advanced Configuration")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} makes networking simple. You define API endpoints in service classes that extend `NyApiService`, then call them from your pages. The framework handles JSON decoding, error handling, caching, and automatic conversion of responses to your model classes (called "morphing").

Your API services live in `lib/app/networking/`. A fresh project includes a default `ApiService`:

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

There are three ways to make HTTP requests:

| Approach | Returns | Best For |
|----------|---------|----------|
| Convenience methods (`get`, `post`, etc.) | `T?` | Simple CRUD operations |
| `network()` | `T?` | Requests needing caching, retries, or custom headers |
| `networkResponse()` | `NyResponse<T>` | When you need status codes, headers, or error details |

Under the hood, {{ config('app.name') }} uses <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>, a powerful HTTP client.


<div id="convenience-methods"></div>

## Convenience Methods

`NyApiService` provides shorthand methods for common HTTP operations. These call `network()` internally.

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

Use HEAD to check resource existence or get headers without downloading the body:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Network Helper

The `network` method gives you more control than the convenience methods. It returns the morphed data (`T?`) directly.

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

The `request` callback receives a <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> instance with your base URL and interceptors already configured.

### network Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `request` | `Function(Dio)` | The HTTP request to perform (required) |
| `bearerToken` | `String?` | Bearer token for this request |
| `baseUrl` | `String?` | Override the service base URL |
| `headers` | `Map<String, dynamic>?` | Additional headers |
| `retry` | `int?` | Number of retry attempts |
| `retryDelay` | `Duration?` | Delay between retries |
| `retryIf` | `bool Function(DioException)?` | Condition for retrying |
| `connectionTimeout` | `Duration?` | Connection timeout |
| `receiveTimeout` | `Duration?` | Receive timeout |
| `sendTimeout` | `Duration?` | Send timeout |
| `cacheKey` | `String?` | Cache key |
| `cacheDuration` | `Duration?` | Cache duration |
| `cachePolicy` | `CachePolicy?` | Cache strategy |
| `checkConnectivity` | `bool?` | Check connectivity before request |
| `handleSuccess` | `Function(NyResponse<T>)?` | Success callback |
| `handleFailure` | `Function(NyResponse<T>)?` | Failure callback |


<div id="network-response-helper"></div>

## networkResponse Helper

Use `networkResponse` when you need access to the full response — status codes, headers, error messages — not just the data. It returns an `NyResponse<T>` instead of `T?`.

Use `networkResponse` when you need to:
- Check HTTP status codes for specific handling
- Access response headers
- Get detailed error messages for user feedback
- Implement custom error handling logic

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

Then use the response in your page:

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

Both methods accept the same parameters. Choose `networkResponse` when you need to inspect the response beyond just the data.


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` wraps the Dio response with morphed data and status helpers.

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `response` | `Response?` | Original Dio Response |
| `data` | `T?` | Morphed/decoded data |
| `rawData` | `dynamic` | Raw response data |
| `headers` | `Headers?` | Response headers |
| `statusCode` | `int?` | HTTP status code |
| `statusMessage` | `String?` | HTTP status message |
| `contentType` | `String?` | Content type from headers |
| `errorMessage` | `String?` | Extracted error message |

### Status Checks

| Getter | Description |
|--------|-------------|
| `isSuccessful` | Status 200-299 |
| `isClientError` | Status 400-499 |
| `isServerError` | Status 500-599 |
| `isRedirect` | Status 300-399 |
| `hasData` | Data is not null |
| `isUnauthorized` | Status 401 |
| `isForbidden` | Status 403 |
| `isNotFound` | Status 404 |
| `isTimeout` | Status 408 |
| `isConflict` | Status 409 |
| `isRateLimited` | Status 429 |

### Data Helpers

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

## Base Options

Configure default Dio options for your API service using the `baseOptions` parameter:

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

You can also configure options dynamically on an instance:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

Click <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">here</a> to view all the base options you can set.


<div id="adding-headers"></div>

## Adding Headers

### Per-Request Headers

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

### Service-Level Headers

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### RequestHeaders Extension

The `RequestHeaders` type (a `Map<String, dynamic>` typedef) provides helper methods:

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

| Method | Description |
|--------|-------------|
| `addBearerToken(token)` | Set the `Authorization: Bearer` header |
| `getBearerToken()` | Read the bearer token from headers |
| `addHeader(key, value)` | Add a custom header |
| `hasHeader(key)` | Check if a header exists |


<div id="uploading-files"></div>

## Uploading Files

### Single File Upload

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

### Multiple File Upload

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

## Downloading Files

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

Interceptors let you modify requests before they're sent, handle responses, and manage errors. They run on every request made through the API service.

Use interceptors when you need to:
- Add authentication headers to all requests
- Log requests and responses for debugging
- Transform request/response data globally
- Handle specific error codes (e.g., refresh tokens on 401)

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

### Creating a Custom Interceptor

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

{{ config('app.name') }} includes a built-in `NetworkLogger` interceptor. It is enabled by default when `APP_DEBUG` is `true` in your environment.

### Configuration

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

You can disable it by setting `useNetworkLogger: false`.

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- Disable logger
        );
```

### Log Levels

| Level | Description |
|-------|-------------|
| `LogLevelType.verbose` | Print all request/response details |
| `LogLevelType.minimal` | Print method, URL, status, and time only |
| `LogLevelType.none` | No logging output |

### Filtering Logs

```dart
NetworkLogger(
  filter: (options, args) {
    // Only log requests to specific endpoints
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## Using an API Service

There are two ways to call your API service from a page.

### Direct Instantiation

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

### Using the api() Helper

The `api` helper creates instances using your `apiDecoders` from `config/decoders.dart`:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

With callbacks:

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

### api() Helper Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `request` | `Function(T)` | The API request function |
| `context` | `BuildContext?` | Build context |
| `headers` | `Map<String, dynamic>` | Additional headers |
| `bearerToken` | `String?` | Bearer token |
| `baseUrl` | `String?` | Override base URL |
| `page` | `int?` | Pagination page |
| `perPage` | `int?` | Items per page |
| `retry` | `int` | Retry attempts |
| `retryDelay` | `Duration?` | Delay between retries |
| `onSuccess` | `Function(Response, dynamic)?` | Success callback |
| `onError` | `Function(DioException)?` | Error callback |
| `cacheKey` | `String?` | Cache key |
| `cacheDuration` | `Duration?` | Cache duration |


<div id="create-an-api-service"></div>

## Create an API Service

To create a new API service:

```bash
metro make:api_service user
```

With a model:

```bash
metro make:api_service user --model="User"
```

This creates an API service with CRUD methods:

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

## Morphing JSON to Models

"Morphing" is {{ config('app.name') }}'s term for automatically converting JSON responses into your Dart model classes. When you use `network<User>(...)`, the response JSON is passed through your decoder to create a `User` instance — no manual parsing needed.

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

The decoders are defined in `lib/bootstrap/decoders.dart`:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

The type parameter you pass to `network<T>()` is matched against your `modelDecoders` map to find the right decoder.

**See also:** [Decoders](/docs/{{$version}}/decoders#model-decoders) for details on registering model decoders.


<div id="caching-responses"></div>

## Caching Responses

Cache responses to reduce API calls and improve performance. Caching is useful for data that doesn't change frequently, like country lists, categories, or configuration.

Provide a `cacheKey` and optional `cacheDuration`:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### Clearing Cache

```dart
// Clear a specific cache key
await apiService.clearCache("app_countries");

// Clear all API cache
await apiService.clearAllCache();
```

### Caching with the api() Helper

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## Cache Policies

Use `CachePolicy` for fine-grained control over caching behavior:

| Policy | Description |
|--------|-------------|
| `CachePolicy.networkOnly` | Always fetch from network (default) |
| `CachePolicy.cacheFirst` | Try cache first, fallback to network |
| `CachePolicy.networkFirst` | Try network first, fallback to cache |
| `CachePolicy.cacheOnly` | Only use cache, error if empty |
| `CachePolicy.staleWhileRevalidate` | Return cache immediately, update in background |

### Usage

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

### When to Use Each Policy

- **cacheFirst** — Data that rarely changes. Returns cached data instantly, only fetches from network if cache is empty.
- **networkFirst** — Data that should be fresh when possible. Tries network first, falls back to cache on failure.
- **staleWhileRevalidate** — UI that needs an immediate response but should stay updated. Returns cached data, then refreshes in the background.
- **cacheOnly** — Offline mode. Throws an error if no cached data exists.

> **Note:** If you provide a `cacheKey` or `cacheDuration` without specifying a `cachePolicy`, the default policy is `cacheFirst`.


<div id="retrying-failed-requests"></div>

## Retrying Failed Requests

Automatically retry requests that fail.

### Basic Retry

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### Retry with Delay

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### Conditional Retry

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

### Service-Level Retry

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## Connectivity Checks

Fail fast when the device is offline instead of waiting for a timeout.

### Service-Level

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### Per-Request

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### Dynamic

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

When enabled and the device is offline:
- `networkFirst` policy falls back to cache
- Other policies throw `DioExceptionType.connectionError` immediately


<div id="cancel-tokens"></div>

## Cancel Tokens

Manage and cancel pending requests.

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

## Setting Auth Headers

Override `setAuthHeaders` to attach authentication headers to every request. This method is called before each request when `shouldSetAuthHeaders` is `true` (the default).

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

### Disabling Auth Headers

For public endpoints that don't need authentication:

```dart
// Per-request
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// Service-level
apiService.setShouldSetAuthHeaders(false);
```

**See also:** [Authentication](/docs/{{ $version }}/authentication) for details on authenticating users and storing tokens.


<div id="refreshing-tokens"></div>

## Refreshing Tokens

Override `shouldRefreshToken` and `refreshToken` to handle token expiry. These are called before every request.

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

The `dio` parameter in `refreshToken` is a new Dio instance, separate from the service's main instance, to avoid interceptor loops.


<div id="singleton-api-service"></div>

## Singleton API Service

By default, the `api` helper creates a new instance each time. To use a singleton, pass an instance instead of a factory in `config/decoders.dart`:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // New instance each time

  ApiService: ApiService(), // Singleton — same instance always
};
```


<div id="advanced-configuration"></div>

## Advanced Configuration

### Custom Dio Initialization

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

### Accessing the Dio Instance

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### Pagination Helper

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

### Overridable Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `baseUrl` | `String` | `""` | Base URL for all requests |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Dio interceptors |
| `decoders` | `Map<Type, dynamic>?` | `{}` | Model decoders for JSON morphing |
| `shouldSetAuthHeaders` | `bool` | `true` | Whether to call `setAuthHeaders` before requests |
| `retry` | `int` | `0` | Default retry attempts |
| `retryDelay` | `Duration` | `1 second` | Default delay between retries |
| `checkConnectivityBeforeRequest` | `bool` | `false` | Check connectivity before requests |
