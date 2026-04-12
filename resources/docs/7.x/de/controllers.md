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
    // Dienste initialisieren oder Daten abrufen
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
# Eine Seite mit einem Controller erstellen
metro make:page dashboard --controller
# oder Kurzform
metro make:page dashboard -c

# Nur einen Controller erstellen
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
    // Controller-Methoden aufrufen
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
// Mit Daten navigieren
routeTo(ProfilePage.path, data: {"userId": 123});

// In Ihrem Controller
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Die uebergebenen Daten abrufen
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
    // Vom Controller
    var userData = widget.controller.data();

    // Oder direkt vom Widget
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## Query-Parameter

Greifen Sie in Ihrem Controller auf URL-Query-Parameter zu:

``` dart
// Navigieren zu: /profile?tab=settings&highlight=true
routeTo("/profile?tab=settings&highlight=true");

// In Ihrem Controller
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Alle Query-Parameter als Map abrufen
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // Einen bestimmten Parameter abrufen
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

Prüfen Sie, ob ein Query-Parameter existiert:

``` dart
// In Ihrer Seite
if (widget.hasQueryParameter("tab")) {
  // Tab-Parameter behandeln
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
    // Ein setState auf der Seite ausloesen
    setState(setState: () {});
  }

  void refresh() {
    // Die gesamte Seite aktualisieren
    refreshPage();
  }

  void goBack() {
    // Die Seite mit optionalem Ergebnis verlassen
    pop(result: {"updated": true});
  }

  void goBackFromRoot() {
    // Aus dem Root-Navigator herausspringen (z. B. um ein modales Root-Fenster in einem Navigation Hub zu schliessen)
    pop(rootNavigator: true);
  }

  void updateCustomState() {
    // Benutzerdefinierte Aktion an die Seite senden
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
    // Erfolgs-Toast
    showToastSuccess(description: "Profile updated!");

    // Warn-Toast
    showToastWarning(description: "Please check your input");

    // Fehler/Gefahr-Toast
    showToastDanger(description: "Failed to save changes");

    // Info-Toast
    showToastInfo(description: "New features available");

    // Entschuldigungs-Toast
    showToastSorry(description: "We couldn't process your request");

    // Oops-Toast
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // Benutzerdefinierter Toast mit Titel
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // Benutzerdefinierten Toast-Stil verwenden (in Nylo registriert)
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
        // Validierung bestanden
        _performRegistration();
      },
      onFailure: (exception) {
        // Validierung fehlgeschlagen
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // Registrierungslogik verarbeiten
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
      // Dieser Code wird nur einmal ausgefuehrt, bis die Sperre freigegeben wird
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
      shouldSetState: false, // Kein setState danach ausloesen
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
        // Benutzer hat bestaetigt - Loeschung durchfuehren
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
    // Anmeldelogik
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
    // Wird nur ausgefuehrt, wenn alle Guards bestanden haben
  }
}
```

Siehe die [Router-Dokumentation](/docs/7.x/router) für weitere Details zu Route Guards.

