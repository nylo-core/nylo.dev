# Testing

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [시작하기](#getting-started "시작하기")
- [테스트 작성](#writing-tests "테스트 작성")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [위젯 테스트 유틸리티](#widget-testing-utilities "위젯 테스트 유틸리티")
  - [nyGroup](#ny-group "nyGroup")
  - [테스트 생명주기](#test-lifecycle "테스트 생명주기")
  - [테스트 건너뛰기 및 CI 테스트](#skipping-tests "테스트 건너뛰기 및 CI 테스트")
- [인증](#authentication "인증")
- [시간 여행](#time-travel "시간 여행")
- [API 모킹](#api-mocking "API 모킹")
  - [URL 패턴으로 모킹](#mocking-by-url "URL 패턴으로 모킹")
  - [API Service 타입으로 모킹](#mocking-by-type "API Service 타입으로 모킹")
  - [호출 기록 및 어서션](#call-history "호출 기록 및 어서션")
- [팩토리](#factories "팩토리")
  - [팩토리 정의](#defining-factories "팩토리 정의")
  - [팩토리 상태](#factory-states "팩토리 상태")
  - [인스턴스 생성](#creating-instances "인스턴스 생성")
- [NyFaker](#ny-faker "NyFaker")
- [테스트 캐시](#test-cache "테스트 캐시")
- [Platform Channel 모킹](#platform-channel-mocking "Platform Channel 모킹")
- [Route Guard 모킹](#route-guard-mocking "Route Guard 모킹")
- [어서션](#assertions "어서션")
- [커스텀 매처](#custom-matchers "커스텀 매처")
- [State 테스트](#state-testing "State 테스트")
- [디버깅](#debugging "디버깅")
- [예제](#examples "예제")

<div id="introduction"></div>

## 소개

{{ config('app.name') }} v7에는 Laravel의 테스트 유틸리티에서 영감을 받은 종합 테스트 프레임워크가 포함되어 있습니다. 다음을 제공합니다:

- 자동 setup/teardown이 포함된 **테스트 함수** (`nyTest`, `nyWidgetTest`, `nyGroup`)
- `NyTest.actingAs<T>()`를 통한 **인증 시뮬레이션**
- 테스트에서 시간을 고정하거나 조작하는 **시간 여행**
- URL 패턴 매칭 및 호출 추적이 포함된 **API 모킹**
- 내장 가짜 데이터 생성기(`NyFaker`)를 갖춘 **팩토리**
- 보안 저장소, 경로 제공자 등을 위한 **Platform Channel 모킹**
- 라우트, Backpack, 인증, 환경을 위한 **커스텀 어서션**

<div id="getting-started"></div>

## 시작하기

테스트 파일 상단에서 테스트 프레임워크를 초기화합니다:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()`은 테스트 환경을 설정하고 `autoReset: true`(기본값)일 때 테스트 간 자동 상태 리셋을 활성화합니다.

<div id="writing-tests"></div>

## 테스트 작성

<div id="ny-test"></div>

### nyTest

테스트를 작성하는 기본 함수입니다:

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

옵션:

``` dart
nyTest('my test', () async {
  // test body
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

`WidgetTester`를 사용하여 Flutter 위젯을 테스트합니다:

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

### 위젯 테스트 유틸리티

`NyWidgetTest` 클래스와 `WidgetTester` 확장은 적절한 테마 지원으로 Nylo 위젯을 펌핑하고, `init()` 완료를 기다리며, 로딩 상태를 테스트하는 헬퍼를 제공합니다.

#### 테스트 환경 구성

`setUpAll`에서 `NyWidgetTest.configure()`를 호출하여 Google Fonts 가져오기를 비활성화하고 선택적으로 커스텀 테마를 설정합니다:

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

`NyWidgetTest.reset()`으로 구성을 리셋할 수 있습니다.

폰트 없는 테스트를 위한 두 가지 내장 테마가 제공됩니다:

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### Nylo 위젯 펌핑

`pumpNyWidget`을 사용하여 위젯을 테마 지원이 포함된 `MaterialApp`으로 래핑합니다:

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

폰트 없는 테마로 빠르게 펌핑하려면:

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### Init 대기

`pumpNyWidgetAndWaitForInit`은 로딩 표시기가 사라지거나 타임아웃에 도달할 때까지 프레임을 펌핑합니다. 비동기 `init()` 메서드가 있는 페이지에 유용합니다:

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() has completed
expect(find.text('Loaded Data'), findsOneWidget);
```

#### 펌프 헬퍼

``` dart
// Pump frames until a specific widget appears
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Settle gracefully (won't throw on timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### 생명주기 시뮬레이션

위젯 트리의 모든 `NyPage`에서 `AppLifecycleState` 변경을 시뮬레이션합니다:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Assert side effects of the paused lifecycle action
```

#### 로딩 및 잠금 확인

`NyPage`/`NyState` 위젯의 이름 있는 로딩 키와 잠금을 확인합니다:

``` dart
// Check if a named loading key is active
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Check if a named lock is held
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Check for any loading indicator (CircularProgressIndicator or Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### testNyPage 헬퍼

`NyPage`를 펌핑하고, init을 기다린 후, 기대값을 실행하는 편의 함수입니다:

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

#### testNyPageLoading 헬퍼

`init()` 중에 페이지가 로딩 표시기를 표시하는지 테스트합니다:

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

일반적인 페이지 테스트 유틸리티를 제공하는 mixin입니다:

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

관련된 테스트를 그룹으로 묶습니다:

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

### 테스트 생명주기

생명주기 훅을 사용하여 설정 및 해제 로직을 정의합니다:

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

### 테스트 건너뛰기 및 CI 테스트

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

## 인증

테스트에서 인증된 사용자를 시뮬레이션합니다:

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

사용자를 로그아웃합니다:

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## 시간 여행

`NyTime`을 사용하여 테스트에서 시간을 조작합니다:

### 특정 날짜로 이동

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Reset to real time
});
```

### 시간 앞으로 또는 뒤로 이동

``` dart
NyTest.travelForward(Duration(days: 30)); // Jump 30 days ahead
NyTest.travelBackward(Duration(hours: 2)); // Go back 2 hours
```

### 시간 고정

``` dart
NyTest.freezeTime(); // Freeze at the current moment

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Time hasn't moved

NyTest.travelBack(); // Unfreeze
```

### 시간 경계

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1st of current month
NyTime.travelToEndOfMonth();   // Last day of current month
NyTime.travelToStartOfYear();  // Jan 1st
NyTime.travelToEndOfYear();    // Dec 31st
```

### 범위 지정 시간 여행

고정된 시간 컨텍스트 내에서 코드를 실행합니다:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Time is automatically restored after the callback
```

<div id="api-mocking"></div>

## API 모킹

<div id="mocking-by-url"></div>

### URL 패턴으로 모킹

와일드카드를 지원하는 URL 패턴을 사용하여 API 응답을 모킹합니다:

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

### API Service 타입으로 모킹

타입별로 전체 API 서비스를 모킹합니다:

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

### 호출 기록 및 어서션

API 호출을 추적하고 검증합니다:

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

### Mock 응답 생성

``` dart
Response<Map<String, dynamic>> response = NyMockApi.createResponse(
  data: {'id': 1, 'name': 'Anthony'},
  statusCode: 200,
  statusMessage: 'OK',
);
```

<div id="factories"></div>

## 팩토리

<div id="defining-factories"></div>

### 팩토리 정의

모델의 테스트 인스턴스를 생성하는 방법을 정의합니다:

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

오버라이드 지원:

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### 팩토리 상태

팩토리의 변형을 정의합니다:

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### 인스턴스 생성

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

`NyFaker`는 테스트를 위한 실제와 유사한 가짜 데이터를 생성합니다. 팩토리 정의 내에서 사용할 수 있으며 직접 인스턴스화할 수도 있습니다.

``` dart
NyFaker faker = NyFaker();
```

### 사용 가능한 메서드

| 카테고리 | 메서드 | 반환 타입 | 설명 |
|----------|--------|-------------|-------------|
| **이름** | `faker.firstName()` | `String` | 랜덤 이름 |
| | `faker.lastName()` | `String` | 랜덤 성 |
| | `faker.name()` | `String` | 전체 이름 (이름 + 성) |
| | `faker.username()` | `String` | 사용자명 문자열 |
| **연락처** | `faker.email()` | `String` | 이메일 주소 |
| | `faker.phone()` | `String` | 전화번호 |
| | `faker.company()` | `String` | 회사명 |
| **숫자** | `faker.randomInt(min, max)` | `int` | 범위 내 랜덤 정수 |
| | `faker.randomDouble(min, max)` | `double` | 범위 내 랜덤 실수 |
| | `faker.randomBool()` | `bool` | 랜덤 불리언 |
| **식별자** | `faker.uuid()` | `String` | UUID v4 문자열 |
| **날짜** | `faker.date()` | `DateTime` | 랜덤 날짜 |
| | `faker.pastDate()` | `DateTime` | 과거 날짜 |
| | `faker.futureDate()` | `DateTime` | 미래 날짜 |
| **텍스트** | `faker.lorem()` | `String` | Lorem ipsum 단어 |
| | `faker.sentences()` | `String` | 여러 문장 |
| | `faker.paragraphs()` | `String` | 여러 단락 |
| | `faker.slug()` | `String` | URL 슬러그 |
| **웹** | `faker.url()` | `String` | URL 문자열 |
| | `faker.imageUrl()` | `String` | 이미지 URL (picsum.photos 경유) |
| | `faker.ipAddress()` | `String` | IPv4 주소 |
| | `faker.macAddress()` | `String` | MAC 주소 |
| **위치** | `faker.address()` | `String` | 도로 주소 |
| | `faker.city()` | `String` | 도시 이름 |
| | `faker.state()` | `String` | 미국 주 약어 |
| | `faker.zipCode()` | `String` | 우편번호 |
| | `faker.country()` | `String` | 국가 이름 |
| **기타** | `faker.hexColor()` | `String` | 16진수 색상 코드 |
| | `faker.creditCardNumber()` | `String` | 신용카드 번호 |
| | `faker.randomElement(list)` | `T` | 목록에서 랜덤 항목 |
| | `faker.randomElements(list, count)` | `List<T>` | 목록에서 랜덤 항목들 |

<div id="test-cache"></div>

## 테스트 캐시

`NyTestCache`는 캐시 관련 기능을 테스트하기 위한 인메모리 캐시를 제공합니다:

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

## Platform Channel 모킹

`NyMockChannels`는 테스트가 충돌하지 않도록 일반적인 Platform Channel을 자동으로 모킹합니다:

``` dart
void main() {
  NyTest.init(); // Automatically sets up mock channels

  // Or set up manually
  NyMockChannels.setup();
}
```

### 모킹된 Channel

- **path_provider** -- 문서, 임시, 앱 지원, 라이브러리, 캐시 디렉토리
- **flutter_secure_storage** -- 인메모리 보안 저장소
- **flutter_timezone** -- 타임존 데이터
- **flutter_local_notifications** -- 알림 채널
- **sqflite** -- 데이터베이스 작업

### 경로 오버라이드

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### 테스트에서의 보안 저장소

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## Route Guard 모킹

`NyMockRouteGuard`를 사용하면 실제 인증이나 네트워크 호출 없이 Route Guard 동작을 테스트할 수 있습니다. `NyRouteGuard`를 확장하며 일반적인 시나리오를 위한 팩토리 생성자를 제공합니다.

### 항상 통과하는 Guard

``` dart
final guard = NyMockRouteGuard.pass();
```

### 리다이렉트하는 Guard

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// With additional data
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### 커스텀 로직이 있는 Guard

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // abort navigation
  }
  return GuardResult.next; // allow navigation
});
```

### Guard 호출 추적

Guard가 호출된 후 상태를 검사할 수 있습니다:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Access the RouteContext from the last call
RouteContext? context = guard.lastContext;

// Reset tracking
guard.reset();
```

<div id="assertions"></div>

## 어서션

{{ config('app.name') }}은 커스텀 어서션 함수를 제공합니다:

### 라우트 어서션

``` dart
expectRoute('/home');           // Assert current route
expectNotRoute('/login');       // Assert not on route
expectRouteInHistory('/home');  // Assert route was visited
expectRouteExists('/profile');  // Assert route is registered
expectRoutesExist(['/home', '/profile', '/settings']);
```

### State 어서션

``` dart
expectBackpackContains("key");                        // Key exists
expectBackpackContains("key", value: "expected");     // Key has value
expectBackpackNotContains("key");                     // Key doesn't exist
```

### 인증 어서션

``` dart
expectAuthenticated<User>();  // User is authenticated
expectGuest();                // No user authenticated
```

### 환경 어서션

``` dart
expectEnv("APP_NAME", "MyApp");  // Env variable equals value
expectEnvSet("APP_KEY");          // Env variable is set
```

### 모드 어서션

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### API 어서션

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### 로케일 어서션

``` dart
expectLocale("en");
```

### 토스트 어서션

테스트 중에 기록된 토스트 알림에 대해 어서션합니다. 테스트 setUp에서 `NyToastRecorder.setup()`이 필요합니다:

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

**NyToastRecorder**는 테스트 중 토스트 알림을 추적합니다:

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

### 잠금 및 로딩 어서션

`NyPage`/`NyState` 위젯의 이름 있는 잠금 및 로딩 상태에 대해 어서션합니다:

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

## 커스텀 매처

`expect()`와 함께 커스텀 매처를 사용합니다:

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

## State 테스트

EventBus 기반의 상태 관리를 `NyPage` 및 `NyState` 위젯에서 State 테스트 헬퍼를 사용하여 테스트합니다.

### State 업데이트 발생

다른 위젯이나 컨트롤러에서 오는 상태 업데이트를 시뮬레이션합니다:

``` dart
// Fire an UpdateState event
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### State 액션 발생

페이지의 `whenStateAction()`에서 처리되는 State 액션을 전송합니다:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// With additional data
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### State 어서션

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

발생된 State 업데이트 및 액션을 추적하고 검사합니다:

``` dart
// Get all updates fired to a state
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Get all actions fired to a state
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Reset all tracked state updates and actions
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## 디버깅

### dump

현재 테스트 상태를 출력합니다 (Backpack 내용, 인증 사용자, 시간, API 호출, 로케일):

``` dart
NyTest.dump();
```

### dd (Dump and Die)

테스트 상태를 출력하고 즉시 테스트를 종료합니다:

``` dart
NyTest.dd();
```

### 테스트 State 저장소

테스트 중에 값을 저장하고 가져옵니다:

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### Backpack 시드

테스트 데이터로 Backpack을 미리 채웁니다:

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## 예제

### 전체 테스트 파일

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
