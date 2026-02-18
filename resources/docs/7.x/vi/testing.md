# Testing

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Bắt đầu](#getting-started "Bắt đầu")
- [Viết test](#writing-tests "Viết test")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [Tiện ích kiểm thử widget](#widget-testing-utilities "Tiện ích kiểm thử widget")
  - [nyGroup](#ny-group "nyGroup")
  - [Vòng đời test](#test-lifecycle "Vòng đời test")
  - [Bỏ qua test và test CI](#skipping-tests "Bỏ qua test và test CI")
- [Xác thực](#authentication "Xác thực")
- [Du hành thời gian](#time-travel "Du hành thời gian")
- [Giả lập API](#api-mocking "Giả lập API")
  - [Giả lập theo mẫu URL](#mocking-by-url "Giả lập theo mẫu URL")
  - [Giả lập theo loại API Service](#mocking-by-type "Giả lập theo loại API Service")
  - [Lịch sử cuộc gọi và xác nhận](#call-history "Lịch sử cuộc gọi và xác nhận")
- [Factories](#factories "Factories")
  - [Định nghĩa Factories](#defining-factories "Định nghĩa Factories")
  - [Trạng thái Factory](#factory-states "Trạng thái Factory")
  - [Tạo thể hiện](#creating-instances "Tạo thể hiện")
- [NyFaker](#ny-faker "NyFaker")
- [Test Cache](#test-cache "Test Cache")
- [Giả lập Platform Channel](#platform-channel-mocking "Giả lập Platform Channel")
- [Giả lập Route Guard](#route-guard-mocking "Giả lập Route Guard")
- [Xác nhận](#assertions "Xác nhận")
- [Matcher tùy chỉnh](#custom-matchers "Matcher tùy chỉnh")
- [Kiểm thử State](#state-testing "Kiểm thử State")
- [Gỡ lỗi](#debugging "Gỡ lỗi")
- [Ví dụ](#examples "Ví dụ thực tế")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} v7 bao gồm một framework kiểm thử toàn diện lấy cảm hứng từ các tiện ích kiểm thử của Laravel. Nó cung cấp:

- **Hàm test** với thiết lập/dọn dẹp tự động (`nyTest`, `nyWidgetTest`, `nyGroup`)
- **Mô phỏng xác thực** thông qua `NyTest.actingAs<T>()`
- **Du hành thời gian** để đóng băng hoặc thao tác thời gian trong test
- **Giả lập API** với khớp mẫu URL và theo dõi cuộc gọi
- **Factories** với trình tạo dữ liệu giả tích hợp (`NyFaker`)
- **Giả lập platform channel** cho secure storage, path provider, và nhiều hơn nữa
- **Xác nhận tùy chỉnh** cho route, Backpack, xác thực, và môi trường

<div id="getting-started"></div>

## Bắt đầu

Khởi tạo framework kiểm thử ở đầu file test của bạn:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` thiết lập môi trường test và kích hoạt tự động đặt lại trạng thái giữa các test khi `autoReset: true` (mặc định).

<div id="writing-tests"></div>

## Viết test

<div id="ny-test"></div>

### nyTest

Hàm chính để viết test:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

Tùy chọn:

``` dart
nyTest('my test', () async {
  // nội dung test
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

Dùng để kiểm thử widget Flutter với `WidgetTester`:

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

### Tiện ích kiểm thử widget

Class `NyWidgetTest` và các extension của `WidgetTester` cung cấp các helper để pump widget Nylo với hỗ trợ theme phù hợp, chờ `init()` hoàn thành, và kiểm thử trạng thái loading.

#### Cấu hình môi trường test

Gọi `NyWidgetTest.configure()` trong `setUpAll` của bạn để tắt tải Google Fonts và tùy chọn đặt theme tùy chỉnh:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

Bạn có thể đặt lại cấu hình bằng `NyWidgetTest.reset()`.

Có hai theme tích hợp sẵn để kiểm thử không cần font:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Pump widget Nylo

Sử dụng `pumpNyWidget` để bọc widget trong `MaterialApp` với hỗ trợ theme:

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

Để pump nhanh với theme không cần font:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Chờ Init

`pumpNyWidgetAndWaitForInit` pump các frame cho đến khi chỉ báo loading biến mất (hoặc hết thời gian chờ), hữu ích cho các trang có phương thức `init()` bất đồng bộ:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() đã hoàn thành
expect(find.text('Loaded Data'), findsOneWidget);
```

#### Helper Pump

``` dart
// Pump các frame cho đến khi một widget cụ thể xuất hiện
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Settle nhẹ nhàng (không ném lỗi khi hết thời gian chờ)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### Mô phỏng vòng đời

Mô phỏng thay đổi `AppLifecycleState` trên bất kỳ `NyPage` nào trong cây widget:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Xác nhận tác dụng phụ của hành động vòng đời paused
```

#### Kiểm tra Loading và Lock

Kiểm tra các khóa loading và lock được đặt tên trên widget `NyPage`/`NyState`:

``` dart
// Kiểm tra xem một khóa loading có đang hoạt động không
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Kiểm tra xem một lock có đang được giữ không
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Kiểm tra bất kỳ chỉ báo loading nào (CircularProgressIndicator hoặc Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### Helper testNyPage

Một hàm tiện ích pump `NyPage`, chờ init, sau đó chạy các kỳ vọng của bạn:

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

Kiểm thử rằng một trang hiển thị chỉ báo loading trong quá trình `init()`:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

Một mixin cung cấp các tiện ích kiểm thử trang phổ biến:

``` dart
class HomePageTest with NyPageTestMixin {
  void runTests(WidgetTester tester) async {
    // Xác minh init đã được gọi và loading đã hoàn thành
    await verifyInitCalled(tester, HomePage(), timeout: Duration(seconds: 5));

    // Xác minh trạng thái loading được hiển thị trong quá trình init
    await verifyLoadingState(tester, HomePage());
  }
}
```

<div id="ny-group"></div>

### nyGroup

Nhóm các test liên quan lại với nhau:

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

### Vòng đời test

Thiết lập và dọn dẹp logic bằng các hook vòng đời:

``` dart
void main() {
  NyTest.init();

  nySetUpAll(() {
    // Chạy một lần trước tất cả test
  });

  nySetUp(() {
    // Chạy trước mỗi test
  });

  nyTearDown(() {
    // Chạy sau mỗi test
  });

  nyTearDownAll(() {
    // Chạy một lần sau tất cả test
  });
}
```

<div id="skipping-tests"></div>

### Bỏ qua test và test CI

``` dart
// Bỏ qua test với lý do
nySkip('not implemented yet', () async {
  // ...
}, "Waiting for API update");

// Test dự kiến sẽ thất bại
nyFailing('known bug', () async {
  // ...
});

// Test chỉ chạy trên CI (được gắn tag 'ci')
nyCi('integration test', () async {
  // Chỉ chạy trong môi trường CI
});
```

<div id="authentication"></div>

## Xác thực

Mô phỏng người dùng đã xác thực trong test:

``` dart
nyTest('user can access profile', () async {
  // Mô phỏng người dùng đã đăng nhập
  NyTest.actingAs<User>(User(name: "Anthony", email: "anthony@example.com"));

  // Xác minh đã xác thực
  expectAuthenticated<User>();

  // Truy cập người dùng đang hoạt động
  User? user = NyTest.actingUser<User>();
  expect(user?.name, equals("Anthony"));
});

nyTest('guest cannot access profile', () async {
  // Xác minh chưa xác thực
  expectGuest();
});
```

Đăng xuất người dùng:

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## Du hành thời gian

Thao tác thời gian trong test của bạn bằng `NyTime`:

### Nhảy đến ngày cụ thể

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Đặt lại về thời gian thực
});
```

### Tua tới hoặc tua lùi thời gian

``` dart
NyTest.travelForward(Duration(days: 30)); // Nhảy tới 30 ngày
NyTest.travelBackward(Duration(hours: 2)); // Quay lại 2 giờ
```

### Đóng băng thời gian

``` dart
NyTest.freezeTime(); // Đóng băng tại thời điểm hiện tại

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Thời gian không thay đổi

NyTest.travelBack(); // Hủy đóng băng
```

### Ranh giới thời gian

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // Ngày 1 của tháng hiện tại
NyTime.travelToEndOfMonth();   // Ngày cuối của tháng hiện tại
NyTime.travelToStartOfYear();  // Ngày 1 tháng 1
NyTime.travelToEndOfYear();    // Ngày 31 tháng 12
```

### Du hành thời gian có phạm vi

Thực thi code trong ngữ cảnh thời gian đóng băng:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Thời gian được tự động khôi phục sau callback
```

<div id="api-mocking"></div>

## Giả lập API

<div id="mocking-by-url"></div>

### Giả lập theo mẫu URL

Giả lập phản hồi API bằng mẫu URL với hỗ trợ ký tự đại diện:

``` dart
nyTest('mock API responses', () async {
  // Khớp URL chính xác
  NyMockApi.respond('/users/1', {'id': 1, 'name': 'Anthony'});

  // Ký tự đại diện một phân đoạn (*)
  NyMockApi.respond('/users/*', {'id': 1, 'name': 'User'});

  // Ký tự đại diện nhiều phân đoạn (**)
  NyMockApi.respond('/api/**', {'status': 'ok'});

  // Với mã trạng thái và header
  NyMockApi.respond(
    '/users',
    {'error': 'Unauthorized'},
    statusCode: 401,
    method: 'POST',
    headers: {'X-Error': 'true'},
  );

  // Với độ trễ mô phỏng
  NyMockApi.respond(
    '/slow-endpoint',
    {'data': 'loaded'},
    delay: Duration(seconds: 2),
  );
});
```

<div id="mocking-by-type"></div>

### Giả lập theo loại API Service

Giả lập toàn bộ API service theo loại:

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

### Lịch sử cuộc gọi và xác nhận

Theo dõi và xác minh các cuộc gọi API:

``` dart
nyTest('verify API was called', () async {
  NyMockApi.setRecordCalls(true);

  // ... thực hiện các hành động kích hoạt cuộc gọi API ...

  // Xác nhận endpoint đã được gọi
  expectApiCalled('/users');

  // Xác nhận endpoint không được gọi
  expectApiNotCalled('/admin');

  // Xác nhận số lần gọi
  expectApiCalled('/users', times: 2);

  // Xác nhận phương thức cụ thể
  expectApiCalled('/users', method: 'POST');

  // Lấy chi tiết cuộc gọi
  List<ApiCallInfo> calls = NyMockApi.getCallsFor('/users');
});
```

### Tạo phản hồi giả lập

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

### Định nghĩa Factories

Định nghĩa cách tạo thể hiện test của model:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

Với hỗ trợ ghi đè:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### Trạng thái Factory

Định nghĩa các biến thể của factory:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### Tạo thể hiện

``` dart
// Tạo một thể hiện đơn
User user = NyFactory.make<User>();

// Tạo với ghi đè
User admin = NyFactory.make<User>(overrides: {'name': 'Admin User'});

// Tạo với các trạng thái được áp dụng
User premiumAdmin = NyFactory.make<User>(states: ['admin', 'premium']);

// Tạo nhiều thể hiện
List<User> users = NyFactory.create<User>(count: 5);

// Tạo chuỗi với dữ liệu dựa trên chỉ số
List<User> numbered = NyFactory.sequence<User>(3, (int index, NyFaker faker) {
  return User(name: "User ${index + 1}", email: faker.email());
});
```

<div id="ny-faker"></div>

## NyFaker

`NyFaker` tạo dữ liệu giả thực tế cho test. Nó có sẵn trong định nghĩa factory và có thể được khởi tạo trực tiếp.

``` dart
NyFaker faker = NyFaker();
```

### Các phương thức có sẵn

| Danh mục | Phương thức | Kiểu trả về | Mô tả |
|----------|--------|-------------|-------------|
| **Tên** | `faker.firstName()` | `String` | Tên ngẫu nhiên |
| | `faker.lastName()` | `String` | Họ ngẫu nhiên |
| | `faker.name()` | `String` | Họ tên đầy đủ (tên + họ) |
| | `faker.username()` | `String` | Chuỗi tên người dùng |
| **Liên hệ** | `faker.email()` | `String` | Địa chỉ email |
| | `faker.phone()` | `String` | Số điện thoại |
| | `faker.company()` | `String` | Tên công ty |
| **Số** | `faker.randomInt(min, max)` | `int` | Số nguyên ngẫu nhiên trong phạm vi |
| | `faker.randomDouble(min, max)` | `double` | Số thực ngẫu nhiên trong phạm vi |
| | `faker.randomBool()` | `bool` | Boolean ngẫu nhiên |
| **Định danh** | `faker.uuid()` | `String` | Chuỗi UUID v4 |
| **Ngày tháng** | `faker.date()` | `DateTime` | Ngày ngẫu nhiên |
| | `faker.pastDate()` | `DateTime` | Ngày trong quá khứ |
| | `faker.futureDate()` | `DateTime` | Ngày trong tương lai |
| **Văn bản** | `faker.lorem()` | `String` | Từ lorem ipsum |
| | `faker.sentences()` | `String` | Nhiều câu |
| | `faker.paragraphs()` | `String` | Nhiều đoạn văn |
| | `faker.slug()` | `String` | URL slug |
| **Web** | `faker.url()` | `String` | Chuỗi URL |
| | `faker.imageUrl()` | `String` | URL hình ảnh (qua picsum.photos) |
| | `faker.ipAddress()` | `String` | Địa chỉ IPv4 |
| | `faker.macAddress()` | `String` | Địa chỉ MAC |
| **Địa điểm** | `faker.address()` | `String` | Địa chỉ đường |
| | `faker.city()` | `String` | Tên thành phố |
| | `faker.state()` | `String` | Viết tắt bang Hoa Kỳ |
| | `faker.zipCode()` | `String` | Mã bưu chính |
| | `faker.country()` | `String` | Tên quốc gia |
| **Khác** | `faker.hexColor()` | `String` | Mã màu hex |
| | `faker.creditCardNumber()` | `String` | Số thẻ tín dụng |
| | `faker.randomElement(list)` | `T` | Phần tử ngẫu nhiên từ danh sách |
| | `faker.randomElements(list, count)` | `List<T>` | Các phần tử ngẫu nhiên từ danh sách |

<div id="test-cache"></div>

## Test Cache

`NyTestCache` cung cấp bộ nhớ đệm trong bộ nhớ để kiểm thử chức năng liên quan đến cache:

``` dart
nyTest('cache operations', () async {
  NyTestCache cache = NyTest.cache;

  // Lưu một giá trị
  await cache.put<String>("key", "value");

  // Lưu với thời gian hết hạn
  await cache.put<String>("temp", "data", seconds: 60);

  // Đọc một giá trị
  String? value = await cache.get<String>("key");

  // Kiểm tra sự tồn tại
  bool exists = await cache.has("key");

  // Xóa một khóa
  await cache.clear("key");

  // Xóa tất cả
  await cache.flush();

  // Lấy thông tin cache
  int size = await cache.size();
  List<String> keys = await cache.documents();
});
```

<div id="platform-channel-mocking"></div>

## Giả lập Platform Channel

`NyMockChannels` tự động giả lập các platform channel phổ biến để test không bị crash:

``` dart
void main() {
  NyTest.init(); // Tự động thiết lập các mock channel

  // Hoặc thiết lập thủ công
  NyMockChannels.setup();
}
```

### Các channel được giả lập

- **path_provider** -- thư mục documents, temporary, application support, library, và cache
- **flutter_secure_storage** -- secure storage trong bộ nhớ
- **flutter_timezone** -- dữ liệu múi giờ
- **flutter_local_notifications** -- kênh thông báo
- **sqflite** -- thao tác cơ sở dữ liệu

### Ghi đè đường dẫn

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### Secure Storage trong test

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## Giả lập Route Guard

`NyMockRouteGuard` cho phép bạn kiểm thử hành vi route guard mà không cần xác thực thực tế hoặc cuộc gọi mạng. Nó kế thừa `NyRouteGuard` và cung cấp các factory constructor cho các tình huống phổ biến.

### Guard luôn cho phép

``` dart
final guard = NyMockRouteGuard.pass();
```

### Guard chuyển hướng

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// Với dữ liệu bổ sung
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### Guard với logic tùy chỉnh

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // hủy điều hướng
  }
  return GuardResult.next; // cho phép điều hướng
});
```

### Theo dõi cuộc gọi Guard

Sau khi guard đã được gọi, bạn có thể kiểm tra trạng thái của nó:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Truy cập RouteContext từ lần gọi cuối cùng
RouteContext? context = guard.lastContext;

// Đặt lại theo dõi
guard.reset();
```

<div id="assertions"></div>

## Xác nhận

{{ config('app.name') }} cung cấp các hàm xác nhận tùy chỉnh:

### Xác nhận Route

``` dart
expectRoute('/home');           // Xác nhận route hiện tại
expectNotRoute('/login');       // Xác nhận không ở route
expectRouteInHistory('/home');  // Xác nhận route đã được truy cập
expectRouteExists('/profile');  // Xác nhận route đã được đăng ký
expectRoutesExist(['/home', '/profile', '/settings']);
```

### Xác nhận State

``` dart
expectBackpackContains("key");                        // Khóa tồn tại
expectBackpackContains("key", value: "expected");     // Khóa có giá trị
expectBackpackNotContains("key");                     // Khóa không tồn tại
```

### Xác nhận xác thực

``` dart
expectAuthenticated<User>();  // Người dùng đã xác thực
expectGuest();                // Không có người dùng nào xác thực
```

### Xác nhận môi trường

``` dart
expectEnv("APP_NAME", "MyApp");  // Biến môi trường bằng giá trị
expectEnvSet("APP_KEY");          // Biến môi trường đã được đặt
```

### Xác nhận chế độ

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### Xác nhận API

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### Xác nhận ngôn ngữ

``` dart
expectLocale("en");
```

### Xác nhận Toast

Xác nhận các thông báo toast đã được ghi lại trong test. Yêu cầu `NyToastRecorder.setup()` trong setUp của test:

``` dart
setUp(() {
  NyToastRecorder.setup();
});

nyWidgetTest('shows success toast', (tester) async {
  await tester.pumpNyWidget(MyPage());
  // ... kích hoạt hành động hiển thị toast ...

  expectToastShown(id: 'success');
  expectToastShown(id: 'danger', description: 'Something went wrong');
  expectNoToastShown(id: 'info');
});
```

**NyToastRecorder** theo dõi thông báo toast trong test:

``` dart
// Ghi lại toast thủ công
NyToastRecorder.record(id: 'success', title: 'Done', description: 'Saved!');

// Kiểm tra xem toast đã được hiển thị chưa
bool shown = NyToastRecorder.wasShown(id: 'success');

// Truy cập tất cả toast đã ghi lại
List<ToastRecord> toasts = NyToastRecorder.records;

// Xóa toast đã ghi lại
NyToastRecorder.clear();
```

### Xác nhận Lock và Loading

Xác nhận các trạng thái lock và loading được đặt tên trong widget `NyPage`/`NyState`:

``` dart
// Xác nhận lock được đặt tên đang được giữ
expectLocked(tester, find.byType(MyPage), 'submit');

// Xác nhận lock được đặt tên không được giữ
expectNotLocked(tester, find.byType(MyPage), 'submit');

// Xác nhận khóa loading được đặt tên đang hoạt động
expectLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');

// Xác nhận khóa loading được đặt tên không hoạt động
expectNotLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');
```

<div id="custom-matchers"></div>

## Matcher tùy chỉnh

Sử dụng matcher tùy chỉnh với `expect()`:

``` dart
// Matcher kiểu dữ liệu
expect(result, isType<User>());

// Matcher tên route
expect(widget, hasRouteName('/home'));

// Matcher Backpack
expect(true, backpackHas("key", value: "expected"));

// Matcher cuộc gọi API
expect(true, apiWasCalled('/users', method: 'GET', times: 1));
```

<div id="state-testing"></div>

## Kiểm thử State

Kiểm thử quản lý trạng thái dựa trên EventBus trong widget `NyPage` và `NyState` bằng các helper kiểm thử state.

### Kích hoạt cập nhật State

Mô phỏng cập nhật state thường đến từ widget hoặc controller khác:

``` dart
// Kích hoạt sự kiện UpdateState
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### Kích hoạt hành động State

Gửi hành động state được xử lý bởi `whenStateAction()` trong trang của bạn:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// Với dữ liệu bổ sung
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### Xác nhận State

``` dart
// Xác nhận cập nhật state đã được kích hoạt
expectStateUpdated('HomePageState');
expectStateUpdated('HomePageState', times: 2);

// Xác nhận hành động state đã được kích hoạt
expectStateAction('HomePageState', 'refresh-page');
expectStateAction('CartState', 'add-item', times: 1);

// Xác nhận stateData của widget NyPage/NyState
expectStateData(tester, find.byType(MyWidget), equals(42));
```

### NyStateTestHelpers

Theo dõi và kiểm tra các cập nhật và hành động state đã kích hoạt:

``` dart
// Lấy tất cả cập nhật đã kích hoạt đến một state
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Lấy tất cả hành động đã kích hoạt đến một state
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Đặt lại tất cả cập nhật và hành động state đã theo dõi
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## Gỡ lỗi

### dump

In trạng thái test hiện tại (nội dung Backpack, người dùng xác thực, thời gian, cuộc gọi API, ngôn ngữ):

``` dart
NyTest.dump();
```

### dd (Dump and Die)

In trạng thái test và dừng test ngay lập tức:

``` dart
NyTest.dd();
```

### Lưu trữ trạng thái test

Lưu và truy xuất giá trị trong test:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Nạp dữ liệu Backpack

Nạp sẵn dữ liệu test vào Backpack:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## Ví dụ

### File test đầy đủ

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

      // ... kích hoạt cuộc gọi API ...

      expectApiCalled('/api/users');
    });
  });

  nyGroup('Time-Sensitive Features', () {
    nyTest('subscription expires correctly', () async {
      NyTest.travel(DateTime(2025, 1, 1));

      // Kiểm thử logic đăng ký tại ngày đã biết
      expect(NyTime.now().year, equals(2025));

      NyTest.travelForward(Duration(days: 365));
      expect(NyTime.now().year, equals(2026));

      NyTest.travelBack();
    });
  });
}
```
