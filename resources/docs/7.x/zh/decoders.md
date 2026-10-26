# 解码器

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- 使用方法
  - [模型解码器](#model-decoders "模型解码器")
  - [类型化解码器](#typed-decoders "Decode 和 decoders()")
  - [OneOf](#one-of "解码可能具有多种结构的响应")
  - [API 解码器](#api-decoders "API 解码器")


<div id="introduction"></div>

## 简介

解码器是 {{ config('app.name') }} 中引入的一个概念，允许您将数据解码为对象或类。当您使用[网络](/docs/7.x/networking)类或 {{ config('app.name') }} 中的 `api` 辅助函数时，通常会用到解码器。

> 默认情况下，解码器的位置在 `lib/config/decoders.dart`

decoders.dart 文件将包含两个变量：
- [modelDecoders](#model-decoders) - 处理所有模型解码器
- [apiDecoders](#api-decoders) - 处理所有 API 解码器

<div id="model-decoders"></div>

## 模型解码器

模型解码器是 {{ config('app.name') }} 中的新功能，它提供了一种将数据负载转换为模型表示的方式。

`network()` 辅助方法将使用 <b>config/decoders.dart</b> 文件中的 `modelDecoders` 变量来确定使用哪个解码器。

以下是一个示例。

这是 `network` 辅助函数如何使用 modelDecoders。

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

`fetchUsers` 方法会自动将请求的负载解码为 `User`。

这是如何工作的？

您有一个如下所示的 `User` 类。

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

从上面可以看到，这个类有一个 `fromJson` 方法，为我们提供了一种初始化类的方式。

我们可以通过调用以下方法来初始化这个类。

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

现在，要设置我们的解码器，我们需要执行以下操作。

<b>文件：</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

在 modelDecoders 文件中，我们需要提供 `Type` 作为键，并在值中处理转换，如上面的示例。

`data` 参数将包含来自 API 请求的负载。

<div id="typed-decoders"></div>

## 类型化解码器

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` 通过列表构建您的 `modelDecoders` 映射。`Decode(User.fromJson)` 可以解码 `User`，也可以解码 `List<User>`，无需单独的条目：

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

使用 `from` 从被包装的响应体内部读取模型，例如 `{"data": {...}}`。像 `'data.user'` 这样的点分键可以读取更深层的内容；对于列表，列表本身会从 `from` 中读取。

`Decode` 接受一个参数为 `dynamic` 或 `Map<String, dynamic>` 的 `fromJson`。对于闭包，请为其参数指定类型：

```dart
Decode((dynamic json) => User.fromJson(json)),
```

`{...}` 映射中的闭包解码器，例如 `User: (data) => User.fromJson(data)`，仍然可以正常使用。两者都是 `NyDecoder`，即 `Decode` 和 `OneOf` 所继承的类。自定义的 `NyDecoder<T>` 实现 `decode(json, response, decoders)`，可以看到完整的 `NyResponse`，因此能够读取状态码或响应头。

<div id="one-of"></div>

## OneOf

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf` 用于解码可能具有多种结构的响应。将其注册在共同的父类型之下，并通过响应体中的字段、状态码或您自己的条件来选择结构：

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

`OneOf` 也能解码 4xx 和 5xx 响应体，并逐项解码 `List<T>`。如果遇到无法识别的结构，响应数据将为 null。

| 构造函数 | 选择结构的依据 |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | 响应体中 `key` 的值。像 `'data.type'` 这样的点分键会读取嵌套字段 |
| `OneOf.byStatus(types, otherwise:)` | 状态码。键可以是 `404` 这样的状态码，也可以是 `'4xx'` 或 `'40x'` 这样的模式 |
| `OneOf.byJson(types, otherwise:)` | 您自己的条件，每个条件接收响应体和响应。第一个返回 true 的条件生效 |
| `OneOf(resolve)` | 一个接收响应体和响应并返回值（或 null）的函数 |

按状态码：

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

精确的状态码优先，其次是 `x` 最少的模式。

使用您自己的条件：

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

使用函数：

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

使用 `dataAs<X>()` 和 `dataIs<X>()` 从响应中读取结构；参见 [NyResponse](/docs/{{$version}}/networking#ny-response)。

Metro 会为您创建父类、其子类型以及 `OneOf.byKey` 条目：

```bash
metro make:model login_result --one-of="success,two_factor"
```

参见[创建 OneOf 模型](/docs/{{$version}}/metro#make-a-one-of-model)。

<div id="api-decoders"></div>

## API 解码器

API 解码器在调用 `api` 辅助方法时使用。

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

`api` 辅助函数将使用泛型匹配正确的 API 服务，因此您可以调用以下辅助函数来访问您的服务。

```dart
await api<MyService>((request) => request.callMyMethod());
```

在使用 `api` 辅助函数之前，您需要先将 API 服务添加到 <b>lib/config/decoders.dart > apiDecoders</b> 中。

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```

