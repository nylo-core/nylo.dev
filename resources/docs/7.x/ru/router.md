# Router

---

<a name="section-1"></a>

- [Введение](#introduction "Введение")
- Основы
  - [Добавление маршрутов](#adding-routes "Добавление маршрутов")
  - [Навигация на страницы](#navigating-to-pages "Навигация на страницы")
  - [Начальный маршрут](#initial-route "Начальный маршрут")
  - [Предварительный просмотр маршрута](#preview-route "Предварительный просмотр маршрута")
  - [Аутентифицированный маршрут](#authenticated-route "Аутентифицированный маршрут")
  - [Неизвестный маршрут](#unknown-route "Неизвестный маршрут")
- Передача данных на другую страницу
  - [Передача данных на другую страницу](#passing-data-to-another-page "Передача данных на другую страницу")
- Навигация
  - [Типы навигации](#navigation-types "Типы навигации")
  - [Навигация назад](#navigating-back "Навигация назад")
  - [Условная навигация](#conditional-navigation "Условная навигация")
  - [Переходы между страницами](#page-transitions "Переходы между страницами")
  - [История маршрутов](#route-history "История маршрутов")
  - [Обновление стека маршрутов](#update-route-stack "Обновление стека маршрутов")
- Параметры маршрутов
  - [Использование параметров маршрута](#route-parameters "Параметры маршрута")
  - [Query-параметры](#query-parameters "Query-параметры")
- Охранники маршрутов (Route Guards)
  - [Создание Route Guards](#route-guards "Route Guards")
  - [Жизненный цикл NyRouteGuard](#nyroute-guard-lifecycle "Жизненный цикл NyRouteGuard")
  - [Вспомогательные методы Guard](#guard-helper-methods "Вспомогательные методы Guard")
  - [Параметризованные Guards](#parameterized-guards "Параметризованные Guards")
  - [Стеки Guards](#guard-stacks "Стеки Guards")
  - [Условные Guards](#conditional-guards "Условные Guards")
- Группы маршрутов
  - [Группы маршрутов](#route-groups "Группы маршрутов")
- [Deep linking](#deep-linking "Deep linking")
- [Продвинутые возможности](#advanced "Продвинутые возможности")



<div id="introduction"></div>

## Введение

Маршруты позволяют определять различные страницы в вашем приложении и осуществлять навигацию между ними.

Используйте маршруты, когда вам нужно:
- Определить доступные страницы в приложении
- Навигировать пользователей между экранами
- Защитить страницы аутентификацией
- Передать данные с одной страницы на другую
- Обработать deep links из URL

Вы можете добавлять маршруты в файле `lib/routes/router.dart`.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // добавить ещё маршруты
  // router.add(AccountPage.path);

});
```

> **Совет:** Вы можете создавать маршруты вручную или использовать инструмент <a href="/docs/{{ $version }}/metro">Metro</a> CLI для их автоматического создания.

Вот пример создания страницы 'account' с помощью Metro.

``` bash
metro make:page account_page
```

``` dart
// Автоматически добавляет новый маршрут в /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

Вам также может понадобиться передавать данные из одного представления в другое. В {{ config('app.name') }} это возможно с помощью `NyStatefulWidget` (stateful-виджет со встроенным доступом к данным маршрута). Далее мы рассмотрим это подробнее.


<div id="adding-routes"></div>

## Добавление маршрутов

Это самый простой способ добавить новые маршруты в ваш проект.

Выполните следующую команду для создания новой страницы.

```bash
metro make:page profile_page
```

После выполнения будет создан новый виджет `ProfilePage`, который будет добавлен в директорию `resources/pages/`.
Также новый маршрут будет добавлен в файл `lib/routes/router.dart`.

Файл: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // Мой новый маршрут
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## Навигация на страницы

Вы можете переходить на новые страницы с помощью хелпера `routeTo`.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## Начальный маршрут

В маршрутизаторе вы можете определить первую страницу, которая должна загружаться, с помощью метода `.initialRoute()`.

После установки начального маршрута он будет первой страницей, которая загружается при открытии приложения.

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // новый начальный маршрут
});
```


### Условный начальный маршрут

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

### Навигация к начальному маршруту

Используйте `routeToInitial()` для навигации к начальному маршруту приложения:

``` dart
void _goHome() {
    routeToInitial();
}
```

Это выполнит навигацию к маршруту, отмеченному `.initialRoute()`, и очистит стек навигации.

<div id="preview-route"></div>

## Предварительный просмотр маршрута

Во время разработки вы можете захотеть быстро просмотреть конкретную страницу, не меняя начальный маршрут навсегда. Используйте `.previewRoute()`, чтобы временно сделать любой маршрут начальным:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // Эта страница будет показана первой во время разработки
});
```

Метод `previewRoute()`:
- Переопределяет любые существующие настройки `initialRoute()` и `authenticatedRoute()`
- Делает указанный маршрут начальным
- Полезен для быстрого тестирования определённых страниц во время разработки

> **Внимание:** Не забудьте удалить `.previewRoute()` перед выпуском приложения!

<div id="authenticated-route"></div>

## Аутентифицированный маршрут

В вашем приложении вы можете определить маршрут, который будет начальным, когда пользователь аутентифицирован.
Он автоматически переопределит начальный маршрут по умолчанию и станет первой страницей, которую видит пользователь при входе.

Сначала пользователь должен быть авторизован с помощью хелпера `Auth.authenticate({...})`.

Теперь при открытии приложения определённый вами маршрут будет страницей по умолчанию, пока пользователь не выйдет из системы.

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // страница аутентификации
});
```

### Условный аутентифицированный маршрут

Вы также можете задать условный аутентифицированный маршрут:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### Навигация к аутентифицированному маршруту

Вы можете перейти на аутентифицированную страницу с помощью хелпера `routeToAuthenticatedRoute()`:

``` dart
routeToAuthenticatedRoute();
```

**Смотрите также:** [Аутентификация](/docs/{{ $version }}/authentication) для получения информации об аутентификации пользователей и управлении сессиями.


<div id="unknown-route"></div>

## Неизвестный маршрут

Вы можете определить маршрут для обработки сценариев 404/не найдено с помощью `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

Когда пользователь переходит на несуществующий маршрут, ему будет показана страница неизвестного маршрута.


<div id="route-guards"></div>

## Охранники маршрутов (Route Guards)

Route guards защищают страницы от несанкционированного доступа. Они выполняются до завершения навигации, позволяя перенаправить пользователей или заблокировать доступ на основе условий.

Используйте route guards, когда вам нужно:
- Защитить страницы от неаутентифицированных пользователей
- Проверить разрешения перед предоставлением доступа
- Перенаправить пользователей на основе условий (например, незавершённый онбординг)
- Логировать или отслеживать просмотры страниц

Чтобы создать новый Route Guard, выполните следующую команду.

``` bash
metro make:route_guard dashboard
```

Затем добавьте новый Route Guard к вашему маршруту.

``` dart
// Файл: /routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(LoginPage.path);

  router.add(DashboardPage.path,
    routeGuards: [
      DashboardRouteGuard() // Добавьте ваш guard
    ]
  ); // защищённая страница
});
```

Вы также можете установить route guards с помощью метода `addRouteGuard`:

``` dart
// Файл: /routes/router.dart
appRouter() => nyRoutes((router) {
    router.add(DashboardPage.path)
            .addRouteGuard(MyRouteGuard());

    // или добавить несколько guards

    router.add(DashboardPage.path)
            .addRouteGuards([MyRouteGuard(), MyOtherRouteGuard()]);
})
```

<div id="nyroute-guard-lifecycle"></div>

## Жизненный цикл NyRouteGuard

В v7 route guards используют класс `NyRouteGuard` с тремя методами жизненного цикла:

- **`onBefore(RouteContext context)`** -- вызывается перед навигацией. Верните `next()` для продолжения, `redirect()` для перенаправления или `abort()` для остановки.
- **`onAfter(RouteContext context)`** -- вызывается после успешной навигации на маршрут.

### Простой пример

Файл: **/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Проверяем, может ли пользователь получить доступ к странице
    bool userLoggedIn = await Auth.isAuthenticated();

    if (userLoggedIn == false) {
      return redirect(LoginPage.path);
    }

    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    // Отслеживаем просмотр страницы после успешной навигации
    Analytics.trackPageView(context.routeName);
  }
}
```

### RouteContext

Класс `RouteContext` предоставляет доступ к информации о навигации:

| Свойство | Тип | Описание |
|----------|------|-------------|
| `context` | `BuildContext?` | Текущий контекст сборки |
| `data` | `dynamic` | Данные, переданные маршруту |
| `queryParameters` | `Map<String, String>` | Query-параметры URL |
| `routeName` | `String` | Имя/путь маршрута |
| `originalRouteName` | `String?` | Исходное имя маршрута до преобразований |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  print('Navigating to: ${context.routeName}');
  print('Query params: ${context.queryParameters}');
  print('Route data: ${context.data}');

  return next();
}
```

<div id="guard-helper-methods"></div>

## Вспомогательные методы Guard

### next()

Продолжить к следующему guard или к маршруту:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Разрешить навигацию
}
```

### redirect()

Перенаправить на другой маршрут:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (!isLoggedIn) {
    return redirect(
      LoginPage.path,
      data: {'returnTo': context.routeName},
      navigationType: NavigationType.pushReplace,
    );
  }
  return next();
}
```

Метод `redirect()` принимает:

| Параметр | Тип | Описание |
|-----------|------|-------------|
| `path` | `Object` | Путь маршрута или RouteView |
| `data` | `dynamic` | Данные для передачи маршруту |
| `queryParameters` | `Map<String, dynamic>?` | Query-параметры |
| `navigationType` | `NavigationType` | Тип навигации (по умолчанию: pushReplace) |
| `transitionType` | `TransitionType?` | Переход страницы |
| `onPop` | `Function(dynamic)?` | Callback при закрытии маршрута |

### abort()

Остановить навигацию без перенаправления:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (isMaintenanceMode) {
    showMaintenanceDialog();
    return abort(); // Пользователь остаётся на текущем маршруте
  }
  return next();
}
```

### setData()

Изменить данные, передаваемые последующим guards и маршруту:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## Параметризованные Guards

Используйте `ParameterizedGuard`, когда нужно настроить поведение guard для конкретного маршрута:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    final user = await Auth.user();
    if (!params.any((role) => user.hasRole(role))) {
      return redirect('/unauthorized');
    }
    return next();
  }
}

// Использование:
router.add(AdminPage.path, routeGuards: [
  RoleGuard(['admin', 'moderator'])
]);
```

<div id="guard-stacks"></div>

## Стеки Guards

Объединяйте несколько guards в один переиспользуемый guard с помощью `GuardStack`:

``` dart
// Создание переиспользуемых комбинаций guards
final adminGuards = GuardStack([
  AuthGuard(),
  RoleGuard(['admin']),
  AuditLogGuard(),
]);

router.add(AdminPage.path, routeGuards: [adminGuards]);
```

<div id="conditional-guards"></div>

## Условные Guards

Применяйте guards условно на основе предиката:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## Передача данных на другую страницу

В этом разделе мы покажем, как передавать данные из одного виджета в другой.

Из вашего виджета используйте хелпер `routeTo` и передайте `data`, которые хотите отправить на новую страницу.

``` dart
// Виджет HomePage
void _pressedSettings() {
    routeTo(SettingsPage.path, data: "Hello World");
}
...
// Виджет SettingsPage (другая страница)
class _SettingsPageState extends NyPage<SettingsPage> {
  ...
  @override
  get init => () {
    print(widget.data()); // Hello World
    // или
    print(data()); // Hello World
  };
```

Дополнительные примеры

``` dart
// Виджет домашней страницы
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo(ProfilePage.path, data: user);
  }

...

// Виджет страницы профиля (другая страница)
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    User user = widget.data();
    print(user.firstName); // Anthony

  };
```


<div id="route-groups"></div>

## Группы маршрутов

Группы маршрутов организуют связанные маршруты и применяют общие настройки. Они полезны, когда нескольким маршрутам нужны одинаковые guards, префикс URL или стиль перехода.

Используйте группы маршрутов, когда вам нужно:
- Применить один и тот же route guard к нескольким страницам
- Добавить префикс URL для набора маршрутов (например, `/admin/...`)
- Установить одинаковый переход страниц для связанных маршрутов

Вы можете определить группу маршрутов, как в примере ниже.

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.group(() => {
    "route_guards": [AuthRouteGuard()],
    "prefix": "/dashboard",
    "transition_type": TransitionType.fade(),
  }, (router) {
    router.add(ChatPage.path);

    router.add(FollowersPage.path);
  });
```

#### Доступные настройки для групп маршрутов:

| Настройка | Тип | Описание |
|---------|------|-------------|
| `route_guards` | `List<RouteGuard>` | Применить route guards ко всем маршрутам в группе |
| `prefix` | `String` | Добавить префикс ко всем путям маршрутов в группе |
| `transition_type` | `TransitionType` | Установить переход для всех маршрутов в группе |
| `transition` | `PageTransitionType` | Установить тип перехода страницы (устарело, используйте transition_type) |
| `transition_settings` | `PageTransitionSettings` | Установить настройки перехода |


<div id="route-parameters"></div>

## Использование параметров маршрута

При создании новой страницы вы можете обновить маршрут для принятия параметров.

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

Теперь при навигации на страницу вы можете передать `userId`

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

Вы можете получить доступ к параметрам на новой странице следующим образом.

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## Query-параметры

При навигации на новую страницу вы также можете передать query-параметры.

Давайте рассмотрим пример.

```dart
  // Домашняя страница
  routeTo(ProfilePage.path, queryParameters: {"user": "7"});
  // навигация на страницу профиля

  ...

  // Страница профиля
  @override
  get init => () {
    print(widget.queryParameters()); // {"user": 7}
    // или
    print(queryParameters()); // {"user": 7}
  };
```

> **Примечание:** Если ваш виджет страницы наследует `NyStatefulWidget` и класс `NyPage`, то вы можете вызвать `widget.queryParameters()` для получения всех query-параметров из имени маршрута.

```dart
// Пример страницы
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// Домашняя страница
class MyHomePage extends NyStatefulWidget<HomeController> {
  ...
}

class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () {
    widget.queryParameters(); // {"hello": "World", "say": "I love code"}
    // или
    queryParameters(); // {"hello": "World", "say": "I love code"}
  };
```

> **Совет:** Query-параметры должны соответствовать протоколу HTTP, например: /account?userId=1&tab=2


<div id="page-transitions"></div>

## Переходы между страницами

Вы можете добавлять переходы при навигации с одной страницы, изменив файл `router.dart`.

``` dart
import 'package:page_transition/page_transition.dart';

appRouter() => nyRoutes((router) {

  // снизу вверх
  router.add(SettingsPage.path,
    transitionType: TransitionType.bottomToTop()
  );

  // затухание
  router.add(HomePage.path,
    transitionType: TransitionType.fade()
  );

});
```

### Доступные переходы страниц

#### Базовые переходы
- **`TransitionType.fade()`** -- плавное появление новой страницы с затуханием предыдущей
- **`TransitionType.theme()`** -- использует тему переходов страниц приложения

#### Направленные переходы со сдвигом
- **`TransitionType.rightToLeft()`** -- сдвиг от правого края экрана
- **`TransitionType.leftToRight()`** -- сдвиг от левого края экрана
- **`TransitionType.topToBottom()`** -- сдвиг от верхнего края экрана
- **`TransitionType.bottomToTop()`** -- сдвиг от нижнего края экрана

#### Переходы со сдвигом и затуханием
- **`TransitionType.rightToLeftWithFade()`** -- сдвиг с затуханием от правого края
- **`TransitionType.leftToRightWithFade()`** -- сдвиг с затуханием от левого края

#### Переходы с трансформацией
- **`TransitionType.scale(alignment: ...)`** -- масштабирование от указанной точки
- **`TransitionType.rotate(alignment: ...)`** -- вращение вокруг указанной точки
- **`TransitionType.size(alignment: ...)`** -- увеличение от указанной точки

#### Совместные переходы (требуется текущий виджет)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** -- текущая страница уходит вправо, новая появляется слева
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** -- текущая страница уходит влево, новая появляется справа
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** -- текущая страница уходит вниз, новая появляется сверху
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** -- текущая страница уходит вверх, новая появляется снизу

#### Pop-переходы (требуется текущий виджет)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** -- текущая страница уходит вправо, новая остаётся на месте
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** -- текущая страница уходит влево, новая остаётся на месте
- **`TransitionType.topToBottomPop(childCurrent: ...)`** -- текущая страница уходит вниз, новая остаётся на месте
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** -- текущая страница уходит вверх, новая остаётся на месте

#### Переходы Material Design Shared Axis
- **`TransitionType.sharedAxisHorizontal()`** -- горизонтальный сдвиг с затуханием
- **`TransitionType.sharedAxisVertical()`** -- вертикальный сдвиг с затуханием
- **`TransitionType.sharedAxisScale()`** -- масштабирование с затуханием

#### Параметры настройки
Каждый переход принимает следующие необязательные параметры:

| Параметр | Описание | По умолчанию |
|-----------|-------------|---------|
| `curve` | Кривая анимации | Платформенно-зависимые кривые |
| `duration` | Длительность анимации | Платформенно-зависимые длительности |
| `reverseDuration` | Длительность обратной анимации | Такая же, как duration |
| `fullscreenDialog` | Является ли маршрут полноэкранным диалогом | `false` |
| `opaque` | Является ли маршрут непрозрачным | `false` |


``` dart
// Виджет домашней страницы
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(ProfilePage.path,
      transitionType: TransitionType.bottomToTop()
    );
  }
...
```


<div id="navigation-types"></div>

## Типы навигации

При навигации вы можете указать один из следующих типов, если используете хелпер `routeTo`.

| Тип | Описание |
|------|-------------|
| `NavigationType.push` | Добавить новую страницу в стек маршрутов приложения |
| `NavigationType.pushReplace` | Заменить текущий маршрут, удалив предыдущий после завершения нового |
| `NavigationType.popAndPushNamed` | Закрыть текущий маршрут и открыть именованный маршрут вместо него |
| `NavigationType.pushAndRemoveUntil` | Добавить маршрут и удалять маршруты, пока предикат не вернёт true |
| `NavigationType.pushAndForgetAll` | Перейти на новую страницу и удалить все остальные страницы из стека |

``` dart
// Виджет домашней страницы
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(
      ProfilePage.path,
      navigationType: NavigationType.pushReplace
    );
  }
...
```


<div id="navigating-back"></div>

## Навигация назад

Находясь на новой странице, вы можете использовать хелпер `pop()` для возврата на предыдущую страницу.

``` dart
// Виджет SettingsPage
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop();
    // или
    Navigator.pop(context);
  }
...
```

Если вы хотите вернуть значение предыдущему виджету, передайте `result`, как в примере ниже.

``` dart
// Виджет SettingsPage
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// Получение значения от предыдущего виджета с помощью параметра `onPop`
// Виджет HomePage
class _HomePageState extends NyPage<HomePage> {

  _viewSettings() {
    routeTo(SettingsPage.path, onPop: (value) {
      print(value); // {"status": "COMPLETE"}
    });
  }
...

```

<div id="conditional-navigation"></div>

## Условная навигация

Используйте `routeIf()` для навигации только при выполнении условия:

``` dart
// Навигация только если пользователь авторизован
routeIf(isLoggedIn, DashboardPage.path);

// С дополнительными параметрами
routeIf(
  hasPermission('view_reports'),
  ReportsPage.path,
  data: {'filters': defaultFilters},
  navigationType: NavigationType.push,
);
```

Если условие равно `false`, навигация не выполняется.


<div id="route-history"></div>

## История маршрутов

В {{ config('app.name') }} вы можете получить доступ к информации об истории маршрутов с помощью следующих хелперов.

``` dart
// Получить историю маршрутов
Nylo.getRouteHistory(); // List<dynamic>

// Получить текущий маршрут
Nylo.getCurrentRoute(); // Route<dynamic>?

// Получить предыдущий маршрут
Nylo.getPreviousRoute(); // Route<dynamic>?

// Получить имя текущего маршрута
Nylo.getCurrentRouteName(); // String?

// Получить имя предыдущего маршрута
Nylo.getPreviousRouteName(); // String?

// Получить аргументы текущего маршрута
Nylo.getCurrentRouteArguments(); // dynamic

// Получить аргументы предыдущего маршрута
Nylo.getPreviousRouteArguments(); // dynamic
```


<div id="update-route-stack"></div>

## Обновление стека маршрутов

Вы можете программно обновить стек навигации с помощью `NyNavigator.updateStack()`:

``` dart
// Обновить стек списком маршрутов
NyNavigator.updateStack([
  HomePage.path,
  SettingsPage.path,
  ProfilePage.path,
], replace: true);

// Передать данные конкретным маршрутам
NyNavigator.updateStack([
  HomePage.path,
  ProfilePage.path,
],
  replace: true,
  dataForRoute: {
    ProfilePage.path: {"userId": 42}
  }
);
```

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `routes` | `List<String>` | обязательный | Список путей маршрутов для навигации |
| `replace` | `bool` | `true` | Заменять ли текущий стек |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | Данные для передачи конкретным маршрутам |

Это полезно для:
- Сценариев deep linking
- Восстановления состояния навигации
- Построения сложных потоков навигации


<div id="deep-linking"></div>

## Deep Linking

Deep linking позволяет пользователям переходить непосредственно к конкретному содержимому в вашем приложении с помощью URL. Это полезно для:
- Совместного использования прямых ссылок на конкретное содержимое приложения
- Маркетинговых кампаний, нацеленных на определённые функции приложения
- Обработки уведомлений, которые должны открывать конкретные экраны
- Плавного перехода между веб-версией и приложением

## Настройка

Перед внедрением deep linking в приложении убедитесь, что проект правильно настроен:

### 1. Настройка платформы

**iOS**: Настройте universal links в проекте Xcode
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Руководство по настройке Flutter Universal Links</a>

**Android**: Настройте app links в AndroidManifest.xml
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Руководство по настройке Flutter App Links</a>

### 2. Определение маршрутов

Все маршруты, которые должны быть доступны через deep links, должны быть зарегистрированы в конфигурации маршрутизатора:

```dart
// Файл: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  // Базовые маршруты
  router.add(HomePage.path).initialRoute();
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);

  // Маршрут с параметрами
  router.add(HotelBookingPage.path);
});
```

## Использование Deep Links

После настройки ваше приложение может обрабатывать входящие URL в различных форматах:

### Базовые Deep Links

Простая навигация к конкретным страницам:

``` bash
https://yourdomain.com/profile       // Открывает страницу профиля
https://yourdomain.com/settings      // Открывает страницу настроек
```

Для программного вызова этих навигаций внутри приложения:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### Параметры пути

Для маршрутов, которым требуются динамические данные как часть пути:

#### Определение маршрута

```dart
class HotelBookingPage extends NyStatefulWidget {
  // Определение маршрута с параметром-заполнителем {id}
  static RouteView path = ("/hotel/{id}/booking", (_) => HotelBookingPage());

  HotelBookingPage({super.key}) : super(child: () => _HotelBookingPageState());
}

class _HotelBookingPageState extends NyPage<HotelBookingPage> {
  @override
  get init => () {
    // Доступ к параметру пути
    final hotelId = queryParameters()["id"]; // Возвращает "87" для URL ../hotel/87/booking
    print("Loading hotel ID: $hotelId");

    // Использование ID для получения данных об отеле или выполнения операций
  };

  // Остальная реализация страницы
}
```

#### Формат URL

``` bash
https://yourdomain.com/hotel/87/booking
```

#### Программная навигация

```dart
// Навигация с параметрами
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### Query-параметры

Для необязательных параметров или когда нужны несколько динамических значений:

#### Формат URL

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### Доступ к Query-параметрам

```dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  get init => () {
    // Получить все query-параметры
    final params = queryParameters();

    // Доступ к конкретным параметрам
    final userId = params["user"];            // "20"
    final activeTab = params["tab"];          // "posts"

    // Альтернативный способ доступа
    final params2 = widget.queryParameters();
    print(params2);                           // {"user": "20", "tab": "posts"}
  };
}
```

#### Программная навигация с Query-параметрами

```dart
// Навигация с query-параметрами
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Комбинирование параметров пути и query-параметров
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## Обработка Deep Links

Вы можете обрабатывать события deep link в вашем `RouteProvider`:

```dart
class RouteProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.addRouter(appRouter());

    // Обработка deep links
    nylo.onDeepLink(_onDeepLink);
    return nylo;
  }

  _onDeepLink(String route, Map<String, String>? data) {
    print("Deep link route: $route");
    print("Deep link data: $data");

    // Обновление стека маршрутов для deep links
    if (route == ProfilePage.path) {
      NyNavigator.updateStack([
        HomePage.path,
        ProfilePage.path,
      ], replace: true, dataForRoute: {
        ProfilePage.path: data,
      });
    }
  }

  @override
  boot(Nylo nylo) async {
    nylo.initRoutes();
  }
}
```

### Тестирование Deep Links

Для разработки и тестирования вы можете имитировать активацию deep link с помощью ADB (Android) или xcrun (iOS):

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### Советы по отладке

- Выводите все параметры в методе init для проверки корректного парсинга
- Тестируйте различные форматы URL, чтобы убедиться, что приложение обрабатывает их правильно
- Помните, что query-параметры всегда приходят как строки -- конвертируйте их в нужный тип по необходимости

---

## Распространённые паттерны

### Преобразование типов параметров

Поскольку все параметры URL передаются как строки, часто необходимо их конвертировать:

```dart
// Преобразование строковых параметров в соответствующие типы
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### Необязательные параметры

Обработка случаев, когда параметры могут отсутствовать:

```dart
final userId = queryParameters()["user"];
if (userId != null) {
  // Загрузить профиль конкретного пользователя
} else {
  // Загрузить профиль текущего пользователя
}

// Или проверить hasQueryParameter
if (hasQueryParameter('status')) {
  // Выполнить действие с параметром status
} else {
  // Обработать отсутствие параметра
}
```


<div id="advanced"></div>

## Продвинутые возможности

### Проверка существования маршрута

Вы можете проверить, зарегистрирован ли маршрут в вашем маршрутизаторе:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### Методы NyRouter

Класс `NyRouter` предоставляет несколько полезных методов:

| Метод | Описание |
|--------|-------------|
| `getRegisteredRouteNames()` | Получить все зарегистрированные имена маршрутов в виде списка |
| `getRegisteredRoutes()` | Получить все зарегистрированные маршруты в виде карты |
| `containsRoutes(routes)` | Проверить, содержит ли маршрутизатор все указанные маршруты |
| `getInitialRouteName()` | Получить имя начального маршрута |
| `getAuthRouteName()` | Получить имя аутентифицированного маршрута |
| `getUnknownRouteName()` | Получить имя неизвестного/404 маршрута |

### Получение аргументов маршрута

Вы можете получить аргументы маршрута с помощью `NyRouter.args<T>()`:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    // Получить типизированные аргументы
    final args = NyRouter.args<NyArgument>(context);
    final userData = args?.data;

    return Scaffold(...);
  }
}
```

### NyArgument и NyQueryParameters

Данные, передаваемые между маршрутами, оборачиваются в эти классы:

``` dart
// NyArgument содержит данные маршрута
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters содержит query-параметры URL
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
