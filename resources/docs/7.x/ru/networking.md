# Networking

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- Выполнение HTTP-запросов
  - [Удобные методы](#convenience-methods "Удобные методы")
  - [Хелпер network](#network-helper "Хелпер network")
  - [Хелпер networkResponse](#network-response-helper "Хелпер networkResponse")
  - [NyResponse](#ny-response "NyResponse")
  - [Базовые параметры](#base-options "Базовые параметры")
  - [Добавление заголовков](#adding-headers "Добавление заголовков")
- Операции с файлами
  - [Загрузка файлов на сервер](#uploading-files "Загрузка файлов на сервер")
  - [Скачивание файлов](#downloading-files "Скачивание файлов")
- [Перехватчики](#interceptors "Перехватчики")
  - [Сетевой логгер](#network-logger "Сетевой логгер")
- [Использование API-сервиса](#using-an-api-service "Использование API-сервиса")
- [Создание API-сервиса](#create-an-api-service "Создание API-сервиса")
- [Преобразование JSON в модели](#morphing-json-payloads-to-models "Преобразование JSON в модели")
- Кэширование
  - [Кэширование ответов](#caching-responses "Кэширование ответов")
  - [Политики кэширования](#cache-policies "Политики кэширования")
- Обработка ошибок
  - [Повторные попытки при ошибках](#retrying-failed-requests "Повторные попытки при ошибках")
  - [Проверка подключения](#connectivity-checks "Проверка подключения")
  - [Токены отмены](#cancel-tokens "Токены отмены")
- Аутентификация
  - [Установка заголовков аутентификации](#setting-auth-headers "Установка заголовков аутентификации")
  - [Обновление токенов](#refreshing-tokens "Обновление токенов")
- [Singleton API-сервис](#singleton-api-service "Singleton API-сервис")
- [Расширенная конфигурация](#advanced-configuration "Расширенная конфигурация")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} делает работу с сетью простой. Вы определяете конечные точки API в классах-сервисах, которые расширяют `NyApiService`, а затем вызываете их со своих страниц. Фреймворк берёт на себя декодирование JSON, обработку ошибок, кэширование и автоматическое преобразование ответов в классы ваших моделей (так называемый «морфинг»).

Ваши API-сервисы находятся в `lib/app/networking/`. Новый проект включает стандартный `ApiService`:

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
        );

  @override
  String get baseUrl => getEnv('API_BASE_URL');

  @override
  Map<Type, Interceptor> get interceptors => {
    ...super.interceptors,
  };

  Future fetchUsers() async {
    return await network(
      request: (request) => request.get("/users"),
    );
  }
}
```

Существует три способа выполнения HTTP-запросов:

| Подход | Возвращает | Лучше всего для |
|----------|---------|----------|
| Удобные методы (`get`, `post` и т.д.) | `T?` | Простых CRUD-операций |
| `network()` | `T?` | Запросов, требующих кэширования, повторных попыток или пользовательских заголовков |
| `networkResponse()` | `NyResponse<T>` | Когда нужны коды статусов, заголовки или детали ошибок |

Под капотом {{ config('app.name') }} использует <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> -- мощный HTTP-клиент.


<div id="convenience-methods"></div>

## Удобные методы

`NyApiService` предоставляет сокращённые методы для распространённых HTTP-операций. Они внутренне вызывают `network()`.

### GET-запрос

```dart
Future<User?> fetchUser(int id) async {
  return await get<User>(
    "/users/$id",
    queryParameters: {"include": "profile"},
  );
}
```

### POST-запрос

```dart
Future<User?> createUser(Map<String, dynamic> data) async {
  return await post<User>("/users", data: data);
}
```

### PUT-запрос

```dart
Future<User?> updateUser(int id, Map<String, dynamic> data) async {
  return await put<User>("/users/$id", data: data);
}
```

### DELETE-запрос

```dart
Future<bool?> deleteUser(int id) async {
  return await delete<bool>("/users/$id");
}
```

### PATCH-запрос

```dart
Future<User?> patchUser(int id, Map<String, dynamic> data) async {
  return await patch<User>("/users/$id", data: data);
}
```

### HEAD-запрос

Используйте HEAD для проверки существования ресурса или получения заголовков без скачивания тела ответа:

```dart
Future<bool> checkResourceExists(String url) async {
  Response response = await head(url);
  return response.statusCode == 200;
}
```


<div id="network-helper"></div>

## Хелпер network

Метод `network` даёт больше контроля, чем удобные методы. Он возвращает преобразованные данные (`T?`) напрямую.

```dart
class ApiService extends NyApiService {
  ...

  Future<User?> fetchUser(int id) async {
    return await network<User>(
      request: (request) => request.get("/users/$id"),
    );
  }

  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }

  Future<User?> createUser(Map<String, dynamic> data) async {
    return await network<User>(
      request: (request) => request.post("/users", data: data),
    );
  }
}
```

Обратный вызов `request` получает экземпляр <a href="https://pub.dev/packages/dio" target="_BLANK">Dio</a> с уже настроенными базовым URL и перехватчиками.

### Параметры network

| Параметр | Тип | Описание |
|-----------|------|-------------|
| `request` | `Function(Dio)` | HTTP-запрос для выполнения (обязательный) |
| `bearerToken` | `String?` | Bearer-токен для этого запроса |
| `baseUrl` | `String?` | Переопределение базового URL сервиса |
| `headers` | `Map<String, dynamic>?` | Дополнительные заголовки |
| `retry` | `int?` | Количество повторных попыток |
| `retryDelay` | `Duration?` | Задержка между повторными попытками |
| `retryIf` | `bool Function(DioException)?` | Условие для повторной попытки |
| `connectionTimeout` | `Duration?` | Тайм-аут соединения |
| `receiveTimeout` | `Duration?` | Тайм-аут получения данных |
| `sendTimeout` | `Duration?` | Тайм-аут отправки данных |
| `cacheKey` | `String?` | Ключ кэша |
| `cacheDuration` | `Duration?` | Продолжительность кэширования |
| `cachePolicy` | `CachePolicy?` | Стратегия кэширования |
| `checkConnectivity` | `bool?` | Проверка подключения перед запросом |
| `handleSuccess` | `Function(NyResponse<T>)?` | Обратный вызов при успехе |
| `handleFailure` | `Function(NyResponse<T>)?` | Обратный вызов при ошибке |


<div id="network-response-helper"></div>

## Хелпер networkResponse

Используйте `networkResponse`, когда вам нужен доступ к полному ответу -- кодам статусов, заголовкам, сообщениям об ошибках -- а не только к данным. Он возвращает `NyResponse<T>` вместо `T?`.

Используйте `networkResponse`, когда вам нужно:
- Проверять HTTP-коды статусов для специфичной обработки
- Получать доступ к заголовкам ответа
- Получать детальные сообщения об ошибках для обратной связи с пользователем
- Реализовывать пользовательскую логику обработки ошибок

```dart
Future<NyResponse<User>> fetchUser(int id) async {
  return await networkResponse<User>(
    request: (request) => request.get("/users/$id"),
  );
}
```

Затем используйте ответ на вашей странице:

```dart
NyResponse<User> response = await _apiService.fetchUser(1);

if (response.isSuccessful) {
  User? user = response.data;
  print('Status: ${response.statusCode}');
} else {
  print('Error: ${response.errorMessage}');
  print('Status: ${response.statusCode}');
}
```

### network vs networkResponse

```dart
// network() — returns the data directly
User? user = await network<User>(
  request: (request) => request.get("/users/1"),
);

// networkResponse() — returns the full response
NyResponse<User> response = await networkResponse<User>(
  request: (request) => request.get("/users/1"),
);
User? user = response.data;
int? status = response.statusCode;
```

Оба метода принимают одинаковые параметры. Выбирайте `networkResponse`, когда вам нужно анализировать ответ за пределами данных.


<div id="ny-response"></div>

## NyResponse

`NyResponse<T>` оборачивает ответ Dio с преобразованными данными и хелперами статусов.

### Свойства

| Свойство | Тип | Описание |
|----------|------|-------------|
| `response` | `Response?` | Оригинальный ответ Dio |
| `data` | `T?` | Преобразованные/декодированные данные |
| `rawData` | `dynamic` | Необработанные данные ответа |
| `headers` | `Headers?` | Заголовки ответа |
| `statusCode` | `int?` | HTTP-код статуса |
| `statusMessage` | `String?` | HTTP-сообщение статуса |
| `contentType` | `String?` | Тип содержимого из заголовков |
| `errorMessage` | `String?` | Извлечённое сообщение об ошибке |

### Проверки статусов

| Геттер | Описание |
|--------|-------------|
| `isSuccessful` | Статус 200-299 |
| `isClientError` | Статус 400-499 |
| `isServerError` | Статус 500-599 |
| `isRedirect` | Статус 300-399 |
| `hasData` | Данные не равны null |
| `isUnauthorized` | Статус 401 |
| `isForbidden` | Статус 403 |
| `isNotFound` | Статус 404 |
| `isTimeout` | Статус 408 |
| `isConflict` | Статус 409 |
| `isRateLimited` | Статус 429 |

### Хелперы данных

```dart
NyResponse<User> response = await apiService.fetchUser(1);

// Get data or throw if null
User user = response.dataOrThrow('User not found');

// Get data or use a fallback
User user = response.dataOr(User.guest());

// Run callback only if successful
String? greeting = response.ifSuccessful((user) => 'Hello ${user.name}');

// Pattern match on success/failure
String result = response.when(
  success: (user) => 'Welcome, ${user.name}!',
  failure: (response) => 'Error: ${response.statusMessage}',
);

// Get a specific header
String? authHeader = response.getHeader('Authorization');
```


<div id="base-options"></div>

## Базовые параметры

Настройте параметры Dio по умолчанию для вашего API-сервиса с помощью параметра `baseOptions`:

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(
    buildContext,
    decoders: modelDecoders,
    baseOptions: (BaseOptions baseOptions) {
      return baseOptions
        ..connectTimeout = Duration(seconds: 5)
        ..sendTimeout = Duration(seconds: 5)
        ..receiveTimeout = Duration(seconds: 5);
    },
  );
  ...
}
```

Вы также можете динамически настраивать параметры на экземпляре:

```dart
apiService.setConnectTimeout(Duration(seconds: 10));
apiService.setReceiveTimeout(Duration(seconds: 30));
apiService.setSendTimeout(Duration(seconds: 10));
apiService.setContentType('application/json');
```

Нажмите <a href="https://pub.dev/packages/dio#request-options" target="_BLANK">сюда</a>, чтобы посмотреть все доступные базовые параметры.


<div id="adding-headers"></div>

## Добавление заголовков

### Заголовки для отдельного запроса

```dart
Future fetchWithHeaders() async => await network(
  request: (request) => request.get("/test"),
  headers: {
    "Authorization": "Bearer aToken123",
    "Device": "iPhone"
  }
);
```

### Bearer-токен

```dart
Future fetchUser() async => await network(
  request: (request) => request.get("/user"),
  bearerToken: "hello-world-123",
);
```

### Заголовки на уровне сервиса

```dart
apiService.setHeaders({"X-Custom-Header": "value"});
apiService.setBearerToken("my-token");
```

### Расширение RequestHeaders

Тип `RequestHeaders` (typedef для `Map<String, dynamic>`) предоставляет вспомогательные методы:

```dart
@override
Future<RequestHeaders> setAuthHeaders(RequestHeaders headers) async {
  String? token = Auth.data(field: 'token');
  if (token != null) {
    headers.addBearerToken(token);
  }
  headers.addHeader('X-App-Version', '1.0.0');
  return headers;
}
```

| Метод | Описание |
|--------|-------------|
| `addBearerToken(token)` | Установить заголовок `Authorization: Bearer` |
| `getBearerToken()` | Прочитать Bearer-токен из заголовков |
| `addHeader(key, value)` | Добавить пользовательский заголовок |
| `hasHeader(key)` | Проверить наличие заголовка |


<div id="uploading-files"></div>

## Загрузка файлов на сервер

### Загрузка одного файла

```dart
Future<UploadResponse?> uploadAvatar(String filePath) async {
  return await upload<UploadResponse>(
    '/upload',
    filePath: filePath,
    fieldName: 'avatar',
    additionalFields: {'userId': '123'},
    onProgress: (sent, total) {
      double progress = sent / total * 100;
      print('Progress: ${progress.toStringAsFixed(0)}%');
    },
  );
}
```

### Загрузка нескольких файлов

```dart
Future<UploadResponse?> uploadDocuments() async {
  return await uploadMultiple<UploadResponse>(
    '/upload',
    files: {
      'avatar': '/path/to/avatar.jpg',
      'document': '/path/to/doc.pdf',
    },
    additionalFields: {'userId': '123'},
    onProgress: (sent, total) {
      print('Progress: ${(sent / total * 100).toStringAsFixed(0)}%');
    },
  );
}
```


<div id="downloading-files"></div>

## Скачивание файлов

```dart
Future<void> downloadFile(String url, String savePath) async {
  await download(
    url,
    savePath: savePath,
    onProgress: (received, total) {
      if (total != -1) {
        print('Progress: ${(received / total * 100).toStringAsFixed(0)}%');
      }
    },
    deleteOnError: true,
  );
}
```


<div id="interceptors"></div>

## Перехватчики

Перехватчики позволяют модифицировать запросы перед отправкой, обрабатывать ответы и управлять ошибками. Они выполняются при каждом запросе, сделанном через API-сервис.

Используйте перехватчики, когда вам нужно:
- Добавлять заголовки аутентификации ко всем запросам
- Логировать запросы и ответы для отладки
- Глобально трансформировать данные запросов/ответов
- Обрабатывать определённые коды ошибок (например, обновлять токены при 401)

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  Map<Type, Interceptor> get interceptors => {
    ...super.interceptors,
    BearerAuthInterceptor: BearerAuthInterceptor(),
    LoggingInterceptor: LoggingInterceptor(),
  };
  ...
}
```

### Создание пользовательского перехватчика

```bash
metro make:interceptor logging
```

**Файл:** `app/networking/dio/interceptors/logging_interceptor.dart`

```dart
import 'package:nylo_framework/nylo_framework.dart';

class LoggingInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    print('REQUEST[${options.method}] => PATH: ${options.path}');
    return super.onRequest(options, handler);
  }

  @override
  void onResponse(Response response, ResponseInterceptorHandler handler) {
    print('RESPONSE[${response.statusCode}] => PATH: ${response.requestOptions.path}');
    handler.next(response);
  }

  @override
  void onError(DioException dioException, ErrorInterceptorHandler handler) {
    print('ERROR[${dioException.response?.statusCode}] => PATH: ${dioException.requestOptions.path}');
    handler.next(dioException);
  }
}
```


<div id="network-logger"></div>

## Сетевой логгер

{{ config('app.name') }} включает встроенный перехватчик `NetworkLogger`. Он включён по умолчанию, когда `APP_DEBUG` установлен в `true` в вашем окружении.

### Конфигурация

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(
    buildContext,
    decoders: modelDecoders,
    useNetworkLogger: true,
    networkLogger: NetworkLogger(
      logLevel: LogLevelType.verbose,
      request: true,
      requestHeader: true,
      requestBody: true,
      responseBody: true,
      responseHeader: false,
      error: true,
    ),
  );
}
```

Вы можете отключить его, установив `useNetworkLogger: false`.

```
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext})
      : super(
          buildContext,
          decoders: modelDecoders,
          useNetworkLogger: false, // <-- Disable logger
        );
```

### Уровни логирования

| Уровень | Описание |
|-------|-------------|
| `LogLevelType.verbose` | Выводить все детали запросов/ответов |
| `LogLevelType.minimal` | Выводить только метод, URL, статус и время |
| `LogLevelType.none` | Без вывода логов |

### Фильтрация логов

```dart
NetworkLogger(
  filter: (options, args) {
    // Only log requests to specific endpoints
    return options.path.contains('/api/v1');
  },
)
```


<div id="using-an-api-service"></div>

## Использование API-сервиса

Существует два способа вызова API-сервиса со страницы.

### Прямое создание экземпляра

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  ApiService _apiService = ApiService();

  @override
  get init => () async {
    List<User>? users = await _apiService.fetchUsers();
    print(users);
  };
}
```

### Использование хелпера api()

Хелпер `api` создаёт экземпляры, используя ваши `apiDecoders` из `config/decoders.dart`:

```dart
class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () async {
    User? user = await api<ApiService>((request) => request.fetchUser());
    print(user);
  };
}
```

С обратными вызовами:

```dart
await api<ApiService>(
  (request) => request.fetchUser(),
  onSuccess: (response, data) {
    // data is the morphed User? instance
  },
  onError: (DioException dioException) {
    // Handle the error
  },
);
```

### Параметры хелпера api()

| Параметр | Тип | Описание |
|-----------|------|-------------|
| `request` | `Function(T)` | Функция API-запроса |
| `context` | `BuildContext?` | Контекст сборки |
| `headers` | `Map<String, dynamic>` | Дополнительные заголовки |
| `bearerToken` | `String?` | Bearer-токен |
| `baseUrl` | `String?` | Переопределение базового URL |
| `page` | `int?` | Страница пагинации |
| `perPage` | `int?` | Элементов на страницу |
| `retry` | `int` | Повторные попытки |
| `retryDelay` | `Duration?` | Задержка между повторными попытками |
| `onSuccess` | `Function(Response, dynamic)?` | Обратный вызов при успехе |
| `onError` | `Function(DioException)?` | Обратный вызов при ошибке |
| `cacheKey` | `String?` | Ключ кэша |
| `cacheDuration` | `Duration?` | Продолжительность кэширования |


<div id="create-an-api-service"></div>

## Создание API-сервиса

Чтобы создать новый API-сервис:

```bash
metro make:api_service user
```

С моделью:

```bash
metro make:api_service user --model="User"
```

Это создаст API-сервис с CRUD-методами:

```dart
class UserApiService extends NyApiService {
  ...

  Future<List<User>?> fetchAll({dynamic query}) async {
    return await network<List<User>>(
      request: (request) => request.get("/endpoint-path", queryParameters: query),
    );
  }

  Future<User?> find({required int id}) async {
    return await network<User>(
      request: (request) => request.get("/endpoint-path/$id"),
    );
  }

  Future<User?> create({required dynamic data}) async {
    return await network<User>(
      request: (request) => request.post("/endpoint-path", data: data),
    );
  }

  Future<User?> update({dynamic query}) async {
    return await network<User>(
      request: (request) => request.put("/endpoint-path", queryParameters: query),
    );
  }

  Future<bool?> delete({required int id}) async {
    return await network<bool>(
      request: (request) => request.delete("/endpoint-path/$id"),
    );
  }
}
```


<div id="morphing-json-payloads-to-models"></div>

## Преобразование JSON в модели

«Морфинг» -- это термин {{ config('app.name') }} для автоматического преобразования JSON-ответов в классы моделей Dart. Когда вы используете `network<User>(...)`, JSON ответа передаётся через ваш декодер для создания экземпляра `User` -- ручной парсинг не нужен.

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  // Returns a single User
  Future<User?> fetchUser() async {
    return await network<User>(
      request: (request) => request.get("/user/1"),
    );
  }

  // Returns a List of Users
  Future<List<User>?> fetchUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
    );
  }
}
```

Декодеры определяются в `lib/bootstrap/decoders.dart`:

```dart
final Map<Type, dynamic> modelDecoders = {
  User: (data) => User.fromJson(data),

  List<User>: (data) =>
      List.from(data).map((json) => User.fromJson(json)).toList(),
};
```

Параметр типа, который вы передаёте в `network<T>()`, сопоставляется с вашей картой `modelDecoders` для поиска нужного декодера.

**Смотрите также:** [Decoders](/docs/{{$version}}/decoders#model-decoders) для получения подробностей о регистрации декодеров моделей.


<div id="caching-responses"></div>

## Кэширование ответов

Кэшируйте ответы для сокращения API-вызовов и повышения производительности. Кэширование полезно для данных, которые не меняются часто, например, списки стран, категории или конфигурации.

Укажите `cacheKey` и опциональный `cacheDuration`:

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
  ) ?? [];
}
```

### Очистка кэша

```dart
// Clear a specific cache key
await apiService.clearCache("app_countries");

// Clear all API cache
await apiService.clearAllCache();
```

### Кэширование с хелпером api()

```dart
api<ApiService>(
  (request) => request.fetchCountries(),
  cacheKey: "app_countries",
  cacheDuration: const Duration(hours: 1),
);
```


<div id="cache-policies"></div>

## Политики кэширования

Используйте `CachePolicy` для тонкой настройки поведения кэширования:

| Политика | Описание |
|--------|-------------|
| `CachePolicy.networkOnly` | Всегда запрашивать из сети (по умолчанию) |
| `CachePolicy.cacheFirst` | Сначала пробовать кэш, затем сеть |
| `CachePolicy.networkFirst` | Сначала пробовать сеть, затем кэш |
| `CachePolicy.cacheOnly` | Использовать только кэш, ошибка при отсутствии данных |
| `CachePolicy.staleWhileRevalidate` | Вернуть кэш сразу, обновить в фоновом режиме |

### Использование

```dart
Future<List<Country>> fetchCountries() async {
  return await network<List<Country>>(
    request: (request) => request.get("/countries"),
    cacheKey: "app_countries",
    cacheDuration: const Duration(hours: 1),
    cachePolicy: CachePolicy.staleWhileRevalidate,
  ) ?? [];
}
```

### Когда использовать каждую политику

- **cacheFirst** -- данные, которые редко меняются. Мгновенно возвращает кэшированные данные, запрашивает из сети только при пустом кэше.
- **networkFirst** -- данные, которые должны быть свежими когда возможно. Сначала пробует сеть, при ошибке использует кэш.
- **staleWhileRevalidate** -- UI, которому нужен мгновенный ответ, но данные должны обновляться. Возвращает кэшированные данные, затем обновляет в фоне.
- **cacheOnly** -- автономный режим. Выбрасывает ошибку при отсутствии кэшированных данных.

> **Примечание:** Если вы указываете `cacheKey` или `cacheDuration` без `cachePolicy`, политика по умолчанию -- `cacheFirst`.


<div id="retrying-failed-requests"></div>

## Повторные попытки при ошибках

Автоматический повтор запросов при сбое.

### Базовый повтор

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
  );
}
```

### Повтор с задержкой

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryDelay: Duration(seconds: 2),
  );
}
```

### Условный повтор

```dart
Future fetchUsers() async {
  return await network(
    request: (request) => request.get("/users"),
    retry: 3,
    retryIf: (DioException dioException) {
      // Only retry on server errors
      return dioException.response?.statusCode == 500;
    },
  );
}
```

### Повтор на уровне сервиса

```dart
apiService.setRetry(3);
apiService.setRetryDelay(Duration(seconds: 2));
apiService.setRetryIf((dioException) => dioException.response?.statusCode == 500);
```


<div id="connectivity-checks"></div>

## Проверка подключения

Быстрая ошибка при отсутствии подключения устройства вместо ожидания тайм-аута.

### На уровне сервиса

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(buildContext, decoders: modelDecoders);

  @override
  bool get checkConnectivityBeforeRequest => true;
  ...
}
```

### Для отдельного запроса

```dart
await network(
  request: (request) => request.get("/users"),
  checkConnectivity: true,
);
```

### Динамически

```dart
apiService.setCheckConnectivityBeforeRequest(true);
```

Когда функция включена и устройство не в сети:
- Политика `networkFirst` переключается на кэш
- Другие политики сразу выбрасывают `DioExceptionType.connectionError`


<div id="cancel-tokens"></div>

## Токены отмены

Управление и отмена ожидающих запросов.

```dart
// Create a managed cancel token
final token = apiService.createCancelToken();
await apiService.get('/endpoint', cancelToken: token);

// Cancel all pending requests (e.g., on logout)
apiService.cancelAllRequests('User logged out');

// Check active request count
int count = apiService.activeRequestCount;

// Clean up a specific token when done
apiService.removeCancelToken(token);
```


<div id="setting-auth-headers"></div>

## Установка заголовков аутентификации

Переопределите `setAuthHeaders` для прикрепления заголовков аутентификации к каждому запросу. Этот метод вызывается перед каждым запросом, когда `shouldSetAuthHeaders` равен `true` (по умолчанию).

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<RequestHeaders> setAuthHeaders(RequestHeaders headers) async {
    String? myAuthToken = Auth.data(field: 'token');
    if (myAuthToken != null) {
      headers.addBearerToken(myAuthToken);
    }
    return headers;
  }
}
```

### Отключение заголовков аутентификации

Для публичных конечных точек, не требующих аутентификации:

```dart
// Per-request
await network(
  request: (request) => request.get("/public-endpoint"),
  shouldSetAuthHeaders: false,
);

// Service-level
apiService.setShouldSetAuthHeaders(false);
```

**Смотрите также:** [Authentication](/docs/{{ $version }}/authentication) для получения подробностей об аутентификации пользователей и хранении токенов.


<div id="refreshing-tokens"></div>

## Обновление токенов

Переопределите `shouldRefreshToken` и `refreshToken` для обработки истечения срока токена. Они вызываются перед каждым запросом.

```dart
class ApiService extends NyApiService {
  ...

  @override
  Future<bool> shouldRefreshToken() async {
    // Check if the token needs refreshing
    return false;
  }

  @override
  Future<void> refreshToken(Dio dio) async {
    // Use the fresh Dio instance (no interceptors) to refresh the token
    dynamic response = (await dio.post("https://example.com/refresh-token")).data;

    // Save the new token to storage
    await Auth.set((data) {
      data['token'] = response['token'];
      return data;
    });
  }
}
```

Параметр `dio` в `refreshToken` -- это новый экземпляр Dio, отдельный от основного экземпляра сервиса, чтобы избежать зацикливания перехватчиков.


<div id="singleton-api-service"></div>

## Singleton API-сервис

По умолчанию хелпер `api` создаёт новый экземпляр каждый раз. Чтобы использовать синглтон, передайте экземпляр вместо фабрики в `config/decoders.dart`:

```dart
final Map<Type, dynamic> apiDecoders = {
  ApiService: () => ApiService(), // New instance each time

  ApiService: ApiService(), // Singleton — same instance always
};
```


<div id="advanced-configuration"></div>

## Расширенная конфигурация

### Пользовательская инициализация Dio

```dart
class ApiService extends NyApiService {
  ApiService({BuildContext? buildContext}) : super(
    buildContext,
    decoders: modelDecoders,
    initDio: (Dio dio) {
      dio.options.validateStatus = (status) => status! < 500;
      return dio;
    },
  );
}
```

### Доступ к экземпляру Dio

```dart
Dio dioInstance = apiService.dio;

Response response = await dioInstance.request(
  '/custom-endpoint',
  options: Options(method: 'OPTIONS'),
);
```

### Хелпер пагинации

```dart
apiService.setPagination(
  1,
  paramPage: 'page',
  paramPerPage: 'per_page',
  perPage: '20',
);
```

### Обратные вызовы событий

```dart
apiService.onSuccess((response, data) {
  print('Success: ${response.statusCode}');
});

apiService.onError((dioException) {
  print('Error: ${dioException.message}');
});
```

### Переопределяемые свойства

| Свойство | Тип | По умолчанию | Описание |
|----------|------|---------|-------------|
| `baseUrl` | `String` | `""` | Базовый URL для всех запросов |
| `interceptors` | `Map<Type, Interceptor>` | `{}` | Перехватчики Dio |
| `decoders` | `Map<Type, dynamic>?` | `{}` | Декодеры моделей для морфинга JSON |
| `shouldSetAuthHeaders` | `bool` | `true` | Вызывать ли `setAuthHeaders` перед запросами |
| `retry` | `int` | `0` | Количество повторных попыток по умолчанию |
| `retryDelay` | `Duration` | `1 second` | Задержка между повторными попытками по умолчанию |
| `checkConnectivityBeforeRequest` | `bool` | `false` | Проверять подключение перед запросами |
