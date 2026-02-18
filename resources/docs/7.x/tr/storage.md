# Depolama

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- NyStorage
  - [Değerleri Kaydetme](#save-values "Değerleri Kaydetme")
  - [Değerleri Okuma](#read-values "Değerleri Okuma")
  - [Değerleri Silme](#delete-values "Değerleri Silme")
  - [Depolama Anahtarları](#storage-keys "Depolama Anahtarları")
  - [JSON Kaydetme/Okuma](#save-json "JSON Kaydetme/Okuma")
  - [TTL (Yaşam Süresi)](#ttl-storage "TTL (Yaşam Süresi)")
  - [Toplu İşlemler](#batch-operations "Toplu İşlemler")
- Koleksiyonlar
  - [Giriş](#introduction-to-collections "Giriş")
  - [Koleksiyona Ekleme](#add-to-a-collection "Koleksiyona Ekleme")
  - [Koleksiyonu Okuma](#read-a-collection "Koleksiyonu Okuma")
  - [Koleksiyonu Güncelleme](#update-collection "Koleksiyonu Güncelleme")
  - [Koleksiyondan Silme](#delete-from-collection "Koleksiyondan Silme")
- Backpack
  - [Giriş](#backpack-storage "Giriş")
  - [Backpack ile Kalıcı Depolama](#persist-data-with-backpack "Backpack ile Kalıcı Depolama")
- [Oturumlar](#introduction-to-sessions "Oturumlar")
- Model Depolama
  - [Model Kaydetme](#model-save "Model Kaydetme")
  - [Model Koleksiyonları](#model-collections "Model Koleksiyonları")
- [StorageKey Uzantı Referansı](#storage-key-extension-reference "StorageKey Uzantı Referansı")
- [Depolama İstisnaları](#storage-exceptions "Depolama İstisnaları")
- [Eski Sürüm Göçü](#legacy-migration "Eski Sürüm Göçü")

<div id="introduction"></div>

## Giriş

{{ config('app.name') }} v7, `NyStorage` sınıfı aracılığıyla güçlü bir depolama sistemi sunar. Kullanıcının cihazında verileri güvenli bir şekilde depolamak için arka planda <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> kullanır.

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

## Değerleri Kaydetme

`NyStorage.save()` veya `storageSave()` yardımcısını kullanarak değerleri kaydedin:

``` dart
// Using NyStorage class
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Using helper function
await storageSave("username", "Anthony");
```

### Aynı Anda Backpack'e Kaydetme

Hem kalıcı depolamaya hem de bellek içi Backpack'e kaydedin:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## Değerleri Okuma

Otomatik tür dönüşümüyle değerleri okuyun:

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

## Değerleri Silme

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

## Depolama Anahtarları

Depolama anahtarlarınızı `lib/config/storage_keys.dart` dosyasında düzenleyin:

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

### StorageKey Uzantılarını Kullanma

`StorageKey`, `String` için bir typedef'tir ve güçlü bir uzantı metotları setine sahiptir:

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

## JSON Kaydetme/Okuma

JSON verileri depolayın ve alın:

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

## TTL (Yaşam Süresi)

{{ config('app.name') }} v7, otomatik süre dolumu olan değerlerin depolanmasını destekler:

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

## Toplu İşlemler

Birden fazla depolama işlemini verimli bir şekilde yönetin:

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

## Koleksiyonlara Giriş

Koleksiyonlar, tek bir anahtar altında öğe listeleri depolamanıza olanak tanır:

``` dart
// Add items to a collection
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Read the collection
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## Koleksiyona Ekleme

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

## Koleksiyonu Okuma

``` dart
// Read collection with type
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Check if collection is empty
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## Koleksiyonu Güncelleme

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

## Koleksiyondan Silme

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

## Backpack Depolama

`Backpack`, kullanıcı oturumu sırasında hızlı senkron erişim için hafif bir bellek içi depolama sınıfıdır. Uygulama kapandığında veriler **kalıcı olmaz**.

### Backpack'e Kaydetme

``` dart
// Using helper
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Using Backpack directly
Backpack.instance.save('settings', {'darkMode': true});
```

### Backpack'ten Okuma

``` dart
// Using helper
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Using Backpack directly
var settings = Backpack.instance.read('settings');
```

### Backpack'ten Silme

``` dart
backpackDelete('user_token');

// Delete all
backpackDeleteAll();
```

### Pratik Örnek

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

## Backpack ile Kalıcı Depolama

Tek bir çağrıda hem kalıcı depolamaya hem de Backpack'e kaydedin:

``` dart
// Save to both
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Now accessible via Backpack (sync) and NyStorage (async)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### Depolamayı Backpack ile Senkronize Etme

Uygulama başlangıcında tüm kalıcı depolamayı Backpack'e yükleyin:

``` dart
// In your app provider
await NyStorage.syncToBackpack();

// With overwrite option
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## Oturumlar

Oturumlar, ilgili verileri gruplamak için adlandırılmış, bellek içi depolama sağlar (kalıcı değildir):

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

### Oturumları Kalıcı Yapma

Oturumlar kalıcı depolamaya senkronize edilebilir:

``` dart
// Save session to storage
await session('checkout').syncToStorage();

// Restore session from storage
await session('checkout').syncFromStorage();
```

### Oturum Kullanım Alanları

Oturumlar şunlar için idealdir:
- Çok adımlı formlar (tanıtım, ödeme)
- Geçici kullanıcı tercihleri
- Sihirbaz/yolculuk akışları
- Alışveriş sepeti verileri

<div id="model-save"></div>

## Model Kaydetme

`Model` temel sınıfı yerleşik depolama metotları sunar. Yapıcıda bir `key` tanımladığınızda, model kendini kaydedebilir:

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

### Model Kaydetme

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Save to persistent storage
await user.save();

// Save to both storage and Backpack
await user.save(inBackpack: true);
```

### Model Geri Okuma

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Backpack ile Senkronize Etme

Senkron erişim için bir modeli depolamadan Backpack'e yükleyin:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Model Koleksiyonları

Modelleri bir koleksiyona kaydedin:

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

## StorageKey Uzantı Referansı

`StorageKey`, `String` için bir typedef'tir. `NyStorageKeyExt` uzantısı şu metotları sunar:

| Metot | Döndürür | Açıklama |
|-------|---------|----------|
| `save(value, {inBackpack})` | `Future` | Değeri depolamaya kaydet |
| `saveJson(value, {inBackpack})` | `Future` | JSON değerini depolamaya kaydet |
| `read<T>({defaultValue})` | `Future<T?>` | Depolamadan değer oku |
| `readJson<T>({defaultValue})` | `Future<T?>` | Depolamadan JSON değeri oku |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | read için takma ad |
| `fromBackpack<T>({defaultValue})` | `T?` | Backpack'ten oku (senkron) |
| `toModel<T>()` | `T` | JSON dizesini modele dönüştür |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | Koleksiyona öğe ekle |
| `readCollection<T>()` | `Future<List<T>>` | Koleksiyonu oku |
| `deleteFromStorage({andFromBackpack})` | `Future` | Depolamadan sil |
| `flush({andFromBackpack})` | `Future` | deleteFromStorage için takma ad |
| `defaultValue<T>(value)` | `Future Function(bool)?` | Anahtar boşsa varsayılan ayarla (syncedOnBoot'ta kullanılır) |

<div id="storage-exceptions"></div>

## Depolama İstisnaları

{{ config('app.name') }} v7, türlenmiş depolama istisnaları sunar:

| İstisna | Açıklama |
|---------|----------|
| `StorageException` | Mesaj ve isteğe bağlı anahtarla temel istisna |
| `StorageSerializationException` | Depolama için nesne serileştirme başarısız |
| `StorageDeserializationException` | Depolanan verinin serileştirmesini geri alma başarısız |
| `StorageKeyNotFoundException` | Depolama anahtarı bulunamadı |
| `StorageTimeoutException` | Depolama işlemi zaman aşımına uğradı |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Failed to load user: ${e.message}');
  print('Expected type: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## Eski Sürüm Göçü

{{ config('app.name') }} v7, yeni bir zarf depolama formatı kullanır. v6'dan yükseltme yapıyorsanız, eski verileri göçürebilirsiniz:

``` dart
// Call during app initialization
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Migrated $migrated keys to new format');
```

Bu, eski formatı (ayrı `_runtime_type` anahtarları) yeni zarf formatına dönüştürür. Göç birden fazla kez güvenle çalıştırılabilir - zaten göç edilmiş anahtarlar atlanır.
