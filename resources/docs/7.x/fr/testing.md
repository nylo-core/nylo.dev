# Tests

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Pour commencer](#getting-started "Pour commencer")
- [Ecrire des tests](#writing-tests "Ecrire des tests")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [Utilitaires de test de widgets](#widget-testing-utilities "Utilitaires de test de widgets")
  - [nyGroup](#ny-group "nyGroup")
  - [Cycle de vie des tests](#test-lifecycle "Cycle de vie des tests")
  - [Ignorer des tests et tests CI](#skipping-tests "Ignorer des tests et tests CI")
- [Authentification](#authentication "Authentification")
- [Voyage dans le temps](#time-travel "Voyage dans le temps")
- [Simulation d'API](#api-mocking "Simulation d'API")
  - [Simulation par patron d'URL](#mocking-by-url "Simulation par patron d'URL")
  - [Simulation par type de service API](#mocking-by-type "Simulation par type de service API")
  - [Historique des appels et assertions](#call-history "Historique des appels et assertions")
- [Factories](#factories "Factories")
  - [Definir des factories](#defining-factories "Definir des factories")
  - [Etats de factory](#factory-states "Etats de factory")
  - [Creer des instances](#creating-instances "Creer des instances")
- [NyFaker](#ny-faker "NyFaker")
- [Cache de test](#test-cache "Cache de test")
- [Simulation de canaux de plateforme](#platform-channel-mocking "Simulation de canaux de plateforme")
- [Simulation de Route Guard](#route-guard-mocking "Simulation de Route Guard")
- [Assertions](#assertions "Assertions")
- [Matchers personnalises](#custom-matchers "Matchers personnalises")
- [Test d'etat](#state-testing "Test d'etat")
- [Debogage](#debugging "Debogage")
- [Exemples](#examples "Exemples")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 inclut un framework de test complet inspire des utilitaires de test de Laravel. Il fournit :

- **Fonctions de test** avec setup/teardown automatiques (`nyTest`, `nyWidgetTest`, `nyGroup`)
- **Simulation d'authentification** via `NyTest.actingAs<T>()`
- **Voyage dans le temps** pour figer ou manipuler le temps dans les tests
- **Simulation d'API** avec correspondance de patrons d'URL et suivi des appels
- **Factories** avec un generateur de donnees fictives integre (`NyFaker`)
- **Simulation de canaux de plateforme** pour le stockage securise, le fournisseur de chemins et plus
- **Assertions personnalisees** pour les routes, Backpack, l'authentification et l'environnement

<div id="getting-started"></div>

## Pour commencer

Initialisez le framework de test en haut de votre fichier de test :

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` configure l'environnement de test et active la reinitialisation automatique de l'etat entre les tests lorsque `autoReset: true` (valeur par defaut).

<div id="writing-tests"></div>

## Ecrire des tests

<div id="ny-test"></div>

### nyTest

La fonction principale pour ecrire des tests :

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

Options :

``` dart
nyTest('my test', () async {
  // test body
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

Pour tester les widgets Flutter avec un `WidgetTester` :

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

### Utilitaires de test de widgets

La classe `NyWidgetTest` et les extensions `WidgetTester` fournissent des aides pour injecter des widgets Nylo avec un support de theme correct, attendre la fin de `init()`, et tester les etats de chargement.

#### Configurer l'environnement de test

Appelez `NyWidgetTest.configure()` dans votre `setUpAll` pour desactiver la recuperation de Google Fonts et optionnellement definir un theme personnalise :

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

Vous pouvez reinitialiser la configuration avec `NyWidgetTest.reset()`.

Deux themes integres sont disponibles pour les tests sans polices :

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Injecter des widgets Nylo

Utilisez `pumpNyWidget` pour envelopper un widget dans un `MaterialApp` avec support de theme :

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

Pour une injection rapide avec un theme sans polices :

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Attendre l'initialisation

`pumpNyWidgetAndWaitForInit` injecte des frames jusqu'a ce que les indicateurs de chargement disparaissent (ou que le delai soit atteint), ce qui est utile pour les pages avec des methodes `init()` asynchrones :

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() has completed
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Aides de pompage

``` dart
// Pump frames until a specific widget appears
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Settle gracefully (won't throw on timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Simulation de cycle de vie

Simulez des changements de `AppLifecycleState` sur n'importe quel `NyPage` dans l'arbre de widgets :

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Assert side effects of the paused lifecycle action
```

#### Verifications de chargement et de verrouillage

Verifiez les cles de chargement nommees et les verrous sur les widgets `NyPage`/`NyState` :

``` dart
// Check if a named loading key is active
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Check if a named lock is held
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Check for any loading indicator (CircularProgressIndicator or Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### Aide testNyPage

Une fonction pratique qui injecte un `NyPage`, attend l'initialisation, puis execute vos attentes :

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

#### Aide testNyPageLoading

Testez qu'une page affiche un indicateur de chargement pendant `init()` :

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

Un mixin fournissant des utilitaires de test de page courants :

``` dart
class HomePageTest with NyPageTestMixin {
  void runTests(WidgetTester tester) async {
    // Verify init was called and loading completed
    await verifyInitCalled(tester, HomePage(), timeout: Duration(seconds: 5));

    // Verify loading state is shown during init
    await verifyLoadingState(tester, HomePage());
  }
}
```

<div id="ny-group"></div>

### nyGroup

Regroupez des tests lies ensemble :

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

### Cycle de vie des tests

Configurez la logique de setup et teardown a l'aide de hooks de cycle de vie :

``` dart
void main() {
  NyTest.init();

  nySetUpAll(() {
    // Runs once before all tests
  });

  nySetUp(() {
    // Runs before each test
  });

  nyTearDown(() {
    // Runs after each test
  });

  nyTearDownAll(() {
    // Runs once after all tests
  });
}
```

<div id="skipping-tests"></div>

### Ignorer des tests et tests CI

``` dart
// Skip a test with a reason
nySkip('not implemented yet', () async {
  // ...
}, "Waiting for API update");

// Tests expected to fail
nyFailing('known bug', () async {
  // ...
});

// CI-only tests (tagged with 'ci')
nyCi('integration test', () async {
  // Only runs in CI environments
});
```

<div id="authentication"></div>

## Authentification

Simulez des utilisateurs authentifies dans les tests :

``` dart
nyTest('user can access profile', () async {
  // Simulate a logged-in user
  NyTest.actingAs<User>(User(name: "Anthony", email: "anthony@example.com"));

  // Verify authenticated
  expectAuthenticated<User>();

  // Access the acting user
  User? user = NyTest.actingUser<User>();
  expect(user?.name, equals("Anthony"));
});

nyTest('guest cannot access profile', () async {
  // Verify not authenticated
  expectGuest();
});
```

Deconnectez l'utilisateur :

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## Voyage dans le temps

Manipulez le temps dans vos tests en utilisant `NyTime` :

### Se deplacer a une date specifique

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Reset to real time
});
```

### Avancer ou reculer dans le temps

``` dart
NyTest.travelForward(Duration(days: 30)); // Jump 30 days ahead
NyTest.travelBackward(Duration(hours: 2)); // Go back 2 hours
```

### Figer le temps

``` dart
NyTest.freezeTime(); // Freeze at the current moment

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Time hasn't moved

NyTest.travelBack(); // Unfreeze
```

### Limites temporelles

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1st of current month
NyTime.travelToEndOfMonth();   // Last day of current month
NyTime.travelToStartOfYear();  // Jan 1st
NyTime.travelToEndOfYear();    // Dec 31st
```

### Voyage dans le temps avec portee

Executez du code dans un contexte temporel fige :

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Time is automatically restored after the callback
```

<div id="api-mocking"></div>

## Simulation d'API

<div id="mocking-by-url"></div>

### Simulation par patron d'URL

Simulez des reponses API en utilisant des patrons d'URL avec prise en charge des caracteres generiques :

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

### Simulation par type de service API

Simulez un service API entier par type :

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

### Historique des appels et assertions

Suivez et verifiez les appels API :

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

### Creer des reponses simulees

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

### Definir des factories

Definissez comment creer des instances de test de vos modeles :

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

Avec prise en charge des surcharges :

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### Etats de factory

Definissez des variations d'une factory :

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### Creer des instances

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

`NyFaker` genere des donnees fictives realistes pour les tests. Il est disponible dans les definitions de factory et peut etre instancie directement.

``` dart
NyFaker faker = NyFaker();
```

### Methodes disponibles

| Categorie | Methode | Type de retour | Description |
|-----------|---------|----------------|-------------|
| **Noms** | `faker.firstName()` | `String` | Prenom aleatoire |
| | `faker.lastName()` | `String` | Nom de famille aleatoire |
| | `faker.name()` | `String` | Nom complet (prenom + nom) |
| | `faker.username()` | `String` | Nom d'utilisateur |
| **Contact** | `faker.email()` | `String` | Adresse e-mail |
| | `faker.phone()` | `String` | Numero de telephone |
| | `faker.company()` | `String` | Nom d'entreprise |
| **Nombres** | `faker.randomInt(min, max)` | `int` | Entier aleatoire dans une plage |
| | `faker.randomDouble(min, max)` | `double` | Double aleatoire dans une plage |
| | `faker.randomBool()` | `bool` | Booleen aleatoire |
| **Identifiants** | `faker.uuid()` | `String` | Chaine UUID v4 |
| **Dates** | `faker.date()` | `DateTime` | Date aleatoire |
| | `faker.pastDate()` | `DateTime` | Date dans le passe |
| | `faker.futureDate()` | `DateTime` | Date dans le futur |
| **Texte** | `faker.lorem()` | `String` | Mots lorem ipsum |
| | `faker.sentences()` | `String` | Plusieurs phrases |
| | `faker.paragraphs()` | `String` | Plusieurs paragraphes |
| | `faker.slug()` | `String` | Slug d'URL |
| **Web** | `faker.url()` | `String` | Chaine d'URL |
| | `faker.imageUrl()` | `String` | URL d'image (via picsum.photos) |
| | `faker.ipAddress()` | `String` | Adresse IPv4 |
| | `faker.macAddress()` | `String` | Adresse MAC |
| **Localisation** | `faker.address()` | `String` | Adresse postale |
| | `faker.city()` | `String` | Nom de ville |
| | `faker.state()` | `String` | Abreviation d'etat americain |
| | `faker.zipCode()` | `String` | Code postal |
| | `faker.country()` | `String` | Nom de pays |
| **Autre** | `faker.hexColor()` | `String` | Code couleur hexadecimal |
| | `faker.creditCardNumber()` | `String` | Numero de carte de credit |
| | `faker.randomElement(list)` | `T` | Element aleatoire d'une liste |
| | `faker.randomElements(list, count)` | `List<T>` | Elements aleatoires d'une liste |

<div id="test-cache"></div>

## Cache de test

`NyTestCache` fournit un cache en memoire pour tester les fonctionnalites liees au cache :

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

## Simulation de canaux de plateforme

`NyMockChannels` simule automatiquement les canaux de plateforme courants pour eviter les plantages dans les tests :

``` dart
void main() {
  NyTest.init(); // Automatically sets up mock channels

  // Or set up manually
  NyMockChannels.setup();
}
```

### Canaux simules

- **path_provider** -- repertoires de documents, temporaires, support d'application, bibliotheque et cache
- **flutter_secure_storage** -- stockage securise en memoire
- **flutter_timezone** -- donnees de fuseau horaire
- **flutter_local_notifications** -- canal de notifications
- **sqflite** -- operations de base de donnees

### Remplacer les chemins

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### Stockage securise dans les tests

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## Simulation de Route Guard

`NyMockRouteGuard` vous permet de tester le comportement des route guards sans authentification reelle ni appels reseau. Il etend `NyRouteGuard` et fournit des constructeurs factory pour les scenarios courants.

### Guard qui passe toujours

``` dart
final guard = NyMockRouteGuard.pass();
```

### Guard qui redirige

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// With additional data
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Guard avec logique personnalisee

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // abort navigation
  }
  return GuardResult.next; // allow navigation
});
```

### Suivi des appels de guard

Apres l'invocation d'un guard, vous pouvez inspecter son etat :

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Access the RouteContext from the last call
RouteContext? context = guard.lastContext;

// Reset tracking
guard.reset();
```

<div id="assertions"></div>

## Assertions

{{ config('app.name') }} fournit des fonctions d'assertion personnalisees :

### Assertions de route

``` dart
expectRoute('/home');           // Assert current route
expectNotRoute('/login');       // Assert not on route
expectRouteInHistory('/home');  // Assert route was visited
expectRouteExists('/profile');  // Assert route is registered
expectRoutesExist(['/home', '/profile', '/settings']);
```

### Assertions d'etat

``` dart
expectBackpackContains("key");                        // Key exists
expectBackpackContains("key", value: "expected");     // Key has value
expectBackpackNotContains("key");                     // Key doesn't exist
```

### Assertions d'authentification

``` dart
expectAuthenticated<User>();  // User is authenticated
expectGuest();                // No user authenticated
```

### Assertions d'environnement

``` dart
expectEnv("APP_NAME", "MyApp");  // Env variable equals value
expectEnvSet("APP_KEY");          // Env variable is set
```

### Assertions de mode

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### Assertions d'API

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### Assertions de locale

``` dart
expectLocale("en");
```

### Assertions de toast

Verifiez les notifications toast enregistrees pendant un test. Necessite `NyToastRecorder.setup()` dans votre setUp de test :

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

**NyToastRecorder** suit les notifications toast pendant les tests :

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

### Assertions de verrouillage et de chargement

Verifiez les etats de verrouillage et de chargement nommes dans les widgets `NyPage`/`NyState` :

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

## Matchers personnalises

Utilisez des matchers personnalises avec `expect()` :

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

## Test d'etat

Testez la gestion d'etat pilotee par EventBus dans les widgets `NyPage` et `NyState` en utilisant les aides de test d'etat.

### Emettre des mises a jour d'etat

Simulez des mises a jour d'etat qui viendraient normalement d'un autre widget ou controlleur :

``` dart
// Fire an UpdateState event
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### Emettre des actions d'etat

Envoyez des actions d'etat gerees par `whenStateAction()` dans votre page :

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// With additional data
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### Assertions d'etat

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

Suivez et inspectez les mises a jour et actions d'etat emises :

``` dart
// Get all updates fired to a state
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Get all actions fired to a state
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Reset all tracked state updates and actions
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## Debogage

### dump

Affiche l'etat actuel du test (contenu du Backpack, utilisateur authentifie, heure, appels API, locale) :

``` dart
NyTest.dump();
```

### dd (Dump and Die)

Affiche l'etat du test et termine immediatement le test :

``` dart
NyTest.dd();
```

### Stockage d'etat de test

Stockez et recuperez des valeurs pendant un test :

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Pre-remplir Backpack

Pre-remplissez Backpack avec des donnees de test :

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## Exemples

### Fichier de test complet

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
