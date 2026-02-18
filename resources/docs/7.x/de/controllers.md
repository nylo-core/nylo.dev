# Controller

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Controller erstellen](#creating-controllers "Controller erstellen")
- [Controller verwenden](#using-controllers "Controller verwenden")
- Controller-Funktionen
  - [Auf Routendaten zugreifen](#accessing-route-data "Auf Routendaten zugreifen")
  - [Query-Parameter](#query-parameters "Query-Parameter")
  - [Seiten-Statusverwaltung](#page-state-management "Seiten-Statusverwaltung")
  - [Toast-Benachrichtigungen](#toast-notifications "Toast-Benachrichtigungen")
  - [Formularvalidierung](#form-validation "Formularvalidierung")
  - [Sprachwechsel](#language-switching "Sprachwechsel")
  - [Lock Release](#lock-release "Lock Release")
  - [Aktionen bestätigen](#confirm-actions "Aktionen bestätigen")
- [Singleton-Controller](#singleton-controllers "Singleton-Controller")
- [Controller-Decoders](#controller-decoders "Controller-Decoders")
- [Route Guards](#route-guards "Route Guards")

<div id="introduction"></div>

## Einleitung

Controller in {{ config('app.name') }} v7 fungieren als Koordinator zwischen Ihren Views (Seiten) und der Geschäftslogik. Sie verarbeiten Benutzereingaben, verwalten Statusaktualisierungen und bieten eine saubere Trennung der Zuständigkeiten.

{{ config('app.name') }} v7 führt die `NyController`-Klasse mit leistungsstarken integrierten Methoden für Toast-Benachrichtigungen, Formularvalidierung, Statusverwaltung und mehr ein.

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

## Controller erstellen

Verwenden Sie die Metro CLI, um Controller zu generieren:

``` bash
# Create a page with a controller
metro make:page dashboard --controller
# or shorthand
metro make:page dashboard -c

# Create a controller only
metro make:controller profile_controller
```

Dies erstellt:
- **Controller**: `lib/app/controllers/dashboard_controller.dart`
- **Seite**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## Controller verwenden

Verbinden Sie einen Controller mit Ihrer Seite, indem Sie ihn als generischen Typ auf `NyStatefulWidget` angeben:

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

## Auf Routendaten zugreifen

Übergeben Sie Daten zwischen Seiten und greifen Sie in Ihrem Controller darauf zu:

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

Oder greifen Sie direkt in Ihrem Seitenstatus auf Daten zu:

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

## Query-Parameter

Greifen Sie in Ihrem Controller auf URL-Query-Parameter zu:

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

Prüfen Sie, ob ein Query-Parameter existiert:

``` dart
// In your page
if (widget.hasQueryParameter("tab")) {
  // Handle tab parameter
}
```

<div id="page-state-management"></div>

## Seiten-Statusverwaltung

Controller können den Seitenstatus direkt verwalten:

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

## Toast-Benachrichtigungen

Controller enthalten integrierte Toast-Benachrichtigungsmethoden:

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

## Formularvalidierung

Validieren Sie Formulardaten direkt aus Ihrem Controller:

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

## Sprachwechsel

Ändern Sie die Sprache der App aus Ihrem Controller:

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

Verhindern Sie mehrfaches schnelles Tippen auf Buttons:

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

## Aktionen bestätigen

Zeigen Sie einen Bestätigungsdialog vor destruktiven Aktionen an:

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

## Singleton-Controller

Machen Sie einen Controller zu einem Singleton, der in der gesamten App bestehen bleibt:

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

Singleton-Controller werden einmal erstellt und während des gesamten App-Lebenszyklus wiederverwendet.

<div id="controller-decoders"></div>

## Controller-Decoders

Registrieren Sie Ihre Controller in `lib/config/decoders.dart`:

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

Diese Map ermöglicht es {{ config('app.name') }}, Controller aufzulösen, wenn Seiten geladen werden.

<div id="route-guards"></div>

## Route Guards

Controller können Route Guards definieren, die vor dem Laden der Seite ausgeführt werden:

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

Siehe die [Router-Dokumentation](/docs/7.x/router) für weitere Details zu Route Guards.

