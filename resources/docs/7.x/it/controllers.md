# Controller

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Creazione dei Controller](#creating-controllers "Creazione dei Controller")
- [Utilizzo dei Controller](#using-controllers "Utilizzo dei Controller")
- Funzionalità dei Controller
  - [Accesso ai Dati della Rotta](#accessing-route-data "Accesso ai Dati della Rotta")
  - [Parametri Query](#query-parameters "Parametri Query")
  - [Gestione dello Stato della Pagina](#page-state-management "Gestione dello Stato della Pagina")
  - [Notifiche Toast](#toast-notifications "Notifiche Toast")
  - [Validazione del Form](#form-validation "Validazione del Form")
  - [Cambio Lingua](#language-switching "Cambio Lingua")
  - [Lock Release](#lock-release "Lock Release")
  - [Azioni di Conferma](#confirm-actions "Azioni di Conferma")
- [Controller Singleton](#singleton-controllers "Controller Singleton")
- [Decoder dei Controller](#controller-decoders "Decoder dei Controller")
- [Route Guard](#route-guards "Route Guard")

<div id="introduction"></div>

## Introduzione

I controller in {{ config('app.name') }} v7 agiscono come coordinatori tra le tue viste (pagine) e la logica di business. Gestiscono l'input dell'utente, gestiscono gli aggiornamenti dello stato e forniscono una chiara separazione delle responsabilità.

{{ config('app.name') }} v7 introduce la classe `NyController` con potenti metodi integrati per notifiche toast, validazione dei form, gestione dello stato e altro.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class HomeController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);
    // Inizializza i servizi o recupera i dati
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

## Creazione dei Controller

Usa la CLI Metro per generare i controller:

``` bash
# Crea una pagina con un controller
metro make:page dashboard --controller
# o abbreviato
metro make:page dashboard -c

# Crea solo un controller
metro make:controller profile_controller
```

Questo crea:
- **Controller**: `lib/app/controllers/dashboard_controller.dart`
- **Pagina**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## Utilizzo dei Controller

Collega un controller alla tua pagina specificandolo come tipo generico su `NyStatefulWidget`:

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
    // Accedi ai metodi del controller
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

## Accesso ai Dati della Rotta

Passa dati tra le pagine e accedivi nel tuo controller:

``` dart
// Naviga con i dati
routeTo(ProfilePage.path, data: {"userId": 123});

// Nel tuo controller
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Ottieni i dati passati
    Map<String, dynamic>? userData = data();
    int? userId = userData?['userId'];
  }
}
```

Oppure accedi ai dati direttamente nel tuo stato della pagina:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () async {
    // Dal controller
    var userData = widget.controller.data();

    // O direttamente dal widget
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## Parametri Query

Accedi ai parametri query dell'URL nel tuo controller:

``` dart
// Naviga verso: /profile?tab=settings&highlight=true
routeTo("/profile?tab=settings&highlight=true");

// Nel tuo controller
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Ottieni tutti i parametri query come Map
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // Ottieni un parametro specifico
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

Verifica se un parametro query esiste:

``` dart
// Nella tua pagina
if (widget.hasQueryParameter("tab")) {
  // Gestisci il parametro tab
}
```

<div id="page-state-management"></div>

## Gestione dello Stato della Pagina

I controller possono gestire direttamente lo stato della pagina:

``` dart
class HomeController extends NyController {

  int counter = 0;

  void increment() {
    counter++;
    // Attiva un setState sulla pagina
    setState(setState: () {});
  }

  void refresh() {
    // Aggiorna l'intera pagina
    refreshPage();
  }

  void goBack() {
    // Chiudi la pagina con risultato opzionale
    pop(result: {"updated": true});
  }

  void goBackFromRoot() {
    // Chiudi dal navigator root (ad es. per chiudere un modale root-level in un Navigation Hub)
    pop(rootNavigator: true);
  }

  void updateCustomState() {
    // Invia un'azione personalizzata alla pagina
    updatePageState("customAction", {"key": "value"});
  }
}
```

<div id="toast-notifications"></div>

## Notifiche Toast

I controller includono metodi integrati per le notifiche toast:

``` dart
class FormController extends NyController {

  void showNotifications() {
    // Toast di successo
    showToastSuccess(description: "Profile updated!");

    // Toast di avviso
    showToastWarning(description: "Please check your input");

    // Toast di errore/pericolo
    showToastDanger(description: "Failed to save changes");

    // Toast informativo
    showToastInfo(description: "New features available");

    // Toast di scuse
    showToastSorry(description: "We couldn't process your request");

    // Toast di oops
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // Toast personalizzato con titolo
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // Usa uno stile toast personalizzato (registrato in Nylo)
    showToastCustom(
      title: "Custom",
      description: "Using custom style",
      id: "my_custom_toast",
    );
  }
}
```

<div id="form-validation"></div>

## Validazione del Form

Valida i dati del form direttamente dal tuo controller:

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
        // Validazione superata
        _performRegistration();
      },
      onFailure: (exception) {
        // Validazione fallita
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // Gestisci la logica di registrazione
    showToastSuccess(description: "Account created!");
  }
}
```

<div id="language-switching"></div>

## Cambio Lingua

Cambia la lingua dell'app dal tuo controller:

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

Previeni tap rapidi multipli sui pulsanti:

``` dart
class CheckoutController extends NyController {

  void onTapPurchase() {
    lockRelease("purchase_lock", perform: () async {
      // Questo codice viene eseguito una sola volta fino al rilascio del lock
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
      shouldSetState: false, // Non attivare setState dopo
    );
  }
}
```

<div id="confirm-actions"></div>

## Azioni di Conferma

Mostra un dialogo di conferma prima di eseguire azioni distruttive:

``` dart
class AccountController extends NyController {

  void onTapDeleteAccount() {
    confirmAction(
      () async {
        // Utente confermato - esegui l'eliminazione
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

Rendi un controller persistente nell'app come singleton:

``` dart
class AuthController extends NyController {

  @override
  bool get singleton => true;

  User? currentUser;

  Future<void> login(String email, String password) async {
    // Logica di login
    currentUser = await AuthService.login(email, password);
  }

  bool get isLoggedIn => currentUser != null;
}
```

I controller singleton vengono creati una sola volta e riutilizzati per tutta la durata dell'app.

<div id="controller-decoders"></div>

## Decoder dei Controller

Registra i tuoi controller in `lib/config/decoders.dart`:

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

Questa mappa consente a {{ config('app.name') }} di risolvere i controller quando le pagine vengono caricate.

<div id="route-guards"></div>

## Route Guard

I controller possono definire route guard che vengono eseguiti prima del caricamento della pagina:

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
    // Viene eseguito solo se tutti i guard superano il controllo
  }
}
```

Consulta la [documentazione del Router](/docs/7.x/router) per maggiori dettagli sui route guard.

