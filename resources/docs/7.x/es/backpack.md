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

// Guardar un valor
Backpack.instance.save("user_name", "Anthony");

// Leer un valor (sincrono)
String? name = Backpack.instance.read("user_name");

// Eliminar un valor
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## Uso basico

Backpack usa el **patron singleton** -- accede a el a traves de `Backpack.instance`:

``` dart
// Guardar datos
Backpack.instance.save("theme", "dark");

// Leer datos
String? theme = Backpack.instance.read("theme"); // "dark"

// Verificar si existen datos
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Leer datos

Lee valores desde Backpack usando el metodo `read<T>()`. Soporta tipos genericos y un valor predeterminado opcional:

``` dart
// Leer una cadena
String? name = Backpack.instance.read<String>("name");

// Leer con un valor predeterminado
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Leer un entero
int? score = Backpack.instance.read<int>("score");
```

Backpack deserializa automaticamente los valores almacenados a objetos de modelo cuando se proporciona un tipo. Esto funciona tanto para cadenas JSON como para valores brutos `Map<String, dynamic>`:

``` dart
// Si un modelo User esta almacenado como cadena JSON, sera deserializado
User? user = Backpack.instance.read<User>("current_user");

// Si se almaceno un Map bruto (por ejemplo, via syncKeys de NyStorage), tambien se
// deserializa automaticamente al modelo tipado al leer
Backpack.instance.save("current_user", {"name": "Alice", "age": 30});
User? user = Backpack.instance.read<User>("current_user"); // retorna un User
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
// Agregar a una lista
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Agregar con un limite (conserva solo los ultimos N elementos)
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
// Usar las funciones auxiliares
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Acceder a la instancia de Nylo
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## Integracion con NyStorage

Backpack se integra con `NyStorage` para almacenamiento combinado persistente + en memoria:

``` dart
// Guardar en NyStorage (persistente) y Backpack (en memoria)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Ahora accesible de forma sincrona via Backpack
String? token = Backpack.instance.read("auth_token");

// Al eliminar de NyStorage, tambien limpiar de Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

Este patron es util para datos como tokens de autenticacion que necesitan tanto persistencia como acceso sincrono rapido (por ejemplo, en interceptores HTTP).

<div id="examples"></div>

## Ejemplos

### Almacenar tokens de autenticacion para solicitudes API

``` dart
// En tu interceptor de autenticacion
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
// Agregar articulos a una sesion de carrito
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Leer datos del carrito
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Vaciar el carrito
Backpack.instance.sessionFlush("cart");
```

### Feature flags rapidos

``` dart
// Almacenar feature flags en Backpack para acceso rapido
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Verificar un feature flag
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
