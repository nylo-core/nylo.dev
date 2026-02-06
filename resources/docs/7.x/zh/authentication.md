# 认证

---

<a name="section-1"></a>
- [简介](#introduction "认证简介")
- 基础
  - [用户认证](#authenticating-users "用户认证")
  - [获取认证数据](#retrieving-auth-data "获取认证数据")
  - [更新认证数据](#updating-auth-data "更新认证数据")
  - [退出登录](#logging-out "退出登录")
  - [检查认证状态](#checking-authentication "检查认证状态")
- 高级
  - [多会话](#multiple-sessions "多会话")
  - [设备 ID](#device-id "设备 ID")
  - [同步到 Backpack](#syncing-to-backpack "同步到 Backpack")
- 路由配置
  - [初始路由](#initial-route "初始路由")
  - [认证路由](#authenticated-route "认证路由")
  - [预览路由](#preview-route "预览路由")
  - [未知路由](#unknown-route "未知路由")
- [辅助函数](#helper-functions "辅助函数")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} v7 通过 `Auth` 类提供了全面的认证系统。它处理用户凭证的安全存储、会话管理，并支持多个命名会话用于不同的认证场景。

认证数据安全存储并同步到 Backpack（内存键值存储），以便在整个应用中进行快速、同步的访问。

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Authenticate a user
await Auth.authenticate(data: {"token": "abc123", "userId": 1});

// Check if authenticated
bool loggedIn = await Auth.isAuthenticated(); // true

// Get auth data
dynamic token = Auth.data(field: 'token'); // "abc123"

// Logout
await Auth.logout();
```


<div id="authenticating-users"></div>

## 用户认证

使用 `Auth.authenticate()` 存储用户会话数据：

``` dart
// With a Map
await Auth.authenticate(data: {
  "token": "ey2sdm...",
  "userId": 123,
  "email": "user@example.com",
});

// With a Model class
User user = User(id: 123, email: "user@example.com", token: "ey2sdm...");
await Auth.authenticate(data: user);

// Without data (stores a timestamp)
await Auth.authenticate();
```

### 实际使用示例

``` dart
class _LoginPageState extends NyPage<LoginPage> {

  Future<void> handleLogin(String email, String password) async {
    // 1. Call your API to authenticate
    User? user = await api<AuthApiService>((request) => request.login(
      email: email,
      password: password,
    ));

    if (user == null) {
      showToastDanger(description: "Invalid credentials");
      return;
    }

    // 2. Store the authenticated user
    await Auth.authenticate(data: user);

    // 3. Navigate to home
    routeTo(HomePage.path, removeUntil: (_) => false);
  }
}
```


<div id="retrieving-auth-data"></div>

## 获取认证数据

使用 `Auth.data()` 获取已存储的认证数据：

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

`Auth.data()` 方法从 Backpack（{{ config('app.name') }} 的内存键值存储）读取以实现快速同步访问。当您进行认证时，数据会自动同步到 Backpack。


<div id="updating-auth-data"></div>

## 更新认证数据

{{ config('app.name') }} v7 引入了 `Auth.set()` 来更新认证数据：

``` dart
// Update a specific field
await Auth.set((data) {
  data['token'] = 'new_token_value';
  return data;
});

// Add new fields
await Auth.set((data) {
  data['refreshToken'] = 'refresh_abc123';
  data['lastLogin'] = DateTime.now().toIso8601String();
  return data;
});

// Replace entire data
await Auth.set((data) => {
  'token': newToken,
  'userId': userId,
});
```


<div id="logging-out"></div>

## 退出登录

使用 `Auth.logout()` 移除已认证的用户：

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### 退出所有会话

当使用多个会话时，清除所有会话：

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## 检查认证状态

检查用户是否已认证：

``` dart
bool isLoggedIn = await Auth.isAuthenticated();

if (isLoggedIn) {
  // User is authenticated
  routeTo(HomePage.path);
} else {
  // User needs to login
  routeTo(LoginPage.path);
}
```


<div id="multiple-sessions"></div>

## 多会话

{{ config('app.name') }} v7 支持多个命名认证会话，用于不同的场景。当您需要分别跟踪不同类型的认证时，这非常有用（例如，用户登录 vs 设备注册 vs 管理员访问）。

``` dart
// Default user session
await Auth.authenticate(data: user);

// Device authentication session
await Auth.authenticate(
  data: {"deviceToken": "abc123"},
  session: 'device',
);

// Admin session
await Auth.authenticate(
  data: adminUser,
  session: 'admin',
);
```

### 从命名会话读取

``` dart
// Default session
dynamic userData = Auth.data();
String? userToken = Auth.data(field: 'token');

// Device session
dynamic deviceData = Auth.data(session: 'device');
String? deviceToken = Auth.data(field: 'deviceToken', session: 'device');

// Admin session
dynamic adminData = Auth.data(session: 'admin');
```

### 按会话退出

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### 按会话检查认证

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## 设备 ID

{{ config('app.name') }} v7 提供了一个在应用会话之间持久化的唯一设备标识符：

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

设备 ID 特点：
- 仅生成一次并永久存储
- 每个设备/安装的标识唯一
- 适用于设备注册、分析或推送通知

``` dart
// Example: Register device with backend
Future<void> registerDevice() async {
  String deviceId = await Auth.deviceId();
  String? pushToken = await FirebaseMessaging.instance.getToken();

  await api<DeviceApiService>((request) => request.register(
    deviceId: deviceId,
    pushToken: pushToken,
  ));

  // Store device auth
  await Auth.authenticate(
    data: {"deviceId": deviceId, "pushToken": pushToken},
    session: 'device',
  );
}
```


<div id="syncing-to-backpack"></div>

## 同步到 Backpack

认证数据在您认证时会自动同步到 Backpack。要手动同步（例如在应用启动时）：

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

这在应用启动序列中非常有用，可以确保认证数据在 Backpack 中可用以进行快速同步访问。


<div id="initial-route"></div>

## 初始路由

初始路由是用户打开应用时看到的第一个页面。在路由器中使用 `.initialRoute()` 设置：

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

您还可以使用 `when` 参数设置条件初始路由：

``` dart
appRouter() => nyRoutes((router) {
  router.add(OnboardingPage.path).initialRoute(
    when: () => !hasCompletedOnboarding()
  );

  router.add(HomePage.path).initialRoute(
    when: () => hasCompletedOnboarding()
  );
});
```

使用 `routeToInitial()` 从任何位置导航回初始路由：

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## 认证路由

当用户已登录时，认证路由会覆盖初始路由。使用 `.authenticatedRoute()` 设置：

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

当应用启动时：
- `Auth.isAuthenticated()` 返回 `true` → 用户看到**认证路由**（HomePage）
- `Auth.isAuthenticated()` 返回 `false` → 用户看到**初始路由**（LoginPage）

您还可以设置条件认证路由：

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

使用 `routeToAuthenticatedRoute()` 以编程方式导航到认证路由：

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**另请参阅：** [Router](/docs/{{ $version }}/router) 获取完整的路由文档，包括守卫和深度链接。


<div id="preview-route"></div>

## 预览路由

在开发过程中，您可能希望快速预览特定页面而不更改初始路由或认证路由。使用 `.previewRoute()`：

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()` 会覆盖 `initialRoute()` 和 `authenticatedRoute()`，无论认证状态如何，都会将指定路由设为首先显示的页面。

> **警告：** 在发布应用之前，请移除 `.previewRoute()`。


<div id="unknown-route"></div>

## 未知路由

定义一个后备页面，用于用户导航到不存在的路由时显示。使用 `.unknownRoute()` 设置：

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### 完整组合

以下是包含所有路由类型的完整路由器设置：

``` dart
appRouter() => nyRoutes((router) {
  // First page for unauthenticated users
  router.add(LoginPage.path).initialRoute();

  // First page for authenticated users
  router.add(HomePage.path).authenticatedRoute();

  // 404 page
  router.add(NotFoundPage.path).unknownRoute();

  // Regular routes
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

| 路由方法 | 用途 |
|--------------|---------|
| `.initialRoute()` | 未认证用户看到的第一个页面 |
| `.authenticatedRoute()` | 已认证用户看到的第一个页面 |
| `.previewRoute()` | 在开发期间覆盖以上两者 |
| `.unknownRoute()` | 路由未找到时显示 |


<div id="helper-functions"></div>

## 辅助函数

{{ config('app.name') }} v7 提供了与 `Auth` 类方法对应的辅助函数：

| 辅助函数 | 等效方法 |
|-----------------|------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | 默认会话的存储键 |
| `authDeviceId()` | `Auth.deviceId()` |

所有辅助函数接受与其对应 `Auth` 类方法相同的参数，包括可选的 `session` 参数：

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```

