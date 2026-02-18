# Testing

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [เริ่มต้นใช้งาน](#getting-started "เริ่มต้นใช้งาน")
- [การเขียนเทสต์](#writing-tests "การเขียนเทสต์")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [ยูทิลิตี้การทดสอบ Widget](#widget-testing-utilities "ยูทิลิตี้การทดสอบ Widget")
  - [nyGroup](#ny-group "nyGroup")
  - [วงจรชีวิตการทดสอบ](#test-lifecycle "วงจรชีวิตการทดสอบ")
  - [การข้ามเทสต์และเทสต์ CI](#skipping-tests "การข้ามเทสต์และเทสต์ CI")
- [การยืนยันตัวตน](#authentication "การยืนยันตัวตน")
- [การเดินทางข้ามเวลา](#time-travel "การเดินทางข้ามเวลา")
- [การจำลอง API](#api-mocking "การจำลอง API")
  - [การจำลองด้วย URL Pattern](#mocking-by-url "การจำลองด้วย URL Pattern")
  - [การจำลองด้วยประเภท API Service](#mocking-by-type "การจำลองด้วยประเภท API Service")
  - [ประวัติการเรียกและ Assertion](#call-history "ประวัติการเรียกและ Assertion")
- [Factory](#factories "Factory")
  - [การกำหนด Factory](#defining-factories "การกำหนด Factory")
  - [Factory State](#factory-states "Factory State")
  - [การสร้าง Instance](#creating-instances "การสร้าง Instance")
- [NyFaker](#ny-faker "NyFaker")
- [Test Cache](#test-cache "Test Cache")
- [การจำลอง Platform Channel](#platform-channel-mocking "การจำลอง Platform Channel")
- [การจำลอง Route Guard](#route-guard-mocking "การจำลอง Route Guard")
- [Assertion](#assertions "Assertion")
- [Custom Matcher](#custom-matchers "Custom Matcher")
- [การทดสอบ State](#state-testing "การทดสอบ State")
- [การดีบัก](#debugging "การดีบัก")
- [ตัวอย่าง](#examples "ตัวอย่างการใช้งานจริง")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} v7 มี framework การทดสอบที่ครอบคลุมซึ่งได้แรงบันดาลใจจากยูทิลิตี้การทดสอบของ Laravel ประกอบด้วย:

- **ฟังก์ชันทดสอบ** พร้อม setup/teardown อัตโนมัติ (`nyTest`, `nyWidgetTest`, `nyGroup`)
- **การจำลองการยืนยันตัวตน** ผ่าน `NyTest.actingAs<T>()`
- **การเดินทางข้ามเวลา** เพื่อหยุดหรือจัดการเวลาในเทสต์
- **การจำลอง API** พร้อม URL pattern matching และการติดตามการเรียก
- **Factory** พร้อมตัวสร้างข้อมูลปลอมในตัว (`NyFaker`)
- **การจำลอง platform channel** สำหรับ secure storage, path provider และอื่นๆ
- **Custom assertion** สำหรับ route, Backpack, การยืนยันตัวตน และ environment

<div id="getting-started"></div>

## เริ่มต้นใช้งาน

เริ่มต้น test framework ที่ด้านบนของไฟล์ทดสอบ:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` ตั้งค่าสภาพแวดล้อมการทดสอบและเปิดใช้การรีเซ็ต state อัตโนมัติระหว่างเทสต์เมื่อ `autoReset: true` (ค่าเริ่มต้น)

<div id="writing-tests"></div>

## การเขียนเทสต์

<div id="ny-test"></div>

### nyTest

ฟังก์ชันหลักสำหรับเขียนเทสต์:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

ตัวเลือก:

``` dart
nyTest('my test', () async {
  // test body
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

สำหรับทดสอบ Flutter widget ด้วย `WidgetTester`:

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

### ยูทิลิตี้การทดสอบ Widget

คลาส `NyWidgetTest` และ extension ของ `WidgetTester` มี helper สำหรับ pump widget ของ Nylo พร้อมรองรับ theme, รอ `init()` เสร็จ และทดสอบ loading state

#### การกำหนดค่าสภาพแวดล้อมทดสอบ

เรียก `NyWidgetTest.configure()` ใน `setUpAll` เพื่อปิดการดึง Google Fonts และตั้งค่า theme กำหนดเอง:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

คุณสามารถรีเซ็ตการกำหนดค่าด้วย `NyWidgetTest.reset()`

มี theme ในตัวสองแบบสำหรับการทดสอบแบบไม่ใช้ฟอนต์:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### การ Pump Widget ของ Nylo

ใช้ `pumpNyWidget` เพื่อห่อ widget ใน `MaterialApp` พร้อมรองรับ theme:

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

สำหรับ pump อย่างรวดเร็วพร้อม theme ที่ไม่ใช้ฟอนต์:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### การรอ Init

`pumpNyWidgetAndWaitForInit` จะ pump frame จนกว่า loading indicator จะหายไป (หรือถึง timeout) ซึ่งมีประโยชน์สำหรับหน้าเพจที่มีเมธอด `init()` แบบ async:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() เสร็จแล้ว
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Pump Helper

``` dart
// Pump frame จนกว่า widget เฉพาะจะปรากฏ
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Settle อย่างนุ่มนวล (จะไม่ throw เมื่อ timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### การจำลอง Lifecycle

จำลองการเปลี่ยนแปลง `AppLifecycleState` บน `NyPage` ใดก็ได้ใน widget tree:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// ตรวจสอบผลข้างเคียงจาก lifecycle action ที่หยุดชั่วคราว
```

#### การตรวจสอบ Loading และ Lock

ตรวจสอบ loading key และ lock ที่มีชื่อบน widget `NyPage`/`NyState`:

``` dart
// ตรวจสอบว่า loading key ที่มีชื่อกำลังทำงานอยู่
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// ตรวจสอบว่า lock ที่มีชื่อถูกถือไว้
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// ตรวจสอบ loading indicator ใดก็ได้ (CircularProgressIndicator หรือ Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### testNyPage Helper

ฟังก์ชันที่สะดวกซึ่ง pump `NyPage`, รอ init, จากนั้นรัน expectation ของคุณ:

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

ทดสอบว่าหน้าเพจแสดง loading indicator ระหว่าง `init()`:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

Mixin ที่มียูทิลิตี้การทดสอบหน้าเพจทั่วไป:

``` dart
class HomePageTest with NyPageTestMixin {
  void runTests(WidgetTester tester) async {
    // ตรวจสอบว่า init ถูกเรียกและ loading เสร็จ
    await verifyInitCalled(tester, HomePage(), timeout: Duration(seconds: 5));

    // ตรวจสอบว่า loading state แสดงขึ้นระหว่าง init
    await verifyLoadingState(tester, HomePage());
  }
}
```

<div id="ny-group"></div>

### nyGroup

จัดกลุ่มเทสต์ที่เกี่ยวข้องเข้าด้วยกัน:

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

### วงจรชีวิตการทดสอบ

ตั้งค่าลอจิก setup และ tear down โดยใช้ lifecycle hook:

``` dart
void main() {
  NyTest.init();

  nySetUpAll(() {
    // รันครั้งเดียวก่อนเทสต์ทั้งหมด
  });

  nySetUp(() {
    // รันก่อนแต่ละเทสต์
  });

  nyTearDown(() {
    // รันหลังแต่ละเทสต์
  });

  nyTearDownAll(() {
    // รันครั้งเดียวหลังเทสต์ทั้งหมด
  });
}
```

<div id="skipping-tests"></div>

### การข้ามเทสต์และเทสต์ CI

``` dart
// ข้ามเทสต์พร้อมเหตุผล
nySkip('not implemented yet', () async {
  // ...
}, "Waiting for API update");

// เทสต์ที่คาดว่าจะล้มเหลว
nyFailing('known bug', () async {
  // ...
});

// เทสต์เฉพาะ CI (แท็กด้วย 'ci')
nyCi('integration test', () async {
  // รันเฉพาะในสภาพแวดล้อม CI
});
```

<div id="authentication"></div>

## การยืนยันตัวตน

จำลองผู้ใช้ที่ยืนยันตัวตนแล้วในเทสต์:

``` dart
nyTest('user can access profile', () async {
  // จำลองผู้ใช้ที่เข้าสู่ระบบ
  NyTest.actingAs<User>(User(name: "Anthony", email: "anthony@example.com"));

  // ตรวจสอบว่ายืนยันตัวตนแล้ว
  expectAuthenticated<User>();

  // เข้าถึงผู้ใช้ที่กำลังทดสอบ
  User? user = NyTest.actingUser<User>();
  expect(user?.name, equals("Anthony"));
});

nyTest('guest cannot access profile', () async {
  // ตรวจสอบว่าไม่ได้ยืนยันตัวตน
  expectGuest();
});
```

ออกจากระบบผู้ใช้:

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## การเดินทางข้ามเวลา

จัดการเวลาในเทสต์ของคุณโดยใช้ `NyTime`:

### ข้ามไปยังวันที่เฉพาะ

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // รีเซ็ตกลับเป็นเวลาจริง
});
```

### เลื่อนเวลาไปข้างหน้าหรือย้อนกลับ

``` dart
NyTest.travelForward(Duration(days: 30)); // ข้ามไป 30 วันข้างหน้า
NyTest.travelBackward(Duration(hours: 2)); // ย้อนกลับ 2 ชั่วโมง
```

### หยุดเวลา

``` dart
NyTest.freezeTime(); // หยุดเวลาที่ช่วงเวลาปัจจุบัน

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // เวลาไม่เปลี่ยน

NyTest.travelBack(); // ปลดหยุดเวลา
```

### ขอบเขตเวลา

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // วันที่ 1 ของเดือนปัจจุบัน
NyTime.travelToEndOfMonth();   // วันสุดท้ายของเดือนปัจจุบัน
NyTime.travelToStartOfYear();  // 1 มกราคม
NyTime.travelToEndOfYear();    // 31 ธันวาคม
```

### การเดินทางข้ามเวลาแบบมีขอบเขต

รันโค้ดภายในบริบทที่หยุดเวลาไว้:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// เวลาจะถูกคืนค่าอัตโนมัติหลัง callback
```

<div id="api-mocking"></div>

## การจำลอง API

<div id="mocking-by-url"></div>

### การจำลองด้วย URL Pattern

จำลอง response ของ API โดยใช้ URL pattern พร้อมรองรับ wildcard:

``` dart
nyTest('mock API responses', () async {
  // จับคู่ URL แบบตรง
  NyMockApi.respond('/users/1', {'id': 1, 'name': 'Anthony'});

  // wildcard แบบ segment เดียว (*)
  NyMockApi.respond('/users/*', {'id': 1, 'name': 'User'});

  // wildcard แบบหลาย segment (**)
  NyMockApi.respond('/api/**', {'status': 'ok'});

  // พร้อม status code และ header
  NyMockApi.respond(
    '/users',
    {'error': 'Unauthorized'},
    statusCode: 401,
    method: 'POST',
    headers: {'X-Error': 'true'},
  );

  // พร้อมความล่าช้าจำลอง
  NyMockApi.respond(
    '/slow-endpoint',
    {'data': 'loaded'},
    delay: Duration(seconds: 2),
  );
});
```

<div id="mocking-by-type"></div>

### การจำลองด้วยประเภท API Service

จำลอง API service ทั้งหมดตามประเภท:

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

### ประวัติการเรียกและ Assertion

ติดตามและตรวจสอบการเรียก API:

``` dart
nyTest('verify API was called', () async {
  NyMockApi.setRecordCalls(true);

  // ... ดำเนินการที่ trigger การเรียก API ...

  // ยืนยันว่า endpoint ถูกเรียก
  expectApiCalled('/users');

  // ยืนยันว่า endpoint ไม่ถูกเรียก
  expectApiNotCalled('/admin');

  // ยืนยันจำนวนครั้งที่เรียก
  expectApiCalled('/users', times: 2);

  // ยืนยัน method เฉพาะ
  expectApiCalled('/users', method: 'POST');

  // ดึงรายละเอียดการเรียก
  List<ApiCallInfo> calls = NyMockApi.getCallsFor('/users');
});
```

### การสร้าง Mock Response

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

### การกำหนด Factory

กำหนดวิธีสร้าง instance ทดสอบของ model:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

พร้อมรองรับการ override:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### Factory State

กำหนดรูปแบบต่างๆ ของ factory:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### การสร้าง Instance

``` dart
// สร้าง instance เดียว
User user = NyFactory.make<User>();

// สร้างพร้อม override
User admin = NyFactory.make<User>(overrides: {'name': 'Admin User'});

// สร้างพร้อมใช้ state
User premiumAdmin = NyFactory.make<User>(states: ['admin', 'premium']);

// สร้างหลาย instance
List<User> users = NyFactory.create<User>(count: 5);

// สร้าง sequence พร้อมข้อมูลตาม index
List<User> numbered = NyFactory.sequence<User>(3, (int index, NyFaker faker) {
  return User(name: "User ${index + 1}", email: faker.email());
});
```

<div id="ny-faker"></div>

## NyFaker

`NyFaker` สร้างข้อมูลปลอมที่สมจริงสำหรับเทสต์ สามารถใช้ได้ภายใน factory definition และสร้าง instance โดยตรง

``` dart
NyFaker faker = NyFaker();
```

### เมธอดที่ใช้ได้

| หมวดหมู่ | เมธอด | ชนิดส่งกลับ | คำอธิบาย |
|----------|--------|-------------|-------------|
| **ชื่อ** | `faker.firstName()` | `String` | ชื่อจริงแบบสุ่ม |
| | `faker.lastName()` | `String` | นามสกุลแบบสุ่ม |
| | `faker.name()` | `String` | ชื่อเต็ม (ชื่อจริง + นามสกุล) |
| | `faker.username()` | `String` | สตริง username |
| **ข้อมูลติดต่อ** | `faker.email()` | `String` | ที่อยู่อีเมล |
| | `faker.phone()` | `String` | หมายเลขโทรศัพท์ |
| | `faker.company()` | `String` | ชื่อบริษัท |
| **ตัวเลข** | `faker.randomInt(min, max)` | `int` | จำนวนเต็มสุ่มในช่วง |
| | `faker.randomDouble(min, max)` | `double` | ทศนิยมสุ่มในช่วง |
| | `faker.randomBool()` | `bool` | ค่า boolean สุ่ม |
| **ตัวระบุ** | `faker.uuid()` | `String` | สตริง UUID v4 |
| **วันที่** | `faker.date()` | `DateTime` | วันที่สุ่ม |
| | `faker.pastDate()` | `DateTime` | วันที่ในอดีต |
| | `faker.futureDate()` | `DateTime` | วันที่ในอนาคต |
| **ข้อความ** | `faker.lorem()` | `String` | คำ lorem ipsum |
| | `faker.sentences()` | `String` | หลายประโยค |
| | `faker.paragraphs()` | `String` | หลายย่อหน้า |
| | `faker.slug()` | `String` | URL slug |
| **เว็บ** | `faker.url()` | `String` | สตริง URL |
| | `faker.imageUrl()` | `String` | URL รูปภาพ (ผ่าน picsum.photos) |
| | `faker.ipAddress()` | `String` | ที่อยู่ IPv4 |
| | `faker.macAddress()` | `String` | ที่อยู่ MAC |
| **สถานที่** | `faker.address()` | `String` | ที่อยู่ถนน |
| | `faker.city()` | `String` | ชื่อเมือง |
| | `faker.state()` | `String` | ตัวย่อรัฐสหรัฐ |
| | `faker.zipCode()` | `String` | รหัสไปรษณีย์ |
| | `faker.country()` | `String` | ชื่อประเทศ |
| **อื่นๆ** | `faker.hexColor()` | `String` | รหัสสี hex |
| | `faker.creditCardNumber()` | `String` | หมายเลขบัตรเครดิต |
| | `faker.randomElement(list)` | `T` | รายการสุ่มจาก list |
| | `faker.randomElements(list, count)` | `List<T>` | รายการสุ่มหลายรายการจาก list |

<div id="test-cache"></div>

## Test Cache

`NyTestCache` มี cache ในหน่วยความจำสำหรับทดสอบฟังก์ชันที่เกี่ยวข้องกับ cache:

``` dart
nyTest('cache operations', () async {
  NyTestCache cache = NyTest.cache;

  // จัดเก็บค่า
  await cache.put<String>("key", "value");

  // จัดเก็บพร้อมกำหนดเวลาหมดอายุ
  await cache.put<String>("temp", "data", seconds: 60);

  // อ่านค่า
  String? value = await cache.get<String>("key");

  // ตรวจสอบว่ามีอยู่
  bool exists = await cache.has("key");

  // ล้างคีย์
  await cache.clear("key");

  // ล้างทั้งหมด
  await cache.flush();

  // ดูข้อมูล cache
  int size = await cache.size();
  List<String> keys = await cache.documents();
});
```

<div id="platform-channel-mocking"></div>

## การจำลอง Platform Channel

`NyMockChannels` จำลอง platform channel ทั่วไปโดยอัตโนมัติเพื่อไม่ให้เทสต์ crash:

``` dart
void main() {
  NyTest.init(); // ตั้งค่า mock channel อัตโนมัติ

  // หรือตั้งค่าด้วยตนเอง
  NyMockChannels.setup();
}
```

### Channel ที่ถูกจำลอง

- **path_provider** -- ไดเรกทอรี documents, temporary, application support, library และ cache
- **flutter_secure_storage** -- secure storage ในหน่วยความจำ
- **flutter_timezone** -- ข้อมูล timezone
- **flutter_local_notifications** -- ช่อง notification
- **sqflite** -- การดำเนินการฐานข้อมูล

### Override Path

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### Secure Storage ในเทสต์

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## การจำลอง Route Guard

`NyMockRouteGuard` ช่วยให้คุณทดสอบพฤติกรรมของ route guard โดยไม่ต้องยืนยันตัวตนจริงหรือเรียก network โดยขยายจาก `NyRouteGuard` และมี factory constructor สำหรับสถานการณ์ทั่วไป

### Guard ที่ผ่านเสมอ

``` dart
final guard = NyMockRouteGuard.pass();
```

### Guard ที่ Redirect

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// พร้อมข้อมูลเพิ่มเติม
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Guard พร้อม Logic กำหนดเอง

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // ยกเลิกการนำทาง
  }
  return GuardResult.next; // อนุญาตการนำทาง
});
```

### การติดตามการเรียก Guard

หลังจาก guard ถูกเรียก คุณสามารถตรวจสอบ state ของมัน:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// เข้าถึง RouteContext จากการเรียกครั้งล่าสุด
RouteContext? context = guard.lastContext;

// รีเซ็ตการติดตาม
guard.reset();
```

<div id="assertions"></div>

## Assertion

{{ config('app.name') }} มีฟังก์ชัน assertion แบบกำหนดเอง:

### Route Assertion

``` dart
expectRoute('/home');           // ยืนยัน route ปัจจุบัน
expectNotRoute('/login');       // ยืนยันว่าไม่ได้อยู่บน route
expectRouteInHistory('/home');  // ยืนยันว่า route ถูกเยี่ยมชม
expectRouteExists('/profile');  // ยืนยันว่า route ถูกลงทะเบียน
expectRoutesExist(['/home', '/profile', '/settings']);
```

### State Assertion

``` dart
expectBackpackContains("key");                        // คีย์มีอยู่
expectBackpackContains("key", value: "expected");     // คีย์มีค่า
expectBackpackNotContains("key");                     // คีย์ไม่มีอยู่
```

### Auth Assertion

``` dart
expectAuthenticated<User>();  // ผู้ใช้ยืนยันตัวตนแล้ว
expectGuest();                // ไม่มีผู้ใช้ยืนยันตัวตน
```

### Environment Assertion

``` dart
expectEnv("APP_NAME", "MyApp");  // ตัวแปร env เท่ากับค่า
expectEnvSet("APP_KEY");          // ตัวแปร env ถูกตั้งค่า
```

### Mode Assertion

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### API Assertion

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### Locale Assertion

``` dart
expectLocale("en");
```

### Toast Assertion

ยืนยันการแจ้งเตือน toast ที่ถูกบันทึกระหว่างเทสต์ ต้องใช้ `NyToastRecorder.setup()` ใน setUp ของเทสต์:

``` dart
setUp(() {
  NyToastRecorder.setup();
});

nyWidgetTest('shows success toast', (tester) async {
  await tester.pumpNyWidget(MyPage());
  // ... trigger action ที่แสดง toast ...

  expectToastShown(id: 'success');
  expectToastShown(id: 'danger', description: 'Something went wrong');
  expectNoToastShown(id: 'info');
});
```

**NyToastRecorder** ติดตามการแจ้งเตือน toast ระหว่างเทสต์:

``` dart
// บันทึก toast ด้วยตนเอง
NyToastRecorder.record(id: 'success', title: 'Done', description: 'Saved!');

// ตรวจสอบว่า toast ถูกแสดง
bool shown = NyToastRecorder.wasShown(id: 'success');

// เข้าถึง toast ที่บันทึกทั้งหมด
List<ToastRecord> toasts = NyToastRecorder.records;

// ล้าง toast ที่บันทึก
NyToastRecorder.clear();
```

### Lock และ Loading Assertion

ยืนยัน named lock และ loading state ใน widget `NyPage`/`NyState`:

``` dart
// ยืนยันว่า named lock ถูกถือไว้
expectLocked(tester, find.byType(MyPage), 'submit');

// ยืนยันว่า named lock ไม่ได้ถูกถือไว้
expectNotLocked(tester, find.byType(MyPage), 'submit');

// ยืนยันว่า named loading key กำลังทำงาน
expectLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');

// ยืนยันว่า named loading key ไม่ได้ทำงาน
expectNotLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');
```

<div id="custom-matchers"></div>

## Custom Matcher

ใช้ custom matcher กับ `expect()`:

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

## การทดสอบ State

ทดสอบการจัดการ state ที่ขับเคลื่อนด้วย EventBus ใน widget `NyPage` และ `NyState` โดยใช้ state test helper

### การส่ง State Update

จำลอง state update ที่ปกติจะมาจาก widget หรือ controller อื่น:

``` dart
// ส่ง event UpdateState
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### การส่ง State Action

ส่ง state action ที่ถูกจัดการโดย `whenStateAction()` ในหน้าเพจของคุณ:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// พร้อมข้อมูลเพิ่มเติม
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### State Assertion

``` dart
// ยืนยันว่า state update ถูกส่ง
expectStateUpdated('HomePageState');
expectStateUpdated('HomePageState', times: 2);

// ยืนยันว่า state action ถูกส่ง
expectStateAction('HomePageState', 'refresh-page');
expectStateAction('CartState', 'add-item', times: 1);

// ยืนยัน stateData ของ widget NyPage/NyState
expectStateData(tester, find.byType(MyWidget), equals(42));
```

### NyStateTestHelpers

ติดตามและตรวจสอบ state update และ action ที่ถูกส่ง:

``` dart
// ดึง update ทั้งหมดที่ส่งไปยัง state
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// ดึง action ทั้งหมดที่ส่งไปยัง state
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// รีเซ็ต state update และ action ที่ติดตามทั้งหมด
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## การดีบัก

### dump

พิมพ์ state ของเทสต์ปัจจุบัน (เนื้อหา Backpack, auth user, เวลา, API call, locale):

``` dart
NyTest.dump();
```

### dd (Dump and Die)

พิมพ์ state ของเทสต์และยุติเทสต์ทันที:

``` dart
NyTest.dd();
```

### ที่จัดเก็บ State ของเทสต์

จัดเก็บและดึงค่าระหว่างเทสต์:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Seed Backpack

เติมข้อมูลทดสอบลงใน Backpack ล่วงหน้า:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## ตัวอย่าง

### ไฟล์เทสต์แบบเต็ม

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

      // ... trigger การเรียก API ...

      expectApiCalled('/api/users');
    });
  });

  nyGroup('Time-Sensitive Features', () {
    nyTest('subscription expires correctly', () async {
      NyTest.travel(DateTime(2025, 1, 1));

      // ทดสอบลอจิกการสมัครสมาชิกที่วันที่ที่ทราบ
      expect(NyTime.now().year, equals(2025));

      NyTest.travelForward(Duration(days: 365));
      expect(NyTime.now().year, equals(2026));

      NyTest.travelBack();
    });
  });
}
```
