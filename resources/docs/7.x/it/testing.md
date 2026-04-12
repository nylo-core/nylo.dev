# Testing

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Per Iniziare](#getting-started "Per Iniziare")
- [Scrivere i Test](#writing-tests "Scrivere i Test")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [Utilita' di Test dei Widget](#widget-testing-utilities "Utilita' di Test dei Widget")
  - [nyGroup](#ny-group "nyGroup")
  - [Ciclo di Vita dei Test](#test-lifecycle "Ciclo di Vita dei Test")
  - [Test da Saltare e Test CI](#skipping-tests "Test da Saltare e Test CI")
- [Autenticazione](#authentication "Autenticazione")
- [Viaggio nel Tempo](#time-travel "Viaggio nel Tempo")
- [Mock delle API](#api-mocking "Mock delle API")
  - [Mock per Pattern URL](#mocking-by-url "Mock per Pattern URL")
  - [Mock per Tipo di Servizio API](#mocking-by-type "Mock per Tipo di Servizio API")
  - [Cronologia delle Chiamate e Asserzioni](#call-history "Cronologia delle Chiamate e Asserzioni")
- [Factory](#factories "Factory")
  - [Definire le Factory](#defining-factories "Definire le Factory")
  - [Stati delle Factory](#factory-states "Stati delle Factory")
  - [Creare Istanze](#creating-instances "Creare Istanze")
- [NyFaker](#ny-faker "NyFaker")
- [Cache di Test](#test-cache "Cache di Test")
- [Mock dei Platform Channel](#platform-channel-mocking "Mock dei Platform Channel")
- [Mock dei Route Guard](#route-guard-mocking "Mock dei Route Guard")
- [Asserzioni](#assertions "Asserzioni")
- [Matcher Personalizzati](#custom-matchers "Matcher Personalizzati")
- [Test dello Stato](#state-testing "Test dello Stato")
- [Debug](#debugging "Debug")
- [Navigazione e Helper di Interazione](#nav-interaction "Navigazione e Helper di Interazione")
- [Esempi](#examples "Esempi")

<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} v7 include un framework di testing completo ispirato alle utility di testing di Laravel. Fornisce:

- **Funzioni di test** con setup/teardown automatico (`nyTest`, `nyWidgetTest`, `nyGroup`)
- **Simulazione dell'autenticazione** tramite `NyTest.actingAs<T>()`
- **Viaggio nel tempo** per congelare o manipolare il tempo nei test
- **Mock delle API** con corrispondenza di pattern URL e tracciamento delle chiamate
- **Factory** con un generatore di dati fittizi integrato (`NyFaker`)
- **Mock dei platform channel** per secure storage, path provider e altro
- **Asserzioni personalizzate** per route, Backpack, autenticazione e ambiente

<div id="getting-started"></div>

## Per Iniziare

Inizializza il framework di testing all'inizio del tuo file di test:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` configura l'ambiente di test e abilita il reset automatico dello stato tra i test quando `autoReset: true` (il valore predefinito).

<div id="writing-tests"></div>

## Scrivere i Test

<div id="ny-test"></div>

### nyTest

La funzione principale per scrivere i test:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

Opzioni:

``` dart
nyTest('my test', () async {
  // corpo del test
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

Per testare i widget Flutter con un `WidgetTester`:

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

### Utilita' di Test dei Widget

La classe `NyWidgetTest` e le estensioni `WidgetTester` forniscono helper per pompare widget Nylo con il supporto del tema corretto, attendere il completamento di `init()` e testare gli stati di caricamento.

#### Configurare l'Ambiente di Test

Chiama `NyWidgetTest.configure()` nel tuo `setUpAll` per disabilitare il recupero di Google Fonts e opzionalmente impostare un tema personalizzato:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

Puoi resettare la configurazione con `NyWidgetTest.reset()`.

Due temi integrati sono disponibili per il testing senza font:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Pompare Widget Nylo

Usa `pumpNyWidget` per avvolgere un widget in un `MaterialApp` con supporto del tema:

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

Per un pompaggio rapido con un tema senza font:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Attendere l'Inizializzazione

`pumpNyWidgetAndWaitForInit` pompa frame finche' gli indicatori di caricamento scompaiono (o fino al timeout), utile per le pagine con metodi `init()` asincroni:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() e' completato
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Helper di Pompaggio

``` dart
// Pompa frame finche' un widget specifico appare
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Stabilizza senza eccezioni (non genera eccezione al timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Simulazione del Ciclo di Vita

Simula cambiamenti di `AppLifecycleState` su qualsiasi `NyPage` nell'albero dei widget:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Verifica gli effetti collaterali dell'azione di ciclo di vita in pausa
```

#### Controlli di Caricamento e Blocco

Controlla le chiavi di caricamento nominate e i blocchi sui widget `NyPage`/`NyState`:

``` dart
// Controlla se una chiave di caricamento nominata e' attiva
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Controlla se un blocco nominato e' attivo
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Controlla la presenza di qualsiasi indicatore di caricamento (CircularProgressIndicator o Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### Helper testNyPage

Una funzione pratica che pompa un `NyPage`, attende l'init, poi esegue le tue aspettative:

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

Testa che una pagina mostri un indicatore di caricamento durante `init()`:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

Un mixin che fornisce utilita' comuni per il test delle pagine:

``` dart
class HomePageTest with NyPageTestMixin {
  void runTests(WidgetTester tester) async {
    // Verifica che init sia stato chiamato e che il caricamento sia completato
    await verifyInitCalled(tester, HomePage(), timeout: Duration(seconds: 5));

    // Verifica che lo stato di caricamento sia mostrato durante init
    await verifyLoadingState(tester, HomePage());
  }
}
```

<div id="ny-group"></div>

### nyGroup

Raggruppa test correlati insieme:

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

### Ciclo di Vita dei Test

Configura la logica di setup e teardown usando gli hook del ciclo di vita:

``` dart
void main() {
  NyTest.init();

  nySetUpAll(() {
    // Eseguito una volta prima di tutti i test
  });

  nySetUp(() {
    // Eseguito prima di ogni test
  });

  nyTearDown(() {
    // Eseguito dopo ogni test
  });

  nyTearDownAll(() {
    // Eseguito una volta dopo tutti i test
  });
}
```

<div id="skipping-tests"></div>

### Test da Saltare e Test CI

``` dart
// Salta un test con una motivazione
nySkip('not implemented yet', () async {
  // ...
}, "Waiting for API update");

// Test previsti per fallire
nyFailing('known bug', () async {
  // ...
});

// Test solo per CI (contrassegnati con 'ci')
nyCi('integration test', () async {
  // Eseguiti solo in ambienti CI
});
```

<div id="authentication"></div>

## Autenticazione

Simula utenti autenticati nei test:

``` dart
nyTest('user can access profile', () async {
  // Simula un utente autenticato
  NyTest.actingAs<User>(User(name: "Anthony", email: "anthony@example.com"));

  // Verifica l'autenticazione
  expectAuthenticated<User>();

  // Accedi all'utente corrente
  User? user = NyTest.actingUser<User>();
  expect(user?.name, equals("Anthony"));
});

nyTest('guest cannot access profile', () async {
  // Verifica che l'utente non sia autenticato
  expectGuest();
});
```

Effettua il logout dell'utente:

``` dart
NyTest.logout();
expectGuest();
```

Usa `actingAsGuest()` come alias leggibile di `logout()` quando si imposta un contesto guest:

``` dart
NyTest.actingAsGuest();
expectGuest();
```

<div id="time-travel"></div>

## Viaggio nel Tempo

Manipola il tempo nei tuoi test usando `NyTime`:

### Saltare a una Data Specifica

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Torna al tempo reale
});
```

### Avanzare o Riavvolgere il Tempo

``` dart
NyTest.travelForward(Duration(days: 30)); // Salta 30 giorni avanti
NyTest.travelBackward(Duration(hours: 2)); // Torna indietro di 2 ore
```

### Congelare il Tempo

``` dart
NyTest.freezeTime(); // Congela il momento corrente

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Il tempo non e' avanzato

NyTest.travelBack(); // Scongela
```

### Limiti Temporali

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1° del mese corrente
NyTime.travelToEndOfMonth();   // Ultimo giorno del mese corrente
NyTime.travelToStartOfYear();  // 1° gennaio
NyTime.travelToEndOfYear();    // 31 dicembre
```

### Viaggio nel Tempo con Scope

Esegui codice all'interno di un contesto temporale congelato:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Il tempo viene ripristinato automaticamente dopo il callback
```

<div id="api-mocking"></div>

## Mock delle API

<div id="mocking-by-url"></div>

### Mock per Pattern URL

Simula le risposte API usando pattern URL con supporto per i caratteri jolly:

``` dart
nyTest('mock API responses', () async {
  // Corrispondenza esatta dell'URL
  NyMockApi.respond('/users/1', {'id': 1, 'name': 'Anthony'});

  // Carattere jolly per un singolo segmento (*)
  NyMockApi.respond('/users/*', {'id': 1, 'name': 'User'});

  // Carattere jolly per piu' segmenti (**)
  NyMockApi.respond('/api/**', {'status': 'ok'});

  // Con codice di stato e intestazioni
  NyMockApi.respond(
    '/users',
    {'error': 'Unauthorized'},
    statusCode: 401,
    method: 'POST',
    headers: {'X-Error': 'true'},
  );

  // Con ritardo simulato
  NyMockApi.respond(
    '/slow-endpoint',
    {'data': 'loaded'},
    delay: Duration(seconds: 2),
  );
});
```

<div id="mocking-by-type"></div>

### Mock per Tipo di Servizio API

Simula un intero servizio API per tipo:

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

### Cronologia delle Chiamate e Asserzioni

Traccia e verifica le chiamate API:

``` dart
nyTest('verify API was called', () async {
  NyMockApi.setRecordCalls(true);

  // ... esegui azioni che attivano chiamate API ...

  // Verifica che l'endpoint sia stato chiamato
  expectApiCalled('/users');

  // Verifica che l'endpoint non sia stato chiamato
  expectApiNotCalled('/admin');

  // Verifica il numero di chiamate
  expectApiCalled('/users', times: 2);

  // Verifica il metodo specifico
  expectApiCalled('/users', method: 'POST');

  // Ottieni i dettagli delle chiamate
  List<ApiCallInfo> calls = NyMockApi.getCallsFor('/users');
});
```

Verifica che un endpoint sia stato chiamato con specifici dati nel corpo della richiesta:

``` dart
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
```

### Creare Risposte Mock

``` dart
Response<Map<String, dynamic>> response = NyMockApi.createResponse(
  data: {'id': 1, 'name': 'Anthony'},
  statusCode: 200,
  statusMessage: 'OK',
);
```

<div id="factories"></div>

## Factory

<div id="defining-factories"></div>

### Definire le Factory

Definisci come creare istanze di test dei tuoi modelli:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

Con supporto per gli override:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### Stati delle Factory

Definisci variazioni di una factory:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### Creare Istanze

``` dart
// Crea una singola istanza
User user = NyFactory.make<User>();

// Crea con override
User admin = NyFactory.make<User>(overrides: {'name': 'Admin User'});

// Crea con stati applicati
User premiumAdmin = NyFactory.make<User>(states: ['admin', 'premium']);

// Crea piu' istanze
List<User> users = NyFactory.create<User>(count: 5);

// Crea una sequenza con dati basati sull'indice
List<User> numbered = NyFactory.sequence<User>(3, (int index, NyFaker faker) {
  return User(name: "User ${index + 1}", email: faker.email());
});
```

<div id="ny-faker"></div>

## NyFaker

`NyFaker` genera dati fittizi realistici per i test. E' disponibile all'interno delle definizioni delle factory e puo' essere istanziato direttamente.

``` dart
NyFaker faker = NyFaker();
```

### Metodi Disponibili

| Categoria | Metodo | Tipo di Ritorno | Descrizione |
|----------|--------|-------------|-------------|
| **Nomi** | `faker.firstName()` | `String` | Nome casuale |
| | `faker.lastName()` | `String` | Cognome casuale |
| | `faker.name()` | `String` | Nome completo (nome + cognome) |
| | `faker.username()` | `String` | Stringa username |
| **Contatti** | `faker.email()` | `String` | Indirizzo email |
| | `faker.phone()` | `String` | Numero di telefono |
| | `faker.company()` | `String` | Nome dell'azienda |
| **Numeri** | `faker.randomInt(min, max)` | `int` | Intero casuale nell'intervallo |
| | `faker.randomDouble(min, max)` | `double` | Double casuale nell'intervallo |
| | `faker.randomBool()` | `bool` | Booleano casuale |
| **Identificativi** | `faker.uuid()` | `String` | Stringa UUID v4 |
| **Date** | `faker.date()` | `DateTime` | Data casuale |
| | `faker.pastDate()` | `DateTime` | Data nel passato |
| | `faker.futureDate()` | `DateTime` | Data nel futuro |
| **Testo** | `faker.lorem()` | `String` | Parole lorem ipsum |
| | `faker.sentences()` | `String` | Frasi multiple |
| | `faker.paragraphs()` | `String` | Paragrafi multipli |
| | `faker.slug()` | `String` | Slug URL |
| **Web** | `faker.url()` | `String` | Stringa URL |
| | `faker.imageUrl()` | `String` | URL immagine (tramite picsum.photos) |
| | `faker.ipAddress()` | `String` | Indirizzo IPv4 |
| | `faker.macAddress()` | `String` | Indirizzo MAC |
| **Localita'** | `faker.address()` | `String` | Indirizzo stradale |
| | `faker.city()` | `String` | Nome della citta' |
| | `faker.state()` | `String` | Abbreviazione dello stato USA |
| | `faker.zipCode()` | `String` | Codice postale |
| | `faker.country()` | `String` | Nome del paese |
| **Altro** | `faker.hexColor()` | `String` | Codice colore esadecimale |
| | `faker.creditCardNumber()` | `String` | Numero di carta di credito |
| | `faker.randomElement(list)` | `T` | Elemento casuale dalla lista |
| | `faker.randomElements(list, count)` | `List<T>` | Elementi casuali dalla lista |

<div id="test-cache"></div>

## Cache di Test

`NyTestCache` fornisce una cache in memoria per testare funzionalita' legate alla cache:

``` dart
nyTest('cache operations', () async {
  NyTestCache cache = NyTest.cache;

  // Salva un valore
  await cache.put<String>("key", "value");

  // Salva con scadenza
  await cache.put<String>("temp", "data", seconds: 60);

  // Leggi un valore
  String? value = await cache.get<String>("key");

  // Controlla l'esistenza
  bool exists = await cache.has("key");

  // Cancella una chiave
  await cache.clear("key");

  // Svuota tutto
  await cache.flush();

  // Ottieni informazioni sulla cache
  int size = await cache.size();
  List<String> keys = await cache.documents();
});
```

<div id="platform-channel-mocking"></div>

## Mock dei Platform Channel

`NyMockChannels` simula automaticamente i platform channel comuni in modo che i test non vadano in errore:

``` dart
void main() {
  NyTest.init(); // Configura automaticamente i canali mock

  // Oppure configura manualmente
  NyMockChannels.setup();
}
```

### Channel Simulati

- **path_provider** -- directory documenti, temporanee, supporto applicazione, libreria e cache
- **flutter_secure_storage** -- secure storage in memoria
- **flutter_timezone** -- dati del fuso orario
- **flutter_local_notifications** -- canale notifiche
- **sqflite** -- operazioni database

### Sovrascrivere i Percorsi

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### Secure Storage nei Test

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## Mock dei Route Guard

`NyMockRouteGuard` consente di testare il comportamento dei route guard senza autenticazione reale o chiamate di rete. Estende `NyRouteGuard` e fornisce costruttori factory per scenari comuni.

### Guard che Passa Sempre

``` dart
final guard = NyMockRouteGuard.pass();
```

### Guard che Reindirizza

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// Con dati aggiuntivi
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Guard con Logica Personalizzata

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // interrompe la navigazione
  }
  return GuardResult.next; // consente la navigazione
});
```

### Tracciamento delle Chiamate del Guard

Dopo che un guard e' stato invocato, puoi ispezionare il suo stato:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Accedi al RouteContext dell'ultima chiamata
RouteContext? context = guard.lastContext;

// Reimposta il tracciamento
guard.reset();
```

<div id="assertions"></div>

## Asserzioni

{{ config('app.name') }} fornisce funzioni di asserzione personalizzate:

### Asserzioni sulle Route

``` dart
expectRoute('/home');           // Verifica la rotta corrente
expectNotRoute('/login');       // Verifica che non si e' sulla rotta
expectRouteInHistory('/home');  // Verifica che la rotta e' stata visitata
expectRouteExists('/profile');  // Verifica che la rotta e' registrata
expectRoutesExist(['/home', '/profile', '/settings']);
```

### Asserzioni sullo Stato

``` dart
expectBackpackContains("key");                        // La chiave esiste
expectBackpackContains("key", value: "expected");     // La chiave ha il valore
expectBackpackNotContains("key");                     // La chiave non esiste
```

### Asserzioni sull'Autenticazione

``` dart
expectAuthenticated<User>();  // L'utente e' autenticato
expectGuest();                // Nessun utente autenticato
```

### Asserzioni sull'Ambiente

``` dart
expectEnv("APP_NAME", "MyApp");  // La variabile d'ambiente e' uguale al valore
expectEnvSet("APP_KEY");          // La variabile d'ambiente e' impostata
```

### Asserzioni sulla Modalita'

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### Asserzioni sulle API

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
```

### Asserzioni sui Widget

``` dart
// Verifica che un tipo di widget appaia un numero specifico di volte
expectWidgetCount(ListTile, 3);
expectWidgetCount(Icon, 0);

// Verifica che il testo sia visibile
expectTextVisible('Welcome');

// Verifica che il testo non sia visibile
expectTextNotVisible('Error');

// Verifica che qualsiasi widget sia visibile (usa qualsiasi Finder)
expectVisible(find.byType(FloatingActionButton));
expectVisible(find.byIcon(Icons.notifications));
expectVisible(find.byKey(Key('submit_btn')));

// Verifica che un widget non sia visibile
expectNotVisible(find.byType(ErrorBanner));
expectNotVisible(find.byKey(Key('loading_spinner')));
```

### Asserzioni sulla Lingua

``` dart
expectLocale("en");
```

### Asserzioni sui Toast

Verifica le notifiche toast registrate durante un test. Richiede `NyToastRecorder.setup()` nel setUp del test:

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

**NyToastRecorder** traccia le notifiche toast durante i test:

``` dart
// Registra un toast manualmente
NyToastRecorder.record(id: 'success', title: 'Done', description: 'Saved!');

// Controlla se un toast e' stato mostrato
bool shown = NyToastRecorder.wasShown(id: 'success');

// Accedi a tutti i toast registrati
List<ToastRecord> toasts = NyToastRecorder.records;

// Cancella i toast registrati
NyToastRecorder.clear();
```

### Asserzioni di Blocco e Caricamento

Verifica gli stati di blocco e caricamento nominati nei widget `NyPage`/`NyState`:

``` dart
// Verifica che un blocco nominato sia attivo
expectLocked(tester, find.byType(MyPage), 'submit');

// Verifica che un blocco nominato non sia attivo
expectNotLocked(tester, find.byType(MyPage), 'submit');

// Verifica che una chiave di caricamento nominata sia attiva
expectLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');

// Verifica che una chiave di caricamento nominata non sia attiva
expectNotLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');
```

<div id="custom-matchers"></div>

## Matcher Personalizzati

Usa matcher personalizzati con `expect()`:

``` dart
// Matcher di tipo
expect(result, isType<User>());

// Matcher del nome della rotta
expect(widget, hasRouteName('/home'));

// Matcher di Backpack
expect(true, backpackHas("key", value: "expected"));

// Matcher di chiamata API
expect(true, apiWasCalled('/users', method: 'GET', times: 1));
```

<div id="state-testing"></div>

## Test dello Stato

Testa la gestione dello stato guidata da EventBus nei widget `NyPage` e `NyState` usando gli helper di test dello stato.

### Emissione di Aggiornamenti di Stato

Simula aggiornamenti di stato che normalmente provengono da un altro widget o controller:

``` dart
// Emetti un evento UpdateState
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### Emissione di Azioni di Stato

Invia azioni di stato gestite da `whenStateAction()` nella tua pagina:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// Con dati aggiuntivi
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### Asserzioni sullo Stato

``` dart
// Verifica che un aggiornamento di stato sia stato emesso
expectStateUpdated('HomePageState');
expectStateUpdated('HomePageState', times: 2);

// Verifica che un'azione di stato sia stata emessa
expectStateAction('HomePageState', 'refresh-page');
expectStateAction('CartState', 'add-item', times: 1);

// Verifica lo stateData di un widget NyPage/NyState
expectStateData(tester, find.byType(MyWidget), equals(42));
```

### NyStateTestHelpers

Traccia e ispeziona gli aggiornamenti e le azioni di stato emessi:

``` dart
// Ottieni tutti gli aggiornamenti emessi verso uno stato
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Ottieni tutte le azioni emesse verso uno stato
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Reimposta tutti gli aggiornamenti e le azioni di stato tracciati
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## Debug

### dump

Stampa lo stato corrente del test (contenuto di Backpack, utente autenticato, tempo, chiamate API, lingua):

``` dart
NyTest.dump();
```

### dd (Dump and Die)

Stampa lo stato del test e termina immediatamente il test:

``` dart
NyTest.dd();
```

### Archiviazione dello Stato del Test

Memorizza e recupera valori durante un test:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Popolare Backpack

Pre-popola Backpack con dati di test:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="nav-interaction"></div>

## Navigazione e Helper di Interazione

Le estensioni `WidgetTester` forniscono un DSL di alto livello per scrivere flussi di navigazione e interazioni UI in `nyWidgetTest`.

### visit

Naviga verso una rotta e attendi che la pagina si stabilizzi:

``` dart
nyWidgetTest('loads dashboard', (tester) async {
  await tester.visit(DashboardPage.path);
  expectTextVisible('Dashboard');
});
```

### assertNavigatedTo

Verifica che un'azione di navigazione ti abbia portato alla rotta attesa:

``` dart
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);
```

### assertOnRoute

Verifica che la rotta corrente corrisponda alla rotta fornita (da usare per confermare dove ti trovi, non che ci sia stata una navigazione):

``` dart
await tester.visit(DashboardPage.path);
tester.assertOnRoute(DashboardPage.path);
```

### settle

Attendi il completamento di tutte le animazioni e callback dei frame in sospeso:

``` dart
await tester.tap(find.byType(MyButton));
await tester.settle();
tester.assertNavigatedTo(ProfilePage.path);
```

### navigateBack

Esegui il pop della rotta corrente e attendi:

``` dart
await tester.visit(DashboardPage.path);
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);

await tester.navigateBack();
tester.assertOnRoute(DashboardPage.path);
```

### tapText

Trova un widget per testo, toccalo e stabilizzalo in una sola chiamata:

``` dart
await tester.tapText('Login');
await tester.tapText('Submit');
```

### fillField

Tocca un campo del form, inserisci testo e stabilizza:

``` dart
await tester.fillField(find.byKey(Key('email')), 'test@example.com');
await tester.fillField(find.byKey(Key('password')), 'secret123');
```

### scrollTo

Scorri finche' un widget e' visibile, poi stabilizza:

``` dart
await tester.scrollTo(find.text('Item 50'));
await tester.tapText('Item 50');
```

Passa un finder `scrollable` specifico e un `delta` per un controllo preciso:

``` dart
await tester.scrollTo(
  find.text('Footer'),
  scrollable: find.byKey(Key('main_list')),
  delta: 200,
);
```

<div id="examples"></div>

## Esempi

### File di Test Completo

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

      // ... attiva la chiamata API ...

      expectApiCalled('/api/users');
    });
  });

  nyGroup('Time-Sensitive Features', () {
    nyTest('subscription expires correctly', () async {
      NyTest.travel(DateTime(2025, 1, 1));

      // Testa la logica della sottoscrizione a una data nota
      expect(NyTime.now().year, equals(2025));

      NyTest.travelForward(Duration(days: 365));
      expect(NyTime.now().year, equals(2026));

      NyTest.travelBack();
    });
  });
}
```
