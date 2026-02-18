# Testing

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Rozpoczęcie pracy](#getting-started "Rozpoczęcie pracy")
- [Pisanie testów](#writing-tests "Pisanie testów")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [Narzędzia do testowania widgetów](#widget-testing-utilities "Narzędzia do testowania widgetów")
  - [nyGroup](#ny-group "nyGroup")
  - [Cykl życia testu](#test-lifecycle "Cykl życia testu")
  - [Pomijanie i testy CI](#skipping-tests "Pomijanie i testy CI")
- [Uwierzytelnianie](#authentication "Uwierzytelnianie")
- [Podróż w czasie](#time-travel "Podróż w czasie")
- [Mockowanie API](#api-mocking "Mockowanie API")
  - [Mockowanie według wzorca URL](#mocking-by-url "Mockowanie według wzorca URL")
  - [Mockowanie według typu serwisu API](#mocking-by-type "Mockowanie według typu serwisu API")
  - [Historia wywołań i asercje](#call-history "Historia wywołań i asercje")
- [Fabryki](#factories "Fabryki")
  - [Definiowanie fabryk](#defining-factories "Definiowanie fabryk")
  - [Stany fabryk](#factory-states "Stany fabryk")
  - [Tworzenie instancji](#creating-instances "Tworzenie instancji")
- [NyFaker](#ny-faker "NyFaker")
- [Testowa pamięć podręczna](#test-cache "Testowa pamięć podręczna")
- [Mockowanie kanałów platformowych](#platform-channel-mocking "Mockowanie kanałów platformowych")
- [Mockowanie strażników tras](#route-guard-mocking "Mockowanie strażników tras")
- [Asercje](#assertions "Asercje")
- [Niestandardowe matchery](#custom-matchers "Niestandardowe matchery")
- [Testowanie stanu](#state-testing "Testowanie stanu")
- [Debugowanie](#debugging "Debugowanie")
- [Przykłady](#examples "Praktyczne przykłady")

<div id="introduction"></div>

## Wprowadzenie

{{ config('app.name') }} v7 zawiera kompleksowy framework testowy inspirowany narzędziami testowymi Laravel. Zapewnia:

- **Funkcje testowe** z automatycznym setup/teardown (`nyTest`, `nyWidgetTest`, `nyGroup`)
- **Symulację uwierzytelniania** przez `NyTest.actingAs<T>()`
- **Podróż w czasie** do zamrażania lub manipulowania czasem w testach
- **Mockowanie API** z dopasowywaniem wzorców URL i śledzeniem wywołań
- **Fabryki** z wbudowanym generatorem fałszywych danych (`NyFaker`)
- **Mockowanie kanałów platformowych** dla bezpiecznego przechowywania, dostawcy ścieżek i innych
- **Niestandardowe asercje** dla tras, Backpack, uwierzytelniania i środowiska

<div id="getting-started"></div>

## Rozpoczęcie pracy

Zainicjalizuj framework testowy na początku pliku testowego:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` konfiguruje środowisko testowe i umożliwia automatyczny reset stanu między testami, gdy `autoReset: true` (domyślnie).

<div id="writing-tests"></div>

## Pisanie testów

<div id="ny-test"></div>

### nyTest

Główna funkcja do pisania testów:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

Opcje:

``` dart
nyTest('my test', () async {
  // test body
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

Do testowania widgetów Flutter z `WidgetTester`:

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

### Narzędzia do testowania widgetów

Klasa `NyWidgetTest` i rozszerzenia `WidgetTester` zapewniają helpery do pompowania widgetów Nylo z odpowiednią obsługą motywów, oczekiwania na zakończenie `init()` i testowania stanów ładowania.

#### Konfiguracja środowiska testowego

Wywołaj `NyWidgetTest.configure()` w `setUpAll`, aby wyłączyć pobieranie Google Fonts i opcjonalnie ustawić niestandardowy motyw:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

Możesz zresetować konfigurację za pomocą `NyWidgetTest.reset()`.

Dwa wbudowane motywy są dostępne do testowania bez czcionek:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Pompowanie widgetów Nylo

Użyj `pumpNyWidget`, aby opakować widget w `MaterialApp` z obsługą motywów:

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

Aby szybko pompować z motywem bez czcionek:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Oczekiwanie na Init

`pumpNyWidgetAndWaitForInit` pompuje klatki do momentu zniknięcia wskaźników ładowania (lub osiągnięcia limitu czasu), co jest przydatne dla stron z asynchronicznymi metodami `init()`:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() has completed
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Helpery pompowania

``` dart
// Pump frames until a specific widget appears
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Settle gracefully (won't throw on timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Symulacja cyklu życia

Symuluj zmiany `AppLifecycleState` na dowolnym `NyPage` w drzewie widgetów:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Assert side effects of the paused lifecycle action
```

#### Sprawdzanie ładowania i blokad

Sprawdzaj nazwane klucze ładowania i blokady na widgetach `NyPage`/`NyState`:

``` dart
// Check if a named loading key is active
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Check if a named lock is held
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Check for any loading indicator (CircularProgressIndicator or Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### Helper testNyPage

Funkcja ułatwiająca, która pompuje `NyPage`, czeka na init, a następnie uruchamia Twoje oczekiwania:

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

Testuj, czy strona wyświetla wskaźnik ładowania podczas `init()`:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

Mixin zapewniający typowe narzędzia do testowania stron:

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

Grupuj powiązane testy razem:

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

### Cykl życia testu

Konfiguruj i czyszcz logikę za pomocą hooków cyklu życia:

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

### Pomijanie i testy CI

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

## Uwierzytelnianie

Symuluj uwierzytelnionych użytkowników w testach:

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

Wyloguj użytkownika:

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## Podróż w czasie

Manipuluj czasem w testach za pomocą `NyTime`:

### Przeskok do konkretnej daty

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Reset to real time
});
```

### Przesunięcie czasu do przodu lub wstecz

``` dart
NyTest.travelForward(Duration(days: 30)); // Jump 30 days ahead
NyTest.travelBackward(Duration(hours: 2)); // Go back 2 hours
```

### Zamrożenie czasu

``` dart
NyTest.freezeTime(); // Freeze at the current moment

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Time hasn't moved

NyTest.travelBack(); // Unfreeze
```

### Granice czasowe

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1st of current month
NyTime.travelToEndOfMonth();   // Last day of current month
NyTime.travelToStartOfYear();  // Jan 1st
NyTime.travelToEndOfYear();    // Dec 31st
```

### Zakresowa podróż w czasie

Wykonaj kod w kontekście zamrożonego czasu:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Time is automatically restored after the callback
```

<div id="api-mocking"></div>

## Mockowanie API

<div id="mocking-by-url"></div>

### Mockowanie według wzorca URL

Mockuj odpowiedzi API za pomocą wzorców URL z obsługą symboli wieloznacznych:

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

### Mockowanie według typu serwisu API

Mockuj cały serwis API według typu:

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

### Historia wywołań i asercje

Śledź i weryfikuj wywołania API:

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

### Tworzenie mockowanych odpowiedzi

``` dart
Response<Map<String, dynamic>> response = NyMockApi.createResponse(
  data: {'id': 1, 'name': 'Anthony'},
  statusCode: 200,
  statusMessage: 'OK',
);
```

<div id="factories"></div>

## Fabryki

<div id="defining-factories"></div>

### Definiowanie fabryk

Zdefiniuj sposób tworzenia testowych instancji Twoich modeli:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

Z obsługą nadpisywania:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### Stany fabryk

Definiuj warianty fabryki:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### Tworzenie instancji

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

`NyFaker` generuje realistyczne fałszywe dane do testów. Jest dostępny wewnątrz definicji fabryk i może być tworzony bezpośrednio.

``` dart
NyFaker faker = NyFaker();
```

### Dostępne metody

| Kategoria | Metoda | Typ zwracany | Opis |
|----------|--------|-------------|-------------|
| **Imiona** | `faker.firstName()` | `String` | Losowe imię |
| | `faker.lastName()` | `String` | Losowe nazwisko |
| | `faker.name()` | `String` | Pełne imię i nazwisko |
| | `faker.username()` | `String` | Nazwa użytkownika |
| **Kontakt** | `faker.email()` | `String` | Adres email |
| | `faker.phone()` | `String` | Numer telefonu |
| | `faker.company()` | `String` | Nazwa firmy |
| **Liczby** | `faker.randomInt(min, max)` | `int` | Losowa liczba całkowita z zakresu |
| | `faker.randomDouble(min, max)` | `double` | Losowa liczba zmiennoprzecinkowa z zakresu |
| | `faker.randomBool()` | `bool` | Losowa wartość logiczna |
| **Identyfikatory** | `faker.uuid()` | `String` | Ciąg UUID v4 |
| **Daty** | `faker.date()` | `DateTime` | Losowa data |
| | `faker.pastDate()` | `DateTime` | Data w przeszłości |
| | `faker.futureDate()` | `DateTime` | Data w przyszłości |
| **Tekst** | `faker.lorem()` | `String` | Słowa lorem ipsum |
| | `faker.sentences()` | `String` | Wiele zdań |
| | `faker.paragraphs()` | `String` | Wiele akapitów |
| | `faker.slug()` | `String` | Slug URL |
| **Web** | `faker.url()` | `String` | Ciąg URL |
| | `faker.imageUrl()` | `String` | URL obrazka (przez picsum.photos) |
| | `faker.ipAddress()` | `String` | Adres IPv4 |
| | `faker.macAddress()` | `String` | Adres MAC |
| **Lokalizacja** | `faker.address()` | `String` | Adres uliczny |
| | `faker.city()` | `String` | Nazwa miasta |
| | `faker.state()` | `String` | Skrót stanu US |
| | `faker.zipCode()` | `String` | Kod pocztowy |
| | `faker.country()` | `String` | Nazwa kraju |
| **Inne** | `faker.hexColor()` | `String` | Kod koloru hex |
| | `faker.creditCardNumber()` | `String` | Numer karty kredytowej |
| | `faker.randomElement(list)` | `T` | Losowy element z listy |
| | `faker.randomElements(list, count)` | `List<T>` | Losowe elementy z listy |

<div id="test-cache"></div>

## Testowa pamięć podręczna

`NyTestCache` zapewnia pamięć podręczną w pamięci do testowania funkcjonalności związanych z cache:

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

## Mockowanie kanałów platformowych

`NyMockChannels` automatycznie mockuje typowe kanały platformowe, aby testy nie ulegały awarii:

``` dart
void main() {
  NyTest.init(); // Automatically sets up mock channels

  // Or set up manually
  NyMockChannels.setup();
}
```

### Mockowane kanały

- **path_provider** -- katalogi dokumentów, tymczasowe, wsparcia aplikacji, biblioteki i cache
- **flutter_secure_storage** -- bezpieczne przechowywanie w pamięci
- **flutter_timezone** -- dane strefy czasowej
- **flutter_local_notifications** -- kanał powiadomień
- **sqflite** -- operacje bazodanowe

### Nadpisywanie ścieżek

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### Bezpieczne przechowywanie w testach

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## Mockowanie strażników tras

`NyMockRouteGuard` pozwala testować zachowanie strażników tras bez prawdziwego uwierzytelniania lub wywołań sieciowych. Rozszerza `NyRouteGuard` i zapewnia konstruktory fabryczne dla typowych scenariuszy.

### Strażnik, który zawsze przepuszcza

``` dart
final guard = NyMockRouteGuard.pass();
```

### Strażnik, który przekierowuje

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// With additional data
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Strażnik z niestandardową logiką

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // abort navigation
  }
  return GuardResult.next; // allow navigation
});
```

### Śledzenie wywołań strażnika

Po wywołaniu strażnika możesz sprawdzić jego stan:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Access the RouteContext from the last call
RouteContext? context = guard.lastContext;

// Reset tracking
guard.reset();
```

<div id="assertions"></div>

## Asercje

{{ config('app.name') }} udostępnia niestandardowe funkcje asercji:

### Asercje tras

``` dart
expectRoute('/home');           // Assert current route
expectNotRoute('/login');       // Assert not on route
expectRouteInHistory('/home');  // Assert route was visited
expectRouteExists('/profile');  // Assert route is registered
expectRoutesExist(['/home', '/profile', '/settings']);
```

### Asercje stanu

``` dart
expectBackpackContains("key");                        // Key exists
expectBackpackContains("key", value: "expected");     // Key has value
expectBackpackNotContains("key");                     // Key doesn't exist
```

### Asercje uwierzytelniania

``` dart
expectAuthenticated<User>();  // User is authenticated
expectGuest();                // No user authenticated
```

### Asercje środowiska

``` dart
expectEnv("APP_NAME", "MyApp");  // Env variable equals value
expectEnvSet("APP_KEY");          // Env variable is set
```

### Asercje trybu

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### Asercje API

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### Asercje lokalizacji

``` dart
expectLocale("en");
```

### Asercje toastów

Asercje powiadomień toast, które zostały nagrane podczas testu. Wymaga `NyToastRecorder.setup()` w setUp testu:

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

**NyToastRecorder** śledzi powiadomienia toast podczas testów:

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

### Asercje blokad i ładowania

Asercje nazwanych stanów blokad i ładowania w widgetach `NyPage`/`NyState`:

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

## Niestandardowe matchery

Używaj niestandardowych matcherów z `expect()`:

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

## Testowanie stanu

Testuj zarządzanie stanem oparte na EventBus w widgetach `NyPage` i `NyState` za pomocą helperów testowania stanu.

### Wyzwalanie aktualizacji stanu

Symuluj aktualizacje stanu, które normalnie pochodzą z innego widgetu lub kontrolera:

``` dart
// Fire an UpdateState event
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### Wyzwalanie akcji stanu

Wysyłaj akcje stanu obsługiwane przez `whenStateAction()` w Twojej stronie:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// With additional data
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### Asercje stanu

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

Śledź i sprawdzaj wyzwolone aktualizacje i akcje stanu:

``` dart
// Get all updates fired to a state
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Get all actions fired to a state
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Reset all tracked state updates and actions
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## Debugowanie

### dump

Wypisz bieżący stan testu (zawartość Backpack, użytkownik auth, czas, wywołania API, lokalizacja):

``` dart
NyTest.dump();
```

### dd (Dump and Die)

Wypisz stan testu i natychmiast zakończ test:

``` dart
NyTest.dd();
```

### Przechowywanie stanu testu

Zapisuj i odczytuj wartości podczas testu:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Zasilanie Backpack

Wstępnie wypełnij Backpack danymi testowymi:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## Przykłady

### Kompletny plik testowy

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
