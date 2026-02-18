# Router

---

<a name="section-1"></a>

- [Introduccion](#introduction "Introduccion")
- Conceptos basicos
  - [Agregar rutas](#adding-routes "Agregar rutas")
  - [Navegar a paginas](#navigating-to-pages "Navegar a paginas")
  - [Ruta inicial](#initial-route "Ruta inicial")
  - [Ruta de vista previa](#preview-route "Ruta de vista previa")
  - [Ruta autenticada](#authenticated-route "Ruta autenticada")
  - [Ruta desconocida](#unknown-route "Ruta desconocida")
- Enviar datos a otra pagina
  - [Pasar datos a otra pagina](#passing-data-to-another-page "Pasar datos a otra pagina")
- Navegacion
  - [Tipos de navegacion](#navigation-types "Tipos de navegacion")
  - [Navegar hacia atras](#navigating-back "Navegar hacia atras")
  - [Navegacion condicional](#conditional-navigation "Navegacion condicional")
  - [Transiciones de pagina](#page-transitions "Transiciones de pagina")
  - [Historial de rutas](#route-history "Historial de rutas")
  - [Actualizar pila de rutas](#update-route-stack "Actualizar pila de rutas")
- Parametros de ruta
  - [Usar parametros de ruta](#route-parameters "Parametros de ruta")
  - [Parametros de consulta](#query-parameters "Parametros de consulta")
- Route Guards
  - [Crear Route Guards](#route-guards "Route Guards")
  - [Ciclo de vida de NyRouteGuard](#nyroute-guard-lifecycle "Ciclo de vida de NyRouteGuard")
  - [Metodos auxiliares de Guard](#guard-helper-methods "Metodos auxiliares de Guard")
  - [Guards parametrizados](#parameterized-guards "Guards parametrizados")
  - [Pilas de Guards](#guard-stacks "Pilas de Guards")
  - [Guards condicionales](#conditional-guards "Guards condicionales")
- Grupos de rutas
  - [Grupos de rutas](#route-groups "Grupos de rutas")
- [Deep linking](#deep-linking "Deep linking")
- [Avanzado](#advanced "Avanzado")



<div id="introduction"></div>

## Introduccion

Las rutas te permiten definir las diferentes paginas de tu aplicacion y navegar entre ellas.

Usa rutas cuando necesites:
- Definir las paginas disponibles en tu aplicacion
- Navegar a los usuarios entre pantallas
- Proteger paginas detras de autenticacion
- Pasar datos de una pagina a otra
- Manejar deep links desde URLs

Puedes agregar rutas dentro del archivo `lib/routes/router.dart`.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // agregar mas rutas
  // router.add(AccountPage.path);

});
```

> **Consejo:** Puedes crear tus rutas manualmente o usar la herramienta <a href="/docs/{{ $version }}/metro">Metro</a> CLI para crearlas por ti.

Aqui hay un ejemplo de como crear una pagina 'account' usando Metro.

``` bash
metro make:page account_page
```

``` dart
// Agrega tu nueva ruta automaticamente a /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

Tambien puedes necesitar pasar datos de una vista a otra. En {{ config('app.name') }}, esto es posible usando `NyStatefulWidget` (un widget con estado con acceso integrado a datos de ruta). Profundizaremos mas para explicar como funciona.


<div id="adding-routes"></div>

## Agregar rutas

Esta es la forma mas facil de agregar nuevas rutas a tu proyecto.

Ejecuta el siguiente comando para crear una nueva pagina.

```bash
metro make:page profile_page
```

Despues de ejecutar lo anterior, creara un nuevo Widget llamado `ProfilePage` y lo agregara a tu directorio `resources/pages/`.
Tambien agregara la nueva ruta a tu archivo `lib/routes/router.dart`.

Archivo: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // Mi nueva ruta
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## Navegar a paginas

Puedes navegar a nuevas paginas usando el helper `routeTo`.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## Ruta inicial

En tus routers, puedes definir la primera pagina que debe cargarse usando el metodo `.initialRoute()`.

Una vez que hayas establecido la ruta inicial, sera la primera pagina que se cargue al abrir la aplicacion.

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // nueva ruta inicial
});
```


### Ruta inicial condicional

Tambien puedes establecer una ruta inicial condicional usando el parametro `when`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(OnboardingPage.path).initialRoute(
    when: () => !hasCompletedOnboarding()
  );

  router.add(HomePage.path).initialRoute(
    when: () => hasCompletedOnboarding()
  );
});
```

### Navegar a la ruta inicial

Usa `routeToInitial()` para navegar a la ruta inicial de la aplicacion:

``` dart
void _goHome() {
    routeToInitial();
}
```

Esto navegara a la ruta marcada con `.initialRoute()` y limpiara la pila de navegacion.

<div id="preview-route"></div>

## Ruta de vista previa

Durante el desarrollo, puedes querer previsualizar rapidamente una pagina especifica sin cambiar tu ruta inicial permanentemente. Usa `.previewRoute()` para hacer temporalmente cualquier ruta la ruta inicial:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // Esta se mostrara primero durante el desarrollo
});
```

El metodo `previewRoute()`:
- Sobreescribe cualquier configuracion existente de `initialRoute()` y `authenticatedRoute()`
- Hace que la ruta especificada sea la ruta inicial
- Util para probar rapidamente paginas especificas durante el desarrollo

> **Advertencia:** Recuerda eliminar `.previewRoute()` antes de lanzar tu aplicacion.

<div id="authenticated-route"></div>

## Ruta autenticada

En tu aplicacion, puedes definir una ruta para que sea la ruta inicial cuando un usuario esta autenticado.
Esto sobreescribira automaticamente la ruta inicial por defecto y sera la primera pagina que el usuario vea cuando inicie sesion.

Primero, tu usuario debe estar autenticado usando el helper `Auth.authenticate({...})`.

Ahora, cuando abran la aplicacion, la ruta que hayas definido sera la pagina por defecto hasta que cierren sesion.

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // pagina de autenticacion
});
```

### Ruta autenticada condicional

Tambien puedes establecer una ruta autenticada condicional:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### Navegar a la ruta autenticada

Puedes navegar a la pagina autenticada usando el helper `routeToAuthenticatedRoute()`:

``` dart
routeToAuthenticatedRoute();
```

**Ver tambien:** [Authentication](/docs/{{ $version }}/authentication) para detalles sobre la autenticacion de usuarios y la gestion de sesiones.


<div id="unknown-route"></div>

## Ruta desconocida

Puedes definir una ruta para manejar escenarios 404/no encontrado usando `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

Cuando un usuario navega a una ruta que no existe, se le mostrara la pagina de ruta desconocida.


<div id="route-guards"></div>

## Route guards

Los route guards protegen paginas de acceso no autorizado. Se ejecutan antes de que la navegacion se complete, permitiendote redirigir usuarios o bloquear el acceso basandote en condiciones.

Usa route guards cuando necesites:
- Proteger paginas de usuarios no autenticados
- Verificar permisos antes de permitir el acceso
- Redirigir usuarios basandote en condiciones (ej., onboarding no completado)
- Registrar o rastrear vistas de pagina

Para crear un nuevo Route Guard, ejecuta el siguiente comando.

``` bash
metro make:route_guard dashboard
```

Luego, agrega el nuevo Route Guard a tu ruta.

``` dart
// Archivo: /routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(LoginPage.path);

  router.add(DashboardPage.path,
    routeGuards: [
      DashboardRouteGuard() // Agrega tu guard
    ]
  ); // pagina restringida
});
```

Tambien puedes establecer route guards usando el metodo `addRouteGuard`:

``` dart
// Archivo: /routes/router.dart
appRouter() => nyRoutes((router) {
    router.add(DashboardPage.path)
            .addRouteGuard(MyRouteGuard());

    // o agregar multiples guards

    router.add(DashboardPage.path)
            .addRouteGuards([MyRouteGuard(), MyOtherRouteGuard()]);
})
```

<div id="nyroute-guard-lifecycle"></div>

## Ciclo de vida de NyRouteGuard

En v7, los route guards usan la clase `NyRouteGuard` con tres metodos de ciclo de vida:

- **`onBefore(RouteContext context)`** - Se llama antes de la navegacion. Devuelve `next()` para continuar, `redirect()` para ir a otro lugar, o `abort()` para detener.
- **`onAfter(RouteContext context)`** - Se llama despues de la navegacion exitosa a la ruta.

### Ejemplo basico

Archivo: **/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Realizar una verificacion si pueden acceder a la pagina
    bool userLoggedIn = await Auth.isAuthenticated();

    if (userLoggedIn == false) {
      return redirect(LoginPage.path);
    }

    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    // Rastrear vista de pagina despues de navegacion exitosa
    Analytics.trackPageView(context.routeName);
  }
}
```

### RouteContext

La clase `RouteContext` proporciona acceso a la informacion de navegacion:

| Propiedad | Tipo | Descripcion |
|-----------|------|-------------|
| `context` | `BuildContext?` | Contexto de construccion actual |
| `data` | `dynamic` | Datos pasados a la ruta |
| `queryParameters` | `Map<String, String>` | Parametros de consulta de la URL |
| `routeName` | `String` | Nombre/ruta de la ruta |
| `originalRouteName` | `String?` | Nombre original de la ruta antes de transformaciones |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  print('Navigating to: ${context.routeName}');
  print('Query params: ${context.queryParameters}');
  print('Route data: ${context.data}');

  return next();
}
```

<div id="guard-helper-methods"></div>

## Metodos auxiliares de Guard

### next()

Continuar al siguiente guard o a la ruta:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Permitir que la navegacion continue
}
```

### redirect()

Redirigir a una ruta diferente:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (!isLoggedIn) {
    return redirect(
      LoginPage.path,
      data: {'returnTo': context.routeName},
      navigationType: NavigationType.pushReplace,
    );
  }
  return next();
}
```

El metodo `redirect()` acepta:

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `path` | `Object` | Ruta o RouteView |
| `data` | `dynamic` | Datos a pasar a la ruta |
| `queryParameters` | `Map<String, dynamic>?` | Parametros de consulta |
| `navigationType` | `NavigationType` | Tipo de navegacion (por defecto: pushReplace) |
| `transitionType` | `TransitionType?` | Transicion de pagina |
| `onPop` | `Function(dynamic)?` | Callback cuando la ruta hace pop |

### abort()

Detener la navegacion sin redirigir:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (isMaintenanceMode) {
    showMaintenanceDialog();
    return abort(); // El usuario permanece en la ruta actual
  }
  return next();
}
```

### setData()

Modificar datos pasados a los guards subsiguientes y a la ruta:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## Guards parametrizados

Usa `ParameterizedGuard` cuando necesites configurar el comportamiento del guard por ruta:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    final user = await Auth.user();
    if (!params.any((role) => user.hasRole(role))) {
      return redirect('/unauthorized');
    }
    return next();
  }
}

// Uso:
router.add(AdminPage.path, routeGuards: [
  RoleGuard(['admin', 'moderator'])
]);
```

<div id="guard-stacks"></div>

## Pilas de Guards

Compone multiples guards en un solo guard reutilizable usando `GuardStack`:

``` dart
// Crear combinaciones de guards reutilizables
final adminGuards = GuardStack([
  AuthGuard(),
  RoleGuard(['admin']),
  AuditLogGuard(),
]);

router.add(AdminPage.path, routeGuards: [adminGuards]);
```

<div id="conditional-guards"></div>

## Guards condicionales

Aplica guards condicionalmente basandote en un predicado:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## Pasar datos a otra pagina

En esta seccion, mostraremos como puedes pasar datos de un widget a otro.

Desde tu Widget, usa el helper `routeTo` y pasa los `data` que deseas enviar a la nueva pagina.

``` dart
// Widget HomePage
void _pressedSettings() {
    routeTo(SettingsPage.path, data: "Hello World");
}
...
// Widget SettingsPage (otra pagina)
class _SettingsPageState extends NyPage<SettingsPage> {
  ...
  @override
  get init => () {
    print(widget.data()); // Hello World
    // o
    print(data()); // Hello World
  };
```

Mas ejemplos

``` dart
// Widget de la pagina Home
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo(ProfilePage.path, data: user);
  }

...

// Widget de la pagina Profile (otra pagina)
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    User user = widget.data();
    print(user.firstName); // Anthony

  };
```


<div id="route-groups"></div>

## Grupos de rutas

Los grupos de rutas organizan rutas relacionadas y aplican configuraciones compartidas. Son utiles cuando multiples rutas necesitan los mismos guards, prefijo de URL o estilo de transicion.

Usa grupos de rutas cuando necesites:
- Aplicar el mismo route guard a multiples paginas
- Agregar un prefijo de URL a un conjunto de rutas (ej., `/admin/...`)
- Establecer la misma transicion de pagina para rutas relacionadas

Puedes definir un grupo de rutas como en el ejemplo a continuacion.

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.group(() => {
    "route_guards": [AuthRouteGuard()],
    "prefix": "/dashboard",
    "transition_type": TransitionType.fade(),
  }, (router) {
    router.add(ChatPage.path);

    router.add(FollowersPage.path);
  });
```

#### Configuraciones opcionales para grupos de rutas:

| Configuracion | Tipo | Descripcion |
|---------------|------|-------------|
| `route_guards` | `List<RouteGuard>` | Aplicar route guards a todas las rutas del grupo |
| `prefix` | `String` | Agregar un prefijo a todas las rutas del grupo |
| `transition_type` | `TransitionType` | Establecer transicion para todas las rutas del grupo |
| `transition` | `PageTransitionType` | Establecer tipo de transicion de pagina (obsoleto, usar transition_type) |
| `transition_settings` | `PageTransitionSettings` | Establecer configuraciones de transicion |


<div id="route-parameters"></div>

## Usar parametros de ruta

Cuando creas una nueva pagina, puedes actualizar la ruta para aceptar parametros.

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

Ahora, cuando navegues a la pagina, puedes pasar el `userId`

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

Puedes acceder a los parametros en la nueva pagina asi.

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## Parametros de consulta

Al navegar a una nueva pagina, tambien puedes proporcionar parametros de consulta.

Veamos un ejemplo.

```dart
  // Pagina Home
  routeTo(ProfilePage.path, queryParameters: {"user": "7"});
  // navegar a la pagina de perfil

  ...

  // Pagina Profile
  @override
  get init => () {
    print(widget.queryParameters()); // {"user": 7}
    // o
    print(queryParameters()); // {"user": 7}
  };
```

> **Nota:** Siempre que tu widget de pagina extienda la clase `NyStatefulWidget` y `NyPage`, puedes llamar a `widget.queryParameters()` para obtener todos los parametros de consulta del nombre de la ruta.

```dart
// Pagina de ejemplo
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// Pagina Home
class MyHomePage extends NyStatefulWidget<HomeController> {
  ...
}

class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () {
    widget.queryParameters(); // {"hello": "World", "say": "I love code"}
    // o
    queryParameters(); // {"hello": "World", "say": "I love code"}
  };
```

> **Consejo:** Los parametros de consulta deben seguir el protocolo HTTP, Ej. /account?userId=1&tab=2


<div id="page-transitions"></div>

## Transiciones de pagina

Puedes agregar transiciones cuando navegas de una pagina modificando tu archivo `router.dart`.

``` dart
import 'package:page_transition/page_transition.dart';

appRouter() => nyRoutes((router) {

  // bottomToTop
  router.add(SettingsPage.path,
    transitionType: TransitionType.bottomToTop()
  );

  // fade
  router.add(HomePage.path,
    transitionType: TransitionType.fade()
  );

});
```

### Transiciones de pagina disponibles

#### Transiciones basicas
- **`TransitionType.fade()`** - Desvanece la nueva pagina mientras desvanece la anterior
- **`TransitionType.theme()`** - Usa el tema de transiciones de pagina de la aplicacion

#### Transiciones de deslizamiento direccional
- **`TransitionType.rightToLeft()`** - Desliza desde el borde derecho de la pantalla
- **`TransitionType.leftToRight()`** - Desliza desde el borde izquierdo de la pantalla
- **`TransitionType.topToBottom()`** - Desliza desde el borde superior de la pantalla
- **`TransitionType.bottomToTop()`** - Desliza desde el borde inferior de la pantalla

#### Transiciones de deslizamiento con desvanecimiento
- **`TransitionType.rightToLeftWithFade()`** - Desliza y desvanece desde el borde derecho
- **`TransitionType.leftToRightWithFade()`** - Desliza y desvanece desde el borde izquierdo

#### Transiciones de transformacion
- **`TransitionType.scale(alignment: ...)`** - Escala desde el punto de alineacion especificado
- **`TransitionType.rotate(alignment: ...)`** - Rota alrededor del punto de alineacion especificado
- **`TransitionType.size(alignment: ...)`** - Crece desde el punto de alineacion especificado

#### Transiciones unidas (requiere widget actual)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - La pagina actual sale a la derecha mientras la nueva entra desde la izquierda
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - La pagina actual sale a la izquierda mientras la nueva entra desde la derecha
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - La pagina actual sale hacia abajo mientras la nueva entra desde arriba
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - La pagina actual sale hacia arriba mientras la nueva entra desde abajo

#### Transiciones Pop (requiere widget actual)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - La pagina actual sale a la derecha, la nueva permanece en su lugar
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - La pagina actual sale a la izquierda, la nueva permanece en su lugar
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - La pagina actual sale hacia abajo, la nueva permanece en su lugar
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - La pagina actual sale hacia arriba, la nueva permanece en su lugar

#### Transiciones Material Design de eje compartido
- **`TransitionType.sharedAxisHorizontal()`** - Transicion horizontal de deslizamiento y desvanecimiento
- **`TransitionType.sharedAxisVertical()`** - Transicion vertical de deslizamiento y desvanecimiento
- **`TransitionType.sharedAxisScale()`** - Transicion de escala y desvanecimiento

#### Parametros de personalizacion
Cada transicion acepta los siguientes parametros opcionales:

| Parametro | Descripcion | Por defecto |
|-----------|-------------|-------------|
| `curve` | Curva de animacion | Curvas especificas de la plataforma |
| `duration` | Duracion de la animacion | Duraciones especificas de la plataforma |
| `reverseDuration` | Duracion de la animacion inversa | Igual que duration |
| `fullscreenDialog` | Si la ruta es un dialogo de pantalla completa | `false` |
| `opaque` | Si la ruta es opaca | `false` |


``` dart
// Widget de la pagina Home
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(ProfilePage.path,
      transitionType: TransitionType.bottomToTop()
    );
  }
...
```


<div id="navigation-types"></div>

## Tipos de navegacion

Al navegar, puedes especificar uno de los siguientes si estas usando el helper `routeTo`.

| Tipo | Descripcion |
|------|-------------|
| `NavigationType.push` | Agregar una nueva pagina a la pila de rutas de tu aplicacion |
| `NavigationType.pushReplace` | Reemplazar la ruta actual, eliminando la ruta anterior una vez que la nueva ruta termine |
| `NavigationType.popAndPushNamed` | Sacar la ruta actual del navegador y agregar una ruta nombrada en su lugar |
| `NavigationType.pushAndRemoveUntil` | Agregar y eliminar rutas hasta que el predicado devuelva true |
| `NavigationType.pushAndForgetAll` | Navegar a una nueva pagina y eliminar cualquier otra pagina en la pila de rutas |

``` dart
// Widget de la pagina Home
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(
      ProfilePage.path,
      navigationType: NavigationType.pushReplace
    );
  }
...
```


<div id="navigating-back"></div>

## Navegar hacia atras

Una vez que estes en la nueva pagina, puedes usar el helper `pop()` para volver a la pagina existente.

``` dart
// Widget SettingsPage
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop();
    // o
    Navigator.pop(context);
  }
...
```

Si deseas devolver un valor al widget anterior, proporciona un `result` como en el ejemplo a continuacion.

``` dart
// Widget SettingsPage
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// Obtener el valor del widget anterior usando el parametro `onPop`
// Widget HomePage
class _HomePageState extends NyPage<HomePage> {

  _viewSettings() {
    routeTo(SettingsPage.path, onPop: (value) {
      print(value); // {"status": "COMPLETE"}
    });
  }
...

```

<div id="conditional-navigation"></div>

## Navegacion condicional

Usa `routeIf()` para navegar solo cuando se cumple una condicion:

``` dart
// Solo navegar si el usuario ha iniciado sesion
routeIf(isLoggedIn, DashboardPage.path);

// Con opciones adicionales
routeIf(
  hasPermission('view_reports'),
  ReportsPage.path,
  data: {'filters': defaultFilters},
  navigationType: NavigationType.push,
);
```

Si la condicion es `false`, no ocurre navegacion.


<div id="route-history"></div>

## Historial de rutas

En {{ config('app.name') }}, puedes acceder a la informacion del historial de rutas usando los siguientes helpers.

``` dart
// Obtener historial de rutas
Nylo.getRouteHistory(); // List<dynamic>

// Obtener la ruta actual
Nylo.getCurrentRoute(); // Route<dynamic>?

// Obtener la ruta anterior
Nylo.getPreviousRoute(); // Route<dynamic>?

// Obtener el nombre de la ruta actual
Nylo.getCurrentRouteName(); // String?

// Obtener el nombre de la ruta anterior
Nylo.getPreviousRouteName(); // String?

// Obtener los argumentos de la ruta actual
Nylo.getCurrentRouteArguments(); // dynamic

// Obtener los argumentos de la ruta anterior
Nylo.getPreviousRouteArguments(); // dynamic
```


<div id="update-route-stack"></div>

## Actualizar pila de rutas

Puedes actualizar la pila de navegacion programaticamente usando `NyNavigator.updateStack()`:

``` dart
// Actualizar la pila con una lista de rutas
NyNavigator.updateStack([
  HomePage.path,
  SettingsPage.path,
  ProfilePage.path,
], replace: true);

// Pasar datos a rutas especificas
NyNavigator.updateStack([
  HomePage.path,
  ProfilePage.path,
],
  replace: true,
  dataForRoute: {
    ProfilePage.path: {"userId": 42}
  }
);
```

| Parametro | Tipo | Por defecto | Descripcion |
|-----------|------|-------------|-------------|
| `routes` | `List<String>` | requerido | Lista de rutas a navegar |
| `replace` | `bool` | `true` | Si reemplazar la pila actual |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | Datos a pasar a rutas especificas |

Esto es util para:
- Escenarios de deep linking
- Restaurar el estado de navegacion
- Construir flujos de navegacion complejos


<div id="deep-linking"></div>

## Deep Linking

El deep linking permite a los usuarios navegar directamente a contenido especifico dentro de tu aplicacion usando URLs. Esto es util para:
- Compartir enlaces directos a contenido especifico de la aplicacion
- Campanas de marketing que apuntan a funciones especificas dentro de la aplicacion
- Manejar notificaciones que deben abrir pantallas especificas de la aplicacion
- Transiciones fluidas de web a aplicacion

## Configuracion

Antes de implementar deep linking en tu aplicacion, asegurate de que tu proyecto este correctamente configurado:

### 1. Configuracion de plataforma

**iOS**: Configura universal links en tu proyecto Xcode
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Guia de configuracion de Universal Links de Flutter</a>

**Android**: Configura app links en tu AndroidManifest.xml
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Guia de configuracion de App Links de Flutter</a>

### 2. Definir tus rutas

Todas las rutas que deben ser accesibles via deep links deben estar registradas en la configuracion de tu router:

```dart
// Archivo: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  // Rutas basicas
  router.add(HomePage.path).initialRoute();
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);

  // Ruta con parametros
  router.add(HotelBookingPage.path);
});
```

## Usar Deep Links

Una vez configurado, tu aplicacion puede manejar URLs entrantes en varios formatos:

### Deep Links basicos

Navegacion simple a paginas especificas:

``` bash
https://yourdomain.com/profile       // Abre la pagina de perfil
https://yourdomain.com/settings      // Abre la pagina de configuracion
```

Para activar estas navegaciones programaticamente dentro de tu aplicacion:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### Parametros de ruta

Para rutas que requieren datos dinamicos como parte de la ruta:

#### Definicion de ruta

```dart
class HotelBookingPage extends NyStatefulWidget {
  // Definir una ruta con un marcador de posicion de parametro {id}
  static RouteView path = ("/hotel/{id}/booking", (_) => HotelBookingPage());

  HotelBookingPage({super.key}) : super(child: () => _HotelBookingPageState());
}

class _HotelBookingPageState extends NyPage<HotelBookingPage> {
  @override
  get init => () {
    // Acceder al parametro de ruta
    final hotelId = queryParameters()["id"]; // Devuelve "87" para URL ../hotel/87/booking
    print("Loading hotel ID: $hotelId");

    // Usar el ID para obtener datos del hotel o realizar operaciones
  };

  // Resto de la implementacion de tu pagina
}
```

#### Formato de URL

``` bash
https://yourdomain.com/hotel/87/booking
```

#### Navegacion programatica

```dart
// Navegar con parametros
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### Parametros de consulta

Para parametros opcionales o cuando se necesitan multiples valores dinamicos:

#### Formato de URL

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### Acceder a parametros de consulta

```dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  get init => () {
    // Obtener todos los parametros de consulta
    final params = queryParameters();

    // Acceder a parametros especificos
    final userId = params["user"];            // "20"
    final activeTab = params["tab"];          // "posts"

    // Metodo de acceso alternativo
    final params2 = widget.queryParameters();
    print(params2);                           // {"user": "20", "tab": "posts"}
  };
}
```

#### Navegacion programatica con parametros de consulta

```dart
// Navegar con parametros de consulta
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Combinar parametros de ruta y de consulta
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## Manejar Deep Links

Puedes manejar eventos de deep link en tu `RouteProvider`:

```dart
class RouteProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.addRouter(appRouter());

    // Manejar deep links
    nylo.onDeepLink(_onDeepLink);
    return nylo;
  }

  _onDeepLink(String route, Map<String, String>? data) {
    print("Deep link route: $route");
    print("Deep link data: $data");

    // Actualizar la pila de rutas para deep links
    if (route == ProfilePage.path) {
      NyNavigator.updateStack([
        HomePage.path,
        ProfilePage.path,
      ], replace: true, dataForRoute: {
        ProfilePage.path: data,
      });
    }
  }

  @override
  boot(Nylo nylo) async {
    nylo.initRoutes();
  }
}
```

### Probar Deep Links

Para desarrollo y pruebas, puedes simular la activacion de deep links usando ADB (Android) o xcrun (iOS):

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### Consejos de depuracion

- Imprime todos los parametros en tu metodo init para verificar el parseo correcto
- Prueba diferentes formatos de URL para asegurarte de que tu aplicacion los maneje correctamente
- Recuerda que los parametros de consulta siempre se reciben como strings, conviertelos al tipo apropiado segun sea necesario

---

## Patrones comunes

### Conversion de tipo de parametro

Ya que todos los parametros de URL se pasan como strings, a menudo necesitaras convertirlos:

```dart
// Convertir parametros de string a tipos apropiados
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### Parametros opcionales

Manejar casos donde los parametros podrian estar ausentes:

```dart
final userId = queryParameters()["user"];
if (userId != null) {
  // Cargar perfil de usuario especifico
} else {
  // Cargar perfil del usuario actual
}

// O verificar hasQueryParameter
if (hasQueryParameter('status')) {
  // Hacer algo con el parametro de estado
} else {
  // Manejar la ausencia del parametro
}
```


<div id="advanced"></div>

## Avanzado

### Verificar si una ruta existe

Puedes verificar si una ruta esta registrada en tu router:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### Metodos de NyRouter

La clase `NyRouter` proporciona varios metodos utiles:

| Metodo | Descripcion |
|--------|-------------|
| `getRegisteredRouteNames()` | Obtener todos los nombres de rutas registrados como una lista |
| `getRegisteredRoutes()` | Obtener todas las rutas registradas como un mapa |
| `containsRoutes(routes)` | Verificar si el router contiene todas las rutas especificadas |
| `getInitialRouteName()` | Obtener el nombre de la ruta inicial |
| `getAuthRouteName()` | Obtener el nombre de la ruta autenticada |
| `getUnknownRouteName()` | Obtener el nombre de la ruta desconocida/404 |

### Obtener argumentos de ruta

Puedes obtener argumentos de ruta usando `NyRouter.args<T>()`:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    // Obtener argumentos tipados
    final args = NyRouter.args<NyArgument>(context);
    final userData = args?.data;

    return Scaffold(...);
  }
}
```

### NyArgument y NyQueryParameters

Los datos pasados entre rutas se envuelven en estas clases:

``` dart
// NyArgument contiene datos de ruta
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters contiene parametros de consulta de URL
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
