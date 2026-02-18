# Testing

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Primeros pasos](#getting-started "Primeros pasos")
- [Escribir pruebas](#writing-tests "Escribir pruebas")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [Utilidades de prueba de widgets](#widget-testing-utilities "Utilidades de prueba de widgets")
  - [nyGroup](#ny-group "nyGroup")
  - [Ciclo de vida de pruebas](#test-lifecycle "Ciclo de vida de pruebas")
  - [Omitir pruebas y pruebas CI](#skipping-tests "Omitir pruebas y pruebas CI")
- [Autenticacion](#authentication "Autenticacion")
- [Viaje en el tiempo](#time-travel "Viaje en el tiempo")
- [Simulacion de API](#api-mocking "Simulacion de API")
  - [Simulacion por patron de URL](#mocking-by-url "Simulacion por patron de URL")
  - [Simulacion por tipo de servicio API](#mocking-by-type "Simulacion por tipo de servicio API")
  - [Historial de llamadas y aserciones](#call-history "Historial de llamadas y aserciones")
- [Factories](#factories "Factories")
  - [Definir factories](#defining-factories "Definir factories")
  - [Estados de factory](#factory-states "Estados de factory")
  - [Crear instancias](#creating-instances "Crear instancias")
- [NyFaker](#ny-faker "NyFaker")
- [Cache de pruebas](#test-cache "Cache de pruebas")
- [Simulacion de canales de plataforma](#platform-channel-mocking "Simulacion de canales de plataforma")
- [Simulacion de Route Guard](#route-guard-mocking "Simulacion de Route Guard")
- [Aserciones](#assertions "Aserciones")
- [Matchers personalizados](#custom-matchers "Matchers personalizados")
- [Pruebas de estado](#state-testing "Pruebas de estado")
- [Depuracion](#debugging "Depuracion")
- [Ejemplos](#examples "Ejemplos practicos")

<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} v7 incluye un framework de pruebas completo inspirado en las utilidades de prueba de Laravel. Proporciona:

- **Funciones de prueba** con setup/teardown automatico (`nyTest`, `nyWidgetTest`, `nyGroup`)
- **Simulacion de autenticacion** a traves de `NyTest.actingAs<T>()`
- **Viaje en el tiempo** para congelar o manipular el tiempo en pruebas
- **Simulacion de API** con coincidencia de patrones de URL y seguimiento de llamadas
- **Factories** con un generador de datos falsos integrado (`NyFaker`)
- **Simulacion de canales de plataforma** para almacenamiento seguro, proveedor de rutas y mas
- **Aserciones personalizadas** para rutas, Backpack, autenticacion y entorno

<div id="getting-started"></div>

## Primeros pasos

Inicializa el framework de pruebas al inicio de tu archivo de pruebas:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` configura el entorno de pruebas y habilita el reinicio automatico de estado entre pruebas cuando `autoReset: true` (el valor por defecto).

<div id="writing-tests"></div>

## Escribir pruebas

<div id="ny-test"></div>

### nyTest

La funcion principal para escribir pruebas:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

Opciones:

``` dart
nyTest('my test', () async {
  // test body
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

Para probar widgets de Flutter con un `WidgetTester`:

``` dart
nyWidgetTest('renders a button', (WidgetTester tester) async {
  await tester.pumpWidget(MaterialApp(
    home: Scaffold(
      body: ElevatedButton(
        onPressed: () {},
        child: Text("Tap me"),
      ),
    ),
  ));

  expect(find.text("Tap me"), findsOneWidget);
});
```

<div id="widget-testing-utilities"></div>

### Utilidades de prueba de widgets

La clase `NyWidgetTest` y las extensiones de `WidgetTester` proporcionan helpers para renderizar widgets de Nylo con soporte de temas adecuado, esperar a que `init()` se complete y probar estados de carga.

#### Configurar el entorno de pruebas

Llama a `NyWidgetTest.configure()` en tu `setUpAll` para deshabilitar la descarga de Google Fonts y opcionalmente establecer un tema personalizado:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

Puedes reiniciar la configuracion con `NyWidgetTest.reset()`.

Dos temas integrados estan disponibles para pruebas sin fuentes:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Renderizar widgets de Nylo

Usa `pumpNyWidget` para envolver un widget en un `MaterialApp` con soporte de temas:

``` dart
nyWidgetTest('renders page', (tester) async {
  await tester.pumpNyWidget(
    HomePage(),
    theme: ThemeData.light(),
    darkTheme: ThemeData.dark(),
    themeMode: ThemeMode.light,
    settleTimeout: Duration(seconds: 5),
    useSimpleTheme: false,
  );

  expect(find.text('Welcome'), findsOneWidget);
});
```

Para un renderizado rapido con un tema sin fuentes:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Esperar Init

`pumpNyWidgetAndWaitForInit` renderiza frames hasta que los indicadores de carga desaparezcan (o se alcance el tiempo limite), lo cual es util para paginas con metodos `init()` asincronos:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() ha completado
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Helpers de renderizado

``` dart
// Renderizar frames hasta que un widget especifico aparezca
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Estabilizar graciosamente (no lanzara excepcion por tiempo limite)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Simulacion de ciclo de vida

Simular cambios de `AppLifecycleState` en cualquier `NyPage` del arbol de widgets:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Verificar efectos secundarios de la accion de ciclo de vida pausado
```

#### Verificaciones de carga y bloqueo

Verificar claves de carga nombradas y bloqueos en widgets `NyPage`/`NyState`:

``` dart
// Verificar si una clave de carga nombrada esta activa
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Verificar si un bloqueo nombrado esta retenido
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Verificar cualquier indicador de carga (CircularProgressIndicator o Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### Helper testNyPage

Una funcion de conveniencia que renderiza un `NyPage`, espera init y luego ejecuta tus expectativas:

``` dart
testNyPage(
  'HomePage loads correctly',
  build: () => HomePage(),
  expectations: (tester) async {
    expect(find.text('Welcome'), findsOneWidget);
  },
  useSimpleTheme: true,
  initTimeout: Duration(seconds: 10),
  skip: false,
);
```

#### Helper testNyPageLoading

Probar que una pagina muestra un indicador de carga durante `init()`:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

Un mixin que proporciona utilidades comunes de prueba de paginas:

``` dart
class HomePageTest with NyPageTestMixin {
  void runTests(WidgetTester tester) async {
    // Verificar que init fue llamado y la carga se completo
    await verifyInitCalled(tester, HomePage(), timeout: Duration(seconds: 5));

    // Verificar que el estado de carga se muestra durante init
    await verifyLoadingState(tester, HomePage());
  }
}
```

<div id="ny-group"></div>

### nyGroup

Agrupar pruebas relacionadas:

``` dart
nyGroup('Authentication', () {
  nyTest('can login', () async {
    NyTest.actingAs<User>(User(name: "Anthony"));
    expectAuthenticated<User>();
  });

  nyTest('can logout', () async {
    NyTest.actingAs<User>(User(name: "Anthony"));
    NyTest.logout();
    expectGuest();
  });
});
```

<div id="test-lifecycle"></div>

### Ciclo de vida de pruebas

Configurar logica de setup y teardown usando hooks de ciclo de vida:

``` dart
void main() {
  NyTest.init();

  nySetUpAll(() {
    // Se ejecuta una vez antes de todas las pruebas
  });

  nySetUp(() {
    // Se ejecuta antes de cada prueba
  });

  nyTearDown(() {
    // Se ejecuta despues de cada prueba
  });

  nyTearDownAll(() {
    // Se ejecuta una vez despues de todas las pruebas
  });
}
```

<div id="skipping-tests"></div>

### Omitir pruebas y pruebas CI

``` dart
// Omitir una prueba con una razon
nySkip('not implemented yet', () async {
  // ...
}, "Waiting for API update");

// Pruebas que se espera que fallen
nyFailing('known bug', () async {
  // ...
});

// Pruebas solo para CI (etiquetadas con 'ci')
nyCi('integration test', () async {
  // Solo se ejecuta en entornos CI
});
```

<div id="authentication"></div>

## Autenticacion

Simular usuarios autenticados en pruebas:

``` dart
nyTest('user can access profile', () async {
  // Simular un usuario autenticado
  NyTest.actingAs<User>(User(name: "Anthony", email: "anthony@example.com"));

  // Verificar autenticacion
  expectAuthenticated<User>();

  // Acceder al usuario actuante
  User? user = NyTest.actingUser<User>();
  expect(user?.name, equals("Anthony"));
});

nyTest('guest cannot access profile', () async {
  // Verificar que no esta autenticado
  expectGuest();
});
```

Cerrar sesion del usuario:

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## Viaje en el tiempo

Manipular el tiempo en tus pruebas usando `NyTime`:

### Saltar a una fecha especifica

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Reset to real time
});
```

### Avanzar o retroceder el tiempo

``` dart
NyTest.travelForward(Duration(days: 30)); // Jump 30 days ahead
NyTest.travelBackward(Duration(hours: 2)); // Go back 2 hours
```

### Congelar el tiempo

``` dart
NyTest.freezeTime(); // Freeze at the current moment

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Time hasn't moved

NyTest.travelBack(); // Unfreeze
```

### Limites de tiempo

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1st of current month
NyTime.travelToEndOfMonth();   // Last day of current month
NyTime.travelToStartOfYear();  // Jan 1st
NyTime.travelToEndOfYear();    // Dec 31st
```

### Viaje en el tiempo con alcance

Ejecutar codigo dentro de un contexto de tiempo congelado:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// El tiempo se restaura automaticamente despues del callback
```

<div id="api-mocking"></div>

## Simulacion de API

<div id="mocking-by-url"></div>

### Simulacion por patron de URL

Simular respuestas de API usando patrones de URL con soporte de comodines:

``` dart
nyTest('mock API responses', () async {
  // Exact URL match
  NyMockApi.respond('/users/1', {'id': 1, 'name': 'Anthony'});

  // Single segment wildcard (*)
  NyMockApi.respond('/users/*', {'id': 1, 'name': 'User'});

  // Multi-segment wildcard (**)
  NyMockApi.respond('/api/**', {'status': 'ok'});

  // With status code and headers
  NyMockApi.respond(
    '/users',
    {'error': 'Unauthorized'},
    statusCode: 401,
    method: 'POST',
    headers: {'X-Error': 'true'},
  );

  // With simulated delay
  NyMockApi.respond(
    '/slow-endpoint',
    {'data': 'loaded'},
    delay: Duration(seconds: 2),
  );
});
```

<div id="mocking-by-type"></div>

### Simulacion por tipo de servicio API

Simular un servicio API completo por tipo:

``` dart
nyTest('mock API service', () async {
  NyMockApi.register<UserApiService>((MockApiRequest request) async {
    if (request.endpoint.contains('/users')) {
      return {'users': [{'id': 1, 'name': 'Anthony'}]};
    }
    return {'error': 'not found'};
  });
});
```

<div id="call-history"></div>

### Historial de llamadas y aserciones

Rastrear y verificar llamadas a la API:

``` dart
nyTest('verify API was called', () async {
  NyMockApi.setRecordCalls(true);

  // ... perform actions that trigger API calls ...

  // Assert endpoint was called
  expectApiCalled('/users');

  // Assert endpoint was not called
  expectApiNotCalled('/admin');

  // Assert call count
  expectApiCalled('/users', times: 2);

  // Assert specific method
  expectApiCalled('/users', method: 'POST');

  // Get call details
  List<ApiCallInfo> calls = NyMockApi.getCallsFor('/users');
});
```

### Crear respuestas simuladas

``` dart
Response<Map<String, dynamic>> response = NyMockApi.createResponse(
  data: {'id': 1, 'name': 'Anthony'},
  statusCode: 200,
  statusMessage: 'OK',
);
```

<div id="factories"></div>

## Factories

<div id="defining-factories"></div>

### Definir factories

Define como crear instancias de prueba de tus modelos:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

Con soporte de sobreescritura:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### Estados de factory

Define variaciones de un factory:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### Crear instancias

``` dart
// Create a single instance
User user = NyFactory.make<User>();

// Create with overrides
User admin = NyFactory.make<User>(overrides: {'name': 'Admin User'});

// Create with states applied
User premiumAdmin = NyFactory.make<User>(states: ['admin', 'premium']);

// Create multiple instances
List<User> users = NyFactory.create<User>(count: 5);

// Create a sequence with index-based data
List<User> numbered = NyFactory.sequence<User>(3, (int index, NyFaker faker) {
  return User(name: "User ${index + 1}", email: faker.email());
});
```

<div id="ny-faker"></div>

## NyFaker

`NyFaker` genera datos falsos realistas para pruebas. Esta disponible dentro de las definiciones de factory y puede ser instanciado directamente.

``` dart
NyFaker faker = NyFaker();
```

### Metodos disponibles

| Categoria | Metodo | Tipo de retorno | Descripcion |
|-----------|--------|-----------------|-------------|
| **Nombres** | `faker.firstName()` | `String` | Nombre aleatorio |
| | `faker.lastName()` | `String` | Apellido aleatorio |
| | `faker.name()` | `String` | Nombre completo (nombre + apellido) |
| | `faker.username()` | `String` | Cadena de nombre de usuario |
| **Contacto** | `faker.email()` | `String` | Direccion de correo electronico |
| | `faker.phone()` | `String` | Numero de telefono |
| | `faker.company()` | `String` | Nombre de empresa |
| **Numeros** | `faker.randomInt(min, max)` | `int` | Entero aleatorio en rango |
| | `faker.randomDouble(min, max)` | `double` | Double aleatorio en rango |
| | `faker.randomBool()` | `bool` | Booleano aleatorio |
| **Identificadores** | `faker.uuid()` | `String` | Cadena UUID v4 |
| **Fechas** | `faker.date()` | `DateTime` | Fecha aleatoria |
| | `faker.pastDate()` | `DateTime` | Fecha en el pasado |
| | `faker.futureDate()` | `DateTime` | Fecha en el futuro |
| **Texto** | `faker.lorem()` | `String` | Palabras lorem ipsum |
| | `faker.sentences()` | `String` | Multiples oraciones |
| | `faker.paragraphs()` | `String` | Multiples parrafos |
| | `faker.slug()` | `String` | Slug de URL |
| **Web** | `faker.url()` | `String` | Cadena de URL |
| | `faker.imageUrl()` | `String` | URL de imagen (via picsum.photos) |
| | `faker.ipAddress()` | `String` | Direccion IPv4 |
| | `faker.macAddress()` | `String` | Direccion MAC |
| **Ubicacion** | `faker.address()` | `String` | Direccion postal |
| | `faker.city()` | `String` | Nombre de ciudad |
| | `faker.state()` | `String` | Abreviatura de estado de EE.UU. |
| | `faker.zipCode()` | `String` | Codigo postal |
| | `faker.country()` | `String` | Nombre de pais |
| **Otros** | `faker.hexColor()` | `String` | Codigo de color hexadecimal |
| | `faker.creditCardNumber()` | `String` | Numero de tarjeta de credito |
| | `faker.randomElement(list)` | `T` | Elemento aleatorio de una lista |
| | `faker.randomElements(list, count)` | `List<T>` | Elementos aleatorios de una lista |

<div id="test-cache"></div>

## Cache de pruebas

`NyTestCache` proporciona un cache en memoria para probar funcionalidad relacionada con cache:

``` dart
nyTest('cache operations', () async {
  NyTestCache cache = NyTest.cache;

  // Store a value
  await cache.put<String>("key", "value");

  // Store with expiration
  await cache.put<String>("temp", "data", seconds: 60);

  // Read a value
  String? value = await cache.get<String>("key");

  // Check existence
  bool exists = await cache.has("key");

  // Clear a key
  await cache.clear("key");

  // Flush all
  await cache.flush();

  // Get cache info
  int size = await cache.size();
  List<String> keys = await cache.documents();
});
```

<div id="platform-channel-mocking"></div>

## Simulacion de canales de plataforma

`NyMockChannels` simula automaticamente los canales de plataforma comunes para que las pruebas no fallen:

``` dart
void main() {
  NyTest.init(); // Automatically sets up mock channels

  // Or set up manually
  NyMockChannels.setup();
}
```

### Canales simulados

- **path_provider** -- directorios de documentos, temporales, soporte de aplicacion, libreria y cache
- **flutter_secure_storage** -- almacenamiento seguro en memoria
- **flutter_timezone** -- datos de zona horaria
- **flutter_local_notifications** -- canal de notificaciones
- **sqflite** -- operaciones de base de datos

### Sobreescribir rutas

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### Almacenamiento seguro en pruebas

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## Simulacion de Route Guard

`NyMockRouteGuard` te permite probar el comportamiento de route guards sin autenticacion real ni llamadas de red. Extiende `NyRouteGuard` y proporciona constructores factory para escenarios comunes.

### Guard que siempre permite

``` dart
final guard = NyMockRouteGuard.pass();
```

### Guard que redirige

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// With additional data
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Guard con logica personalizada

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // abort navigation
  }
  return GuardResult.next; // allow navigation
});
```

### Rastreo de llamadas al guard

Despues de que un guard ha sido invocado puedes inspeccionar su estado:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Access the RouteContext from the last call
RouteContext? context = guard.lastContext;

// Reset tracking
guard.reset();
```

<div id="assertions"></div>

## Aserciones

{{ config('app.name') }} proporciona funciones de asercion personalizadas:

### Aserciones de rutas

``` dart
expectRoute('/home');           // Assert current route
expectNotRoute('/login');       // Assert not on route
expectRouteInHistory('/home');  // Assert route was visited
expectRouteExists('/profile');  // Assert route is registered
expectRoutesExist(['/home', '/profile', '/settings']);
```

### Aserciones de estado

``` dart
expectBackpackContains("key");                        // Key exists
expectBackpackContains("key", value: "expected");     // Key has value
expectBackpackNotContains("key");                     // Key doesn't exist
```

### Aserciones de autenticacion

``` dart
expectAuthenticated<User>();  // User is authenticated
expectGuest();                // No user authenticated
```

### Aserciones de entorno

``` dart
expectEnv("APP_NAME", "MyApp");  // Env variable equals value
expectEnvSet("APP_KEY");          // Env variable is set
```

### Aserciones de modo

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### Aserciones de API

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### Aserciones de idioma

``` dart
expectLocale("en");
```

### Aserciones de Toast

Verificar notificaciones toast que fueron registradas durante una prueba. Requiere `NyToastRecorder.setup()` en tu setUp de prueba:

``` dart
setUp(() {
  NyToastRecorder.setup();
});

nyWidgetTest('shows success toast', (tester) async {
  await tester.pumpNyWidget(MyPage());
  // ... trigger action that shows a toast ...

  expectToastShown(id: 'success');
  expectToastShown(id: 'danger', description: 'Something went wrong');
  expectNoToastShown(id: 'info');
});
```

**NyToastRecorder** rastrea notificaciones toast durante las pruebas:

``` dart
// Record a toast manually
NyToastRecorder.record(id: 'success', title: 'Done', description: 'Saved!');

// Check if a toast was shown
bool shown = NyToastRecorder.wasShown(id: 'success');

// Access all recorded toasts
List<ToastRecord> toasts = NyToastRecorder.records;

// Clear recorded toasts
NyToastRecorder.clear();
```

### Aserciones de bloqueo y carga

Verificar estados de bloqueo nombrado y carga en widgets `NyPage`/`NyState`:

``` dart
// Assert a named lock is held
expectLocked(tester, find.byType(MyPage), 'submit');

// Assert a named lock is not held
expectNotLocked(tester, find.byType(MyPage), 'submit');

// Assert a named loading key is active
expectLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');

// Assert a named loading key is not active
expectNotLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');
```

<div id="custom-matchers"></div>

## Matchers personalizados

Usa matchers personalizados con `expect()`:

``` dart
// Type matcher
expect(result, isType<User>());

// Route name matcher
expect(widget, hasRouteName('/home'));

// Backpack matcher
expect(true, backpackHas("key", value: "expected"));

// API call matcher
expect(true, apiWasCalled('/users', method: 'GET', times: 1));
```

<div id="state-testing"></div>

## Pruebas de estado

Prueba la gestion de estado basada en EventBus en widgets `NyPage` y `NyState` usando helpers de prueba de estado.

### Disparar actualizaciones de estado

Simular actualizaciones de estado que normalmente vendrian de otro widget o controlador:

``` dart
// Fire an UpdateState event
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### Disparar acciones de estado

Enviar acciones de estado que son manejadas por `whenStateAction()` en tu pagina:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// With additional data
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### Aserciones de estado

``` dart
// Assert a state update was fired
expectStateUpdated('HomePageState');
expectStateUpdated('HomePageState', times: 2);

// Assert a state action was fired
expectStateAction('HomePageState', 'refresh-page');
expectStateAction('CartState', 'add-item', times: 1);

// Assert on the stateData of a NyPage/NyState widget
expectStateData(tester, find.byType(MyWidget), equals(42));
```

### NyStateTestHelpers

Rastrear e inspeccionar actualizaciones de estado y acciones disparadas:

``` dart
// Get all updates fired to a state
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Get all actions fired to a state
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Reset all tracked state updates and actions
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## Depuracion

### dump

Imprimir el estado actual de la prueba (contenido de Backpack, usuario autenticado, tiempo, llamadas API, idioma):

``` dart
NyTest.dump();
```

### dd (Dump and Die)

Imprimir el estado de la prueba y terminar inmediatamente la prueba:

``` dart
NyTest.dd();
```

### Almacenamiento de estado de prueba

Almacenar y recuperar valores durante una prueba:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Seed Backpack

Pre-poblar Backpack con datos de prueba:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## Ejemplos

### Archivo de prueba completo

``` dart
import 'package:flutter_test/flutter_test.dart';
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyGroup('User Authentication', () {
    nyTest('can authenticate a user', () async {
      NyFactory.define<User>((faker) => User(
        name: faker.name(),
        email: faker.email(),
      ));

      User user = NyFactory.make<User>();
      NyTest.actingAs<User>(user);

      expectAuthenticated<User>();
    });

    nyTest('guest has no access', () async {
      expectGuest();
    });
  });

  nyGroup('API Integration', () {
    nyTest('can fetch users', () async {
      NyMockApi.setRecordCalls(true);
      NyMockApi.respond('/api/users', {
        'users': [
          {'id': 1, 'name': 'Anthony'},
          {'id': 2, 'name': 'Jane'},
        ]
      });

      // ... trigger API call ...

      expectApiCalled('/api/users');
    });
  });

  nyGroup('Time-Sensitive Features', () {
    nyTest('subscription expires correctly', () async {
      NyTest.travel(DateTime(2025, 1, 1));

      // Test subscription logic at a known date
      expect(NyTime.now().year, equals(2025));

      NyTest.travelForward(Duration(days: 365));
      expect(NyTime.now().year, equals(2026));

      NyTest.travelBack();
    });
  });
}
```
