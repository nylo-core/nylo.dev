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
- [Navigasyon ve Etkileşim Yardımcıları](#nav-interaction "Navigasyon ve Etkileşim Yardımcıları")
- [Örnekler](#examples "Örnekler")

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
  // test gövdesi
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
// init() tamamlandı
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Pompa Yardımcıları

``` dart
// Belirli bir widget görünene kadar kare pompala
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Nazikçe yerleş (zaman aşımında hata fırlatmaz)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Yaşam Döngüsü Simülasyonu

Widget ağacındaki herhangi bir `NyPage` üzerinde `AppLifecycleState` değişikliklerini simüle edin:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Duraklatılmış yaşam döngüsü aksiyonunun yan etkilerini doğrula
```

#### Yükleme ve Kilit Kontrolleri

`NyPage`/`NyState` widget'larında adlandırılmış yükleme anahtarlarını ve kilitleri kontrol edin:

``` dart
// Adlandırılmış yükleme anahtarının aktif olup olmadığını kontrol et
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Adlandırılmış kilidin tutulup tutulmadığını kontrol et
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Herhangi bir yükleme göstergesini kontrol et (CircularProgressIndicator veya Skeletonizer)
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
    // init'in çağrıldığını ve yüklemenin tamamlandığını doğrula
    await verifyInitCalled(tester, HomePage(), timeout: Duration(seconds: 5));

    // init sırasında yükleme durumunun gösterildiğini doğrula
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
    // Tüm testlerden önce bir kez çalışır
  });

  nySetUp(() {
    // Her testten önce çalışır
  });

  nyTearDown(() {
    // Her testten sonra çalışır
  });

  nyTearDownAll(() {
    // Tüm testlerden sonra bir kez çalışır
  });
}
```

<div id="skipping-tests"></div>

### Testleri Atlama ve CI Testleri

``` dart
// Bir testi gerekçeyle atla
nySkip('not implemented yet', () async {
  // ...
}, "Waiting for API update");

// Başarısız olması beklenen testler
nyFailing('known bug', () async {
  // ...
});

// Yalnızca CI testleri ('ci' etiketiyle)
nyCi('integration test', () async {
  // Yalnızca CI ortamlarında çalışır
});
```

<div id="authentication"></div>

## Kimlik Doğrulama

Testlerde kimliği doğrulanmış kullanıcıları simüle edin:

``` dart
nyTest('user can access profile', () async {
  // Oturum açmış kullanıcıyı simüle et
  NyTest.actingAs<User>(User(name: "Anthony", email: "anthony@example.com"));

  // Kimlik doğrulamayı onayla
  expectAuthenticated<User>();

  // Aktif kullanıcıya eriş
  User? user = NyTest.actingUser<User>();
  expect(user?.name, equals("Anthony"));
});

nyTest('guest cannot access profile', () async {
  // Kimlik doğrulama olmadığını doğrula
  expectGuest();
});
```

Kullanıcının oturumunu kapatın:

``` dart
NyTest.logout();
expectGuest();
```

Misafir bağlamı kurarken `logout()` için okunabilir bir takma ad olarak `actingAsGuest()` kullanın:

``` dart
NyTest.actingAsGuest();
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

  NyTest.travelBack(); // Gerçek zamana sıfırla
});
```

### Zamanı İleri veya Geri Alma

``` dart
NyTest.travelForward(Duration(days: 30)); // 30 gün ileri atla
NyTest.travelBackward(Duration(hours: 2)); // 2 saat geri git
```

### Zamanı Dondurma

``` dart
NyTest.freezeTime(); // Mevcut anda dondur

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Zaman ilerlemedi

NyTest.travelBack(); // Çöz
```

### Zaman Sınırları

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // Mevcut ayın 1. günü
NyTime.travelToEndOfMonth();   // Mevcut ayın son günü
NyTime.travelToStartOfYear();  // 1 Oca
NyTime.travelToEndOfYear();    // 31 Ara
```

### Kapsamlı Zaman Yolculuğu

Dondurulmuş zaman bağlamında kod yürütün:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Geri çağrı sonrasında zaman otomatik olarak geri yüklenir
```

<div id="api-mocking"></div>

## API Mocklama

<div id="mocking-by-url"></div>

### URL Kalıbına Göre Mocklama

Joker karakter desteğiyle URL kalıplarını kullanarak API yanıtlarını mocklayın:

``` dart
nyTest('mock API responses', () async {
  // Tam URL eşleşmesi
  NyMockApi.respond('/users/1', {'id': 1, 'name': 'Anthony'});

  // Tek segmentli joker karakter (*)
  NyMockApi.respond('/users/*', {'id': 1, 'name': 'User'});

  // Çok segmentli joker karakter (**)
  NyMockApi.respond('/api/**', {'status': 'ok'});

  // Durum kodu ve başlıklarla
  NyMockApi.respond(
    '/users',
    {'error': 'Unauthorized'},
    statusCode: 401,
    method: 'POST',
    headers: {'X-Error': 'true'},
  );

  // Simüle edilmiş gecikmeyle
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

  // ... API çağrılarını tetikleyen aksiyonları gerçekleştir ...

  // Endpoint'in çağrıldığını doğrula
  expectApiCalled('/users');

  // Endpoint'in çağrılmadığını doğrula
  expectApiNotCalled('/admin');

  // Çağrı sayısını doğrula
  expectApiCalled('/users', times: 2);

  // Belirli metodu doğrula
  expectApiCalled('/users', method: 'POST');

  // Çağrı ayrıntılarını al
  List<ApiCallInfo> calls = NyMockApi.getCallsFor('/users');
});
```

Bir endpoint'in belirli istek gövdesi verileriyle çağrıldığını doğrulayın:

``` dart
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
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
// Tek bir örnek oluştur
User user = NyFactory.make<User>();

// Geçersiz kılmalarla oluştur
User admin = NyFactory.make<User>(overrides: {'name': 'Admin User'});

// Durumlar uygulanmış şekilde oluştur
User premiumAdmin = NyFactory.make<User>(states: ['admin', 'premium']);

// Birden fazla örnek oluştur
List<User> users = NyFactory.create<User>(count: 5);

// İndeks tabanlı verilerle dizi oluştur
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

  // Değer sakla
  await cache.put<String>("key", "value");

  // Son kullanma süresiyle sakla
  await cache.put<String>("temp", "data", seconds: 60);

  // Değer oku
  String? value = await cache.get<String>("key");

  // Varlığı kontrol et
  bool exists = await cache.has("key");

  // Bir anahtarı temizle
  await cache.clear("key");

  // Tümünü temizle
  await cache.flush();

  // Önbellek bilgilerini al
  int size = await cache.size();
  List<String> keys = await cache.documents();
});
```

<div id="platform-channel-mocking"></div>

## Platform Kanalı Mocklama

`NyMockChannels`, testlerin çökmemesi için yaygın platform kanallarını otomatik olarak mocklar:

``` dart
void main() {
  NyTest.init(); // Mock kanallarını otomatik olarak kurar

  // Veya manuel olarak kur
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

// Ek verilerle
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Özel Mantıklı Guard

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // navigasyonu iptal et
  }
  return GuardResult.next; // navigasyona izin ver
});
```

### Guard Çağrılarını İzleme

Bir guard çağrıldıktan sonra durumunu inceleyebilirsiniz:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Son çağrıdan RouteContext'e eriş
RouteContext? context = guard.lastContext;

// Takibi sıfırla
guard.reset();
```

<div id="assertions"></div>

## Doğrulamalar

{{ config('app.name') }} özel doğrulama fonksiyonları sağlar:

### Rota Doğrulamaları

``` dart
expectRoute('/home');           // Mevcut rotayı doğrula
expectNotRoute('/login');       // Rotada olmadığını doğrula
expectRouteInHistory('/home');  // Rotanın ziyaret edildiğini doğrula
expectRouteExists('/profile');  // Rotanın kayıtlı olduğunu doğrula
expectRoutesExist(['/home', '/profile', '/settings']);
```

### Durum Doğrulamaları

``` dart
expectBackpackContains("key");                        // Anahtar mevcut
expectBackpackContains("key", value: "expected");     // Anahtarın değeri var
expectBackpackNotContains("key");                     // Anahtar mevcut değil
```

### Kimlik Doğrulama Doğrulamaları

``` dart
expectAuthenticated<User>();  // Kullanıcı kimlik doğrulandı
expectGuest();                // Kimlik doğrulanmış kullanıcı yok
```

### Ortam Doğrulamaları

``` dart
expectEnv("APP_NAME", "MyApp");  // Ortam değişkeni değere eşit
expectEnvSet("APP_KEY");          // Ortam değişkeni ayarlandı
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
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
```

### Widget Doğrulamaları

``` dart
// Widget türünün belirli sayıda göründüğünü doğrula
expectWidgetCount(ListTile, 3);
expectWidgetCount(Icon, 0);

// Metnin görünür olduğunu doğrula
expectTextVisible('Welcome');

// Metnin görünür olmadığını doğrula
expectTextNotVisible('Error');

// Herhangi bir widget'ın görünür olduğunu doğrula (herhangi bir Finder kullan)
expectVisible(find.byType(FloatingActionButton));
expectVisible(find.byIcon(Icons.notifications));
expectVisible(find.byKey(Key('submit_btn')));

// Widget'ın görünür olmadığını doğrula
expectNotVisible(find.byType(ErrorBanner));
expectNotVisible(find.byKey(Key('loading_spinner')));
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
// Toastı manuel olarak kaydet
NyToastRecorder.record(id: 'success', title: 'Done', description: 'Saved!');

// Toastın gösterilip gösterilmediğini kontrol et
bool shown = NyToastRecorder.wasShown(id: 'success');

// Tüm kaydedilmiş toastlara eriş
List<ToastRecord> toasts = NyToastRecorder.records;

// Kaydedilmiş toastları temizle
NyToastRecorder.clear();
```

### Kilit ve Yükleme Doğrulamaları

`NyPage`/`NyState` widget'larında adlandırılmış kilit ve yükleme durumlarını doğrulayın:

``` dart
// Adlandırılmış kilidin tutulduğunu doğrula
expectLocked(tester, find.byType(MyPage), 'submit');

// Adlandırılmış kilidin tutulmadığını doğrula
expectNotLocked(tester, find.byType(MyPage), 'submit');

// Adlandırılmış yükleme anahtarının aktif olduğunu doğrula
expectLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');

// Adlandırılmış yükleme anahtarının aktif olmadığını doğrula
expectNotLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');
```

<div id="custom-matchers"></div>

## Özel Eşleştiriciler

`expect()` ile özel eşleştiriciler kullanın:

``` dart
// Tür eşleştiricisi
expect(result, isType<User>());

// Rota adı eşleştiricisi
expect(widget, hasRouteName('/home'));

// Backpack eşleştiricisi
expect(true, backpackHas("key", value: "expected"));

// API çağrısı eşleştiricisi
expect(true, apiWasCalled('/users', method: 'GET', times: 1));
```

<div id="state-testing"></div>

## Durum Testi

Durum test yardımcılarını kullanarak `NyPage` ve `NyState` widget'larında EventBus tabanlı durum yönetimini test edin.

### Durum Güncellemeleri Tetikleme

Normalde başka bir widget veya controller'dan gelecek durum güncellemelerini simüle edin:

``` dart
// UpdateState olayı tetikle
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### Durum Eylemleri Tetikleme

Sayfanızda `whenStateAction()` tarafından işlenen durum eylemleri gönderin:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// Ek verilerle
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### Durum Doğrulamaları

``` dart
// Durum güncellemesinin tetiklendiğini doğrula
expectStateUpdated('HomePageState');
expectStateUpdated('HomePageState', times: 2);

// Durum eyleminin tetiklendiğini doğrula
expectStateAction('HomePageState', 'refresh-page');
expectStateAction('CartState', 'add-item', times: 1);

// NyPage/NyState widget'ının stateData'sını doğrula
expectStateData(tester, find.byType(MyWidget), equals(42));
```

### NyStateTestHelpers

Tetiklenen durum güncellemelerini ve eylemlerini takip edin ve inceleyin:

``` dart
// Bir duruma tetiklenen tüm güncellemeleri al
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Bir duruma tetiklenen tüm aksiyonları al
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Takip edilen tüm durum güncellemelerini ve aksiyonlarını sıfırla
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

<div id="nav-interaction"></div>

## Navigasyon ve Etkileşim Yardımcıları

`WidgetTester` uzantıları, `nyWidgetTest` içinde navigasyon akışları ve UI etkileşimleri yazmak için üst düzey bir DSL sağlar.

### visit

Bir rotaya git ve sayfanın yerleşmesini bekle:

``` dart
nyWidgetTest('loads dashboard', (tester) async {
  await tester.visit(DashboardPage.path);
  expectTextVisible('Dashboard');
});
```

### assertNavigatedTo

Bir navigasyon aksiyonunun sizi beklenen rotaya götürdüğünü doğrulayın:

``` dart
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);
```

### assertOnRoute

Mevcut rotanın verilen rotayla eşleştiğini doğrulayın (nereye yeni gittiğinizi değil, nerede olduğunuzu onaylamak için kullanın):

``` dart
await tester.visit(DashboardPage.path);
tester.assertOnRoute(DashboardPage.path);
```

### settle

Tüm bekleyen animasyonların ve kare geri çağırmalarının tamamlanmasını bekle:

``` dart
await tester.tap(find.byType(MyButton));
await tester.settle();
tester.assertNavigatedTo(ProfilePage.path);
```

### navigateBack

Mevcut rotadan çık ve yerleş:

``` dart
await tester.visit(DashboardPage.path);
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);

await tester.navigateBack();
tester.assertOnRoute(DashboardPage.path);
```

### tapText

Bir widget'ı metne göre bul, dokunup tek bir çağrıda yerleş:

``` dart
await tester.tapText('Login');
await tester.tapText('Submit');
```

### fillField

Bir form alanına dokunun, metin girin ve yerleşin:

``` dart
await tester.fillField(find.byKey(Key('email')), 'test@example.com');
await tester.fillField(find.byKey(Key('password')), 'secret123');
```

### scrollTo

Widget görünür olana kadar kaydırın, ardından yerleşin:

``` dart
await tester.scrollTo(find.text('Item 50'));
await tester.tapText('Item 50');
```

Hassas kontrol için belirli bir `scrollable` finder ve `delta` geçirin:

``` dart
await tester.scrollTo(
  find.text('Footer'),
  scrollable: find.byKey(Key('main_list')),
  delta: 200,
);
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

      // ... API çağrısını tetikle ...

      expectApiCalled('/api/users');
    });
  });

  nyGroup('Time-Sensitive Features', () {
    nyTest('subscription expires correctly', () async {
      NyTest.travel(DateTime(2025, 1, 1));

      // Bilinen bir tarihte abonelik mantığını test et
      expect(NyTime.now().year, equals(2025));

      NyTest.travelForward(Duration(days: 365));
      expect(NyTime.now().year, equals(2026));

      NyTest.travelBack();
    });
  });
}
```
