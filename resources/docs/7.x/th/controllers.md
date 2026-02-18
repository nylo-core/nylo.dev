# Controllers

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับ controller")
- [การสร้าง Controller](#creating-controllers "การสร้าง controller")
- [การใช้งาน Controller](#using-controllers "การใช้งาน controller")
- ฟีเจอร์ Controller
  - [การเข้าถึงข้อมูล Route](#accessing-route-data "การเข้าถึงข้อมูล route")
  - [Query Parameters](#query-parameters "Query parameters")
  - [การจัดการสถานะหน้าเพจ](#page-state-management "การจัดการสถานะหน้าเพจ")
  - [การแจ้งเตือน Toast](#toast-notifications "การแจ้งเตือน toast")
  - [การตรวจสอบฟอร์ม](#form-validation "การตรวจสอบฟอร์ม")
  - [การสลับภาษา](#language-switching "การสลับภาษา")
  - [Lock Release](#lock-release "Lock Release")
  - [การยืนยันการกระทำ](#confirm-actions "การยืนยันการกระทำ")
- [Singleton Controller](#singleton-controllers "Singleton controller")
- [Controller Decoders](#controller-decoders "Controller decoders")
- [Route Guards](#route-guards "Route guards")

<div id="introduction"></div>

## บทนำ

Controller ใน {{ config('app.name') }} v7 ทำหน้าที่เป็นตัวประสานระหว่าง view (หน้าเพจ) และ business logic โดยจัดการ input ของผู้ใช้ จัดการการอัปเดตสถานะ และให้การแยก concerns ที่สะอาด

{{ config('app.name') }} v7 นำเสนอคลาส `NyController` พร้อมเมธอดในตัวที่ทรงพลังสำหรับการแจ้งเตือน toast, การตรวจสอบฟอร์ม, การจัดการสถานะ และอื่นๆ

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

## การสร้าง Controller

ใช้ Metro CLI เพื่อสร้าง controller:

``` bash
# Create a page with a controller
metro make:page dashboard --controller
# or shorthand
metro make:page dashboard -c

# Create a controller only
metro make:controller profile_controller
```

คำสั่งนี้จะสร้าง:
- **Controller**: `lib/app/controllers/dashboard_controller.dart`
- **Page**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## การใช้งาน Controller

เชื่อมต่อ controller กับหน้าเพจของคุณโดยระบุเป็น generic type บน `NyStatefulWidget`:

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

## การเข้าถึงข้อมูล Route

ส่งข้อมูลระหว่างหน้าเพจและเข้าถึงใน controller ของคุณ:

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

หรือเข้าถึงข้อมูลโดยตรงใน page state ของคุณ:

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

## Query Parameters

เข้าถึง URL query parameters ใน controller ของคุณ:

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

ตรวจสอบว่า query parameter มีอยู่หรือไม่:

``` dart
// In your page
if (widget.hasQueryParameter("tab")) {
  // Handle tab parameter
}
```

<div id="page-state-management"></div>

## การจัดการสถานะหน้าเพจ

Controller สามารถจัดการสถานะของหน้าเพจได้โดยตรง:

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

## การแจ้งเตือน Toast

Controller มีเมธอดการแจ้งเตือน toast ในตัว:

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

## การตรวจสอบฟอร์ม

ตรวจสอบข้อมูลฟอร์มโดยตรงจาก controller ของคุณ:

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

## การสลับภาษา

เปลี่ยนภาษาของแอปจาก controller ของคุณ:

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

ป้องกันการกดปุ่มหลายครั้งอย่างรวดเร็ว:

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

## การยืนยันการกระทำ

แสดงกล่องยืนยันก่อนดำเนินการที่มีผลกระทบ:

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

## Singleton Controller

ทำให้ controller คงอยู่ตลอดในแอปเป็น singleton:

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

Singleton controller จะถูกสร้างครั้งเดียวและนำกลับมาใช้ซ้ำตลอดวงจรชีวิตของแอป

<div id="controller-decoders"></div>

## Controller Decoders

ลงทะเบียน controller ของคุณใน `lib/config/decoders.dart`:

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

map นี้ช่วยให้ {{ config('app.name') }} สามารถ resolve controller เมื่อหน้าเพจถูกโหลด

<div id="route-guards"></div>

## Route Guards

Controller สามารถกำหนด route guard ที่ทำงานก่อนหน้าเพจโหลด:

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

ดู[เอกสาร Router](/docs/7.x/router) สำหรับรายละเอียดเพิ่มเติมเกี่ยวกับ route guard
