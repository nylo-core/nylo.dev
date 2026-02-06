# Stockage

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction au stockage")
- NyStorage
  - [Enregistrer des valeurs](#save-values "Enregistrer des valeurs")
  - [Lire des valeurs](#read-values "Lire des valeurs")
  - [Supprimer des valeurs](#delete-values "Supprimer des valeurs")
  - [Cles de stockage](#storage-keys "Cles de stockage")
  - [Enregistrer/Lire du JSON](#save-json "Enregistrer et lire du JSON")
  - [TTL (Duree de vie)](#ttl-storage "Stockage TTL")
  - [Operations par lots](#batch-operations "Operations par lots")
- Collections
  - [Introduction](#introduction-to-collections "Introduction aux collections")
  - [Ajouter a une collection](#add-to-a-collection "Ajouter a une collection")
  - [Lire une collection](#read-a-collection "Lire une collection")
  - [Mettre a jour une collection](#update-collection "Mettre a jour une collection")
  - [Supprimer d'une collection](#delete-from-collection "Supprimer d'une collection")
- Backpack
  - [Introduction](#backpack-storage "Stockage Backpack")
  - [Persister avec Backpack](#persist-data-with-backpack "Persister les donnees avec Backpack")
- [Sessions](#introduction-to-sessions "Sessions")
- Stockage de modeles
  - [Enregistrer un modele](#model-save "Enregistrer un modele")
  - [Collections de modeles](#model-collections "Collections de modeles")
- [Reference des extensions StorageKey](#storage-key-extension-reference "Reference des extensions StorageKey")
- [Exceptions de stockage](#storage-exceptions "Exceptions de stockage")
- [Migration de l'ancien format](#legacy-migration "Migration de l'ancien format")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 fournit un systeme de stockage puissant via la classe `NyStorage`. Il utilise <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> en arriere-plan pour stocker les donnees de maniere securisee sur l'appareil de l'utilisateur.

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

## Enregistrer des valeurs

Enregistrez des valeurs en utilisant `NyStorage.save()` ou le helper `storageSave()` :

``` dart
// Using NyStorage class
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Using helper function
await storageSave("username", "Anthony");
```

### Enregistrer dans Backpack simultanement

Stockez a la fois dans le stockage persistant et dans le Backpack en memoire :

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## Lire des valeurs

Lisez des valeurs avec conversion de type automatique :

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

## Supprimer des valeurs

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

## Cles de stockage

Organisez vos cles de stockage dans `lib/config/storage_keys.dart` :

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

### Utiliser les extensions StorageKey

`StorageKey` est un typedef pour `String`, et est accompagne d'un ensemble puissant de methodes d'extension :

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

## Enregistrer/Lire du JSON

Stockez et recuperez des donnees JSON :

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

## TTL (Duree de vie)

{{ config('app.name') }} v7 prend en charge le stockage de valeurs avec expiration automatique :

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

## Operations par lots

Gerez efficacement plusieurs operations de stockage :

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

## Introduction aux collections

Les collections vous permettent de stocker des listes d'elements sous une seule cle :

``` dart
// Add items to a collection
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Read the collection
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## Ajouter a une collection

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

## Lire une collection

``` dart
// Read collection with type
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Check if collection is empty
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## Mettre a jour une collection

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

## Supprimer d'une collection

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

## Stockage Backpack

`Backpack` est une classe de stockage en memoire legere pour un acces synchrone rapide pendant la session d'un utilisateur. Les donnees ne sont **pas persistees** lorsque l'application se ferme.

### Enregistrer dans Backpack

``` dart
// Using helper
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Using Backpack directly
Backpack.instance.save('settings', {'darkMode': true});
```

### Lire depuis Backpack

``` dart
// Using helper
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Using Backpack directly
var settings = Backpack.instance.read('settings');
```

### Supprimer de Backpack

``` dart
backpackDelete('user_token');

// Delete all
backpackDeleteAll();
```

### Exemple pratique

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

## Persister avec Backpack

Stockez a la fois dans le stockage persistant et Backpack en un seul appel :

``` dart
// Save to both
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Now accessible via Backpack (sync) and NyStorage (async)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### Synchroniser le stockage avec Backpack

Chargez tout le stockage persistant dans Backpack au demarrage de l'application :

``` dart
// In your app provider
await NyStorage.syncToBackpack();

// With overwrite option
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## Sessions

Les sessions fournissent un stockage en memoire nomme pour regrouper des donnees liees (non persistees) :

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

### Persister les sessions

Les sessions peuvent etre synchronisees avec le stockage persistant :

``` dart
// Save session to storage
await session('checkout').syncToStorage();

// Restore session from storage
await session('checkout').syncFromStorage();
```

### Cas d'utilisation des sessions

Les sessions sont ideales pour :
- Les formulaires multi-etapes (integration, paiement)
- Les preferences utilisateur temporaires
- Les flux d'assistant/parcours
- Les donnees du panier d'achat

<div id="model-save"></div>

## Enregistrer un modele

La classe de base `Model` fournit des methodes de stockage integrees. Lorsque vous definissez une `key` dans le constructeur, le modele peut s'enregistrer lui-meme :

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

### Enregistrer un modele

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Save to persistent storage
await user.save();

// Save to both storage and Backpack
await user.save(inBackpack: true);
```

### Relire un modele

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Synchroniser avec Backpack

Chargez un modele depuis le stockage dans Backpack pour un acces synchrone :

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Collections de modeles

Enregistrez des modeles dans une collection :

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

## Reference des extensions StorageKey

`StorageKey` est un typedef pour `String`. L'extension `NyStorageKeyExt` fournit ces methodes :

| Methode | Retour | Description |
|---------|--------|-------------|
| `save(value, {inBackpack})` | `Future` | Enregistrer une valeur dans le stockage |
| `saveJson(value, {inBackpack})` | `Future` | Enregistrer une valeur JSON dans le stockage |
| `read<T>({defaultValue})` | `Future<T?>` | Lire une valeur depuis le stockage |
| `readJson<T>({defaultValue})` | `Future<T?>` | Lire une valeur JSON depuis le stockage |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | Alias pour read |
| `fromBackpack<T>({defaultValue})` | `T?` | Lire depuis Backpack (synchrone) |
| `toModel<T>()` | `T` | Convertir une chaine JSON en modele |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | Ajouter un element a une collection |
| `readCollection<T>()` | `Future<List<T>>` | Lire une collection |
| `deleteFromStorage({andFromBackpack})` | `Future` | Supprimer du stockage |
| `flush({andFromBackpack})` | `Future` | Alias pour deleteFromStorage |
| `defaultValue<T>(value)` | `Future Function(bool)?` | Definir une valeur par defaut si la cle est vide (utilise dans syncedOnBoot) |

<div id="storage-exceptions"></div>

## Exceptions de stockage

{{ config('app.name') }} v7 fournit des exceptions de stockage typees :

| Exception | Description |
|-----------|-------------|
| `StorageException` | Exception de base avec message et cle optionnelle |
| `StorageSerializationException` | Echec de la serialisation de l'objet pour le stockage |
| `StorageDeserializationException` | Echec de la deserialisation des donnees stockees |
| `StorageKeyNotFoundException` | Cle de stockage introuvable |
| `StorageTimeoutException` | Delai d'attente depasse pour l'operation de stockage |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Failed to load user: ${e.message}');
  print('Expected type: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## Migration de l'ancien format

{{ config('app.name') }} v7 utilise un nouveau format de stockage par enveloppe. Si vous effectuez une mise a niveau depuis la v6, vous pouvez migrer les anciennes donnees :

``` dart
// Call during app initialization
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Migrated $migrated keys to new format');
```

Cela convertit l'ancien format (cles `_runtime_type` separees) vers le nouveau format par enveloppe. La migration peut etre executee plusieurs fois en toute securite — les cles deja migrees sont ignorees.
