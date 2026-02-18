# Testing

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Начало работы](#getting-started "Начало работы")
- [Написание тестов](#writing-tests "Написание тестов")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [Утилиты тестирования виджетов](#widget-testing-utilities "Утилиты тестирования виджетов")
  - [nyGroup](#ny-group "nyGroup")
  - [Жизненный цикл тестов](#test-lifecycle "Жизненный цикл тестов")
  - [Пропуск и CI-тесты](#skipping-tests "Пропуск и CI-тесты")
- [Аутентификация](#authentication "Аутентификация")
- [Путешествие во времени](#time-travel "Путешествие во времени")
- [Мокирование API](#api-mocking "Мокирование API")
  - [Мокирование по шаблону URL](#mocking-by-url "Мокирование по шаблону URL")
  - [Мокирование по типу API-сервиса](#mocking-by-type "Мокирование по типу API-сервиса")
  - [История вызовов и проверки](#call-history "История вызовов и проверки")
- [Фабрики](#factories "Фабрики")
  - [Определение фабрик](#defining-factories "Определение фабрик")
  - [Состояния фабрик](#factory-states "Состояния фабрик")
  - [Создание экземпляров](#creating-instances "Создание экземпляров")
- [NyFaker](#ny-faker "NyFaker")
- [Тестовый кэш](#test-cache "Тестовый кэш")
- [Мокирование платформенных каналов](#platform-channel-mocking "Мокирование платформенных каналов")
- [Мокирование Route Guard](#route-guard-mocking "Мокирование Route Guard")
- [Утверждения](#assertions "Утверждения")
- [Пользовательские матчеры](#custom-matchers "Пользовательские матчеры")
- [Тестирование состояния](#state-testing "Тестирование состояния")
- [Отладка](#debugging "Отладка")
- [Примеры](#examples "Практические примеры")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} v7 включает комплексный фреймворк тестирования, вдохновлённый утилитами тестирования Laravel. Он предоставляет:

- **Тестовые функции** с автоматической настройкой/очисткой (`nyTest`, `nyWidgetTest`, `nyGroup`)
- **Симуляцию аутентификации** через `NyTest.actingAs<T>()`
- **Путешествие во времени** для заморозки или манипуляции временем в тестах
- **Мокирование API** с сопоставлением шаблонов URL и отслеживанием вызовов
- **Фабрики** со встроенным генератором фейковых данных (`NyFaker`)
- **Мокирование платформенных каналов** для secure storage, path provider и другого
- **Пользовательские утверждения** для маршрутов, Backpack, аутентификации и окружения

<div id="getting-started"></div>

## Начало работы

Инициализируйте тестовый фреймворк в начале вашего тестового файла:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` настраивает тестовое окружение и включает автоматический сброс состояния между тестами, когда `autoReset: true` (по умолчанию).

<div id="writing-tests"></div>

## Написание тестов

<div id="ny-test"></div>

### nyTest

Основная функция для написания тестов:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

Параметры:

``` dart
nyTest('my test', () async {
  // тело теста
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

Для тестирования виджетов Flutter с `WidgetTester`:

``` dart
nyWidgetTest('renders a button', (WidgetTester tester) async {
  await tester.pumpWidget(MaterialApp(
    home: Scaffold(
      body: ElevatedButton(
        onPressed: () {},
        child: Text("Tap me"),
      ),
    ),
  ));

  expect(find.text("Tap me"), findsOneWidget);
});
```

<div id="widget-testing-utilities"></div>

### Утилиты тестирования виджетов

Класс `NyWidgetTest` и расширения `WidgetTester` предоставляют хелперы для рендеринга виджетов Nylo с правильной поддержкой тем, ожидания завершения `init()` и тестирования состояний загрузки.

#### Настройка тестового окружения

Вызовите `NyWidgetTest.configure()` в `setUpAll`, чтобы отключить загрузку Google Fonts и опционально установить пользовательскую тему:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

Вы можете сбросить конфигурацию с помощью `NyWidgetTest.reset()`.

Для тестирования без шрифтов доступны две встроенные темы:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Рендеринг виджетов Nylo

Используйте `pumpNyWidget` для обёртывания виджета в `MaterialApp` с поддержкой тем:

``` dart
nyWidgetTest('renders page', (tester) async {
  await tester.pumpNyWidget(
    HomePage(),
    theme: ThemeData.light(),
    darkTheme: ThemeData.dark(),
    themeMode: ThemeMode.light,
    settleTimeout: Duration(seconds: 5),
    useSimpleTheme: false,
  );

  expect(find.text('Welcome'), findsOneWidget);
});
```

Для быстрого рендеринга с темой без шрифтов:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Ожидание Init

`pumpNyWidgetAndWaitForInit` рендерит кадры до исчезновения индикаторов загрузки (или до истечения таймаута), что полезно для страниц с асинхронными методами `init()`:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() завершён
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Вспомогательные методы Pump

``` dart
// Рендерить кадры до появления конкретного виджета
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Корректное завершение (не выбрасывает исключение при таймауте)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Симуляция жизненного цикла

Симуляция изменений `AppLifecycleState` для любого `NyPage` в дереве виджетов:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Проверка побочных эффектов от паузы жизненного цикла
```

#### Проверки загрузки и блокировки

Проверка именованных ключей загрузки и блокировок на виджетах `NyPage`/`NyState`:

``` dart
// Проверить, активен ли именованный ключ загрузки
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Проверить, удерживается ли именованная блокировка
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Проверить наличие любого индикатора загрузки (CircularProgressIndicator или Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### Хелпер testNyPage

Удобная функция, которая рендерит `NyPage`, ожидает init, затем выполняет проверки:

``` dart
testNyPage(
  'HomePage loads correctly',
  build: () => HomePage(),
  expectations: (tester) async {
    expect(find.text('Welcome'), findsOneWidget);
  },
  useSimpleTheme: true,
  initTimeout: Duration(seconds: 10),
  skip: false,
);
```

#### Хелпер testNyPageLoading

Тестирование отображения индикатора загрузки во время `init()`:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

Миксин, предоставляющий общие утилиты тестирования страниц:

``` dart
class HomePageTest with NyPageTestMixin {
  void runTests(WidgetTester tester) async {
    // Проверить, что init был вызван и загрузка завершена
    await verifyInitCalled(tester, HomePage(), timeout: Duration(seconds: 5));

    // Проверить, что состояние загрузки отображается во время init
    await verifyLoadingState(tester, HomePage());
  }
}
```

<div id="ny-group"></div>

### nyGroup

Группировка связанных тестов:

``` dart
nyGroup('Authentication', () {
  nyTest('can login', () async {
    NyTest.actingAs<User>(User(name: "Anthony"));
    expectAuthenticated<User>();
  });

  nyTest('can logout', () async {
    NyTest.actingAs<User>(User(name: "Anthony"));
    NyTest.logout();
    expectGuest();
  });
});
```

<div id="test-lifecycle"></div>

### Жизненный цикл тестов

Настройка и очистка с помощью хуков жизненного цикла:

``` dart
void main() {
  NyTest.init();

  nySetUpAll(() {
    // Выполняется один раз перед всеми тестами
  });

  nySetUp(() {
    // Выполняется перед каждым тестом
  });

  nyTearDown(() {
    // Выполняется после каждого теста
  });

  nyTearDownAll(() {
    // Выполняется один раз после всех тестов
  });
}
```

<div id="skipping-tests"></div>

### Пропуск и CI-тесты

``` dart
// Пропустить тест с указанием причины
nySkip('not implemented yet', () async {
  // ...
}, "Waiting for API update");

// Тесты, которые ожидаемо падают
nyFailing('known bug', () async {
  // ...
});

// Тесты только для CI (помечены тегом 'ci')
nyCi('integration test', () async {
  // Выполняются только в CI-окружении
});
```

<div id="authentication"></div>

## Аутентификация

Симуляция аутентифицированных пользователей в тестах:

``` dart
nyTest('user can access profile', () async {
  // Симулировать авторизованного пользователя
  NyTest.actingAs<User>(User(name: "Anthony", email: "anthony@example.com"));

  // Проверить аутентификацию
  expectAuthenticated<User>();

  // Получить доступ к текущему пользователю
  User? user = NyTest.actingUser<User>();
  expect(user?.name, equals("Anthony"));
});

nyTest('guest cannot access profile', () async {
  // Проверить, что пользователь не аутентифицирован
  expectGuest();
});
```

Выход пользователя из системы:

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## Путешествие во времени

Манипуляция временем в тестах с помощью `NyTime`:

### Переход к конкретной дате

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Вернуться к реальному времени
});
```

### Перемещение вперёд или назад

``` dart
NyTest.travelForward(Duration(days: 30)); // Перейти на 30 дней вперёд
NyTest.travelBackward(Duration(hours: 2)); // Вернуться на 2 часа назад
```

### Заморозка времени

``` dart
NyTest.freezeTime(); // Заморозить текущий момент

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Время не сдвинулось

NyTest.travelBack(); // Разморозить
```

### Границы времени

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1-е число текущего месяца
NyTime.travelToEndOfMonth();   // Последний день текущего месяца
NyTime.travelToStartOfYear();  // 1 января
NyTime.travelToEndOfYear();    // 31 декабря
```

### Путешествие во времени с ограниченной областью

Выполнение кода в контексте замороженного времени:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Время автоматически восстанавливается после callback
```

<div id="api-mocking"></div>

## Мокирование API

<div id="mocking-by-url"></div>

### Мокирование по шаблону URL

Мокирование ответов API с помощью шаблонов URL с поддержкой подстановочных знаков:

``` dart
nyTest('mock API responses', () async {
  // Точное совпадение URL
  NyMockApi.respond('/users/1', {'id': 1, 'name': 'Anthony'});

  // Подстановочный знак для одного сегмента (*)
  NyMockApi.respond('/users/*', {'id': 1, 'name': 'User'});

  // Подстановочный знак для нескольких сегментов (**)
  NyMockApi.respond('/api/**', {'status': 'ok'});

  // С кодом состояния и заголовками
  NyMockApi.respond(
    '/users',
    {'error': 'Unauthorized'},
    statusCode: 401,
    method: 'POST',
    headers: {'X-Error': 'true'},
  );

  // С имитацией задержки
  NyMockApi.respond(
    '/slow-endpoint',
    {'data': 'loaded'},
    delay: Duration(seconds: 2),
  );
});
```

<div id="mocking-by-type"></div>

### Мокирование по типу API-сервиса

Мокирование всего API-сервиса по типу:

``` dart
nyTest('mock API service', () async {
  NyMockApi.register<UserApiService>((MockApiRequest request) async {
    if (request.endpoint.contains('/users')) {
      return {'users': [{'id': 1, 'name': 'Anthony'}]};
    }
    return {'error': 'not found'};
  });
});
```

<div id="call-history"></div>

### История вызовов и проверки

Отслеживание и проверка вызовов API:

``` dart
nyTest('verify API was called', () async {
  NyMockApi.setRecordCalls(true);

  // ... выполнение действий, вызывающих API ...

  // Проверить, что endpoint был вызван
  expectApiCalled('/users');

  // Проверить, что endpoint не вызывался
  expectApiNotCalled('/admin');

  // Проверить количество вызовов
  expectApiCalled('/users', times: 2);

  // Проверить конкретный метод
  expectApiCalled('/users', method: 'POST');

  // Получить детали вызовов
  List<ApiCallInfo> calls = NyMockApi.getCallsFor('/users');
});
```

### Создание мок-ответов

``` dart
Response<Map<String, dynamic>> response = NyMockApi.createResponse(
  data: {'id': 1, 'name': 'Anthony'},
  statusCode: 200,
  statusMessage: 'OK',
);
```

<div id="factories"></div>

## Фабрики

<div id="defining-factories"></div>

### Определение фабрик

Определение способа создания тестовых экземпляров ваших моделей:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

С поддержкой переопределения:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### Состояния фабрик

Определение вариаций фабрики:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### Создание экземпляров

``` dart
// Создать один экземпляр
User user = NyFactory.make<User>();

// Создать с переопределениями
User admin = NyFactory.make<User>(overrides: {'name': 'Admin User'});

// Создать с применёнными состояниями
User premiumAdmin = NyFactory.make<User>(states: ['admin', 'premium']);

// Создать несколько экземпляров
List<User> users = NyFactory.create<User>(count: 5);

// Создать последовательность с данными на основе индекса
List<User> numbered = NyFactory.sequence<User>(3, (int index, NyFaker faker) {
  return User(name: "User ${index + 1}", email: faker.email());
});
```

<div id="ny-faker"></div>

## NyFaker

`NyFaker` генерирует реалистичные фейковые данные для тестов. Он доступен внутри определений фабрик и может быть создан напрямую.

``` dart
NyFaker faker = NyFaker();
```

### Доступные методы

| Категория | Метод | Тип возврата | Описание |
|----------|--------|-------------|-------------|
| **Имена** | `faker.firstName()` | `String` | Случайное имя |
| | `faker.lastName()` | `String` | Случайная фамилия |
| | `faker.name()` | `String` | Полное имя (имя + фамилия) |
| | `faker.username()` | `String` | Имя пользователя |
| **Контакты** | `faker.email()` | `String` | Адрес электронной почты |
| | `faker.phone()` | `String` | Номер телефона |
| | `faker.company()` | `String` | Название компании |
| **Числа** | `faker.randomInt(min, max)` | `int` | Случайное целое число в диапазоне |
| | `faker.randomDouble(min, max)` | `double` | Случайное дробное число в диапазоне |
| | `faker.randomBool()` | `bool` | Случайное логическое значение |
| **Идентификаторы** | `faker.uuid()` | `String` | Строка UUID v4 |
| **Даты** | `faker.date()` | `DateTime` | Случайная дата |
| | `faker.pastDate()` | `DateTime` | Дата в прошлом |
| | `faker.futureDate()` | `DateTime` | Дата в будущем |
| **Текст** | `faker.lorem()` | `String` | Слова Lorem ipsum |
| | `faker.sentences()` | `String` | Несколько предложений |
| | `faker.paragraphs()` | `String` | Несколько абзацев |
| | `faker.slug()` | `String` | URL slug |
| **Веб** | `faker.url()` | `String` | Строка URL |
| | `faker.imageUrl()` | `String` | URL изображения (через picsum.photos) |
| | `faker.ipAddress()` | `String` | IPv4-адрес |
| | `faker.macAddress()` | `String` | MAC-адрес |
| **Местоположение** | `faker.address()` | `String` | Уличный адрес |
| | `faker.city()` | `String` | Название города |
| | `faker.state()` | `String` | Сокращение штата США |
| | `faker.zipCode()` | `String` | Почтовый индекс |
| | `faker.country()` | `String` | Название страны |
| **Прочее** | `faker.hexColor()` | `String` | Шестнадцатеричный код цвета |
| | `faker.creditCardNumber()` | `String` | Номер кредитной карты |
| | `faker.randomElement(list)` | `T` | Случайный элемент из списка |
| | `faker.randomElements(list, count)` | `List<T>` | Случайные элементы из списка |

<div id="test-cache"></div>

## Тестовый кэш

`NyTestCache` предоставляет кэш в оперативной памяти для тестирования функциональности кэширования:

``` dart
nyTest('cache operations', () async {
  NyTestCache cache = NyTest.cache;

  // Сохранить значение
  await cache.put<String>("key", "value");

  // Сохранить с истечением срока
  await cache.put<String>("temp", "data", seconds: 60);

  // Прочитать значение
  String? value = await cache.get<String>("key");

  // Проверить существование
  bool exists = await cache.has("key");

  // Очистить ключ
  await cache.clear("key");

  // Очистить всё
  await cache.flush();

  // Получить информацию о кэше
  int size = await cache.size();
  List<String> keys = await cache.documents();
});
```

<div id="platform-channel-mocking"></div>

## Мокирование платформенных каналов

`NyMockChannels` автоматически мокирует распространённые платформенные каналы, чтобы тесты не падали:

``` dart
void main() {
  NyTest.init(); // Автоматически настраивает мок-каналы

  // Или настроить вручную
  NyMockChannels.setup();
}
```

### Мокированные каналы

- **path_provider** -- директории документов, временные, поддержки приложения, библиотеки и кэша
- **flutter_secure_storage** -- secure storage в оперативной памяти
- **flutter_timezone** -- данные часового пояса
- **flutter_local_notifications** -- канал уведомлений
- **sqflite** -- операции с базой данных

### Переопределение путей

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### Secure Storage в тестах

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## Мокирование Route Guard

`NyMockRouteGuard` позволяет тестировать поведение route guard без реальной аутентификации или сетевых вызовов. Он наследует `NyRouteGuard` и предоставляет фабричные конструкторы для распространённых сценариев.

### Guard, который всегда пропускает

``` dart
final guard = NyMockRouteGuard.pass();
```

### Guard, который перенаправляет

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// С дополнительными данными
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Guard с пользовательской логикой

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // прервать навигацию
  }
  return GuardResult.next; // разрешить навигацию
});
```

### Отслеживание вызовов Guard

После вызова guard вы можете проверить его состояние:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Доступ к RouteContext из последнего вызова
RouteContext? context = guard.lastContext;

// Сброс отслеживания
guard.reset();
```

<div id="assertions"></div>

## Утверждения

{{ config('app.name') }} предоставляет пользовательские функции утверждений:

### Утверждения маршрутов

``` dart
expectRoute('/home');           // Проверить текущий маршрут
expectNotRoute('/login');       // Проверить, что не на маршруте
expectRouteInHistory('/home');  // Проверить, что маршрут был посещён
expectRouteExists('/profile');  // Проверить, что маршрут зарегистрирован
expectRoutesExist(['/home', '/profile', '/settings']);
```

### Утверждения состояния

``` dart
expectBackpackContains("key");                        // Ключ существует
expectBackpackContains("key", value: "expected");     // Ключ имеет значение
expectBackpackNotContains("key");                     // Ключ не существует
```

### Утверждения аутентификации

``` dart
expectAuthenticated<User>();  // Пользователь аутентифицирован
expectGuest();                // Пользователь не аутентифицирован
```

### Утверждения окружения

``` dart
expectEnv("APP_NAME", "MyApp");  // Переменная окружения равна значению
expectEnvSet("APP_KEY");          // Переменная окружения установлена
```

### Утверждения режима

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### Утверждения API

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### Утверждения локали

``` dart
expectLocale("en");
```

### Утверждения Toast-уведомлений

Проверка toast-уведомлений, записанных во время теста. Требуется `NyToastRecorder.setup()` в setUp:

``` dart
setUp(() {
  NyToastRecorder.setup();
});

nyWidgetTest('shows success toast', (tester) async {
  await tester.pumpNyWidget(MyPage());
  // ... вызвать действие, показывающее toast ...

  expectToastShown(id: 'success');
  expectToastShown(id: 'danger', description: 'Something went wrong');
  expectNoToastShown(id: 'info');
});
```

**NyToastRecorder** отслеживает toast-уведомления во время тестов:

``` dart
// Записать toast вручную
NyToastRecorder.record(id: 'success', title: 'Done', description: 'Saved!');

// Проверить, был ли показан toast
bool shown = NyToastRecorder.wasShown(id: 'success');

// Доступ ко всем записанным toast
List<ToastRecord> toasts = NyToastRecorder.records;

// Очистить записанные toast
NyToastRecorder.clear();
```

### Утверждения блокировки и загрузки

Проверка именованных состояний блокировки и загрузки в виджетах `NyPage`/`NyState`:

``` dart
// Проверить, что именованная блокировка удерживается
expectLocked(tester, find.byType(MyPage), 'submit');

// Проверить, что именованная блокировка не удерживается
expectNotLocked(tester, find.byType(MyPage), 'submit');

// Проверить, что именованный ключ загрузки активен
expectLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');

// Проверить, что именованный ключ загрузки не активен
expectNotLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');
```

<div id="custom-matchers"></div>

## Пользовательские матчеры

Использование пользовательских матчеров с `expect()`:

``` dart
// Матчер типа
expect(result, isType<User>());

// Матчер имени маршрута
expect(widget, hasRouteName('/home'));

// Матчер Backpack
expect(true, backpackHas("key", value: "expected"));

// Матчер вызова API
expect(true, apiWasCalled('/users', method: 'GET', times: 1));
```

<div id="state-testing"></div>

## Тестирование состояния

Тестирование управления состоянием на основе EventBus в виджетах `NyPage` и `NyState` с помощью хелперов тестирования состояния.

### Отправка обновлений состояния

Симуляция обновлений состояния, которые обычно приходят от другого виджета или контроллера:

``` dart
// Отправить событие UpdateState
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### Отправка действий состояния

Отправка действий состояния, обрабатываемых `whenStateAction()` на вашей странице:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// С дополнительными данными
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### Утверждения состояния

``` dart
// Проверить, что обновление состояния было отправлено
expectStateUpdated('HomePageState');
expectStateUpdated('HomePageState', times: 2);

// Проверить, что действие состояния было отправлено
expectStateAction('HomePageState', 'refresh-page');
expectStateAction('CartState', 'add-item', times: 1);

// Проверить stateData виджета NyPage/NyState
expectStateData(tester, find.byType(MyWidget), equals(42));
```

### NyStateTestHelpers

Отслеживание и инспекция отправленных обновлений и действий состояния:

``` dart
// Получить все обновления, отправленные состоянию
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Получить все действия, отправленные состоянию
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Сбросить все отслеживаемые обновления и действия состояния
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## Отладка

### dump

Вывод текущего состояния теста (содержимое Backpack, пользователь, время, вызовы API, локаль):

``` dart
NyTest.dump();
```

### dd (Dump and Die)

Вывод состояния теста и немедленное завершение теста:

``` dart
NyTest.dd();
```

### Хранилище состояния теста

Сохранение и получение значений во время теста:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Заполнение Backpack

Предварительное заполнение Backpack тестовыми данными:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## Примеры

### Полный тестовый файл

``` dart
import 'package:flutter_test/flutter_test.dart';
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyGroup('User Authentication', () {
    nyTest('can authenticate a user', () async {
      NyFactory.define<User>((faker) => User(
        name: faker.name(),
        email: faker.email(),
      ));

      User user = NyFactory.make<User>();
      NyTest.actingAs<User>(user);

      expectAuthenticated<User>();
    });

    nyTest('guest has no access', () async {
      expectGuest();
    });
  });

  nyGroup('API Integration', () {
    nyTest('can fetch users', () async {
      NyMockApi.setRecordCalls(true);
      NyMockApi.respond('/api/users', {
        'users': [
          {'id': 1, 'name': 'Anthony'},
          {'id': 2, 'name': 'Jane'},
        ]
      });

      // ... вызвать API ...

      expectApiCalled('/api/users');
    });
  });

  nyGroup('Time-Sensitive Features', () {
    nyTest('subscription expires correctly', () async {
      NyTest.travel(DateTime(2025, 1, 1));

      // Тестирование логики подписки в известную дату
      expect(NyTime.now().year, equals(2025));

      NyTest.travelForward(Duration(days: 365));
      expect(NyTime.now().year, equals(2026));

      NyTest.travelBack();
    });
  });
}
```
