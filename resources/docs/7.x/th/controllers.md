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
    // เริ่มต้นใช้งานบริการหรือดึงข้อมูล
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
# สร้างหน้าเพจพร้อม controller
metro make:page dashboard --controller
# หรือแบบย่อ
metro make:page dashboard -c

# สร้างเฉพาะ controller
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
    // เข้าถึงเมธอดของ controller
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
// นำทางพร้อมข้อมูล
routeTo(ProfilePage.path, data: {"userId": 123});

// ใน controller ของคุณ
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // ดึงข้อมูลที่ส่งมา
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
    // จาก controller
    var userData = widget.controller.data();

    // หรือจาก widget โดยตรง
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## Query Parameters

เข้าถึง URL query parameters ใน controller ของคุณ:

``` dart
// นำทางไปที่: /profile?tab=settings&highlight=true
routeTo("/profile?tab=settings&highlight=true");

// ใน controller ของคุณ
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // ดึง query parameters ทั้งหมดเป็น Map
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // ดึง parameter เฉพาะ
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

ตรวจสอบว่า query parameter มีอยู่หรือไม่:

``` dart
// ในหน้าเพจของคุณ
if (widget.hasQueryParameter("tab")) {
  // จัดการ parameter tab
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
    // กระตุ้น setState บนหน้าเพจ
    setState(setState: () {});
  }

  void refresh() {
    // รีเฟรชทั้งหน้าเพจ
    refreshPage();
  }

  void goBack() {
    // Pop หน้าเพจพร้อมผลลัพธ์ที่เลือกได้
    pop(result: {"updated": true});
  }

  void goBackFromRoot() {
    // Pop จาก root navigator (เช่น เพื่อปิด modal ระดับ root ใน Navigation Hub)
    pop(rootNavigator: true);
  }

  void updateCustomState() {
    // ส่ง action แบบกำหนดเองไปยังหน้าเพจ
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
    // Toast สำเร็จ
    showToastSuccess(description: "Profile updated!");

    // Toast คำเตือน
    showToastWarning(description: "Please check your input");

    // Toast ข้อผิดพลาด/อันตราย
    showToastDanger(description: "Failed to save changes");

    // Toast ข้อมูล
    showToastInfo(description: "New features available");

    // Toast ขอโทษ
    showToastSorry(description: "We couldn't process your request");

    // Toast อุ๊ปส์
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // Toast แบบกำหนดเองพร้อมหัวข้อ
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // ใช้สไตล์ toast แบบกำหนดเอง (ลงทะเบียนใน Nylo)
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
        // ผ่านการตรวจสอบ
        _performRegistration();
      },
      onFailure: (exception) {
        // ไม่ผ่านการตรวจสอบ
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // จัดการ logic การลงทะเบียน
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
      // โค้ดนี้ทำงานเพียงครั้งเดียวจนกว่า lock จะถูกปล่อย
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
      shouldSetState: false, // ไม่กระตุ้น setState หลังจากนั้น
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
        // ผู้ใช้ยืนยันแล้ว - ดำเนินการลบ
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
    // Logic การเข้าสู่ระบบ
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
    // ทำงานเฉพาะเมื่อ guard ทั้งหมดผ่าน
  }
}
```

ดู[เอกสาร Router](/docs/7.x/router) สำหรับรายละเอียดเพิ่มเติมเกี่ยวกับ route guard
