# Authentication

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в аутентификацию")
- Основы
  - [Аутентификация пользователей](#authenticating-users "Аутентификация пользователей")
  - [Получение данных аутентификации](#retrieving-auth-data "Получение данных аутентификации")
  - [Обновление данных аутентификации](#updating-auth-data "Обновление данных аутентификации")
  - [Выход из системы](#logging-out "Выход из системы")
  - [Проверка аутентификации](#checking-authentication "Проверка аутентификации")
- Продвинутое использование
  - [Множественные сессии](#multiple-sessions "Множественные сессии")
  - [Идентификатор устройства](#device-id "Идентификатор устройства")
  - [Синхронизация с Backpack](#syncing-to-backpack "Синхронизация с Backpack")
- Настройка маршрутов
  - [Начальный маршрут](#initial-route "Начальный маршрут")
  - [Маршрут для авторизованных](#authenticated-route "Маршрут для авторизованных")
  - [Маршрут предпросмотра](#preview-route "Маршрут предпросмотра")
  - [Маршрут для неизвестных путей](#unknown-route "Маршрут для неизвестных путей")
- [Вспомогательные функции](#helper-functions "Вспомогательные функции")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} v7 предоставляет комплексную систему аутентификации через класс `Auth`. Она обеспечивает безопасное хранение учётных данных пользователя, управление сессиями и поддерживает множественные именованные сессии для различных контекстов аутентификации.

Данные аутентификации хранятся безопасно и синхронизируются с Backpack (хранилище ключ-значение в памяти) для быстрого синхронного доступа по всему приложению.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Authenticate a user
await Auth.authenticate(data: {"token": "abc123", "userId": 1});

// Check if authenticated
bool loggedIn = await Auth.isAuthenticated(); // true

// Get auth data
dynamic token = Auth.data(field: 'token'); // "abc123"

// Logout
await Auth.logout();
```


<div id="authenticating-users"></div>

## Аутентификация пользователей

Используйте `Auth.authenticate()` для сохранения данных сессии пользователя:

``` dart
// With a Map
await Auth.authenticate(data: {
  "token": "ey2sdm...",
  "userId": 123,
  "email": "user@example.com",
});

// With a Model class
User user = User(id: 123, email: "user@example.com", token: "ey2sdm...");
await Auth.authenticate(data: user);

// Without data (stores a timestamp)
await Auth.authenticate();
```

### Пример из реальной практики

``` dart
class _LoginPageState extends NyPage<LoginPage> {

  Future<void> handleLogin(String email, String password) async {
    // 1. Call your API to authenticate
    User? user = await api<AuthApiService>((request) => request.login(
      email: email,
      password: password,
    ));

    if (user == null) {
      showToastDanger(description: "Invalid credentials");
      return;
    }

    // 2. Store the authenticated user
    await Auth.authenticate(data: user);

    // 3. Navigate to home
    routeTo(HomePage.path, removeUntil: (_) => false);
  }
}
```


<div id="retrieving-auth-data"></div>

## Получение данных аутентификации

Получайте сохранённые данные аутентификации с помощью `Auth.data()`:

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

Метод `Auth.data()` читает данные из Backpack (хранилище ключ-значение в памяти {{ config('app.name') }}) для быстрого синхронного доступа. Данные автоматически синхронизируются с Backpack при аутентификации.


<div id="updating-auth-data"></div>

## Обновление данных аутентификации

{{ config('app.name') }} v7 представляет `Auth.set()` для обновления данных аутентификации:

``` dart
// Update a specific field
await Auth.set((data) {
  data['token'] = 'new_token_value';
  return data;
});

// Add new fields
await Auth.set((data) {
  data['refreshToken'] = 'refresh_abc123';
  data['lastLogin'] = DateTime.now().toIso8601String();
  return data;
});

// Replace entire data
await Auth.set((data) => {
  'token': newToken,
  'userId': userId,
});
```


<div id="logging-out"></div>

## Выход из системы

Удалите аутентифицированного пользователя с помощью `Auth.logout()`:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### Выход из всех сессий

При использовании множественных сессий очистите их все:

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## Проверка аутентификации

Проверьте, аутентифицирован ли пользователь в данный момент:

``` dart
bool isLoggedIn = await Auth.isAuthenticated();

if (isLoggedIn) {
  // User is authenticated
  routeTo(HomePage.path);
} else {
  // User needs to login
  routeTo(LoginPage.path);
}
```


<div id="multiple-sessions"></div>

## Множественные сессии

{{ config('app.name') }} v7 поддерживает множественные именованные сессии аутентификации для различных контекстов. Это полезно, когда необходимо отслеживать разные типы аутентификации по отдельности (например, вход пользователя, регистрация устройства, доступ администратора).

``` dart
// Default user session
await Auth.authenticate(data: user);

// Device authentication session
await Auth.authenticate(
  data: {"deviceToken": "abc123"},
  session: 'device',
);

// Admin session
await Auth.authenticate(
  data: adminUser,
  session: 'admin',
);
```

### Чтение из именованных сессий

``` dart
// Default session
dynamic userData = Auth.data();
String? userToken = Auth.data(field: 'token');

// Device session
dynamic deviceData = Auth.data(session: 'device');
String? deviceToken = Auth.data(field: 'deviceToken', session: 'device');

// Admin session
dynamic adminData = Auth.data(session: 'admin');
```

### Выход из конкретной сессии

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### Проверка аутентификации по сессиям

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## Идентификатор устройства

{{ config('app.name') }} v7 предоставляет уникальный идентификатор устройства, который сохраняется между сессиями приложения:

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

Идентификатор устройства:
- Генерируется один раз и сохраняется навсегда
- Уникален для каждого устройства/установки
- Полезен для регистрации устройств, аналитики или push-уведомлений

``` dart
// Example: Register device with backend
Future<void> registerDevice() async {
  String deviceId = await Auth.deviceId();
  String? pushToken = await FirebaseMessaging.instance.getToken();

  await api<DeviceApiService>((request) => request.register(
    deviceId: deviceId,
    pushToken: pushToken,
  ));

  // Store device auth
  await Auth.authenticate(
    data: {"deviceId": deviceId, "pushToken": pushToken},
    session: 'device',
  );
}
```


<div id="syncing-to-backpack"></div>

## Синхронизация с Backpack

Данные аутентификации автоматически синхронизируются с Backpack при аутентификации. Для ручной синхронизации (например, при запуске приложения):

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

Это полезно в последовательности загрузки приложения для обеспечения доступности данных аутентификации в Backpack для быстрого синхронного доступа.


<div id="initial-route"></div>

## Начальный маршрут

Начальный маршрут -- это первая страница, которую видят пользователи при открытии приложения. Установите его с помощью `.initialRoute()` в вашем маршрутизаторе:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

Вы также можете установить условный начальный маршрут с помощью параметра `when`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(OnboardingPage.path).initialRoute(
    when: () => !hasCompletedOnboarding()
  );

  router.add(HomePage.path).initialRoute(
    when: () => hasCompletedOnboarding()
  );
});
```

Перейдите обратно к начальному маршруту из любого места с помощью `routeToInitial()`:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## Маршрут для авторизованных

Маршрут для авторизованных переопределяет начальный маршрут, когда пользователь вошёл в систему. Установите его с помощью `.authenticatedRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

При запуске приложения:
- `Auth.isAuthenticated()` возвращает `true` -- пользователь видит **маршрут для авторизованных** (HomePage)
- `Auth.isAuthenticated()` возвращает `false` -- пользователь видит **начальный маршрут** (LoginPage)

Вы также можете установить условный маршрут для авторизованных:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

Программно перейдите к маршруту для авторизованных с помощью `routeToAuthenticatedRoute()`:

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**Смотрите также:** [Маршрутизатор](/docs/{{ $version }}/router) для полной документации по маршрутизации, включая защиту маршрутов и глубокие ссылки.


<div id="preview-route"></div>

## Маршрут предпросмотра

Во время разработки вы можете захотеть быстро просмотреть конкретную страницу без изменения начального маршрута или маршрута для авторизованных. Используйте `.previewRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()` переопределяет **оба** маршрута `initialRoute()` и `authenticatedRoute()`, делая указанный маршрут первой отображаемой страницей независимо от состояния аутентификации.

> **Внимание:** Удалите `.previewRoute()` перед выпуском приложения.


<div id="unknown-route"></div>

## Маршрут для неизвестных путей

Определите резервную страницу для случаев, когда пользователь переходит к несуществующему маршруту. Установите его с помощью `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### Полная картина

Вот полная настройка маршрутизатора со всеми типами маршрутов:

``` dart
appRouter() => nyRoutes((router) {
  // First page for unauthenticated users
  router.add(LoginPage.path).initialRoute();

  // First page for authenticated users
  router.add(HomePage.path).authenticatedRoute();

  // 404 page
  router.add(NotFoundPage.path).unknownRoute();

  // Regular routes
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

| Метод маршрута | Назначение |
|--------------|---------|
| `.initialRoute()` | Первая страница для неавторизованных пользователей |
| `.authenticatedRoute()` | Первая страница для авторизованных пользователей |
| `.previewRoute()` | Переопределяет оба маршрута во время разработки |
| `.unknownRoute()` | Отображается, когда маршрут не найден |


<div id="helper-functions"></div>

## Вспомогательные функции

{{ config('app.name') }} v7 предоставляет вспомогательные функции, которые дублируют методы класса `Auth`:

| Вспомогательная функция | Эквивалент |
|-----------------|------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | Ключ хранилища для сессии по умолчанию |
| `authDeviceId()` | `Auth.deviceId()` |

Все вспомогательные функции принимают те же параметры, что и их аналоги в классе `Auth`, включая необязательный параметр `session`:

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```
