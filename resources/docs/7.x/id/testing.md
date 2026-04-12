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
- [Helper Navigasi dan Interaksi](#nav-interaction "Helper Navigasi dan Interaksi")
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
// Pompa frame hingga widget tertentu muncul
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Settle dengan mulus (tidak akan error saat timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Simulasi Siklus Hidup

Simulasikan perubahan `AppLifecycleState` pada `NyPage` manapun di widget tree:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Periksa efek samping dari lifecycle action paused
```

#### Pemeriksaan Loading dan Lock

Periksa kunci loading bernama dan lock pada widget `NyPage`/`NyState`:

``` dart
// Periksa apakah kunci loading bernama aktif
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Periksa apakah lock bernama dipegang
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Periksa indikator loading apa pun (CircularProgressIndicator atau Skeletonizer)
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
    // Verifikasi init dipanggil dan loading selesai
    await verifyInitCalled(tester, HomePage(), timeout: Duration(seconds: 5));

    // Verifikasi state loading ditampilkan saat init
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
    // Berjalan sekali sebelum semua test
  });

  nySetUp(() {
    // Berjalan sebelum setiap test
  });

  nyTearDown(() {
    // Berjalan setelah setiap test
  });

  nyTearDownAll(() {
    // Berjalan sekali setelah semua test
  });
}
```

<div id="skipping-tests"></div>

### Melewati dan Test CI

``` dart
// Lewati test dengan alasan
nySkip('not implemented yet', () async {
  // ...
}, "Waiting for API update");

// Test yang diharapkan gagal
nyFailing('known bug', () async {
  // ...
});

// Test khusus CI (diberi tag 'ci')
nyCi('integration test', () async {
  // Hanya berjalan di lingkungan CI
});
```

<div id="authentication"></div>

## Autentikasi

Simulasikan pengguna yang terautentikasi dalam test:

``` dart
nyTest('user can access profile', () async {
  // Simulasikan pengguna yang sudah login
  NyTest.actingAs<User>(User(name: "Anthony", email: "anthony@example.com"));

  // Verifikasi terautentikasi
  expectAuthenticated<User>();

  // Akses pengguna yang sedang aktif
  User? user = NyTest.actingUser<User>();
  expect(user?.name, equals("Anthony"));
});

nyTest('guest cannot access profile', () async {
  // Verifikasi tidak terautentikasi
  expectGuest();
});
```

Logout pengguna:

``` dart
NyTest.logout();
expectGuest();
```

Gunakan `actingAsGuest()` sebagai alias yang mudah dibaca untuk `logout()` saat menyiapkan konteks tamu:

``` dart
NyTest.actingAsGuest();
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

  NyTest.travelBack(); // Kembali ke waktu nyata
});
```

### Memajukan atau Memundurkan Waktu

``` dart
NyTest.travelForward(Duration(days: 30)); // Maju 30 hari ke depan
NyTest.travelBackward(Duration(hours: 2)); // Mundur 2 jam ke belakang
```

### Membekukan Waktu

``` dart
NyTest.freezeTime(); // Bekukan di saat ini

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Waktu tidak bergerak

NyTest.travelBack(); // Cairkan pembekuan
```

### Batas Waktu

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // Tanggal 1 bulan ini
NyTime.travelToEndOfMonth();   // Hari terakhir bulan ini
NyTime.travelToStartOfYear();  // 1 Januari
NyTime.travelToEndOfYear();    // 31 Desember
```

### Perjalanan Waktu Terbatas

Jalankan kode dalam konteks waktu yang dibekukan:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Waktu otomatis dipulihkan setelah callback
```

<div id="api-mocking"></div>

## Mocking API

<div id="mocking-by-url"></div>

### Mocking Berdasarkan Pola URL

Mock respons API menggunakan pola URL dengan dukungan wildcard:

``` dart
nyTest('mock API responses', () async {
  // Cocokkan URL persis
  NyMockApi.respond('/users/1', {'id': 1, 'name': 'Anthony'});

  // Wildcard satu segmen (*)
  NyMockApi.respond('/users/*', {'id': 1, 'name': 'User'});

  // Wildcard multi-segmen (**)
  NyMockApi.respond('/api/**', {'status': 'ok'});

  // Dengan kode status dan header
  NyMockApi.respond(
    '/users',
    {'error': 'Unauthorized'},
    statusCode: 401,
    method: 'POST',
    headers: {'X-Error': 'true'},
  );

  // Dengan penundaan simulasi
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

  // ... lakukan aksi yang memicu panggilan API ...

  // Verifikasi endpoint dipanggil
  expectApiCalled('/users');

  // Verifikasi endpoint tidak dipanggil
  expectApiNotCalled('/admin');

  // Verifikasi jumlah panggilan
  expectApiCalled('/users', times: 2);

  // Verifikasi method tertentu
  expectApiCalled('/users', method: 'POST');

  // Dapatkan detail panggilan
  List<ApiCallInfo> calls = NyMockApi.getCallsFor('/users');
});
```

Tegaskan bahwa sebuah endpoint dipanggil dengan data request body tertentu:

``` dart
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
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
// Buat satu instance
User user = NyFactory.make<User>();

// Buat dengan override
User admin = NyFactory.make<User>(overrides: {'name': 'Admin User'});

// Buat dengan state diterapkan
User premiumAdmin = NyFactory.make<User>(states: ['admin', 'premium']);

// Buat beberapa instance
List<User> users = NyFactory.create<User>(count: 5);

// Buat urutan dengan data berbasis indeks
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

  // Simpan nilai
  await cache.put<String>("key", "value");

  // Simpan dengan kedaluwarsa
  await cache.put<String>("temp", "data", seconds: 60);

  // Baca nilai
  String? value = await cache.get<String>("key");

  // Periksa keberadaan
  bool exists = await cache.has("key");

  // Hapus kunci
  await cache.clear("key");

  // Hapus semua
  await cache.flush();

  // Dapatkan info cache
  int size = await cache.size();
  List<String> keys = await cache.documents();
});
```

<div id="platform-channel-mocking"></div>

## Mocking Platform Channel

`NyMockChannels` secara otomatis melakukan mock pada platform channel umum agar test tidak crash:

``` dart
void main() {
  NyTest.init(); // Secara otomatis menyiapkan mock channel

  // Atau siapkan secara manual
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

// Dengan data tambahan
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Guard dengan Logika Kustom

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // batalkan navigasi
  }
  return GuardResult.next; // izinkan navigasi
});
```

### Melacak Panggilan Guard

Setelah guard dipanggil, Anda dapat memeriksa state-nya:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Akses RouteContext dari panggilan terakhir
RouteContext? context = guard.lastContext;

// Reset pelacakan
guard.reset();
```

<div id="assertions"></div>

## Assertion

{{ config('app.name') }} menyediakan fungsi assertion kustom:

### Assertion Rute

``` dart
expectRoute('/home');           // Verifikasi rute saat ini
expectNotRoute('/login');       // Verifikasi tidak pada rute
expectRouteInHistory('/home');  // Verifikasi rute pernah dikunjungi
expectRouteExists('/profile');  // Verifikasi rute terdaftar
expectRoutesExist(['/home', '/profile', '/settings']);
```

### Assertion State

``` dart
expectBackpackContains("key");                        // Kunci ada
expectBackpackContains("key", value: "expected");     // Kunci memiliki nilai
expectBackpackNotContains("key");                     // Kunci tidak ada
```

### Assertion Auth

``` dart
expectAuthenticated<User>();  // Pengguna terautentikasi
expectGuest();                // Tidak ada pengguna terautentikasi
```

### Assertion Environment

``` dart
expectEnv("APP_NAME", "MyApp");  // Variabel Env sama dengan nilai
expectEnvSet("APP_KEY");          // Variabel Env sudah diset
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
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
```

### Assertion Widget

``` dart
// Verifikasi tipe widget muncul sejumlah tertentu
expectWidgetCount(ListTile, 3);
expectWidgetCount(Icon, 0);

// Verifikasi teks terlihat
expectTextVisible('Welcome');

// Verifikasi teks tidak terlihat
expectTextNotVisible('Error');

// Verifikasi widget apa pun terlihat (gunakan Finder apa pun)
expectVisible(find.byType(FloatingActionButton));
expectVisible(find.byIcon(Icons.notifications));
expectVisible(find.byKey(Key('submit_btn')));

// Verifikasi widget tidak terlihat
expectNotVisible(find.byType(ErrorBanner));
expectNotVisible(find.byKey(Key('loading_spinner')));
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
  // ... picu aksi yang menampilkan toast ...

  expectToastShown(id: 'success');
  expectToastShown(id: 'danger', description: 'Something went wrong');
  expectNoToastShown(id: 'info');
});
```

**NyToastRecorder** melacak notifikasi toast selama test:

``` dart
// Rekam toast secara manual
NyToastRecorder.record(id: 'success', title: 'Done', description: 'Saved!');

// Periksa apakah toast ditampilkan
bool shown = NyToastRecorder.wasShown(id: 'success');

// Akses semua toast yang direkam
List<ToastRecord> toasts = NyToastRecorder.records;

// Hapus toast yang direkam
NyToastRecorder.clear();
```

### Assertion Lock dan Loading

Periksa state lock dan loading bernama pada widget `NyPage`/`NyState`:

``` dart
// Verifikasi lock bernama dipegang
expectLocked(tester, find.byType(MyPage), 'submit');

// Verifikasi lock bernama tidak dipegang
expectNotLocked(tester, find.byType(MyPage), 'submit');

// Verifikasi kunci loading bernama aktif
expectLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');

// Verifikasi kunci loading bernama tidak aktif
expectNotLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');
```

<div id="custom-matchers"></div>

## Matcher Kustom

Gunakan matcher kustom dengan `expect()`:

``` dart
// Matcher tipe
expect(result, isType<User>());

// Matcher nama rute
expect(widget, hasRouteName('/home'));

// Matcher Backpack
expect(true, backpackHas("key", value: "expected"));

// Matcher panggilan API
expect(true, apiWasCalled('/users', method: 'GET', times: 1));
```

<div id="state-testing"></div>

## Testing State

Uji manajemen state berbasis EventBus pada widget `NyPage` dan `NyState` menggunakan helper test state.

### Memicu Update State

Simulasikan update state yang biasanya datang dari widget atau controller lain:

``` dart
// Picu event UpdateState
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### Memicu Action State

Kirim action state yang ditangani oleh `whenStateAction()` di halaman Anda:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// Dengan data tambahan
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### Assertion State

``` dart
// Verifikasi update state dipicu
expectStateUpdated('HomePageState');
expectStateUpdated('HomePageState', times: 2);

// Verifikasi action state dipicu
expectStateAction('HomePageState', 'refresh-page');
expectStateAction('CartState', 'add-item', times: 1);

// Verifikasi stateData pada widget NyPage/NyState
expectStateData(tester, find.byType(MyWidget), equals(42));
```

### NyStateTestHelpers

Lacak dan periksa update dan action state yang dipicu:

``` dart
// Dapatkan semua update yang dipicu ke suatu state
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Dapatkan semua action yang dipicu ke suatu state
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Reset semua update dan action state yang dilacak
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

<div id="nav-interaction"></div>

## Helper Navigasi dan Interaksi

Ekstensi `WidgetTester` menyediakan DSL tingkat tinggi untuk menulis alur navigasi dan interaksi UI dalam `nyWidgetTest`.

### visit

Navigasi ke sebuah rute dan tunggu hingga halaman settle:

``` dart
nyWidgetTest('loads dashboard', (tester) async {
  await tester.visit(DashboardPage.path);
  expectTextVisible('Dashboard');
});
```

### assertNavigatedTo

Verifikasi bahwa aksi navigasi membawa Anda ke rute yang diharapkan:

``` dart
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);
```

### assertOnRoute

Verifikasi bahwa rute saat ini sesuai dengan rute yang diberikan (gunakan untuk konfirmasi posisi Anda, bukan bahwa Anda baru saja bernavigasi):

``` dart
await tester.visit(DashboardPage.path);
tester.assertOnRoute(DashboardPage.path);
```

### settle

Tunggu semua animasi dan frame callback yang tertunda selesai:

``` dart
await tester.tap(find.byType(MyButton));
await tester.settle();
tester.assertNavigatedTo(ProfilePage.path);
```

### navigateBack

Pop rute saat ini dan settle:

``` dart
await tester.visit(DashboardPage.path);
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);

await tester.navigateBack();
tester.assertOnRoute(DashboardPage.path);
```

### tapText

Temukan widget berdasarkan teks, tap, dan settle dalam satu panggilan:

``` dart
await tester.tapText('Login');
await tester.tapText('Submit');
```

### fillField

Tap form field, masukkan teks, dan settle:

``` dart
await tester.fillField(find.byKey(Key('email')), 'test@example.com');
await tester.fillField(find.byKey(Key('password')), 'secret123');
```

### scrollTo

Scroll hingga widget terlihat, lalu settle:

``` dart
await tester.scrollTo(find.text('Item 50'));
await tester.tapText('Item 50');
```

Berikan `scrollable` finder dan `delta` tertentu untuk kontrol yang lebih presisi:

``` dart
await tester.scrollTo(
  find.text('Footer'),
  scrollable: find.byKey(Key('main_list')),
  delta: 200,
);
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

      // ... picu panggilan API ...

      expectApiCalled('/api/users');
    });
  });

  nyGroup('Time-Sensitive Features', () {
    nyTest('subscription expires correctly', () async {
      NyTest.travel(DateTime(2025, 1, 1));

      // Uji logika langganan pada tanggal yang diketahui
      expect(NyTime.now().year, equals(2025));

      NyTest.travelForward(Duration(days: 365));
      expect(NyTime.now().year, equals(2026));

      NyTest.travelBack();
    });
  });
}
```
