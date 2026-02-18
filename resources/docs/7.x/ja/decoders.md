# デコーダー

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- 使い方
  - [Model デコーダー](#model-decoders "Model デコーダー")
  - [API デコーダー](#api-decoders "API デコーダー")


<div id="introduction"></div>

## はじめに

デコーダーは {{ config('app.name') }} で導入された概念で、データをオブジェクトやクラスにデコードできます。
デコーダーは主に[ネットワーキング](/docs/7.x/networking)クラスを扱うときや、{{ config('app.name') }} の `api` ヘルパーを使用する場合に使います。

> デフォルトでは、デコーダーの場所は `lib/config/decoders.dart` です

decoders.dart ファイルには 2 つの変数が含まれます:
- [modelDecoders](#model-decoders) - すべての Model デコーダーを処理
- [apiDecoders](#api-decoders) - すべての API デコーダーを処理

<div id="model-decoders"></div>

## Model デコーダー

Model デコーダーは {{ config('app.name') }} で新しく導入されたもので、データペイロードを Model 表現にモーフィングする方法を提供します。

`network()` ヘルパーメソッドは <b>config/decoders.dart</b> ファイル内の `modelDecoders` 変数を使用して、どのデコーダーを使用するかを決定します。

以下は例です。

`network` ヘルパーが modelDecoders を使用する方法:

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

`fetchUsers` メソッドはリクエストからのペイロードを自動的に `User` にデコードします。

これはどのように動作するのでしょうか？

以下のような `User` クラスがあるとします。

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

上記のクラスには `fromJson` メソッドがあり、クラスを初期化する方法を提供しています。

以下のメソッドを呼び出してこのクラスを初期化できます。

```dart
User user = User.fromJson({'name': 'Anthony', 'email': 'agordon@mail.com'});
```

デコーダーをセットアップするには、以下のようにする必要があります。

<b>ファイル:</b> config/decoders.dart
```dart
final modelDecoders = {
  List<User>: (data) => List.from(data).map((json) => User.fromJson(json)).toList(),

  User: (data) => User.fromJson(data),

  // ...
};
```

modelDecoders ファイルでは、キーとして `Type` を提供し、上記の例のように値でモーフィングを処理する必要があります。

`data` 引数には API リクエストからのペイロードが含まれます。

<div id="api-decoders"></div>

## API デコーダー

API デコーダーは `api` ヘルパーメソッドを呼び出すときに使用されます。

```dart
loadUser() async {
    User user = await api<ApiService>((request) => request.fetchUser());
}
```

`api` ヘルパーはジェネリクスを使用して正しい API サービスをマッチングするため、以下のヘルパーを呼び出してサービスにアクセスできます。

```dart
await api<MyService>((request) => request.callMyMethod());
```

`api` ヘルパーを使用する前に、まず API サービスを <b>lib/config/decoders.dart > apiDecoders</b> に追加する必要があります。

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: ApiService(),

  // ...
};
```
