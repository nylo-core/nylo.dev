# Route Guards

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Crear un Route Guard](#creating-a-route-guard "Crear un Route Guard")
- [Ciclo de Vida del Guard](#guard-lifecycle "Ciclo de Vida del Guard")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [Acciones del Guard](#guard-actions "Acciones del Guard")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [Aplicar Guards a Rutas](#applying-guards "Aplicar Guards a Rutas")
- [Guards de Grupo](#group-guards "Guards de Grupo")
- [Composicion de Guards](#guard-composition "Composicion de Guards")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [Ejemplos](#examples "Ejemplos Practicos")

<div id="introduction"></div>

## Introduccion

Los route guards proporcionan **middleware para la navegacion** en {{ config('app.name') }}. Interceptan las transiciones de rutas y te permiten controlar si un usuario puede acceder a una pagina, redirigirlo a otro lugar o modificar los datos pasados a una ruta.

Casos de uso comunes incluyen:
- **Verificaciones de autenticacion** -- redirigir usuarios no autenticados a una pagina de inicio de sesion
- **Acceso basado en roles** -- restringir paginas a usuarios administradores
- **Validacion de datos** -- asegurar que los datos requeridos existan antes de la navegacion
- **Enriquecimiento de datos** -- adjuntar datos adicionales a una ruta

Los guards se ejecutan **en orden** antes de que ocurra la navegacion. Si algun guard retorna `handled`, la navegacion se detiene (ya sea redirigiendo o abortando).

<div id="creating-a-route-guard"></div>

## Crear un Route Guard

Crea un route guard usando el CLI de Metro:

``` bash
metro make:route_guard auth
```

Esto genera un archivo de guard:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Add your guard logic here
    return next();
  }
}
```

<div id="guard-lifecycle"></div>

## Ciclo de Vida del Guard

Cada route guard tiene tres metodos de ciclo de vida:

<div id="on-before"></div>

### onBefore

Se llama **antes** de que ocurra la navegacion. Aqui es donde verificas las condiciones y decides si permitir, redirigir o abortar la navegacion.

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  bool isLoggedIn = await Auth.isAuthenticated();

  if (!isLoggedIn) {
    return redirect(HomePage.path);
  }

  return next();
}
```

Valores de retorno:
- `next()` -- continuar al siguiente guard o navegar a la ruta
- `redirect(path)` -- redirigir a una ruta diferente
- `abort()` -- cancelar la navegacion completamente

<div id="on-after"></div>

### onAfter

Se llama **despues** de una navegacion exitosa. Usa esto para analiticas, registro o efectos secundarios posteriores a la navegacion.

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // Log page view
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

Se llama cuando el usuario esta **saliendo** de una ruta. Retorna `false` para evitar que el usuario salga.

``` dart
@override
Future<bool> onLeave(RouteContext context) async {
  if (hasUnsavedChanges) {
    // Show confirmation dialog
    return await showConfirmDialog();
  }
  return true; // Allow leaving
}
```

<div id="route-context"></div>

## RouteContext

El objeto `RouteContext` se pasa a todos los metodos del ciclo de vida del guard y contiene informacion sobre la navegacion:

| Propiedad | Tipo | Descripcion |
|----------|------|-------------|
| `context` | `BuildContext?` | Contexto de construccion actual |
| `data` | `dynamic` | Datos pasados a la ruta |
| `queryParameters` | `Map<String, String>` | Parametros de consulta de la URL |
| `routeName` | `String` | Nombre/ruta del destino |
| `originalRouteName` | `String?` | Nombre original de la ruta antes de transformaciones |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  // Access route information
  String route = context.routeName;
  dynamic routeData = context.data;
  Map<String, String> params = context.queryParameters;

  return next();
}
```

### Transformar Route Context

Crea una copia con datos diferentes:

``` dart
// Change the data type
RouteContext<User> userContext = context.withData<User>(currentUser);

// Copy with modified fields
RouteContext updated = context.copyWith(
  data: enrichedData,
  queryParameters: {"tab": "settings"},
);
```

<div id="guard-actions"></div>

## Acciones del Guard

<div id="next"></div>

### next

Continuar al siguiente guard en la cadena, o navegar a la ruta si este es el ultimo guard:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

Redirigir al usuario a una ruta diferente:

``` dart
return redirect(LoginPage.path);
```

Con opciones adicionales:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| Parametro | Tipo | Por Defecto | Descripcion |
|-----------|------|---------|-------------|
| `path` | `Object` | requerido | Cadena de ruta o RouteView |
| `data` | `dynamic` | null | Datos para pasar a la ruta de redireccion |
| `queryParameters` | `Map<String, dynamic>?` | null | Parametros de consulta |
| `navigationType` | `NavigationType` | `pushReplace` | Metodo de navegacion |
| `result` | `dynamic` | null | Resultado a retornar |
| `removeUntilPredicate` | `Function?` | null | Predicado de eliminacion de rutas |
| `transitionType` | `TransitionType?` | null | Tipo de transicion de pagina |
| `onPop` | `Function(dynamic)?` | null | Callback al hacer pop |

<div id="abort"></div>

### abort

Cancelar la navegacion sin redirigir. El usuario permanece en su pagina actual:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

Modificar los datos que se pasaran a los guards subsiguientes y a la ruta destino:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  User user = await fetchUser();

  // Enrich the route data
  setData({"user": user, "originalData": context.data});

  return next();
}
```

<div id="applying-guards"></div>

## Aplicar Guards a Rutas

Agrega guards a rutas individuales en tu archivo de router:

``` dart
appRouter() => nyRoutes((router) {
  router.route(
    HomePage.path,
    (_) => HomePage(),
  ).initialRoute();

  // Add a single guard
  router.route(
    ProfilePage.path,
    (_) => ProfilePage(),
    routeGuards: [AuthRouteGuard()],
  );

  // Add multiple guards (executed in order)
  router.route(
    AdminPage.path,
    (_) => AdminPage(),
    routeGuards: [AuthRouteGuard(), AdminRoleGuard()],
  );
});
```

<div id="group-guards"></div>

## Guards de Grupo

Aplica guards a multiples rutas a la vez usando grupos de rutas:

``` dart
appRouter() => nyRoutes((router) {
  router.route(HomePage.path, (_) => HomePage()).initialRoute();

  // All routes in this group require authentication
  router.group(() {
    return {
      'prefix': '/dashboard',
      'route_guards': [AuthRouteGuard()],
    };
  }, (router) {
    router.route(DashboardPage.path, (_) => DashboardPage());
    router.route(SettingsPage.path, (_) => SettingsPage());
    router.route(ProfilePage.path, (_) => ProfilePage());
  });
});
```

<div id="guard-composition"></div>

## Composicion de Guards

{{ config('app.name') }} proporciona herramientas para componer guards juntos en patrones reutilizables.

<div id="guard-stack"></div>

### GuardStack

Combina multiples guards en un unico guard reutilizable:

``` dart
final protectedRoute = GuardStack([
  AuthRouteGuard(),
  VerifyEmailGuard(),
  TwoFactorGuard(),
]);

// Use the stack on a route
router.route(
  SecurePage.path,
  (_) => SecurePage(),
  routeGuards: [protectedRoute],
);
```

`GuardStack` ejecuta los guards en orden. Si algun guard retorna `handled`, los guards restantes se omiten.

<div id="conditional-guard"></div>

### ConditionalGuard

Aplica un guard solo cuando una condicion es verdadera:

``` dart
router.route(
  BetaPage.path,
  (_) => BetaPage(),
  routeGuards: [
    ConditionalGuard(
      condition: (context) => context.queryParameters.containsKey("beta"),
      guard: BetaAccessGuard(),
    ),
  ],
);
```

Si la condicion retorna `false`, el guard se omite y la navegacion continua.

<div id="parameterized-guard"></div>

### ParameterizedGuard

Crea guards que aceptan parametros de configuracion:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params); // params = allowed roles

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();

    if (user == null || !params.contains(user.role)) {
      return redirect(UnauthorizedPage.path);
    }

    return next();
  }
}

// Usage
router.route(
  AdminPage.path,
  (_) => AdminPage(),
  routeGuards: [RoleGuard(["admin", "super_admin"])],
);
```

<div id="examples"></div>

## Ejemplos

### Guard de Autenticacion

``` dart
class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    bool isAuthenticated = await Auth.isAuthenticated();

    if (!isAuthenticated) {
      return redirect(HomePage.path);
    }

    return next();
  }
}
```

### Guard de Suscripcion con Parametros

``` dart
class SubscriptionGuard extends ParameterizedGuard<List<String>> {
  SubscriptionGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();
    bool hasAccess = params.any((plan) => user?.subscription == plan);

    if (!hasAccess) {
      return redirect(UpgradePage.path, data: {"plans": params});
    }

    setData({"user": user});
    return next();
  }
}

// Require premium or pro subscription
router.route(
  PremiumPage.path,
  (_) => PremiumPage(),
  routeGuards: [
    AuthRouteGuard(),
    SubscriptionGuard(["premium", "pro"]),
  ],
);
```

### Guard de Registro

``` dart
class LoggingGuard extends NyRouteGuard {
  LoggingGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    print("Navigating to: ${context.routeName}");
    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    print("Arrived at: ${context.routeName}");
  }
}
```
