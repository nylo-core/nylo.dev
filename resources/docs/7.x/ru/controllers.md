# Controllers

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в контроллеры")
- [Создание контроллеров](#creating-controllers "Создание контроллеров")
- [Использование контроллеров](#using-controllers "Использование контроллеров")
- Возможности контроллеров
  - [Доступ к данным маршрута](#accessing-route-data "Доступ к данным маршрута")
  - [Параметры запроса](#query-parameters "Параметры запроса")
  - [Управление состоянием страницы](#page-state-management "Управление состоянием страницы")
  - [Всплывающие уведомления](#toast-notifications "Всплывающие уведомления")
  - [Валидация форм](#form-validation "Валидация форм")
  - [Переключение языка](#language-switching "Переключение языка")
  - [Блокировка повторных действий](#lock-release "Блокировка повторных действий")
  - [Подтверждение действий](#confirm-actions "Подтверждение действий")
- [Контроллеры-одиночки](#singleton-controllers "Контроллеры-одиночки")
- [Декодеры контроллеров](#controller-decoders "Декодеры контроллеров")
- [Защитники маршрутов](#route-guards "Защитники маршрутов")

<div id="introduction"></div>

## Введение

Контроллеры в {{ config('app.name') }} v7 выступают координатором между вашими представлениями (страницами) и бизнес-логикой. Они обрабатывают пользовательский ввод, управляют обновлениями состояния и обеспечивают чёткое разделение ответственности.

{{ config('app.name') }} v7 представляет класс `NyController` с мощными встроенными методами для всплывающих уведомлений, валидации форм, управления состоянием и многого другого.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class HomeController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);
    // Initialize services or fetch data
  }

  void onTapProfile() {
    routeTo(ProfilePage.path);
  }

  void submitForm() {
    validate(
      rules: {"email": "email"},
      onSuccess: () => showToastSuccess(description: "Form submitted!"),
    );
  }
}
```

<div id="creating-controllers"></div>

## Создание контроллеров

Используйте Metro CLI для генерации контроллеров:

``` bash
# Create a page with a controller
metro make:page dashboard --controller
# or shorthand
metro make:page dashboard -c

# Create a controller only
metro make:controller profile_controller
```

Это создаёт:
- **Контроллер**: `lib/app/controllers/dashboard_controller.dart`
- **Страница**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## Использование контроллеров

Подключите контроллер к странице, указав его как generic-тип в `NyStatefulWidget`:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/home_controller.dart';

class HomePage extends NyStatefulWidget<HomeController> {

  static RouteView path = ("/home", (_) => HomePage());

  HomePage() : super(child: () => _HomePageState());
}

class _HomePageState extends NyPage<HomePage> {

  @override
  get init => () async {
    // Access controller methods
    widget.controller.fetchData();
  };

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Home")),
      body: Column(
        children: [
          ElevatedButton(
            onPressed: widget.controller.onTapProfile,
            child: Text("View Profile"),
          ),
          TextField(
            controller: widget.controller.nameController,
          ),
        ],
      ),
    );
  }
}
```

<div id="accessing-route-data"></div>

## Доступ к данным маршрута

Передавайте данные между страницами и получайте к ним доступ в контроллере:

``` dart
// Navigate with data
routeTo(ProfilePage.path, data: {"userId": 123});

// In your controller
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Get the passed data
    Map<String, dynamic>? userData = data();
    int? userId = userData?['userId'];
  }
}
```

Или обращайтесь к данным напрямую в состоянии страницы:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () async {
    // From controller
    var userData = widget.controller.data();

    // Or from widget directly
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## Параметры запроса

Доступ к параметрам URL-запроса в контроллере:

``` dart
// Navigate to: /profile?tab=settings&highlight=true
routeTo("/profile?tab=settings&highlight=true");

// In your controller
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Get all query parameters as Map
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // Get a specific parameter
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

Проверка наличия параметра запроса:

``` dart
// In your page
if (widget.hasQueryParameter("tab")) {
  // Handle tab parameter
}
```

<div id="page-state-management"></div>

## Управление состоянием страницы

Контроллеры могут напрямую управлять состоянием страницы:

``` dart
class HomeController extends NyController {

  int counter = 0;

  void increment() {
    counter++;
    // Trigger a setState on the page
    setState(setState: () {});
  }

  void refresh() {
    // Refresh the entire page
    refreshPage();
  }

  void goBack() {
    // Pop the page with optional result
    pop(result: {"updated": true});
  }

  void updateCustomState() {
    // Send custom action to page
    updatePageState("customAction", {"key": "value"});
  }
}
```

<div id="toast-notifications"></div>

## Всплывающие уведомления

Контроллеры включают встроенные методы для всплывающих уведомлений:

``` dart
class FormController extends NyController {

  void showNotifications() {
    // Success toast
    showToastSuccess(description: "Profile updated!");

    // Warning toast
    showToastWarning(description: "Please check your input");

    // Error/Danger toast
    showToastDanger(description: "Failed to save changes");

    // Info toast
    showToastInfo(description: "New features available");

    // Sorry toast
    showToastSorry(description: "We couldn't process your request");

    // Oops toast
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // Custom toast with title
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // Use custom toast style (registered in Nylo)
    showToastCustom(
      title: "Custom",
      description: "Using custom style",
      id: "my_custom_toast",
    );
  }
}
```

<div id="form-validation"></div>

## Валидация форм

Валидация данных формы непосредственно из контроллера:

``` dart
class RegisterController extends NyController {

  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();

  void submitRegistration() {
    validate(
      rules: {
        "email": "email|max:50",
        "password": "min:8|max:64",
      },
      data: {
        "email": emailController.text,
        "password": passwordController.text,
      },
      messages: {
        "email.email": "Please enter a valid email",
        "password.min": "Password must be at least 8 characters",
      },
      showAlert: true,
      alertStyle: 'warning',
      onSuccess: () {
        // Validation passed
        _performRegistration();
      },
      onFailure: (exception) {
        // Validation failed
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // Handle registration logic
    showToastSuccess(description: "Account created!");
  }
}
```

<div id="language-switching"></div>

## Переключение языка

Изменение языка приложения из контроллера:

``` dart
class SettingsController extends NyController {

  void switchToSpanish() {
    changeLanguage('es', restartState: true);
  }

  void switchToEnglish() {
    changeLanguage('en', restartState: true);
  }
}
```

<div id="lock-release"></div>

## Блокировка повторных действий

Предотвращение множественных быстрых нажатий на кнопки:

``` dart
class CheckoutController extends NyController {

  void onTapPurchase() {
    lockRelease("purchase_lock", perform: () async {
      // This code only runs once until the lock is released
      await processPayment();
      showToastSuccess(description: "Payment complete!");
    });
  }

  void onTapWithoutSetState() {
    lockRelease(
      "my_lock",
      perform: () async {
        await someAsyncOperation();
      },
      shouldSetState: false, // Don't trigger setState after
    );
  }
}
```

<div id="confirm-actions"></div>

## Подтверждение действий

Отображение диалога подтверждения перед выполнением деструктивных действий:

``` dart
class AccountController extends NyController {

  void onTapDeleteAccount() {
    confirmAction(
      () async {
        // User confirmed - perform deletion
        await deleteAccount();
        showToastSuccess(description: "Account deleted");
      },
      title: "Delete Account?",
      dismissText: "Cancel",
    );
  }
}
```

<div id="singleton-controllers"></div>

## Контроллеры-одиночки

Сделайте контроллер сохраняемым на протяжении всего приложения как одиночку:

``` dart
class AuthController extends NyController {

  @override
  bool get singleton => true;

  User? currentUser;

  Future<void> login(String email, String password) async {
    // Login logic
    currentUser = await AuthService.login(email, password);
  }

  bool get isLoggedIn => currentUser != null;
}
```

Контроллеры-одиночки создаются один раз и используются повторно на протяжении всего жизненного цикла приложения.

<div id="controller-decoders"></div>

## Декодеры контроллеров

Зарегистрируйте контроллеры в `lib/config/decoders.dart`:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/home_controller.dart';
import '/app/controllers/profile_controller.dart';
import '/app/controllers/auth_controller.dart';

final Map<Type, BaseController Function()> controllers = {
  HomeController: () => HomeController(),
  ProfileController: () => ProfileController(),
  AuthController: () => AuthController(),
};
```

Эта карта позволяет {{ config('app.name') }} разрешать контроллеры при загрузке страниц.

<div id="route-guards"></div>

## Защитники маршрутов

Контроллеры могут определять защитников маршрутов, которые выполняются перед загрузкой страницы:

``` dart
class AdminController extends NyController {

  @override
  List<RouteGuard> get routeGuards => [
    AuthRouteGuard(),
    AdminRoleGuard(),
  ];

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);
    // Only runs if all guards pass
  }
}
```

Подробнее смотрите в [документации по маршрутизатору](/docs/7.x/router).
