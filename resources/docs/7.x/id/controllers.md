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
    // Inisialisasi layanan atau ambil data
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
# Buat halaman dengan controller
metro make:page dashboard --controller
# atau singkatan
metro make:page dashboard -c

# Buat hanya controller
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
    // Akses method controller
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
// Navigasi dengan data
routeTo(ProfilePage.path, data: {"userId": 123});

// Di controller Anda
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Dapatkan data yang dikirim
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
    // Dari controller
    var userData = widget.controller.data();

    // Atau dari widget langsung
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## Parameter Query

Akses parameter query URL di controller Anda:

``` dart
// Navigasi ke: /profile?tab=settings&highlight=true
routeTo("/profile?tab=settings&highlight=true");

// Di controller Anda
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Dapatkan semua parameter query sebagai Map
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // Dapatkan parameter tertentu
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

Periksa apakah parameter query ada:

``` dart
// Di halaman Anda
if (widget.hasQueryParameter("tab")) {
  // Tangani parameter tab
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
    // Picu setState pada halaman
    setState(setState: () {});
  }

  void refresh() {
    // Segarkan seluruh halaman
    refreshPage();
  }

  void goBack() {
    // Pop halaman dengan hasil opsional
    pop(result: {"updated": true});
  }

  void goBackFromRoot() {
    // Pop dari root navigator (mis. untuk menutup modal level root di Navigation Hub)
    pop(rootNavigator: true);
  }

  void updateCustomState() {
    // Kirim aksi kustom ke halaman
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
    // Toast sukses
    showToastSuccess(description: "Profile updated!");

    // Toast peringatan
    showToastWarning(description: "Please check your input");

    // Toast error/bahaya
    showToastDanger(description: "Failed to save changes");

    // Toast info
    showToastInfo(description: "New features available");

    // Toast maaf
    showToastSorry(description: "We couldn't process your request");

    // Toast ups
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // Toast kustom dengan judul
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // Gunakan gaya toast kustom (terdaftar di Nylo)
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
        // Validasi berhasil
        _performRegistration();
      },
      onFailure: (exception) {
        // Validasi gagal
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // Tangani logika registrasi
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
      // Kode ini hanya berjalan sekali sampai lock dilepas
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
      shouldSetState: false, // Jangan picu setState setelahnya
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
        // Pengguna mengonfirmasi - lakukan penghapusan
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
    // Logika login
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
    // Hanya berjalan jika semua guard lolos
  }
}
```

Lihat [dokumentasi Router](/docs/7.x/router) untuk detail lebih lanjut tentang route guard.

