# 存储

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- NyStorage
  - [保存值](#save-values "保存值")
  - [读取值](#read-values "读取值")
  - [删除值](#delete-values "删除值")
  - [存储键](#storage-keys "存储键")
  - [保存/读取 JSON](#save-json "保存/读取 JSON")
  - [TTL（生存时间）](#ttl-storage "TTL（生存时间）")
  - [批量操作](#batch-operations "批量操作")
- 集合
  - [简介](#introduction-to-collections "简介")
  - [添加到集合](#add-to-a-collection "添加到集合")
  - [读取集合](#read-a-collection "读取集合")
  - [更新集合](#update-collection "更新集合")
  - [从集合中删除](#delete-from-collection "从集合中删除")
- Backpack
  - [简介](#backpack-storage "简介")
  - [使用 Backpack 持久化](#persist-data-with-backpack "使用 Backpack 持久化")
- [会话](#introduction-to-sessions "会话")
- Model 存储
  - [Model 保存](#model-save "Model 保存")
  - [Model 集合](#model-collections "Model 集合")
- [文件存储](#file-storage "文件存储")
- [StorageKey 扩展参考](#storage-key-extension-reference "StorageKey 扩展参考")
- [存储异常](#storage-exceptions "存储异常")
- [旧版迁移](#legacy-migration "旧版迁移")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} v7 通过 `NyStorage` 类提供强大的存储系统。它底层使用 <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> 在用户设备上安全存储数据。

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Save a value
await NyStorage.save("coins", 100);

// Read a value
int? coins = await NyStorage.read<int>('coins'); // 100

// Delete a value
await NyStorage.delete('coins');
```

> 当您的应用以调试模式运行时，您可以从终端实时查看和更改这些值，并将它们捕获为填充器以便复用 -- 参见 Metro Live 中的[存储快照](/docs/{{ $version }}/metro-live#storage-snapshots)。

<div id="save-values"></div>

## 保存值

使用 `NyStorage.save()` 或 `storageSave()` 辅助函数保存值：

``` dart
// Using NyStorage class
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Using helper function
await storageSave("username", "Anthony");
```

### 同时保存到 Backpack

同时存储到持久存储和内存中的 Backpack：

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## 读取值

使用自动类型转换读取值：

``` dart
// Read as String (default)
String? username = await NyStorage.read('username');

// Read with type casting
int? score = await NyStorage.read<int>('score');
double? rating = await NyStorage.read<double>('rating');
bool? isPremium = await NyStorage.read<bool>('isPremium');

// With default value
String name = await NyStorage.read('name', defaultValue: 'Guest') ?? 'Guest';

// Using helper function
String? username = await storageRead('username');
int? score = await storageRead<int>('score');
```

<div id="delete-values"></div>

## 删除值

``` dart
// Delete a single key
await NyStorage.delete('username');

// Delete and remove from Backpack too
await NyStorage.delete('auth_token', andFromBackpack: true);

// Delete multiple keys
await NyStorage.deleteMultiple(['key1', 'key2', 'key3']);

// Delete all (with optional exclusions)
await NyStorage.deleteAll();
await NyStorage.deleteAll(excludeKeys: ['auth_token']);
```

<div id="storage-keys"></div>

## 存储键

在 `lib/config/storage_keys.dart` 中组织您的存储键：

``` dart
final class StorageKeysConfig {
  // Auth key for user authentication
  static StorageKey auth = 'SK_AUTH';

  // Keys synced on app boot
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

### 使用 StorageKey 扩展

`StorageKey` 是 `String` 的类型别名，附带一组强大的扩展方法：

``` dart
// Save
await StorageKeysConfig.coins.save(100);

// Save with Backpack
await StorageKeysConfig.coins.save(100, inBackpack: true);

// Read
int? coins = await StorageKeysConfig.coins.read<int>();

// Read with default value
int? coins = await StorageKeysConfig.coins.fromStorage<int>(defaultValue: 0);

// Save/Read JSON
await StorageKeysConfig.coins.saveJson({"gold": 50, "silver": 200});
Map? data = await StorageKeysConfig.coins.readJson<Map>();

// Delete
await StorageKeysConfig.coins.deleteFromStorage();

// Delete (alias)
await StorageKeysConfig.coins.flush();

// Read from Backpack (synchronous)
int? coins = StorageKeysConfig.coins.fromBackpack<int>();

// Collection operations
await StorageKeysConfig.coins.addToCollection<int>(100);
List<int> allCoins = await StorageKeysConfig.coins.readCollection<int>();
```

<div id="save-json"></div>

## 保存/读取 JSON

存储和检索 JSON 数据：

``` dart
// Save JSON
Map<String, dynamic> user = {
  "name": "Anthony",
  "email": "anthony@example.com",
  "preferences": {"theme": "dark"}
};
await NyStorage.saveJson("user_data", user);

// Read JSON
Map<String, dynamic>? userData = await NyStorage.readJson("user_data");
print(userData?['name']); // "Anthony"
```

<div id="ttl-storage"></div>

## TTL（生存时间）

{{ config('app.name') }} v7 支持存储带有自动过期功能的值：

``` dart
// Save with 1 hour expiration
await NyStorage.saveWithExpiry(
  'session_token',
  'abc123',
  ttl: Duration(hours: 1),
);

// Read (returns null if expired)
String? token = await NyStorage.readWithExpiry<String>('session_token');

// Check remaining time
Duration? remaining = await NyStorage.getTimeToLive('session_token');
if (remaining != null) {
  print('Expires in ${remaining.inMinutes} minutes');
}

// Clean up all expired keys
int removed = await NyStorage.removeExpired();
print('Removed $removed expired keys');
```

<div id="batch-operations"></div>

## 批量操作

高效处理多个存储操作：

``` dart
// Save multiple values
await NyStorage.saveAll({
  'username': 'Anthony',
  'score': 1500,
  'level': 10,
});

// Read multiple values
Map<String, dynamic?> values = await NyStorage.readMultiple<dynamic>([
  'username',
  'score',
  'level',
]);

// Delete multiple keys
await NyStorage.deleteMultiple(['temp_1', 'temp_2', 'temp_3']);
```

<div id="introduction-to-collections"></div>

## 集合简介

集合允许您在单个键下存储项目列表：

``` dart
// Add items to a collection
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Read the collection
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## 添加到集合

``` dart
// Add item (allows duplicates by default)
await NyStorage.addToCollection("cart_ids", item: 123);

// Prevent duplicates
await NyStorage.addToCollection(
  "cart_ids",
  item: 123,
  allowDuplicates: false,
);

// Save entire collection at once
await NyStorage.saveCollection<int>("cart_ids", [1, 2, 3, 4, 5]);
```

<div id="read-a-collection"></div>

## 读取集合

``` dart
// Read collection with type
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Check if collection is empty
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## 更新集合

``` dart
// Update item by index
await NyStorage.updateCollectionByIndex<int>(
  0, // index
  (item) => item + 10, // transform function
  key: "scores",
);

// Update items matching a condition
await NyStorage.updateCollectionWhere<Map<String, dynamic>>(
  (item) => item['id'] == 5, // where condition
  key: "products",
  update: (item) {
    item['quantity'] = item['quantity'] + 1;
    return item;
  },
);
```

<div id="delete-from-collection"></div>

## 从集合中删除

``` dart
// Delete by index
await NyStorage.deleteFromCollection<String>(0, key: "favorites");

// Delete by value
await NyStorage.deleteValueFromCollection<int>(
  "cart_ids",
  value: 123,
);

// Delete items matching a condition
await NyStorage.deleteFromCollectionWhere<Map<String, dynamic>>(
  (item) => item['expired'] == true,
  key: "coupons",
);

// Delete entire collection
await NyStorage.delete("favorites");
```

<div id="backpack-storage"></div>

## Backpack 存储

`Backpack` 是一个轻量级的内存存储类，用于在用户会话期间进行快速同步访问。数据在应用关闭时**不会持久化**。

### 保存到 Backpack

``` dart
// Using helper
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Using Backpack directly
Backpack.instance.save('settings', {'darkMode': true});
```

### 从 Backpack 读取

``` dart
// Using helper
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Using Backpack directly
var settings = Backpack.instance.read('settings');
```

### 从 Backpack 删除

``` dart
backpackDelete('user_token');

// Delete all
backpackDeleteAll();
```

### 实际示例

``` dart
// After login, store token in both persistent and session storage
Future<void> handleLogin(String token) async {
  // Persist for app restarts
  await NyStorage.save('auth_token', token);

  // Also store in Backpack for quick access
  backpackSave('auth_token', token);
}

// In API service, access synchronously
class ApiService extends NyApiService {
  Future<User?> getProfile() async {
    return await network<User>(
      request: (request) => request.get("/profile"),
      bearerToken: backpackRead('auth_token'), // No await needed
    );
  }
}
```

<div id="persist-data-with-backpack"></div>

## 使用 Backpack 持久化

一次调用同时存储到持久存储和 Backpack：

``` dart
// Save to both
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Now accessible via Backpack (sync) and NyStorage (async)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### 将存储同步到 Backpack

在应用启动时将所有持久存储加载到 Backpack：

``` dart
// In your app provider
await NyStorage.syncToBackpack();

// With overwrite option
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## 会话

会话提供命名的内存存储，用于分组相关数据（不持久化）：

``` dart
// Create/access a session and add data
session('checkout')
    .add('items', ['Product A', 'Product B'])
    .add('total', 99.99)
    .add('coupon', 'SAVE10');

// Or initialize with data
session('checkout', {
  'items': ['Product A', 'Product B'],
  'total': 99.99,
});

// Read session data
List<String>? items = session('checkout').get<List<String>>('items');
double? total = session('checkout').get<double>('total');

// Get all session data
Map<String, dynamic>? checkoutData = session('checkout').data();

// Delete a single value
session('checkout').delete('coupon');

// Clear entire session
session('checkout').clear();
// or
session('checkout').flush();
```

### 持久化会话

会话可以同步到持久存储：

``` dart
// Save session to storage
await session('checkout').syncToStorage();

// Restore session from storage
await session('checkout').syncFromStorage();
```

### 会话使用场景

会话非常适用于：
- 多步骤表单（引导流程、结账）
- 临时用户偏好设置
- 向导/旅程流程
- 购物车数据

<div id="model-save"></div>

## Model 保存

`Model` 基类提供内置的存储方法。当您在构造函数中定义 `key` 时，模型可以自行保存：

``` dart
class User extends Model {
  String? name;
  String? email;

  // Define a storage key
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

### 保存 Model

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Save to persistent storage
await user.save();

// Save to both storage and Backpack
await user.save(inBackpack: true);
```

### 读取 Model

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### 同步到 Backpack

将模型从存储加载到 Backpack 以进行同步访问：

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Model 集合

将模型保存到集合：

``` dart
User userAnthony = User();
userAnthony.name = 'Anthony';
await userAnthony.saveToCollection();

User userKyle = User();
userKyle.name = 'Kyle';
await userKyle.saveToCollection();

// Read back
List<User> users = await NyStorage.readCollection<User>(User.key);
```

<div id="file-storage"></div>

## 文件存储

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.2.4+, nylo_support 7.31.0+" />

`NyStorage` 保存的是值。文件存储保存的是文件，例如 PDF、照片和下载内容，并存放在具名磁盘上：

``` dart
await disk('documents').put('reports/q3.pdf', pdfBytes);

String text = await disk('documents').get('notes.txt');
bool exists = await disk('documents').exists('reports/q3.pdf');
await disk('documents').delete('reports/q3.pdf');
```

不带名称的 `disk()` 使用默认磁盘。`FileStorage` 为默认磁盘提供了相同的方法：

``` dart
await FileStorage.put('notes.txt', 'Hello');
```

无需任何配置即可使用五个磁盘：

| 磁盘 | 位置 | 说明 |
|------|-------|-------|
| `local` | 应用的支持文件夹 | 默认磁盘 |
| `documents` | 应用的文档文件夹 | 属于用户的文件 |
| `cache` | 应用的缓存文件夹 | 操作系统可能会清除 |
| `temp` | 临时文件夹 | 一天后清理 |
| `assets` | 应用包中的 `assets/` 下 | 只读 |

### 存储上传的文件

`putFile` 会将来自 `image_picker`、`file_picker` 或相机的文件（`XFile`）以随机名称存储，并返回其路径：

``` dart
XFile? photo = await ImagePicker().pickImage(source: ImageSource.gallery);
if (photo != null) {
  String path = await disk().putFile('avatars', photo);
  // avatars/k3v0q...9q.jpg
}
```

使用 `putFileAs('avatars', photo, 'me.jpg')` 可自行选择名称。

### 显示已存储的图片

`DiskImage` 是用于磁盘上图片的 `ImageProvider`：

``` dart
CircleAvatar(backgroundImage: DiskImage('avatars/me.jpg'))
Image(image: DiskImage('thumbs/42.jpg', disk: 'cache'))
```

通过其磁盘再次写入该文件，屏幕上的图片会随之更新。

### 自定义磁盘

将您自己的磁盘传给 `nylo.configure`。它们会被添加到默认磁盘中，与默认磁盘同名的磁盘会将其替换：

``` dart
await nylo.configure(
  // ...
  disks: {
    'exports': DiskConfig.local(DiskRoot.documents, prefix: 'exports'),
    'scratch': DiskConfig.memory(),
  },
  defaultDisk: 'exports',
);
```

`DiskConfig` 还有 `path`、`assets`、`scoped`（另一个磁盘内的文件夹）和 `custom`（您自己的 `DiskDriver`）。

要测试使用磁盘的代码，参见[测试中的文件存储](/docs/{{ $version }}/testing#file-storage)。

<div id="storage-key-extension-reference"></div>

## StorageKey 扩展参考

`StorageKey` 是 `String` 的类型别名。`NyStorageKeyExt` 扩展提供以下方法：

| 方法 | 返回值 | 描述 |
|--------|---------|-------------|
| `save(value, {inBackpack})` | `Future` | 将值保存到存储 |
| `saveJson(value, {inBackpack})` | `Future` | 将 JSON 值保存到存储 |
| `read<T>({defaultValue})` | `Future<T?>` | 从存储中读取值 |
| `readJson<T>({defaultValue})` | `Future<T?>` | 从存储中读取 JSON 值 |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | read 的别名 |
| `fromBackpack<T>({defaultValue})` | `T?` | 从 Backpack 读取（同步） |
| `toModel<T>()` | `T` | 将 JSON 字符串转换为模型 |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | 将项目添加到集合 |
| `readCollection<T>()` | `Future<List<T>>` | 读取集合 |
| `deleteFromStorage({andFromBackpack})` | `Future` | 从存储中删除 |
| `flush({andFromBackpack})` | `Future` | deleteFromStorage 的别名 |
| `defaultValue<T>(value)` | `Future Function(bool)?` | 当键为空时设置默认值（用于 syncedOnBoot） |

<div id="storage-exceptions"></div>

## 存储异常

{{ config('app.name') }} v7 提供类型化的存储异常：

| 异常 | 描述 |
|-----------|-------------|
| `StorageException` | 基础异常，包含消息和可选键 |
| `StorageSerializationException` | 序列化对象到存储失败 |
| `StorageDeserializationException` | 反序列化存储数据失败 |
| `StorageKeyNotFoundException` | 存储键未找到 |
| `StorageTimeoutException` | 存储操作超时 |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Failed to load user: ${e.message}');
  print('Expected type: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## 旧版迁移

{{ config('app.name') }} v7 使用新的信封存储格式。如果您从 v6 升级，可以迁移旧数据：

``` dart
// Call during app initialization
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Migrated $migrated keys to new format');
```

这会将旧版格式（独立的 `_runtime_type` 键）转换为新的信封格式。迁移可以安全地多次运行——已迁移的键将被跳过。
