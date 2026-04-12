# Controller'lar

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Controller Oluşturma](#creating-controllers "Controller Oluşturma")
- [Controller Kullanma](#using-controllers "Controller Kullanma")
- Controller Özellikleri
  - [Rota Verilerine Erişim](#accessing-route-data "Rota Verilerine Erişim")
  - [Sorgu Parametreleri](#query-parameters "Sorgu Parametreleri")
  - [Sayfa Durum Yönetimi](#page-state-management "Sayfa Durum Yönetimi")
  - [Toast Bildirimleri](#toast-notifications "Toast Bildirimleri")
  - [Form Doğrulama](#form-validation "Form Doğrulama")
  - [Dil Değiştirme](#language-switching "Dil Değiştirme")
  - [Kilit Serbest Bırakma](#lock-release "Kilit Serbest Bırakma")
  - [İşlem Onayı](#confirm-actions "İşlem Onayı")
- [Singleton Controller'lar](#singleton-controllers "Singleton Controller'lar")
- [Controller Kod Çözücüler](#controller-decoders "Controller Kod Çözücüler")
- [Rota Korumaları](#route-guards "Rota Korumaları")

<div id="introduction"></div>

## Giriş

{{ config('app.name') }} v7'deki controller'lar, görünümleriniz (sayfalar) ve iş mantığı arasında koordinatör görevi görür. Kullanıcı girdilerini işler, durum güncellemelerini yönetir ve temiz bir sorumluluk ayrımı sağlar.

{{ config('app.name') }} v7, toast bildirimleri, form doğrulama, durum yönetimi ve daha fazlası için güçlü yerleşik metotlara sahip `NyController` sınıfını sunar.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class HomeController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);
    // Servisleri başlat veya veri getir
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

## Controller Oluşturma

Controller oluşturmak için Metro CLI kullanın:

``` bash
# Controller ile sayfa oluştur
metro make:page dashboard --controller
# veya kısa form
metro make:page dashboard -c

# Yalnızca controller oluştur
metro make:controller profile_controller
```

Bu şunları oluşturur:
- **Controller**: `lib/app/controllers/dashboard_controller.dart`
- **Sayfa**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## Controller Kullanma

`NyStatefulWidget` üzerinde generic tip olarak belirterek bir controller'ı sayfanıza bağlayın:

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
    // Controller metotlarına erişim
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

## Rota Verilerine Erişim

Sayfalar arasında veri geçirin ve controller'ınızda erişin:

``` dart
// Veri ile gezin
routeTo(ProfilePage.path, data: {"userId": 123});

// Controller'ınızda
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Geçirilen verileri al
    Map<String, dynamic>? userData = data();
    int? userId = userData?['userId'];
  }
}
```

Veya sayfa durumunuzda doğrudan verilere erişin:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () async {
    // Controller'dan
    var userData = widget.controller.data();

    // Veya doğrudan widget'tan
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## Sorgu Parametreleri

Controller'ınızda URL sorgu parametrelerine erişin:

``` dart
// Şuraya git: /profile?tab=settings&highlight=true
routeTo("/profile?tab=settings&highlight=true");

// Controller'ınızda
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Tüm sorgu parametrelerini Map olarak al
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // Belirli bir parametreyi al
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

Bir sorgu parametresinin mevcut olup olmadığını kontrol edin:

``` dart
// Sayfanızda
if (widget.hasQueryParameter("tab")) {
  // Tab parametresini işle
}
```

<div id="page-state-management"></div>

## Sayfa Durum Yönetimi

Controller'lar sayfa durumunu doğrudan yönetebilir:

``` dart
class HomeController extends NyController {

  int counter = 0;

  void increment() {
    counter++;
    // Sayfada setState tetikle
    setState(setState: () {});
  }

  void refresh() {
    // Tüm sayfayı yenile
    refreshPage();
  }

  void goBack() {
    // İsteğe bağlı sonuçla sayfadan çık
    pop(result: {"updated": true});
  }

  void goBackFromRoot() {
    // Kök navigator'dan çık (örn. Navigation Hub'da kök seviyeli modalı kapatmak için)
    pop(rootNavigator: true);
  }

  void updateCustomState() {
    // Sayfaya özel eylem gönder
    updatePageState("customAction", {"key": "value"});
  }
}
```

<div id="toast-notifications"></div>

## Toast Bildirimleri

Controller'lar yerleşik toast bildirim metotları içerir:

``` dart
class FormController extends NyController {

  void showNotifications() {
    // Başarı toastı
    showToastSuccess(description: "Profile updated!");

    // Uyarı toastı
    showToastWarning(description: "Please check your input");

    // Hata/Tehlike toastı
    showToastDanger(description: "Failed to save changes");

    // Bilgi toastı
    showToastInfo(description: "New features available");

    // Özür toastı
    showToastSorry(description: "We couldn't process your request");

    // Hata toastı
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // Başlıklı özel toast
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // Özel toast stili kullan (Nylo'da kayıtlı)
    showToastCustom(
      title: "Custom",
      description: "Using custom style",
      id: "my_custom_toast",
    );
  }
}
```

<div id="form-validation"></div>

## Form Doğrulama

Controller'ınızdan form verilerini doğrudan doğrulayın:

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
        // Doğrulama başarılı
        _performRegistration();
      },
      onFailure: (exception) {
        // Doğrulama başarısız
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // Kayıt mantığını işle
    showToastSuccess(description: "Account created!");
  }
}
```

<div id="language-switching"></div>

## Dil Değiştirme

Controller'ınızdan uygulamanın dilini değiştirin:

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

## Kilit Serbest Bırakma

Butonlara birden fazla hızlı dokunmayı önleyin:

``` dart
class CheckoutController extends NyController {

  void onTapPurchase() {
    lockRelease("purchase_lock", perform: () async {
      // Bu kod kilit serbest bırakılana kadar yalnızca bir kez çalışır
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
      shouldSetState: false, // Sonrasında setState tetikleme
    );
  }
}
```

<div id="confirm-actions"></div>

## İşlem Onayı

Yıkıcı işlemlerden önce onay diyalogu gösterin:

``` dart
class AccountController extends NyController {

  void onTapDeleteAccount() {
    confirmAction(
      () async {
        // Kullanıcı onayladı - silme işlemini gerçekleştir
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

## Singleton Controller'lar

Bir controller'ı uygulama genelinde singleton olarak kalıcı kılın:

``` dart
class AuthController extends NyController {

  @override
  bool get singleton => true;

  User? currentUser;

  Future<void> login(String email, String password) async {
    // Giriş mantığı
    currentUser = await AuthService.login(email, password);
  }

  bool get isLoggedIn => currentUser != null;
}
```

Singleton controller'lar bir kez oluşturulur ve uygulama yaşam döngüsü boyunca yeniden kullanılır.

<div id="controller-decoders"></div>

## Controller Kod Çözücüler

Controller'larınızı `lib/config/decoders.dart` dosyasında kaydedin:

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

Bu harita, sayfalar yüklendiğinde {{ config('app.name') }}'nun controller'ları çözmesine olanak tanır.

<div id="route-guards"></div>

## Rota Korumaları

Controller'lar, sayfa yüklenmeden önce çalışan rota korumaları tanımlayabilir:

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
    // Yalnızca tüm korumalar geçerse çalışır
  }
}
```

Rota korumaları hakkında daha fazla bilgi için [Router dokümantasyonuna](/docs/7.x/router) bakın.
