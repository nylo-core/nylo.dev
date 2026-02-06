# Storage

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione allo storage")
- NyStorage
  - [Salvare Valori](#save-values "Salvare valori")
  - [Leggere Valori](#read-values "Leggere valori")
  - [Eliminare Valori](#delete-values "Eliminare valori")
  - [Chiavi di Storage](#storage-keys "Chiavi di Storage")
  - [Salvare/Leggere JSON](#save-json "Salvare e Leggere JSON")
  - [TTL (Time-to-Live)](#ttl-storage "TTL Storage")
  - [Operazioni Batch](#batch-operations "Operazioni batch")
- Collezioni
  - [Introduzione](#introduction-to-collections "Introduzione alle collezioni")
  - [Aggiungere a una Collezione](#add-to-a-collection "Aggiungere a una collezione")
  - [Leggere una Collezione](#read-a-collection "Leggere una collezione")
  - [Aggiornare una Collezione](#update-collection "Aggiornare una collezione")
  - [Eliminare da una Collezione](#delete-from-collection "Eliminare da una collezione")
- Backpack
  - [Introduzione](#backpack-storage "Storage Backpack")
  - [Persistere con Backpack](#persist-data-with-backpack "Persistere i Dati con Backpack")
- [Sessioni](#introduction-to-sessions "Sessioni")
- Storage dei Modelli
  - [Salvataggio del Modello](#model-save "Salvataggio del Modello")
  - [Collezioni di Modelli](#model-collections "Collezioni di Modelli")
- [Riferimento Estensioni StorageKey](#storage-key-extension-reference "Riferimento Estensioni StorageKey")
- [Eccezioni Storage](#storage-exceptions "Eccezioni Storage")
- [Migrazione Legacy](#legacy-migration "Migrazione Legacy")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} v7 fornisce un potente sistema di storage attraverso la classe `NyStorage`. Utilizza <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> sotto il cofano per memorizzare i dati in modo sicuro sul dispositivo dell'utente.

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

## Salvare Valori

Salva i valori utilizzando `NyStorage.save()` o l'helper `storageSave()`:

``` dart
// Using NyStorage class
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Using helper function
await storageSave("username", "Anthony");
```

### Salvare contemporaneamente nel Backpack

Memorizza sia nello storage persistente che nel Backpack in memoria:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## Leggere Valori

Leggi i valori con conversione automatica del tipo:

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

## Eliminare Valori

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

## Chiavi di Storage

Organizza le tue chiavi di storage in `lib/config/storage_keys.dart`:

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

### Utilizzo delle Estensioni StorageKey

`StorageKey` è un typedef per `String`, e viene fornito con un potente set di metodi di estensione:

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

## Salvare/Leggere JSON

Memorizza e recupera dati JSON:

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

{{ config('app.name') }} v7 supporta la memorizzazione di valori con scadenza automatica:

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

## Operazioni Batch

Gestisci efficientemente più operazioni di storage:

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

## Introduzione alle Collezioni

Le collezioni ti permettono di memorizzare liste di elementi sotto una singola chiave:

``` dart
// Add items to a collection
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Read the collection
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## Aggiungere a una Collezione

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

## Leggere una Collezione

``` dart
// Read collection with type
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Check if collection is empty
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## Aggiornare una Collezione

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

## Eliminare da una Collezione

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

## Storage Backpack

`Backpack` è una classe di storage leggera in memoria per un accesso sincrono rapido durante la sessione dell'utente. I dati **non vengono persistiti** quando l'app viene chiusa.

### Salvare nel Backpack

``` dart
// Using helper
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Using Backpack directly
Backpack.instance.save('settings', {'darkMode': true});
```

### Leggere dal Backpack

``` dart
// Using helper
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Using Backpack directly
var settings = Backpack.instance.read('settings');
```

### Eliminare dal Backpack

``` dart
backpackDelete('user_token');

// Delete all
backpackDeleteAll();
```

### Esempio Pratico

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

## Persistere con Backpack

Memorizza sia nello storage persistente che nel Backpack in un'unica chiamata:

``` dart
// Save to both
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Now accessible via Backpack (sync) and NyStorage (async)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### Sincronizzare lo Storage nel Backpack

Carica tutto lo storage persistente nel Backpack all'avvio dell'app:

``` dart
// In your app provider
await NyStorage.syncToBackpack();

// With overwrite option
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## Sessioni

Le sessioni forniscono storage in memoria nominato per raggruppare dati correlati (non persistiti):

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

### Persistere le Sessioni

Le sessioni possono essere sincronizzate nello storage persistente:

``` dart
// Save session to storage
await session('checkout').syncToStorage();

// Restore session from storage
await session('checkout').syncFromStorage();
```

### Casi d'Uso delle Sessioni

Le sessioni sono ideali per:
- Form a più passaggi (onboarding, checkout)
- Preferenze utente temporanee
- Flussi wizard/journey
- Dati del carrello

<div id="model-save"></div>

## Salvataggio del Modello

La classe base `Model` fornisce metodi di storage integrati. Quando definisci una `key` nel costruttore, il modello può salvarsi da solo:

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

### Salvare un Modello

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Save to persistent storage
await user.save();

// Save to both storage and Backpack
await user.save(inBackpack: true);
```

### Leggere un Modello

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Sincronizzare nel Backpack

Carica un modello dallo storage nel Backpack per un accesso sincrono:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Collezioni di Modelli

Salva modelli in una collezione:

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

## Riferimento Estensioni StorageKey

`StorageKey` è un typedef per `String`. L'estensione `NyStorageKeyExt` fornisce questi metodi:

| Metodo | Restituisce | Descrizione |
|--------|-------------|-------------|
| `save(value, {inBackpack})` | `Future` | Salva un valore nello storage |
| `saveJson(value, {inBackpack})` | `Future` | Salva un valore JSON nello storage |
| `read<T>({defaultValue})` | `Future<T?>` | Legge un valore dallo storage |
| `readJson<T>({defaultValue})` | `Future<T?>` | Legge un valore JSON dallo storage |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | Alias per read |
| `fromBackpack<T>({defaultValue})` | `T?` | Legge dal Backpack (sincrono) |
| `toModel<T>()` | `T` | Converte una stringa JSON in un modello |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | Aggiunge un elemento a una collezione |
| `readCollection<T>()` | `Future<List<T>>` | Legge una collezione |
| `deleteFromStorage({andFromBackpack})` | `Future` | Elimina dallo storage |
| `flush({andFromBackpack})` | `Future` | Alias per deleteFromStorage |
| `defaultValue<T>(value)` | `Future Function(bool)?` | Imposta il valore predefinito se la chiave è vuota (usato in syncedOnBoot) |

<div id="storage-exceptions"></div>

## Eccezioni Storage

{{ config('app.name') }} v7 fornisce eccezioni di storage tipizzate:

| Eccezione | Descrizione |
|-----------|-------------|
| `StorageException` | Eccezione base con messaggio e chiave opzionale |
| `StorageSerializationException` | Serializzazione dell'oggetto per lo storage fallita |
| `StorageDeserializationException` | Deserializzazione dei dati memorizzati fallita |
| `StorageKeyNotFoundException` | Chiave di storage non trovata |
| `StorageTimeoutException` | Timeout dell'operazione di storage |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Failed to load user: ${e.message}');
  print('Expected type: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## Migrazione Legacy

{{ config('app.name') }} v7 utilizza un nuovo formato di storage envelope. Se stai aggiornando dalla v6, puoi migrare i vecchi dati:

``` dart
// Call during app initialization
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Migrated $migrated keys to new format');
```

Questo converte il formato legacy (chiavi `_runtime_type` separate) nel nuovo formato envelope. La migrazione è sicura da eseguire più volte - le chiavi già migrate vengono saltate.
