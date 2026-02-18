# Storage

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução ao armazenamento")
- NyStorage
  - [Salvar Valores](#save-values "Salvando valores")
  - [Ler Valores](#read-values "Recuperando valores")
  - [Deletar Valores](#delete-values "Deletando valores")
  - [Chaves de Armazenamento](#storage-keys "Chaves de Armazenamento")
  - [Salvar/Ler JSON](#save-json "Salvar e Ler JSON")
  - [TTL (Tempo de Vida)](#ttl-storage "Armazenamento TTL")
  - [Operações em Lote](#batch-operations "Operações em lote")
- Collections
  - [Introdução](#introduction-to-collections "Introdução às collections")
  - [Adicionar a uma Collection](#add-to-a-collection "Adicionar a uma collection")
  - [Ler Collection](#read-a-collection "Ler uma collection")
  - [Atualizar Collection](#update-collection "Atualizar uma collection")
  - [Deletar de uma Collection](#delete-from-collection "Deletar de uma collection")
- Backpack
  - [Introdução](#backpack-storage "Armazenamento Backpack")
  - [Persistir com Backpack](#persist-data-with-backpack "Persistir Dados com Backpack")
- [Sessões](#introduction-to-sessions "Sessões")
- Model Storage
  - [Salvar Model](#model-save "Salvar Model")
  - [Collections de Models](#model-collections "Collections de Models")
- [Referência de Extensões StorageKey](#storage-key-extension-reference "Referência de Extensões StorageKey")
- [Exceções de Armazenamento](#storage-exceptions "Exceções de Armazenamento")
- [Migração Legada](#legacy-migration "Migração Legada")

<div id="introduction"></div>

## Introdução

{{ config('app.name') }} v7 fornece um sistema de armazenamento poderoso através da classe `NyStorage`. Ele usa o <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> internamente para armazenar dados de forma segura no dispositivo do usuário.

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

## Salvar Valores

Salve valores usando `NyStorage.save()` ou o helper `storageSave()`:

``` dart
// Using NyStorage class
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Using helper function
await storageSave("username", "Anthony");
```

### Salvar no Backpack Simultaneamente

Armazene tanto no armazenamento persistente quanto no Backpack em memória:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## Ler Valores

Leia valores com conversão automática de tipo:

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

## Deletar Valores

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

## Chaves de Armazenamento

Organize suas chaves de armazenamento em `lib/config/storage_keys.dart`:

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

### Usando Extensões de StorageKey

`StorageKey` é um typedef para `String`, e vem com um conjunto poderoso de métodos de extensão:

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

## Salvar/Ler JSON

Armazene e recupere dados JSON:

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

## TTL (Tempo de Vida)

{{ config('app.name') }} v7 suporta armazenamento de valores com expiração automática:

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

## Operações em Lote

Gerencie múltiplas operações de armazenamento de forma eficiente:

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

## Introdução às Collections

Collections permitem que você armazene listas de itens sob uma única chave:

``` dart
// Add items to a collection
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Read the collection
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## Adicionar a uma Collection

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

## Ler Collection

``` dart
// Read collection with type
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Check if collection is empty
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## Atualizar Collection

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

## Deletar de uma Collection

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

## Armazenamento Backpack

O `Backpack` é uma classe de armazenamento em memória leve para acesso síncrono rápido durante a sessão do usuário. Os dados **não são persistidos** quando o app é fechado.

### Salvar no Backpack

``` dart
// Using helper
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Using Backpack directly
Backpack.instance.save('settings', {'darkMode': true});
```

### Ler do Backpack

``` dart
// Using helper
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Using Backpack directly
var settings = Backpack.instance.read('settings');
```

### Deletar do Backpack

``` dart
backpackDelete('user_token');

// Delete all
backpackDeleteAll();
```

### Exemplo Prático

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

## Persistir com Backpack

Armazene tanto no armazenamento persistente quanto no Backpack em uma única chamada:

``` dart
// Save to both
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Now accessible via Backpack (sync) and NyStorage (async)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### Sincronizar Armazenamento com o Backpack

Carregue todo o armazenamento persistente no Backpack ao iniciar o app:

``` dart
// In your app provider
await NyStorage.syncToBackpack();

// With overwrite option
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## Sessões

Sessões fornecem armazenamento nomeado em memória para agrupar dados relacionados (não persistidos):

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

### Persistir Sessões

Sessões podem ser sincronizadas com o armazenamento persistente:

``` dart
// Save session to storage
await session('checkout').syncToStorage();

// Restore session from storage
await session('checkout').syncFromStorage();
```

### Casos de Uso de Sessões

Sessões são ideais para:
- Formulários de múltiplas etapas (onboarding, checkout)
- Preferências temporárias do usuário
- Fluxos de assistente/jornada
- Dados do carrinho de compras

<div id="model-save"></div>

## Salvar Model

A classe base `Model` fornece métodos de armazenamento integrados. Quando você define uma `key` no construtor, o model pode se salvar:

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

### Salvando um Model

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Save to persistent storage
await user.save();

// Save to both storage and Backpack
await user.save(inBackpack: true);
```

### Lendo um Model de Volta

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Sincronizando com o Backpack

Carregue um model do armazenamento para o Backpack para acesso síncrono:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Collections de Models

Salve models em uma collection:

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

## Referência de Extensões StorageKey

`StorageKey` é um typedef para `String`. A extensão `NyStorageKeyExt` fornece estes métodos:

| Método | Retorno | Descrição |
|--------|---------|-------------|
| `save(value, {inBackpack})` | `Future` | Salvar valor no armazenamento |
| `saveJson(value, {inBackpack})` | `Future` | Salvar valor JSON no armazenamento |
| `read<T>({defaultValue})` | `Future<T?>` | Ler valor do armazenamento |
| `readJson<T>({defaultValue})` | `Future<T?>` | Ler valor JSON do armazenamento |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | Alias para read |
| `fromBackpack<T>({defaultValue})` | `T?` | Ler do Backpack (síncrono) |
| `toModel<T>()` | `T` | Converter string JSON para model |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | Adicionar item à collection |
| `readCollection<T>()` | `Future<List<T>>` | Ler collection |
| `deleteFromStorage({andFromBackpack})` | `Future` | Deletar do armazenamento |
| `flush({andFromBackpack})` | `Future` | Alias para deleteFromStorage |
| `defaultValue<T>(value)` | `Future Function(bool)?` | Definir valor padrão se chave estiver vazia (usado em syncedOnBoot) |

<div id="storage-exceptions"></div>

## Exceções de Armazenamento

{{ config('app.name') }} v7 fornece exceções de armazenamento tipadas:

| Exceção | Descrição |
|-----------|-------------|
| `StorageException` | Exceção base com mensagem e chave opcional |
| `StorageSerializationException` | Falha ao serializar objeto para armazenamento |
| `StorageDeserializationException` | Falha ao desserializar dados armazenados |
| `StorageKeyNotFoundException` | Chave de armazenamento não encontrada |
| `StorageTimeoutException` | Operação de armazenamento expirou |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Failed to load user: ${e.message}');
  print('Expected type: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## Migração Legada

{{ config('app.name') }} v7 usa um novo formato de armazenamento envelope. Se você está atualizando do v6, pode migrar dados antigos:

``` dart
// Call during app initialization
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Migrated $migrated keys to new format');
```

Isso converte o formato legado (chaves `_runtime_type` separadas) para o novo formato envelope. A migração é segura para executar múltiplas vezes -- chaves já migradas são ignoradas.
