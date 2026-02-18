# Controllers

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do kontrolerów")
- [Tworzenie kontrolerów](#creating-controllers "Tworzenie kontrolerów")
- [Używanie kontrolerów](#using-controllers "Używanie kontrolerów")
- Funkcje kontrolerów
  - [Dostęp do danych trasy](#accessing-route-data "Dostęp do danych trasy")
  - [Parametry zapytania](#query-parameters "Parametry zapytania")
  - [Zarządzanie stanem strony](#page-state-management "Zarządzanie stanem strony")
  - [Powiadomienia toast](#toast-notifications "Powiadomienia toast")
  - [Walidacja formularzy](#form-validation "Walidacja formularzy")
  - [Zmiana języka](#language-switching "Zmiana języka")
  - [Lock Release](#lock-release "Lock Release")
  - [Potwierdzanie akcji](#confirm-actions "Potwierdzanie akcji")
- [Kontrolery singleton](#singleton-controllers "Kontrolery singleton")
- [Dekodery kontrolerów](#controller-decoders "Dekodery kontrolerów")
- [Strażnicy tras](#route-guards "Strażnicy tras")

<div id="introduction"></div>

## Wprowadzenie

Kontrolery w {{ config('app.name') }} v7 pełnią rolę koordynatora między widokami (stronami) a logiką biznesową. Obsługują dane wejściowe użytkownika, zarządzają aktualizacjami stanu i zapewniają czyste rozdzielenie odpowiedzialności.

{{ config('app.name') }} v7 wprowadza klasę `NyController` z potężnymi wbudowanymi metodami do powiadomień toast, walidacji formularzy, zarządzania stanem i nie tylko.

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

## Tworzenie kontrolerów

Użyj Metro CLI do generowania kontrolerów:

``` bash
# Create a page with a controller
metro make:page dashboard --controller
# or shorthand
metro make:page dashboard -c

# Create a controller only
metro make:controller profile_controller
```

To tworzy:
- **Kontroler**: `lib/app/controllers/dashboard_controller.dart`
- **Stronę**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## Używanie kontrolerów

Połącz kontroler ze stroną, podając go jako typ generyczny w `NyStatefulWidget`:

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

## Dostęp do danych trasy

Przekazuj dane między stronami i uzyskuj do nich dostęp w kontrolerze:

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

Lub uzyskaj dostęp do danych bezpośrednio w stanie strony:

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

## Parametry zapytania

Uzyskaj dostęp do parametrów zapytania URL w kontrolerze:

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

Sprawdź, czy parametr zapytania istnieje:

``` dart
// In your page
if (widget.hasQueryParameter("tab")) {
  // Handle tab parameter
}
```

<div id="page-state-management"></div>

## Zarządzanie stanem strony

Kontrolery mogą bezpośrednio zarządzać stanem strony:

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

## Powiadomienia toast

Kontrolery zawierają wbudowane metody powiadomień toast:

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

## Walidacja formularzy

Waliduj dane formularza bezpośrednio z kontrolera:

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

## Zmiana języka

Zmień język aplikacji z kontrolera:

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

Zapobiegaj wielokrotnym szybkim tapnięciom przycisków:

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

## Potwierdzanie akcji

Wyświetl dialog potwierdzenia przed wykonaniem destrukcyjnych akcji:

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

## Kontrolery singleton

Utwórz kontroler, który działa w całej aplikacji jako singleton:

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

Kontrolery singleton są tworzone raz i używane ponownie przez cały cykl życia aplikacji.

<div id="controller-decoders"></div>

## Dekodery kontrolerów

Zarejestruj kontrolery w `lib/config/decoders.dart`:

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

Ta mapa pozwala {{ config('app.name') }} rozwiązywać kontrolery podczas ładowania stron.

<div id="route-guards"></div>

## Strażnicy tras

Kontrolery mogą definiować strażników tras, którzy uruchamiają się przed załadowaniem strony:

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

Zobacz [dokumentację routera](/docs/7.x/router), aby uzyskać więcej szczegółów na temat strażników tras.
