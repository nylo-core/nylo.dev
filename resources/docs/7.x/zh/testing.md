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
- [导航和交互辅助方法](#nav-interaction "导航和交互辅助方法")
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
  // 测试主体
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
// init() 已完成
expect(find.text('Loaded Data'), findsOneWidget);
```

#### 泵送辅助方法

``` dart
// 持续泵送帧直到特定组件出现
bool found = await tester.pumpUntilFound(
  find.text('Welcome'),
  timeout: Duration(seconds: 5),
);

// 优雅等待稳定（超时不抛出异常）
await tester.pumpAndSettleGracefully(timeout: Duration(seconds: 5));
```

#### 生命周期模拟

在 Widget 树中的任何 `NyPage` 上模拟 `AppLifecycleState` 变化：

``` dart
await tester.pumpNyWidget(MyPage());
await tester.simulateLifecycleState(AppLifecycleState.paused);
await tester.pump();
// 断言暂停生命周期动作的副作用
```

#### 加载和锁定检查

检查 `NyPage`/`NyState` Widget 上的命名加载键和锁定：

``` dart
// 检查命名加载键是否活跃
bool loading = tester.isLoadingNamed(find.byType(MyPage), name: 'fetchUsers');

// 检查命名锁是否被持有
bool locked = tester.isLockedNamed(find.byType(MyPage), name: 'submit');

// 检查是否有任何加载指示器（CircularProgressIndicator 或 Skeletonizer）
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
    // 验证 init 已调用且加载已完成
    await verifyInitCalled(tester, HomePage(), timeout: Duration(seconds: 5));

    // 验证 init 期间显示加载状态
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
    // 在所有测试之前运行一次
  });

  nySetUp(() {
    // 在每个测试之前运行
  });

  nyTearDown(() {
    // 在每个测试之后运行
  });

  nyTearDownAll(() {
    // 在所有测试之后运行一次
  });
}
```

<div id="skipping-tests"></div>

### 跳过测试和 CI 测试

``` dart
// 跳过测试并说明原因
nySkip('not implemented yet', () async {
  // ...
}, "Waiting for API update");

// 预期失败的测试
nyFailing('known bug', () async {
  // ...
});

// 仅 CI 测试（标记为 'ci'）
nyCi('integration test', () async {
  // 仅在 CI 环境中运行
});
```

<div id="authentication"></div>

## 身份验证

在测试中模拟已认证用户：

``` dart
nyTest('user can access profile', () async {
  // 模拟已登录用户
  NyTest.actingAs<User>(User(name: "Anthony", email: "anthony@example.com"));

  // 验证已认证
  expectAuthenticated<User>();

  // 访问当前用户
  User? user = NyTest.actingUser<User>();
  expect(user?.name, equals("Anthony"));
});

nyTest('guest cannot access profile', () async {
  // 验证未认证
  expectGuest();
});
```

登出用户：

``` dart
NyTest.logout();
expectGuest();
```

在设置访客上下文时，使用 `actingAsGuest()` 作为 `logout()` 的可读别名：

``` dart
NyTest.actingAsGuest();
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

  NyTest.travelBack(); // 重置为真实时间
});
```

### 前进或后退时间

``` dart
NyTest.travelForward(Duration(days: 30)); // 前进 30 天
NyTest.travelBackward(Duration(hours: 2)); // 后退 2 小时
```

### 冻结时间

``` dart
NyTest.freezeTime(); // 在当前时刻冻结时间

DateTime frozen = NyTime.now();
await Future.delayed(Duration(seconds: 1));
expect(NyTime.now(), equals(frozen)); // 时间未移动

NyTest.travelBack(); // 解冻
```

### 时间边界

``` dart
NyTime.travelToStartOfDay();   // 00:00:00.000
NyTime.travelToEndOfDay();     // 23:59:59.999
NyTime.travelToStartOfMonth(); // 当月第 1 天
NyTime.travelToEndOfMonth();   // 当月最后一天
NyTime.travelToStartOfYear();  // 1 月 1 日
NyTime.travelToEndOfYear();    // 12 月 31 日
```

### 作用域时间穿越

在冻结时间的上下文中执行代码：

``` dart
await NyTime.withFrozenTime<void>(DateTime(2025, 6, 15), () async {
  expect(NyTime.now(), equals(DateTime(2025, 6, 15)));
});
// 回调结束后时间自动恢复
```

<div id="api-mocking"></div>

## API 模拟

<div id="mocking-by-url"></div>

### 按 URL 模式模拟

使用支持通配符的 URL 模式模拟 API 响应：

``` dart
nyTest('mock API responses', () async {
  // 精确 URL 匹配
  NyMockApi.respond('/users/1', {'id': 1, 'name': 'Anthony'});

  // 单段通配符 (*)
  NyMockApi.respond('/users/*', {'id': 1, 'name': 'User'});

  // 多段通配符 (**)
  NyMockApi.respond('/api/**', {'status': 'ok'});

  // 带状态码和请求头
  NyMockApi.respond(
    '/users',
    {'error': 'Unauthorized'},
    statusCode: 401,
    method: 'POST',
    headers: {'X-Error': 'true'},
  );

  // 带模拟延迟
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

  // ... 触发 API 调用的操作 ...

  // 断言端点已被调用
  expectApiCalled('/users');

  // 断言端点未被调用
  expectApiNotCalled('/admin');

  // 断言调用次数
  expectApiCalled('/users', times: 2);

  // 断言特定请求方法
  expectApiCalled('/users', method: 'POST');

  // 获取调用详情
  List<ApiCallInfo> calls = NyMockApi.getCallsFor('/users');
});
```

断言某个端点被以特定请求体数据调用：

``` dart
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
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
// 创建单个实例
User user = NyFactory.make<User>();

// 带覆盖创建
User admin = NyFactory.make<User>(overrides: {'name': 'Admin User'});

// 应用状态后创建
User premiumAdmin = NyFactory.make<User>(states: ['admin', 'premium']);

// 创建多个实例
List<User> users = NyFactory.create<User>(count: 5);

// 使用基于索引的数据创建序列
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

  // 存储值
  await cache.put<String>("key", "value");

  // 带过期时间存储
  await cache.put<String>("temp", "data", seconds: 60);

  // 读取值
  String? value = await cache.get<String>("key");

  // 检查是否存在
  bool exists = await cache.has("key");

  // 清除某个键
  await cache.clear("key");

  // 清除全部
  await cache.flush();

  // 获取缓存信息
  int size = await cache.size();
  List<String> keys = await cache.documents();
});
```

<div id="platform-channel-mocking"></div>

## 平台通道模拟

`NyMockChannels` 自动模拟常见的平台通道，防止测试崩溃：

``` dart
void main() {
  NyTest.init(); // 自动设置模拟通道

  // 或手动设置
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

// 带附加数据
final guard = NyMockRouteGuard.redirect('/error', data: {'code': 403});
```

### 自定义逻辑的守卫

``` dart
final guard = NyMockRouteGuard.custom((context) async {
  if (context.data == null) {
    return GuardResult.handled; // 中止导航
  }
  return GuardResult.next; // 允许导航
});
```

### 跟踪守卫调用

守卫被调用后，您可以检查其状态：

``` dart
expect(guard.wasCalled, isTrue);
expect(guard.callCount, 1);

// 访问最后一次调用的 RouteContext
RouteContext? context = guard.lastContext;

// 重置跟踪
guard.reset();
```

<div id="assertions"></div>

## 断言

{{ config('app.name') }} 提供自定义断言函数：

### 路由断言

``` dart
expectRoute('/home');           // 断言当前路由
expectNotRoute('/login');       // 断言不在该路由
expectRouteInHistory('/home');  // 断言路由已被访问
expectRouteExists('/profile');  // 断言路由已注册
expectRoutesExist(['/home', '/profile', '/settings']);
```

### 状态断言

``` dart
expectBackpackContains("key");                        // 键存在
expectBackpackContains("key", value: "expected");     // 键有值
expectBackpackNotContains("key");                     // 键不存在
```

### 身份验证断言

``` dart
expectAuthenticated<User>();  // 用户已认证
expectGuest();                // 无用户认证
```

### 环境断言

``` dart
expectEnv("APP_NAME", "MyApp");  // 环境变量等于该值
expectEnvSet("APP_KEY");          // 环境变量已设置
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
expectApiCalledWith('/users', method: 'POST', data: {'name': 'John'});
```

### 组件断言

``` dart
// 断言某类型组件出现指定次数
expectWidgetCount(ListTile, 3);
expectWidgetCount(Icon, 0);

// 断言文本可见
expectTextVisible('Welcome');

// 断言文本不可见
expectTextNotVisible('Error');

// 断言任意组件可见（使用任意 Finder）
expectVisible(find.byType(FloatingActionButton));
expectVisible(find.byIcon(Icons.notifications));
expectVisible(find.byKey(Key('submit_btn')));

// 断言组件不可见
expectNotVisible(find.byType(ErrorBanner));
expectNotVisible(find.byKey(Key('loading_spinner')));
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
  // ... 触发显示 Toast 的操作 ...

  expectToastShown(id: 'success');
  expectToastShown(id: 'danger', description: 'Something went wrong');
  expectNoToastShown(id: 'info');
});
```

**NyToastRecorder** 在测试期间跟踪 Toast 通知：

``` dart
// 手动记录 Toast
NyToastRecorder.record(id: 'success', title: 'Done', description: 'Saved!');

// 检查是否显示了某个 Toast
bool shown = NyToastRecorder.wasShown(id: 'success');

// 访问所有已记录的 Toast
List<ToastRecord> toasts = NyToastRecorder.records;

// 清除已记录的 Toast
NyToastRecorder.clear();
```

### 锁定和加载断言

验证 `NyPage`/`NyState` Widget 中的命名锁定和加载状态：

``` dart
// 断言命名锁被持有
expectLocked(tester, find.byType(MyPage), 'submit');

// 断言命名锁未被持有
expectNotLocked(tester, find.byType(MyPage), 'submit');

// 断言命名加载键处于活跃状态
expectLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');

// 断言命名加载键未处于活跃状态
expectNotLoadingNamed(tester, find.byType(MyPage), 'fetchUsers');
```

<div id="custom-matchers"></div>

## 自定义匹配器

使用自定义匹配器配合 `expect()`：

``` dart
// 类型匹配器
expect(result, isType<User>());

// 路由名匹配器
expect(widget, hasRouteName('/home'));

// Backpack 匹配器
expect(true, backpackHas("key", value: "expected"));

// API 调用匹配器
expect(true, apiWasCalled('/users', method: 'GET', times: 1));
```

<div id="state-testing"></div>

## 状态测试

使用状态测试辅助方法测试 `NyPage` 和 `NyState` Widget 中基于 EventBus 的状态管理。

### 触发状态更新

模拟通常来自其他 Widget 或控制器的状态更新：

``` dart
// 触发 UpdateState 事件
fireStateUpdate('HomePageState', data: {'items': ['a', 'b']});
await tester.pump();
expect(find.text('a'), findsOneWidget);
```

### 触发状态操作

发送由页面中 `whenStateAction()` 处理的状态操作：

``` dart
fireStateAction('HomePageState', 'refresh-page');
await tester.pump();

// 带附加数据
fireStateAction('CartState', 'add-item', data: {'id': 42});
await tester.pump();
```

### 状态断言

``` dart
// 断言状态更新已触发
expectStateUpdated('HomePageState');
expectStateUpdated('HomePageState', times: 2);

// 断言状态动作已触发
expectStateAction('HomePageState', 'refresh-page');
expectStateAction('CartState', 'add-item', times: 1);

// 断言 NyPage/NyState 组件的 stateData
expectStateData(tester, find.byType(MyWidget), equals(42));
```

### NyStateTestHelpers

跟踪和检查触发的状态更新和操作：

``` dart
// 获取向某个状态触发的所有更新
List updates = NyStateTestHelpers.getUpdatesFor('MyWidget');

// 获取向某个状态触发的所有动作
List actions = NyStateTestHelpers.getActionsFor('MyWidget');

// 重置所有已跟踪的状态更新和动作
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

<div id="nav-interaction"></div>

## 导航和交互辅助方法

`WidgetTester` 扩展提供了一个高层次的 DSL，用于在 `nyWidgetTest` 中编写导航流程和 UI 交互。

### visit

导航到某个路由并等待页面稳定：

``` dart
nyWidgetTest('loads dashboard', (tester) async {
  await tester.visit(DashboardPage.path);
  expectTextVisible('Dashboard');
});
```

### assertNavigatedTo

断言某个导航操作将你带到了预期的路由：

``` dart
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);
```

### assertOnRoute

断言当前路由与给定路由匹配（用于确认所在位置，而非刚刚导航的动作）：

``` dart
await tester.visit(DashboardPage.path);
tester.assertOnRoute(DashboardPage.path);
```

### settle

等待所有挂起的动画和帧回调完成：

``` dart
await tester.tap(find.byType(MyButton));
await tester.settle();
tester.assertNavigatedTo(ProfilePage.path);
```

### navigateBack

弹出当前路由并等待稳定：

``` dart
await tester.visit(DashboardPage.path);
await tester.tapText('Profile');
tester.assertNavigatedTo(ProfilePage.path);

await tester.navigateBack();
tester.assertOnRoute(DashboardPage.path);
```

### tapText

通过文本查找组件，点击并在一次调用中等待稳定：

``` dart
await tester.tapText('Login');
await tester.tapText('Submit');
```

### fillField

点击表单字段、输入文本并等待稳定：

``` dart
await tester.fillField(find.byKey(Key('email')), 'test@example.com');
await tester.fillField(find.byKey(Key('password')), 'secret123');
```

### scrollTo

滚动直到组件可见，然后等待稳定：

``` dart
await tester.scrollTo(find.text('Item 50'));
await tester.tapText('Item 50');
```

传入特定的 `scrollable` 查找器和 `delta` 以进行精确控制：

``` dart
await tester.scrollTo(
  find.text('Footer'),
  scrollable: find.byKey(Key('main_list')),
  delta: 200,
);
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

      // ... 触发 API 调用 ...

      expectApiCalled('/api/users');
    });
  });

  nyGroup('Time-Sensitive Features', () {
    nyTest('subscription expires correctly', () async {
      NyTest.travel(DateTime(2025, 1, 1));

      // 在已知日期测试订阅逻辑
      expect(NyTime.now().year, equals(2025));

      NyTest.travelForward(Duration(days: 365));
      expect(NyTime.now().year, equals(2026));

      NyTest.travelBack();
    });
  });
}
```
