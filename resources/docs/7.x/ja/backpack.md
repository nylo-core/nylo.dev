# Backpack

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [基本的な使い方](#basic-usage "基本的な使い方")
- [データの読み取り](#reading-data "データの読み取り")
- [データの保存](#saving-data "データの保存")
- [データの削除](#deleting-data "データの削除")
- [セッション](#sessions "セッション")
- [Nylo インスタンスへのアクセス](#nylo-instance "Nylo インスタンスへのアクセス")
- [ヘルパー関数](#helper-functions "ヘルパー関数")
- [NyStorage との統合](#integration-with-nystorage "NyStorage との統合")
- [使用例](#examples "使用例")

<div id="introduction"></div>

## はじめに

**Backpack** は {{ config('app.name') }} のインメモリシングルトンストレージシステムです。アプリの実行中にデータへの高速で同期的なアクセスを提供します。デバイスにデータを永続化する `NyStorage` とは異なり、Backpack はメモリにデータを保存し、アプリが閉じられるとクリアされます。

Backpack はフレームワーク内部で `Nylo` アプリオブジェクト、`EventBus`、認証データなどの重要なインスタンスを保存するために使用されます。非同期呼び出しなしで迅速にアクセスする必要がある独自のデータを保存するためにも使用できます。

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 値を保存
Backpack.instance.save("user_name", "Anthony");

// 値を読み取り（同期）
String? name = Backpack.instance.read("user_name");

// 値を削除
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## 基本的な使い方

Backpack は **シングルトンパターン** を使用します -- `Backpack.instance` を通じてアクセスします:

``` dart
// データを保存
Backpack.instance.save("theme", "dark");

// データを読み取り
String? theme = Backpack.instance.read("theme"); // "dark"

// データが存在するか確認
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## データの読み取り

`read<T>()` メソッドを使用して Backpack から値を読み取ります。ジェネリック型とオプションのデフォルト値をサポートしています:

``` dart
// String を読み取り
String? name = Backpack.instance.read<String>("name");

// デフォルト値付きで読み取り
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// int を読み取り
int? score = Backpack.instance.read<int>("score");
```

型が指定されている場合、Backpack は JSON 文字列を自動的にモデルオブジェクトにデシリアライズします:

``` dart
// User モデルが JSON として保存されている場合、デシリアライズされます
User? user = Backpack.instance.read<User>("current_user");
```

<div id="saving-data"></div>

## データの保存

`save()` メソッドを使用して値を保存します:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### データの追加

`append()` を使用して、キーに保存されたリストに値を追加します:

``` dart
// リストに追加
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// 制限付きで追加（最後の N 件のみ保持）
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## データの削除

### 単一のキーを削除

``` dart
Backpack.instance.delete("api_token");
```

### すべてのデータを削除

`deleteAll()` メソッドは、予約されたフレームワークキー（`nylo` と `event_bus`）**以外** のすべての値を削除します:

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## セッション

Backpack は、データを名前付きグループに整理するためのセッション管理を提供します。関連するデータをまとめて保存するのに便利です。

### セッション値の更新

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### セッション値の取得

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### セッションキーの削除

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### セッション全体のフラッシュ

``` dart
Backpack.instance.sessionFlush("cart");
```

### すべてのセッションデータの取得

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Nylo インスタンスへのアクセス

Backpack は `Nylo` アプリケーションインスタンスを保存しています。以下のように取得できます:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

Nylo インスタンスが初期化されているかを確認します:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## ヘルパー関数

{{ config('app.name') }} は一般的な Backpack 操作のためのグローバルヘルパー関数を提供します:

| 関数 | 説明 |
|----------|-------------|
| `backpackRead<T>(key)` | Backpack から値を読み取り |
| `backpackSave(key, value)` | Backpack に値を保存 |
| `backpackDelete(key)` | Backpack から値を削除 |
| `backpackDeleteAll()` | すべての値を削除（フレームワークキーは保持） |
| `backpackNylo()` | Backpack から Nylo インスタンスを取得 |

### 例

``` dart
// ヘルパー関数の使用
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Nylo インスタンスへのアクセス
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## NyStorage との統合

Backpack は `NyStorage` と統合して、永続ストレージとインメモリストレージを組み合わせて使用できます:

``` dart
// NyStorage（永続）と Backpack（インメモリ）の両方に保存
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Backpack を通じて同期的にアクセス可能に
String? token = Backpack.instance.read("auth_token");

// NyStorage から削除する際に、Backpack からもクリア
await NyStorage.deleteAll(andFromBackpack: true);
```

このパターンは、認証トークンのように永続化と高速な同期アクセスの両方が必要なデータ（例: HTTP インターセプター内）に便利です。

<div id="examples"></div>

## 使用例

### API リクエスト用の認証トークンの保存

``` dart
// 認証インターセプター内で
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

### セッションベースのカート管理

``` dart
// カートセッションにアイテムを追加
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// カートデータの読み取り
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// カートのクリア
Backpack.instance.sessionFlush("cart");
```

### クイック機能フラグ

``` dart
// 高速アクセスのために機能フラグを Backpack に保存
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// 機能フラグの確認
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
