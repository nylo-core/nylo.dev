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
- [내비게이션 및 인터랙션 헬퍼](#nav-interaction "내비게이션 및 인터랙션 헬퍼")
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
  // 테스트 본문
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
// init() 완료
expect(find.text('Loaded Data'), findsOneWidget);
```

#### 펌프 헬퍼

``` dart
// 특정 위젯이 나타날 때까지 프레임 펌핑
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// 부드럽게 안정 대기 (타임아웃 시 예외 미발생)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### 생명주기 시뮬레이션

위젯 트리의 모든 `NyPage`에서 `AppLifecycleState` 변경을 시뮬레이션합니다:

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// 일시정지 생명주기 액션의 부수 효과 어서션
```

#### 로딩 및 잠금 확인

`NyPage`/`NyState` 위젯의 이름 있는 로딩 키와 잠금을 확인합니다:

``` dart
// 명명된 로딩 키가 활성 상태인지 확인
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// 명명된 잠금이 유지 중인지 확인
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// 로딩 인디케이터 존재 여부 확인 (CircularProgressIndicator 또는 Skeletonizer)
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
    // init 호출 및 로딩 완료 확인
    await verifyInitCalled(tester, HomePage(), timeout: Duration(seconds: 5));

    // init 중 로딩 상태 표시 확인
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
    // 모든 테스트 전에 한 번 실행
  });

  nySetUp(() {
    // 각 테스트 전에 실행
  });

  nyTearDown(() {
    // 각 테스트 후에 실행
  });

  nyTearDownAll(() {
    // 모든 테스트 후에 한 번 실행
  });
}
```

<div id="skipping-tests"></div>

### 테스트 건너뛰기 및 CI 테스트

``` dart
// 이유와 함께 테스트 건너뛰기
nySkip('not implemented yet', () async {
  // ...
}, "Waiting for API update");

// 실패가 예상되는 테스트
nyFailing('known bug', () async {
  // ...
});

// CI 전용 테스트 ('ci' 태그)
nyCi('integration test', () async {
  // CI 환경에서만 실행
});
```

<div id="authentication"></div>

## 인증

테스트에서 인증된 사용자를 시뮬레이션합니다:

``` dart
nyTest('user can access profile', () async {
  // 로그인한 사용자 시뮬레이션
  NyTest.actingAs<User>(User(name: "Anthony", email: "anthony@example.com"));

  // 인증 확인
  expectAuthenticated<User>();

  // 현재 사용자 접근
  User? user = NyTest.actingUser<User>();
  expect(user?.name, equals("Anthony"));
});

nyTest('guest cannot access profile', () async {
  // 미인증 확인
  expectGuest();
});
```

사용자를 로그아웃합니다:

``` dart
NyTest.logout();
expectGuest();
```

게스트 컨텍스트를 설정할 때 `actingAsGuest()` 를 `logout()` 의 읽기 쉬운 별칭으로 사용할 수 있습니다.

``` dart
NyTest.actingAsGuest();
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

  NyTest.travelBack(); // 실제 시간으로 리셋
});
```

### 시간 앞으로 또는 뒤로 이동

``` dart
NyTest.travelForward(Duration(days: 30)); // 30일 앞으로
NyTest.travelBackward(Duration(hours: 2)); // 2시간 뒤로
```

### 시간 고정

``` dart
NyTest.freezeTime(); // 현재 시각에 시간 고정

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // 시간이 이동하지 않음

NyTest.travelBack(); // 고정 해제
```

### 시간 경계

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 현재 월의 1일
NyTime.travelToEndOfMonth();   // 현재 월의 마지막 날
NyTime.travelToStartOfYear();  // 1월 1일
NyTime.travelToEndOfYear();    // 12월 31일
```

### 범위 지정 시간 여행

고정된 시간 컨텍스트 내에서 코드를 실행합니다:

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// 콜백 종료 후 시간이 자동으로 복원됨
```

<div id="api-mocking"></div>

## API 모킹

<div id="mocking-by-url"></div>

### URL 패턴으로 모킹

와일드카드를 지원하는 URL 패턴을 사용하여 API 응답을 모킹합니다:

``` dart
nyTest('mock API responses', () async {
  // 정확한 URL 매칭
  NyMockApi.respond('/users/1', {'id': 1, 'name': 'Anthony'});

  // 단일 세그먼트 와일드카드 (*)
  NyMockApi.respond('/users/*', {'id': 1, 'name': 'User'});

  // 다중 세그먼트 와일드카드 (**)
  NyMockApi.respond('/api/**', {'status': 'ok'});

  // 상태 코드 및 헤더 포함
  NyMockApi.respond(
    '/users',
    {'error': 'Unauthorized'},
    statusCode: 401,
    method: 'POST',
    headers: {'X-Error': 'true'},
  );

  // 시뮬레이션 지연 포함
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

  // ... API 호출을 트리거하는 액션 수행 ...

  // 엔드포인트 호출 어서션
  expectApiCalled('/users');

  // 엔드포인트 미호출 어서션
  expectApiNotCalled('/admin');

  // 호출 횟수 어서션
  expectApiCalled('/users', times: 2);

  // 특정 메서드 어서션
  expectApiCalled('/users', method: 'POST');

  // 호출 상세 정보 가져오기
  List<ApiCallInfo> calls = NyMockApi.getCallsFor('/users');
});
```

특정 요청 바디 데이터로 엔드포인트가 호출되었는지 검증합니다:

``` dart
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
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
// 단일 인스턴스 생성
User user = NyFactory.make<User>();

// 오버라이드와 함께 생성
User admin = NyFactory.make<User>(overrides: {'name': 'Admin User'});

// 상태 적용 후 생성
User premiumAdmin = NyFactory.make<User>(states: ['admin', 'premium']);

// 여러 인스턴스 생성
List<User> users = NyFactory.create<User>(count: 5);

// 인덱스 기반 데이터로 시퀀스 생성
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

  // 값 저장
  await cache.put<String>("key", "value");

  // 만료 시간 포함 저장
  await cache.put<String>("temp", "data", seconds: 60);

  // 값 읽기
  String? value = await cache.get<String>("key");

  // 존재 여부 확인
  bool exists = await cache.has("key");

  // 키 삭제
  await cache.clear("key");

  // 전체 삭제
  await cache.flush();

  // 캐시 정보 가져오기
  int size = await cache.size();
  List<String> keys = await cache.documents();
});
```

<div id="platform-channel-mocking"></div>

## Platform Channel 모킹

`NyMockChannels`는 테스트가 충돌하지 않도록 일반적인 Platform Channel을 자동으로 모킹합니다:

``` dart
void main() {
  NyTest.init(); // 모킹 채널을 자동으로 설정

  // 또는 수동으로 설정
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

// 추가 데이터 포함
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### 커스텀 로직이 있는 Guard

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // 내비게이션 중단
  }
  return GuardResult.next; // 내비게이션 허용
});
```

### Guard 호출 추적

Guard가 호출된 후 상태를 검사할 수 있습니다:

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// 마지막 호출의 RouteContext 접근
RouteContext? context = guard.lastContext;

// 추적 초기화
guard.reset();
```

<div id="assertions"></div>

## 어서션

{{ config('app.name') }}은 커스텀 어서션 함수를 제공합니다:

### 라우트 어서션

``` dart
expectRoute('/home');           // 현재 라우트 어서션
expectNotRoute('/login');       // 해당 라우트가 아님 어서션
expectRouteInHistory('/home');  // 라우트 방문 여부 어서션
expectRouteExists('/profile');  // 라우트 등록 여부 어서션
expectRoutesExist(['/home', '/profile', '/settings']);
```

### State 어서션

``` dart
expectBackpackContains("key");                        // 키 존재
expectBackpackContains("key", value: "expected");     // 키 값 보유
expectBackpackNotContains("key");                     // 키 미존재
```

### 인증 어서션

``` dart
expectAuthenticated<User>();  // 사용자 인증됨
expectGuest();                // 인증된 사용자 없음
```

### 환경 어서션

``` dart
expectEnv("APP_NAME", "MyApp");  // 환경 변수 값 일치
expectEnvSet("APP_KEY");          // 환경 변수 설정됨
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
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
```

### 위젯 어서션

``` dart
// 특정 위젯 타입이 지정된 횟수만큼 표시되는지 검증
expectWidgetCount(ListTile, 3);
expectWidgetCount(Icon, 0);

// 텍스트가 표시되는지 검증
expectTextVisible('Welcome');

// 텍스트가 표시되지 않는지 검증
expectTextNotVisible('Error');

// 임의의 위젯이 표시되는지 검증 (임의의 Finder 사용)
expectVisible(find.byType(FloatingActionButton));
expectVisible(find.byIcon(Icons.notifications));
expectVisible(find.byKey(Key('submit_btn')));

// 위젯이 표시되지 않는지 검증
expectNotVisible(find.byType(ErrorBanner));
expectNotVisible(find.byKey(Key('loading_spinner')));
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
  // ... Toast를 표시하는 액션 트리거 ...

  expectToastShown(id: 'success');
  expectToastShown(id: 'danger', description: 'Something went wrong');
  expectNoToastShown(id: 'info');
});
```

**NyToastRecorder**는 테스트 중 토스트 알림을 추적합니다:

``` dart
// 수동으로 Toast 기록
NyToastRecorder.record(id: 'success', title: 'Done', description: 'Saved!');

// Toast 표시 여부 확인
bool shown = NyToastRecorder.wasShown(id: 'success');

// 모든 기록된 Toast 접근
List<ToastRecord> toasts = NyToastRecorder.records;

// 기록된 Toast 초기화
NyToastRecorder.clear();
```

### 잠금 및 로딩 어서션

`NyPage`/`NyState` 위젯의 이름 있는 잠금 및 로딩 상태에 대해 어서션합니다:

``` dart
// 명명된 잠금 유지 어서션
expectLocked(tester, find.byType(MyPage), 'submit');

// 명명된 잠금 미유지 어서션
expectNotLocked(tester, find.byType(MyPage), 'submit');

// 명명된 로딩 키 활성 어서션
expectLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');

// 명명된 로딩 키 비활성 어서션
expectNotLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');
```

<div id="custom-matchers"></div>

## 커스텀 매처

`expect()`와 함께 커스텀 매처를 사용합니다:

``` dart
// 타입 매처
expect(result, isType<User>());

// 라우트 이름 매처
expect(widget, hasRouteName('/home'));

// Backpack 매처
expect(true, backpackHas("key", value: "expected"));

// API 호출 매처
expect(true, apiWasCalled('/users', method: 'GET', times: 1));
```

<div id="state-testing"></div>

## State 테스트

EventBus 기반의 상태 관리를 `NyPage` 및 `NyState` 위젯에서 State 테스트 헬퍼를 사용하여 테스트합니다.

### State 업데이트 발생

다른 위젯이나 컨트롤러에서 오는 상태 업데이트를 시뮬레이션합니다:

``` dart
// UpdateState 이벤트 발생
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### State 액션 발생

페이지의 `whenStateAction()`에서 처리되는 State 액션을 전송합니다:

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// 추가 데이터 포함
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### State 어서션

``` dart
// 상태 업데이트 발생 어서션
expectStateUpdated('HomePageState');
expectStateUpdated('HomePageState', times: 2);

// 상태 액션 발생 어서션
expectStateAction('HomePageState', 'refresh-page');
expectStateAction('CartState', 'add-item', times: 1);

// NyPage/NyState 위젯의 stateData 어서션
expectStateData(tester, find.byType(MyWidget), equals(42));
```

### NyStateTestHelpers

발생된 State 업데이트 및 액션을 추적하고 검사합니다:

``` dart
// 상태에 발생한 모든 업데이트 가져오기
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// 상태에 발생한 모든 액션 가져오기
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// 추적된 모든 상태 업데이트 및 액션 초기화
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

<div id="nav-interaction"></div>

## 내비게이션 및 인터랙션 헬퍼

`WidgetTester` 확장은 `nyWidgetTest` 내에서 내비게이션 흐름과 UI 인터랙션을 작성하기 위한 고수준 DSL을 제공합니다.

### visit

라우트로 이동하고 페이지가 안정될 때까지 기다립니다:

``` dart
nyWidgetTest('loads dashboard', (tester) async {
  await tester.visit(DashboardPage.path);
  expectTextVisible('Dashboard');
});
```

### assertNavigatedTo

내비게이션 동작이 예상한 라우트로 이동시켰는지 어서트합니다:

``` dart
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);
```

### assertOnRoute

현재 라우트가 지정한 라우트와 일치하는지 어서트합니다（직전 내비게이션 동작이 아닌 현재 위치 확인에 사용）:

``` dart
await tester.visit(DashboardPage.path);
tester.assertOnRoute(DashboardPage.path);
```

### settle

보류 중인 모든 애니메이션과 프레임 콜백이 완료될 때까지 기다립니다:

``` dart
await tester.tap(find.byType(MyButton));
await tester.settle();
tester.assertNavigatedTo(ProfilePage.path);
```

### navigateBack

현재 라우트를 팝하고 안정될 때까지 기다립니다:

``` dart
await tester.visit(DashboardPage.path);
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);

await tester.navigateBack();
tester.assertOnRoute(DashboardPage.path);
```

### tapText

텍스트로 위젯을 찾아 탭하고 한 번의 호출로 안정될 때까지 기다립니다:

``` dart
await tester.tapText('Login');
await tester.tapText('Submit');
```

### fillField

폼 필드를 탭하고 텍스트를 입력한 후 안정될 때까지 기다립니다:

``` dart
await tester.fillField(find.byKey(Key('email')), 'test@example.com');
await tester.fillField(find.byKey(Key('password')), 'secret123');
```

### scrollTo

위젯이 보일 때까지 스크롤하고 안정될 때까지 기다립니다:

``` dart
await tester.scrollTo(find.text('Item 50'));
await tester.tapText('Item 50');
```

특정 `scrollable` 파인더와 `delta`를 전달하여 세밀하게 제어할 수 있습니다:

``` dart
await tester.scrollTo(
  find.text('Footer'),
  scrollable: find.byKey(Key('main_list')),
  delta: 200,
);
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

      // 알려진 날짜에서 구독 로직 테스트
      expect(NyTime.now().year, equals(2025));

      NyTest.travelForward(Duration(days: 365));
      expect(NyTime.now().year, equals(2026));

      NyTest.travelBack();
    });
  });
}
```
