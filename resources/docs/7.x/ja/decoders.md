# デコーダー

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- 使い方
  - [Model デコーダー](#model-decoders "Model デコーダー")
  - [型付きデコーダー](#typed-decoders "Decode と decoders()")
  - [OneOf](#one-of "複数の形を取りうるレスポンスのデコード")
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

<div id="typed-decoders"></div>

## 型付きデコーダー

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`decoders([...])` は、リストから `modelDecoders` マップを構築します。`Decode(User.fromJson)` は `User` をデコードし、`List<User>` も専用のエントリなしでデコードします:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  Decode(User.fromJson),
  Decode(Order.fromJson, from: 'data'),
]);
```

`{"data": {...}}` のようにラップされたボディの内側からモデルを読み取るには `from` を使用します。`'data.user'` のようにドットで区切ったキーでさらに深い場所を読み取れ、リストの場合は、リスト自体が `from` から読み取られます。

`Decode` は、`dynamic` または `Map<String, dynamic>` を受け取る `fromJson` を取ります。クロージャの場合は、パラメーターに型を指定してください:

```dart
Decode((dynamic json) => User.fromJson(json)),
```

`User: (data) => User.fromJson(data)` のような `{...}` マップ内のクロージャデコーダーも引き続き動作します。どちらも `NyDecoder` であり、`Decode` と `OneOf` はこのクラスを継承しています。カスタムの `NyDecoder<T>` は `decode(json, response, decoders)` を実装し、`NyResponse` 全体を参照できるため、ステータスコードやヘッダーを読み取れます。

<div id="one-of"></div>

## OneOf

<x-doc-strip label="必須" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`OneOf` は、複数の形を取りうるレスポンスをデコードします。共通の親型の下に登録し、ボディ内のフィールド、ステータスコード、または独自の条件で形を選択します:

```dart
final Map<Type, dynamic> modelDecoders = decoders([
  OneOf<LoginResult>.byKey('status', {
    'success': LoginSuccess.fromJson,
    'two_factor': TwoFactorRequired.fromJson,
  }, otherwise: (json) => LoginFailed(ApiError.fromJson(json))),
]);
```

`OneOf` は 4xx と 5xx のボディもデコードし、`List<T>` は項目ごとにデコードします。認識できない形の場合、レスポンスのデータは null になります。

| コンストラクタ | 形を選択する基準 |
|-------------|--------------------|
| `OneOf.byKey(key, types, otherwise:)` | ボディ内の `key` の値。`'data.type'` のようにドットで区切ったキーでネストしたフィールドを読み取ります |
| `OneOf.byStatus(types, otherwise:)` | ステータスコード。キーは `404` のようなコード、または `'4xx'` や `'40x'` のようなパターンです |
| `OneOf.byJson(types, otherwise:)` | 独自の条件。それぞれボディとレスポンスを受け取ります。true を返した最初のものが採用されます |
| `OneOf(resolve)` | ボディとレスポンスを受け取り、値または null を返す関数 |

ステータスコードで選択する場合:

```dart
OneOf<OrderResult>.byStatus({
  200: OrderPlaced.fromJson,
  404: OrderNotFound.fromJson,
  '4xx': (json) => OrderRejected(ApiError.fromJson(json)),
}),
```

完全一致するコードが優先され、次に `x` の数が最も少ないパターンが採用されます。

独自の条件を使う場合:

```dart
OneOf<SignUpResult>.byJson({
  (json, response) => response.statusCode == 200 && json['status'] == 'ok': SignUpComplete.fromJson,
  (json, response) => response.statusCode == 200 && json['status'] == 'verify': VerifyEmail.fromJson,
}),
```

関数を使う場合:

```dart
OneOf<OrderResult>((json, response) => switch ((response.statusCode, json)) {
  (200, {'status': 'placed'}) => OrderPlaced.fromJson(json),
  (200, _) => OrderPending.fromJson(json),
  _ => null,
}),
```

レスポンスから形を読み取るには `dataAs<X>()` と `dataIs<X>()` を使用します。[NyResponse](/docs/{{$version}}/networking#ny-response) を参照してください。

Metro は、親型、そのサブタイプ、`OneOf.byKey` のエントリを自動で作成します:

```bash
metro make:model login_result --one-of="success,two_factor"
```

[OneOf モデルの作成](/docs/{{$version}}/metro#make-a-one-of-model) を参照してください。

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
