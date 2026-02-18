# ネットワーキング

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- HTTPリクエストの作成
  - [便利メソッド](#convenience-methods "便利メソッド")
  - [Network ヘルパー](#network-helper "Network ヘルパー")
  - [networkResponse ヘルパー](#network-response-helper "networkResponse ヘルパー")
  - [NyResponse](#ny-response "NyResponse")
  - [基本オプション](#base-options "基本オプション")
  - [ヘッダーの追加](#adding-headers "ヘッダーの追加")
- ファイル操作
  - [ファイルのアップロード](#uploading-files "ファイルのアップロード")
  - [ファイルのダウンロード](#downloading-files "ファイルのダウンロード")
- [インターセプター](#interceptors "インターセプター")
  - [ネットワークロガー](#network-logger "ネットワークロガー")
- [API Serviceの使用](#using-an-api-service "API Serviceの使用")
- [API Serviceの作成](#create-an-api-service "API Serviceの作成")
- [JSONからモデルへの変換](#morphing-json-payloads-to-models "JSONからモデルへの変換")
- キャッシュ
  - [レスポンスのキャッシュ](#caching-responses "レスポンスのキャッシュ")
  - [キャッシュポリシー](#cache-policies "キャッシュポリシー")
- エラーハンドリング
  - [失敗したリクエストのリトライ](#retrying-failed-requests "失敗したリクエストのリトライ")
  - [接続チェック](#connectivity-checks "接続チェック")
  - [キャンセルトークン](#cancel-tokens "キャンセルトークン")
- 認証
  - [認証ヘッダーの設定](#setting-auth-headers "認証ヘッダーの設定")
  - [トークンのリフレッシュ](#refreshing-tokens "トークンのリフレッシュ")
- [シングルトン API Service](#singleton-api-service "シングルトン API Service")
- [高度な設定](#advanced-configuration "高度な設定")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} はネットワーキングをシンプルにします。`NyApiService` を継承したサービスクラスで API エンドポイントを定義し、ページから呼び出します。フレームワークが JSON デコード、エラーハンドリング、キャッシュ、レスポンスからモデルクラスへの自動変換（「モーフィング」と呼びます）を処理します。

API サービスは `lib/app/networking/` に配置されます。新規プロジェクトにはデフォルトの `ApiService` が含まれています:

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

HTTP リクエストを行う方法は 3 つあります:

| 方法 | 戻り値 | 最適な用途 |
|----------|---------|----------|
| 便利メソッド (`get`, `post` など) | `T?` | シンプルな CRUD 操作 |
| `network()` | `T?` | キャッシュ、リトライ、カスタムヘッダーが必要なリクエスト |
| `networkResponse()` | `NyResponse<T>` | ステータスコード、ヘッダー、エラー詳細が必要な場合 |

内部的には、{{ config('app.name') }} は強力な HTTP クライアントである <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> を使用しています。


<div id="convenience-methods"></div>

## 便利メソッド

`NyApiService` は一般的な HTTP 操作のためのショートハンドメソッドを提供します。これらは内部的に `network()` を呼び出します。

### GET リクエスト

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### POST リクエスト

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### PUT リクエスト

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### DELETE リクエスト

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### PATCH リクエスト

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### HEAD リクエスト

HEAD を使用してリソースの存在確認やボディをダウンロードせずにヘッダーを取得します:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Network ヘルパー

`network` メソッドは便利メソッドよりも細かい制御が可能です。変換されたデータ (`T?`) を直接返します。

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

`request` コールバックは、ベース URL とインターセプターが設定済みの <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> インスタンスを受け取ります。

### network パラメータ

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `request` | `Function(Dio)` | 実行する HTTP リクエスト（必須） |
| `bearerToken` | `String?` | このリクエスト用の Bearer トークン |
| `baseUrl` | `String?` | サービスのベース URL を上書き |
| `headers` | `Map<String, dynamic>?` | 追加ヘッダー |
| `retry` | `int?` | リトライ回数 |
| `retryDelay` | `Duration?` | リトライ間の遅延 |
| `retryIf` | `bool Function(DioException)?` | リトライの条件 |
| `connectionTimeout` | `Duration?` | 接続タイムアウト |
| `receiveTimeout` | `Duration?` | 受信タイムアウト |
| `sendTimeout` | `Duration?` | 送信タイムアウト |
| `cacheKey` | `String?` | キャッシュキー |
| `cacheDuration` | `Duration?` | キャッシュ期間 |
| `cachePolicy` | `CachePolicy?` | キャッシュ戦略 |
| `checkConnectivity` | `bool?` | リクエスト前に接続を確認 |
| `handleSuccess` | `Function(NyResponse<T>)?` | 成功時コールバック |
| `handleFailure` | `Function(NyResponse<T>)?` | 失敗時コールバック |


<div id="network-response-helper"></div>

## networkResponse ヘルパー

ステータスコード、ヘッダー、エラーメッセージなど、完全なレスポンスにアクセスする必要がある場合は `networkResponse` を使用します。`T?` ではなく `NyResponse<T>` を返します。

`networkResponse` は以下の場合に使用します:
- 特定の処理のために HTTP ステータスコードを確認する場合
- レスポンスヘッダーにアクセスする場合
- ユーザーフィードバック用の詳細なエラーメッセージを取得する場合
- カスタムエラーハンドリングロジックを実装する場合

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

ページでレスポンスを使用します:

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

### network と networkResponse の比較

```dart
// network() — データを直接返す
User? user = await network<User>(
  request: (request) => request.get("/users/1"),
);

// networkResponse() — 完全なレスポンスを返す
NyResponse<User> response = await networkResponse<User>(
  request: (request) => request.get("/users/1"),
);
User? user = response.data;
int? status = response.statusCode;
```

両方のメソッドは同じパラメータを受け付けます。データ以外のレスポンス情報を確認する必要がある場合は `networkResponse` を選択してください。


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` は Dio レスポンスを変換済みデータとステータスヘルパーでラップします。

### プロパティ

| プロパティ | 型 | 説明 |
|----------|------|-------------|
| `response` | `Response?` | 元の Dio Response |
| `data` | `T?` | 変換/デコード済みデータ |
| `rawData` | `dynamic` | 生のレスポンスデータ |
| `headers` | `Headers?` | レスポンスヘッダー |
| `statusCode` | `int?` | HTTP ステータスコード |
| `statusMessage` | `String?` | HTTP ステータスメッセージ |
| `contentType` | `String?` | ヘッダーからのコンテンツタイプ |
| `errorMessage` | `String?` | 抽出されたエラーメッセージ |

### ステータスチェック

| ゲッター | 説明 |
|--------|-------------|
| `isSuccessful` | ステータス 200-299 |
| `isClientError` | ステータス 400-499 |
| `isServerError` | ステータス 500-599 |
| `isRedirect` | ステータス 300-399 |
| `hasData` | データが null でない |
| `isUnauthorized` | ステータス 401 |
| `isForbidden` | ステータス 403 |
| `isNotFound` | ステータス 404 |
| `isTimeout` | ステータス 408 |
| `isConflict` | ステータス 409 |
| `isRateLimited` | ステータス 429 |

### データヘルパー

```dart
NyResponse<User> response = await apiService.fetchUser(1);

// データを取得するか、null の場合はスローする
User user = response.dataOrThrow('User not found');

// データを取得するか、フォールバックを使用する
User user = response.dataOr(User.guest());

// 成功した場合のみコールバックを実行する
String? greeting = response.ifSuccessful((user) => 'Hello ${user.name}');

// 成功/失敗のパターンマッチ
String result = response.when(
  success: (user) => 'Welcome, ${user.name}!',
  failure: (response) => 'Error: ${response.statusMessage}',
);

// 特定のヘッダーを取得する
String? authHeader = response.getHeader('Authorization');
```


<div id="base-options"></div>

## 基本オプション

`baseOptions` パラメータを使用して API サービスのデフォルト Dio オプションを設定します:

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

インスタンスでオプションを動的に設定することもできます:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

設定可能なすべての基本オプションについては<a href="https://pub.dev/packages/dio#request-options" target="_BLANK">こちら</a>をご覧ください。


<div id="adding-headers"></div>

## ヘッダーの追加

### リクエストごとのヘッダー

```dart
Future fetchWithHeaders() async => await network(
  request: (request) => request.get("/test"),
  headers: {
    "Authorization": "Bearer aToken123",
    "Device": "iPhone"
  }
);
```

### Bearer トークン

```dart
Future fetchUser() async => await network(
  request: (request) => request.get("/user"),
  bearerToken: "hello-world-123",
);
```

### サービスレベルのヘッダー

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### RequestHeaders 拡張

`RequestHeaders` 型（`Map<String, dynamic>` の typedef）はヘルパーメソッドを提供します:

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

| メソッド | 説明 |
|--------|-------------|
| `addBearerToken(token)` | `Authorization: Bearer` ヘッダーを設定 |
| `getBearerToken()` | ヘッダーから Bearer トークンを読み取る |
| `addHeader(key, value)` | カスタムヘッダーを追加 |
| `hasHeader(key)` | ヘッダーが存在するか確認 |


<div id="uploading-files"></div>

## ファイルのアップロード

### 単一ファイルのアップロード

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

### 複数ファイルのアップロード

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

## ファイルのダウンロード

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

## インターセプター

インターセプターを使用すると、リクエストの送信前に変更したり、レスポンスを処理したり、エラーを管理したりできます。API サービスを通じて行われるすべてのリクエストで実行されます。

インターセプターは以下の場合に使用します:
- すべてのリクエストに認証ヘッダーを追加する
- デバッグのためにリクエストとレスポンスをログに記録する
- リクエスト/レスポンスデータをグローバルに変換する
- 特定のエラーコードを処理する（例: 401 でトークンをリフレッシュ）

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

### カスタムインターセプターの作成

```bash
metro make:interceptor logging
```

**ファイル:** `app/networking/dio/interceptors/logging_interceptor.dart`

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

## ネットワークロガー

{{ config('app.name') }} には組み込みの `NetworkLogger` インターセプターが含まれています。環境設定で `APP_DEBUG` が `true` の場合、デフォルトで有効になります。

### 設定

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

`useNetworkLogger: false` に設定して無効にできます。

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- ロガーを無効にする
        );
```

### ログレベル

| レベル | 説明 |
|-------|-------------|
| `LogLevelType.verbose` | すべてのリクエスト/レスポンスの詳細を出力 |
| `LogLevelType.minimal` | メソッド、URL、ステータス、時間のみ出力 |
| `LogLevelType.none` | ログ出力なし |

### ログのフィルタリング

```dart
NetworkLogger(
  filter: (options, args) {
    // 特定のエンドポイントへのリクエストのみログに記録する
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## API Service の使用

ページから API サービスを呼び出す方法は 2 つあります。

### 直接インスタンス化

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

### api() ヘルパーの使用

`api` ヘルパーは `config/decoders.dart` の `apiDecoders` を使用してインスタンスを作成します:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

コールバック付き:

```dart
await api<ApiService>(
  (request) => request.fetchUser(),
  onSuccess: (response, data) {
    // data は変換された User? インスタンス
  },
  onError: (DioException dioException) {
    // エラーを処理する
  },
);
```

### api() ヘルパーのパラメータ

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `request` | `Function(T)` | API リクエスト関数 |
| `context` | `BuildContext?` | ビルドコンテキスト |
| `headers` | `Map<String, dynamic>` | 追加ヘッダー |
| `bearerToken` | `String?` | Bearer トークン |
| `baseUrl` | `String?` | ベース URL を上書き |
| `page` | `int?` | ページネーションのページ |
| `perPage` | `int?` | 1 ページあたりの件数 |
| `retry` | `int` | リトライ回数 |
| `retryDelay` | `Duration?` | リトライ間の遅延 |
| `onSuccess` | `Function(Response, dynamic)?` | 成功時コールバック |
| `onError` | `Function(DioException)?` | エラー時コールバック |
| `cacheKey` | `String?` | キャッシュキー |
| `cacheDuration` | `Duration?` | キャッシュ期間 |


<div id="create-an-api-service"></div>

## API Service の作成

新しい API サービスを作成するには:

```bash
metro make:api_service user
```

モデル付き:

```bash
metro make:api_service user --model="User"
```

これにより CRUD メソッドを持つ API サービスが作成されます:

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

## JSON からモデルへの変換

「モーフィング」とは、{{ config('app.name') }} で JSON レスポンスを Dart モデルクラスに自動変換することを指す用語です。`network<User>(...)` を使用すると、レスポンスの JSON がデコーダーを通じて `User` インスタンスに変換されます。手動でのパースは不要です。

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  // 単一の User を返す
  Future<User?> fetchUser() async {
    return await network<User>(
      request: (request) => request.get("/user/1"),
    );
  }

  // User のリストを返す
  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }
}
```

デコーダーは `lib/bootstrap/decoders.dart` で定義されています:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

`network<T>()` に渡す型パラメータは、`modelDecoders` マップと照合されて適切なデコーダーが見つけられます。

**参照:** モデルデコーダーの登録の詳細については [Decoders](/docs/{{$version}}/decoders#model-decoders) をご覧ください。


<div id="caching-responses"></div>

## レスポンスのキャッシュ

レスポンスをキャッシュして API 呼び出しを減らし、パフォーマンスを向上させます。キャッシュは、国リスト、カテゴリ、設定など、頻繁に変更されないデータに有効です。

`cacheKey` とオプションの `cacheDuration` を指定します:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### キャッシュのクリア

```dart
// 特定のキャッシュキーをクリアする
await apiService.clearCache("app_countries");

// すべての API キャッシュをクリアする
await apiService.clearAllCache();
```

### api() ヘルパーでのキャッシュ

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## キャッシュポリシー

`CachePolicy` を使用してキャッシュ動作を細かく制御します:

| ポリシー | 説明 |
|--------|-------------|
| `CachePolicy.networkOnly` | 常にネットワークから取得（デフォルト） |
| `CachePolicy.cacheFirst` | まずキャッシュを試行し、ネットワークにフォールバック |
| `CachePolicy.networkFirst` | まずネットワークを試行し、キャッシュにフォールバック |
| `CachePolicy.cacheOnly` | キャッシュのみ使用、空の場合はエラー |
| `CachePolicy.staleWhileRevalidate` | キャッシュを即座に返し、バックグラウンドで更新 |

### 使い方

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

### 各ポリシーの使い分け

- **cacheFirst** - めったに変更されないデータ。キャッシュされたデータを即座に返し、キャッシュが空の場合のみネットワークから取得します。
- **networkFirst** - できるだけ新鮮であるべきデータ。まずネットワークを試行し、失敗した場合はキャッシュにフォールバックします。
- **staleWhileRevalidate** - 即座のレスポンスが必要だが最新の状態を維持すべき UI。キャッシュされたデータを返してから、バックグラウンドでリフレッシュします。
- **cacheOnly** - オフラインモード。キャッシュされたデータが存在しない場合はエラーをスローします。

> **注:** `cachePolicy` を指定せずに `cacheKey` または `cacheDuration` を提供した場合、デフォルトのポリシーは `cacheFirst` になります。


<div id="retrying-failed-requests"></div>

## 失敗したリクエストのリトライ

失敗したリクエストを自動的にリトライします。

### 基本的なリトライ

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### 遅延付きリトライ

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### 条件付きリトライ

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryIf: (DioException dioException) {
      // サーバーエラーの場合のみリトライする
      return dioException.response?.statusCode == 500;
    },
  );
}
```

### サービスレベルのリトライ

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## 接続チェック

タイムアウトを待つ代わりに、デバイスがオフラインの場合に素早く失敗します。

### サービスレベル

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### リクエストごと

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### 動的設定

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

有効にした場合、デバイスがオフラインのとき:
- `networkFirst` ポリシーはキャッシュにフォールバック
- その他のポリシーは `DioExceptionType.connectionError` を即座にスロー


<div id="cancel-tokens"></div>

## キャンセルトークン

保留中のリクエストを管理およびキャンセルします。

```dart
// マネージドキャンセルトークンを作成する
final token = apiService.createCancelToken();
await apiService.get('/endpoint', cancelToken: token);

// すべての保留中リクエストをキャンセルする（例: ログアウト時）
apiService.cancelAllRequests('User logged out');

// アクティブなリクエスト数を確認する
int count = apiService.activeRequestCount;

// 完了時に特定のトークンをクリーンアップする
apiService.removeCancelToken(token);
```


<div id="setting-auth-headers"></div>

## 認証ヘッダーの設定

`setAuthHeaders` をオーバーライドして、すべてのリクエストに認証ヘッダーを添付します。このメソッドは `shouldSetAuthHeaders` が `true`（デフォルト）の場合、各リクエストの前に呼び出されます。

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

### 認証ヘッダーの無効化

認証が不要な公開エンドポイントの場合:

```dart
// リクエストごと
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// サービスレベル
apiService.setShouldSetAuthHeaders(false);
```

**参照:** ユーザーの認証とトークンの保存の詳細については [Authentication](/docs/{{ $version }}/authentication) をご覧ください。


<div id="refreshing-tokens"></div>

## トークンのリフレッシュ

`shouldRefreshToken` と `refreshToken` をオーバーライドしてトークンの有効期限切れを処理します。これらはすべてのリクエストの前に呼び出されます。

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<bool> shouldRefreshToken() async {
    // トークンのリフレッシュが必要か確認する
    return false;
  }

  @override
  Future<void> refreshToken(Dio dio) async {
    // 新しい Dio インスタンス（インターセプターなし）を使用してトークンをリフレッシュする
    dynamic response = (await dio.post("https://example.com/refresh-token")).data;

    // 新しいトークンをストレージに保存する
    await Auth.set((data) {
      data['token'] = response['token'];
      return data;
    });
  }
}
```

`refreshToken` の `dio` パラメータは、インターセプターのループを避けるために、サービスのメインインスタンスとは別の新しい Dio インスタンスです。


<div id="singleton-api-service"></div>

## シングルトン API Service

デフォルトでは、`api` ヘルパーは毎回新しいインスタンスを作成します。シングルトンを使用するには、`config/decoders.dart` でファクトリの代わりにインスタンスを渡します:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // 毎回新しいインスタンス

  ApiService: ApiService(), // シングルトン - 常に同じインスタンス
};
```


<div id="advanced-configuration"></div>

## 高度な設定

### カスタム Dio 初期化

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

### Dio インスタンスへのアクセス

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### ページネーションヘルパー

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### イベントコールバック

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### オーバーライド可能なプロパティ

| プロパティ | 型 | デフォルト | 説明 |
|----------|------|---------|-------------|
| `baseUrl` | `String` | `""` | すべてのリクエストのベース URL |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Dio インターセプター |
| `decoders` | `Map<Type, dynamic>?` | `{}` | JSON モーフィング用のモデルデコーダー |
| `shouldSetAuthHeaders` | `bool` | `true` | リクエスト前に `setAuthHeaders` を呼び出すかどうか |
| `retry` | `int` | `0` | デフォルトのリトライ回数 |
| `retryDelay` | `Duration` | `1 second` | デフォルトのリトライ間遅延 |
| `checkConnectivityBeforeRequest` | `bool` | `false` | リクエスト前に接続を確認 |
