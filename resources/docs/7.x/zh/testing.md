# 测试

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [开始使用](#getting-started "开始使用")
- [编写测试](#writing-tests "编写测试")
  - [nyTest](#ny-test "nyTest")
  - [nyWidgetTest](#ny-widget-test "nyWidgetTest")
  - [Widget 测试工具](#widget-testing-utilities "Widget 测试工具")
  - [nyGroup](#ny-group "nyGroup")
  - [测试生命周期](#test-lifecycle "测试生命周期")
  - [跳过测试和 CI 测试](#skipping-tests "跳过测试和 CI 测试")
- [身份验证](#authentication "身份验证")
- [时间穿越](#time-travel "时间穿越")
- [API 模拟](#api-mocking "API 模拟")
  - [按 URL 模式模拟](#mocking-by-url "按 URL 模式模拟")
  - [按 API 服务类型模拟](#mocking-by-type "按 API 服务类型模拟")
  - [调用历史和断言](#call-history "调用历史和断言")
- [工厂](#factories "工厂")
  - [定义工厂](#defining-factories "定义工厂")
  - [工厂状态](#factory-states "工厂状态")
  - [创建实例](#creating-instances "创建实例")
- [NyFaker](#ny-faker "NyFaker")
- [测试缓存](#test-cache "测试缓存")
- [平台通道模拟](#platform-channel-mocking "平台通道模拟")
- [路由守卫模拟](#route-guard-mocking "路由守卫模拟")
- [断言](#assertions "断言")
- [自定义匹配器](#custom-matchers "自定义匹配器")
- [状态测试](#state-testing "状态测试")
- [调试](#debugging "调试")
- [示例](#examples "示例")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} v7 包含一个受 Laravel 测试工具启发的全面测试框架。它提供：

- **测试函数**，具有自动设置/拆卸功能（`nyTest`、`nyWidgetTest`、`nyGroup`）
- 通过 `NyTest.actingAs<T>()` 进行**身份验证模拟**
- **时间穿越**，用于在测试中冻结或操纵时间
- **API 模拟**，支持 URL 模式匹配和调用追踪
- **工厂**，内置假数据生成器（`NyFaker`）
- **平台通道模拟**，用于安全存储、路径提供者等
- **自定义断言**，用于路由、Backpack、身份验证和环境

<div id="getting-started"></div>

## 开始使用

在测试文件顶部初始化测试框架：

``` dart
import 'package:nylo_framework/nylo_framework.dart';

void main() {
  NyTest.init();

  nyTest('my first test', () async {
    expect(1 + 1, equals(2));
  });
}
```

`NyTest.init()` 设置测试环境，并在 `autoReset: true`（默认值）时启用测试之间的自动状态重置。

<div id="writing-tests"></div>

## 编写测试

<div id="ny-test"></div>

### nyTest

编写测试的主要函数：

``` dart
nyTest('can save and read from storage', () async {
  backpackSave("name", "Anthony");
  expect(backpackRead<String>("name"), equals("Anthony"));
});
```

选项：

``` dart
nyTest('my test', () async {
  // test body
}, skip: false, timeout: Timeout(Duration(seconds: 30)));
```

<div id="ny-widget-test"></div>

### nyWidgetTest

用于使用 `WidgetTester` 测试 Flutter 组件：

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

### Widget 测试工具

`NyWidgetTest` 类和 `WidgetTester` 扩展提供了辅助方法，用于以正确的主题支持泵送 Nylo Widget、等待 `init()` 完成以及测试加载状态。

#### 配置测试环境

在 `setUpAll` 中调用 `NyWidgetTest.configure()` 以禁用 Google Fonts 获取，并可选地设置自定义主题：

``` dart
nySetUpAll(() async {
  NyWidgetTest.configure(testTheme: ThemeData.light());
  await setupApplication(providers);
});
```

您可以使用 `NyWidgetTest.reset()` 重置配置。

两个内置主题可用于无字体测试：

``` dart
ThemeData light = NyWidgetTest.simpleTestTheme;
ThemeData dark = NyWidgetTest.simpleDarkTestTheme;
```

#### 泵送 Nylo Widget

使用 `pumpNyWidget` 将 Widget 包装在带有主题支持的 `MaterialApp` 中：

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

使用无字体主题快速泵送：

``` dart
await tester.pumpNyWidgetSimple(HomePage());
```

#### 等待初始化

`pumpNyWidgetAndWaitForInit` 持续泵送帧直到加载指示器消失（或达到超时），适用于具有异步 `init()` 方法的页面：

``` dart
await tester.pumpNyWidgetAndWaitForInit(
  HomePage(),
  timeout: Duration(seconds: 10),
  useSimpleTheme: true,
);
// init() has completed
expect(find.text('Loaded Data'), findsOneWidget);
```

#### 泵送辅助方法

``` dart
// Pump frames until a specific widget appears
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// Settle gracefully (won't throw on timeout)
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### 生命周期模拟

在 Widget 树中的任何 `NyPage` 上模拟 `AppLifecycleState` 变化：

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// Assert side effects of the paused lifecycle action
```

#### 加载和锁定检查

检查 `NyPage`/`NyState` Widget 上的命名加载键和锁定：

``` dart
// Check if a named loading key is active
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// Check if a named lock is held
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// Check for any loading indicator (CircularProgressIndicator or Skeletonizer)
bool isAnyLoading = tester.isLoading();
```

#### testNyPage 辅助方法

一个便捷函数，泵送 `NyPage`，等待初始化完成，然后运行您的期望：

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

#### testNyPageLoading 辅助方法

测试页面在 `init()` 期间是否显示加载指示器：

``` dart
testNyPageLoading(
  'HomePage shows loading state',
  build: () => HomePage(),
  skip: false,
);
```

#### NyPageTestMixin

提供常用页面测试工具的 mixin：

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

将相关测试分组在一起：

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

### 测试生命周期

使用生命周期钩子设置和拆卸逻辑：

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

### 跳过测试和 CI 测试

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

## 身份验证

在测试中模拟已认证用户：

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

登出用户：

``` dart
NyTest.logout();
expectGuest();
```

<div id="time-travel"></div>

## 时间穿越

使用 `NyTime` 在测试中操纵时间：

### 跳转到指定日期

``` dart
nyTest('time travel to 2025', () async {
  NyTest.travel(DateTime(2025, 1, 1));

  expect(NyTime.now().year, equals(2025));

  NyTest.travelBack(); // Reset to real time
});
```

### 前进或后退时间

``` dart
NyTest.travelForward(Duration(days: 30)); // Jump 30 days ahead
NyTest.travelBackward(Duration(hours: 2)); // Go back 2 hours
```

### 冻结时间

``` dart
NyTest.freezeTime(); // Freeze at the current moment

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // Time hasn't moved

NyTest.travelBack(); // Unfreeze
```

### 时间边界

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 1st of current month
NyTime.travelToEndOfMonth();   // Last day of current month
NyTime.travelToStartOfYear();  // Jan 1st
NyTime.travelToEndOfYear();    // Dec 31st
```

### 作用域时间穿越

在冻结时间的上下文中执行代码：

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// Time is automatically restored after the callback
```

<div id="api-mocking"></div>

## API 模拟

<div id="mocking-by-url"></div>

### 按 URL 模式模拟

使用支持通配符的 URL 模式模拟 API 响应：

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

### 按 API 服务类型模拟

按类型模拟整个 API 服务：

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

### 调用历史和断言

追踪和验证 API 调用：

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

### 创建模拟响应

``` dart
Response<Map<String, dynamic>> response = NyMockApi.createResponse(
  data: {'id': 1, 'name': 'Anthony'},
  statusCode: 200,
  statusMessage: 'OK',
);
```

<div id="factories"></div>

## 工厂

<div id="defining-factories"></div>

### 定义工厂

定义如何创建模型的测试实例：

``` dart
NyFactory.define<User>((NyFaker faker) => User(
  name: faker.name(),
  email: faker.email(),
  phone: faker.phone(),
));
```

支持覆盖：

``` dart
NyFactory.defineWithOverrides<User>((NyFaker faker, Map<String, dynamic> attributes) => User(
  name: attributes['name'] ?? faker.name(),
  email: attributes['email'] ?? faker.email(),
  phone: attributes['phone'] ?? faker.phone(),
));
```

<div id="factory-states"></div>

### 工厂状态

定义工厂的变体：

``` dart
NyFactory.state<User>('admin', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, role: 'admin');
});

NyFactory.state<User>('premium', (User user, NyFaker faker) {
  return User(name: user.name, email: user.email, subscription: 'premium');
});
```

<div id="creating-instances"></div>

### 创建实例

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

`NyFaker` 为测试生成逼真的假数据。它在工厂定义中可用，也可以直接实例化。

``` dart
NyFaker faker = NyFaker();
```

### 可用方法

| 分类 | 方法 | 返回类型 | 描述 |
|----------|--------|-------------|-------------|
| **姓名** | `faker.firstName()` | `String` | 随机名字 |
| | `faker.lastName()` | `String` | 随机姓氏 |
| | `faker.name()` | `String` | 全名（名字 + 姓氏） |
| | `faker.username()` | `String` | 用户名字符串 |
| **联系方式** | `faker.email()` | `String` | 电子邮箱地址 |
| | `faker.phone()` | `String` | 电话号码 |
| | `faker.company()` | `String` | 公司名称 |
| **数字** | `faker.randomInt(min, max)` | `int` | 范围内的随机整数 |
| | `faker.randomDouble(min, max)` | `double` | 范围内的随机浮点数 |
| | `faker.randomBool()` | `bool` | 随机布尔值 |
| **标识符** | `faker.uuid()` | `String` | UUID v4 字符串 |
| **日期** | `faker.date()` | `DateTime` | 随机日期 |
| | `faker.pastDate()` | `DateTime` | 过去的日期 |
| | `faker.futureDate()` | `DateTime` | 未来的日期 |
| **文本** | `faker.lorem()` | `String` | Lorem ipsum 文字 |
| | `faker.sentences()` | `String` | 多个句子 |
| | `faker.paragraphs()` | `String` | 多个段落 |
| | `faker.slug()` | `String` | URL slug |
| **网络** | `faker.url()` | `String` | URL 字符串 |
| | `faker.imageUrl()` | `String` | 图片 URL（通过 picsum.photos） |
| | `faker.ipAddress()` | `String` | IPv4 地址 |
| | `faker.macAddress()` | `String` | MAC 地址 |
| **位置** | `faker.address()` | `String` | 街道地址 |
| | `faker.city()` | `String` | 城市名称 |
| | `faker.state()` | `String` | 美国州缩写 |
| | `faker.zipCode()` | `String` | 邮政编码 |
| | `faker.country()` | `String` | 国家名称 |
| **其他** | `faker.hexColor()` | `String` | 十六进制颜色代码 |
| | `faker.creditCardNumber()` | `String` | 信用卡号码 |
| | `faker.randomElement(list)` | `T` | 从列表中随机选取一项 |
| | `faker.randomElements(list, count)` | `List<T>` | 从列表中随机选取多项 |

<div id="test-cache"></div>

## 测试缓存

`NyTestCache` 提供内存缓存，用于测试缓存相关功能：

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

## 平台通道模拟

`NyMockChannels` 自动模拟常见的平台通道，防止测试崩溃：

``` dart
void main() {
  NyTest.init(); // Automatically sets up mock channels

  // Or set up manually
  NyMockChannels.setup();
}
```

### 已模拟的通道

- **path_provider** -- 文档、临时、应用支持、库和缓存目录
- **flutter_secure_storage** -- 内存安全存储
- **flutter_timezone** -- 时区数据
- **flutter_local_notifications** -- 通知通道
- **sqflite** -- 数据库操作

### 覆盖路径

``` dart
NyMockChannels.overridePathProvider(
  documentsPath: '/custom/documents',
  temporaryPath: '/custom/temp',
);
```

### 测试中的安全存储

``` dart
NyMockChannels.setSecureStorageValue("token", "test_abc123");

Map<String, String> storage = NyMockChannels.getSecureStorage();
NyMockChannels.clearSecureStorage();
```

<div id="route-guard-mocking"></div>

## 路由守卫模拟

`NyMockRouteGuard` 允许您在没有真实身份验证或网络调用的情况下测试路由守卫行为。它扩展了 `NyRouteGuard` 并为常见场景提供工厂构造函数。

### 始终通过的守卫

``` dart
final guard = NyMockRouteGuard.pass();
```

### 重定向的守卫

``` dart
final guard = NyMockRouteGuard.redirect('/login');

// With additional data
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### 自定义逻辑的守卫

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // abort navigation
  }
  return GuardResult.next; // allow navigation
});
```

### 跟踪守卫调用

守卫被调用后，您可以检查其状态：

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// Access the RouteContext from the last call
RouteContext? context = guard.lastContext;

// Reset tracking
guard.reset();
```

<div id="assertions"></div>

## 断言

{{ config('app.name') }} 提供自定义断言函数：

### 路由断言

``` dart
expectRoute('/home');           // Assert current route
expectNotRoute('/login');       // Assert not on route
expectRouteInHistory('/home');  // Assert route was visited
expectRouteExists('/profile');  // Assert route is registered
expectRoutesExist(['/home', '/profile', '/settings']);
```

### 状态断言

``` dart
expectBackpackContains("key");                        // Key exists
expectBackpackContains("key", value: "expected");     // Key has value
expectBackpackNotContains("key");                     // Key doesn't exist
```

### 身份验证断言

``` dart
expectAuthenticated<User>();  // User is authenticated
expectGuest();                // No user authenticated
```

### 环境断言

``` dart
expectEnv("APP_NAME", "MyApp");  // Env variable equals value
expectEnvSet("APP_KEY");          // Env variable is set
```

### 模式断言

``` dart
expectTestMode();
expectDebugMode();
expectProductionMode();
expectDevelopingMode();
```

### API 断言

``` dart
expectApiCalled('/users');
expectApiCalled('/users', method: 'POST', times: 2);
expectApiNotCalled('/admin');
```

### 语言环境断言

``` dart
expectLocale("en");
```

### Toast 断言

验证测试期间记录的 Toast 通知。需要在测试 setUp 中调用 `NyToastRecorder.setup()`：

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

**NyToastRecorder** 在测试期间跟踪 Toast 通知：

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

### 锁定和加载断言

验证 `NyPage`/`NyState` Widget 中的命名锁定和加载状态：

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

## 自定义匹配器

使用自定义匹配器配合 `expect()`：

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

## 状态测试

使用状态测试辅助方法测试 `NyPage` 和 `NyState` Widget 中基于 EventBus 的状态管理。

### 触发状态更新

模拟通常来自其他 Widget 或控制器的状态更新：

``` dart
// Fire an UpdateState event
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### 触发状态操作

发送由页面中 `whenStateAction()` 处理的状态操作：

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// With additional data
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### 状态断言

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

跟踪和检查触发的状态更新和操作：

``` dart
// Get all updates fired to a state
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// Get all actions fired to a state
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// Reset all tracked state updates and actions
NyStateTestHelpers.reset();
```

<div id="debugging"></div>

## 调试

### dump

打印当前测试状态（Backpack 内容、认证用户、时间、API 调用、语言环境）：

``` dart
NyTest.dump();
```

### dd（打印并终止）

打印测试状态并立即终止测试：

``` dart
NyTest.dd();
```

### 测试状态存储

在测试期间存储和检索值：

``` dart
NyTest.set("step", "completed");
String? step = NyTest.get<String>("step");
```

### 预填充 Backpack

使用测试数据预填充 Backpack：

``` dart
NyTest.seedBackpack({
  "user_name": "Anthony",
  "auth_token": "test_token",
  "settings": {"theme": "dark"},
});
```

<div id="examples"></div>

## 示例

### 完整测试文件

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
