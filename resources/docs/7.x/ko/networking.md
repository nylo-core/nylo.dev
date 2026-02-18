# 네트워킹

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- HTTP 요청 만들기
  - [편의 메서드](#convenience-methods "편의 메서드")
  - [Network 헬퍼](#network-helper "Network 헬퍼")
  - [networkResponse 헬퍼](#network-response-helper "networkResponse 헬퍼")
  - [NyResponse](#ny-response "NyResponse")
  - [Base Options](#base-options "Base Options")
  - [헤더 추가](#adding-headers "헤더 추가")
- 파일 작업
  - [파일 업로드](#uploading-files "파일 업로드")
  - [파일 다운로드](#downloading-files "파일 다운로드")
- [Interceptor](#interceptors "Interceptor")
  - [Network Logger](#network-logger "Network Logger")
- [API Service 사용](#using-an-api-service "API Service 사용")
- [API Service 생성](#create-an-api-service "API Service 생성")
- [JSON을 Model로 변환](#morphing-json-payloads-to-models "JSON을 Model로 변환")
- 캐싱
  - [응답 캐싱](#caching-responses "응답 캐싱")
  - [캐시 정책](#cache-policies "캐시 정책")
- 오류 처리
  - [실패한 요청 재시도](#retrying-failed-requests "실패한 요청 재시도")
  - [연결 상태 확인](#connectivity-checks "연결 상태 확인")
  - [Cancel Token](#cancel-tokens "Cancel Token")
- 인증
  - [인증 헤더 설정](#setting-auth-headers "인증 헤더 설정")
  - [토큰 갱신](#refreshing-tokens "토큰 갱신")
- [Singleton API Service](#singleton-api-service "Singleton API Service")
- [고급 설정](#advanced-configuration "고급 설정")

<div id="introduction"></div>

## 소개

{{ config('app.name') }}는 네트워킹을 간단하게 만들어줍니다. `NyApiService`를 확장하는 Service 클래스에 API 엔드포인트를 정의한 다음 페이지에서 호출하면 됩니다. 프레임워크가 JSON 디코딩, 오류 처리, 캐싱, 응답을 Model 클래스로 자동 변환("모핑"이라 합니다)하는 작업을 처리합니다.

API Service는 `lib/app/networking/`에 위치합니다. 새 프로젝트에는 기본 `ApiService`가 포함되어 있습니다:

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

HTTP 요청을 만드는 세 가지 방법이 있습니다:

| 접근 방식 | 반환 값 | 적합한 용도 |
|----------|---------|----------|
| 편의 메서드 (`get`, `post` 등) | `T?` | 간단한 CRUD 작업 |
| `network()` | `T?` | 캐싱, 재시도 또는 커스텀 헤더가 필요한 요청 |
| `networkResponse()` | `NyResponse<T>` | 상태 코드, 헤더 또는 오류 세부 정보가 필요한 경우 |

내부적으로 {{ config('app.name') }}는 강력한 HTTP 클라이언트인 <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a>를 사용합니다.


<div id="convenience-methods"></div>

## 편의 메서드

`NyApiService`는 일반적인 HTTP 작업을 위한 축약 메서드를 제공합니다. 이 메서드들은 내부적으로 `network()`를 호출합니다.

### GET 요청

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### POST 요청

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### PUT 요청

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### DELETE 요청

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### PATCH 요청

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### HEAD 요청

HEAD를 사용하여 리소스 존재 여부를 확인하거나 본문을 다운로드하지 않고 헤더를 가져올 수 있습니다:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Network 헬퍼

`network` 메서드는 편의 메서드보다 더 세밀한 제어를 제공합니다. 모핑된 데이터(`T?`)를 직접 반환합니다.

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

`request` 콜백은 Base URL과 Interceptor가 이미 설정된 <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> 인스턴스를 받습니다.

### network 매개변수

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `request` | `Function(Dio)` | 수행할 HTTP 요청 (필수) |
| `bearerToken` | `String?` | 이 요청의 Bearer 토큰 |
| `baseUrl` | `String?` | Service Base URL 재정의 |
| `headers` | `Map<String, dynamic>?` | 추가 헤더 |
| `retry` | `int?` | 재시도 횟수 |
| `retryDelay` | `Duration?` | 재시도 간 지연 시간 |
| `retryIf` | `bool Function(DioException)?` | 재시도 조건 |
| `connectionTimeout` | `Duration?` | 연결 타임아웃 |
| `receiveTimeout` | `Duration?` | 수신 타임아웃 |
| `sendTimeout` | `Duration?` | 전송 타임아웃 |
| `cacheKey` | `String?` | 캐시 키 |
| `cacheDuration` | `Duration?` | 캐시 지속 시간 |
| `cachePolicy` | `CachePolicy?` | 캐시 전략 |
| `checkConnectivity` | `bool?` | 요청 전 연결 상태 확인 |
| `handleSuccess` | `Function(NyResponse<T>)?` | 성공 콜백 |
| `handleFailure` | `Function(NyResponse<T>)?` | 실패 콜백 |


<div id="network-response-helper"></div>

## networkResponse 헬퍼

데이터뿐만 아니라 전체 응답(상태 코드, 헤더, 오류 메시지)에 접근해야 할 때 `networkResponse`를 사용합니다. `T?` 대신 `NyResponse<T>`를 반환합니다.

`networkResponse`는 다음과 같은 경우에 사용합니다:
- 특정 처리를 위해 HTTP 상태 코드를 확인해야 할 때
- 응답 헤더에 접근해야 할 때
- 사용자 피드백을 위한 상세 오류 메시지가 필요할 때
- 커스텀 오류 처리 로직을 구현해야 할 때

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

그런 다음 페이지에서 응답을 사용합니다:

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
// network() — 데이터를 직접 반환
User? user = await network<User>(
  request: (request) => request.get("/users/1"),
);

// networkResponse() — 전체 응답을 반환
NyResponse<User> response = await networkResponse<User>(
  request: (request) => request.get("/users/1"),
);
User? user = response.data;
int? status = response.statusCode;
```

두 메서드 모두 동일한 매개변수를 받습니다. 데이터 이외의 응답 정보를 검사해야 할 때 `networkResponse`를 선택하세요.


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>`는 Dio 응답을 모핑된 데이터 및 상태 헬퍼와 함께 래핑합니다.

### 속성

| 속성 | 타입 | 설명 |
|----------|------|-------------|
| `response` | `Response?` | 원본 Dio Response |
| `data` | `T?` | 모핑/디코딩된 데이터 |
| `rawData` | `dynamic` | 원시 응답 데이터 |
| `headers` | `Headers?` | 응답 헤더 |
| `statusCode` | `int?` | HTTP 상태 코드 |
| `statusMessage` | `String?` | HTTP 상태 메시지 |
| `contentType` | `String?` | 헤더의 Content Type |
| `errorMessage` | `String?` | 추출된 오류 메시지 |

### 상태 검사

| Getter | 설명 |
|--------|-------------|
| `isSuccessful` | 상태 200-299 |
| `isClientError` | 상태 400-499 |
| `isServerError` | 상태 500-599 |
| `isRedirect` | 상태 300-399 |
| `hasData` | 데이터가 null이 아님 |
| `isUnauthorized` | 상태 401 |
| `isForbidden` | 상태 403 |
| `isNotFound` | 상태 404 |
| `isTimeout` | 상태 408 |
| `isConflict` | 상태 409 |
| `isRateLimited` | 상태 429 |

### 데이터 헬퍼

```dart
NyResponse<User> response = await apiService.fetchUser(1);

// 데이터를 가져오거나 null이면 예외 발생
User user = response.dataOrThrow('User not found');

// 데이터를 가져오거나 대체 값 사용
User user = response.dataOr(User.guest());

// 성공한 경우에만 콜백 실행
String? greeting = response.ifSuccessful((user) => 'Hello ${user.name}');

// 성공/실패 패턴 매칭
String result = response.when(
  success: (user) => 'Welcome, ${user.name}!',
  failure: (response) => 'Error: ${response.statusMessage}',
);

// 특정 헤더 가져오기
String? authHeader = response.getHeader('Authorization');
```


<div id="base-options"></div>

## Base Options

`baseOptions` 매개변수를 사용하여 API Service의 기본 Dio 옵션을 설정합니다:

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

인스턴스에서 동적으로 옵션을 설정할 수도 있습니다:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

설정 가능한 모든 Base Option을 보려면 <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">여기</a>를 클릭하세요.


<div id="adding-headers"></div>

## 헤더 추가

### 요청별 헤더

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

### Service 레벨 헤더

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### RequestHeaders 확장

`RequestHeaders` 타입(`Map<String, dynamic>` typedef)은 헬퍼 메서드를 제공합니다:

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

| 메서드 | 설명 |
|--------|-------------|
| `addBearerToken(token)` | `Authorization: Bearer` 헤더 설정 |
| `getBearerToken()` | 헤더에서 Bearer 토큰 읽기 |
| `addHeader(key, value)` | 커스텀 헤더 추가 |
| `hasHeader(key)` | 헤더 존재 여부 확인 |


<div id="uploading-files"></div>

## 파일 업로드

### 단일 파일 업로드

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

### 다중 파일 업로드

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

## 파일 다운로드

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

Interceptor를 사용하면 요청이 전송되기 전에 수정하고, 응답을 처리하고, 오류를 관리할 수 있습니다. API Service를 통해 만들어진 모든 요청에 대해 실행됩니다.

Interceptor를 사용하는 경우:
- 모든 요청에 인증 헤더 추가
- 디버깅을 위한 요청 및 응답 로깅
- 전역적으로 요청/응답 데이터 변환
- 특정 오류 코드 처리 (예: 401에서 토큰 갱신)

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

### 커스텀 Interceptor 생성

```bash
metro make:interceptor logging
```

**파일:** `app/networking/dio/interceptors/logging_interceptor.dart`

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

{{ config('app.name') }}에는 내장 `NetworkLogger` Interceptor가 포함되어 있습니다. 환경에서 `APP_DEBUG`가 `true`일 때 기본적으로 활성화됩니다.

### 설정

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

`useNetworkLogger: false`로 설정하여 비활성화할 수 있습니다.

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- Logger 비활성화
        );
```

### 로그 레벨

| 레벨 | 설명 |
|-------|-------------|
| `LogLevelType.verbose` | 모든 요청/응답 세부 정보 출력 |
| `LogLevelType.minimal` | 메서드, URL, 상태, 시간만 출력 |
| `LogLevelType.none` | 로깅 출력 없음 |

### 로그 필터링

```dart
NetworkLogger(
  filter: (options, args) {
    // 특정 엔드포인트에 대한 요청만 로깅
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## API Service 사용

페이지에서 API Service를 호출하는 두 가지 방법이 있습니다.

### 직접 인스턴스화

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

### api() 헬퍼 사용

`api` 헬퍼는 `config/decoders.dart`의 `apiDecoders`를 사용하여 인스턴스를 생성합니다:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

콜백과 함께 사용:

```dart
await api<ApiService>(
  (request) => request.fetchUser(),
  onSuccess: (response, data) {
    // data는 모핑된 User? 인스턴스
  },
  onError: (DioException dioException) {
    // 오류 처리
  },
);
```

### api() 헬퍼 매개변수

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `request` | `Function(T)` | API 요청 함수 |
| `context` | `BuildContext?` | Build context |
| `headers` | `Map<String, dynamic>` | 추가 헤더 |
| `bearerToken` | `String?` | Bearer 토큰 |
| `baseUrl` | `String?` | Base URL 재정의 |
| `page` | `int?` | 페이지네이션 페이지 |
| `perPage` | `int?` | 페이지당 항목 수 |
| `retry` | `int` | 재시도 횟수 |
| `retryDelay` | `Duration?` | 재시도 간 지연 시간 |
| `onSuccess` | `Function(Response, dynamic)?` | 성공 콜백 |
| `onError` | `Function(DioException)?` | 오류 콜백 |
| `cacheKey` | `String?` | 캐시 키 |
| `cacheDuration` | `Duration?` | 캐시 지속 시간 |


<div id="create-an-api-service"></div>

## API Service 생성

새 API Service를 생성하려면:

```bash
metro make:api_service user
```

Model과 함께:

```bash
metro make:api_service user --model="User"
```

CRUD 메서드가 포함된 API Service가 생성됩니다:

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

## JSON을 Model로 변환

"모핑"은 JSON 응답을 Dart Model 클래스로 자동 변환하는 {{ config('app.name') }}의 기능입니다. `network<User>(...)`를 사용하면 응답 JSON이 Decoder를 통해 `User` 인스턴스로 생성됩니다 -- 수동 파싱이 필요하지 않습니다.

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  // 단일 User 반환
  Future<User?> fetchUser() async {
    return await network<User>(
      request: (request) => request.get("/user/1"),
    );
  }

  // User 목록 반환
  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }
}
```

Decoder는 `lib/bootstrap/decoders.dart`에 정의됩니다:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

`network<T>()`에 전달하는 타입 매개변수가 `modelDecoders` 맵과 매칭되어 올바른 Decoder를 찾습니다.

**참고:** Model Decoder 등록에 대한 자세한 내용은 [Decoder](/docs/{{$version}}/decoders#model-decoders)를 참조하세요.


<div id="caching-responses"></div>

## 응답 캐싱

API 호출을 줄이고 성능을 향상시키기 위해 응답을 캐시합니다. 캐싱은 국가 목록, 카테고리 또는 설정 같이 자주 변경되지 않는 데이터에 유용합니다.

`cacheKey`와 선택적 `cacheDuration`을 제공합니다:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### 캐시 지우기

```dart
// 특정 캐시 키 지우기
await apiService.clearCache("app_countries");

// 모든 API 캐시 지우기
await apiService.clearAllCache();
```

### api() 헬퍼로 캐싱

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## 캐시 정책

캐싱 동작을 세밀하게 제어하려면 `CachePolicy`를 사용합니다:

| 정책 | 설명 |
|--------|-------------|
| `CachePolicy.networkOnly` | 항상 네트워크에서 가져오기 (기본값) |
| `CachePolicy.cacheFirst` | 캐시 먼저 시도, 네트워크로 대체 |
| `CachePolicy.networkFirst` | 네트워크 먼저 시도, 캐시로 대체 |
| `CachePolicy.cacheOnly` | 캐시만 사용, 비어있으면 오류 |
| `CachePolicy.staleWhileRevalidate` | 캐시 즉시 반환, 백그라운드에서 업데이트 |

### 사용법

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

### 각 정책의 사용 시기

- **cacheFirst** -- 거의 변경되지 않는 데이터. 캐시된 데이터를 즉시 반환하고, 캐시가 비어있을 때만 네트워크에서 가져옵니다.
- **networkFirst** -- 가능하면 최신 상태여야 하는 데이터. 네트워크를 먼저 시도하고, 실패 시 캐시로 대체합니다.
- **staleWhileRevalidate** -- 즉각적인 응답이 필요하지만 업데이트도 되어야 하는 UI. 캐시된 데이터를 반환한 다음 백그라운드에서 갱신합니다.
- **cacheOnly** -- 오프라인 모드. 캐시된 데이터가 없으면 오류를 발생시킵니다.

> **참고:** `cachePolicy`를 지정하지 않고 `cacheKey` 또는 `cacheDuration`을 제공하면, 기본 정책은 `cacheFirst`입니다.


<div id="retrying-failed-requests"></div>

## 실패한 요청 재시도

실패한 요청을 자동으로 재시도합니다.

### 기본 재시도

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### 지연 시간이 있는 재시도

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### 조건부 재시도

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryIf: (DioException dioException) {
      // 서버 오류에서만 재시도
      return dioException.response?.statusCode == 500;
    },
  );
}
```

### Service 레벨 재시도

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## 연결 상태 확인

타임아웃을 기다리는 대신 기기가 오프라인일 때 빠르게 실패합니다.

### Service 레벨

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### 요청별

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### 동적

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

활성화 상태에서 기기가 오프라인일 때:
- `networkFirst` 정책은 캐시로 대체
- 다른 정책은 즉시 `DioExceptionType.connectionError`를 발생


<div id="cancel-tokens"></div>

## Cancel Token

대기 중인 요청을 관리하고 취소합니다.

```dart
// 관리되는 Cancel Token 생성
final token = apiService.createCancelToken();
await apiService.get('/endpoint', cancelToken: token);

// 모든 대기 중인 요청 취소 (예: 로그아웃 시)
apiService.cancelAllRequests('User logged out');

// 활성 요청 수 확인
int count = apiService.activeRequestCount;

// 완료 시 특정 토큰 정리
apiService.removeCancelToken(token);
```


<div id="setting-auth-headers"></div>

## 인증 헤더 설정

모든 요청에 인증 헤더를 첨부하려면 `setAuthHeaders`를 재정의합니다. 이 메서드는 `shouldSetAuthHeaders`가 `true`(기본값)일 때 각 요청 전에 호출됩니다.

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

### 인증 헤더 비활성화

인증이 필요 없는 공개 엔드포인트의 경우:

```dart
// 요청별
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// Service 레벨
apiService.setShouldSetAuthHeaders(false);
```

**참고:** 사용자 인증 및 토큰 저장에 대한 자세한 내용은 [인증](/docs/{{ $version }}/authentication)을 참조하세요.


<div id="refreshing-tokens"></div>

## 토큰 갱신

토큰 만료를 처리하려면 `shouldRefreshToken`과 `refreshToken`을 재정의합니다. 이 메서드들은 모든 요청 전에 호출됩니다.

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<bool> shouldRefreshToken() async {
    // 토큰 갱신이 필요한지 확인
    return false;
  }

  @override
  Future<void> refreshToken(Dio dio) async {
    // 새로운 Dio 인스턴스(Interceptor 없음)를 사용하여 토큰 갱신
    dynamic response = (await dio.post("https://example.com/refresh-token")).data;

    // 새 토큰을 스토리지에 저장
    await Auth.set((data) {
      data['token'] = response['token'];
      return data;
    });
  }
}
```

`refreshToken`의 `dio` 매개변수는 Service의 메인 인스턴스와 별개인 새로운 Dio 인스턴스로, Interceptor 루프를 방지합니다.


<div id="singleton-api-service"></div>

## Singleton API Service

기본적으로 `api` 헬퍼는 매번 새 인스턴스를 생성합니다. Singleton을 사용하려면 `config/decoders.dart`에서 팩토리 대신 인스턴스를 전달합니다:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // 매번 새 인스턴스

  ApiService: ApiService(), // Singleton -- 항상 동일한 인스턴스
};
```


<div id="advanced-configuration"></div>

## 고급 설정

### 커스텀 Dio 초기화

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

### Dio 인스턴스 접근

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### 페이지네이션 헬퍼

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### 이벤트 콜백

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### 재정의 가능한 속성

| 속성 | 타입 | 기본값 | 설명 |
|----------|------|---------|-------------|
| `baseUrl` | `String` | `""` | 모든 요청의 Base URL |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Dio Interceptor |
| `decoders` | `Map<Type, dynamic>?` | `{}` | JSON 모핑용 Model Decoder |
| `shouldSetAuthHeaders` | `bool` | `true` | 요청 전에 `setAuthHeaders` 호출 여부 |
| `retry` | `int` | `0` | 기본 재시도 횟수 |
| `retryDelay` | `Duration` | `1 second` | 기본 재시도 간 지연 시간 |
| `checkConnectivityBeforeRequest` | `bool` | `false` | 요청 전 연결 상태 확인 |
