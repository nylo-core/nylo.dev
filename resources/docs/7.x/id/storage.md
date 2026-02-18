# Penyimpanan

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- NyStorage
  - [Menyimpan Nilai](#save-values "Menyimpan Nilai")
  - [Membaca Nilai](#read-values "Membaca Nilai")
  - [Menghapus Nilai](#delete-values "Menghapus Nilai")
  - [Kunci Penyimpanan](#storage-keys "Kunci Penyimpanan")
  - [Simpan/Baca JSON](#save-json "Simpan/Baca JSON")
  - [TTL (Time-to-Live)](#ttl-storage "TTL (Time-to-Live)")
  - [Operasi Batch](#batch-operations "Operasi Batch")
- Koleksi
  - [Pengantar](#introduction-to-collections "Pengantar")
  - [Menambah ke Koleksi](#add-to-a-collection "Menambah ke Koleksi")
  - [Membaca Koleksi](#read-a-collection "Membaca Koleksi")
  - [Memperbarui Koleksi](#update-collection "Memperbarui Koleksi")
  - [Menghapus dari Koleksi](#delete-from-collection "Menghapus dari Koleksi")
- Backpack
  - [Pengantar](#backpack-storage "Pengantar")
  - [Persist dengan Backpack](#persist-data-with-backpack "Persist dengan Backpack")
- [Sesi](#introduction-to-sessions "Sesi")
- Penyimpanan Model
  - [Simpan Model](#model-save "Simpan Model")
  - [Koleksi Model](#model-collections "Koleksi Model")
- [Referensi Ekstensi StorageKey](#storage-key-extension-reference "Referensi Ekstensi StorageKey")
- [Exception Penyimpanan](#storage-exceptions "Exception Penyimpanan")
- [Migrasi Legacy](#legacy-migration "Migrasi Legacy")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} v7 menyediakan sistem penyimpanan yang powerful melalui class `NyStorage`. Secara internal menggunakan <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> untuk menyimpan data secara aman di perangkat pengguna.

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

## Menyimpan Nilai

Simpan nilai menggunakan `NyStorage.save()` atau helper `storageSave()`:

``` dart
// Using NyStorage class
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Using helper function
await storageSave("username", "Anthony");
```

### Simpan ke Backpack Secara Bersamaan

Simpan di penyimpanan persisten dan Backpack dalam memori sekaligus:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## Membaca Nilai

Baca nilai dengan type casting otomatis:

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

## Menghapus Nilai

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

## Kunci Penyimpanan

Atur kunci penyimpanan Anda di `lib/config/storage_keys.dart`:

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

### Menggunakan Ekstensi StorageKey

`StorageKey` adalah typedef untuk `String`, dan dilengkapi dengan sekumpulan metode ekstensi yang powerful:

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

## Simpan/Baca JSON

Simpan dan ambil data JSON:

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

{{ config('app.name') }} v7 mendukung penyimpanan nilai dengan kedaluwarsa otomatis:

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

## Operasi Batch

Tangani beberapa operasi penyimpanan secara efisien:

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

## Pengantar Koleksi

Koleksi memungkinkan Anda menyimpan daftar item di bawah satu kunci:

``` dart
// Add items to a collection
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Read the collection
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## Menambah ke Koleksi

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

## Membaca Koleksi

``` dart
// Read collection with type
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Check if collection is empty
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## Memperbarui Koleksi

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

## Menghapus dari Koleksi

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

## Penyimpanan Backpack

`Backpack` adalah class penyimpanan dalam memori yang ringan untuk akses sinkron cepat selama sesi pengguna. Data **tidak disimpan** ketika aplikasi ditutup.

### Simpan ke Backpack

``` dart
// Using helper
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Using Backpack directly
Backpack.instance.save('settings', {'darkMode': true});
```

### Baca dari Backpack

``` dart
// Using helper
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Using Backpack directly
var settings = Backpack.instance.read('settings');
```

### Hapus dari Backpack

``` dart
backpackDelete('user_token');

// Delete all
backpackDeleteAll();
```

### Contoh Praktis

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

## Persist dengan Backpack

Simpan di penyimpanan persisten dan Backpack dalam satu panggilan:

``` dart
// Save to both
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Now accessible via Backpack (sync) and NyStorage (async)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### Sinkronisasi Storage ke Backpack

Muat semua penyimpanan persisten ke Backpack saat boot aplikasi:

``` dart
// In your app provider
await NyStorage.syncToBackpack();

// With overwrite option
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## Sesi

Sesi menyediakan penyimpanan dalam memori dengan nama untuk mengelompokkan data terkait (tidak disimpan):

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

### Persist Sesi

Sesi dapat disinkronkan ke penyimpanan persisten:

``` dart
// Save session to storage
await session('checkout').syncToStorage();

// Restore session from storage
await session('checkout').syncFromStorage();
```

### Kasus Penggunaan Sesi

Sesi ideal untuk:
- Form multi-langkah (onboarding, checkout)
- Preferensi pengguna sementara
- Alur wizard/journey
- Data keranjang belanja

<div id="model-save"></div>

## Simpan Model

Class dasar `Model` menyediakan metode penyimpanan bawaan. Ketika Anda mendefinisikan `key` di constructor, model dapat menyimpan dirinya sendiri:

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

### Menyimpan Model

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Save to persistent storage
await user.save();

// Save to both storage and Backpack
await user.save(inBackpack: true);
```

### Membaca Kembali Model

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Sinkronisasi ke Backpack

Muat model dari penyimpanan ke Backpack untuk akses sinkron:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Koleksi Model

Simpan model ke koleksi:

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

## Referensi Ekstensi StorageKey

`StorageKey` adalah typedef untuk `String`. Ekstensi `NyStorageKeyExt` menyediakan metode-metode ini:

| Metode | Return | Deskripsi |
|--------|---------|-------------|
| `save(value, {inBackpack})` | `Future` | Simpan nilai ke penyimpanan |
| `saveJson(value, {inBackpack})` | `Future` | Simpan nilai JSON ke penyimpanan |
| `read<T>({defaultValue})` | `Future<T?>` | Baca nilai dari penyimpanan |
| `readJson<T>({defaultValue})` | `Future<T?>` | Baca nilai JSON dari penyimpanan |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | Alias untuk read |
| `fromBackpack<T>({defaultValue})` | `T?` | Baca dari Backpack (sinkron) |
| `toModel<T>()` | `T` | Konversi string JSON ke model |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | Tambah item ke koleksi |
| `readCollection<T>()` | `Future<List<T>>` | Baca koleksi |
| `deleteFromStorage({andFromBackpack})` | `Future` | Hapus dari penyimpanan |
| `flush({andFromBackpack})` | `Future` | Alias untuk deleteFromStorage |
| `defaultValue<T>(value)` | `Future Function(bool)?` | Atur default jika kunci kosong (digunakan di syncedOnBoot) |

<div id="storage-exceptions"></div>

## Exception Penyimpanan

{{ config('app.name') }} v7 menyediakan exception penyimpanan yang bertipe:

| Exception | Deskripsi |
|-----------|-------------|
| `StorageException` | Exception dasar dengan pesan dan kunci opsional |
| `StorageSerializationException` | Gagal melakukan serialisasi objek untuk penyimpanan |
| `StorageDeserializationException` | Gagal melakukan deserialisasi data tersimpan |
| `StorageKeyNotFoundException` | Kunci penyimpanan tidak ditemukan |
| `StorageTimeoutException` | Operasi penyimpanan timeout |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Failed to load user: ${e.message}');
  print('Expected type: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## Migrasi Legacy

{{ config('app.name') }} v7 menggunakan format penyimpanan envelope baru. Jika Anda mengupgrade dari v6, Anda dapat memigrasikan data lama:

``` dart
// Call during app initialization
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Migrated $migrated keys to new format');
```

Ini mengkonversi format legacy (kunci `_runtime_type` terpisah) ke format envelope baru. Migrasi aman untuk dijalankan berkali-kali - kunci yang sudah dimigrasi akan dilewati.
