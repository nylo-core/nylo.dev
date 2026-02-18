# Testing

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Primeiros Passos](#getting-started "Primeiros Passos")
- [Escrevendo Testes](#writing-tests "Escrevendo Testes")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [Utilitários de Teste de Widget](#widget-testing-utilities "Utilitários de Teste de Widget")
  - [nyGroup](#ny-group "nyGroup")
  - [Ciclo de Vida do Teste](#test-lifecycle "Ciclo de Vida do Teste")
  - [Pulando Testes e Testes de CI](#skipping-tests "Pulando Testes e Testes de CI")
- [Autenticação](#authentication "Autenticação")
- [Viagem no Tempo](#time-travel "Viagem no Tempo")
- [Mock de API](#api-mocking "Mock de API")
  - [Mock por Padrão de URL](#mocking-by-url "Mock por Padrão de URL")
  - [Mock por Tipo de Serviço de API](#mocking-by-type "Mock por Tipo de Serviço de API")
  - [Histórico de Chamadas e Asserções](#call-history "Histórico de Chamadas e Asserções")
- [Factories](#factories "Factories")
  - [Definindo Factories](#defining-factories "Definindo Factories")
  - [Estados de Factory](#factory-states "Estados de Factory")
  - [Criando Instâncias](#creating-instances "Criando Instâncias")
- [NyFaker](#ny-faker "NyFaker")
- [Cache de Teste](#test-cache "Cache de Teste")
- [Mock de Platform Channel](#platform-channel-mocking "Mock de Platform Channel")
- [Mock de Route Guard](#route-guard-mocking "Mock de Route Guard")
- [Asserções](#assertions "Asserções")
- [Matchers Personalizados](#custom-matchers "Matchers Personalizados")
- [Teste de Estado](#state-testing "Teste de Estado")
- [Depuração](#debugging "Depuração")
- [Exemplos](#examples "Exemplos Práticos")

<div id="introduction"></div>

## Introdução

{{ config('app.name') }} v7 inclui um framework de testes abrangente inspirado nos utilitários de teste do Laravel. Ele fornece:

- **Funções de teste** com setup/teardown automáticos (`nyTest`, `nyWidgetTest`, `nyGroup`)
- **Simulação de autenticação** via `NyTest.actingAs<T>()`
- **Viagem no tempo** para congelar ou manipular o tempo em testes
- **Mock de API** com correspondência de padrão de URL e rastreamento de chamadas
- **Factories** com um gerador de dados falsos embutido (`NyFaker`)
- **Mock de platform channel** para armazenamento seguro, path provider e mais
- **Asserções personalizadas** para rotas, Backpack, autenticação e ambiente

<div id="getting-started"></div>

## Primeiros Passos

Inicialize o framework de testes no topo do seu arquivo de teste:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` configura o ambiente de teste e habilita o reset automático de estado entre testes quando `autoReset: true` (o padrão).

<div id="writing-tests"></div>

## Escrevendo Testes

<div id="ny-test"></div>

### nyTest

A função principal para escrever testes:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

Opções:

``` dart
nyTest('my test', () async {
  // test body
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

Para testar widgets Flutter com um `WidgetTester`:

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

### Utilitários de Teste de Widget

A classe `NyWidgetTest` e as extensões do `WidgetTester` fornecem helpers para renderizar widgets Nylo com suporte adequado a temas, aguardar a conclusão do `init()` e testar estados de carregamento.

#### Configurando o Ambiente de Teste

Chame `NyWidgetTest.configure()` no seu `setUpAll` para desabilitar a busca de Google Fonts e opcionalmente definir um tema personalizado:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

Você pode redefinir a configuração com `NyWidgetTest.reset()`.

Dois temas embutidos estão disponíveis para testes sem fontes:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Renderizando Widgets Nylo

Use `pumpNyWidget` para envolver um widget em um `MaterialApp` com suporte a temas:

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

Para uma renderização rápida com um tema sem fontes:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Aguardando o Init

`pumpNyWidgetAndWaitForInit` renderiza frames até que os indicadores de carregamento desapareçam (ou o timeout seja atingido), o que é útil para páginas com métodos `init()` assíncronos:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() has completed
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Helpers de Pump

``` dart
// Pump frames until a specific widget appears
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Settle gracefully (won't throw on timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Simulação de Ciclo de Vida

Simule mudanças de `AppLifecycleState` em qualquer `NyPage` na árvore de widgets:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Assert side effects of the paused lifecycle action
```

#### Verificações de Loading e Lock

Verifique chaves de loading nomeadas e locks em widgets `NyPage`/`NyState`:

``` dart
// Check if a named loading key is active
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Check if a named lock is held
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Check for any loading indicator (CircularProgressIndicator or Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### Helper testNyPage

Uma função de conveniência que renderiza um `NyPage`, aguarda o init e então executa suas expectativas:

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

Teste que uma página exibe um indicador de carregamento durante o `init()`:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

Um mixin que fornece utilitários comuns de teste de página:

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

Agrupe testes relacionados:

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

### Ciclo de Vida do Teste

Configure lógica de setup e teardown usando hooks de ciclo de vida:

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

### Pulando Testes e Testes de CI

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

## Autenticação

Simule usuários autenticados em testes:

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

Faça logout do usuário:

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## Viagem no Tempo

Manipule o tempo nos seus testes usando `NyTime`:

### Ir para uma Data Específica

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Reset to real time
});
```

### Avançar ou Retroceder o Tempo

``` dart
NyTest.travelForward(Duration(days: 30)); // Jump 30 days ahead
NyTest.travelBackward(Duration(hours: 2)); // Go back 2 hours
```

### Congelar o Tempo

``` dart
NyTest.freezeTime(); // Freeze at the current moment

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Time hasn't moved

NyTest.travelBack(); // Unfreeze
```

### Limites de Tempo

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1st of current month
NyTime.travelToEndOfMonth();   // Last day of current month
NyTime.travelToStartOfYear();  // Jan 1st
NyTime.travelToEndOfYear();    // Dec 31st
```

### Viagem no Tempo com Escopo

Execute código dentro de um contexto de tempo congelado:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Time is automatically restored after the callback
```

<div id="api-mocking"></div>

## Mock de API

<div id="mocking-by-url"></div>

### Mock por Padrão de URL

Faça mock de respostas de API usando padrões de URL com suporte a wildcards:

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

### Mock por Tipo de Serviço de API

Faça mock de um serviço de API inteiro por tipo:

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

### Histórico de Chamadas e Asserções

Rastreie e verifique chamadas de API:

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

### Criando Respostas Mock

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

### Definindo Factories

Defina como criar instâncias de teste dos seus models:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

Com suporte a override:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### Estados de Factory

Defina variações de uma factory:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### Criando Instâncias

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

`NyFaker` gera dados falsos realistas para testes. Está disponível dentro de definições de factory e pode ser instanciado diretamente.

``` dart
NyFaker faker = NyFaker();
```

### Métodos Disponíveis

| Categoria | Método | Tipo de Retorno | Descrição |
|----------|--------|-------------|-------------|
| **Nomes** | `faker.firstName()` | `String` | Primeiro nome aleatório |
| | `faker.lastName()` | `String` | Sobrenome aleatório |
| | `faker.name()` | `String` | Nome completo (primeiro + último) |
| | `faker.username()` | `String` | String de nome de usuário |
| **Contato** | `faker.email()` | `String` | Endereço de email |
| | `faker.phone()` | `String` | Número de telefone |
| | `faker.company()` | `String` | Nome da empresa |
| **Números** | `faker.randomInt(min, max)` | `int` | Inteiro aleatório no intervalo |
| | `faker.randomDouble(min, max)` | `double` | Double aleatório no intervalo |
| | `faker.randomBool()` | `bool` | Booleano aleatório |
| **Identificadores** | `faker.uuid()` | `String` | String UUID v4 |
| **Datas** | `faker.date()` | `DateTime` | Data aleatória |
| | `faker.pastDate()` | `DateTime` | Data no passado |
| | `faker.futureDate()` | `DateTime` | Data no futuro |
| **Texto** | `faker.lorem()` | `String` | Palavras lorem ipsum |
| | `faker.sentences()` | `String` | Múltiplas frases |
| | `faker.paragraphs()` | `String` | Múltiplos parágrafos |
| | `faker.slug()` | `String` | Slug de URL |
| **Web** | `faker.url()` | `String` | String de URL |
| | `faker.imageUrl()` | `String` | URL de imagem (via picsum.photos) |
| | `faker.ipAddress()` | `String` | Endereço IPv4 |
| | `faker.macAddress()` | `String` | Endereço MAC |
| **Localização** | `faker.address()` | `String` | Endereço |
| | `faker.city()` | `String` | Nome da cidade |
| | `faker.state()` | `String` | Abreviação de estado dos EUA |
| | `faker.zipCode()` | `String` | CEP |
| | `faker.country()` | `String` | Nome do país |
| **Outros** | `faker.hexColor()` | `String` | Código de cor hexadecimal |
| | `faker.creditCardNumber()` | `String` | Número de cartão de crédito |
| | `faker.randomElement(list)` | `T` | Item aleatório da lista |
| | `faker.randomElements(list, count)` | `List<T>` | Itens aleatórios da lista |

<div id="test-cache"></div>

## Cache de Teste

`NyTestCache` fornece um cache em memória para testar funcionalidades relacionadas a cache:

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

## Mock de Platform Channel

`NyMockChannels` faz mock automaticamente de platform channels comuns para que os testes não falhem:

``` dart
void main() {
  NyTest.init(); // Automatically sets up mock channels

  // Or set up manually
  NyMockChannels.setup();
}
```

### Channels com Mock

- **path_provider** -- diretórios de documentos, temporário, suporte de aplicação, biblioteca e cache
- **flutter_secure_storage** -- armazenamento seguro em memória
- **flutter_timezone** -- dados de fuso horário
- **flutter_local_notifications** -- canal de notificação
- **sqflite** -- operações de banco de dados

### Sobrescrever Caminhos

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### Armazenamento Seguro em Testes

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## Mock de Route Guard

`NyMockRouteGuard` permite que você teste o comportamento de route guards sem autenticação real ou chamadas de rede. Ele estende `NyRouteGuard` e fornece construtores factory para cenários comuns.

### Guard que Sempre Passa

``` dart
final guard = NyMockRouteGuard.pass();
```

### Guard que Redireciona

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// With additional data
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Guard com Lógica Personalizada

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // abort navigation
  }
  return GuardResult.next; // allow navigation
});
```

### Rastreando Chamadas do Guard

Após um guard ser invocado, você pode inspecionar seu estado:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Access the RouteContext from the last call
RouteContext? context = guard.lastContext;

// Reset tracking
guard.reset();
```

<div id="assertions"></div>

## Asserções

{{ config('app.name') }} fornece funções de asserção personalizadas:

### Asserções de Rota

``` dart
expectRoute('/home');           // Assert current route
expectNotRoute('/login');       // Assert not on route
expectRouteInHistory('/home');  // Assert route was visited
expectRouteExists('/profile');  // Assert route is registered
expectRoutesExist(['/home', '/profile', '/settings']);
```

### Asserções de Estado

``` dart
expectBackpackContains("key");                        // Key exists
expectBackpackContains("key", value: "expected");     // Key has value
expectBackpackNotContains("key");                     // Key doesn't exist
```

### Asserções de Auth

``` dart
expectAuthenticated<User>();  // User is authenticated
expectGuest();                // No user authenticated
```

### Asserções de Ambiente

``` dart
expectEnv("APP_NAME", "MyApp");  // Env variable equals value
expectEnvSet("APP_KEY");          // Env variable is set
```

### Asserções de Modo

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### Asserções de API

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### Asserções de Locale

``` dart
expectLocale("en");
```

### Asserções de Toast

Faça asserções sobre notificações toast que foram registradas durante um teste. Requer `NyToastRecorder.setup()` no setUp do seu teste:

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

**NyToastRecorder** rastreia notificações toast durante testes:

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

### Asserções de Lock e Loading

Faça asserções sobre estados de lock e loading nomeados em widgets `NyPage`/`NyState`:

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

## Matchers Personalizados

Use matchers personalizados com `expect()`:

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

## Teste de Estado

Teste o gerenciamento de estado dirigido por EventBus em widgets `NyPage` e `NyState` usando helpers de teste de estado.

### Disparando Atualizações de Estado

Simule atualizações de estado que normalmente viriam de outro widget ou controller:

``` dart
// Fire an UpdateState event
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### Disparando Ações de Estado

Envie ações de estado que são tratadas por `whenStateAction()` na sua página:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// With additional data
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### Asserções de Estado

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

Rastreie e inspecione atualizações e ações de estado disparadas:

``` dart
// Get all updates fired to a state
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Get all actions fired to a state
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Reset all tracked state updates and actions
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## Depuração

### dump

Imprima o estado atual do teste (conteúdo do Backpack, usuário autenticado, tempo, chamadas de API, locale):

``` dart
NyTest.dump();
```

### dd (Dump and Die)

Imprima o estado do teste e finalize imediatamente o teste:

``` dart
NyTest.dd();
```

### Armazenamento de Estado do Teste

Armazene e recupere valores durante um teste:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Seed do Backpack

Pré-popule o Backpack com dados de teste:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## Exemplos

### Arquivo de Teste Completo

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
