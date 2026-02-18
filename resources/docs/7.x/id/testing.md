# Testing

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Memulai](#getting-started "Memulai")
- [Menulis Test](#writing-tests "Menulis Test")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [Utilitas Testing Widget](#widget-testing-utilities "Utilitas Testing Widget")
  - [nyGroup](#ny-group "nyGroup")
  - [Siklus Hidup Test](#test-lifecycle "Siklus Hidup Test")
  - [Melewati dan Test CI](#skipping-tests "Melewati dan Test CI")
- [Autentikasi](#authentication "Autentikasi")
- [Perjalanan Waktu](#time-travel "Perjalanan Waktu")
- [Mocking API](#api-mocking "Mocking API")
  - [Mocking Berdasarkan Pola URL](#mocking-by-url "Mocking Berdasarkan Pola URL")
  - [Mocking Berdasarkan Tipe API Service](#mocking-by-type "Mocking Berdasarkan Tipe API Service")
  - [Riwayat Panggilan dan Assertion](#call-history "Riwayat Panggilan dan Assertion")
- [Factory](#factories "Factory")
  - [Mendefinisikan Factory](#defining-factories "Mendefinisikan Factory")
  - [State Factory](#factory-states "State Factory")
  - [Membuat Instance](#creating-instances "Membuat Instance")
- [NyFaker](#ny-faker "NyFaker")
- [Cache Test](#test-cache "Cache Test")
- [Mocking Platform Channel](#platform-channel-mocking "Mocking Platform Channel")
- [Mocking Route Guard](#route-guard-mocking "Mocking Route Guard")
- [Assertion](#assertions "Assertion")
- [Matcher Kustom](#custom-matchers "Matcher Kustom")
- [Testing State](#state-testing "Testing State")
- [Debugging](#debugging "Debugging")
- [Contoh](#examples "Contoh")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} v7 menyertakan framework testing komprehensif yang terinspirasi dari utilitas testing Laravel. Framework ini menyediakan:

- **Fungsi test** dengan setup/teardown otomatis (`nyTest`, `nyWidgetTest`, `nyGroup`)
- **Simulasi autentikasi** melalui `NyTest.actingAs<T>()`
- **Perjalanan waktu** untuk membekukan atau memanipulasi waktu dalam test
- **Mocking API** dengan pencocokan pola URL dan pelacakan panggilan
- **Factory** dengan generator data palsu bawaan (`NyFaker`)
- **Mocking platform channel** untuk secure storage, path provider, dan lainnya
- **Assertion kustom** untuk rute, Backpack, autentikasi, dan environment

<div id="getting-started"></div>

## Memulai

Inisialisasi framework test di bagian atas file test Anda:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` menyiapkan lingkungan test dan mengaktifkan reset state otomatis antar test ketika `autoReset: true` (nilai default).

<div id="writing-tests"></div>

## Menulis Test

<div id="ny-test"></div>

### nyTest

Fungsi utama untuk menulis test:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

Opsi:

``` dart
nyTest('my test', () async {
  // test body
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

Untuk menguji widget Flutter dengan `WidgetTester`:

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

### Utilitas Testing Widget

Kelas `NyWidgetTest` dan ekstensi `WidgetTester` menyediakan helper untuk memompa widget Nylo dengan dukungan tema yang tepat, menunggu `init()` selesai, dan menguji state loading.

#### Mengkonfigurasi Lingkungan Test

Panggil `NyWidgetTest.configure()` di `setUpAll` Anda untuk menonaktifkan pengambilan Google Fonts dan secara opsional mengatur tema kustom:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

Anda dapat mereset konfigurasi dengan `NyWidgetTest.reset()`.

Dua tema bawaan tersedia untuk testing tanpa font:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Memompa Widget Nylo

Gunakan `pumpNyWidget` untuk membungkus widget dalam `MaterialApp` dengan dukungan tema:

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

Untuk pompa cepat dengan tema tanpa font:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Menunggu Init

`pumpNyWidgetAndWaitForInit` memompa frame sampai indikator loading menghilang (atau timeout tercapai), yang berguna untuk halaman dengan method `init()` asinkron:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() has completed
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Helper Pompa

``` dart
// Pump frames until a specific widget appears
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Settle gracefully (won't throw on timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Simulasi Siklus Hidup

Simulasikan perubahan `AppLifecycleState` pada `NyPage` manapun di widget tree:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Assert side effects of the paused lifecycle action
```

#### Pemeriksaan Loading dan Lock

Periksa kunci loading bernama dan lock pada widget `NyPage`/`NyState`:

``` dart
// Check if a named loading key is active
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Check if a named lock is held
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Check for any loading indicator (CircularProgressIndicator or Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### Helper testNyPage

Fungsi praktis yang memompa `NyPage`, menunggu init, lalu menjalankan ekspektasi Anda:

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

Uji bahwa halaman menampilkan indikator loading selama `init()`:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

Mixin yang menyediakan utilitas testing halaman umum:

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

Mengelompokkan test yang terkait:

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

### Siklus Hidup Test

Atur logika setup dan teardown menggunakan hook siklus hidup:

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

### Melewati dan Test CI

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

## Autentikasi

Simulasikan pengguna yang terautentikasi dalam test:

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

Logout pengguna:

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## Perjalanan Waktu

Manipulasi waktu dalam test Anda menggunakan `NyTime`:

### Melompat ke Tanggal Tertentu

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Reset to real time
});
```

### Memajukan atau Memundurkan Waktu

``` dart
NyTest.travelForward(Duration(days: 30)); // Jump 30 days ahead
NyTest.travelBackward(Duration(hours: 2)); // Go back 2 hours
```

### Membekukan Waktu

``` dart
NyTest.freezeTime(); // Freeze at the current moment

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Time hasn't moved

NyTest.travelBack(); // Unfreeze
```

### Batas Waktu

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1st of current month
NyTime.travelToEndOfMonth();   // Last day of current month
NyTime.travelToStartOfYear();  // Jan 1st
NyTime.travelToEndOfYear();    // Dec 31st
```

### Perjalanan Waktu Terbatas

Jalankan kode dalam konteks waktu yang dibekukan:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Time is automatically restored after the callback
```

<div id="api-mocking"></div>

## Mocking API

<div id="mocking-by-url"></div>

### Mocking Berdasarkan Pola URL

Mock respons API menggunakan pola URL dengan dukungan wildcard:

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

### Mocking Berdasarkan Tipe API Service

Mock seluruh API service berdasarkan tipe:

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

### Riwayat Panggilan dan Assertion

Lacak dan verifikasi panggilan API:

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

### Membuat Respons Mock

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

### Mendefinisikan Factory

Definisikan cara membuat instance test dari model Anda:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

Dengan dukungan override:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### State Factory

Definisikan variasi dari factory:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### Membuat Instance

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

`NyFaker` menghasilkan data palsu yang realistis untuk test. Tersedia di dalam definisi factory dan dapat diinstansiasi secara langsung.

``` dart
NyFaker faker = NyFaker();
```

### Metode yang Tersedia

| Kategori | Metode | Tipe Kembalian | Deskripsi |
|----------|--------|-------------|-------------|
| **Nama** | `faker.firstName()` | `String` | Nama depan acak |
| | `faker.lastName()` | `String` | Nama belakang acak |
| | `faker.name()` | `String` | Nama lengkap (depan + belakang) |
| | `faker.username()` | `String` | String username |
| **Kontak** | `faker.email()` | `String` | Alamat email |
| | `faker.phone()` | `String` | Nomor telepon |
| | `faker.company()` | `String` | Nama perusahaan |
| **Angka** | `faker.randomInt(min, max)` | `int` | Integer acak dalam rentang |
| | `faker.randomDouble(min, max)` | `double` | Double acak dalam rentang |
| | `faker.randomBool()` | `bool` | Boolean acak |
| **Identifier** | `faker.uuid()` | `String` | String UUID v4 |
| **Tanggal** | `faker.date()` | `DateTime` | Tanggal acak |
| | `faker.pastDate()` | `DateTime` | Tanggal di masa lalu |
| | `faker.futureDate()` | `DateTime` | Tanggal di masa depan |
| **Teks** | `faker.lorem()` | `String` | Kata-kata lorem ipsum |
| | `faker.sentences()` | `String` | Beberapa kalimat |
| | `faker.paragraphs()` | `String` | Beberapa paragraf |
| | `faker.slug()` | `String` | Slug URL |
| **Web** | `faker.url()` | `String` | String URL |
| | `faker.imageUrl()` | `String` | URL gambar (via picsum.photos) |
| | `faker.ipAddress()` | `String` | Alamat IPv4 |
| | `faker.macAddress()` | `String` | Alamat MAC |
| **Lokasi** | `faker.address()` | `String` | Alamat jalan |
| | `faker.city()` | `String` | Nama kota |
| | `faker.state()` | `String` | Singkatan negara bagian AS |
| | `faker.zipCode()` | `String` | Kode pos |
| | `faker.country()` | `String` | Nama negara |
| **Lainnya** | `faker.hexColor()` | `String` | Kode warna hex |
| | `faker.creditCardNumber()` | `String` | Nomor kartu kredit |
| | `faker.randomElement(list)` | `T` | Item acak dari daftar |
| | `faker.randomElements(list, count)` | `List<T>` | Item-item acak dari daftar |

<div id="test-cache"></div>

## Cache Test

`NyTestCache` menyediakan cache dalam memori untuk menguji fungsionalitas terkait cache:

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

## Mocking Platform Channel

`NyMockChannels` secara otomatis melakukan mock pada platform channel umum agar test tidak crash:

``` dart
void main() {
  NyTest.init(); // Automatically sets up mock channels

  // Or set up manually
  NyMockChannels.setup();
}
```

### Channel yang Di-mock

- **path_provider** -- direktori documents, temporary, application support, library, dan cache
- **flutter_secure_storage** -- secure storage dalam memori
- **flutter_timezone** -- data timezone
- **flutter_local_notifications** -- channel notifikasi
- **sqflite** -- operasi database

### Mengganti Path

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### Secure Storage dalam Test

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## Mocking Route Guard

`NyMockRouteGuard` memungkinkan Anda menguji perilaku route guard tanpa autentikasi atau panggilan jaringan nyata. Ini meng-extend `NyRouteGuard` dan menyediakan factory constructor untuk skenario umum.

### Guard yang Selalu Lolos

``` dart
final guard = NyMockRouteGuard.pass();
```

### Guard yang Mengalihkan

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// With additional data
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Guard dengan Logika Kustom

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // abort navigation
  }
  return GuardResult.next; // allow navigation
});
```

### Melacak Panggilan Guard

Setelah guard dipanggil, Anda dapat memeriksa state-nya:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Access the RouteContext from the last call
RouteContext? context = guard.lastContext;

// Reset tracking
guard.reset();
```

<div id="assertions"></div>

## Assertion

{{ config('app.name') }} menyediakan fungsi assertion kustom:

### Assertion Rute

``` dart
expectRoute('/home');           // Assert current route
expectNotRoute('/login');       // Assert not on route
expectRouteInHistory('/home');  // Assert route was visited
expectRouteExists('/profile');  // Assert route is registered
expectRoutesExist(['/home', '/profile', '/settings']);
```

### Assertion State

``` dart
expectBackpackContains("key");                        // Key exists
expectBackpackContains("key", value: "expected");     // Key has value
expectBackpackNotContains("key");                     // Key doesn't exist
```

### Assertion Auth

``` dart
expectAuthenticated<User>();  // User is authenticated
expectGuest();                // No user authenticated
```

### Assertion Environment

``` dart
expectEnv("APP_NAME", "MyApp");  // Env variable equals value
expectEnvSet("APP_KEY");          // Env variable is set
```

### Assertion Mode

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### Assertion API

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### Assertion Locale

``` dart
expectLocale("en");
```

### Assertion Toast

Periksa notifikasi toast yang direkam selama test. Memerlukan `NyToastRecorder.setup()` di setUp test Anda:

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

**NyToastRecorder** melacak notifikasi toast selama test:

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

### Assertion Lock dan Loading

Periksa state lock dan loading bernama pada widget `NyPage`/`NyState`:

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

## Matcher Kustom

Gunakan matcher kustom dengan `expect()`:

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

## Testing State

Uji manajemen state berbasis EventBus pada widget `NyPage` dan `NyState` menggunakan helper test state.

### Memicu Update State

Simulasikan update state yang biasanya datang dari widget atau controller lain:

``` dart
// Fire an UpdateState event
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### Memicu Action State

Kirim action state yang ditangani oleh `whenStateAction()` di halaman Anda:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// With additional data
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### Assertion State

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

Lacak dan periksa update dan action state yang dipicu:

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

Cetak state test saat ini (isi Backpack, pengguna auth, waktu, panggilan API, locale):

``` dart
NyTest.dump();
```

### dd (Dump and Die)

Cetak state test dan segera hentikan test:

``` dart
NyTest.dd();
```

### Penyimpanan State Test

Simpan dan ambil nilai selama test:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Mengisi Backpack Awal

Isi Backpack dengan data test terlebih dahulu:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## Contoh

### File Test Lengkap

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
