# 路由守卫

---

<a name="section-1"></a>
- [简介](#introduction "Introduction")
- [创建路由守卫](#creating-a-route-guard "Creating a Route Guard")
- [守卫生命周期](#guard-lifecycle "Guard Lifecycle")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
  - [onLeave](#on-leave "onLeave")
- [RouteContext](#route-context "RouteContext")
- [守卫操作](#guard-actions "Guard Actions")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [将守卫应用于路由](#applying-guards "Applying Guards to Routes")
- [分组守卫](#group-guards "Group Guards")
- [守卫组合](#guard-composition "Guard Composition")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [示例](#examples "Practical Examples")

<div id="introduction"></div>

## 简介

路由守卫为 {{ config('app.name') }} 提供**导航中间件**功能。它们拦截路由转换，允许你控制用户是否可以访问某个页面、将其重定向到其他页面，或修改传递给路由的数据。

常见用例包括：
- **身份验证检查** -- 将未认证用户重定向到登录页面
- **基于角色的访问控制** -- 限制页面仅供管理员用户访问
- **数据验证** -- 确保导航前所需数据已存在
- **数据补充** -- 向路由附加额外数据

守卫按**顺序**在导航发生之前执行。如果任何守卫返回 `handled`，导航将停止（执行重定向或中止操作）。

<div id="creating-a-route-guard"></div>

## 创建路由守卫

使用 Metro CLI 创建路由守卫：

``` bash
metro make:route_guard auth
```

这将生成一个守卫文件：

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Add your guard logic here
    return next();
  }
}
```

<div id="guard-lifecycle"></div>

## 守卫生命周期

每个路由守卫都有三个生命周期方法：

<div id="on-before"></div>

### onBefore

在导航发生**之前**调用。在这里检查条件并决定是允许、重定向还是中止导航。

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  bool isLoggedIn = await Auth.isAuthenticated();

  if (!isLoggedIn) {
    return redirect(HomePage.path);
  }

  return next();
}
```

返回值：
- `next()` -- 继续执行下一个守卫或导航到该路由
- `redirect(path)` -- 重定向到不同的路由
- `abort()` -- 完全取消导航

<div id="on-after"></div>

### onAfter

在导航成功**之后**调用。用于分析统计、日志记录或导航后的副作用操作。

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // Log page view
  Analytics.trackPageView(context.routeName);
}
```

<div id="on-leave"></div>

### onLeave

当用户**离开**某个路由时调用。返回 `false` 可以阻止用户离开。

``` dart
@override
Future<bool> onLeave(RouteContext context) async {
  if (hasUnsavedChanges) {
    // Show confirmation dialog
    return await showConfirmDialog();
  }
  return true; // Allow leaving
}
```

<div id="route-context"></div>

## RouteContext

`RouteContext` 对象被传递给所有守卫生命周期方法，包含有关导航的信息：

| 属性 | 类型 | 描述 |
|----------|------|-------------|
| `context` | `BuildContext?` | 当前的构建上下文 |
| `data` | `dynamic` | 传递给路由的数据 |
| `queryParameters` | `Map<String, String>` | URL 查询参数 |
| `routeName` | `String` | 目标路由的名称/路径 |
| `originalRouteName` | `String?` | 转换前的原始路由名称 |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  // Access route information
  String route = context.routeName;
  dynamic routeData = context.data;
  Map<String, String> params = context.queryParameters;

  return next();
}
```

### 转换路由上下文

创建具有不同数据的副本：

``` dart
// Change the data type
RouteContext<User> userContext = context.withData<User>(currentUser);

// Copy with modified fields
RouteContext updated = context.copyWith(
  data: enrichedData,
  queryParameters: {"tab": "settings"},
);
```

<div id="guard-actions"></div>

## 守卫操作

<div id="next"></div>

### next

继续执行链中的下一个守卫，如果这是最后一个守卫则导航到该路由：

``` dart
return next();
```

<div id="redirect"></div>

### redirect

将用户重定向到不同的路由：

``` dart
return redirect(LoginPage.path);
```

附带额外选项：

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `path` | `Object` | 必填 | 路由路径字符串或 RouteView |
| `data` | `dynamic` | null | 传递给重定向路由的数据 |
| `queryParameters` | `Map<String, dynamic>?` | null | 查询参数 |
| `navigationType` | `NavigationType` | `pushReplace` | 导航方式 |
| `result` | `dynamic` | null | 返回结果 |
| `removeUntilPredicate` | `Function?` | null | 路由移除谓词 |
| `transitionType` | `TransitionType?` | null | 页面过渡类型 |
| `onPop` | `Function(dynamic)?` | null | 弹出时的回调 |

<div id="abort"></div>

### abort

取消导航而不进行重定向。用户停留在当前页面：

``` dart
return abort();
```

<div id="set-data"></div>

### setData

修改将传递给后续守卫和目标路由的数据：

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  User user = await fetchUser();

  // Enrich the route data
  setData({"user": user, "originalData": context.data});

  return next();
}
```

<div id="applying-guards"></div>

## 将守卫应用于路由

在路由文件中为单个路由添加守卫：

``` dart
appRouter() => nyRoutes((router) {
  router.route(
    HomePage.path,
    (_) => HomePage(),
  ).initialRoute();

  // Add a single guard
  router.route(
    ProfilePage.path,
    (_) => ProfilePage(),
    routeGuards: [AuthRouteGuard()],
  );

  // Add multiple guards (executed in order)
  router.route(
    AdminPage.path,
    (_) => AdminPage(),
    routeGuards: [AuthRouteGuard(), AdminRoleGuard()],
  );
});
```

<div id="group-guards"></div>

## 分组守卫

使用路由分组将守卫同时应用于多个路由：

``` dart
appRouter() => nyRoutes((router) {
  router.route(HomePage.path, (_) => HomePage()).initialRoute();

  // All routes in this group require authentication
  router.group(() {
    return {
      'prefix': '/dashboard',
      'route_guards': [AuthRouteGuard()],
    };
  }, (router) {
    router.route(DashboardPage.path, (_) => DashboardPage());
    router.route(SettingsPage.path, (_) => SettingsPage());
    router.route(ProfilePage.path, (_) => ProfilePage());
  });
});
```

<div id="guard-composition"></div>

## 守卫组合

{{ config('app.name') }} 提供了将守卫组合在一起的工具，以实现可复用的模式。

<div id="guard-stack"></div>

### GuardStack

将多个守卫合并为一个可复用的守卫：

``` dart
final protectedRoute = GuardStack([
  AuthRouteGuard(),
  VerifyEmailGuard(),
  TwoFactorGuard(),
]);

// Use the stack on a route
router.route(
  SecurePage.path,
  (_) => SecurePage(),
  routeGuards: [protectedRoute],
);
```

`GuardStack` 按顺序执行守卫。如果任何守卫返回 `handled`，剩余的守卫将被跳过。

<div id="conditional-guard"></div>

### ConditionalGuard

仅在条件为真时应用守卫：

``` dart
router.route(
  BetaPage.path,
  (_) => BetaPage(),
  routeGuards: [
    ConditionalGuard(
      condition: (context) => context.queryParameters.containsKey("beta"),
      guard: BetaAccessGuard(),
    ),
  ],
);
```

如果条件返回 `false`，守卫将被跳过，导航继续进行。

<div id="parameterized-guard"></div>

### ParameterizedGuard

创建接受配置参数的守卫：

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params); // params = allowed roles

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();

    if (user == null || !params.contains(user.role)) {
      return redirect(UnauthorizedPage.path);
    }

    return next();
  }
}

// Usage
router.route(
  AdminPage.path,
  (_) => AdminPage(),
  routeGuards: [RoleGuard(["admin", "super_admin"])],
);
```

<div id="examples"></div>

## 示例

### 身份验证守卫

``` dart
class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    bool isAuthenticated = await Auth.isAuthenticated();

    if (!isAuthenticated) {
      return redirect(HomePage.path);
    }

    return next();
  }
}
```

### 带参数的订阅守卫

``` dart
class SubscriptionGuard extends ParameterizedGuard<List<String>> {
  SubscriptionGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    User? user = await Auth.user<User>();
    bool hasAccess = params.any((plan) => user?.subscription == plan);

    if (!hasAccess) {
      return redirect(UpgradePage.path, data: {"plans": params});
    }

    setData({"user": user});
    return next();
  }
}

// Require premium or pro subscription
router.route(
  PremiumPage.path,
  (_) => PremiumPage(),
  routeGuards: [
    AuthRouteGuard(),
    SubscriptionGuard(["premium", "pro"]),
  ],
);
```

### 日志守卫

``` dart
class LoggingGuard extends NyRouteGuard {
  LoggingGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    print("Navigating to: ${context.routeName}");
    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    print("Arrived at: ${context.routeName}");
  }
}
```
