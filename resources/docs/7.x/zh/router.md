# 路由

---

<a name="section-1"></a>

- [简介](#introduction "简介")
- 基础
  - [添加路由](#adding-routes "添加路由")
  - [导航到页面](#navigating-to-pages "导航到页面")
  - [初始路由](#initial-route "初始路由")
  - [预览路由](#preview-route "预览路由")
  - [认证路由](#authenticated-route "认证路由")
  - [未知路由](#unknown-route "未知路由")
- 向另一个页面传递数据
  - [向另一个页面传递数据](#passing-data-to-another-page "向另一个页面传递数据")
- 导航
  - [导航类型](#navigation-types "导航类型")
  - [返回导航](#navigating-back "返回导航")
  - [条件导航](#conditional-navigation "条件导航")
  - [页面过渡](#page-transitions "页面过渡")
  - [路由历史](#route-history "路由历史")
  - [更新路由栈](#update-route-stack "更新路由栈")
- 路由参数
  - [使用路由参数](#route-parameters "路由参数")
  - [查询参数](#query-parameters "查询参数")
- 路由守卫
  - [创建路由守卫](#route-guards "路由守卫")
  - [NyRouteGuard 生命周期](#nyroute-guard-lifecycle "NyRouteGuard 生命周期")
  - [守卫辅助方法](#guard-helper-methods "守卫辅助方法")
  - [参数化守卫](#parameterized-guards "参数化守卫")
  - [守卫栈](#guard-stacks "守卫栈")
  - [条件守卫](#conditional-guards "条件守卫")
- 路由组
  - [路由组](#route-groups "路由组")
- [深度链接](#deep-linking "深度链接")
- [高级](#advanced "高级")



<div id="introduction"></div>

## 简介

路由允许您定义应用中的不同页面并在它们之间导航。

在以下情况下使用路由：
- 定义应用中可用的页面
- 在屏幕之间导航用户
- 在认证后保护页面
- 将数据从一个页面传递到另一个页面
- 处理来自 URL 的深度链接

您可以在 `lib/routes/router.dart` 文件中添加路由。

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // add more routes
  // router.add(AccountPage.path);

});
```

> **提示：** 您可以手动创建路由，也可以使用 <a href="/docs/{{ $version }}/metro">Metro</a> CLI 工具来为您创建。

以下是使用 Metro 创建"account"页面的示例。

``` bash
metro make:page account_page
```

``` dart
// Adds your new route automatically to /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

您可能还需要将数据从一个视图传递到另一个视图。在 {{ config('app.name') }} 中，这可以使用 `NyStatefulWidget`（一个内置路由数据访问的有状态组件）来实现。我们将深入探讨其工作原理。


<div id="adding-routes"></div>

## 添加路由

这是向项目添加新路由的最简单方式。

运行以下命令创建一个新页面。

```bash
metro make:page profile_page
```

运行上述命令后，它将创建一个名为 `ProfilePage` 的新 Widget 并将其添加到 `resources/pages/` 目录中。
同时也会将新路由添加到 `lib/routes/router.dart` 文件中。

文件：<b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // My new route
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## 导航到页面

您可以使用 `routeTo` 辅助函数导航到新页面。

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## 初始路由

在路由器中，您可以使用 `.initialRoute()` 方法定义应该首先加载的页面。

设置初始路由后，它将成为打开应用时加载的第一个页面。

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // new initial route
});
```


### 条件初始路由

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

### 导航到初始路由

使用 `routeToInitial()` 导航到应用的初始路由：

``` dart
void _goHome() {
    routeToInitial();
}
```

这将导航到标记了 `.initialRoute()` 的路由并清除导航栈。

<div id="preview-route"></div>

## 预览路由

在开发期间，您可能希望快速预览特定页面而不永久更改初始路由。使用 `.previewRoute()` 可以临时将任何路由设为初始路由：

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // This will be shown first during development
});
```

`previewRoute()` 方法：
- 覆盖任何现有的 `initialRoute()` 和 `authenticatedRoute()` 设置
- 将指定路由设为初始路由
- 在开发期间快速测试特定页面时很有用

> **警告：** 记得在发布应用之前移除 `.previewRoute()`！

<div id="authenticated-route"></div>

## 认证路由

在您的应用中，您可以定义一个路由作为用户认证后的初始路由。
这将自动覆盖默认的初始路由，成为用户登录后看到的第一个页面。

首先，您的用户应使用 `Auth.authenticate({...})` 辅助函数进行登录。

现在，当他们打开应用时，您定义的路由将成为默认页面，直到他们退出登录。

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // auth page
});
```

### 条件认证路由

您还可以设置条件认证路由：

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### 导航到认证路由

您可以使用 `routeToAuthenticatedRoute()` 辅助函数导航到认证页面：

``` dart
routeToAuthenticatedRoute();
```

**另请参阅：** [认证](/docs/{{ $version }}/authentication) 了解有关用户认证和会话管理的详细信息。


<div id="unknown-route"></div>

## 未知路由

您可以使用 `.unknownRoute()` 定义一个路由来处理 404/未找到的场景：

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

当用户导航到不存在的路由时，将显示未知路由页面。


<div id="route-guards"></div>

## 路由守卫

路由守卫保护页面免受未授权访问。它们在导航完成之前运行，允许您根据条件重定向用户或阻止访问。

在以下情况下使用路由守卫：
- 保护页面免受未认证用户的访问
- 在允许访问之前检查权限
- 根据条件重定向用户（例如，未完成的引导流程）
- 记录或跟踪页面浏览

要创建新的路由守卫，运行以下命令。

``` bash
metro make:route_guard dashboard
```

接下来，将新的路由守卫添加到您的路由中。

``` dart
// File: /routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(LoginPage.path);

  router.add(DashboardPage.path,
    routeGuards: [
      DashboardRouteGuard() // Add your guard
    ]
  ); // restricted page
});
```

您也可以使用 `addRouteGuard` 方法设置路由守卫：

``` dart
// File: /routes/router.dart
appRouter() => nyRoutes((router) {
    router.add(DashboardPage.path)
            .addRouteGuard(MyRouteGuard());

    // or add multiple guards

    router.add(DashboardPage.path)
            .addRouteGuards([MyRouteGuard(), MyOtherRouteGuard()]);
})
```

<div id="nyroute-guard-lifecycle"></div>

## NyRouteGuard 生命周期

在 v7 中，路由守卫使用 `NyRouteGuard` 类，包含三个生命周期方法：

- **`onBefore(RouteContext context)`** - 在导航之前调用。返回 `next()` 继续，`redirect()` 重定向到其他页面，或 `abort()` 停止导航。
- **`onAfter(RouteContext context)`** - 成功导航到路由后调用。

### 基本示例

文件：**/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Perform a check if they can access the page
    bool userLoggedIn = await Auth.isAuthenticated();

    if (userLoggedIn == false) {
      return redirect(LoginPage.path);
    }

    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    // Track page view after successful navigation
    Analytics.trackPageView(context.routeName);
  }
}
```

### RouteContext

`RouteContext` 类提供对导航信息的访问：

| 属性 | 类型 | 描述 |
|----------|------|-------------|
| `context` | `BuildContext?` | 当前构建上下文 |
| `data` | `dynamic` | 传递给路由的数据 |
| `queryParameters` | `Map<String, String>` | URL 查询参数 |
| `routeName` | `String` | 路由名称/路径 |
| `originalRouteName` | `String?` | 转换前的原始路由名称 |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  print('Navigating to: ${context.routeName}');
  print('Query params: ${context.queryParameters}');
  print('Route data: ${context.data}');

  return next();
}
```

<div id="guard-helper-methods"></div>

## 守卫辅助方法

### next()

继续到下一个守卫或路由：

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Allow navigation to continue
}
```

### redirect()

重定向到不同的路由：

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (!isLoggedIn) {
    return redirect(
      LoginPage.path,
      data: {'returnTo': context.routeName},
      navigationType: NavigationType.pushReplace,
    );
  }
  return next();
}
```

`redirect()` 方法接受以下参数：

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `path` | `Object` | 路由路径或 RouteView |
| `data` | `dynamic` | 传递给路由的数据 |
| `queryParameters` | `Map<String, dynamic>?` | 查询参数 |
| `navigationType` | `NavigationType` | 导航类型（默认：pushReplace） |
| `transitionType` | `TransitionType?` | 页面过渡效果 |
| `onPop` | `Function(dynamic)?` | 路由弹出时的回调 |

### abort()

停止导航但不重定向：

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (isMaintenanceMode) {
    showMaintenanceDialog();
    return abort(); // User stays on current route
  }
  return next();
}
```

### setData()

修改传递给后续守卫和路由的数据：

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## 参数化守卫

当需要按路由配置守卫行为时，使用 `ParameterizedGuard`：

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    final user = await Auth.user();
    if (!params.any((role) => user.hasRole(role))) {
      return redirect('/unauthorized');
    }
    return next();
  }
}

// Usage:
router.add(AdminPage.path, routeGuards: [
  RoleGuard(['admin', 'moderator'])
]);
```

<div id="guard-stacks"></div>

## 守卫栈

使用 `GuardStack` 将多个守卫组合成一个可重用的守卫：

``` dart
// Create reusable guard combinations
final adminGuards = GuardStack([
  AuthGuard(),
  RoleGuard(['admin']),
  AuditLogGuard(),
]);

router.add(AdminPage.path, routeGuards: [adminGuards]);
```

<div id="conditional-guards"></div>

## 条件守卫

根据条件有条件地应用守卫：

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## 向另一个页面传递数据

在本节中，我们将展示如何将数据从一个组件传递到另一个组件。

从您的 Widget 中，使用 `routeTo` 辅助函数并传递要发送到新页面的 `data`。

``` dart
// HomePage Widget
void _pressedSettings() {
    routeTo(SettingsPage.path, data: "Hello World");
}
...
// SettingsPage Widget (other page)
class _SettingsPageState extends NyPage<SettingsPage> {
  ...
  @override
  get init => () {
    print(widget.data()); // Hello World
    // or
    print(data()); // Hello World
  };
```

更多示例

``` dart
// Home page widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo(ProfilePage.path, data: user);
  }

...

// Profile page widget (other page)
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    User user = widget.data();
    print(user.firstName); // Anthony

  };
```


<div id="route-groups"></div>

## 路由组

路由组用于组织相关路由并应用共享设置。当多个路由需要相同的守卫、URL 前缀或过渡样式时，它们非常有用。

在以下情况下使用路由组：
- 将相同的路由守卫应用于多个页面
- 为一组路由添加 URL 前缀（例如 `/admin/...`）
- 为相关路由设置相同的页面过渡效果

您可以像下面的示例一样定义路由组。

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.group(() => {
    "route_guards": [AuthRouteGuard()],
    "prefix": "/dashboard",
    "transition_type": TransitionType.fade(),
  }, (router) {
    router.add(ChatPage.path);

    router.add(FollowersPage.path);
  });
```

#### 路由组的可选设置：

| 设置 | 类型 | 描述 |
|---------|------|-------------|
| `route_guards` | `List<RouteGuard>` | 对组内所有路由应用路由守卫 |
| `prefix` | `String` | 为组内所有路由路径添加前缀 |
| `transition_type` | `TransitionType` | 为组内所有路由设置过渡效果 |
| `transition` | `PageTransitionType` | 设置页面过渡类型（已弃用，使用 transition_type） |
| `transition_settings` | `PageTransitionSettings` | 设置过渡设置 |


<div id="route-parameters"></div>

## 使用路由参数

创建新页面时，您可以更新路由以接受参数。

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

现在，当您导航到该页面时，可以传递 `userId`

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

您可以在新页面中像这样访问参数。

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## 查询参数

导航到新页面时，您还可以提供查询参数。

让我们看一下。

```dart
  // Home page
  routeTo(ProfilePage.path, queryParameters: {"user": "7"});
  // navigate to profile page

  ...

  // Profile Page
  @override
  get init => () {
    print(widget.queryParameters()); // {"user": 7}
    // or
    print(queryParameters()); // {"user": 7}
  };
```

> **注意：** 只要您的页面 Widget 扩展了 `NyStatefulWidget` 和 `NyPage` 类，您就可以调用 `widget.queryParameters()` 来获取路由名称中的所有查询参数。

```dart
// Example page
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// Home page
class MyHomePage extends NyStatefulWidget<HomeController> {
  ...
}

class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () {
    widget.queryParameters(); // {"hello": "World", "say": "I love code"}
    // or
    queryParameters(); // {"hello": "World", "say": "I love code"}
  };
```

> **提示：** 查询参数必须遵循 HTTP 协议，例如 /account?userId=1&tab=2


<div id="page-transitions"></div>

## 页面过渡

您可以通过修改 `router.dart` 文件来在页面导航时添加过渡效果。

``` dart
import 'package:page_transition/page_transition.dart';

appRouter() => nyRoutes((router) {

  // bottomToTop
  router.add(SettingsPage.path,
    transitionType: TransitionType.bottomToTop()
  );

  // fade
  router.add(HomePage.path,
    transitionType: TransitionType.fade()
  );

});
```

### 可用的页面过渡效果

#### 基础过渡
- **`TransitionType.fade()`** - 新页面淡入，旧页面淡出
- **`TransitionType.theme()`** - 使用应用主题的页面过渡主题

#### 方向滑动过渡
- **`TransitionType.rightToLeft()`** - 从屏幕右边缘滑入
- **`TransitionType.leftToRight()`** - 从屏幕左边缘滑入
- **`TransitionType.topToBottom()`** - 从屏幕上边缘滑入
- **`TransitionType.bottomToTop()`** - 从屏幕下边缘滑入

#### 带淡入的滑动过渡
- **`TransitionType.rightToLeftWithFade()`** - 从右边缘滑入并淡入
- **`TransitionType.leftToRightWithFade()`** - 从左边缘滑入并淡入

#### 变换过渡
- **`TransitionType.scale(alignment: ...)`** - 从指定对齐点缩放
- **`TransitionType.rotate(alignment: ...)`** - 围绕指定对齐点旋转
- **`TransitionType.size(alignment: ...)`** - 从指定对齐点增长

#### 联合过渡（需要当前组件）
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - 当前页面向右退出，新页面从左侧进入
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - 当前页面向左退出，新页面从右侧进入
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - 当前页面向下退出，新页面从上方进入
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - 当前页面向上退出，新页面从下方进入

#### 弹出过渡（需要当前组件）
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - 当前页面向右退出，新页面保持不动
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - 当前页面向左退出，新页面保持不动
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - 当前页面向下退出，新页面保持不动
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - 当前页面向上退出，新页面保持不动

#### Material Design 共享轴过渡
- **`TransitionType.sharedAxisHorizontal()`** - 水平滑动和淡入过渡
- **`TransitionType.sharedAxisVertical()`** - 垂直滑动和淡入过渡
- **`TransitionType.sharedAxisScale()`** - 缩放和淡入过渡

#### 自定义参数
每个过渡效果接受以下可选参数：

| 参数 | 描述 | 默认值 |
|-----------|-------------|---------|
| `curve` | 动画曲线 | 平台特定曲线 |
| `duration` | 动画持续时间 | 平台特定持续时间 |
| `reverseDuration` | 反向动画持续时间 | 与 duration 相同 |
| `fullscreenDialog` | 路由是否为全屏对话框 | `false` |
| `opaque` | 路由是否不透明 | `false` |


``` dart
// Home page widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(ProfilePage.path,
      transitionType: TransitionType.bottomToTop()
    );
  }
...
```


<div id="navigation-types"></div>

## 导航类型

导航时，如果您使用 `routeTo` 辅助函数，可以指定以下类型之一。

| 类型 | 描述 |
|------|-------------|
| `NavigationType.push` | 将新页面推入应用的路由栈 |
| `NavigationType.pushReplace` | 替换当前路由，新路由完成后释放前一个路由 |
| `NavigationType.popAndPushNamed` | 弹出当前路由并推入一个命名路由替代 |
| `NavigationType.pushAndRemoveUntil` | 推入并移除路由，直到谓词返回 true |
| `NavigationType.pushAndForgetAll` | 推入新页面并释放路由栈上的所有其他页面 |

``` dart
// Home page widget
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(
      ProfilePage.path,
      navigationType: NavigationType.pushReplace
    );
  }
...
```


<div id="navigating-back"></div>

## 返回导航

在新页面上，您可以使用 `pop()` 辅助函数返回到上一个页面。

``` dart
// SettingsPage Widget
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop();
    // or
    Navigator.pop(context);
  }
...
```

如果您想向上一个组件返回一个值，请提供一个 `result`，如下面的示例所示。

``` dart
// SettingsPage Widget
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// Get the value from the previous widget using the `onPop` parameter
// HomePage Widget
class _HomePageState extends NyPage<HomePage> {

  _viewSettings() {
    routeTo(SettingsPage.path, onPop: (value) {
      print(value); // {"status": "COMPLETE"}
    });
  }
...

```

<div id="conditional-navigation"></div>

## 条件导航

使用 `routeIf()` 仅在满足条件时导航：

``` dart
// Only navigate if the user is logged in
routeIf(isLoggedIn, DashboardPage.path);

// With additional options
routeIf(
  hasPermission('view_reports'),
  ReportsPage.path,
  data: {'filters': defaultFilters},
  navigationType: NavigationType.push,
);
```

如果条件为 `false`，则不会发生导航。


<div id="route-history"></div>

## 路由历史

在 {{ config('app.name') }} 中，您可以使用以下辅助函数访问路由历史信息。

``` dart
// Get route history
Nylo.getRouteHistory(); // List<dynamic>

// Get the current route
Nylo.getCurrentRoute(); // Route<dynamic>?

// Get the previous route
Nylo.getPreviousRoute(); // Route<dynamic>?

// Get the current route name
Nylo.getCurrentRouteName(); // String?

// Get the previous route name
Nylo.getPreviousRouteName(); // String?

// Get the current route arguments
Nylo.getCurrentRouteArguments(); // dynamic

// Get the previous route arguments
Nylo.getPreviousRouteArguments(); // dynamic
```


<div id="update-route-stack"></div>

## 更新路由栈

您可以使用 `NyNavigator.updateStack()` 以编程方式更新导航栈：

``` dart
// Update the stack with a list of routes
NyNavigator.updateStack([
  HomePage.path,
  SettingsPage.path,
  ProfilePage.path,
], replace: true);

// Pass data to specific routes
NyNavigator.updateStack([
  HomePage.path,
  ProfilePage.path,
],
  replace: true,
  dataForRoute: {
    ProfilePage.path: {"userId": 42}
  }
);
```

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `routes` | `List<String>` | 必需 | 要导航到的路由路径列表 |
| `replace` | `bool` | `true` | 是否替换当前栈 |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | 传递给特定路由的数据 |

这在以下场景中很有用：
- 深度链接场景
- 恢复导航状态
- 构建复杂的导航流程


<div id="deep-linking"></div>

## 深度链接

深度链接允许用户使用 URL 直接导航到应用内的特定内容。这适用于：
- 分享指向特定应用内容的直接链接
- 针对特定应用功能的营销活动
- 处理应打开特定应用页面的通知
- 无缝的网页到应用过渡

## 设置

在应用中实现深度链接之前，请确保项目配置正确：

### 1. 平台配置

**iOS**：在 Xcode 项目中配置通用链接
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Flutter 通用链接配置指南</a>

**Android**：在 AndroidManifest.xml 中设置应用链接
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Flutter 应用链接配置指南</a>

### 2. 定义路由

所有应通过深度链接访问的路由都必须在路由配置中注册：

```dart
// File: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  // Basic routes
  router.add(HomePage.path).initialRoute();
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);

  // Route with parameters
  router.add(HotelBookingPage.path);
});
```

## 使用深度链接

配置完成后，您的应用可以处理各种格式的传入 URL：

### 基本深度链接

导航到特定页面的简单链接：

``` bash
https://yourdomain.com/profile       // Opens the profile page
https://yourdomain.com/settings      // Opens the settings page
```

要在应用内以编程方式触发这些导航：

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### 路径参数

对于需要动态数据作为路径一部分的路由：

#### 路由定义

```dart
class HotelBookingPage extends NyStatefulWidget {
  // Define a route with a parameter placeholder {id}
  static RouteView path = ("/hotel/{id}/booking", (_) => HotelBookingPage());

  HotelBookingPage({super.key}) : super(child: () => _HotelBookingPageState());
}

class _HotelBookingPageState extends NyPage<HotelBookingPage> {
  @override
  get init => () {
    // Access the path parameter
    final hotelId = queryParameters()["id"]; // Returns "87" for URL ../hotel/87/booking
    print("Loading hotel ID: $hotelId");

    // Use the ID to fetch hotel data or perform operations
  };

  // Rest of your page implementation
}
```

#### URL 格式

``` bash
https://yourdomain.com/hotel/87/booking
```

#### 编程式导航

```dart
// Navigate with parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### 查询参数

用于可选参数或需要多个动态值时：

#### URL 格式

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### 访问查询参数

```dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  get init => () {
    // Get all query parameters
    final params = queryParameters();

    // Access specific parameters
    final userId = params["user"];            // "20"
    final activeTab = params["tab"];          // "posts"

    // Alternative access method
    final params2 = widget.queryParameters();
    print(params2);                           // {"user": "20", "tab": "posts"}
  };
}
```

#### 使用查询参数的编程式导航

```dart
// Navigate with query parameters
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Combine path and query parameters
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## 处理深度链接

您可以在 `RouteProvider` 中处理深度链接事件：

```dart
class RouteProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.addRouter(appRouter());

    // Handle deep links
    nylo.onDeepLink(_onDeepLink);
    return nylo;
  }

  _onDeepLink(String route, Map<String, String>? data) {
    print("Deep link route: $route");
    print("Deep link data: $data");

    // Update the route stack for deep links
    if (route == ProfilePage.path) {
      NyNavigator.updateStack([
        HomePage.path,
        ProfilePage.path,
      ], replace: true, dataForRoute: {
        ProfilePage.path: data,
      });
    }
  }

  @override
  boot(Nylo nylo) async {
    nylo.initRoutes();
  }
}
```

### 测试深度链接

在开发和测试中，您可以使用 ADB（Android）或 xcrun（iOS）模拟深度链接激活：

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### 调试提示

- 在 init 方法中打印所有参数以验证正确解析
- 测试不同的 URL 格式以确保应用正确处理
- 请记住查询参数始终以字符串形式接收，根据需要将其转换为适当的类型

---

## 常见模式

### 参数类型转换

由于所有 URL 参数都是字符串形式传递的，您通常需要转换它们：

```dart
// Converting string parameters to appropriate types
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### 可选参数

处理参数可能缺失的情况：

```dart
final userId = queryParameters()["user"];
if (userId != null) {
  // Load specific user profile
} else {
  // Load current user profile
}

// Or check hasQueryParameter
if (hasQueryParameter('status')) {
  // Do something with the status parameter
} else {
  // Handle absence of the parameter
}
```


<div id="advanced"></div>

## 高级

### 检查路由是否存在

您可以检查路由是否在路由器中注册：

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### NyRouter 方法

`NyRouter` 类提供了一些有用的方法：

| 方法 | 描述 |
|--------|-------------|
| `getRegisteredRouteNames()` | 获取所有已注册路由名称的列表 |
| `getRegisteredRoutes()` | 获取所有已注册路由的映射 |
| `containsRoutes(routes)` | 检查路由器是否包含所有指定的路由 |
| `getInitialRouteName()` | 获取初始路由名称 |
| `getAuthRouteName()` | 获取认证路由名称 |
| `getUnknownRouteName()` | 获取未知/404 路由名称 |

### 获取路由参数

您可以使用 `NyRouter.args<T>()` 获取路由参数：

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    // Get typed arguments
    final args = NyRouter.args<NyArgument>(context);
    final userData = args?.data;

    return Scaffold(...);
  }
}
```

### NyArgument 和 NyQueryParameters

路由之间传递的数据被包装在这些类中：

``` dart
// NyArgument contains route data
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters contains URL query parameters
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
