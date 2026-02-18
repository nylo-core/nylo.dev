# Route Guards

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Создание Route Guard](#creating-a-route-guard "Создание Route Guard")
- [Жизненный цикл Guard](#guard-lifecycle "Жизненный цикл Guard")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [Действия Guard](#guard-actions "Действия Guard")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [Применение Guard к маршрутам](#applying-guards "Применение Guard к маршрутам")
- [Групповые Guard](#group-guards "Групповые Guard")
- [Композиция Guard](#guard-composition "Композиция Guard")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [Примеры](#examples "Практические примеры")

<div id="introduction"></div>

## Введение

Route guard предоставляют **middleware для навигации** в {{ config('app.name') }}. Они перехватывают переходы между маршрутами и позволяют вам контролировать, может ли пользователь получить доступ к странице, перенаправить его в другое место или изменить данные, передаваемые маршруту.

Типичные случаи использования:
- **Проверка аутентификации** --- перенаправление неаутентифицированных пользователей на страницу входа
- **Доступ на основе ролей** --- ограничение страниц для администраторов
- **Валидация данных** --- проверка наличия необходимых данных перед навигацией
- **Обогащение данных** --- добавление дополнительных данных к маршруту

Guard выполняются **по порядку** перед началом навигации. Если какой-либо guard возвращает `handled`, навигация останавливается (перенаправление или отмена).

<div id="creating-a-route-guard"></div>

## Создание Route Guard

Создайте route guard с помощью Metro CLI:

``` bash
metro make:route_guard auth
```

Это сгенерирует файл guard:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Add your guard logic here
    return next();
  }
}
```

<div id="guard-lifecycle"></div>

## Жизненный цикл Guard

У каждого route guard есть три метода жизненного цикла:

<div id="on-before"></div>

### onBefore

Вызывается **перед** началом навигации. Здесь вы проверяете условия и решаете, разрешить навигацию, перенаправить или отменить.

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  bool isLoggedIn = await Auth.isAuthenticated();

  if (!isLoggedIn) {
    return redirect(HomePage.path);
  }

  return next();
}
```

Возвращаемые значения:
- `next()` --- продолжить к следующему guard или перейти к маршруту
- `redirect(path)` --- перенаправить на другой маршрут
- `abort()` --- полностью отменить навигацию

<div id="on-after"></div>

### onAfter

Вызывается **после** успешной навигации. Используйте для аналитики, логирования или побочных эффектов после навигации.

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // Log page view
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

Вызывается, когда пользователь **покидает** маршрут. Верните `false`, чтобы предотвратить уход пользователя.

``` dart
@override
Future<bool> onLeave(RouteContext context) async {
  if (hasUnsavedChanges) {
    // Show confirmation dialog
    return await showConfirmDialog();
  }
  return true; // Allow leaving
}
```

<div id="route-context"></div>

## RouteContext

Объект `RouteContext` передаётся во все методы жизненного цикла guard и содержит информацию о навигации:

| Свойство | Тип | Описание |
|----------|------|-------------|
| `context` | `BuildContext?` | Текущий контекст сборки |
| `data` | `dynamic` | Данные, переданные маршруту |
| `queryParameters` | `Map<String, String>` | Параметры URL-запроса |
| `routeName` | `String` | Имя/путь целевого маршрута |
| `originalRouteName` | `String?` | Исходное имя маршрута до преобразований |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  // Access route information
  String route = context.routeName;
  dynamic routeData = context.data;
  Map<String, String> params = context.queryParameters;

  return next();
}
```

### Преобразование Route Context

Создание копии с другими данными:

``` dart
// Change the data type
RouteContext<User> userContext = context.withData<User>(currentUser);

// Copy with modified fields
RouteContext updated = context.copyWith(
  data: enrichedData,
  queryParameters: {"tab": "settings"},
);
```

<div id="guard-actions"></div>

## Действия Guard

<div id="next"></div>

### next

Продолжить к следующему guard в цепочке или перейти к маршруту, если это последний guard:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

Перенаправить пользователя на другой маршрут:

``` dart
return redirect(LoginPage.path);
```

С дополнительными параметрами:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `path` | `Object` | обязательный | Строка пути маршрута или RouteView |
| `data` | `dynamic` | null | Данные для передачи маршруту перенаправления |
| `queryParameters` | `Map<String, dynamic>?` | null | Параметры запроса |
| `navigationType` | `NavigationType` | `pushReplace` | Метод навигации |
| `result` | `dynamic` | null | Возвращаемый результат |
| `removeUntilPredicate` | `Function?` | null | Предикат удаления маршрутов |
| `transitionType` | `TransitionType?` | null | Тип перехода страницы |
| `onPop` | `Function(dynamic)?` | null | Обратный вызов при возврате |

<div id="abort"></div>

### abort

Отменить навигацию без перенаправления. Пользователь остаётся на текущей странице:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

Изменить данные, которые будут переданы последующим guard и целевому маршруту:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  User user = await fetchUser();

  // Enrich the route data
  setData({"user": user, "originalData": context.data});

  return next();
}
```

<div id="applying-guards"></div>

## Применение Guard к маршрутам

Добавьте guard к отдельным маршрутам в файле маршрутизатора:

``` dart
appRouter() => nyRoutes((router) {
  router.route(
    HomePage.path,
    (_) => HomePage(),
  ).initialRoute();

  // Add a single guard
  router.route(
    ProfilePage.path,
    (_) => ProfilePage(),
    routeGuards: [AuthRouteGuard()],
  );

  // Add multiple guards (executed in order)
  router.route(
    AdminPage.path,
    (_) => AdminPage(),
    routeGuards: [AuthRouteGuard(), AdminRoleGuard()],
  );
});
```

<div id="group-guards"></div>

## Групповые Guard

Применение guard к нескольким маршрутам одновременно с помощью групп маршрутов:

``` dart
appRouter() => nyRoutes((router) {
  router.route(HomePage.path, (_) => HomePage()).initialRoute();

  // All routes in this group require authentication
  router.group(() {
    return {
      'prefix': '/dashboard',
      'route_guards': [AuthRouteGuard()],
    };
  }, (router) {
    router.route(DashboardPage.path, (_) => DashboardPage());
    router.route(SettingsPage.path, (_) => SettingsPage());
    router.route(ProfilePage.path, (_) => ProfilePage());
  });
});
```

<div id="guard-composition"></div>

## Композиция Guard

{{ config('app.name') }} предоставляет инструменты для компоновки guard в повторно используемые паттерны.

<div id="guard-stack"></div>

### GuardStack

Объединение нескольких guard в один повторно используемый guard:

``` dart
final protectedRoute = GuardStack([
  AuthRouteGuard(),
  VerifyEmailGuard(),
  TwoFactorGuard(),
]);

// Use the stack on a route
router.route(
  SecurePage.path,
  (_) => SecurePage(),
  routeGuards: [protectedRoute],
);
```

`GuardStack` выполняет guard по порядку. Если какой-либо guard возвращает `handled`, оставшиеся guard пропускаются.

<div id="conditional-guard"></div>

### ConditionalGuard

Применение guard только при выполнении условия:

``` dart
router.route(
  BetaPage.path,
  (_) => BetaPage(),
  routeGuards: [
    ConditionalGuard(
      condition: (context) => context.queryParameters.containsKey("beta"),
      guard: BetaAccessGuard(),
    ),
  ],
);
```

Если условие возвращает `false`, guard пропускается и навигация продолжается.

<div id="parameterized-guard"></div>

### ParameterizedGuard

Создание guard, принимающих параметры конфигурации:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params); // params = allowed roles

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();

    if (user == null || !params.contains(user.role)) {
      return redirect(UnauthorizedPage.path);
    }

    return next();
  }
}

// Usage
router.route(
  AdminPage.path,
  (_) => AdminPage(),
  routeGuards: [RoleGuard(["admin", "super_admin"])],
);
```

<div id="examples"></div>

## Примеры

### Guard аутентификации

``` dart
class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    bool isAuthenticated = await Auth.isAuthenticated();

    if (!isAuthenticated) {
      return redirect(HomePage.path);
    }

    return next();
  }
}
```

### Guard подписки с параметрами

``` dart
class SubscriptionGuard extends ParameterizedGuard<List<String>> {
  SubscriptionGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();
    bool hasAccess = params.any((plan) => user?.subscription == plan);

    if (!hasAccess) {
      return redirect(UpgradePage.path, data: {"plans": params});
    }

    setData({"user": user});
    return next();
  }
}

// Require premium or pro subscription
router.route(
  PremiumPage.path,
  (_) => PremiumPage(),
  routeGuards: [
    AuthRouteGuard(),
    SubscriptionGuard(["premium", "pro"]),
  ],
);
```

### Guard логирования

``` dart
class LoggingGuard extends NyRouteGuard {
  LoggingGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    print("Navigating to: ${context.routeName}");
    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    print("Arrived at: ${context.routeName}");
  }
}
```
