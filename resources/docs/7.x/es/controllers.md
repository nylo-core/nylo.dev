# Controllers

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion a los controladores")
- [Crear controladores](#creating-controllers "Crear controladores")
- [Usar controladores](#using-controllers "Usar controladores")
- Funcionalidades del controlador
  - [Acceder a datos de ruta](#accessing-route-data "Acceder a datos de ruta")
  - [Parametros de consulta](#query-parameters "Parametros de consulta")
  - [Gestion de estado de pagina](#page-state-management "Gestion de estado de pagina")
  - [Notificaciones toast](#toast-notifications "Notificaciones toast")
  - [Validacion de formularios](#form-validation "Validacion de formularios")
  - [Cambio de idioma](#language-switching "Cambio de idioma")
  - [Lock Release](#lock-release "Lock Release")
  - [Confirmar acciones](#confirm-actions "Confirmar acciones")
- [Controladores singleton](#singleton-controllers "Controladores singleton")
- [Decoders de controladores](#controller-decoders "Decoders de controladores")
- [Route Guards](#route-guards "Route Guards")

<div id="introduction"></div>

## Introduccion

Los controladores en {{ config('app.name') }} v7 actuan como el coordinador entre tus vistas (paginas) y la logica de negocio. Manejan la entrada del usuario, gestionan las actualizaciones de estado y proporcionan una separacion limpia de responsabilidades.

{{ config('app.name') }} v7 introduce la clase `NyController` con potentes metodos integrados para notificaciones toast, validacion de formularios, gestion de estado y mas.

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

## Crear controladores

Usa el CLI de Metro para generar controladores:

``` bash
# Create a page with a controller
metro make:page dashboard --controller
# or shorthand
metro make:page dashboard -c

# Create a controller only
metro make:controller profile_controller
```

Esto crea:
- **Controlador**: `lib/app/controllers/dashboard_controller.dart`
- **Pagina**: `lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## Usar controladores

Conecta un controlador a tu pagina especificandolo como el tipo generico en `NyStatefulWidget`:

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

## Acceder a datos de ruta

Pasa datos entre paginas y accede a ellos en tu controlador:

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

O accede a los datos directamente en el estado de tu pagina:

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

## Parametros de consulta

Accede a los parametros de consulta de la URL en tu controlador:

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

Verifica si existe un parametro de consulta:

``` dart
// In your page
if (widget.hasQueryParameter("tab")) {
  // Handle tab parameter
}
```

<div id="page-state-management"></div>

## Gestion de estado de pagina

Los controladores pueden gestionar el estado de la pagina directamente:

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

## Notificaciones toast

Los controladores incluyen metodos integrados para notificaciones toast:

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

## Validacion de formularios

Valida datos de formulario directamente desde tu controlador:

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

## Cambio de idioma

Cambia el idioma de la aplicacion desde tu controlador:

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

Previene multiples toques rapidos en botones:

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

## Confirmar acciones

Muestra un dialogo de confirmacion antes de realizar acciones destructivas:

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

## Controladores singleton

Haz que un controlador persista en toda la aplicacion como singleton:

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

Los controladores singleton se crean una vez y se reutilizan durante todo el ciclo de vida de la aplicacion.

<div id="controller-decoders"></div>

## Decoders de controladores

Registra tus controladores en `lib/config/decoders.dart`:

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

Este mapa permite a {{ config('app.name') }} resolver los controladores cuando se cargan las paginas.

<div id="route-guards"></div>

## Route Guards

Los controladores pueden definir route guards que se ejecutan antes de que la pagina se cargue:

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

Consulta la [documentacion del Router](/docs/7.x/router) para mas detalles sobre route guards.
