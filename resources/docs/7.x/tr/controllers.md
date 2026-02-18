# Controller'lar

---

<a name="section-1"></a>
- [Giri&#351;](#introduction "Giri&#351;")
- [Controller Olu&#351;turma](#creating-controllers "Controller Olu&#351;turma")
- [Controller Kullanma](#using-controllers "Controller Kullanma")
- Controller &#214;zellikleri
  - [Rota Verilerine Eri&#351;im](#accessing-route-data "Rota Verilerine Eri&#351;im")
  - [Sorgu Parametreleri](#query-parameters "Sorgu Parametreleri")
  - [Sayfa Durum Y&#246;netimi](#page-state-management "Sayfa Durum Y&#246;netimi")
  - [Toast Bildirimleri](#toast-notifications "Toast Bildirimleri")
  - [Form Do&#287;rulama](#form-validation "Form Do&#287;rulama")
  - [Dil De&#287;i&#351;tirme](#language-switching "Dil De&#287;i&#351;tirme")
  - [Kilit Serbest B&#305;rakma](#lock-release "Kilit Serbest B&#305;rakma")
  - [&#304;&#351;lem Onay&#305;](#confirm-actions "&#304;&#351;lem Onay&#305;")
- [Singleton Controller'lar](#singleton-controllers "Singleton Controller'lar")
- [Controller Kod &#199;&#246;z&#252;c&#252;ler](#controller-decoders "Controller Kod &#199;&#246;z&#252;c&#252;ler")
- [Rota Korumalar&#305;](#route-guards "Rota Korumalar&#305;")

<div id="introduction"></div>

## Giri&#351;

{{ config('app.name') }} v7'deki controller'lar, g&#246;r&#252;n&#252;mleriniz (sayfalar) ve i&#351; mant&#305;&#287;&#305; aras&#305;nda koordinat&#246;r g&#246;revi g&#246;r&#252;r. Kullan&#305;c&#305; girdilerini i&#351;ler, durum g&#252;ncellemelerini y&#246;netir ve temiz bir sorumluluk ayr&#305;m&#305; sa&#287;lar.

{{ config('app.name') }} v7, toast bildirimleri, form do&#287;rulama, durum y&#246;netimi ve daha fazlas&#305; i&#231;in g&#252;&#231;l&#252; yerle&#351;ik metotlara sahip `NyController` s&#305;n&#305;f&#305;n&#305; sunar.

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

## Controller Olu&#351;turma

Controller olu&#351;turmak i&#231;in Metro CLI kullan&#305;n:

``` bash
# Create a page with a controller
metro make:page dashboard --controller
# or shorthand
metro make:page dashboard -c

# Create a controller only
metro make:controller profile_controller
```

Bu &#351;unlar&#305; olu&#351;turur:
- **Controller**: `lib/app/controllers/dashboard_controller.dart`
- **Sayfa**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## Controller Kullanma

`NyStatefulWidget` &#252;zerinde generic tip olarak belirterek bir controller'&#305; sayfan&#305;za ba&#287;lay&#305;n:

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

## Rota Verilerine Eri&#351;im

Sayfalar aras&#305;nda veri ge&#231;irin ve controller'&#305;n&#305;zda eri&#351;in:

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

Veya sayfa durumunuzda do&#287;rudan verilere eri&#351;in:

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

## Sorgu Parametreleri

Controller'&#305;n&#305;zda URL sorgu parametrelerine eri&#351;in:

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

Bir sorgu parametresinin mevcut olup olmad&#305;&#287;&#305;n&#305; kontrol edin:

``` dart
// In your page
if (widget.hasQueryParameter("tab")) {
  // Handle tab parameter
}
```

<div id="page-state-management"></div>

## Sayfa Durum Y&#246;netimi

Controller'lar sayfa durumunu do&#287;rudan y&#246;netebilir:

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

## Toast Bildirimleri

Controller'lar yerle&#351;ik toast bildirim metotlar&#305; i&#231;erir:

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

## Form Do&#287;rulama

Controller'&#305;n&#305;zdan form verilerini do&#287;rudan do&#287;rulay&#305;n:

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

## Dil De&#287;i&#351;tirme

Controller'&#305;n&#305;zdan uygulaman&#305;n dilini de&#287;i&#351;tirin:

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

## Kilit Serbest B&#305;rakma

Butonlara birden fazla h&#305;zl&#305; dokunmay&#305; &#246;nleyin:

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

## &#304;&#351;lem Onay&#305;

Y&#305;k&#305;c&#305; i&#351;lemlerden &#246;nce onay diyalogu g&#246;sterin:

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

## Singleton Controller'lar

Bir controller'&#305; uygulama genelinde singleton olarak kal&#305;c&#305; k&#305;l&#305;n:

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

Singleton controller'lar bir kez olu&#351;turulur ve uygulama ya&#351;am d&#246;ng&#252;s&#252; boyunca yeniden kullan&#305;l&#305;r.

<div id="controller-decoders"></div>

## Controller Kod &#199;&#246;z&#252;c&#252;ler

Controller'lar&#305;n&#305;z&#305; `lib/config/decoders.dart` dosyas&#305;nda kaydedin:

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

Bu harita, sayfalar y&#252;klendi&#287;inde {{ config('app.name') }}'nun controller'lar&#305; &#231;&#246;zmesine olanak tan&#305;r.

<div id="route-guards"></div>

## Rota Korumalar&#305;

Controller'lar, sayfa y&#252;klenmeden &#246;nce &#231;al&#305;&#351;an rota korumalar&#305; tan&#305;mlayabilir:

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

Rota korumalar&#305; hakk&#305;nda daha fazla bilgi i&#231;in [Router dok&#252;mantasyonuna](/docs/7.x/router) bak&#305;n.

