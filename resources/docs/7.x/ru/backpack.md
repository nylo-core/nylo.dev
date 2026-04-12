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

// Сохранить значение
Backpack.instance.save("user_name", "Anthony");

// Прочитать значение (синхронно)
String? name = Backpack.instance.read("user_name");

// Удалить значение
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## Базовое использование

Backpack использует **паттерн синглтон** -- доступ осуществляется через `Backpack.instance`:

``` dart
// Сохранить данные
Backpack.instance.save("theme", "dark");

// Прочитать данные
String? theme = Backpack.instance.read("theme"); // "dark"

// Проверить наличие данных
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Чтение данных

Читайте значения из Backpack с помощью метода `read<T>()`. Он поддерживает обобщённые типы и необязательное значение по умолчанию:

``` dart
// Прочитать строку
String? name = Backpack.instance.read<String>("name");

// Прочитать со значением по умолчанию
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Прочитать целое число
int? score = Backpack.instance.read<int>("score");
```

Backpack автоматически десериализует сохранённые значения в объекты моделей при указании типа. Это работает как для JSON-строк, так и для raw-значений `Map<String, dynamic>`:

``` dart
// Если модель User сохранена как JSON-строка, она будет десериализована
User? user = Backpack.instance.read<User>("current_user");

// Если была сохранена raw Map (например, через syncKeys из NyStorage), она тоже
// автоматически десериализуется в типизированную модель при чтении
Backpack.instance.save("current_user", {"name": "Alice", "age": 30});
User? user = Backpack.instance.read<User>("current_user"); // возвращает User
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
// Добавить в список
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Добавить с ограничением (хранит только последние N элементов)
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
// Использование вспомогательных функций
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Получить экземпляр Nylo
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## Интеграция с NyStorage

Backpack интегрируется с `NyStorage` для комбинированного постоянного + хранения в памяти:

``` dart
// Сохранить как в NyStorage (постоянно), так и в Backpack (в памяти)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Теперь доступно синхронно через Backpack
String? token = Backpack.instance.read("auth_token");

// При удалении из NyStorage также очистить из Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

Этот паттерн полезен для данных, таких как токены аутентификации, которым необходимо как постоянное хранение, так и быстрый синхронный доступ (например, в HTTP-перехватчиках).

<div id="examples"></div>

## Примеры

### Хранение токенов аутентификации для API-запросов

``` dart
// В вашем перехватчике аутентификации
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
// Добавить элементы в сессию корзины
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Прочитать данные корзины
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Очистить корзину
Backpack.instance.sessionFlush("cart");
```

### Быстрые флаги функций

``` dart
// Сохранить флаги функций в Backpack для быстрого доступа
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Проверить флаг функции
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
