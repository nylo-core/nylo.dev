# ストレージ

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- NyStorage
  - [値の保存](#save-values "値の保存")
  - [値の読み取り](#read-values "値の読み取り")
  - [値の削除](#delete-values "値の削除")
  - [ストレージキー](#storage-keys "ストレージキー")
  - [JSON の保存/読み取り](#save-json "JSON の保存/読み取り")
  - [TTL（有効期限）](#ttl-storage "TTL（有効期限）")
  - [バッチ操作](#batch-operations "バッチ操作")
- コレクション
  - [はじめに](#introduction-to-collections "はじめに")
  - [コレクションへの追加](#add-to-a-collection "コレクションへの追加")
  - [コレクションの読み取り](#read-a-collection "コレクションの読み取り")
  - [コレクションの更新](#update-collection "コレクションの更新")
  - [コレクションからの削除](#delete-from-collection "コレクションからの削除")
- Backpack
  - [はじめに](#backpack-storage "はじめに")
  - [Backpack で永続化](#persist-data-with-backpack "Backpack で永続化")
- [セッション](#introduction-to-sessions "セッション")
- Model ストレージ
  - [Model の保存](#model-save "Model の保存")
  - [Model コレクション](#model-collections "Model コレクション")
- [StorageKey エクステンションリファレンス](#storage-key-extension-reference "StorageKey エクステンションリファレンス")
- [ストレージ例外](#storage-exceptions "ストレージ例外")
- [レガシーマイグレーション](#legacy-migration "レガシーマイグレーション")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} v7 は `NyStorage` クラスを通じて強力なストレージシステムを提供します。内部で <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> を使用して、ユーザーのデバイスにデータを安全に保存します。

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 値を保存
await NyStorage.save("coins", 100);

// 値を読み取り
int? coins = await NyStorage.read<int>('coins'); // 100

// 値を削除
await NyStorage.delete('coins');
```

<div id="save-values"></div>

## 値の保存

`NyStorage.save()` または `storageSave()` ヘルパーを使用して値を保存します:

``` dart
// NyStorage クラスを使用
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// ヘルパー関数を使用
await storageSave("username", "Anthony");
```

### Backpack にも同時保存

永続ストレージとインメモリの Backpack の両方に保存します:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Backpack 経由で同期的にアクセス可能
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## 値の読み取り

自動型キャストで値を読み取ります:

``` dart
// String として読み取り（デフォルト）
String? username = await NyStorage.read('username');

// 型キャスト付きで読み取り
int? score = await NyStorage.read<int>('score');
double? rating = await NyStorage.read<double>('rating');
bool? isPremium = await NyStorage.read<bool>('isPremium');

// デフォルト値付き
String name = await NyStorage.read('name', defaultValue: 'Guest') ?? 'Guest';

// ヘルパー関数を使用
String? username = await storageRead('username');
int? score = await storageRead<int>('score');
```

<div id="delete-values"></div>

## 値の削除

``` dart
// 単一のキーを削除
await NyStorage.delete('username');

// Backpack からも削除
await NyStorage.delete('auth_token', andFromBackpack: true);

// 複数のキーを削除
await NyStorage.deleteMultiple(['key1', 'key2', 'key3']);

// すべて削除（オプションで除外指定可能）
await NyStorage.deleteAll();
await NyStorage.deleteAll(excludeKeys: ['auth_token']);
```

<div id="storage-keys"></div>

## ストレージキー

`lib/config/storage_keys.dart` でストレージキーを整理します:

``` dart
final class StorageKeysConfig {
  // ユーザー認証用の Auth キー
  static StorageKey auth = 'SK_AUTH';

  // アプリ起動時に同期されるキー
  static syncedOnBoot() => () async {
    return [
      coins.defaultValue(0),
      themePreference.defaultValue('light'),
    ];
  };

  static StorageKey coins = 'SK_COINS';
  static StorageKey themePreference = 'SK_THEME_PREFERENCE';
  static StorageKey onboardingComplete = 'SK_ONBOARDING_COMPLETE';
}
```

### StorageKey エクステンションの使用

`StorageKey` は `String` の typedef で、強力なエクステンションメソッドセットが付属しています:

``` dart
// 保存
await StorageKeysConfig.coins.save(100);

// Backpack 付きで保存
await StorageKeysConfig.coins.save(100, inBackpack: true);

// 読み取り
int? coins = await StorageKeysConfig.coins.read<int>();

// デフォルト値付きで読み取り
int? coins = await StorageKeysConfig.coins.fromStorage<int>(defaultValue: 0);

// JSON の保存/読み取り
await StorageKeysConfig.coins.saveJson({"gold": 50, "silver": 200});
Map? data = await StorageKeysConfig.coins.readJson<Map>();

// 削除
await StorageKeysConfig.coins.deleteFromStorage();

// 削除（エイリアス）
await StorageKeysConfig.coins.flush();

// Backpack から読み取り（同期）
int? coins = StorageKeysConfig.coins.fromBackpack<int>();

// コレクション操作
await StorageKeysConfig.coins.addToCollection<int>(100);
List<int> allCoins = await StorageKeysConfig.coins.readCollection<int>();
```

<div id="save-json"></div>

## JSON の保存/読み取り

JSON データを保存および取得します:

``` dart
// JSON を保存
Map<String, dynamic> user = {
  "name": "Anthony",
  "email": "anthony@example.com",
  "preferences": {"theme": "dark"}
};
await NyStorage.saveJson("user_data", user);

// JSON を読み取り
Map<String, dynamic>? userData = await NyStorage.readJson("user_data");
print(userData?['name']); // "Anthony"
```

<div id="ttl-storage"></div>

## TTL（有効期限）

{{ config('app.name') }} v7 は自動期限切れ付きの値の保存をサポートしています:

``` dart
// 1 時間の有効期限で保存
await NyStorage.saveWithExpiry(
  'session_token',
  'abc123',
  ttl: Duration(hours: 1),
);

// 読み取り（期限切れの場合は null を返す）
String? token = await NyStorage.readWithExpiry<String>('session_token');

// 残り時間を確認
Duration? remaining = await NyStorage.getTimeToLive('session_token');
if (remaining != null) {
  print('Expires in ${remaining.inMinutes} minutes');
}

// 期限切れのすべてのキーをクリーンアップ
int removed = await NyStorage.removeExpired();
print('Removed $removed expired keys');
```

<div id="batch-operations"></div>

## バッチ操作

複数のストレージ操作を効率的に処理します:

``` dart
// 複数の値を保存
await NyStorage.saveAll({
  'username': 'Anthony',
  'score': 1500,
  'level': 10,
});

// 複数の値を読み取り
Map<String, dynamic?> values = await NyStorage.readMultiple<dynamic>([
  'username',
  'score',
  'level',
]);

// 複数のキーを削除
await NyStorage.deleteMultiple(['temp_1', 'temp_2', 'temp_3']);
```

<div id="introduction-to-collections"></div>

## コレクションの紹介

コレクションにより、単一のキーの下にアイテムのリストを保存できます:

``` dart
// コレクションにアイテムを追加
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// コレクションを読み取り
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## コレクションへの追加

``` dart
// アイテムを追加（デフォルトで重複を許可）
await NyStorage.addToCollection("cart_ids", item: 123);

// 重複を防止
await NyStorage.addToCollection(
  "cart_ids",
  item: 123,
  allowDuplicates: false,
);

// コレクション全体を一度に保存
await NyStorage.saveCollection<int>("cart_ids", [1, 2, 3, 4, 5]);
```

<div id="read-a-collection"></div>

## コレクションの読み取り

``` dart
// 型付きでコレクションを読み取り
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// コレクションが空かどうかを確認
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## コレクションの更新

``` dart
// インデックスでアイテムを更新
await NyStorage.updateCollectionByIndex<int>(
  0, // インデックス
  (item) => item + 10, // 変換関数
  key: "scores",
);

// 条件に一致するアイテムを更新
await NyStorage.updateCollectionWhere<Map<String, dynamic>>(
  (item) => item['id'] == 5, // 条件
  key: "products",
  update: (item) {
    item['quantity'] = item['quantity'] + 1;
    return item;
  },
);
```

<div id="delete-from-collection"></div>

## コレクションからの削除

``` dart
// インデックスで削除
await NyStorage.deleteFromCollection<String>(0, key: "favorites");

// 値で削除
await NyStorage.deleteValueFromCollection<int>(
  "cart_ids",
  value: 123,
);

// 条件に一致するアイテムを削除
await NyStorage.deleteFromCollectionWhere<Map<String, dynamic>>(
  (item) => item['expired'] == true,
  key: "coupons",
);

// コレクション全体を削除
await NyStorage.delete("favorites");
```

<div id="backpack-storage"></div>

## Backpack ストレージ

`Backpack` は、ユーザーのセッション中に高速な同期アクセスを提供する軽量なインメモリストレージクラスです。アプリが閉じるとデータは**永続化されません**。

### Backpack に保存

``` dart
// ヘルパーを使用
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Backpack を直接使用
Backpack.instance.save('settings', {'darkMode': true});
```

### Backpack から読み取り

``` dart
// ヘルパーを使用
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Backpack を直接使用
var settings = Backpack.instance.read('settings');
```

### Backpack から削除

``` dart
backpackDelete('user_token');

// すべて削除
backpackDeleteAll();
```

### 実用的な例

``` dart
// ログイン後、永続ストレージとセッションストレージの両方にトークンを保存
Future<void> handleLogin(String token) async {
  // アプリ再起動用に永続化
  await NyStorage.save('auth_token', token);

  // 高速アクセス用に Backpack にも保存
  backpackSave('auth_token', token);
}

// API サービスで同期的にアクセス
class ApiService extends NyApiService {
  Future<User?> getProfile() async {
    return await network<User>(
      request: (request) => request.get("/profile"),
      bearerToken: backpackRead('auth_token'), // await 不要
    );
  }
}
```

<div id="persist-data-with-backpack"></div>

## Backpack で永続化

永続ストレージと Backpack の両方に一度の呼び出しで保存します:

``` dart
// 両方に保存
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Backpack（同期）と NyStorage（非同期）の両方からアクセス可能
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### ストレージを Backpack に同期

アプリ起動時にすべての永続ストレージを Backpack に読み込みます:

``` dart
// アプリプロバイダー内で
await NyStorage.syncToBackpack();

// 上書きオプション付き
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## セッション

セッションは、関連データをグループ化するための名前付きインメモリストレージを提供します（永続化されません）:

``` dart
// セッションを作成/アクセスしてデータを追加
session('checkout')
    .add('items', ['Product A', 'Product B'])
    .add('total', 99.99)
    .add('coupon', 'SAVE10');

// またはデータ付きで初期化
session('checkout', {
  'items': ['Product A', 'Product B'],
  'total': 99.99,
});

// セッションデータを読み取り
List<String>? items = session('checkout').get<List<String>>('items');
double? total = session('checkout').get<double>('total');

// すべてのセッションデータを取得
Map<String, dynamic>? checkoutData = session('checkout').data();

// 単一の値を削除
session('checkout').delete('coupon');

// セッション全体をクリア
session('checkout').clear();
// または
session('checkout').flush();
```

### セッションの永続化

セッションは永続ストレージに同期できます:

``` dart
// セッションをストレージに保存
await session('checkout').syncToStorage();

// ストレージからセッションを復元
await session('checkout').syncFromStorage();
```

### セッションの使用例

セッションは以下に最適です:
- マルチステップフォーム（オンボーディング、チェックアウト）
- 一時的なユーザー設定
- ウィザード/ジャーニーフロー
- ショッピングカートデータ

<div id="model-save"></div>

## Model の保存

`Model` ベースクラスは組み込みのストレージメソッドを提供します。コンストラクタで `key` を定義すると、モデルは自身を保存できます:

``` dart
class User extends Model {
  String? name;
  String? email;

  // ストレージキーを定義
  static StorageKey key = 'user';
  User() : super(key: key);

  User.fromJson(dynamic data) : super(key: key) {
    name = data['name'];
    email = data['email'];
  }

  @override
  Map<String, dynamic> toJson() => {
    "name": name,
    "email": email,
  };
}
```

### Model の保存

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// 永続ストレージに保存
await user.save();

// ストレージと Backpack の両方に保存
await user.save(inBackpack: true);
```

### Model の読み戻し

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Backpack への同期

同期アクセスのためにストレージから Backpack にモデルを読み込みます:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Model コレクション

モデルをコレクションに保存します:

``` dart
User userAnthony = User();
userAnthony.name = 'Anthony';
await userAnthony.saveToCollection();

User userKyle = User();
userKyle.name = 'Kyle';
await userKyle.saveToCollection();

// 読み戻し
List<User> users = await NyStorage.readCollection<User>(User.key);
```

<div id="storage-key-extension-reference"></div>

## StorageKey エクステンションリファレンス

`StorageKey` は `String` の typedef です。`NyStorageKeyExt` エクステンションは以下のメソッドを提供します:

| メソッド | 戻り値 | 説明 |
|--------|---------|-------------|
| `save(value, {inBackpack})` | `Future` | 値をストレージに保存 |
| `saveJson(value, {inBackpack})` | `Future` | JSON 値をストレージに保存 |
| `read<T>({defaultValue})` | `Future<T?>` | ストレージから値を読み取り |
| `readJson<T>({defaultValue})` | `Future<T?>` | ストレージから JSON 値を読み取り |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | read のエイリアス |
| `fromBackpack<T>({defaultValue})` | `T?` | Backpack から読み取り（同期） |
| `toModel<T>()` | `T` | JSON 文字列をモデルに変換 |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | コレクションにアイテムを追加 |
| `readCollection<T>()` | `Future<List<T>>` | コレクションを読み取り |
| `deleteFromStorage({andFromBackpack})` | `Future` | ストレージから削除 |
| `flush({andFromBackpack})` | `Future` | deleteFromStorage のエイリアス |
| `defaultValue<T>(value)` | `Future Function(bool)?` | キーが空の場合にデフォルトを設定（syncedOnBoot で使用） |

<div id="storage-exceptions"></div>

## ストレージ例外

{{ config('app.name') }} v7 は型付きストレージ例外を提供します:

| 例外 | 説明 |
|-----------|-------------|
| `StorageException` | メッセージとオプションのキー付きの基本例外 |
| `StorageSerializationException` | ストレージ用のオブジェクトシリアライズに失敗 |
| `StorageDeserializationException` | 保存されたデータのデシリアライズに失敗 |
| `StorageKeyNotFoundException` | ストレージキーが見つからない |
| `StorageTimeoutException` | ストレージ操作がタイムアウト |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Failed to load user: ${e.message}');
  print('Expected type: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## レガシーマイグレーション

{{ config('app.name') }} v7 は新しいエンベロープストレージフォーマットを使用しています。v6 からアップグレードする場合、古いデータをマイグレーションできます:

``` dart
// アプリの初期化中に呼び出し
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Migrated $migrated keys to new format');
```

これにより、レガシーフォーマット（別個の `_runtime_type` キー）が新しいエンベロープフォーマットに変換されます。マイグレーションは複数回実行しても安全です -- 既にマイグレーション済みのキーはスキップされます。
