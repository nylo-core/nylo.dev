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
    // Zainicjalizuj serwisy lub pobierz dane
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
# Utwórz stronę z kontrolerem
metro make:page dashboard --controller
# lub skrócona forma
metro make:page dashboard -c

# Utwórz tylko kontroler
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
    // Dostęp do metod kontrolera
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
// Nawiguj z danymi
routeTo(ProfilePage.path, data: {"userId": 123});

// W kontrolerze
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Pobierz przekazane dane
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
    // Z kontrolera
    var userData = widget.controller.data();

    // Lub bezpośrednio z widgetu
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## Parametry zapytania

Uzyskaj dostęp do parametrów zapytania URL w kontrolerze:

``` dart
// Nawiguj do: /profile?tab=settings&highlight=true
routeTo("/profile?tab=settings&highlight=true");

// W kontrolerze
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Pobierz wszystkie parametry zapytania jako Mapę
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // Pobierz konkretny parametr
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

Sprawdź, czy parametr zapytania istnieje:

``` dart
// Na stronie
if (widget.hasQueryParameter("tab")) {
  // Obsłuż parametr tab
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
    // Wywołaj setState na stronie
    setState(setState: () {});
  }

  void refresh() {
    // Odśwież całą stronę
    refreshPage();
  }

  void goBack() {
    // Wyjdź ze strony z opcjonalnym wynikiem
    pop(result: {"updated": true});
  }

  void goBackFromRoot() {
    // Wyjdź z głównego navigatora (np. aby zamknąć modalny na poziomie głównym w Navigation Hub)
    pop(rootNavigator: true);
  }

  void updateCustomState() {
    // Wyślij niestandardową akcję do strony
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
    // Toast sukcesu
    showToastSuccess(description: "Profile updated!");

    // Toast ostrzeżenia
    showToastWarning(description: "Please check your input");

    // Toast błędu/niebezpieczeństwa
    showToastDanger(description: "Failed to save changes");

    // Toast informacyjny
    showToastInfo(description: "New features available");

    // Toast przepraszam
    showToastSorry(description: "We couldn't process your request");

    // Toast ups
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // Niestandardowy toast z tytułem
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // Użyj niestandardowego stylu toast (zarejestrowanego w Nylo)
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
        // Walidacja przeszła pomyślnie
        _performRegistration();
      },
      onFailure: (exception) {
        // Walidacja nie powiodła się
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // Obsłuż logikę rejestracji
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
      // Ten kod uruchamia się tylko raz do czasu zwolnienia blokady
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
      shouldSetState: false, // Nie wywołuj setState po zakończeniu
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
        // Użytkownik potwierdził - wykonaj usunięcie
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
    // Logika logowania
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
    // Uruchamia się tylko jeśli wszystkie strażnicy przejdą pomyślnie
  }
}
```

Zobacz [dokumentację routera](/docs/7.x/router), aby uzyskać więcej szczegółów na temat strażników tras.
