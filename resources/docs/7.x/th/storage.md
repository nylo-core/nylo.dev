# Storage

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับ storage")
- NyStorage
  - [บันทึกค่า](#save-values "การบันทึกค่า")
  - [อ่านค่า](#read-values "การดึงค่า")
  - [ลบค่า](#delete-values "การลบค่า")
  - [Storage Keys](#storage-keys "Storage Keys")
  - [บันทึก/อ่าน JSON](#save-json "บันทึกและอ่าน JSON")
  - [TTL (Time-to-Live)](#ttl-storage "TTL Storage")
  - [การดำเนินการแบบ Batch](#batch-operations "การดำเนินการแบบ batch")
- Collections
  - [บทนำ](#introduction-to-collections "บทนำเกี่ยวกับ collections")
  - [เพิ่มไปยัง Collection](#add-to-a-collection "เพิ่มไปยัง collection")
  - [อ่าน Collection](#read-a-collection "อ่าน collection")
  - [อัปเดต Collection](#update-collection "อัปเดต collection")
  - [ลบจาก Collection](#delete-from-collection "ลบจาก collection")
- Backpack
  - [บทนำ](#backpack-storage "Backpack Storage")
  - [คงข้อมูลด้วย Backpack](#persist-data-with-backpack "คงข้อมูลด้วย Backpack")
- [Sessions](#introduction-to-sessions "Sessions")
- Model Storage
  - [การบันทึก Model](#model-save "การบันทึก Model")
  - [Model Collections](#model-collections "Model Collections")
- [อ้างอิง StorageKey Extension](#storage-key-extension-reference "อ้างอิง StorageKey Extension")
- [Storage Exceptions](#storage-exceptions "Storage Exceptions")
- [การย้ายจากเวอร์ชันเก่า](#legacy-migration "การย้ายจากเวอร์ชันเก่า")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} v7 มีระบบ storage ที่ทรงพลังผ่านคลาส `NyStorage` มันใช้ <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> ภายในเพื่อเก็บข้อมูลอย่างปลอดภัยบนอุปกรณ์ของผู้ใช้

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Save a value
await NyStorage.save("coins", 100);

// Read a value
int? coins = await NyStorage.read<int>('coins'); // 100

// Delete a value
await NyStorage.delete('coins');
```

<div id="save-values"></div>

## บันทึกค่า

บันทึกค่าโดยใช้ `NyStorage.save()` หรือตัวช่วย `storageSave()`:

``` dart
// Using NyStorage class
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Using helper function
await storageSave("username", "Anthony");
```

### บันทึกไปยัง Backpack พร้อมกัน

จัดเก็บทั้ง persistent storage และ Backpack ในหน่วยความจำ:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## อ่านค่า

อ่านค่าพร้อมการแปลงชนิดอัตโนมัติ:

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

## ลบค่า

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

## Storage Keys

จัดระเบียบ storage keys ของคุณใน `lib/config/storage_keys.dart`:

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

### การใช้ StorageKey Extensions

`StorageKey` เป็น typedef สำหรับ `String` และมาพร้อมกับชุด extension method ที่ทรงพลัง:

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

## บันทึก/อ่าน JSON

จัดเก็บและดึงข้อมูล JSON:

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

## TTL (Time-to-Live)

{{ config('app.name') }} v7 รองรับการจัดเก็บค่าที่หมดอายุอัตโนมัติ:

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

## การดำเนินการแบบ Batch

จัดการการดำเนินการ storage หลายรายการอย่างมีประสิทธิภาพ:

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

## บทนำเกี่ยวกับ Collections

Collections ช่วยให้คุณจัดเก็บรายการของไอเท็มภายใต้ key เดียว:

``` dart
// Add items to a collection
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Read the collection
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## เพิ่มไปยัง Collection

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

## อ่าน Collection

``` dart
// Read collection with type
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Check if collection is empty
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## อัปเดต Collection

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

## ลบจาก Collection

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

## Backpack Storage

`Backpack` เป็นคลาส storage ในหน่วยความจำแบบเบาสำหรับการเข้าถึงแบบ synchronous อย่างรวดเร็วระหว่าง session ของผู้ใช้ ข้อมูล**ไม่ถูกคงไว้**เมื่อแอปปิด

### บันทึกไปยัง Backpack

``` dart
// Using helper
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Using Backpack directly
Backpack.instance.save('settings', {'darkMode': true});
```

### อ่านจาก Backpack

``` dart
// Using helper
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Using Backpack directly
var settings = Backpack.instance.read('settings');
```

### ลบจาก Backpack

``` dart
backpackDelete('user_token');

// Delete all
backpackDeleteAll();
```

### ตัวอย่างการใช้งานจริง

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

## คงข้อมูลด้วย Backpack

จัดเก็บทั้ง persistent storage และ Backpack ในการเรียกเดียว:

``` dart
// Save to both
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Now accessible via Backpack (sync) and NyStorage (async)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### ซิงค์ Storage ไปยัง Backpack

โหลด persistent storage ทั้งหมดเข้า Backpack เมื่อเริ่มต้นแอป:

``` dart
// In your app provider
await NyStorage.syncToBackpack();

// With overwrite option
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## Sessions

Sessions มี storage ในหน่วยความจำแบบมีชื่อสำหรับการจัดกลุ่มข้อมูลที่เกี่ยวข้อง (ไม่ถูกคงไว้):

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

### คงข้อมูล Sessions

Sessions สามารถซิงค์ไปยัง persistent storage:

``` dart
// Save session to storage
await session('checkout').syncToStorage();

// Restore session from storage
await session('checkout').syncFromStorage();
```

### กรณีการใช้งาน Session

Sessions เหมาะสำหรับ:
- แบบฟอร์มหลายขั้นตอน (onboarding, checkout)
- การตั้งค่าผู้ใช้ชั่วคราว
- ขั้นตอน wizard/journey
- ข้อมูลตะกร้าสินค้า

<div id="model-save"></div>

## การบันทึก Model

คลาสฐาน `Model` มี method สำหรับ storage ในตัว เมื่อคุณกำหนด `key` ใน constructor model สามารถบันทึกตัวเองได้:

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

### การบันทึก Model

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Save to persistent storage
await user.save();

// Save to both storage and Backpack
await user.save(inBackpack: true);
```

### การอ่าน Model กลับ

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### การซิงค์ไปยัง Backpack

โหลด model จาก storage เข้า Backpack สำหรับการเข้าถึงแบบ synchronous:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Model Collections

บันทึก model ไปยัง collection:

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

<div id="storage-key-extension-reference"></div>

## อ้างอิง StorageKey Extension

`StorageKey` เป็น typedef สำหรับ `String` Extension `NyStorageKeyExt` มี method เหล่านี้:

| เมธอด | ค่าที่คืน | คำอธิบาย |
|--------|---------|-------------|
| `save(value, {inBackpack})` | `Future` | บันทึกค่าไปยัง storage |
| `saveJson(value, {inBackpack})` | `Future` | บันทึกค่า JSON ไปยัง storage |
| `read<T>({defaultValue})` | `Future<T?>` | อ่านค่าจาก storage |
| `readJson<T>({defaultValue})` | `Future<T?>` | อ่านค่า JSON จาก storage |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | Alias สำหรับ read |
| `fromBackpack<T>({defaultValue})` | `T?` | อ่านจาก Backpack (sync) |
| `toModel<T>()` | `T` | แปลง JSON string เป็น model |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | เพิ่มไอเท็มไปยัง collection |
| `readCollection<T>()` | `Future<List<T>>` | อ่าน collection |
| `deleteFromStorage({andFromBackpack})` | `Future` | ลบจาก storage |
| `flush({andFromBackpack})` | `Future` | Alias สำหรับ deleteFromStorage |
| `defaultValue<T>(value)` | `Future Function(bool)?` | ตั้งค่าเริ่มต้นถ้า key ว่าง (ใช้ใน syncedOnBoot) |

<div id="storage-exceptions"></div>

## Storage Exceptions

{{ config('app.name') }} v7 มี storage exception แบบมีชนิด:

| Exception | คำอธิบาย |
|-----------|-------------|
| `StorageException` | Exception พื้นฐานพร้อมข้อความและ key ที่เป็นตัวเลือก |
| `StorageSerializationException` | ล้มเหลวในการ serialize object สำหรับ storage |
| `StorageDeserializationException` | ล้มเหลวในการ deserialize ข้อมูลที่เก็บไว้ |
| `StorageKeyNotFoundException` | ไม่พบ storage key |
| `StorageTimeoutException` | การดำเนินการ storage หมดเวลา |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Failed to load user: ${e.message}');
  print('Expected type: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## การย้ายจากเวอร์ชันเก่า

{{ config('app.name') }} v7 ใช้รูปแบบ envelope storage ใหม่ หากคุณกำลังอัปเกรดจาก v6 คุณสามารถย้ายข้อมูลเก่าได้:

``` dart
// Call during app initialization
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Migrated $migrated keys to new format');
```

สิ่งนี้แปลงรูปแบบเก่า (key `_runtime_type` แยกต่างหาก) เป็นรูปแบบ envelope ใหม่ การย้ายสามารถรันได้หลายครั้งอย่างปลอดภัย - key ที่ย้ายแล้วจะถูกข้าม
