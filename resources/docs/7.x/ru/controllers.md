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
    // Инициализировать сервисы или загрузить данные
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
# Создать страницу с контроллером
metro make:page dashboard --controller
# или сокращённо
metro make:page dashboard -c

# Создать только контроллер
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
    // Обращение к методам контроллера
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
// Перейти с данными
routeTo(ProfilePage.path, data: {"userId": 123});

// В вашем контроллере
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Получить переданные данные
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
    // Из контроллера
    var userData = widget.controller.data();

    // Или напрямую из виджета
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## Параметры запроса

Доступ к параметрам URL-запроса в контроллере:

``` dart
// Перейти к: /profile?tab=settings&highlight=true
routeTo("/profile?tab=settings&highlight=true");

// В вашем контроллере
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Получить все параметры запроса как Map
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // Получить конкретный параметр
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

Проверка наличия параметра запроса:

``` dart
// На вашей странице
if (widget.hasQueryParameter("tab")) {
  // Обработать параметр tab
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
    // Вызвать setState на странице
    setState(setState: () {});
  }

  void refresh() {
    // Обновить всю страницу
    refreshPage();
  }

  void goBack() {
    // Закрыть страницу с необязательным результатом
    pop(result: {"updated": true});
  }

  void goBackFromRoot() {
    // Закрыть из корневого навигатора (например, для закрытия модального окна верхнего уровня в Navigation Hub)
    pop(rootNavigator: true);
  }

  void updateCustomState() {
    // Отправить пользовательское действие на страницу
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
    // Уведомление об успехе
    showToastSuccess(description: "Profile updated!");

    // Предупреждающее уведомление
    showToastWarning(description: "Please check your input");

    // Уведомление об ошибке/опасности
    showToastDanger(description: "Failed to save changes");

    // Информационное уведомление
    showToastInfo(description: "New features available");

    // Уведомление с извинениями
    showToastSorry(description: "We couldn't process your request");

    // Уведомление Oops
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // Пользовательское уведомление с заголовком
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // Использовать пользовательский стиль уведомления (зарегистрированный в Nylo)
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
        // Валидация пройдена
        _performRegistration();
      },
      onFailure: (exception) {
        // Валидация не пройдена
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // Логика регистрации
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
      // Этот код выполняется только один раз до снятия блокировки
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
      shouldSetState: false, // Не вызывать setState после
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
        // Пользователь подтвердил — выполнить удаление
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
    // Логика входа
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
    // Выполняется только если все защитники пропустили
  }
}
```

Подробнее смотрите в [документации по маршрутизатору](/docs/7.x/router).
