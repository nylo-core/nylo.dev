# Backpack

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [기본 사용법](#basic-usage "기본 사용법")
- [데이터 읽기](#reading-data "데이터 읽기")
- [데이터 저장](#saving-data "데이터 저장")
- [데이터 삭제](#deleting-data "데이터 삭제")
- [세션](#sessions "세션")
- [Nylo 인스턴스 접근](#nylo-instance "Nylo 인스턴스 접근")
- [헬퍼 함수](#helper-functions "헬퍼 함수")
- [NyStorage 통합](#integration-with-nystorage "NyStorage 통합")
- [예제](#examples "예제")

<div id="introduction"></div>

## 소개

**Backpack**은 {{ config('app.name') }}의 인메모리 싱글톤 저장 시스템입니다. 앱 실행 중 데이터에 빠르고 동기적으로 접근할 수 있습니다. 데이터를 기기에 영구 저장하는 `NyStorage`와 달리, Backpack은 데이터를 메모리에 저장하며 앱이 종료되면 지워집니다.

Backpack은 프레임워크 내부에서 `Nylo` 앱 객체, `EventBus`, 인증 데이터와 같은 중요한 인스턴스를 저장하는 데 사용됩니다. 비동기 호출 없이 빠르게 접근해야 하는 자체 데이터를 저장하는 데도 사용할 수 있습니다.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 값 저장
Backpack.instance.save("user_name", "Anthony");

// 값 읽기 (동기)
String? name = Backpack.instance.read("user_name");

// 값 삭제
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## 기본 사용법

Backpack은 **싱글톤 패턴**을 사용합니다 -- `Backpack.instance`를 통해 접근합니다:

``` dart
// 데이터 저장
Backpack.instance.save("theme", "dark");

// 데이터 읽기
String? theme = Backpack.instance.read("theme"); // "dark"

// 데이터 존재 여부 확인
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## 데이터 읽기

`read<T>()` 메서드를 사용하여 Backpack에서 값을 읽습니다. 제네릭 타입과 선택적 기본값을 지원합니다:

``` dart
// String 읽기
String? name = Backpack.instance.read<String>("name");

// 기본값으로 읽기
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// int 읽기
int? score = Backpack.instance.read<int>("score");
```

Backpack은 타입이 제공되면 JSON 문자열을 모델 객체로 자동 역직렬화합니다:

``` dart
// User 모델이 JSON으로 저장된 경우 역직렬화됩니다
User? user = Backpack.instance.read<User>("current_user");
```

<div id="saving-data"></div>

## 데이터 저장

`save()` 메서드를 사용하여 값을 저장합니다:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### 데이터 추가

`append()`를 사용하여 키에 저장된 리스트에 값을 추가합니다:

``` dart
// 리스트에 추가
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// 제한을 두고 추가 (마지막 N개 항목만 유지)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## 데이터 삭제

### 단일 키 삭제

``` dart
Backpack.instance.delete("api_token");
```

### 모든 데이터 삭제

`deleteAll()` 메서드는 예약된 프레임워크 키(`nylo` 및 `event_bus`)를 **제외한** 모든 값을 제거합니다:

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## 세션

Backpack은 데이터를 명명된 그룹으로 구성하기 위한 세션 관리를 제공합니다. 관련 데이터를 함께 저장하는 데 유용합니다.

### 세션 값 업데이트

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### 세션 값 가져오기

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### 세션 키 제거

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### 전체 세션 플러시

``` dart
Backpack.instance.sessionFlush("cart");
```

### 모든 세션 데이터 가져오기

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Nylo 인스턴스 접근

Backpack은 `Nylo` 애플리케이션 인스턴스를 저장합니다. 다음과 같이 가져올 수 있습니다:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

Nylo 인스턴스가 초기화되었는지 확인합니다:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## 헬퍼 함수

{{ config('app.name') }}은 일반적인 Backpack 작업을 위한 전역 헬퍼 함수를 제공합니다:

| 함수 | 설명 |
|----------|-------------|
| `backpackRead<T>(key)` | Backpack에서 값 읽기 |
| `backpackSave(key, value)` | Backpack에 값 저장 |
| `backpackDelete(key)` | Backpack에서 값 삭제 |
| `backpackDeleteAll()` | 모든 값 삭제 (프레임워크 키 보존) |
| `backpackNylo()` | Backpack에서 Nylo 인스턴스 가져오기 |

### 예제

``` dart
// 헬퍼 함수 사용
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Nylo 인스턴스 접근
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## NyStorage 통합

Backpack은 영구 저장 + 인메모리 저장을 결합하기 위해 `NyStorage`와 통합됩니다:

``` dart
// NyStorage(영구)와 Backpack(인메모리) 모두에 저장
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// 이제 Backpack을 통해 동기적으로 접근 가능
String? token = Backpack.instance.read("auth_token");

// NyStorage에서 삭제할 때 Backpack에서도 삭제
await NyStorage.deleteAll(andFromBackpack: true);
```

이 패턴은 영구 저장과 빠른 동기적 접근(예: HTTP 인터셉터에서)이 모두 필요한 인증 토큰과 같은 데이터에 유용합니다.

<div id="examples"></div>

## 예제

### API 요청을 위한 인증 토큰 저장

``` dart
// 인증 인터셉터에서
class BearerAuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    String? userToken = Backpack.instance.read(StorageKeysConfig.auth);

    if (userToken != null) {
      options.headers.addAll({"Authorization": "Bearer $userToken"});
    }

    return super.onRequest(options, handler);
  }
}
```

### 세션 기반 장바구니 관리

``` dart
// 장바구니 세션에 항목 추가
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// 장바구니 데이터 읽기
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// 장바구니 비우기
Backpack.instance.sessionFlush("cart");
```

### 빠른 기능 플래그

``` dart
// 빠른 접근을 위해 Backpack에 기능 플래그 저장
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// 기능 플래그 확인
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
