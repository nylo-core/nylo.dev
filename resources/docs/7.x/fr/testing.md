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
- [Assistants de navigation et d'interaction](#nav-interaction "Assistants de navigation et d'interaction")
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
  // corps du test
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
// init() est termine
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Aides de pompage

``` dart
// Pomper des frames jusqu'a ce qu'un widget specifique apparaisse
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Settle avec grace (ne leve pas d'exception si timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Simulation de cycle de vie

Simulez des changements de `AppLifecycleState` sur n'importe quel `NyPage` dans l'arbre de widgets :

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Verifier les effets secondaires de l'action du cycle de vie en pause
```

#### Verifications de chargement et de verrouillage

Verifiez les cles de chargement nommees et les verrous sur les widgets `NyPage`/`NyState` :

``` dart
// Verifier si une cle de chargement nommee est active
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Verifier si un verrou nomme est tenu
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Verifier la presence d'un indicateur de chargement (CircularProgressIndicator ou Skeletonizer)
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
    // Verifier que init a ete appele et le chargement termine
    await verifyInitCalled(tester, HomePage(), timeout: Duration(seconds: 5));

    // Verifier que l'etat de chargement est affiche pendant init
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
    // S'execute une fois avant tous les tests
  });

  nySetUp(() {
    // S'execute avant chaque test
  });

  nyTearDown(() {
    // S'execute apres chaque test
  });

  nyTearDownAll(() {
    // S'execute une fois apres tous les tests
  });
}
```

<div id="skipping-tests"></div>

### Ignorer des tests et tests CI

``` dart
// Passer un test avec une raison
nySkip('not implemented yet', () async {
  // ...
}, "Waiting for API update");

// Tests prevus pour echouer
nyFailing('known bug', () async {
  // ...
});

// Tests uniquement pour la CI (marques avec 'ci')
nyCi('integration test', () async {
  // S'execute uniquement dans les environnements CI
});
```

<div id="authentication"></div>

## Authentification

Simulez des utilisateurs authentifies dans les tests :

``` dart
nyTest('user can access profile', () async {
  // Simuler un utilisateur connecte
  NyTest.actingAs<User>(User(name: "Anthony", email: "anthony@example.com"));

  // Verifier l'authentification
  expectAuthenticated<User>();

  // Acceder a l'utilisateur actif
  User? user = NyTest.actingUser<User>();
  expect(user?.name, equals("Anthony"));
});

nyTest('guest cannot access profile', () async {
  // Verifier que non authentifie
  expectGuest();
});
```

Deconnectez l'utilisateur :

``` dart
NyTest.logout();
expectGuest();
```

Utilisez `actingAsGuest()` comme alias lisible pour `logout()` lors de la configuration d'un contexte invite :

``` dart
NyTest.actingAsGuest();
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

  NyTest.travelBack(); // Reinitialiser a l'heure reelle
});
```

### Avancer ou reculer dans le temps

``` dart
NyTest.travelForward(Duration(days: 30)); // Avancer de 30 jours
NyTest.travelBackward(Duration(hours: 2)); // Reculer de 2 heures
```

### Figer le temps

``` dart
NyTest.freezeTime(); // Figer au moment actuel

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Le temps n'a pas avance

NyTest.travelBack(); // Defiger
```

### Limites temporelles

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1er du mois en cours
NyTime.travelToEndOfMonth();   // Dernier jour du mois en cours
NyTime.travelToStartOfYear();  // 1er janvier
NyTime.travelToEndOfYear();    // 31 decembre
```

### Voyage dans le temps avec portee

Executez du code dans un contexte temporel fige :

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Le temps est automatiquement restaure apres le callback
```

<div id="api-mocking"></div>

## Simulation d'API

<div id="mocking-by-url"></div>

### Simulation par patron d'URL

Simulez des reponses API en utilisant des patrons d'URL avec prise en charge des caracteres generiques :

``` dart
nyTest('mock API responses', () async {
  // Correspondance exacte de l'URL
  NyMockApi.respond('/users/1', {'id': 1, 'name': 'Anthony'});

  // Caractere generique sur un segment (*)
  NyMockApi.respond('/users/*', {'id': 1, 'name': 'User'});

  // Caractere generique sur plusieurs segments (**)
  NyMockApi.respond('/api/**', {'status': 'ok'});

  // Avec code de statut et en-tetes
  NyMockApi.respond(
    '/users',
    {'error': 'Unauthorized'},
    statusCode: 401,
    method: 'POST',
    headers: {'X-Error': 'true'},
  );

  // Avec delai simule
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

  // ... effectuer des actions qui declenchent des appels API ...

  // Affirmer que le point de terminaison a ete appele
  expectApiCalled('/users');

  // Affirmer que le point de terminaison n'a pas ete appele
  expectApiNotCalled('/admin');

  // Affirmer le nombre d'appels
  expectApiCalled('/users', times: 2);

  // Affirmer la methode specifique
  expectApiCalled('/users', method: 'POST');

  // Obtenir les details des appels
  List<ApiCallInfo> calls = NyMockApi.getCallsFor('/users');
});
```

Verifier qu'un endpoint a ete appele avec des donnees de corps de requete specifiques :

``` dart
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
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
// Creer une seule instance
User user = NyFactory.make<User>();

// Creer avec des substitutions
User admin = NyFactory.make<User>(overrides: {'name': 'Admin User'});

// Creer avec des etats appliques
User premiumAdmin = NyFactory.make<User>(states: ['admin', 'premium']);

// Creer plusieurs instances
List<User> users = NyFactory.create<User>(count: 5);

// Creer une sequence avec des donnees basees sur l'index
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

  // Stocker une valeur
  await cache.put<String>("key", "value");

  // Stocker avec expiration
  await cache.put<String>("temp", "data", seconds: 60);

  // Lire une valeur
  String? value = await cache.get<String>("key");

  // Verifier l'existence
  bool exists = await cache.has("key");

  // Effacer une cle
  await cache.clear("key");

  // Tout vider
  await cache.flush();

  // Obtenir des informations sur le cache
  int size = await cache.size();
  List<String> keys = await cache.documents();
});
```

<div id="platform-channel-mocking"></div>

## Simulation de canaux de plateforme

`NyMockChannels` simule automatiquement les canaux de plateforme courants pour eviter les plantages dans les tests :

``` dart
void main() {
  NyTest.init(); // Configure automatiquement les canaux de simulation

  // Ou configurer manuellement
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

// Avec des donnees supplementaires
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Guard avec logique personnalisee

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // annuler la navigation
  }
  return GuardResult.next; // autoriser la navigation
});
```

### Suivi des appels de guard

Apres l'invocation d'un guard, vous pouvez inspecter son etat :

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Acceder au RouteContext du dernier appel
RouteContext? context = guard.lastContext;

// Reinitialiser le suivi
guard.reset();
```

<div id="assertions"></div>

## Assertions

{{ config('app.name') }} fournit des fonctions d'assertion personnalisees :

### Assertions de route

``` dart
expectRoute('/home');           // Affirmer la route actuelle
expectNotRoute('/login');       // Affirmer ne pas etre sur la route
expectRouteInHistory('/home');  // Affirmer que la route a ete visitee
expectRouteExists('/profile');  // Affirmer que la route est enregistree
expectRoutesExist(['/home', '/profile', '/settings']);
```

### Assertions d'etat

``` dart
expectBackpackContains("key");                        // La cle existe
expectBackpackContains("key", value: "expected");     // La cle a une valeur
expectBackpackNotContains("key");                     // La cle n'existe pas
```

### Assertions d'authentification

``` dart
expectAuthenticated<User>();  // L'utilisateur est authentifie
expectGuest();                // Aucun utilisateur authentifie
```

### Assertions d'environnement

``` dart
expectEnv("APP_NAME", "MyApp");  // La variable d'environnement est egale a la valeur
expectEnvSet("APP_KEY");          // La variable d'environnement est definie
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
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
```

### Assertions de widget

``` dart
// Affirmer qu'un type de widget apparait un nombre specifique de fois
expectWidgetCount(ListTile, 3);
expectWidgetCount(Icon, 0);

// Affirmer que le texte est visible
expectTextVisible('Welcome');

// Affirmer que le texte n'est pas visible
expectTextNotVisible('Error');

// Affirmer qu'un widget est visible (utiliser n'importe quel Finder)
expectVisible(find.byType(FloatingActionButton));
expectVisible(find.byIcon(Icons.notifications));
expectVisible(find.byKey(Key('submit_btn')));

// Affirmer qu'un widget n'est pas visible
expectNotVisible(find.byType(ErrorBanner));
expectNotVisible(find.byKey(Key('loading_spinner')));
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
  // ... declencher l'action qui affiche un toast ...

  expectToastShown(id: 'success');
  expectToastShown(id: 'danger', description: 'Something went wrong');
  expectNoToastShown(id: 'info');
});
```

**NyToastRecorder** suit les notifications toast pendant les tests :

``` dart
// Enregistrer un toast manuellement
NyToastRecorder.record(id: 'success', title: 'Done', description: 'Saved!');

// Verifier si un toast a ete affiche
bool shown = NyToastRecorder.wasShown(id: 'success');

// Acceder a tous les toasts enregistres
List<ToastRecord> toasts = NyToastRecorder.records;

// Effacer les toasts enregistres
NyToastRecorder.clear();
```

### Assertions de verrouillage et de chargement

Verifiez les etats de verrouillage et de chargement nommes dans les widgets `NyPage`/`NyState` :

``` dart
// Affirmer qu'un verrou nomme est tenu
expectLocked(tester, find.byType(MyPage), 'submit');

// Affirmer qu'un verrou nomme n'est pas tenu
expectNotLocked(tester, find.byType(MyPage), 'submit');

// Affirmer qu'une cle de chargement nommee est active
expectLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');

// Affirmer qu'une cle de chargement nommee n'est pas active
expectNotLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');
```

<div id="custom-matchers"></div>

## Matchers personnalises

Utilisez des matchers personnalises avec `expect()` :

``` dart
// Matcher de type
expect(result, isType<User>());

// Matcher de nom de route
expect(widget, hasRouteName('/home'));

// Matcher Backpack
expect(true, backpackHas("key", value: "expected"));

// Matcher d'appel API
expect(true, apiWasCalled('/users', method: 'GET', times: 1));
```

<div id="state-testing"></div>

## Test d'etat

Testez la gestion d'etat pilotee par EventBus dans les widgets `NyPage` et `NyState` en utilisant les aides de test d'etat.

### Emettre des mises a jour d'etat

Simulez des mises a jour d'etat qui viendraient normalement d'un autre widget ou controlleur :

``` dart
// Declencher un evenement UpdateState
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### Emettre des actions d'etat

Envoyez des actions d'etat gerees par `whenStateAction()` dans votre page :

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// Avec des donnees supplementaires
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### Assertions d'etat

``` dart
// Affirmer qu'une mise a jour d'etat a ete declenchee
expectStateUpdated('HomePageState');
expectStateUpdated('HomePageState', times: 2);

// Affirmer qu'une action d'etat a ete declenchee
expectStateAction('HomePageState', 'refresh-page');
expectStateAction('CartState', 'add-item', times: 1);

// Affirmer la stateData d'un widget NyPage/NyState
expectStateData(tester, find.byType(MyWidget), equals(42));
```

### NyStateTestHelpers

Suivez et inspectez les mises a jour et actions d'etat emises :

``` dart
// Obtenir toutes les mises a jour declenchees pour un etat
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Obtenir toutes les actions declenchees pour un etat
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Reinitialiser toutes les mises a jour et actions d'etat suivies
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

<div id="nav-interaction"></div>

## Assistants de navigation et d'interaction

Les extensions de `WidgetTester` fournissent un DSL haut niveau pour ecrire des flux de navigation et des interactions UI dans `nyWidgetTest`.

### visit

Naviguer vers une route et attendre que la page se stabilise :

``` dart
nyWidgetTest('charge le tableau de bord', (tester) async {
  await tester.visit(DashboardPage.path);
  expectTextVisible('Dashboard');
});
```

### assertNavigatedTo

Affirmer qu'une action de navigation vous a amene a la route attendue :

``` dart
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);
```

### assertOnRoute

Affirmer que la route actuelle correspond a la route donnee (a utiliser pour confirmer ou vous etes, non que vous venez de naviguer) :

``` dart
await tester.visit(DashboardPage.path);
tester.assertOnRoute(DashboardPage.path);
```

### settle

Attendre que toutes les animations en attente et les callbacks de frame soient termines :

``` dart
await tester.tap(find.byType(MyButton));
await tester.settle();
tester.assertNavigatedTo(ProfilePage.path);
```

### navigateBack

Fermer la route actuelle et attendre la stabilisation :

``` dart
await tester.visit(DashboardPage.path);
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);

await tester.navigateBack();
tester.assertOnRoute(DashboardPage.path);
```

### tapText

Trouver un widget par son texte, le toucher et attendre en un seul appel :

``` dart
await tester.tapText('Login');
await tester.tapText('Submit');
```

### fillField

Toucher un champ de formulaire, saisir du texte et attendre :

``` dart
await tester.fillField(find.byKey(Key('email')), 'test@example.com');
await tester.fillField(find.byKey(Key('password')), 'secret123');
```

### scrollTo

Faire defiler jusqu'a ce qu'un widget soit visible, puis attendre :

``` dart
await tester.scrollTo(find.text('Item 50'));
await tester.tapText('Item 50');
```

Passer un finder `scrollable` specifique et un `delta` pour un controle precis :

``` dart
await tester.scrollTo(
  find.text('Footer'),
  scrollable: find.byKey(Key('main_list')),
  delta: 200,
);
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

      // ... declencher l'appel API ...

      expectApiCalled('/api/users');
    });
  });

  nyGroup('Time-Sensitive Features', () {
    nyTest('subscription expires correctly', () async {
      NyTest.travel(DateTime(2025, 1, 1));

      // Tester la logique d'abonnement a une date connue
      expect(NyTime.now().year, equals(2025));

      NyTest.travelForward(Duration(days: 365));
      expect(NyTime.now().year, equals(2026));

      NyTest.travelBack();
    });
  });
}
```
