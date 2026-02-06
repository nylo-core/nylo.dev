# Testing

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Getting Started](#getting-started "Getting Started")
- [Writing Tests](#writing-tests "Writing Tests")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [Widget Testing Utilities](#widget-testing-utilities "Widget Testing Utilities")
  - [nyGroup](#ny-group "nyGroup")
  - [Test Lifecycle](#test-lifecycle "Test Lifecycle")
  - [Skipping and CI Tests](#skipping-tests "Skipping and CI Tests")
- [Authentication](#authentication "Authentication")
- [Time Travel](#time-travel "Time Travel")
- [API Mocking](#api-mocking "API Mocking")
  - [Mocking by URL Pattern](#mocking-by-url "Mocking by URL Pattern")
  - [Mocking by API Service Type](#mocking-by-type "Mocking by API Service Type")
  - [Call History and Assertions](#call-history "Call History and Assertions")
- [Factories](#factories "Factories")
  - [Defining Factories](#defining-factories "Defining Factories")
  - [Factory States](#factory-states "Factory States")
  - [Creating Instances](#creating-instances "Creating Instances")
- [NyFaker](#ny-faker "NyFaker")
- [Test Cache](#test-cache "Test Cache")
- [Platform Channel Mocking](#platform-channel-mocking "Platform Channel Mocking")
- [Route Guard Mocking](#route-guard-mocking "Route Guard Mocking")
- [Assertions](#assertions "Assertions")
- [Custom Matchers](#custom-matchers "Custom Matchers")
- [State Testing](#state-testing "State Testing")
- [Debugging](#debugging "Debugging")
- [Examples](#examples "Practical Examples")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 includes a comprehensive testing framework inspired by Laravel's testing utilities. It provides:

- **Test functions** with automatic setup/teardown (`nyTest`, `nyWidgetTest`, `nyGroup`)
- **Authentication simulation** via `NyTest.actingAs<T>()`
- **Time travel** to freeze or manipulate time in tests
- **API mocking** with URL pattern matching and call tracking
- **Factories** with a built-in fake data generator (`NyFaker`)
- **Platform channel mocking** for secure storage, path provider, and more
- **Custom assertions** for routes, Backpack, authentication, and environment

<div id="getting-started"></div>

## Getting Started

Initialize the test framework at the top of your test file:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` sets up the test environment and enables automatic state reset between tests when `autoReset: true` (the default).

<div id="writing-tests"></div>

## Writing Tests

<div id="ny-test"></div>

### nyTest

The primary function for writing tests:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

Options:

``` dart
nyTest('my test', () async {
  // test body
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

For testing Flutter widgets with a `WidgetTester`:

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

### Widget Testing Utilities

The `NyWidgetTest` class and `WidgetTester` extensions provide helpers for pumping Nylo widgets with proper theme support, waiting for `init()` to complete, and testing loading states.

#### Configuring the Test Environment

Call `NyWidgetTest.configure()` in your `setUpAll` to disable Google Fonts fetching and optionally set a custom theme:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

You can reset the configuration with `NyWidgetTest.reset()`.

Two built-in themes are available for font-free testing:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Pumping Nylo Widgets

Use `pumpNyWidget` to wrap a widget in a `MaterialApp` with theme support:

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

For a quick pump with a font-free theme:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Waiting for Init

`pumpNyWidgetAndWaitForInit` pumps frames until loading indicators disappear (or the timeout is reached), which is useful for pages with async `init()` methods:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() has completed
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Pump Helpers

``` dart
// Pump frames until a specific widget appears
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Settle gracefully (won't throw on timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Lifecycle Simulation

Simulate `AppLifecycleState` changes on any `NyPage` in the widget tree:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Assert side effects of the paused lifecycle action
```

#### Loading and Lock Checks

Check named loading keys and locks on `NyPage`/`NyState` widgets:

``` dart
// Check if a named loading key is active
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Check if a named lock is held
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Check for any loading indicator (CircularProgressIndicator or Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### testNyPage Helper

A convenience function that pumps a `NyPage`, waits for init, then runs your expectations:

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

#### testNyPageLoading Helper

Test that a page displays a loading indicator during `init()`:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

A mixin providing common page testing utilities:

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

Group related tests together:

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

### Test Lifecycle

Set up and tear down logic using lifecycle hooks:

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

### Skipping and CI Tests

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

## Authentication

Simulate authenticated users in tests:

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

Log the user out:

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## Time Travel

Manipulate time in your tests using `NyTime`:

### Jump to a Specific Date

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Reset to real time
});
```

### Advance or Rewind Time

``` dart
NyTest.travelForward(Duration(days: 30)); // Jump 30 days ahead
NyTest.travelBackward(Duration(hours: 2)); // Go back 2 hours
```

### Freeze Time

``` dart
NyTest.freezeTime(); // Freeze at the current moment

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Time hasn't moved

NyTest.travelBack(); // Unfreeze
```

### Time Boundaries

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1st of current month
NyTime.travelToEndOfMonth();   // Last day of current month
NyTime.travelToStartOfYear();  // Jan 1st
NyTime.travelToEndOfYear();    // Dec 31st
```

### Scoped Time Travel

Execute code within a frozen time context:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Time is automatically restored after the callback
```

<div id="api-mocking"></div>

## API Mocking

<div id="mocking-by-url"></div>

### Mocking by URL Pattern

Mock API responses using URL patterns with wildcard support:

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

### Mocking by API Service Type

Mock an entire API service by type:

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

### Call History and Assertions

Track and verify API calls:

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

### Creating Mock Responses

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

### Defining Factories

Define how to create test instances of your models:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

With override support:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### Factory States

Define variations of a factory:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### Creating Instances

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

`NyFaker` generates realistic fake data for tests. It's available inside factory definitions and can be instantiated directly.

``` dart
NyFaker faker = NyFaker();
```

### Available Methods

| Category | Method | Return Type | Description |
|----------|--------|-------------|-------------|
| **Names** | `faker.firstName()` | `String` | Random first name |
| | `faker.lastName()` | `String` | Random last name |
| | `faker.name()` | `String` | Full name (first + last) |
| | `faker.username()` | `String` | Username string |
| **Contact** | `faker.email()` | `String` | Email address |
| | `faker.phone()` | `String` | Phone number |
| | `faker.company()` | `String` | Company name |
| **Numbers** | `faker.randomInt(min, max)` | `int` | Random integer in range |
| | `faker.randomDouble(min, max)` | `double` | Random double in range |
| | `faker.randomBool()` | `bool` | Random boolean |
| **Identifiers** | `faker.uuid()` | `String` | UUID v4 string |
| **Dates** | `faker.date()` | `DateTime` | Random date |
| | `faker.pastDate()` | `DateTime` | Date in the past |
| | `faker.futureDate()` | `DateTime` | Date in the future |
| **Text** | `faker.lorem()` | `String` | Lorem ipsum words |
| | `faker.sentences()` | `String` | Multiple sentences |
| | `faker.paragraphs()` | `String` | Multiple paragraphs |
| | `faker.slug()` | `String` | URL slug |
| **Web** | `faker.url()` | `String` | URL string |
| | `faker.imageUrl()` | `String` | Image URL (via picsum.photos) |
| | `faker.ipAddress()` | `String` | IPv4 address |
| | `faker.macAddress()` | `String` | MAC address |
| **Location** | `faker.address()` | `String` | Street address |
| | `faker.city()` | `String` | City name |
| | `faker.state()` | `String` | US state abbreviation |
| | `faker.zipCode()` | `String` | Zip code |
| | `faker.country()` | `String` | Country name |
| **Other** | `faker.hexColor()` | `String` | Hex color code |
| | `faker.creditCardNumber()` | `String` | Credit card number |
| | `faker.randomElement(list)` | `T` | Random item from list |
| | `faker.randomElements(list, count)` | `List<T>` | Random items from list |

<div id="test-cache"></div>

## Test Cache

`NyTestCache` provides an in-memory cache for testing cache-related functionality:

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

## Platform Channel Mocking

`NyMockChannels` automatically mocks common platform channels so tests don't crash:

``` dart
void main() {
  NyTest.init(); // Automatically sets up mock channels

  // Or set up manually
  NyMockChannels.setup();
}
```

### Mocked Channels

- **path_provider** -- documents, temporary, application support, library, and cache directories
- **flutter_secure_storage** -- in-memory secure storage
- **flutter_timezone** -- timezone data
- **flutter_local_notifications** -- notification channel
- **sqflite** -- database operations

### Override Paths

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

## Route Guard Mocking

`NyMockRouteGuard` lets you test route guard behavior without real authentication or network calls. It extends `NyRouteGuard` and provides factory constructors for common scenarios.

### Guard That Always Passes

``` dart
final guard = NyMockRouteGuard.pass();
```

### Guard That Redirects

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// With additional data
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Guard With Custom Logic

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // abort navigation
  }
  return GuardResult.next; // allow navigation
});
```

### Tracking Guard Calls

After a guard has been invoked you can inspect its state:

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

{{ config('app.name') }} provides custom assertion functions:

### Route Assertions

``` dart
expectRoute('/home');           // Assert current route
expectNotRoute('/login');       // Assert not on route
expectRouteInHistory('/home');  // Assert route was visited
expectRouteExists('/profile');  // Assert route is registered
expectRoutesExist(['/home', '/profile', '/settings']);
```

### State Assertions

``` dart
expectBackpackContains("key");                        // Key exists
expectBackpackContains("key", value: "expected");     // Key has value
expectBackpackNotContains("key");                     // Key doesn't exist
```

### Auth Assertions

``` dart
expectAuthenticated<User>();  // User is authenticated
expectGuest();                // No user authenticated
```

### Environment Assertions

``` dart
expectEnv("APP_NAME", "MyApp");  // Env variable equals value
expectEnvSet("APP_KEY");          // Env variable is set
```

### Mode Assertions

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### API Assertions

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### Locale Assertions

``` dart
expectLocale("en");
```

### Toast Assertions

Assert on toast notifications that were recorded during a test. Requires `NyToastRecorder.setup()` in your test setUp:

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

**NyToastRecorder** tracks toast notifications during tests:

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

### Lock and Loading Assertions

Assert on named lock and loading states in `NyPage`/`NyState` widgets:

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

## Custom Matchers

Use custom matchers with `expect()`:

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

## State Testing

Test EventBus-driven state management in `NyPage` and `NyState` widgets using state test helpers.

### Firing State Updates

Simulate state updates that would normally come from another widget or controller:

``` dart
// Fire an UpdateState event
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### Firing State Actions

Send state actions that are handled by `whenStateAction()` in your page:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// With additional data
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### State Assertions

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

Track and inspect fired state updates and actions:

``` dart
// Get all updates fired to a state
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Get all actions fired to a state
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Reset all tracked state updates and actions
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## Debugging

### dump

Print the current test state (Backpack contents, auth user, time, API calls, locale):

``` dart
NyTest.dump();
```

### dd (Dump and Die)

Print the test state and immediately terminate the test:

``` dart
NyTest.dd();
```

### Test State Storage

Store and retrieve values during a test:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Seed Backpack

Pre-populate Backpack with test data:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## Examples

### Full Test File

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
