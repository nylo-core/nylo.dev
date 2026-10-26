# Decoders

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- 사용법
  - [Model Decoders](#model-decoders "Model Decoders")
  - [Typed Decoders](#typed-decoders "Decode와 decoders()")
  - [OneOf](#one-of "둘 이상의 형태를 가질 수 있는 응답 디코딩")
  - [API Decoders](#api-decoders "API Decoders")


<div id="introduction"></div>

## 소개

Decoders는 {{ config('app.name') }}에서 도입된 새로운 개념으로, 데이터를 객체나 클래스로 디코딩할 수 있게 해줍니다.
[네트워킹](/docs/7.x/networking) 클래스를 다루거나 {{ config('app.name') }}에서 `api` 헬퍼를 사용하려는 경우 Decoders를 주로 사용하게 됩니다.

> 기본적으로 Decoders의 위치는 `lib/config/decoders.dart`입니다

decoders.dart 파일에는 두 개의 변수가 포함됩니다:
- [modelDecoders](#model-decoders) - 모든 모델 디코더를 처리합니다
- [apiDecoders](#api-decoders) - 모든 API 디코더를 처리합니다

<div id="model-decoders"></div>

## Model Decoders

Model Decoders는 {{ config('app.name') }}에서 새로 도입되었으며, 데이터 페이로드를 모델 표현으로 변환하는 방법을 제공합니다.

`network()` 헬퍼 메서드는 <b>config/decoders.dart</b> 파일 내의 `modelDecoders` 변수를 사용하여 어떤 디코더를 사용할지 결정합니다.

다음은 예제입니다.

`network` 헬퍼가 modelDecoders를 사용하는 방법입니다.

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
        : super(buildContext, decoders: modelDecoders);

  @override
  String get baseUrl => "https://jsonplaceholder.typicode.com";

  Future<User?> fetchUsers() async {
    return await network<User>(
        request: (request) => request.get("/users"),
    );
  }
...
```

`fetchUsers` 메서드는 요청의 페이로드를 자동으로 `User`로 디코딩합니다.

이것은 어떻게 작동할까요?

아래와 같은 `User` 클래스가 있습니다.

```dart
class User {
  String? name;
  String? email;

  User.fromJson(dynamic data) {
    this.name = data['name'];
    this.email = data['email'];
  }

  toJson()  => {
    "name": this.name,
    "email": this.email
  };
}
```

위에서 이 클래스에는 클래스를 초기화하는 방법을 제공하는 `fromJson` 메서드가 있음을 알 수 있습니다.

아래 메서드를 호출하여 이 클래스를 초기화할 수 있습니다.

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

이제 Decoders를 설정하려면 다음을 수행해야 합니다.

<b>파일:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

modelDecoders 파일에서 `Type`을 키로 제공하고 위 예제처럼 값에서 변환을 처리해야 합니다.

`data` 인수에는 API 요청의 페이로드가 포함됩니다.

<div id="typed-decoders"></div>

## Typed Decoders

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])`는 목록으로 `modelDecoders` 맵을 구성합니다. `Decode(User.fromJson)`은 `User`를 디코딩하며 `List<User>`도 별도 항목 없이 디코딩합니다:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

`{"data": {...}}`처럼 감싸진 본문 안에서 모델을 읽으려면 `from`을 사용하세요. `'data.user'` 같은 점으로 구분된 키는 더 깊은 곳을 읽으며, 리스트의 경우 리스트 자체를 `from`에서 읽습니다.

`Decode`는 `dynamic` 또는 `Map<String, dynamic>`을 받는 `fromJson`을 사용합니다. 클로저의 경우 매개변수에 타입을 지정하세요:

```dart
Decode((dynamic json) => User.fromJson(json)),
```

`User: (data) => User.fromJson(data)`처럼 `{...}` 맵에 클로저 디코더를 쓰는 방식도 계속 동작합니다. 둘 다 `NyDecoder`이며, `Decode`와 `OneOf`가 이 클래스를 확장합니다. 커스텀 `NyDecoder<T>`는 `decode(json, response, decoders)`를 구현하고 전체 `NyResponse`를 볼 수 있으므로 상태 코드나 헤더를 읽을 수 있습니다.

<div id="one-of"></div>

## OneOf

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf`는 둘 이상의 형태를 가질 수 있는 응답을 디코딩합니다. 공통 부모 타입으로 등록하고, 본문의 필드, 상태 코드 또는 직접 만든 조건으로 형태를 선택하세요:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

`OneOf`는 4xx 및 5xx 본문도 디코딩하며, `List<T>`는 항목별로 디코딩합니다. 인식하지 못하는 형태는 응답 데이터를 null로 둡니다.

| 생성자 | 형태를 선택하는 기준 |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | 본문의 `key` 값. `'data.type'` 같은 점으로 구분된 키는 중첩된 필드를 읽습니다 |
| `OneOf.byStatus(types, otherwise:)` | 상태 코드. 키는 `404` 같은 코드이거나 `'4xx'`, `'40x'` 같은 패턴입니다 |
| `OneOf.byJson(types, otherwise:)` | 직접 만든 조건으로, 각각 본문과 응답을 받습니다. true를 반환하는 첫 번째 조건이 선택됩니다 |
| `OneOf(resolve)` | 본문과 응답을 받아 값 또는 null을 반환하는 함수 |

상태 코드로 선택:

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

정확한 코드가 우선하고, 그다음은 `x`가 가장 적은 패턴입니다.

직접 만든 조건으로 선택:

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

함수로 선택:

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

응답에서 형태를 읽으려면 `dataAs<X>()`와 `dataIs<X>()`를 사용하세요. [NyResponse](/docs/{{$version}}/networking#ny-response)를 참조하세요.

Metro가 부모, 하위 타입, `OneOf.byKey` 항목을 대신 생성합니다:

```bash
metro make:model login_result --one-of="success,two_factor"
```

[OneOf 모델 만들기](/docs/{{$version}}/metro#make-a-one-of-model)를 참조하세요.

<div id="api-decoders"></div>

## API Decoders

API Decoders는 `api` 헬퍼 메서드를 호출할 때 사용됩니다.

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

`api` 헬퍼는 제네릭을 사용하여 올바른 API Service를 매칭하므로, 아래 헬퍼를 호출하여 서비스에 접근할 수 있습니다.

```dart
await api<MyService>((request) => request.callMyMethod());
```

`api` 헬퍼를 사용하기 전에 먼저 API Service를 <b>lib/config/decoders.dart > apiDecoders</b>에 추가해야 합니다.

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
