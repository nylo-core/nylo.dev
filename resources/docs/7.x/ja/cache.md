# キャッシュ

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- 基本
  - [有効期限付き保存](#save-with-expiration "有効期限付き保存")
  - [永続保存](#save-forever "永続保存")
  - [データの取得](#retrieve-data "データの取得")
  - [データの直接保存](#store-data-directly "データの直接保存")
  - [データの削除](#remove-data "データの削除")
  - [キャッシュの確認](#check-cache "キャッシュの確認")
- ネットワーキング
  - [API レスポンスのキャッシュ](#caching-api-responses "API レスポンスのキャッシュ")
- [プラットフォームサポート](#platform-support "プラットフォームサポート")
- [API リファレンス](#api-reference "API リファレンス")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} v7 は、データの効率的な保存と取得のためのファイルベースのキャッシュシステムを提供します。キャッシュは、API レスポンスや計算結果などのコストの高いデータを保存するのに便利です。

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 値を60秒間キャッシュ
String? value = await cache().saveRemember("my_key", 60, () {
  return "Hello World";
});

// キャッシュされた値を取得
String? cached = await cache().get("my_key");

// キャッシュから削除
await cache().clear("my_key");
```

<div id="save-with-expiration"></div>

## 有効期限付き保存

`saveRemember` を使用して、有効期限付きで値をキャッシュします:

``` dart
String key = "user_profile";
int seconds = 300; // 5分

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // このコールバックはキャッシュミス時のみ実行
  printInfo("API から取得中...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

有効期限内の後続の呼び出しでは、コールバックを実行せずにキャッシュされた値が返されます。

<div id="save-forever"></div>

## 永続保存

`saveForever` を使用して、データを無期限にキャッシュします:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

データは明示的に削除されるか、アプリのキャッシュがクリアされるまでキャッシュに残ります。

<div id="retrieve-data"></div>

## データの取得

キャッシュされた値を直接取得します:

``` dart
// キャッシュされた値を取得
String? value = await cache().get<String>("my_key");

// 型キャスト付き
Map<String, dynamic>? data = await cache().get<Map<String, dynamic>>("user_data");

// 見つからないか期限切れの場合は null を返す
if (value == null) {
  print("キャッシュミスまたは期限切れ");
}
```

キャッシュされたアイテムの有効期限が切れている場合、`get()` は自動的にそれを削除して `null` を返します。

<div id="store-data-directly"></div>

## データの直接保存

`put` を使用して、コールバックなしで値を直接保存します:

``` dart
// 有効期限付きで保存
await cache().put("session_token", "abc123", seconds: 3600);

// 永続保存（seconds パラメータなし）
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## データの削除

``` dart
// 単一アイテムの削除
await cache().clear("my_key");

// すべてのキャッシュアイテムを削除
await cache().flush();
```

<div id="check-cache"></div>

## キャッシュの確認

``` dart
// キーが存在するか確認
bool exists = await cache().has("my_key");

// すべてのキャッシュキーを取得
List<String> keys = await cache().documents();

// キャッシュの合計サイズをバイト単位で取得
int sizeInBytes = await cache().size();
print("キャッシュサイズ: ${sizeInBytes / 1024} KB");
```

<div id="caching-api-responses"></div>

## API レスポンスのキャッシュ

### api() ヘルパーの使用

API レスポンスを直接キャッシュします:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### NyApiService の使用

API サービスメソッドでキャッシュを定義します:

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

メソッドの呼び出し:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## プラットフォームサポート

{{ config('app.name') }} のキャッシュはファイルベースのストレージを使用しており、以下のプラットフォームサポートがあります:

| プラットフォーム | サポート |
|----------|---------|
| iOS | フルサポート |
| Android | フルサポート |
| macOS | フルサポート |
| Windows | フルサポート |
| Linux | フルサポート |
| Web | 利用不可 |

Web プラットフォームでは、キャッシュは適切にデグレードします。コールバックは常に実行され、キャッシュはバイパスされます。

``` dart
// キャッシュが利用可能か確認
if (cache().isAvailable) {
  // キャッシュを使用
} else {
  // Web プラットフォーム - キャッシュ利用不可
}
```

<div id="api-reference"></div>

## API リファレンス

### メソッド

| メソッド | 説明 |
|--------|-------------|
| `saveRemember<T>(key, seconds, callback)` | 有効期限付きで値をキャッシュ。キャッシュされた値またはコールバックの結果を返す。 |
| `saveForever<T>(key, callback)` | 値を無期限にキャッシュ。キャッシュされた値またはコールバックの結果を返す。 |
| `get<T>(key)` | キャッシュされた値を取得。見つからないか期限切れの場合は `null` を返す。 |
| `put<T>(key, value, {seconds})` | 値を直接保存。有効期限は秒単位で任意指定。 |
| `clear(key)` | 特定のキャッシュアイテムを削除。 |
| `flush()` | すべてのキャッシュアイテムを削除。 |
| `has(key)` | キーがキャッシュに存在するか確認。`bool` を返す。 |
| `documents()` | すべてのキャッシュキーのリストを取得。`List<String>` を返す。 |
| `size()` | キャッシュの合計サイズをバイト単位で取得。`int` を返す。 |

### プロパティ

| プロパティ | 型 | 説明 |
|----------|------|-------------|
| `isAvailable` | `bool` | 現在のプラットフォームでキャッシュが利用可能かどうか。 |
