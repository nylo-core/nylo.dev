# Storage

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to storage")
- NyStorage
  - [Save Values](#save-values "Saving values")
  - [Read Values](#read-values "Retrieve values")
  - [Delete Values](#delete-values "Delete values")
  - [Storage Keys](#storage-keys "Storage Keys")
  - [Save/Read JSON](#save-json "Save and Read JSON")
  - [TTL (Time-to-Live)](#ttl-storage "TTL Storage")
  - [Batch Operations](#batch-operations "Batch operations")
- Collections
  - [Introduction](#introduction-to-collections "Introduction to collections")
  - [Add to Collection](#add-to-a-collection "Add to a collection")
  - [Read Collection](#read-a-collection "Read a collection")
  - [Update Collection](#update-collection "Update a collection")
  - [Delete from Collection](#delete-from-collection "Delete from collection")
- Backpack
  - [Introduction](#backpack-storage "Backpack Storage")
  - [Persist with Backpack](#persist-data-with-backpack "Persist Data with Backpack")
- [Sessions](#introduction-to-sessions "Sessions")
- Model Storage
  - [Model Save](#model-save "Model Save")
  - [Model Collections](#model-collections "Model Collections")
- [StorageKey Extension Reference](#storage-key-extension-reference "StorageKey Extension Reference")
- [Storage Exceptions](#storage-exceptions "Storage Exceptions")
- [Legacy Migration](#legacy-migration "Legacy Migration")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 provides a powerful storage system through the `NyStorage` class. It uses <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> under the hood to store data securely on the user's device.

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

## Save Values

Save values using `NyStorage.save()` or the `storageSave()` helper:

``` dart
// Using NyStorage class
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Using helper function
await storageSave("username", "Anthony");
```

### Save to Backpack Simultaneously

Store in both persistent storage and in-memory Backpack:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## Read Values

Read values with automatic type casting:

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

## Delete Values

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

Organize your storage keys in `lib/config/storage_keys.dart`:

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

### Using StorageKey Extensions

`StorageKey` is a typedef for `String`, and comes with a powerful set of extension methods:

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

## Save/Read JSON

Store and retrieve JSON data:

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

{{ config('app.name') }} v7 supports storing values with automatic expiration:

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

## Batch Operations

Efficiently handle multiple storage operations:

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

## Introduction to Collections

Collections allow you to store lists of items under a single key:

``` dart
// Add items to a collection
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Read the collection
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## Add to Collection

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

## Read Collection

``` dart
// Read collection with type
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Check if collection is empty
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## Update Collection

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

## Delete from Collection

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

`Backpack` is a lightweight in-memory storage class for quick synchronous access during a user's session. Data is **not persisted** when the app closes.

### Save to Backpack

``` dart
// Using helper
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Using Backpack directly
Backpack.instance.save('settings', {'darkMode': true});
```

### Read from Backpack

``` dart
// Using helper
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Using Backpack directly
var settings = Backpack.instance.read('settings');
```

### Delete from Backpack

``` dart
backpackDelete('user_token');

// Delete all
backpackDeleteAll();
```

### Practical Example

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

## Persist with Backpack

Store in both persistent storage and Backpack in one call:

``` dart
// Save to both
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Now accessible via Backpack (sync) and NyStorage (async)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### Sync Storage to Backpack

Load all persistent storage into Backpack on app boot:

``` dart
// In your app provider
await NyStorage.syncToBackpack();

// With overwrite option
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## Sessions

Sessions provide named, in-memory storage for grouping related data (not persisted):

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

### Persist Sessions

Sessions can be synced to persistent storage:

``` dart
// Save session to storage
await session('checkout').syncToStorage();

// Restore session from storage
await session('checkout').syncFromStorage();
```

### Session Use Cases

Sessions are ideal for:
- Multi-step forms (onboarding, checkout)
- Temporary user preferences
- Wizard/journey flows
- Shopping cart data

<div id="model-save"></div>

## Model Save

The `Model` base class provides built-in storage methods. When you define a `key` in the constructor, the model can save itself:

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

### Saving a Model

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Save to persistent storage
await user.save();

// Save to both storage and Backpack
await user.save(inBackpack: true);
```

### Reading a Model Back

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Syncing to Backpack

Load a model from storage into Backpack for synchronous access:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Model Collections

Save models to a collection:

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

## StorageKey Extension Reference

`StorageKey` is a typedef for `String`. The `NyStorageKeyExt` extension provides these methods:

| Method | Returns | Description |
|--------|---------|-------------|
| `save(value, {inBackpack})` | `Future` | Save value to storage |
| `saveJson(value, {inBackpack})` | `Future` | Save JSON value to storage |
| `read<T>({defaultValue})` | `Future<T?>` | Read value from storage |
| `readJson<T>({defaultValue})` | `Future<T?>` | Read JSON value from storage |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | Alias for read |
| `fromBackpack<T>({defaultValue})` | `T?` | Read from Backpack (sync) |
| `toModel<T>()` | `T` | Convert JSON string to model |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | Add item to collection |
| `readCollection<T>()` | `Future<List<T>>` | Read collection |
| `deleteFromStorage({andFromBackpack})` | `Future` | Delete from storage |
| `flush({andFromBackpack})` | `Future` | Alias for deleteFromStorage |
| `defaultValue<T>(value)` | `Future Function(bool)?` | Set default if key is empty (used in syncedOnBoot) |

<div id="storage-exceptions"></div>

## Storage Exceptions

{{ config('app.name') }} v7 provides typed storage exceptions:

| Exception | Description |
|-----------|-------------|
| `StorageException` | Base exception with message and optional key |
| `StorageSerializationException` | Failed to serialize object for storage |
| `StorageDeserializationException` | Failed to deserialize stored data |
| `StorageKeyNotFoundException` | Storage key was not found |
| `StorageTimeoutException` | Storage operation timed out |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Failed to load user: ${e.message}');
  print('Expected type: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## Legacy Migration

{{ config('app.name') }} v7 uses a new envelope storage format. If you're upgrading from v6, you can migrate old data:

``` dart
// Call during app initialization
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Migrated $migrated keys to new format');
```

This converts legacy format (separate `_runtime_type` keys) to the new envelope format. The migration is safe to run multiple times - already-migrated keys are skipped.
