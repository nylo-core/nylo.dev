# Backpack

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Uso basico](#basic-usage "Uso basico")
- [Leer datos](#reading-data "Leer datos")
- [Guardar datos](#saving-data "Guardar datos")
- [Eliminar datos](#deleting-data "Eliminar datos")
- [Sesiones](#sessions "Sesiones")
- [Acceder a la instancia de Nylo](#nylo-instance "Acceder a la instancia de Nylo")
- [Funciones auxiliares](#helper-functions "Funciones auxiliares")
- [Integracion con NyStorage](#integration-with-nystorage "Integracion con NyStorage")
- [Ejemplos](#examples "Ejemplos practicos")

<div id="introduction"></div>

## Introduccion

**Backpack** es un sistema de almacenamiento singleton en memoria en {{ config('app.name') }}. Proporciona acceso rapido y sincrono a los datos durante la ejecucion de tu aplicacion. A diferencia de `NyStorage` que persiste los datos en el dispositivo, Backpack almacena los datos en memoria y se limpia cuando la aplicacion se cierra.

Backpack es utilizado internamente por el framework para almacenar instancias criticas como el objeto `Nylo`, `EventBus` y datos de autenticacion. Tambien puedes usarlo para almacenar tus propios datos que necesitan ser accedidos rapidamente sin llamadas asincronas.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Save a value
Backpack.instance.save("user_name", "Anthony");

// Read a value (synchronous)
String? name = Backpack.instance.read("user_name");

// Delete a value
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## Uso basico

Backpack usa el **patron singleton** -- accede a el a traves de `Backpack.instance`:

``` dart
// Save data
Backpack.instance.save("theme", "dark");

// Read data
String? theme = Backpack.instance.read("theme"); // "dark"

// Check if data exists
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Leer datos

Lee valores desde Backpack usando el metodo `read<T>()`. Soporta tipos genericos y un valor predeterminado opcional:

``` dart
// Read a String
String? name = Backpack.instance.read<String>("name");

// Read with a default value
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Read an int
int? score = Backpack.instance.read<int>("score");
```

Backpack deserializa automaticamente cadenas JSON a objetos de modelo cuando se proporciona un tipo:

``` dart
// If a User model is stored as JSON, it will be deserialized
User? user = Backpack.instance.read<User>("current_user");
```

<div id="saving-data"></div>

## Guardar datos

Guarda valores usando el metodo `save()`:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### Agregar datos

Usa `append()` para agregar valores a una lista almacenada en una clave:

``` dart
// Append to a list
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Append with a limit (keeps only the last N items)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## Eliminar datos

### Eliminar una clave individual

``` dart
Backpack.instance.delete("api_token");
```

### Eliminar todos los datos

El metodo `deleteAll()` elimina todos los valores **excepto** las claves reservadas del framework (`nylo` y `event_bus`):

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## Sesiones

Backpack proporciona gestion de sesiones para organizar datos en grupos con nombre. Esto es util para almacenar datos relacionados juntos.

### Actualizar un valor de sesion

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### Obtener un valor de sesion

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### Eliminar una clave de sesion

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### Vaciar una sesion completa

``` dart
Backpack.instance.sessionFlush("cart");
```

### Obtener todos los datos de sesion

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Acceder a la instancia de Nylo

Backpack almacena la instancia de la aplicacion `Nylo`. Puedes recuperarla usando:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

Verifica si la instancia de Nylo ha sido inicializada:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## Funciones auxiliares

{{ config('app.name') }} proporciona funciones auxiliares globales para operaciones comunes de Backpack:

| Funcion | Descripcion |
|----------|-------------|
| `backpackRead<T>(key)` | Leer un valor de Backpack |
| `backpackSave(key, value)` | Guardar un valor en Backpack |
| `backpackDelete(key)` | Eliminar un valor de Backpack |
| `backpackDeleteAll()` | Eliminar todos los valores (conserva claves del framework) |
| `backpackNylo()` | Obtener la instancia de Nylo desde Backpack |

### Ejemplo

``` dart
// Using helper functions
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Access the Nylo instance
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## Integracion con NyStorage

Backpack se integra con `NyStorage` para almacenamiento combinado persistente + en memoria:

``` dart
// Save to both NyStorage (persistent) and Backpack (in-memory)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read("auth_token");

// When deleting from NyStorage, also clear from Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

Este patron es util para datos como tokens de autenticacion que necesitan tanto persistencia como acceso sincrono rapido (por ejemplo, en interceptores HTTP).

<div id="examples"></div>

## Ejemplos

### Almacenar tokens de autenticacion para solicitudes API

``` dart
// In your auth interceptor
class BearerAuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    String? userToken = Backpack.instance.read(StorageKeysConfig.auth);

    if (userToken != null) {
      options.headers.addAll({"Authorization": "Bearer $userToken"});
    }

    return super.onRequest(options, handler);
  }
}
```

### Gestion de carrito basada en sesiones

``` dart
// Add items to a cart session
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Read cart data
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Clear the cart
Backpack.instance.sessionFlush("cart");
```

### Feature flags rapidos

``` dart
// Store feature flags in Backpack for fast access
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Check a feature flag
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
