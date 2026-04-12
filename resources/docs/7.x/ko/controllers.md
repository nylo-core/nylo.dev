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
    // 서비스 초기화 또는 데이터 가져오기
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
# Controller가 포함된 페이지 생성
metro make:page dashboard --controller
# 또는 약어
metro make:page dashboard -c

# Controller만 생성
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
    // Controller 메서드 접근
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
// 데이터와 함께 이동
routeTo(ProfilePage.path, data: {"userId": 123});

// Controller 내에서
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // 전달된 데이터 가져오기
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
    // Controller에서
    var userData = widget.controller.data();

    // 또는 위젯에서 직접
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## 쿼리 매개변수

Controller에서 URL 쿼리 매개변수에 접근합니다:

``` dart
// 이동: /profile?tab=settings&highlight=true
routeTo("/profile?tab=settings&highlight=true");

// Controller 내에서
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // 모든 쿼리 매개변수를 Map으로 가져오기
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // 특정 매개변수 가져오기
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

쿼리 매개변수가 존재하는지 확인합니다:

``` dart
// 페이지 내에서
if (widget.hasQueryParameter("tab")) {
  // tab 매개변수 처리
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
    // 페이지의 setState 트리거
    setState(setState: () {});
  }

  void refresh() {
    // 전체 페이지 새로고침
    refreshPage();
  }

  void goBack() {
    // 선택적 결과와 함께 페이지 팝
    pop(result: {"updated": true});
  }

  void goBackFromRoot() {
    // 루트 네비게이터에서 팝 (예: Navigation Hub의 루트 레벨 모달 닫기)
    pop(rootNavigator: true);
  }

  void updateCustomState() {
    // 페이지에 커스텀 액션 전송
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
    // 성공 Toast
    showToastSuccess(description: "Profile updated!");

    // 경고 Toast
    showToastWarning(description: "Please check your input");

    // 오류/위험 Toast
    showToastDanger(description: "Failed to save changes");

    // 정보 Toast
    showToastInfo(description: "New features available");

    // Sorry Toast
    showToastSorry(description: "We couldn't process your request");

    // Oops Toast
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // 제목이 있는 커스텀 Toast
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // 커스텀 Toast 스타일 사용 (Nylo에 등록됨)
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
        // 유효성 검사 통과
        _performRegistration();
      },
      onFailure: (exception) {
        // 유효성 검사 실패
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // 등록 로직 처리
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
      // 이 코드는 잠금이 해제될 때까지 한 번만 실행됩니다
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
      shouldSetState: false, // 완료 후 setState 트리거하지 않음
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
        // 사용자 확인 - 삭제 수행
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
    // 로그인 로직
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
    // 모든 가드를 통과한 경우에만 실행됩니다
  }
}
```

Route Guard에 대한 자세한 내용은 [Router 문서](/docs/7.x/router)를 참조하세요.
