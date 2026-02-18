# Authentication

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion a la autenticacion")
- Conceptos basicos
  - [Autenticar usuarios](#authenticating-users "Autenticar usuarios")
  - [Obtener datos de autenticacion](#retrieving-auth-data "Obtener datos de autenticacion")
  - [Actualizar datos de autenticacion](#updating-auth-data "Actualizar datos de autenticacion")
  - [Cerrar sesion](#logging-out "Cerrar sesion")
  - [Verificar autenticacion](#checking-authentication "Verificar autenticacion")
- Avanzado
  - [Sesiones multiples](#multiple-sessions "Sesiones multiples")
  - [ID de dispositivo](#device-id "ID de dispositivo")
  - [Sincronizar con Backpack](#syncing-to-backpack "Sincronizar con Backpack")
- Configuracion de rutas
  - [Ruta inicial](#initial-route "Ruta inicial")
  - [Ruta autenticada](#authenticated-route "Ruta autenticada")
  - [Ruta de vista previa](#preview-route "Ruta de vista previa")
  - [Ruta desconocida](#unknown-route "Ruta desconocida")
- [Funciones auxiliares](#helper-functions "Funciones auxiliares")

<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} v7 proporciona un sistema de autenticacion completo a traves de la clase `Auth`. Gestiona el almacenamiento seguro de credenciales de usuario, la gestion de sesiones y soporta multiples sesiones con nombre para diferentes contextos de autenticacion.

Los datos de autenticacion se almacenan de forma segura y se sincronizan con Backpack (un almacen clave-valor en memoria) para un acceso rapido y sincrono en toda tu aplicacion.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Authenticate a user
await Auth.authenticate(data: {"token": "abc123", "userId": 1});

// Check if authenticated
bool loggedIn = await Auth.isAuthenticated(); // true

// Get auth data
dynamic token = Auth.data(field: 'token'); // "abc123"

// Logout
await Auth.logout();
```


<div id="authenticating-users"></div>

## Autenticar usuarios

Usa `Auth.authenticate()` para almacenar los datos de sesion del usuario:

``` dart
// With a Map
await Auth.authenticate(data: {
  "token": "ey2sdm...",
  "userId": 123,
  "email": "user@example.com",
});

// With a Model class
User user = User(id: 123, email: "user@example.com", token: "ey2sdm...");
await Auth.authenticate(data: user);

// Without data (stores a timestamp)
await Auth.authenticate();
```

### Ejemplo del mundo real

``` dart
class _LoginPageState extends NyPage<LoginPage> {

  Future<void> handleLogin(String email, String password) async {
    // 1. Call your API to authenticate
    User? user = await api<AuthApiService>((request) => request.login(
      email: email,
      password: password,
    ));

    if (user == null) {
      showToastDanger(description: "Invalid credentials");
      return;
    }

    // 2. Store the authenticated user
    await Auth.authenticate(data: user);

    // 3. Navigate to home
    routeTo(HomePage.path, removeUntil: (_) => false);
  }
}
```


<div id="retrieving-auth-data"></div>

## Obtener datos de autenticacion

Obtiene los datos de autenticacion almacenados usando `Auth.data()`:

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

El metodo `Auth.data()` lee desde Backpack (el almacen clave-valor en memoria de {{ config('app.name') }}) para un acceso sincrono rapido. Los datos se sincronizan automaticamente con Backpack cuando te autentificas.


<div id="updating-auth-data"></div>

## Actualizar datos de autenticacion

{{ config('app.name') }} v7 introduce `Auth.set()` para actualizar los datos de autenticacion:

``` dart
// Update a specific field
await Auth.set((data) {
  data['token'] = 'new_token_value';
  return data;
});

// Add new fields
await Auth.set((data) {
  data['refreshToken'] = 'refresh_abc123';
  data['lastLogin'] = DateTime.now().toIso8601String();
  return data;
});

// Replace entire data
await Auth.set((data) => {
  'token': newToken,
  'userId': userId,
});
```


<div id="logging-out"></div>

## Cerrar sesion

Elimina al usuario autenticado con `Auth.logout()`:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### Cerrar sesion en todas las sesiones

Cuando usas multiples sesiones, limpia todas:

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## Verificar autenticacion

Verifica si un usuario esta actualmente autenticado:

``` dart
bool isLoggedIn = await Auth.isAuthenticated();

if (isLoggedIn) {
  // User is authenticated
  routeTo(HomePage.path);
} else {
  // User needs to login
  routeTo(LoginPage.path);
}
```


<div id="multiple-sessions"></div>

## Sesiones multiples

{{ config('app.name') }} v7 soporta multiples sesiones de autenticacion con nombre para diferentes contextos. Esto es util cuando necesitas rastrear diferentes tipos de autenticacion por separado (por ejemplo, inicio de sesion de usuario vs registro de dispositivo vs acceso de administrador).

``` dart
// Default user session
await Auth.authenticate(data: user);

// Device authentication session
await Auth.authenticate(
  data: {"deviceToken": "abc123"},
  session: 'device',
);

// Admin session
await Auth.authenticate(
  data: adminUser,
  session: 'admin',
);
```

### Leer desde sesiones con nombre

``` dart
// Default session
dynamic userData = Auth.data();
String? userToken = Auth.data(field: 'token');

// Device session
dynamic deviceData = Auth.data(session: 'device');
String? deviceToken = Auth.data(field: 'deviceToken', session: 'device');

// Admin session
dynamic adminData = Auth.data(session: 'admin');
```

### Cierre de sesion por sesion especifica

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### Verificar autenticacion por sesion

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## ID de dispositivo

{{ config('app.name') }} v7 proporciona un identificador unico de dispositivo que persiste entre sesiones de la aplicacion:

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

El ID de dispositivo:
- Se genera una vez y se almacena permanentemente
- Es unico para cada dispositivo/instalacion
- Es util para registro de dispositivos, analiticas o notificaciones push

``` dart
// Example: Register device with backend
Future<void> registerDevice() async {
  String deviceId = await Auth.deviceId();
  String? pushToken = await FirebaseMessaging.instance.getToken();

  await api<DeviceApiService>((request) => request.register(
    deviceId: deviceId,
    pushToken: pushToken,
  ));

  // Store device auth
  await Auth.authenticate(
    data: {"deviceId": deviceId, "pushToken": pushToken},
    session: 'device',
  );
}
```


<div id="syncing-to-backpack"></div>

## Sincronizar con Backpack

Los datos de autenticacion se sincronizan automaticamente con Backpack cuando te autentificas. Para sincronizar manualmente (por ejemplo, al iniciar la app):

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

Esto es util en la secuencia de inicio de tu aplicacion para asegurar que los datos de autenticacion esten disponibles en Backpack para un acceso sincrono rapido.


<div id="initial-route"></div>

## Ruta inicial

La ruta inicial es la primera pagina que los usuarios ven cuando abren tu aplicacion. Configurala usando `.initialRoute()` en tu router:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

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

Navega de vuelta a la ruta inicial desde cualquier lugar usando `routeToInitial()`:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## Ruta autenticada

La ruta autenticada reemplaza la ruta inicial cuando un usuario ha iniciado sesion. Configurala usando `.authenticatedRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

Cuando la aplicacion se inicia:
- `Auth.isAuthenticated()` retorna `true` → el usuario ve la **ruta autenticada** (HomePage)
- `Auth.isAuthenticated()` retorna `false` → el usuario ve la **ruta inicial** (LoginPage)

Tambien puedes establecer una ruta autenticada condicional:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

Navega a la ruta autenticada programaticamente usando `routeToAuthenticatedRoute()`:

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**Ver tambien:** [Router](/docs/{{ $version }}/router) para la documentacion completa de enrutamiento incluyendo guards y deep linking.


<div id="preview-route"></div>

## Ruta de vista previa

Durante el desarrollo, puede que quieras previsualizar rapidamente una pagina especifica sin cambiar tu ruta inicial o autenticada. Usa `.previewRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()` reemplaza **tanto** `initialRoute()` como `authenticatedRoute()`, haciendo que la ruta especificada sea la primera pagina mostrada independientemente del estado de autenticacion.

> **Advertencia:** Elimina `.previewRoute()` antes de publicar tu aplicacion.


<div id="unknown-route"></div>

## Ruta desconocida

Define una pagina de respaldo para cuando un usuario navega a una ruta que no existe. Configurala usando `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### Integracion completa

Aqui tienes una configuracion completa del router con todos los tipos de ruta:

``` dart
appRouter() => nyRoutes((router) {
  // First page for unauthenticated users
  router.add(LoginPage.path).initialRoute();

  // First page for authenticated users
  router.add(HomePage.path).authenticatedRoute();

  // 404 page
  router.add(NotFoundPage.path).unknownRoute();

  // Regular routes
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

| Metodo de ruta | Proposito |
|--------------|---------|
| `.initialRoute()` | Primera pagina mostrada a usuarios no autenticados |
| `.authenticatedRoute()` | Primera pagina mostrada a usuarios autenticados |
| `.previewRoute()` | Reemplaza ambas durante el desarrollo |
| `.unknownRoute()` | Se muestra cuando no se encuentra una ruta |


<div id="helper-functions"></div>

## Funciones auxiliares

{{ config('app.name') }} v7 proporciona funciones auxiliares que reflejan los metodos de la clase `Auth`:

| Funcion auxiliar | Equivalente |
|-----------------|------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | Clave de almacenamiento para la sesion predeterminada |
| `authDeviceId()` | `Auth.deviceId()` |

Todas las funciones auxiliares aceptan los mismos parametros que sus equivalentes de la clase `Auth`, incluyendo el parametro opcional `session`:

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```
