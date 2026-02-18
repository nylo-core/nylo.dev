# Controller

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Membuat Controller](#creating-controllers "Membuat Controller")
- [Menggunakan Controller](#using-controllers "Menggunakan Controller")
- Fitur Controller
  - [Mengakses Data Rute](#accessing-route-data "Mengakses Data Rute")
  - [Parameter Query](#query-parameters "Parameter Query")
  - [Manajemen State Halaman](#page-state-management "Manajemen State Halaman")
  - [Notifikasi Toast](#toast-notifications "Notifikasi Toast")
  - [Validasi Form](#form-validation "Validasi Form")
  - [Pergantian Bahasa](#language-switching "Pergantian Bahasa")
  - [Lock Release](#lock-release "Lock Release")
  - [Konfirmasi Aksi](#confirm-actions "Konfirmasi Aksi")
- [Controller Singleton](#singleton-controllers "Controller Singleton")
- [Decoder Controller](#controller-decoders "Decoder Controller")
- [Route Guard](#route-guards "Route Guard")

<div id="introduction"></div>

## Pengantar

Controller di {{ config('app.name') }} v7 bertindak sebagai koordinator antara view (halaman) dan logika bisnis Anda. Controller menangani input pengguna, mengelola pembaruan state, dan menyediakan pemisahan tanggung jawab yang bersih.

{{ config('app.name') }} v7 memperkenalkan kelas `NyController` dengan method bawaan yang kuat untuk notifikasi toast, validasi form, manajemen state, dan lainnya.

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

## Membuat Controller

Gunakan Metro CLI untuk membuat controller:

``` bash
# Create a page with a controller
metro make:page dashboard --controller
# or shorthand
metro make:page dashboard -c

# Create a controller only
metro make:controller profile_controller
```

Ini membuat:
- **Controller**: `lib/app/controllers/dashboard_controller.dart`
- **Halaman**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## Menggunakan Controller

Hubungkan controller ke halaman Anda dengan menentukannya sebagai tipe generik pada `NyStatefulWidget`:

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

## Mengakses Data Rute

Kirim data antar halaman dan akses di controller Anda:

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

Atau akses data langsung di state halaman Anda:

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

## Parameter Query

Akses parameter query URL di controller Anda:

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

Periksa apakah parameter query ada:

``` dart
// In your page
if (widget.hasQueryParameter("tab")) {
  // Handle tab parameter
}
```

<div id="page-state-management"></div>

## Manajemen State Halaman

Controller dapat mengelola state halaman secara langsung:

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

## Notifikasi Toast

Controller menyertakan method notifikasi toast bawaan:

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

## Validasi Form

Validasi data form langsung dari controller Anda:

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

## Pergantian Bahasa

Ubah bahasa aplikasi dari controller Anda:

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

Cegah ketukan ganda yang cepat pada tombol:

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

## Konfirmasi Aksi

Tampilkan dialog konfirmasi sebelum melakukan aksi destruktif:

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

Buat controller yang bertahan di seluruh aplikasi sebagai singleton:

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

Controller singleton dibuat sekali dan digunakan kembali sepanjang siklus hidup aplikasi.

<div id="controller-decoders"></div>

## Decoder Controller

Daftarkan controller Anda di `lib/config/decoders.dart`:

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

Map ini memungkinkan {{ config('app.name') }} untuk me-resolve controller saat halaman dimuat.

<div id="route-guards"></div>

## Route Guard

Controller dapat mendefinisikan route guard yang berjalan sebelum halaman dimuat:

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

Lihat [dokumentasi Router](/docs/7.x/router) untuk detail lebih lanjut tentang route guard.

