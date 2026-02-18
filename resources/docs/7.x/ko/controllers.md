# Controllers

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [Controller 생성](#creating-controllers "Controller 생성")
- [Controller 사용](#using-controllers "Controller 사용")
- Controller 기능
  - [라우트 데이터 접근](#accessing-route-data "라우트 데이터 접근")
  - [쿼리 매개변수](#query-parameters "쿼리 매개변수")
  - [페이지 상태 관리](#page-state-management "페이지 상태 관리")
  - [Toast 알림](#toast-notifications "Toast 알림")
  - [폼 유효성 검사](#form-validation "폼 유효성 검사")
  - [언어 전환](#language-switching "언어 전환")
  - [Lock Release](#lock-release "Lock Release")
  - [작업 확인](#confirm-actions "작업 확인")
- [싱글톤 Controller](#singleton-controllers "싱글톤 Controller")
- [Controller Decoders](#controller-decoders "Controller Decoders")
- [Route Guards](#route-guards "Route Guards")

<div id="introduction"></div>

## 소개

{{ config('app.name') }} v7의 Controller는 뷰(페이지)와 비즈니스 로직 사이의 조정자 역할을 합니다. 사용자 입력을 처리하고, 상태 업데이트를 관리하며, 관심사의 깔끔한 분리를 제공합니다.

{{ config('app.name') }} v7은 Toast 알림, 폼 유효성 검사, 상태 관리 등을 위한 강력한 내장 메서드가 포함된 `NyController` 클래스를 도입합니다.

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

## Controller 생성

Metro CLI를 사용하여 Controller를 생성합니다:

``` bash
# Create a page with a controller
metro make:page dashboard --controller
# or shorthand
metro make:page dashboard -c

# Create a controller only
metro make:controller profile_controller
```

생성되는 파일:
- **Controller**: `lib/app/controllers/dashboard_controller.dart`
- **Page**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## Controller 사용

`NyStatefulWidget`의 제네릭 타입으로 지정하여 Controller를 페이지에 연결합니다:

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

## 라우트 데이터 접근

페이지 간 데이터를 전달하고 Controller에서 접근합니다:

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

또는 페이지 상태에서 직접 데이터에 접근합니다:

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

## 쿼리 매개변수

Controller에서 URL 쿼리 매개변수에 접근합니다:

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

쿼리 매개변수가 존재하는지 확인합니다:

``` dart
// In your page
if (widget.hasQueryParameter("tab")) {
  // Handle tab parameter
}
```

<div id="page-state-management"></div>

## 페이지 상태 관리

Controller는 페이지 상태를 직접 관리할 수 있습니다:

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

## Toast 알림

Controller에는 내장 Toast 알림 메서드가 포함되어 있습니다:

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

## 폼 유효성 검사

Controller에서 직접 폼 데이터를 유효성 검사합니다:

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

## 언어 전환

Controller에서 앱의 언어를 변경합니다:

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

## Lock Release

버튼의 빠른 다중 탭을 방지합니다:

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

## 작업 확인

파괴적인 작업을 수행하기 전에 확인 대화상자를 표시합니다:

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

## 싱글톤 Controller

Controller를 앱 전체에서 싱글톤으로 유지합니다:

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

싱글톤 Controller는 한 번 생성되어 앱 수명 주기 동안 재사용됩니다.

<div id="controller-decoders"></div>

## Controller Decoders

`lib/config/decoders.dart`에 Controller를 등록합니다:

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

이 맵을 통해 {{ config('app.name') }}이 페이지 로드 시 Controller를 해석할 수 있습니다.

<div id="route-guards"></div>

## Route Guards

Controller는 페이지 로드 전에 실행되는 Route Guard를 정의할 수 있습니다:

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

Route Guard에 대한 자세한 내용은 [Router 문서](/docs/7.x/router)를 참조하세요.
