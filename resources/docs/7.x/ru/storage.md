# Storage

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в хранилище")
- NyStorage
  - [Сохранение значений](#save-values "Сохранение значений")
  - [Чтение значений](#read-values "Чтение значений")
  - [Удаление значений](#delete-values "Удаление значений")
  - [Ключи хранилища](#storage-keys "Ключи хранилища")
  - [Сохранение/Чтение JSON](#save-json "Сохранение и чтение JSON")
  - [TTL (Время жизни)](#ttl-storage "TTL хранилище")
  - [Пакетные операции](#batch-operations "Пакетные операции")
- Коллекции
  - [Введение](#introduction-to-collections "Введение в коллекции")
  - [Добавление в коллекцию](#add-to-a-collection "Добавление в коллекцию")
  - [Чтение коллекции](#read-a-collection "Чтение коллекции")
  - [Обновление коллекции](#update-collection "Обновление коллекции")
  - [Удаление из коллекции](#delete-from-collection "Удаление из коллекции")
- Backpack
  - [Введение](#backpack-storage "Хранилище Backpack")
  - [Сохранение с Backpack](#persist-data-with-backpack "Сохранение данных с Backpack")
- [Сессии](#introduction-to-sessions "Сессии")
- Хранение моделей
  - [Сохранение модели](#model-save "Сохранение модели")
  - [Коллекции моделей](#model-collections "Коллекции моделей")
- [Справочник расширений StorageKey](#storage-key-extension-reference "Справочник расширений StorageKey")
- [Исключения хранилища](#storage-exceptions "Исключения хранилища")
- [Миграция с предыдущих версий](#legacy-migration "Миграция с предыдущих версий")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} v7 предоставляет мощную систему хранения данных через класс `NyStorage`. Под капотом используется <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> для безопасного хранения данных на устройстве пользователя.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Сохранить значение
await NyStorage.save("coins", 100);

// Прочитать значение
int? coins = await NyStorage.read<int>('coins'); // 100

// Удалить значение
await NyStorage.delete('coins');
```

<div id="save-values"></div>

## Сохранение значений

Сохраняйте значения с помощью `NyStorage.save()` или хелпера `storageSave()`:

``` dart
// Использование класса NyStorage
await NyStorage.save("username", "Anthony");
await NyStorage.save("score", 1500);
await NyStorage.save("isPremium", true);

// Использование вспомогательной функции
await storageSave("username", "Anthony");
```

### Одновременное сохранение в Backpack

Сохранение одновременно в постоянное хранилище и в оперативную память Backpack:

``` dart
await NyStorage.save('auth_token', 'abc123', inBackpack: true);

// Теперь доступно синхронно через Backpack
String? token = Backpack.instance.read('auth_token');
```

<div id="read-values"></div>

## Чтение значений

Чтение значений с автоматическим приведением типов:

``` dart
// Чтение как String (по умолчанию)
String? username = await NyStorage.read('username');

// Чтение с приведением типа
int? score = await NyStorage.read<int>('score');
double? rating = await NyStorage.read<double>('rating');
bool? isPremium = await NyStorage.read<bool>('isPremium');

// Со значением по умолчанию
String name = await NyStorage.read('name', defaultValue: 'Guest') ?? 'Guest';

// Использование вспомогательной функции
String? username = await storageRead('username');
int? score = await storageRead<int>('score');
```

<div id="delete-values"></div>

## Удаление значений

``` dart
// Удалить один ключ
await NyStorage.delete('username');

// Удалить и из Backpack тоже
await NyStorage.delete('auth_token', andFromBackpack: true);

// Удалить несколько ключей
await NyStorage.deleteMultiple(['key1', 'key2', 'key3']);

// Удалить всё (с необязательными исключениями)
await NyStorage.deleteAll();
await NyStorage.deleteAll(excludeKeys: ['auth_token']);
```

<div id="storage-keys"></div>

## Ключи хранилища

Организуйте ключи хранилища в файле `lib/config/storage_keys.dart`:

``` dart
final class StorageKeysConfig {
  // Ключ аутентификации
  static StorageKey auth = 'SK_AUTH';

  // Ключи, синхронизируемые при загрузке приложения
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

### Использование расширений StorageKey

`StorageKey` -- это typedef для `String`, который поставляется с мощным набором методов-расширений:

``` dart
// Сохранить
await StorageKeysConfig.coins.save(100);

// Сохранить с Backpack
await StorageKeysConfig.coins.save(100, inBackpack: true);

// Прочитать
int? coins = await StorageKeysConfig.coins.read<int>();

// Прочитать со значением по умолчанию
int? coins = await StorageKeysConfig.coins.fromStorage<int>(defaultValue: 0);

// Сохранить/Прочитать JSON
await StorageKeysConfig.coins.saveJson({"gold": 50, "silver": 200});
Map? data = await StorageKeysConfig.coins.readJson<Map>();

// Удалить
await StorageKeysConfig.coins.deleteFromStorage();

// Удалить (алиас)
await StorageKeysConfig.coins.flush();

// Чтение из Backpack (синхронное)
int? coins = StorageKeysConfig.coins.fromBackpack<int>();

// Операции с коллекциями
await StorageKeysConfig.coins.addToCollection<int>(100);
List<int> allCoins = await StorageKeysConfig.coins.readCollection<int>();
```

<div id="save-json"></div>

## Сохранение/Чтение JSON

Сохранение и получение данных в формате JSON:

``` dart
// Сохранить JSON
Map<String, dynamic> user = {
  "name": "Anthony",
  "email": "anthony@example.com",
  "preferences": {"theme": "dark"}
};
await NyStorage.saveJson("user_data", user);

// Прочитать JSON
Map<String, dynamic>? userData = await NyStorage.readJson("user_data");
print(userData?['name']); // "Anthony"
```

<div id="ttl-storage"></div>

## TTL (Время жизни)

{{ config('app.name') }} v7 поддерживает хранение значений с автоматическим истечением срока действия:

``` dart
// Сохранить с истечением через 1 час
await NyStorage.saveWithExpiry(
  'session_token',
  'abc123',
  ttl: Duration(hours: 1),
);

// Прочитать (возвращает null, если истёк)
String? token = await NyStorage.readWithExpiry<String>('session_token');

// Проверить оставшееся время
Duration? remaining = await NyStorage.getTimeToLive('session_token');
if (remaining != null) {
  print('Истекает через ${remaining.inMinutes} минут');
}

// Очистить все просроченные ключи
int removed = await NyStorage.removeExpired();
print('Удалено $removed просроченных ключей');
```

<div id="batch-operations"></div>

## Пакетные операции

Эффективная обработка нескольких операций с хранилищем:

``` dart
// Сохранить несколько значений
await NyStorage.saveAll({
  'username': 'Anthony',
  'score': 1500,
  'level': 10,
});

// Прочитать несколько значений
Map<String, dynamic?> values = await NyStorage.readMultiple<dynamic>([
  'username',
  'score',
  'level',
]);

// Удалить несколько ключей
await NyStorage.deleteMultiple(['temp_1', 'temp_2', 'temp_3']);
```

<div id="introduction-to-collections"></div>

## Введение в коллекции

Коллекции позволяют хранить списки элементов под одним ключом:

``` dart
// Добавить элементы в коллекцию
await NyStorage.addToCollection("favorites", item: "Product A");
await NyStorage.addToCollection("favorites", item: "Product B");

// Прочитать коллекцию
List<String> favorites = await NyStorage.readCollection<String>("favorites");
// ["Product A", "Product B"]
```

<div id="add-to-a-collection"></div>

## Добавление в коллекцию

``` dart
// Добавить элемент (по умолчанию допускает дубликаты)
await NyStorage.addToCollection("cart_ids", item: 123);

// Запретить дубликаты
await NyStorage.addToCollection(
  "cart_ids",
  item: 123,
  allowDuplicates: false,
);

// Сохранить всю коллекцию сразу
await NyStorage.saveCollection<int>("cart_ids", [1, 2, 3, 4, 5]);
```

<div id="read-a-collection"></div>

## Чтение коллекции

``` dart
// Прочитать коллекцию с типом
List<int> cartIds = await NyStorage.readCollection<int>("cart_ids");

// Проверить, пуста ли коллекция
bool isEmpty = await NyStorage.isCollectionEmpty("cart_ids");
```

<div id="update-collection"></div>

## Обновление коллекции

``` dart
// Обновить элемент по индексу
await NyStorage.updateCollectionByIndex<int>(
  0, // индекс
  (item) => item + 10, // функция преобразования
  key: "scores",
);

// Обновить элементы, соответствующие условию
await NyStorage.updateCollectionWhere<Map<String, dynamic>>(
  (item) => item['id'] == 5, // условие where
  key: "products",
  update: (item) {
    item['quantity'] = item['quantity'] + 1;
    return item;
  },
);
```

<div id="delete-from-collection"></div>

## Удаление из коллекции

``` dart
// Удалить по индексу
await NyStorage.deleteFromCollection<String>(0, key: "favorites");

// Удалить по значению
await NyStorage.deleteValueFromCollection<int>(
  "cart_ids",
  value: 123,
);

// Удалить элементы, соответствующие условию
await NyStorage.deleteFromCollectionWhere<Map<String, dynamic>>(
  (item) => item['expired'] == true,
  key: "coupons",
);

// Удалить всю коллекцию
await NyStorage.delete("favorites");
```

<div id="backpack-storage"></div>

## Хранилище Backpack

`Backpack` -- это легковесный класс хранения в оперативной памяти для быстрого синхронного доступа во время сессии пользователя. Данные **не сохраняются** при закрытии приложения.

### Сохранение в Backpack

``` dart
// Использование хелпера
backpackSave('user_token', 'abc123');
backpackSave('user', userObject);

// Использование Backpack напрямую
Backpack.instance.save('settings', {'darkMode': true});
```

### Чтение из Backpack

``` dart
// Использование хелпера
String? token = backpackRead('user_token');
User? user = backpackRead<User>('user');

// Использование Backpack напрямую
var settings = Backpack.instance.read('settings');
```

### Удаление из Backpack

``` dart
backpackDelete('user_token');

// Удалить всё
backpackDeleteAll();
```

### Практический пример

``` dart
// После входа сохранить токен в постоянном хранилище и в сессионном хранилище
Future<void> handleLogin(String token) async {
  // Сохранить для перезапусков приложения
  await NyStorage.save('auth_token', token);

  // Также сохранить в Backpack для быстрого доступа
  backpackSave('auth_token', token);
}

// В API-сервисе, синхронный доступ
class ApiService extends NyApiService {
  Future<User?> getProfile() async {
    return await network<User>(
      request: (request) => request.get("/profile"),
      bearerToken: backpackRead('auth_token'), // Без await
    );
  }
}
```

<div id="persist-data-with-backpack"></div>

## Сохранение с Backpack

Сохранение одновременно в постоянное хранилище и Backpack одним вызовом:

``` dart
// Сохранить в оба
await NyStorage.save('user_token', 'abc123', inBackpack: true);

// Теперь доступно через Backpack (синхронно) и NyStorage (асинхронно)
String? tokenSync = Backpack.instance.read('user_token');
String? tokenAsync = await NyStorage.read('user_token');
```

### Синхронизация хранилища с Backpack

Загрузка всех данных из постоянного хранилища в Backpack при запуске приложения:

``` dart
// В вашем провайдере приложения
await NyStorage.syncToBackpack();

// С параметром перезаписи
await NyStorage.syncToBackpack(overwrite: true);
```

<div id="introduction-to-sessions"></div>

## Сессии

Сессии предоставляют именованное хранилище в оперативной памяти для группировки связанных данных (не сохраняются на диск):

``` dart
// Создать/получить сессию и добавить данные
session('checkout')
    .add('items', ['Product A', 'Product B'])
    .add('total', 99.99)
    .add('coupon', 'SAVE10');

// Или инициализировать с данными
session('checkout', {
  'items': ['Product A', 'Product B'],
  'total': 99.99,
});

// Прочитать данные сессии
List<String>? items = session('checkout').get<List<String>>('items');
double? total = session('checkout').get<double>('total');

// Получить все данные сессии
Map<String, dynamic>? checkoutData = session('checkout').data();

// Удалить одно значение
session('checkout').delete('coupon');

// Очистить всю сессию
session('checkout').clear();
// или
session('checkout').flush();
```

### Сохранение сессий

Сессии можно синхронизировать с постоянным хранилищем:

``` dart
// Сохранить сессию в хранилище
await session('checkout').syncToStorage();

// Восстановить сессию из хранилища
await session('checkout').syncFromStorage();
```

### Сценарии использования сессий

Сессии идеально подходят для:
- Многошаговых форм (онбординг, оформление заказа)
- Временных пользовательских предпочтений
- Мастеров/пошаговых процессов
- Данных корзины покупок

<div id="model-save"></div>

## Сохранение модели

Базовый класс `Model` предоставляет встроенные методы хранения. Когда вы определяете `key` в конструкторе, модель может сохранять себя:

``` dart
class User extends Model {
  String? name;
  String? email;

  // Определение ключа хранилища
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

### Сохранение модели

``` dart
User user = User();
user.name = "Anthony";
user.email = "anthony@example.com";

// Сохранить в постоянное хранилище
await user.save();

// Сохранить в хранилище и Backpack
await user.save(inBackpack: true);
```

### Чтение модели

``` dart
User? user = await NyStorage.read<User>(User.key);
```

### Синхронизация с Backpack

Загрузка модели из хранилища в Backpack для синхронного доступа:

``` dart
bool found = await User().syncToBackpack();
if (found) {
  User? user = Backpack.instance.read<User>(User.key);
}
```

<div id="model-collections"></div>

## Коллекции моделей

Сохранение моделей в коллекцию:

``` dart
User userAnthony = User();
userAnthony.name = 'Anthony';
await userAnthony.saveToCollection();

User userKyle = User();
userKyle.name = 'Kyle';
await userKyle.saveToCollection();

// Прочитать обратно
List<User> users = await NyStorage.readCollection<User>(User.key);
```

<div id="storage-key-extension-reference"></div>

## Справочник расширений StorageKey

`StorageKey` -- это typedef для `String`. Расширение `NyStorageKeyExt` предоставляет следующие методы:

| Метод | Возвращает | Описание |
|--------|---------|-------------|
| `save(value, {inBackpack})` | `Future` | Сохранить значение в хранилище |
| `saveJson(value, {inBackpack})` | `Future` | Сохранить JSON-значение в хранилище |
| `read<T>({defaultValue})` | `Future<T?>` | Прочитать значение из хранилища |
| `readJson<T>({defaultValue})` | `Future<T?>` | Прочитать JSON-значение из хранилища |
| `fromStorage<T>({defaultValue})` | `Future<T?>` | Алиас для read |
| `fromBackpack<T>({defaultValue})` | `T?` | Прочитать из Backpack (синхронно) |
| `toModel<T>()` | `T` | Преобразовать JSON-строку в модель |
| `addToCollection<T>(value, {allowDuplicates})` | `Future` | Добавить элемент в коллекцию |
| `readCollection<T>()` | `Future<List<T>>` | Прочитать коллекцию |
| `deleteFromStorage({andFromBackpack})` | `Future` | Удалить из хранилища |
| `flush({andFromBackpack})` | `Future` | Алиас для deleteFromStorage |
| `defaultValue<T>(value)` | `Future Function(bool)?` | Установить значение по умолчанию, если ключ пуст (используется в syncedOnBoot) |

<div id="storage-exceptions"></div>

## Исключения хранилища

{{ config('app.name') }} v7 предоставляет типизированные исключения хранилища:

| Исключение | Описание |
|-----------|-------------|
| `StorageException` | Базовое исключение с сообщением и необязательным ключом |
| `StorageSerializationException` | Не удалось сериализовать объект для хранения |
| `StorageDeserializationException` | Не удалось десериализовать сохранённые данные |
| `StorageKeyNotFoundException` | Ключ хранилища не найден |
| `StorageTimeoutException` | Превышено время ожидания операции хранилища |

``` dart
try {
  await NyStorage.read<User>('user');
} on StorageDeserializationException catch (e) {
  print('Не удалось загрузить пользователя: ${e.message}');
  print('Ожидаемый тип: ${e.expectedType}');
}
```

<div id="legacy-migration"></div>

## Миграция с предыдущих версий

{{ config('app.name') }} v7 использует новый формат хранения envelope. При обновлении с v6 вы можете мигрировать старые данные:

``` dart
// Вызовите при инициализации приложения
int migrated = await NyStorage.migrateToEnvelopeFormat();
print('Мигрировано $migrated ключей в новый формат');
```

Это преобразует устаревший формат (отдельные ключи `_runtime_type`) в новый формат envelope. Миграцию безопасно запускать многократно -- уже мигрированные ключи пропускаются.
