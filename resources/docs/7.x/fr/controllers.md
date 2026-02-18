# Controleurs

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Creer des controleurs](#creating-controllers "Creer des controleurs")
- [Utiliser les controleurs](#using-controllers "Utiliser les controleurs")
- Fonctionnalites des controleurs
  - [Acceder aux donnees de route](#accessing-route-data "Acceder aux donnees de route")
  - [Parametres de requete](#query-parameters "Parametres de requete")
  - [Gestion de l'etat de la page](#page-state-management "Gestion de l'etat de la page")
  - [Notifications toast](#toast-notifications "Notifications toast")
  - [Validation de formulaire](#form-validation "Validation de formulaire")
  - [Changement de langue](#language-switching "Changement de langue")
  - [Verrouillage et liberation](#lock-release "Verrouillage et liberation")
  - [Confirmer les actions](#confirm-actions "Confirmer les actions")
- [Controleurs singleton](#singleton-controllers "Controleurs singleton")
- [Decodeurs de controleurs](#controller-decoders "Decodeurs de controleurs")
- [Gardes de routes](#route-guards "Gardes de routes")

<div id="introduction"></div>

## Introduction

Les controleurs dans {{ config('app.name') }} v7 agissent comme coordinateurs entre vos vues (pages) et la logique metier. Ils gerent les entrees utilisateur, administrent les mises a jour d'etat et fournissent une separation claire des responsabilites.

{{ config('app.name') }} v7 introduit la classe `NyController` avec des methodes integrees puissantes pour les notifications toast, la validation de formulaires, la gestion d'etat, et plus encore.

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

## Creer des controleurs

Utilisez le Metro CLI pour generer des controleurs :

``` bash
# Create a page with a controller
metro make:page dashboard --controller
# or shorthand
metro make:page dashboard -c

# Create a controller only
metro make:controller profile_controller
```

Cela cree :
- **Controleur** : `lib/app/controllers/dashboard_controller.dart`
- **Page** : `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## Utiliser les controleurs

Connectez un controleur a votre page en le specifiiant comme type generique sur `NyStatefulWidget` :

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

## Acceder aux donnees de route

Passez des donnees entre les pages et accedez-y dans votre controleur :

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

Ou accedez aux donnees directement dans l'etat de votre page :

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

## Parametres de requete

Accedez aux parametres de requete URL dans votre controleur :

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

Verifiez si un parametre de requete existe :

``` dart
// In your page
if (widget.hasQueryParameter("tab")) {
  // Handle tab parameter
}
```

<div id="page-state-management"></div>

## Gestion de l'etat de la page

Les controleurs peuvent gerer l'etat de la page directement :

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

## Notifications toast

Les controleurs incluent des methodes integrees pour les notifications toast :

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

## Validation de formulaire

Validez les donnees de formulaire directement depuis votre controleur :

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

## Changement de langue

Changez la langue de l'application depuis votre controleur :

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

## Verrouillage et liberation

Empêchez les appuis rapides multiples sur les boutons :

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

## Confirmer les actions

Affichez une boite de dialogue de confirmation avant d'effectuer des actions destructives :

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

## Controleurs singleton

Rendez un controleur persistant dans l'application en tant que singleton :

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

Les controleurs singleton sont crees une seule fois et reutilises tout au long du cycle de vie de l'application.

<div id="controller-decoders"></div>

## Decodeurs de controleurs

Enregistrez vos controleurs dans `lib/config/decoders.dart` :

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

Cette map permet a {{ config('app.name') }} de resoudre les controleurs lorsque les pages sont chargees.

<div id="route-guards"></div>

## Gardes de routes

Les controleurs peuvent definir des gardes de routes qui s'executent avant le chargement de la page :

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

Consultez la [documentation du routeur](/docs/7.x/router) pour plus de details sur les gardes de routes.

