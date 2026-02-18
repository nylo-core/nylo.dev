# Storage

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion al almacenamiento")
- NyStorage
  - [Guardar valores](#save-values "Guardar valores")
  - [Leer valores](#read-values "Leer valores")
  - [Eliminar valores](#delete-values "Eliminar valores")
  - [Claves de almacenamiento](#storage-keys "Claves de almacenamiento")
  - [Guardar/Leer JSON](#save-json "Guardar y leer JSON")
  - [TTL (Tiempo de vida)](#ttl-storage "TTL de almacenamiento")
  - [Operaciones por lotes](#batch-operations "Operaciones por lotes")
- Colecciones
  - [Introduccion](#introduction-to-collections "Introduccion a las colecciones")
  - [Agregar a una coleccion](#add-to-a-collection "Agregar a una coleccion")
  - [Leer coleccion](#read-a-collection "Leer una coleccion")
  - [Actualizar coleccion](#update-collection "Actualizar una coleccion")
  - [Eliminar de una coleccion](#delete-from-collection "Eliminar de una coleccion")
- Backpack
  - [Introduccion](#backpack-storage "Almacenamiento Backpack")
  - [Persistir con Backpack](#persist-data-with-backpack "Persistir datos con Backpack")
- [Sesiones](#introduction-to-sessions "Sesiones")
- Almacenamiento de modelos
  - [Guardar modelo](#model-save "Guardar modelo")
  - [Colecciones de modelos](#model-collections "Colecciones de modelos")
- [Referencia de extension StorageKey](#storage-key-extension-reference "Referencia de extension StorageKey")
- [Excepciones de almacenamiento](#storage-exceptions "Excepciones de almacenamiento")
- [Migracion heredada](#legacy-migration "Migracion heredada")

<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} v7 proporciona un potente sistema de almacenamiento a traves de la clase `NyStorage`. Usa <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> internamente para almacenar datos de forma segura en el dispositivo del usuario.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Guardar un valor
await NyStorage.save("coins", 100);

// Leer un valor
int? coins = await NyStorage.read<int>('coins'); // 100

// Eliminar un valor
await NyStorage.delete('coins');
```

<div id="save-values"></div>

## Guardar valores

Guarda valores usando `NyStorage.save()` o el helper `storageSave()`:

``` dart
// Usando la clase NyStorage
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Usando la funcion helper
await storageSave("username", "Anthony");
```

### Guardar en Backpack simultaneamente

Almacena tanto en almacenamiento persistente como en Backpack en memoria:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Ahora accesible sincronicamente via Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## Leer valores

Lee valores con conversion de tipo automatica:

``` dart
// Leer como String (por defecto)
String? username = await NyStorage.read('username');

// Leer con conversion de tipo
int? score = await NyStorage.read<int>('score');
double? rating = await NyStorage.read<double>('rating');
bool? isPremium = await NyStorage.read<bool>('isPremium');

// Con valor por defecto
String name = await NyStorage.read('name', defaultValue: 'Guest') ?? 'Guest';

// Usando la funcion helper
String? username = await storageRead('username');
int? score = await storageRead<int>('score');
```

<div id="delete-values"></div>

## Eliminar valores

``` dart
// Eliminar una sola clave
await NyStorage.delete('username');

// Eliminar y remover de Backpack tambien
await NyStorage.delete('auth_token', andFromBackpack: true);

// Eliminar multiples claves
await NyStorage.deleteMultiple(['key1', 'key2', 'key3']);

// Eliminar todo (con exclusiones opcionales)
await NyStorage.deleteAll();
await NyStorage.deleteAll(excludeKeys: ['auth_token']);
```

<div id="storage-keys"></div>

## Claves de almacenamiento

Organiza tus claves de almacenamiento en `lib/config/storage_keys.dart`:

``` dart
final class StorageKeysConfig {
  // Clave de autenticacion para la autenticacion de usuario
  static StorageKey auth = 'SK_AUTH';

  // Claves sincronizadas al iniciar la aplicacion
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

### Usar extensiones de StorageKey

`StorageKey` es un typedef para `String`, y viene con un potente conjunto de metodos de extension:

``` dart
// Guardar
await StorageKeysConfig.coins.save(100);

// Guardar con Backpack
await StorageKeysConfig.coins.save(100, inBackpack: true);

// Leer
int? coins = await StorageKeysConfig.coins.read<int>();

// Leer con valor por defecto
int? coins = await StorageKeysConfig.coins.fromStorage<int>(defaultValue: 0);

// Guardar/Leer JSON
await StorageKeysConfig.coins.saveJson({"gold": 50, "silver": 200});
Map? data = await StorageKeysConfig.coins.readJson<Map>();

// Eliminar
await StorageKeysConfig.coins.deleteFromStorage();

// Eliminar (alias)
await StorageKeysConfig.coins.flush();

// Leer de Backpack (sincronico)
int? coins = StorageKeysConfig.coins.fromBackpack<int>();

// Operaciones de coleccion
await StorageKeysConfig.coins.addToCollection<int>(100);
List<int> allCoins = await StorageKeysConfig.coins.readCollection<int>();
```

<div id="save-json"></div>

## Guardar/Leer JSON

Almacena y recupera datos JSON:

``` dart
// Guardar JSON
Map<String, dynamic> user = {
  "name": "Anthony",
  "email": "anthony@example.com",
  "preferences": {"theme": "dark"}
};
await NyStorage.saveJson("user_data", user);

// Leer JSON
Map<String, dynamic>? userData = await NyStorage.readJson("user_data");
print(userData?['name']); // "Anthony"
```

<div id="ttl-storage"></div>

## TTL (Tiempo de vida)

{{ config('app.name') }} v7 soporta almacenar valores con expiracion automatica:

``` dart
// Guardar con expiracion de 1 hora
await NyStorage.saveWithExpiry(
  'session_token',
  'abc123',
  ttl: Duration(hours: 1),
);

// Leer (devuelve null si ha expirado)
String? token = await NyStorage.readWithExpiry<String>('session_token');

// Verificar tiempo restante
Duration? remaining = await NyStorage.getTimeToLive('session_token');
if (remaining != null) {
  print('Expires in ${remaining.inMinutes} minutes');
}

// Limpiar todas las claves expiradas
int removed = await NyStorage.removeExpired();
print('Removed $removed expired keys');
```

<div id="batch-operations"></div>

## Operaciones por lotes

Maneja multiples operaciones de almacenamiento de manera eficiente:

``` dart
// Guardar multiples valores
await NyStorage.saveAll({
  'username': 'Anthony',
  'score': 1500,
  'level': 10,
});

// Leer multiples valores
Map<String, dynamic?> values = await NyStorage.readMultiple<dynamic>([
  'username',
  'score',
  'level',
]);

// Eliminar multiples claves
await NyStorage.deleteMultiple(['temp_1', 'temp_2', 'temp_3']);
```

<div id="introduction-to-collections"></div>

## Introduccion a las colecciones

Las colecciones te permiten almacenar listas de elementos bajo una sola clave:

``` dart
// Agregar elementos a una coleccion
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Leer la coleccion
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## Agregar a una coleccion

``` dart
// Agregar elemento (permite duplicados por defecto)
await NyStorage.addToCollection("cart_ids", item: 123);

// Prevenir duplicados
await NyStorage.addToCollection(
  "cart_ids",
  item: 123,
  allowDuplicates: false,
);

// Guardar coleccion completa de una vez
await NyStorage.saveCollection<int>("cart_ids", [1, 2, 3, 4, 5]);
```

<div id="read-a-collection"></div>

## Leer coleccion

``` dart
// Leer coleccion con tipo
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Verificar si la coleccion esta vacia
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## Actualizar coleccion

``` dart
// Actualizar elemento por indice
await NyStorage.updateCollectionByIndex<int>(
  0, // indice
  (item) => item + 10, // funcion de transformacion
  key: "scores",
);

// Actualizar elementos que coincidan con una condicion
await NyStorage.updateCollectionWhere<Map<String, dynamic>>(
  (item) => item['id'] == 5, // condicion where
  key: "products",
  update: (item) {
    item['quantity'] = item['quantity'] + 1;
    return item;
  },
);
```

<div id="delete-from-collection"></div>

## Eliminar de una coleccion

``` dart
// Eliminar por indice
await NyStorage.deleteFromCollection<String>(0, key: "favorites");

// Eliminar por valor
await NyStorage.deleteValueFromCollection<int>(
  "cart_ids",
  value: 123,
);

// Eliminar elementos que coincidan con una condicion
await NyStorage.deleteFromCollectionWhere<Map<String, dynamic>>(
  (item) => item['expired'] == true,
  key: "coupons",
);

// Eliminar coleccion completa
await NyStorage.delete("favorites");
```

<div id="backpack-storage"></div>

## Almacenamiento Backpack

`Backpack` es una clase de almacenamiento ligera en memoria para acceso sincronico rapido durante la sesion del usuario. Los datos **no se persisten** cuando la aplicacion se cierra.

### Guardar en Backpack

``` dart
// Usando helper
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Usando Backpack directamente
Backpack.instance.save('settings', {'darkMode': true});
```

### Leer de Backpack

``` dart
// Usando helper
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Usando Backpack directamente
var settings = Backpack.instance.read('settings');
```

### Eliminar de Backpack

``` dart
backpackDelete('user_token');

// Eliminar todo
backpackDeleteAll();
```

### Ejemplo practico

``` dart
// Despues del inicio de sesion, almacenar token en almacenamiento persistente y de sesion
Future<void> handleLogin(String token) async {
  // Persistir para reinicios de la aplicacion
  await NyStorage.save('auth_token', token);

  // Tambien almacenar en Backpack para acceso rapido
  backpackSave('auth_token', token);
}

// En el servicio API, acceder sincronicamente
class ApiService extends NyApiService {
  Future<User?> getProfile() async {
    return await network<User>(
      request: (request) => request.get("/profile"),
      bearerToken: backpackRead('auth_token'), // Sin await necesario
    );
  }
}
```

<div id="persist-data-with-backpack"></div>

## Persistir con Backpack

Almacena tanto en almacenamiento persistente como en Backpack en una sola llamada:

``` dart
// Guardar en ambos
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Ahora accesible via Backpack (sinc) y NyStorage (asinc)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### Sincronizar almacenamiento a Backpack

Cargar todo el almacenamiento persistente en Backpack al iniciar la aplicacion:

``` dart
// En tu proveedor de aplicacion
await NyStorage.syncToBackpack();

// Con opcion de sobreescritura
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## Sesiones

Las sesiones proporcionan almacenamiento con nombre en memoria para agrupar datos relacionados (no se persisten):

``` dart
// Crear/acceder a una sesion y agregar datos
session('checkout')
    .add('items', ['Product A', 'Product B'])
    .add('total', 99.99)
    .add('coupon', 'SAVE10');

// O inicializar con datos
session('checkout', {
  'items': ['Product A', 'Product B'],
  'total': 99.99,
});

// Leer datos de sesion
List<String>? items = session('checkout').get<List<String>>('items');
double? total = session('checkout').get<double>('total');

// Obtener todos los datos de la sesion
Map<String, dynamic>? checkoutData = session('checkout').data();

// Eliminar un solo valor
session('checkout').delete('coupon');

// Limpiar sesion completa
session('checkout').clear();
// o
session('checkout').flush();
```

### Persistir sesiones

Las sesiones pueden sincronizarse al almacenamiento persistente:

``` dart
// Guardar sesion en almacenamiento
await session('checkout').syncToStorage();

// Restaurar sesion desde almacenamiento
await session('checkout').syncFromStorage();
```

### Casos de uso de sesiones

Las sesiones son ideales para:
- Formularios de multiples pasos (onboarding, checkout)
- Preferencias temporales del usuario
- Flujos de asistente/recorrido
- Datos del carrito de compras

<div id="model-save"></div>

## Guardar modelo

La clase base `Model` proporciona metodos de almacenamiento integrados. Cuando defines una `key` en el constructor, el modelo puede guardarse a si mismo:

``` dart
class User extends Model {
  String? name;
  String? email;

  // Definir una clave de almacenamiento
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

### Guardar un modelo

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Guardar en almacenamiento persistente
await user.save();

// Guardar en ambos almacenamiento y Backpack
await user.save(inBackpack: true);
```

### Leer un modelo

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Sincronizar a Backpack

Cargar un modelo desde almacenamiento a Backpack para acceso sincronico:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Colecciones de modelos

Guardar modelos en una coleccion:

``` dart
User userAnthony = User();
userAnthony.name = 'Anthony';
await userAnthony.saveToCollection();

User userKyle = User();
userKyle.name = 'Kyle';
await userKyle.saveToCollection();

// Leer de vuelta
List<User> users = await NyStorage.readCollection<User>(User.key);
```

<div id="storage-key-extension-reference"></div>

## Referencia de extension StorageKey

`StorageKey` es un typedef para `String`. La extension `NyStorageKeyExt` proporciona estos metodos:

| Metodo | Devuelve | Descripcion |
|--------|----------|-------------|
| `save(value, {inBackpack})` | `Future` | Guardar valor en almacenamiento |
| `saveJson(value, {inBackpack})` | `Future` | Guardar valor JSON en almacenamiento |
| `read<T>({defaultValue})` | `Future<T?>` | Leer valor del almacenamiento |
| `readJson<T>({defaultValue})` | `Future<T?>` | Leer valor JSON del almacenamiento |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | Alias para read |
| `fromBackpack<T>({defaultValue})` | `T?` | Leer de Backpack (sinc) |
| `toModel<T>()` | `T` | Convertir string JSON a modelo |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | Agregar elemento a coleccion |
| `readCollection<T>()` | `Future<List<T>>` | Leer coleccion |
| `deleteFromStorage({andFromBackpack})` | `Future` | Eliminar del almacenamiento |
| `flush({andFromBackpack})` | `Future` | Alias para deleteFromStorage |
| `defaultValue<T>(value)` | `Future Function(bool)?` | Establecer valor por defecto si la clave esta vacia (usado en syncedOnBoot) |

<div id="storage-exceptions"></div>

## Excepciones de almacenamiento

{{ config('app.name') }} v7 proporciona excepciones de almacenamiento tipadas:

| Excepcion | Descripcion |
|-----------|-------------|
| `StorageException` | Excepcion base con mensaje y clave opcional |
| `StorageSerializationException` | Error al serializar objeto para almacenamiento |
| `StorageDeserializationException` | Error al deserializar datos almacenados |
| `StorageKeyNotFoundException` | Clave de almacenamiento no encontrada |
| `StorageTimeoutException` | Tiempo de espera agotado en operacion de almacenamiento |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Failed to load user: ${e.message}');
  print('Expected type: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## Migracion heredada

{{ config('app.name') }} v7 usa un nuevo formato de almacenamiento de sobre (envelope). Si estas actualizando desde v6, puedes migrar datos antiguos:

``` dart
// Llamar durante la inicializacion de la aplicacion
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Migrated $migrated keys to new format');
```

Esta conversion transforma el formato heredado (claves `_runtime_type` separadas) al nuevo formato de sobre. La migracion es segura de ejecutar multiples veces -- las claves ya migradas se omiten.
