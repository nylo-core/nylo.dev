# Speicher

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung zum Speicher")
- NyStorage
  - [Werte speichern](#save-values "Werte speichern")
  - [Werte lesen](#read-values "Werte abrufen")
  - [Werte loeschen](#delete-values "Werte loeschen")
  - [Storage Keys](#storage-keys "Storage Keys")
  - [JSON speichern/lesen](#save-json "JSON speichern und lesen")
  - [TTL (Time-to-Live)](#ttl-storage "TTL-Speicher")
  - [Batch-Operationen](#batch-operations "Batch-Operationen")
- Collections
  - [Einleitung](#introduction-to-collections "Einleitung zu Collections")
  - [Zu einer Collection hinzufuegen](#add-to-a-collection "Zu einer Collection hinzufuegen")
  - [Collection lesen](#read-a-collection "Eine Collection lesen")
  - [Collection aktualisieren](#update-collection "Eine Collection aktualisieren")
  - [Aus Collection loeschen](#delete-from-collection "Aus Collection loeschen")
- Backpack
  - [Einleitung](#backpack-storage "Backpack-Speicher")
  - [Daten mit Backpack persistieren](#persist-data-with-backpack "Daten mit Backpack persistieren")
- [Sessions](#introduction-to-sessions "Sessions")
- Model-Speicher
  - [Model speichern](#model-save "Model speichern")
  - [Model Collections](#model-collections "Model Collections")
- [StorageKey Extension-Referenz](#storage-key-extension-reference "StorageKey Extension-Referenz")
- [Speicher-Ausnahmen](#storage-exceptions "Speicher-Ausnahmen")
- [Legacy-Migration](#legacy-migration "Legacy-Migration")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} v7 bietet ein leistungsstarkes Speichersystem ueber die Klasse `NyStorage`. Es verwendet unter der Haube <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a>, um Daten sicher auf dem Geraet des Benutzers zu speichern.

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

## Werte speichern

Speichern Sie Werte mit `NyStorage.save()` oder dem `storageSave()`-Helfer:

``` dart
// Using NyStorage class
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Using helper function
await storageSave("username", "Anthony");
```

### Gleichzeitig in Backpack speichern

Speichern Sie sowohl im persistenten Speicher als auch im In-Memory-Backpack:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## Werte lesen

Lesen Sie Werte mit automatischer Typkonvertierung:

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

## Werte loeschen

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

Organisieren Sie Ihre Speicherschluessel in `lib/config/storage_keys.dart`:

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

### StorageKey-Extensions verwenden

`StorageKey` ist ein Typedef fuer `String` und verfuegt ueber eine leistungsstarke Sammlung von Extension-Methoden:

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

## JSON speichern/lesen

Speichern und Abrufen von JSON-Daten:

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

{{ config('app.name') }} v7 unterstuetzt das Speichern von Werten mit automatischem Ablauf:

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

## Batch-Operationen

Effizientes Verarbeiten mehrerer Speicheroperationen:

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

## Einleitung zu Collections

Collections ermoeglichen es Ihnen, Listen von Elementen unter einem einzelnen Schluessel zu speichern:

``` dart
// Add items to a collection
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Read the collection
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## Zu einer Collection hinzufuegen

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

## Collection lesen

``` dart
// Read collection with type
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Check if collection is empty
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## Collection aktualisieren

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

## Aus Collection loeschen

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

## Backpack-Speicher

`Backpack` ist eine leichtgewichtige In-Memory-Speicherklasse fuer schnellen synchronen Zugriff waehrend der Benutzersitzung. Daten werden **nicht persistiert**, wenn die App geschlossen wird.

### In Backpack speichern

``` dart
// Using helper
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Using Backpack directly
Backpack.instance.save('settings', {'darkMode': true});
```

### Aus Backpack lesen

``` dart
// Using helper
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Using Backpack directly
var settings = Backpack.instance.read('settings');
```

### Aus Backpack loeschen

``` dart
backpackDelete('user_token');

// Delete all
backpackDeleteAll();
```

### Praktisches Beispiel

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

## Daten mit Backpack persistieren

In einem Aufruf sowohl im persistenten Speicher als auch in Backpack speichern:

``` dart
// Save to both
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Now accessible via Backpack (sync) and NyStorage (async)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### Speicher mit Backpack synchronisieren

Laden Sie beim App-Start den gesamten persistenten Speicher in Backpack:

``` dart
// In your app provider
await NyStorage.syncToBackpack();

// With overwrite option
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## Sessions

Sessions bieten benannten, In-Memory-Speicher zum Gruppieren zusammengehoeriger Daten (nicht persistiert):

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

### Sessions persistieren

Sessions koennen mit dem persistenten Speicher synchronisiert werden:

``` dart
// Save session to storage
await session('checkout').syncToStorage();

// Restore session from storage
await session('checkout').syncFromStorage();
```

### Anwendungsfaelle fuer Sessions

Sessions sind ideal fuer:
- Mehrstufige Formulare (Onboarding, Checkout)
- Temporaere Benutzereinstellungen
- Wizard-/Journey-Ablaeufe
- Warenkorb-Daten

<div id="model-save"></div>

## Model speichern

Die `Model`-Basisklasse bietet integrierte Speichermethoden. Wenn Sie einen `key` im Konstruktor definieren, kann sich das Model selbst speichern:

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

### Ein Model speichern

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Save to persistent storage
await user.save();

// Save to both storage and Backpack
await user.save(inBackpack: true);
```

### Ein Model zuruecklesen

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Mit Backpack synchronisieren

Laden Sie ein Model aus dem Speicher in Backpack fuer synchronen Zugriff:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Model Collections

Speichern Sie Models in einer Collection:

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

## StorageKey Extension-Referenz

`StorageKey` ist ein Typedef fuer `String`. Die `NyStorageKeyExt`-Extension bietet folgende Methoden:

| Methode | Rueckgabetyp | Beschreibung |
|---------|-------------|-------------|
| `save(value, {inBackpack})` | `Future` | Wert im Speicher speichern |
| `saveJson(value, {inBackpack})` | `Future` | JSON-Wert im Speicher speichern |
| `read<T>({defaultValue})` | `Future<T?>` | Wert aus dem Speicher lesen |
| `readJson<T>({defaultValue})` | `Future<T?>` | JSON-Wert aus dem Speicher lesen |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | Alias fuer read |
| `fromBackpack<T>({defaultValue})` | `T?` | Aus Backpack lesen (synchron) |
| `toModel<T>()` | `T` | JSON-String in Model konvertieren |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | Element zur Collection hinzufuegen |
| `readCollection<T>()` | `Future<List<T>>` | Collection lesen |
| `deleteFromStorage({andFromBackpack})` | `Future` | Aus dem Speicher loeschen |
| `flush({andFromBackpack})` | `Future` | Alias fuer deleteFromStorage |
| `defaultValue<T>(value)` | `Future Function(bool)?` | Standardwert setzen, wenn Schluessel leer ist (verwendet in syncedOnBoot) |

<div id="storage-exceptions"></div>

## Speicher-Ausnahmen

{{ config('app.name') }} v7 bietet typisierte Speicher-Ausnahmen:

| Ausnahme | Beschreibung |
|----------|-------------|
| `StorageException` | Basis-Ausnahme mit Nachricht und optionalem Schluessel |
| `StorageSerializationException` | Serialisierung des Objekts fuer den Speicher fehlgeschlagen |
| `StorageDeserializationException` | Deserialisierung der gespeicherten Daten fehlgeschlagen |
| `StorageKeyNotFoundException` | Speicherschluessel wurde nicht gefunden |
| `StorageTimeoutException` | Speicheroperation hat das Zeitlimit ueberschritten |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Failed to load user: ${e.message}');
  print('Expected type: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## Legacy-Migration

{{ config('app.name') }} v7 verwendet ein neues Envelope-Speicherformat. Wenn Sie von v6 aktualisieren, koennen Sie alte Daten migrieren:

``` dart
// Call during app initialization
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Migrated $migrated keys to new format');
```

Dies konvertiert das Legacy-Format (separate `_runtime_type`-Schluessel) in das neue Envelope-Format. Die Migration kann sicher mehrfach ausgefuehrt werden - bereits migrierte Schluessel werden uebersprungen.
