# Controllers

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về controller")
- [Tạo Controller](#creating-controllers "Tạo controller")
- [Sử dụng Controller](#using-controllers "Sử dụng controller")
- Tính năng Controller
  - [Truy cập dữ liệu route](#accessing-route-data "Truy cập dữ liệu route")
  - [Tham số truy vấn](#query-parameters "Tham số truy vấn")
  - [Quản lý trạng thái trang](#page-state-management "Quản lý trạng thái trang")
  - [Thông báo Toast](#toast-notifications "Thông báo Toast")
  - [Xác thực form](#form-validation "Xác thực form")
  - [Chuyển đổi ngôn ngữ](#language-switching "Chuyển đổi ngôn ngữ")
  - [Khóa giải phóng](#lock-release "Khóa giải phóng")
  - [Xác nhận hành động](#confirm-actions "Xác nhận hành động")
- [Controller Singleton](#singleton-controllers "Controller Singleton")
- [Controller Decoders](#controller-decoders "Controller Decoders")
- [Route Guards](#route-guards "Route Guards")

<div id="introduction"></div>

## Giới thiệu

Controller trong {{ config('app.name') }} v7 đóng vai trò là bộ điều phối giữa view (trang) và logic nghiệp vụ. Chúng xử lý đầu vào từ người dùng, quản lý cập nhật trạng thái, và cung cấp sự phân tách rõ ràng giữa các thành phần.

{{ config('app.name') }} v7 giới thiệu class `NyController` với các phương thức dựng sẵn mạnh mẽ cho thông báo toast, xác thực form, quản lý trạng thái, và nhiều hơn nữa.

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

## Tạo Controller

Sử dụng Metro CLI để tạo controller:

``` bash
# Create a page with a controller
metro make:page dashboard --controller
# or shorthand
metro make:page dashboard -c

# Create a controller only
metro make:controller profile_controller
```

Lệnh này tạo:
- **Controller**: `lib/app/controllers/dashboard_controller.dart`
- **Trang**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## Sử dụng Controller

Kết nối controller với trang bằng cách chỉ định nó làm kiểu generic trên `NyStatefulWidget`:

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

## Truy cập dữ liệu route

Truyền dữ liệu giữa các trang và truy cập trong controller:

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

Hoặc truy cập dữ liệu trực tiếp trong trạng thái trang:

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

## Tham số truy vấn

Truy cập tham số truy vấn URL trong controller:

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

Kiểm tra xem tham số truy vấn có tồn tại không:

``` dart
// In your page
if (widget.hasQueryParameter("tab")) {
  // Handle tab parameter
}
```

<div id="page-state-management"></div>

## Quản lý trạng thái trang

Controller có thể quản lý trạng thái trang trực tiếp:

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

## Thông báo Toast

Controller bao gồm các phương thức thông báo toast dựng sẵn:

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

## Xác thực form

Xác thực dữ liệu form trực tiếp từ controller:

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

## Chuyển đổi ngôn ngữ

Thay đổi ngôn ngữ ứng dụng từ controller:

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

## Khóa giải phóng

Ngăn chặn nhiều lần nhấn nhanh trên nút:

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

## Xác nhận hành động

Hiển thị hộp thoại xác nhận trước khi thực hiện hành động nguy hiểm:

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

## Controller Singleton

Biến controller thành singleton tồn tại trong toàn bộ ứng dụng:

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

Controller singleton được tạo một lần và tái sử dụng trong suốt vòng đời ứng dụng.

<div id="controller-decoders"></div>

## Controller Decoders

Đăng ký controller trong `lib/config/decoders.dart`:

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

Map này cho phép {{ config('app.name') }} giải quyết controller khi các trang được tải.

<div id="route-guards"></div>

## Route Guards

Controller có thể định nghĩa route guard chạy trước khi trang được tải:

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

Xem [tài liệu Router](/docs/7.x/router) để biết thêm chi tiết về route guard.
