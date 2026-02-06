# Testing

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Başlarken](#getting-started "Başlarken")
- [Test Yazma](#writing-tests "Test Yazma")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [Widget Test Araçları](#widget-testing-utilities "Widget Test Araçları")
  - [nyGroup](#ny-group "nyGroup")
  - [Test Yaşam Döngüsü](#test-lifecycle "Test Yaşam Döngüsü")
  - [Testleri Atlama ve CI Testleri](#skipping-tests "Testleri Atlama ve CI Testleri")
- [Kimlik Doğrulama](#authentication "Kimlik Doğrulama")
- [Zaman Yolculuğu](#time-travel "Zaman Yolculuğu")
- [API Mocklama](#api-mocking "API Mocklama")
  - [URL Kalıbına Göre Mocklama](#mocking-by-url "URL Kalıbına Göre Mocklama")
  - [API Servis Türüne Göre Mocklama](#mocking-by-type "API Servis Türüne Göre Mocklama")
  - [Çağrı Geçmişi ve Doğrulamalar](#call-history "Çağrı Geçmişi ve Doğrulamalar")
- [Factory'ler](#factories "Factory'ler")
  - [Factory Tanımlama](#defining-factories "Factory Tanımlama")
  - [Factory Durumları](#factory-states "Factory Durumları")
  - [Örnek Oluşturma](#creating-instances "Örnek Oluşturma")
- [NyFaker](#ny-faker "NyFaker")
- [Test Önbelleği](#test-cache "Test Önbelleği")
- [Platform Kanalı Mocklama](#platform-channel-mocking "Platform Kanalı Mocklama")
- [Route Guard Mocklama](#route-guard-mocking "Route Guard Mocklama")
- [Doğrulamalar](#assertions "Doğrulamalar")
- [Özel Eşleştiriciler](#custom-matchers "Özel Eşleştiriciler")
- [Durum Testi](#state-testing "Durum Testi")
- [Hata Ayıklama](#debugging "Hata Ayıklama")
- [Örnekler](#examples "Pratik Örnekler")

<div id="introduction"></div>

## Giriş

{{ config('app.name') }} v7, Laravel'in test araçlarından ilham alan kapsamlı bir test framework'ü içerir. Sağladıkları:

- Otomatik kurulum/temizleme ile **test fonksiyonları** (`nyTest`, `nyWidgetTest`, `nyGroup`)
- `NyTest.actingAs<T>()` ile **kimlik doğrulama simülasyonu**
- Testlerde zamanı dondurma veya manipüle etme ile **zaman yolculuğu**
- URL kalıp eşleştirme ve çağrı takibi ile **API mocklama**
- Yerleşik sahte veri üreteci (`NyFaker`) ile **factory'ler**
- Güvenli depolama, path provider ve daha fazlası için **platform kanalı mocklama**
- Rotalar, Backpack, kimlik doğrulama ve ortam için **özel doğrulamalar**

<div id="getting-started"></div>

## Başlarken

Test dosyanızın başında test framework'ünü başlatın:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` test ortamını kurar ve `autoReset: true` olduğunda (varsayılan) testler arasında otomatik durum sıfırlamayı etkinleştirir.

<div id="writing-tests"></div>

## Test Yazma

<div id="ny-test"></div>

### nyTest

Test yazmak için birincil fonksiyon:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

Seçenekler:

``` dart
nyTest('my test', () async {
  // test body
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

`WidgetTester` ile Flutter widget'larını test etmek için:

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

### Widget Test Araçları

`NyWidgetTest` sınıfı ve `WidgetTester` uzantıları, Nylo widget'larını doğru tema desteğiyle pompalamak, `init()` tamamlanmasını beklemek ve yükleme durumlarını test etmek için yardımcılar sağlar.

#### Test Ortamını Yapılandırma

Google Fonts almayı devre dışı bırakmak ve isteğe bağlı olarak özel bir tema ayarlamak için `setUpAll` içinde `NyWidgetTest.configure()` çağırın:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

Yapılandırmayı `NyWidgetTest.reset()` ile sıfırlayabilirsiniz.

Yazı tipi gerektirmeyen test için iki yerleşik tema mevcuttur:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Nylo Widget'larını Pompalama

Bir widget'ı tema desteği ile `MaterialApp` içine sarmak için `pumpNyWidget` kullanın:

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

Yazı tipi gerektirmeyen tema ile hızlı pompalama için:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Init Bekleme

`pumpNyWidgetAndWaitForInit`, yükleme göstergeleri kaybolana (veya zaman aşımına ulaşılana) kadar frame pompalar; bu, asenkron `init()` metotlarına sahip sayfalar için kullanışlıdır:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() has completed
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Pompa Yardımcıları

``` dart
// Pump frames until a specific widget appears
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Settle gracefully (won't throw on timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Yaşam Döngüsü Simülasyonu

Widget ağacındaki herhangi bir `NyPage` üzerinde `AppLifecycleState` değişikliklerini simüle edin:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Assert side effects of the paused lifecycle action
```

#### Yükleme ve Kilit Kontrolleri

`NyPage`/`NyState` widget'larında adlandırılmış yükleme anahtarlarını ve kilitleri kontrol edin:

``` dart
// Check if a named loading key is active
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Check if a named lock is held
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Check for any loading indicator (CircularProgressIndicator or Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### testNyPage Yardımcısı

Bir `NyPage`'i pompalar, init'i bekler, ardından beklentilerinizi çalıştıran pratik bir fonksiyon:

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

#### testNyPageLoading Yardımcısı

Bir sayfanın `init()` sırasında yükleme göstergesi gösterdiğini test edin:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

Yaygın sayfa test araçları sağlayan bir mixin:

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

İlgili testleri bir arada gruplandırın:

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

### Test Yaşam Döngüsü

Yaşam döngüsü kancalarını kullanarak kurulum ve temizleme mantığı ayarlayın:

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

### Testleri Atlama ve CI Testleri

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

## Kimlik Doğrulama

Testlerde kimliği doğrulanmış kullanıcıları simüle edin:

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

Kullanıcının oturumunu kapatın:

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## Zaman Yolculuğu

`NyTime` kullanarak testlerinizde zamanı manipüle edin:

### Belirli Bir Tarihe Atlama

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Reset to real time
});
```

### Zamanı İleri veya Geri Alma

``` dart
NyTest.travelForward(Duration(days: 30)); // Jump 30 days ahead
NyTest.travelBackward(Duration(hours: 2)); // Go back 2 hours
```

### Zamanı Dondurma

``` dart
NyTest.freezeTime(); // Freeze at the current moment

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Time hasn't moved

NyTest.travelBack(); // Unfreeze
```

### Zaman Sınırları

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1st of current month
NyTime.travelToEndOfMonth();   // Last day of current month
NyTime.travelToStartOfYear();  // Jan 1st
NyTime.travelToEndOfYear();    // Dec 31st
```

### Kapsamlı Zaman Yolculuğu

Dondurulmuş zaman bağlamında kod yürütün:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Time is automatically restored after the callback
```

<div id="api-mocking"></div>

## API Mocklama

<div id="mocking-by-url"></div>

### URL Kalıbına Göre Mocklama

Joker karakter desteğiyle URL kalıplarını kullanarak API yanıtlarını mocklayın:

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

### API Servis Türüne Göre Mocklama

Tüm bir API servisini türüne göre mocklayın:

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

### Çağrı Geçmişi ve Doğrulamalar

API çağrılarını takip edin ve doğrulayın:

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

### Mock Yanıtları Oluşturma

``` dart
Response<Map<String, dynamic>> response = NyMockApi.createResponse(
  data: {'id': 1, 'name': 'Anthony'},
  statusCode: 200,
  statusMessage: 'OK',
);
```

<div id="factories"></div>

## Factory'ler

<div id="defining-factories"></div>

### Factory Tanımlama

Modellerinizin test örneklerinin nasıl oluşturulacağını tanımlayın:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

Geçersiz kılma desteği ile:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### Factory Durumları

Bir factory'nin varyasyonlarını tanımlayın:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### Örnek Oluşturma

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

`NyFaker`, testler için gerçekçi sahte veriler üretir. Factory tanımları içinde kullanılabilir ve doğrudan örneklendirilebilir.

``` dart
NyFaker faker = NyFaker();
```

### Kullanılabilir Metotlar

| Kategori | Metot | Dönüş Türü | Açıklama |
|----------|--------|-------------|-------------|
| **İsimler** | `faker.firstName()` | `String` | Rastgele ad |
| | `faker.lastName()` | `String` | Rastgele soyad |
| | `faker.name()` | `String` | Tam isim (ad + soyad) |
| | `faker.username()` | `String` | Kullanıcı adı |
| **İletişim** | `faker.email()` | `String` | E-posta adresi |
| | `faker.phone()` | `String` | Telefon numarası |
| | `faker.company()` | `String` | Şirket adı |
| **Sayılar** | `faker.randomInt(min, max)` | `int` | Aralıkta rastgele tam sayı |
| | `faker.randomDouble(min, max)` | `double` | Aralıkta rastgele ondalık sayı |
| | `faker.randomBool()` | `bool` | Rastgele boolean |
| **Tanımlayıcılar** | `faker.uuid()` | `String` | UUID v4 dizesi |
| **Tarihler** | `faker.date()` | `DateTime` | Rastgele tarih |
| | `faker.pastDate()` | `DateTime` | Geçmişteki bir tarih |
| | `faker.futureDate()` | `DateTime` | Gelecekteki bir tarih |
| **Metin** | `faker.lorem()` | `String` | Lorem ipsum kelimeleri |
| | `faker.sentences()` | `String` | Birden fazla cümle |
| | `faker.paragraphs()` | `String` | Birden fazla paragraf |
| | `faker.slug()` | `String` | URL slug'ı |
| **Web** | `faker.url()` | `String` | URL dizesi |
| | `faker.imageUrl()` | `String` | Resim URL'si (picsum.photos üzerinden) |
| | `faker.ipAddress()` | `String` | IPv4 adresi |
| | `faker.macAddress()` | `String` | MAC adresi |
| **Konum** | `faker.address()` | `String` | Sokak adresi |
| | `faker.city()` | `String` | Şehir adı |
| | `faker.state()` | `String` | ABD eyalet kısaltması |
| | `faker.zipCode()` | `String` | Posta kodu |
| | `faker.country()` | `String` | Ülke adı |
| **Diğer** | `faker.hexColor()` | `String` | Hex renk kodu |
| | `faker.creditCardNumber()` | `String` | Kredi kartı numarası |
| | `faker.randomElement(list)` | `T` | Listeden rastgele öğe |
| | `faker.randomElements(list, count)` | `List<T>` | Listeden rastgele öğeler |

<div id="test-cache"></div>

## Test Önbelleği

`NyTestCache`, önbellekle ilgili işlevselliği test etmek için bellek içi önbellek sağlar:

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

## Platform Kanalı Mocklama

`NyMockChannels`, testlerin çökmemesi için yaygın platform kanallarını otomatik olarak mocklar:

``` dart
void main() {
  NyTest.init(); // Automatically sets up mock channels

  // Or set up manually
  NyMockChannels.setup();
}
```

### Mocklanan Kanallar

- **path_provider** -- belgeler, geçici, uygulama desteği, kütüphane ve önbellek dizinleri
- **flutter_secure_storage** -- bellek içi güvenli depolama
- **flutter_timezone** -- saat dilimi verileri
- **flutter_local_notifications** -- bildirim kanalı
- **sqflite** -- veritabanı işlemleri

### Yolları Geçersiz Kılma

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### Testlerde Güvenli Depolama

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## Route Guard Mocklama

`NyMockRouteGuard`, gerçek kimlik doğrulama veya ağ çağrıları olmadan route guard davranışını test etmenizi sağlar. `NyRouteGuard`'ı genişletir ve yaygın senaryolar için factory yapıcıları sağlar.

### Her Zaman Geçiren Guard

``` dart
final guard = NyMockRouteGuard.pass();
```

### Yönlendiren Guard

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// With additional data
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Özel Mantıklı Guard

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // abort navigation
  }
  return GuardResult.next; // allow navigation
});
```

### Guard Çağrılarını İzleme

Bir guard çağrıldıktan sonra durumunu inceleyebilirsiniz:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Access the RouteContext from the last call
RouteContext? context = guard.lastContext;

// Reset tracking
guard.reset();
```

<div id="assertions"></div>

## Doğrulamalar

{{ config('app.name') }} özel doğrulama fonksiyonları sağlar:

### Rota Doğrulamaları

``` dart
expectRoute('/home');           // Assert current route
expectNotRoute('/login');       // Assert not on route
expectRouteInHistory('/home');  // Assert route was visited
expectRouteExists('/profile');  // Assert route is registered
expectRoutesExist(['/home', '/profile', '/settings']);
```

### Durum Doğrulamaları

``` dart
expectBackpackContains("key");                        // Key exists
expectBackpackContains("key", value: "expected");     // Key has value
expectBackpackNotContains("key");                     // Key doesn't exist
```

### Kimlik Doğrulama Doğrulamaları

``` dart
expectAuthenticated<User>();  // User is authenticated
expectGuest();                // No user authenticated
```

### Ortam Doğrulamaları

``` dart
expectEnv("APP_NAME", "MyApp");  // Env variable equals value
expectEnvSet("APP_KEY");          // Env variable is set
```

### Mod Doğrulamaları

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### API Doğrulamaları

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### Yerel Ayar Doğrulamaları

``` dart
expectLocale("en");
```

### Toast Doğrulamaları

Bir test sırasında kaydedilen toast bildirimlerini doğrulayın. Test setUp'ınızda `NyToastRecorder.setup()` gerektirir:

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

**NyToastRecorder** testler sırasında toast bildirimlerini takip eder:

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

### Kilit ve Yükleme Doğrulamaları

`NyPage`/`NyState` widget'larında adlandırılmış kilit ve yükleme durumlarını doğrulayın:

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

## Özel Eşleştiriciler

`expect()` ile özel eşleştiriciler kullanın:

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

## Durum Testi

Durum test yardımcılarını kullanarak `NyPage` ve `NyState` widget'larında EventBus tabanlı durum yönetimini test edin.

### Durum Güncellemeleri Tetikleme

Normalde başka bir widget veya controller'dan gelecek durum güncellemelerini simüle edin:

``` dart
// Fire an UpdateState event
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### Durum Eylemleri Tetikleme

Sayfanızda `whenStateAction()` tarafından işlenen durum eylemleri gönderin:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// With additional data
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### Durum Doğrulamaları

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

Tetiklenen durum güncellemelerini ve eylemlerini takip edin ve inceleyin:

``` dart
// Get all updates fired to a state
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Get all actions fired to a state
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Reset all tracked state updates and actions
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## Hata Ayıklama

### dump

Mevcut test durumunu yazdırın (Backpack içeriği, auth kullanıcısı, zaman, API çağrıları, yerel ayar):

``` dart
NyTest.dump();
```

### dd (Dump and Die)

Test durumunu yazdırın ve testi hemen sonlandırın:

``` dart
NyTest.dd();
```

### Test Durumu Depolama

Test sırasında değerleri saklayın ve alın:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Backpack'i Doldurmak

Backpack'i test verileriyle önceden doldurun:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## Örnekler

### Tam Test Dosyası

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
