# Backpack

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Базовое использование](#basic-usage "Базовое использование")
- [Чтение данных](#reading-data "Чтение данных")
- [Сохранение данных](#saving-data "Сохранение данных")
- [Удаление данных](#deleting-data "Удаление данных")
- [Сессии](#sessions "Сессии")
- [Доступ к экземпляру Nylo](#nylo-instance "Доступ к экземпляру Nylo")
- [Вспомогательные функции](#helper-functions "Вспомогательные функции")
- [Интеграция с NyStorage](#integration-with-nystorage "Интеграция с NyStorage")
- [Примеры](#examples "Практические примеры")

<div id="introduction"></div>

## Введение

**Backpack** -- это система хранения в памяти на основе паттерна синглтон в {{ config('app.name') }}. Она обеспечивает быстрый синхронный доступ к данным во время работы вашего приложения. В отличие от `NyStorage`, который сохраняет данные на устройстве, Backpack хранит данные в памяти и очищается при закрытии приложения.

Backpack используется внутри фреймворка для хранения критически важных экземпляров, таких как объект приложения `Nylo`, `EventBus` и данные аутентификации. Вы также можете использовать его для хранения собственных данных, к которым необходим быстрый доступ без асинхронных вызовов.

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

## Базовое использование

Backpack использует **паттерн синглтон** -- доступ осуществляется через `Backpack.instance`:

``` dart
// Save data
Backpack.instance.save("theme", "dark");

// Read data
String? theme = Backpack.instance.read("theme"); // "dark"

// Check if data exists
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Чтение данных

Читайте значения из Backpack с помощью метода `read<T>()`. Он поддерживает обобщённые типы и необязательное значение по умолчанию:

``` dart
// Read a String
String? name = Backpack.instance.read<String>("name");

// Read with a default value
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Read an int
int? score = Backpack.instance.read<int>("score");
```

Backpack автоматически десериализует JSON-строки в объекты моделей при указании типа:

``` dart
// If a User model is stored as JSON, it will be deserialized
User? user = Backpack.instance.read<User>("current_user");
```

<div id="saving-data"></div>

## Сохранение данных

Сохраняйте значения с помощью метода `save()`:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### Добавление данных

Используйте `append()` для добавления значений в список, хранящийся по ключу:

``` dart
// Append to a list
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Append with a limit (keeps only the last N items)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## Удаление данных

### Удаление одного ключа

``` dart
Backpack.instance.delete("api_token");
```

### Удаление всех данных

Метод `deleteAll()` удаляет все значения, **кроме** зарезервированных ключей фреймворка (`nylo` и `event_bus`):

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## Сессии

Backpack предоставляет управление сессиями для организации данных в именованные группы. Это полезно для хранения связанных данных вместе.

### Обновление значения сессии

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### Получение значения сессии

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### Удаление ключа сессии

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### Очистка всей сессии

``` dart
Backpack.instance.sessionFlush("cart");
```

### Получение всех данных сессии

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Доступ к экземпляру Nylo

Backpack хранит экземпляр приложения `Nylo`. Вы можете получить его с помощью:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

Проверьте, был ли экземпляр Nylo инициализирован:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## Вспомогательные функции

{{ config('app.name') }} предоставляет глобальные вспомогательные функции для типичных операций с Backpack:

| Функция | Описание |
|----------|-------------|
| `backpackRead<T>(key)` | Чтение значения из Backpack |
| `backpackSave(key, value)` | Сохранение значения в Backpack |
| `backpackDelete(key)` | Удаление значения из Backpack |
| `backpackDeleteAll()` | Удаление всех значений (сохраняет ключи фреймворка) |
| `backpackNylo()` | Получение экземпляра Nylo из Backpack |

### Пример

``` dart
// Using helper functions
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Access the Nylo instance
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## Интеграция с NyStorage

Backpack интегрируется с `NyStorage` для комбинированного постоянного + хранения в памяти:

``` dart
// Save to both NyStorage (persistent) and Backpack (in-memory)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read("auth_token");

// When deleting from NyStorage, also clear from Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

Этот паттерн полезен для данных, таких как токены аутентификации, которым необходимо как постоянное хранение, так и быстрый синхронный доступ (например, в HTTP-перехватчиках).

<div id="examples"></div>

## Примеры

### Хранение токенов аутентификации для API-запросов

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

### Управление корзиной на основе сессий

``` dart
// Add items to a cart session
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Read cart data
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Clear the cart
Backpack.instance.sessionFlush("cart");
```

### Быстрые флаги функций

``` dart
// Store feature flags in Backpack for fast access
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Check a feature flag
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
