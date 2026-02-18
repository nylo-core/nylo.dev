# 캐시

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- 기본 사항
  - [만료와 함께 저장](#save-with-expiration "만료와 함께 저장")
  - [영구 저장](#save-forever "영구 저장")
  - [데이터 가져오기](#retrieve-data "데이터 가져오기")
  - [데이터 직접 저장](#store-data-directly "데이터 직접 저장")
  - [데이터 삭제](#remove-data "데이터 삭제")
  - [캐시 확인](#check-cache "캐시 확인")
- 네트워킹
  - [API 응답 캐싱](#caching-api-responses "API 응답 캐싱")
- [플랫폼 지원](#platform-support "플랫폼 지원")
- [API 참조](#api-reference "API 참조")

<div id="introduction"></div>

## 소개

{{ config('app.name') }} v7은 데이터를 효율적으로 저장하고 가져오기 위한 파일 기반 캐시 시스템을 제공합니다. 캐싱은 API 응답이나 계산된 결과와 같은 비용이 많이 드는 데이터를 저장하는 데 유용합니다.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 60초 동안 값 캐시
String? value = await cache().saveRemember("my_key", 60, () {
  return "Hello World";
});

// 캐시된 값 가져오기
String? cached = await cache().get("my_key");

// 캐시에서 삭제
await cache().clear("my_key");
```

<div id="save-with-expiration"></div>

## 만료와 함께 저장

`saveRemember`를 사용하여 만료 시간과 함께 값을 캐시합니다:

``` dart
String key = "user_profile";
int seconds = 300; // 5분

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // 이 콜백은 캐시 미스 시에만 실행됩니다
  printInfo("API에서 가져오는 중...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

만료 기간 내의 후속 호출에서는 콜백을 실행하지 않고 캐시된 값이 반환됩니다.

<div id="save-forever"></div>

## 영구 저장

`saveForever`를 사용하여 데이터를 무기한으로 캐시합니다:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

데이터는 명시적으로 제거되거나 앱의 캐시가 초기화될 때까지 캐시에 유지됩니다.

<div id="retrieve-data"></div>

## 데이터 가져오기

캐시된 값을 직접 가져옵니다:

``` dart
// 캐시된 값 가져오기
String? value = await cache().get<String>("my_key");

// 타입 캐스팅 사용
Map<String, dynamic>? data = await cache().get<Map<String, dynamic>>("user_data");

// 찾을 수 없거나 만료된 경우 null 반환
if (value == null) {
  print("캐시 미스 또는 만료됨");
}
```

캐시된 항목이 만료된 경우 `get()`은 자동으로 제거하고 `null`을 반환합니다.

<div id="store-data-directly"></div>

## 데이터 직접 저장

`put`을 사용하여 콜백 없이 값을 직접 저장합니다:

``` dart
// 만료와 함께 저장
await cache().put("session_token", "abc123", seconds: 3600);

// 영구 저장 (seconds 파라미터 없음)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## 데이터 삭제

``` dart
// 단일 항목 삭제
await cache().clear("my_key");

// 모든 캐시 항목 삭제
await cache().flush();
```

<div id="check-cache"></div>

## 캐시 확인

``` dart
// 키가 존재하는지 확인
bool exists = await cache().has("my_key");

// 모든 캐시된 키 가져오기
List<String> keys = await cache().documents();

// 전체 캐시 크기 (바이트) 가져오기
int sizeInBytes = await cache().size();
print("캐시 크기: ${sizeInBytes / 1024} KB");
```

<div id="caching-api-responses"></div>

## API 응답 캐싱

### api() 헬퍼 사용

API 응답을 직접 캐시합니다:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### NyApiService 사용

API 서비스 메서드에서 캐싱을 정의합니다:

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

그런 다음 메서드를 호출합니다:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## 플랫폼 지원

{{ config('app.name') }}의 캐시는 파일 기반 스토리지를 사용하며 다음과 같은 플랫폼을 지원합니다:

| 플랫폼 | 지원 |
|----------|---------|
| iOS | 전체 지원 |
| Android | 전체 지원 |
| macOS | 전체 지원 |
| Windows | 전체 지원 |
| Linux | 전체 지원 |
| Web | 사용 불가 |

웹 플랫폼에서는 캐시가 우아하게 저하됩니다 - 콜백이 항상 실행되고 캐싱은 우회됩니다.

``` dart
// 캐시 사용 가능 여부 확인
if (cache().isAvailable) {
  // 캐싱 사용
} else {
  // 웹 플랫폼 - 캐시 사용 불가
}
```

<div id="api-reference"></div>

## API 참조

### 메서드

| 메서드 | 설명 |
|--------|-------------|
| `saveRemember<T>(key, seconds, callback)` | 만료와 함께 값을 캐시합니다. 캐시된 값 또는 콜백 결과를 반환합니다. |
| `saveForever<T>(key, callback)` | 값을 무기한으로 캐시합니다. 캐시된 값 또는 콜백 결과를 반환합니다. |
| `get<T>(key)` | 캐시된 값을 가져옵니다. 찾을 수 없거나 만료된 경우 `null`을 반환합니다. |
| `put<T>(key, value, {seconds})` | 값을 직접 저장합니다. 선택적 만료 시간(초). |
| `clear(key)` | 특정 캐시 항목을 삭제합니다. |
| `flush()` | 모든 캐시 항목을 삭제합니다. |
| `has(key)` | 캐시에 키가 존재하는지 확인합니다. `bool`을 반환합니다. |
| `documents()` | 모든 캐시 키 목록을 가져옵니다. `List<String>`을 반환합니다. |
| `size()` | 전체 캐시 크기(바이트)를 가져옵니다. `int`를 반환합니다. |

### 속성

| 속성 | 타입 | 설명 |
|----------|------|-------------|
| `isAvailable` | `bool` | 현재 플랫폼에서 캐싱이 가능한지 여부. |
