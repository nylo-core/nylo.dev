# Testing

---

<a name="section-1"></a>
- [परिचय](#introduction "परिचय")
- [शुरुआत करना](#getting-started "शुरुआत करना")
- [टेस्ट लिखना](#writing-tests "टेस्ट लिखना")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [विजेट टेस्टिंग उपयोगिताएँ](#widget-testing-utilities "विजेट टेस्टिंग उपयोगिताएँ")
  - [nyGroup](#ny-group "nyGroup")
  - [टेस्ट जीवनचक्र](#test-lifecycle "टेस्ट जीवनचक्र")
  - [टेस्ट छोड़ना और CI टेस्ट](#skipping-tests "टेस्ट छोड़ना और CI टेस्ट")
- [प्रमाणीकरण](#authentication "प्रमाणीकरण")
- [टाइम ट्रैवल](#time-travel "टाइम ट्रैवल")
- [API मॉकिंग](#api-mocking "API मॉकिंग")
  - [URL पैटर्न द्वारा मॉकिंग](#mocking-by-url "URL पैटर्न द्वारा मॉकिंग")
  - [API सर्विस टाइप द्वारा मॉकिंग](#mocking-by-type "API सर्विस टाइप द्वारा मॉकिंग")
  - [कॉल हिस्ट्री और असर्शन](#call-history "कॉल हिस्ट्री और असर्शन")
- [फैक्ट्रीज़](#factories "फैक्ट्रीज़")
  - [फैक्ट्री परिभाषित करना](#defining-factories "फैक्ट्री परिभाषित करना")
  - [फैक्ट्री स्टेट्स](#factory-states "फैक्ट्री स्टेट्स")
  - [इंस्टेंस बनाना](#creating-instances "इंस्टेंस बनाना")
- [NyFaker](#ny-faker "NyFaker")
- [टेस्ट कैश](#test-cache "टेस्ट कैश")
- [प्लेटफ़ॉर्म चैनल मॉकिंग](#platform-channel-mocking "प्लेटफ़ॉर्म चैनल मॉकिंग")
- [रूट गार्ड मॉकिंग](#route-guard-mocking "रूट गार्ड मॉकिंग")
- [असर्शन](#assertions "असर्शन")
- [कस्टम मैचर्स](#custom-matchers "कस्टम मैचर्स")
- [स्टेट टेस्टिंग](#state-testing "स्टेट टेस्टिंग")
- [डीबगिंग](#debugging "डीबगिंग")
- [उदाहरण](#examples "उदाहरण")

<div id="introduction"></div>

## परिचय

{{ config('app.name') }} v7 में Laravel की टेस्टिंग उपयोगिताओं से प्रेरित एक व्यापक टेस्टिंग फ्रेमवर्क शामिल है। यह प्रदान करता है:

- स्वचालित सेटअप/टियरडाउन के साथ **टेस्ट फंक्शन** (`nyTest`, `nyWidgetTest`, `nyGroup`)
- `NyTest.actingAs<T>()` के माध्यम से **प्रमाणीकरण सिमुलेशन**
- टेस्ट में समय को फ़्रीज़ या बदलने के लिए **टाइम ट्रैवल**
- URL पैटर्न मैचिंग और कॉल ट्रैकिंग के साथ **API मॉकिंग**
- बिल्ट-इन फ़ेक डेटा जनरेटर (`NyFaker`) के साथ **फैक्ट्रीज़**
- सिक्योर स्टोरेज, पाथ प्रोवाइडर, और अधिक के लिए **प्लेटफ़ॉर्म चैनल मॉकिंग**
- रूट, Backpack, प्रमाणीकरण, और एनवायरनमेंट के लिए **कस्टम असर्शन**

<div id="getting-started"></div>

## शुरुआत करना

अपनी टेस्ट फ़ाइल के शीर्ष पर टेस्ट फ्रेमवर्क इनिशियलाइज़ करें:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` टेस्ट एनवायरनमेंट सेट अप करता है और जब `autoReset: true` (डिफ़ॉल्ट) होता है तो टेस्ट के बीच स्वचालित स्टेट रीसेट सक्षम करता है।

<div id="writing-tests"></div>

## टेस्ट लिखना

<div id="ny-test"></div>

### nyTest

टेस्ट लिखने के लिए प्राथमिक फंक्शन:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

विकल्प:

``` dart
nyTest('my test', () async {
  // test body
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

`WidgetTester` के साथ Flutter विजेट्स को टेस्ट करने के लिए:

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

### विजेट टेस्टिंग उपयोगिताएँ

`NyWidgetTest` क्लास और `WidgetTester` एक्सटेंशन सही थीम सपोर्ट के साथ Nylo विजेट्स को पंप करने, `init()` पूरा होने तक प्रतीक्षा करने, और लोडिंग स्टेट्स टेस्ट करने के लिए हेल्पर्स प्रदान करते हैं।

#### टेस्ट एनवायरनमेंट कॉन्फ़िगर करना

Google Fonts फ़ेचिंग को अक्षम करने और वैकल्पिक रूप से कस्टम थीम सेट करने के लिए अपने `setUpAll` में `NyWidgetTest.configure()` कॉल करें:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

आप `NyWidgetTest.reset()` से कॉन्फ़िगरेशन रीसेट कर सकते हैं।

फ़ॉन्ट-रहित टेस्टिंग के लिए दो बिल्ट-इन थीम उपलब्ध हैं:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Nylo विजेट्स पंप करना

थीम सपोर्ट के साथ विजेट को `MaterialApp` में रैप करने के लिए `pumpNyWidget` का उपयोग करें:

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

फ़ॉन्ट-रहित थीम के साथ त्वरित पंप के लिए:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Init की प्रतीक्षा करना

`pumpNyWidgetAndWaitForInit` तब तक फ्रेम पंप करता है जब तक लोडिंग इंडिकेटर गायब न हो जाएँ (या टाइमआउट न हो जाए), जो async `init()` मेथड वाले पेजों के लिए उपयोगी है:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() has completed
expect(find.text('Loaded Data'), findsOneWidget);
```

#### पंप हेल्पर्स

``` dart
// Pump frames until a specific widget appears
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Settle gracefully (won't throw on timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### लाइफ़साइकल सिमुलेशन

विजेट ट्री में किसी भी `NyPage` पर `AppLifecycleState` परिवर्तन सिमुलेट करें:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Assert side effects of the paused lifecycle action
```

#### लोडिंग और लॉक जाँच

`NyPage`/`NyState` विजेट्स पर नामित लोडिंग कुंजियों और लॉक की जाँच करें:

``` dart
// Check if a named loading key is active
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Check if a named lock is held
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Check for any loading indicator (CircularProgressIndicator or Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### testNyPage हेल्पर

एक सुविधाजनक फंक्शन जो `NyPage` को पंप करता है, init की प्रतीक्षा करता है, फिर आपकी अपेक्षाएँ चलाता है:

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

#### testNyPageLoading हेल्पर

टेस्ट करें कि `init()` के दौरान पेज लोडिंग इंडिकेटर दिखाता है:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

सामान्य पेज टेस्टिंग उपयोगिताएँ प्रदान करने वाला एक mixin:

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

संबंधित टेस्ट को एक साथ समूहित करें:

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

### टेस्ट जीवनचक्र

जीवनचक्र हुक का उपयोग करके सेटअप और टियरडाउन लॉजिक सेट करें:

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

### टेस्ट छोड़ना और CI टेस्ट

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

## प्रमाणीकरण

टेस्ट में प्रमाणित उपयोगकर्ताओं का सिमुलेशन करें:

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

उपयोगकर्ता को लॉग आउट करें:

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## टाइम ट्रैवल

`NyTime` का उपयोग करके अपने टेस्ट में समय को नियंत्रित करें:

### किसी निश्चित तारीख़ पर जाएँ

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Reset to real time
});
```

### समय आगे या पीछे करें

``` dart
NyTest.travelForward(Duration(days: 30)); // Jump 30 days ahead
NyTest.travelBackward(Duration(hours: 2)); // Go back 2 hours
```

### समय फ़्रीज़ करें

``` dart
NyTest.freezeTime(); // Freeze at the current moment

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Time hasn't moved

NyTest.travelBack(); // Unfreeze
```

### समय सीमाएँ

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1st of current month
NyTime.travelToEndOfMonth();   // Last day of current month
NyTime.travelToStartOfYear();  // Jan 1st
NyTime.travelToEndOfYear();    // Dec 31st
```

### स्कोप्ड टाइम ट्रैवल

फ़्रोज़न टाइम कॉन्टेक्स्ट के अंदर कोड निष्पादित करें:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Time is automatically restored after the callback
```

<div id="api-mocking"></div>

## API मॉकिंग

<div id="mocking-by-url"></div>

### URL पैटर्न द्वारा मॉकिंग

वाइल्डकार्ड सपोर्ट के साथ URL पैटर्न का उपयोग करके API रिस्पॉन्स मॉक करें:

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

### API सर्विस टाइप द्वारा मॉकिंग

टाइप द्वारा पूरी API सर्विस को मॉक करें:

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

### कॉल हिस्ट्री और असर्शन

API कॉल को ट्रैक और सत्यापित करें:

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

### मॉक रिस्पॉन्स बनाना

``` dart
Response<Map<String, dynamic>> response = NyMockApi.createResponse(
  data: {'id': 1, 'name': 'Anthony'},
  statusCode: 200,
  statusMessage: 'OK',
);
```

<div id="factories"></div>

## फैक्ट्रीज़

<div id="defining-factories"></div>

### फैक्ट्री परिभाषित करना

अपने मॉडल के टेस्ट इंस्टेंस बनाने का तरीका परिभाषित करें:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

ओवरराइड सपोर्ट के साथ:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### फैक्ट्री स्टेट्स

फैक्ट्री की विविधताएँ परिभाषित करें:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### इंस्टेंस बनाना

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

`NyFaker` टेस्ट के लिए वास्तविक जैसा फ़ेक डेटा जनरेट करता है। यह फैक्ट्री परिभाषाओं के अंदर उपलब्ध है और सीधे भी इंस्टेंशिएट किया जा सकता है।

``` dart
NyFaker faker = NyFaker();
```

### उपलब्ध मेथड

| श्रेणी | मेथड | रिटर्न टाइप | विवरण |
|----------|--------|-------------|-------------|
| **नाम** | `faker.firstName()` | `String` | यादृच्छिक पहला नाम |
| | `faker.lastName()` | `String` | यादृच्छिक अंतिम नाम |
| | `faker.name()` | `String` | पूरा नाम (पहला + अंतिम) |
| | `faker.username()` | `String` | यूज़रनेम स्ट्रिंग |
| **संपर्क** | `faker.email()` | `String` | ईमेल पता |
| | `faker.phone()` | `String` | फ़ोन नंबर |
| | `faker.company()` | `String` | कंपनी का नाम |
| **संख्याएँ** | `faker.randomInt(min, max)` | `int` | रेंज में यादृच्छिक पूर्णांक |
| | `faker.randomDouble(min, max)` | `double` | रेंज में यादृच्छिक डबल |
| | `faker.randomBool()` | `bool` | यादृच्छिक बूलियन |
| **पहचानकर्ता** | `faker.uuid()` | `String` | UUID v4 स्ट्रिंग |
| **तिथियाँ** | `faker.date()` | `DateTime` | यादृच्छिक तिथि |
| | `faker.pastDate()` | `DateTime` | अतीत की तिथि |
| | `faker.futureDate()` | `DateTime` | भविष्य की तिथि |
| **टेक्स्ट** | `faker.lorem()` | `String` | लोरेम इप्सम शब्द |
| | `faker.sentences()` | `String` | कई वाक्य |
| | `faker.paragraphs()` | `String` | कई पैराग्राफ |
| | `faker.slug()` | `String` | URL स्लग |
| **वेब** | `faker.url()` | `String` | URL स्ट्रिंग |
| | `faker.imageUrl()` | `String` | इमेज URL (picsum.photos द्वारा) |
| | `faker.ipAddress()` | `String` | IPv4 पता |
| | `faker.macAddress()` | `String` | MAC पता |
| **स्थान** | `faker.address()` | `String` | सड़क का पता |
| | `faker.city()` | `String` | शहर का नाम |
| | `faker.state()` | `String` | US राज्य का संक्षिप्त नाम |
| | `faker.zipCode()` | `String` | ज़िप कोड |
| | `faker.country()` | `String` | देश का नाम |
| **अन्य** | `faker.hexColor()` | `String` | हेक्स रंग कोड |
| | `faker.creditCardNumber()` | `String` | क्रेडिट कार्ड नंबर |
| | `faker.randomElement(list)` | `T` | सूची से यादृच्छिक आइटम |
| | `faker.randomElements(list, count)` | `List<T>` | सूची से यादृच्छिक आइटम |

<div id="test-cache"></div>

## टेस्ट कैश

`NyTestCache` कैश-संबंधित कार्यक्षमता के परीक्षण के लिए एक इन-मेमोरी कैश प्रदान करता है:

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

## प्लेटफ़ॉर्म चैनल मॉकिंग

`NyMockChannels` स्वचालित रूप से सामान्य प्लेटफ़ॉर्म चैनल को मॉक करता है ताकि टेस्ट क्रैश न हों:

``` dart
void main() {
  NyTest.init(); // Automatically sets up mock channels

  // Or set up manually
  NyMockChannels.setup();
}
```

### मॉक किए गए चैनल

- **path_provider** -- डॉक्यूमेंट्स, टेम्पररी, एप्लिकेशन सपोर्ट, लाइब्रेरी, और कैश डायरेक्टरीज़
- **flutter_secure_storage** -- इन-मेमोरी सिक्योर स्टोरेज
- **flutter_timezone** -- टाइमज़ोन डेटा
- **flutter_local_notifications** -- नोटिफ़िकेशन चैनल
- **sqflite** -- डेटाबेस ऑपरेशन

### पथ ओवरराइड करें

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### टेस्ट में सिक्योर स्टोरेज

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## रूट गार्ड मॉकिंग

`NyMockRouteGuard` आपको वास्तविक प्रमाणीकरण या नेटवर्क कॉल के बिना रूट गार्ड व्यवहार का परीक्षण करने देता है। यह `NyRouteGuard` को एक्सटेंड करता है और सामान्य परिदृश्यों के लिए फैक्ट्री कंस्ट्रक्टर प्रदान करता है।

### गार्ड जो हमेशा पास करता है

``` dart
final guard = NyMockRouteGuard.pass();
```

### गार्ड जो रीडायरेक्ट करता है

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// With additional data
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### कस्टम लॉजिक वाला गार्ड

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // abort navigation
  }
  return GuardResult.next; // allow navigation
});
```

### गार्ड कॉल ट्रैकिंग

गार्ड को इनवोक करने के बाद आप उसकी स्थिति की जाँच कर सकते हैं:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Access the RouteContext from the last call
RouteContext? context = guard.lastContext;

// Reset tracking
guard.reset();
```

<div id="assertions"></div>

## असर्शन

{{ config('app.name') }} कस्टम असर्शन फंक्शन प्रदान करता है:

### रूट असर्शन

``` dart
expectRoute('/home');           // Assert current route
expectNotRoute('/login');       // Assert not on route
expectRouteInHistory('/home');  // Assert route was visited
expectRouteExists('/profile');  // Assert route is registered
expectRoutesExist(['/home', '/profile', '/settings']);
```

### स्टेट असर्शन

``` dart
expectBackpackContains("key");                        // Key exists
expectBackpackContains("key", value: "expected");     // Key has value
expectBackpackNotContains("key");                     // Key doesn't exist
```

### Auth असर्शन

``` dart
expectAuthenticated<User>();  // User is authenticated
expectGuest();                // No user authenticated
```

### एनवायरनमेंट असर्शन

``` dart
expectEnv("APP_NAME", "MyApp");  // Env variable equals value
expectEnvSet("APP_KEY");          // Env variable is set
```

### मोड असर्शन

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### API असर्शन

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### लोकेल असर्शन

``` dart
expectLocale("en");
```

### टोस्ट असर्शन

टेस्ट के दौरान रिकॉर्ड की गई टोस्ट नोटिफ़िकेशन की जाँच करें। आपके टेस्ट setUp में `NyToastRecorder.setup()` आवश्यक है:

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

**NyToastRecorder** टेस्ट के दौरान टोस्ट नोटिफ़िकेशन ट्रैक करता है:

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

### लॉक और लोडिंग असर्शन

`NyPage`/`NyState` विजेट्स में नामित लॉक और लोडिंग स्टेट्स की जाँच करें:

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

## कस्टम मैचर्स

`expect()` के साथ कस्टम मैचर्स का उपयोग करें:

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

## स्टेट टेस्टिंग

स्टेट टेस्ट हेल्पर्स का उपयोग करके `NyPage` और `NyState` विजेट्स में EventBus-चालित स्टेट मैनेजमेंट का परीक्षण करें।

### स्टेट अपडेट भेजना

उन स्टेट अपडेट को सिमुलेट करें जो सामान्यतः किसी अन्य विजेट या कंट्रोलर से आते हैं:

``` dart
// Fire an UpdateState event
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### स्टेट एक्शन भेजना

अपने पेज में `whenStateAction()` द्वारा संभाले जाने वाले स्टेट एक्शन भेजें:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// With additional data
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### स्टेट असर्शन

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

भेजे गए स्टेट अपडेट और एक्शन को ट्रैक और निरीक्षण करें:

``` dart
// Get all updates fired to a state
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Get all actions fired to a state
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Reset all tracked state updates and actions
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## डीबगिंग

### dump

वर्तमान टेस्ट स्टेट प्रिंट करें (Backpack सामग्री, auth उपयोगकर्ता, समय, API कॉल, लोकेल):

``` dart
NyTest.dump();
```

### dd (Dump and Die)

टेस्ट स्टेट प्रिंट करें और टेस्ट तुरंत समाप्त करें:

``` dart
NyTest.dd();
```

### टेस्ट स्टेट स्टोरेज

टेस्ट के दौरान मान संग्रहीत और पुनर्प्राप्त करें:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Backpack में डेटा भरें (Seed)

टेस्ट डेटा के साथ Backpack को पहले से भरें:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## उदाहरण

### पूरी टेस्ट फ़ाइल

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
