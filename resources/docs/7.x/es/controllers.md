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
    // Inicializar servicios o recuperar datos
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
# Crear una pagina con un controlador
metro make:page dashboard --controller
# o forma abreviada
metro make:page dashboard -c

# Crear solo un controlador
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
    // Acceder a los metodos del controlador
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
// Navegar con datos
routeTo(ProfilePage.path, data: {"userId": 123});

// En tu controlador
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Obtener los datos pasados
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
    // Desde el controlador
    var userData = widget.controller.data();

    // O directamente desde el widget
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## Parametros de consulta

Accede a los parametros de consulta de la URL en tu controlador:

``` dart
// Navegar a: /profile?tab=settings&highlight=true
routeTo("/profile?tab=settings&highlight=true");

// En tu controlador
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // Obtener todos los parametros de consulta como Map
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // Obtener un parametro especifico
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

Verifica si existe un parametro de consulta:

``` dart
// En tu pagina
if (widget.hasQueryParameter("tab")) {
  // Manejar el parametro tab
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
    // Disparar un setState en la pagina
    setState(setState: () {});
  }

  void refresh() {
    // Actualizar toda la pagina
    refreshPage();
  }

  void goBack() {
    // Sacar la pagina con resultado opcional
    pop(result: {"updated": true});
  }

  void goBackFromRoot() {
    // Sacar desde el navegador raiz (por ej. para cerrar un modal de nivel raiz en un Navigation Hub)
    pop(rootNavigator: true);
  }

  void updateCustomState() {
    // Enviar accion personalizada a la pagina
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
    // Toast de exito
    showToastSuccess(description: "Profile updated!");

    // Toast de advertencia
    showToastWarning(description: "Please check your input");

    // Toast de error/peligro
    showToastDanger(description: "Failed to save changes");

    // Toast de informacion
    showToastInfo(description: "New features available");

    // Toast de disculpa
    showToastSorry(description: "We couldn't process your request");

    // Toast oops
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // Toast personalizado con titulo
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // Usar estilo de toast personalizado (registrado en Nylo)
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
        // Validacion exitosa
        _performRegistration();
      },
      onFailure: (exception) {
        // Validacion fallida
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // Manejar la logica de registro
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
      // Este codigo solo se ejecuta una vez hasta que se libera el bloqueo
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
      shouldSetState: false, // No disparar setState despues
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
        // Usuario confirmo - realizar eliminacion
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
    // Logica de inicio de sesion
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
    // Solo se ejecuta si todos los guards pasan
  }
}
```

Consulta la [documentacion del Router](/docs/7.x/router) para mas detalles sobre route guards.
