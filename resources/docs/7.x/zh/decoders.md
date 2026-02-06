# 解码器

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- 使用方法
  - [模型解码器](#model-decoders "模型解码器")
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

