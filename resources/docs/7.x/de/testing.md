# Testen

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Erste Schritte](#getting-started "Erste Schritte")
- [Tests schreiben](#writing-tests "Tests schreiben")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [Widget-Test-Hilfsmittel](#widget-testing-utilities "Widget-Test-Hilfsmittel")
  - [nyGroup](#ny-group "nyGroup")
  - [Test-Lebenszyklus](#test-lifecycle "Test-Lebenszyklus")
  - [Tests ueberspringen und CI-Tests](#skipping-tests "Tests ueberspringen und CI-Tests")
- [Authentifizierung](#authentication "Authentifizierung")
- [Zeitreisen](#time-travel "Zeitreisen")
- [API-Mocking](#api-mocking "API-Mocking")
  - [Mocking nach URL-Muster](#mocking-by-url "Mocking nach URL-Muster")
  - [Mocking nach API-Service-Typ](#mocking-by-type "Mocking nach API-Service-Typ")
  - [Aufrufverlauf und Assertions](#call-history "Aufrufverlauf und Assertions")
- [Factories](#factories "Factories")
  - [Factories definieren](#defining-factories "Factories definieren")
  - [Factory-Zustaende](#factory-states "Factory-Zustaende")
  - [Instanzen erstellen](#creating-instances "Instanzen erstellen")
- [NyFaker](#ny-faker "NyFaker")
- [Test-Cache](#test-cache "Test-Cache")
- [Platform-Channel-Mocking](#platform-channel-mocking "Platform-Channel-Mocking")
- [Route-Guard-Mocking](#route-guard-mocking "Route-Guard-Mocking")
- [Assertions](#assertions "Assertions")
- [Benutzerdefinierte Matcher](#custom-matchers "Benutzerdefinierte Matcher")
- [State-Testing](#state-testing "State-Testing")
- [Debugging](#debugging "Debugging")
- [Navigation- und Interaktions-Helfer](#nav-interaction "Navigation- und Interaktions-Helfer")
- [Beispiele](#examples "Beispiele")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} v7 enthaelt ein umfassendes Test-Framework, inspiriert von Laravels Test-Utilities. Es bietet:

- **Testfunktionen** mit automatischem Setup/Teardown (`nyTest`, `nyWidgetTest`, `nyGroup`)
- **Authentifizierungssimulation** ueber `NyTest.actingAs<T>()`
- **Zeitreisen** zum Einfrieren oder Manipulieren der Zeit in Tests
- **API-Mocking** mit URL-Musterabgleich und Aufrufverfolgung
- **Factories** mit einem integrierten Fake-Datengenerator (`NyFaker`)
- **Platform-Channel-Mocking** fuer Secure Storage, Path Provider und mehr
- **Benutzerdefinierte Assertions** fuer Routen, Backpack, Authentifizierung und Umgebung

<div id="getting-started"></div>

## Erste Schritte

Initialisieren Sie das Test-Framework am Anfang Ihrer Testdatei:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` richtet die Testumgebung ein und aktiviert das automatische Zuruecksetzen des Zustands zwischen Tests, wenn `autoReset: true` (Standardeinstellung).

<div id="writing-tests"></div>

## Tests schreiben

<div id="ny-test"></div>

### nyTest

Die primaere Funktion zum Schreiben von Tests:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

Optionen:

``` dart
nyTest('my test', () async {
  // Test-Rumpf
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

Zum Testen von Flutter-Widgets mit einem `WidgetTester`:

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

### Widget-Test-Hilfsmittel

Die Klasse `NyWidgetTest` und die `WidgetTester`-Erweiterungen bieten Hilfsfunktionen zum Pumpen von Nylo-Widgets mit korrekter Theme-Unterstuetzung, zum Warten auf den Abschluss von `init()` und zum Testen von Ladezustaenden.

#### Testumgebung konfigurieren

Rufen Sie `NyWidgetTest.configure()` in Ihrem `setUpAll` auf, um das Laden von Google Fonts zu deaktivieren und optional ein benutzerdefiniertes Theme festzulegen:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

Sie koennen die Konfiguration mit `NyWidgetTest.reset()` zuruecksetzen.

Zwei integrierte Themes sind fuer schriftfreies Testen verfuegbar:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Nylo-Widgets pumpen

Verwenden Sie `pumpNyWidget`, um ein Widget in eine `MaterialApp` mit Theme-Unterstuetzung einzubetten:

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

Fuer schnelles Pumpen mit einem schriftfreien Theme:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Auf Initialisierung warten

`pumpNyWidgetAndWaitForInit` pumpt Frames, bis die Ladeanzeigen verschwinden (oder das Timeout erreicht ist), was nützlich für Seiten mit asynchronen `init()`-Methoden ist:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() wurde abgeschlossen
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Pump-Hilfsfunktionen

``` dart
// Frames pumpen, bis ein bestimmtes Widget erscheint
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Graceful Settle (wirft keinen Fehler beim Timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Lebenszyklus-Simulation

Simulieren Sie `AppLifecycleState`-Aenderungen bei jedem `NyPage` im Widget-Baum:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Nebenwirkungen der pausierten Lebenszyklus-Aktion pruefen
```

#### Lade- und Sperr-Ueberpruefungen

Ueberpruefen Sie benannte Ladeschluessel und Sperren auf `NyPage`/`NyState`-Widgets:

``` dart
// Pruefen, ob ein benannter Ladeschluessel aktiv ist
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Pruefen, ob eine benannte Sperre gehalten wird
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Pruefen, ob ein Ladeindikator vorhanden ist (CircularProgressIndicator oder Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### testNyPage-Hilfsfunktion

Eine praktische Funktion, die ein `NyPage` pumpt, auf die Initialisierung wartet und dann Ihre Erwartungen ausfuehrt:

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

#### testNyPageLoading-Hilfsfunktion

Testen Sie, dass eine Seite waehrend `init()` einen Ladeindikator anzeigt:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

Ein Mixin mit gaengigen Seitentest-Hilfsmitteln:

``` dart
class HomePageTest with NyPageTestMixin {
  void runTests(WidgetTester tester) async {
    // Pruefen, ob init aufgerufen wurde und Laden abgeschlossen ist
    await verifyInitCalled(tester, HomePage(), timeout: Duration(seconds: 5));

    // Pruefen, ob der Ladezustand waehrend init angezeigt wird
    await verifyLoadingState(tester, HomePage());
  }
}
```

<div id="ny-group"></div>

### nyGroup

Zusammengehoerige Tests gruppieren:

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

### Test-Lebenszyklus

Setup- und Teardown-Logik mit Lebenszyklus-Hooks einrichten:

``` dart
void main() {
  NyTest.init();

  nySetUpAll(() {
    // Wird einmal vor allen Tests ausgefuehrt
  });

  nySetUp(() {
    // Wird vor jedem Test ausgefuehrt
  });

  nyTearDown(() {
    // Wird nach jedem Test ausgefuehrt
  });

  nyTearDownAll(() {
    // Wird einmal nach allen Tests ausgefuehrt
  });
}
```

<div id="skipping-tests"></div>

### Tests ueberspringen und CI-Tests

``` dart
// Einen Test mit einer Begruendung ueberspringen
nySkip('not implemented yet', () async {
  // ...
}, "Waiting for API update");

// Tests, die voraussichtlich fehlschlagen
nyFailing('known bug', () async {
  // ...
});

// Nur-CI-Tests (mit 'ci' markiert)
nyCi('integration test', () async {
  // Wird nur in CI-Umgebungen ausgefuehrt
});
```

<div id="authentication"></div>

## Authentifizierung

Authentifizierte Benutzer in Tests simulieren:

``` dart
nyTest('user can access profile', () async {
  // Einen angemeldeten Benutzer simulieren
  NyTest.actingAs<User>(User(name: "Anthony", email: "anthony@example.com"));

  // Authentifizierung pruefen
  expectAuthenticated<User>();

  // Den agierenden Benutzer abrufen
  User? user = NyTest.actingUser<User>();
  expect(user?.name, equals("Anthony"));
});

nyTest('guest cannot access profile', () async {
  // Pruefen, dass nicht authentifiziert
  expectGuest();
});
```

Den Benutzer abmelden:

``` dart
NyTest.logout();
expectGuest();
```

Verwenden Sie `actingAsGuest()` als lesbaren Alias fuer `logout()`, wenn ein Gast-Kontext eingerichtet wird:

``` dart
NyTest.actingAsGuest();
expectGuest();
```

<div id="time-travel"></div>

## Zeitreisen

Manipulieren Sie die Zeit in Ihren Tests mit `NyTime`:

### Zu einem bestimmten Datum springen

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Zur echten Zeit zuruecksetzen
});
```

### Zeit vor- oder zurueckspulen

``` dart
NyTest.travelForward(Duration(days: 30)); // 30 Tage vorspulen
NyTest.travelBackward(Duration(hours: 2)); // 2 Stunden zurueckgehen
```

### Zeit einfrieren

``` dart
NyTest.freezeTime(); // Im aktuellen Moment einfrieren

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Zeit hat sich nicht bewegt

NyTest.travelBack(); // Einfrieren aufheben
```

### Zeitgrenzen

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1. des aktuellen Monats
NyTime.travelToEndOfMonth();   // Letzter Tag des aktuellen Monats
NyTime.travelToStartOfYear();  // 1. Januar
NyTime.travelToEndOfYear();    // 31. Dezember
```

### Zeitreisen mit begrenztem Gueltigkeitsbereich

Code innerhalb eines eingefrorenen Zeitkontexts ausfuehren:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Zeit wird nach dem Callback automatisch wiederhergestellt
```

<div id="api-mocking"></div>

## API-Mocking

<div id="mocking-by-url"></div>

### Mocking nach URL-Muster

API-Antworten mit URL-Mustern und Wildcard-Unterstuetzung mocken:

``` dart
nyTest('mock API responses', () async {
  // Exakte URL-Uebereinstimmung
  NyMockApi.respond('/users/1', {'id': 1, 'name': 'Anthony'});

  // Einzelsegment-Wildcard (*)
  NyMockApi.respond('/users/*', {'id': 1, 'name': 'User'});

  // Mehrsegment-Wildcard (**)
  NyMockApi.respond('/api/**', {'status': 'ok'});

  // Mit Statuscode und Headern
  NyMockApi.respond(
    '/users',
    {'error': 'Unauthorized'},
    statusCode: 401,
    method: 'POST',
    headers: {'X-Error': 'true'},
  );

  // Mit simulierter Verzoegerung
  NyMockApi.respond(
    '/slow-endpoint',
    {'data': 'loaded'},
    delay: Duration(seconds: 2),
  );
});
```

<div id="mocking-by-type"></div>

### Mocking nach API-Service-Typ

Einen gesamten API-Service nach Typ mocken:

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

### Aufrufverlauf und Assertions

API-Aufrufe verfolgen und ueberpruefen:

``` dart
nyTest('verify API was called', () async {
  NyMockApi.setRecordCalls(true);

  // ... Aktionen durchfuehren, die API-Aufrufe ausloesen ...

  // Behaupten, dass Endpunkt aufgerufen wurde
  expectApiCalled('/users');

  // Behaupten, dass Endpunkt nicht aufgerufen wurde
  expectApiNotCalled('/admin');

  // Anzahl der Aufrufe behaupten
  expectApiCalled('/users', times: 2);

  // Bestimmte Methode behaupten
  expectApiCalled('/users', method: 'POST');

  // Aufrufdetails abrufen
  List<ApiCallInfo> calls = NyMockApi.getCallsFor('/users');
});
```

Behaupten, dass ein Endpunkt mit bestimmten Anfragedaten aufgerufen wurde:

``` dart
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
```

### Mock-Antworten erstellen

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

### Factories definieren

Definieren Sie, wie Testinstanzen Ihrer Modelle erstellt werden:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

Mit Unterstuetzung fuer Ueberschreibungen:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### Factory-Zustaende

Variationen einer Factory definieren:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### Instanzen erstellen

``` dart
// Eine einzelne Instanz erstellen
User user = NyFactory.make<User>();

// Mit Ueberschreibungen erstellen
User admin = NyFactory.make<User>(overrides: {'name': 'Admin User'});

// Mit angewendeten Zustaenden erstellen
User premiumAdmin = NyFactory.make<User>(states: ['admin', 'premium']);

// Mehrere Instanzen erstellen
List<User> users = NyFactory.create<User>(count: 5);

// Eine Sequenz mit indexbasierter Datenerstellung
List<User> numbered = NyFactory.sequence<User>(3, (int index, NyFaker faker) {
  return User(name: "User ${index + 1}", email: faker.email());
});
```

<div id="ny-faker"></div>

## NyFaker

`NyFaker` generiert realistische Testdaten. Es ist innerhalb von Factory-Definitionen verfuegbar und kann auch direkt instanziiert werden.

``` dart
NyFaker faker = NyFaker();
```

### Verfuegbare Methoden

| Kategorie | Methode | Rueckgabetyp | Beschreibung |
|-----------|---------|-------------|-------------|
| **Namen** | `faker.firstName()` | `String` | Zufaelliger Vorname |
| | `faker.lastName()` | `String` | Zufaelliger Nachname |
| | `faker.name()` | `String` | Vollstaendiger Name (Vor- + Nachname) |
| | `faker.username()` | `String` | Benutzername |
| **Kontakt** | `faker.email()` | `String` | E-Mail-Adresse |
| | `faker.phone()` | `String` | Telefonnummer |
| | `faker.company()` | `String` | Firmenname |
| **Zahlen** | `faker.randomInt(min, max)` | `int` | Zufaellige Ganzzahl im Bereich |
| | `faker.randomDouble(min, max)` | `double` | Zufaellige Gleitkommazahl im Bereich |
| | `faker.randomBool()` | `bool` | Zufaelliger Wahrheitswert |
| **Bezeichner** | `faker.uuid()` | `String` | UUID-v4-String |
| **Datum** | `faker.date()` | `DateTime` | Zufaelliges Datum |
| | `faker.pastDate()` | `DateTime` | Datum in der Vergangenheit |
| | `faker.futureDate()` | `DateTime` | Datum in der Zukunft |
| **Text** | `faker.lorem()` | `String` | Lorem-ipsum-Woerter |
| | `faker.sentences()` | `String` | Mehrere Saetze |
| | `faker.paragraphs()` | `String` | Mehrere Absaetze |
| | `faker.slug()` | `String` | URL-Slug |
| **Web** | `faker.url()` | `String` | URL-String |
| | `faker.imageUrl()` | `String` | Bild-URL (via picsum.photos) |
| | `faker.ipAddress()` | `String` | IPv4-Adresse |
| | `faker.macAddress()` | `String` | MAC-Adresse |
| **Standort** | `faker.address()` | `String` | Strassenadresse |
| | `faker.city()` | `String` | Stadtname |
| | `faker.state()` | `String` | US-Bundesstaatabkuerzung |
| | `faker.zipCode()` | `String` | Postleitzahl |
| | `faker.country()` | `String` | Laendername |
| **Sonstiges** | `faker.hexColor()` | `String` | Hex-Farbcode |
| | `faker.creditCardNumber()` | `String` | Kreditkartennummer |
| | `faker.randomElement(list)` | `T` | Zufaelliges Element aus einer Liste |
| | `faker.randomElements(list, count)` | `List<T>` | Zufaellige Elemente aus einer Liste |

<div id="test-cache"></div>

## Test-Cache

`NyTestCache` bietet einen In-Memory-Cache zum Testen von Cache-bezogener Funktionalitaet:

``` dart
nyTest('cache operations', () async {
  NyTestCache cache = NyTest.cache;

  // Einen Wert speichern
  await cache.put<String>("key", "value");

  // Mit Ablaufzeit speichern
  await cache.put<String>("temp", "data", seconds: 60);

  // Einen Wert lesen
  String? value = await cache.get<String>("key");

  // Existenz pruefen
  bool exists = await cache.has("key");

  // Einen Schluessel loeschen
  await cache.clear("key");

  // Alles leeren
  await cache.flush();

  // Cache-Informationen abrufen
  int size = await cache.size();
  List<String> keys = await cache.documents();
});
```

<div id="platform-channel-mocking"></div>

## Platform-Channel-Mocking

`NyMockChannels` mockt automatisch gaengige Platform-Channels, damit Tests nicht abstuerzen:

``` dart
void main() {
  NyTest.init(); // Richtet Mock-Kanaele automatisch ein

  // Oder manuell einrichten
  NyMockChannels.setup();
}
```

### Gemockte Channels

- **path_provider** -- Dokumente, temporaere Dateien, Application-Support, Bibliothek und Cache-Verzeichnisse
- **flutter_secure_storage** -- In-Memory-Secure-Storage
- **flutter_timezone** -- Zeitzonendaten
- **flutter_local_notifications** -- Benachrichtigungskanal
- **sqflite** -- Datenbankoperationen

### Pfade ueberschreiben

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### Secure Storage in Tests

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## Route-Guard-Mocking

`NyMockRouteGuard` ermoeglicht es Ihnen, das Verhalten von Route Guards ohne echte Authentifizierung oder Netzwerkaufrufe zu testen. Es erweitert `NyRouteGuard` und bietet Factory-Konstruktoren fuer gaengige Szenarien.

### Guard, der immer durchlaesst

``` dart
final guard = NyMockRouteGuard.pass();
```

### Guard, der umleitet

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// Mit zusaetzlichen Daten
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Guard mit benutzerdefinierter Logik

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // Navigation abbrechen
  }
  return GuardResult.next; // Navigation erlauben
});
```

### Guard-Aufrufe verfolgen

Nachdem ein Guard aufgerufen wurde, koennen Sie seinen Zustand ueberpruefen:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Den RouteContext des letzten Aufrufs abrufen
RouteContext? context = guard.lastContext;

// Verfolgung zuruecksetzen
guard.reset();
```

<div id="assertions"></div>

## Assertions

{{ config('app.name') }} stellt benutzerdefinierte Assertion-Funktionen bereit:

### Routen-Assertions

``` dart
expectRoute('/home');           // Aktuelle Route behaupten
expectNotRoute('/login');       // Behaupten, nicht auf Route zu sein
expectRouteInHistory('/home');  // Behaupten, Route wurde besucht
expectRouteExists('/profile');  // Behaupten, Route ist registriert
expectRoutesExist(['/home', '/profile', '/settings']);
```

### Zustands-Assertions

``` dart
expectBackpackContains("key");                        // Schluessel existiert
expectBackpackContains("key", value: "expected");     // Schluessel hat Wert
expectBackpackNotContains("key");                     // Schluessel existiert nicht
```

### Auth-Assertions

``` dart
expectAuthenticated<User>();  // Benutzer ist authentifiziert
expectGuest();                // Kein Benutzer authentifiziert
```

### Umgebungs-Assertions

``` dart
expectEnv("APP_NAME", "MyApp");  // Umgebungsvariable ist gleich Wert
expectEnvSet("APP_KEY");          // Umgebungsvariable ist gesetzt
```

### Modus-Assertions

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### API-Assertions

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
```

### Widget-Assertions

``` dart
// Behaupten, dass ein Widget-Typ eine bestimmte Anzahl von Malen erscheint
expectWidgetCount(ListTile, 3);
expectWidgetCount(Icon, 0);

// Behaupten, dass Text sichtbar ist
expectTextVisible('Welcome');

// Behaupten, dass Text nicht sichtbar ist
expectTextNotVisible('Error');

// Behaupten, dass ein Widget sichtbar ist (beliebigen Finder verwenden)
expectVisible(find.byType(FloatingActionButton));
expectVisible(find.byIcon(Icons.notifications));
expectVisible(find.byKey(Key('submit_btn')));

// Behaupten, dass ein Widget nicht sichtbar ist
expectNotVisible(find.byType(ErrorBanner));
expectNotVisible(find.byKey(Key('loading_spinner')));
```

### Locale-Assertions

``` dart
expectLocale("en");
```

### Toast-Assertions

Ueberpruefen Sie Toast-Benachrichtigungen, die waehrend eines Tests aufgezeichnet wurden. Erfordert `NyToastRecorder.setup()` in Ihrem Test-setUp:

``` dart
setUp(() {
  NyToastRecorder.setup();
});

nyWidgetTest('shows success toast', (tester) async {
  await tester.pumpNyWidget(MyPage());
  // ... Aktion ausloesen, die einen Toast anzeigt ...

  expectToastShown(id: 'success');
  expectToastShown(id: 'danger', description: 'Something went wrong');
  expectNoToastShown(id: 'info');
});
```

**NyToastRecorder** verfolgt Toast-Benachrichtigungen waehrend der Tests:

``` dart
// Einen Toast manuell aufzeichnen
NyToastRecorder.record(id: 'success', title: 'Done', description: 'Saved!');

// Pruefen, ob ein Toast angezeigt wurde
bool shown = NyToastRecorder.wasShown(id: 'success');

// Alle aufgezeichneten Toasts abrufen
List<ToastRecord> toasts = NyToastRecorder.records;

// Aufgezeichnete Toasts loeschen
NyToastRecorder.clear();
```

### Sperr- und Lade-Assertions

Ueberpruefen Sie benannte Sperr- und Ladezustaende in `NyPage`/`NyState`-Widgets:

``` dart
// Behaupten, dass eine benannte Sperre gehalten wird
expectLocked(tester, find.byType(MyPage), 'submit');

// Behaupten, dass eine benannte Sperre nicht gehalten wird
expectNotLocked(tester, find.byType(MyPage), 'submit');

// Behaupten, dass ein benannter Ladeschluessel aktiv ist
expectLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');

// Behaupten, dass ein benannter Ladeschluessel nicht aktiv ist
expectNotLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');
```

<div id="custom-matchers"></div>

## Benutzerdefinierte Matcher

Benutzerdefinierte Matcher mit `expect()` verwenden:

``` dart
// Typ-Matcher
expect(result, isType<User>());

// Routenname-Matcher
expect(widget, hasRouteName('/home'));

// Backpack-Matcher
expect(true, backpackHas("key", value: "expected"));

// API-Aufruf-Matcher
expect(true, apiWasCalled('/users', method: 'GET', times: 1));
```

<div id="state-testing"></div>

## State-Testing

Testen Sie EventBus-gesteuerte Zustandsverwaltung in `NyPage`- und `NyState`-Widgets mit State-Test-Hilfsfunktionen.

### State-Updates ausloesen

Simulieren Sie State-Updates, die normalerweise von einem anderen Widget oder Controller kommen wuerden:

``` dart
// Ein UpdateState-Ereignis ausloesen
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### State-Actions ausloesen

Senden Sie State-Actions, die von `whenStateAction()` in Ihrer Seite verarbeitet werden:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// Mit zusaetzlichen Daten
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### State-Assertions

``` dart
// Behaupten, dass ein State-Update ausgeloest wurde
expectStateUpdated('HomePageState');
expectStateUpdated('HomePageState', times: 2);

// Behaupten, dass eine State-Action ausgeloest wurde
expectStateAction('HomePageState', 'refresh-page');
expectStateAction('CartState', 'add-item', times: 1);

// Behaupten, dass stateData eines NyPage/NyState-Widgets einen Wert hat
expectStateData(tester, find.byType(MyWidget), equals(42));
```

### NyStateTestHelpers

Verfolgen und ueberpruefen Sie ausgeloeste State-Updates und Actions:

``` dart
// Alle Updates abrufen, die an einen State gesendet wurden
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Alle Actions abrufen, die an einen State gesendet wurden
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Alle verfolgten State-Updates und Actions zuruecksetzen
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## Debugging

### dump

Den aktuellen Testzustand ausgeben (Backpack-Inhalte, Auth-Benutzer, Zeit, API-Aufrufe, Locale):

``` dart
NyTest.dump();
```

### dd (Dump and Die)

Den Testzustand ausgeben und den Test sofort beenden:

``` dart
NyTest.dd();
```

### Testzustandsspeicher

Werte waehrend eines Tests speichern und abrufen:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Backpack mit Testdaten befuellen

Backpack mit Testdaten vorab befuellen:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="nav-interaction"></div>

## Navigation- und Interaktions-Helfer

`WidgetTester`-Erweiterungen bieten eine High-Level-DSL fuer das Schreiben von Navigationsablaeufen und UI-Interaktionen in `nyWidgetTest`.

### visit

Zu einer Route navigieren und warten, bis die Seite stabil ist:

``` dart
nyWidgetTest('laedt Dashboard', (tester) async {
  await tester.visit(DashboardPage.path);
  expectTextVisible('Dashboard');
});
```

### assertNavigatedTo

Behaupten, dass eine Navigationsaktion Sie zur erwarteten Route gefuehrt hat:

``` dart
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);
```

### assertOnRoute

Behaupten, dass die aktuelle Route mit der angegebenen Route uebereinstimmt (verwenden Sie dies, um zu bestaetigen, wo Sie sich befinden, nicht dass Sie gerade navigiert haben):

``` dart
await tester.visit(DashboardPage.path);
tester.assertOnRoute(DashboardPage.path);
```

### settle

Warten, bis alle ausstehenden Animationen und Frame-Callbacks abgeschlossen sind:

``` dart
await tester.tap(find.byType(MyButton));
await tester.settle();
tester.assertNavigatedTo(ProfilePage.path);
```

### navigateBack

Die aktuelle Route verlassen und warten, bis es stabil ist:

``` dart
await tester.visit(DashboardPage.path);
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);

await tester.navigateBack();
tester.assertOnRoute(DashboardPage.path);
```

### tapText

Ein Widget per Text finden, antippen und in einem Aufruf warten:

``` dart
await tester.tapText('Login');
await tester.tapText('Submit');
```

### fillField

Ein Formularfeld antippen, Text eingeben und warten:

``` dart
await tester.fillField(find.byKey(Key('email')), 'test@example.com');
await tester.fillField(find.byKey(Key('password')), 'secret123');
```

### scrollTo

Scrollen, bis ein Widget sichtbar ist, dann warten:

``` dart
await tester.scrollTo(find.text('Item 50'));
await tester.tapText('Item 50');
```

Einen spezifischen `scrollable`-Finder und `delta` fuer praezise Steuerung uebergeben:

``` dart
await tester.scrollTo(
  find.text('Footer'),
  scrollable: find.byKey(Key('main_list')),
  delta: 200,
);
```

<div id="examples"></div>

## Beispiele

### Vollstaendige Testdatei

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

      // ... API-Aufruf ausloesen ...

      expectApiCalled('/api/users');
    });
  });

  nyGroup('Time-Sensitive Features', () {
    nyTest('subscription expires correctly', () async {
      NyTest.travel(DateTime(2025, 1, 1));

      // Abonnement-Logik zu einem bekannten Datum testen
      expect(NyTime.now().year, equals(2025));

      NyTest.travelForward(Duration(days: 365));
      expect(NyTime.now().year, equals(2026));

      NyTest.travelBack();
    });
  });
}
```
