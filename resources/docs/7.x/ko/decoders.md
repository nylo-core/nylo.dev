# Decoders

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- 사용법
  - [Model Decoders](#model-decoders "Model Decoders")
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
