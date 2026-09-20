# Route Guards

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Tạo Route Guard](#creating-a-route-guard "Tạo Route Guard")
- [Vòng đời Guard](#guard-lifecycle "Vòng đời Guard")
  - [onBefore](#on-before "onBefore")
  - [onAfter](#on-after "onAfter")
- [RouteContext](#route-context "RouteContext")
- [Hành động Guard](#guard-actions "Hành động Guard")
  - [next](#next "next")
  - [redirect](#redirect "redirect")
  - [abort](#abort "abort")
  - [setData](#set-data "setData")
- [Áp dụng Guards cho Routes](#applying-guards "Áp dụng Guards cho Routes")
- [Group Guards](#group-guards "Group Guards")
- [Kết hợp Guard](#guard-composition "Kết hợp Guard")
  - [GuardStack](#guard-stack "GuardStack")
  - [ConditionalGuard](#conditional-guard "ConditionalGuard")
  - [ParameterizedGuard](#parameterized-guard "ParameterizedGuard")
- [Ví dụ](#examples "Ví dụ thực tế")

<div id="introduction"></div>

## Giới thiệu

Route guards cung cấp **middleware cho điều hướng** trong {{ config('app.name') }}. Chúng chặn các chuyển đổi route và cho phép bạn kiểm soát liệu người dùng có thể truy cập trang, chuyển hướng họ đi nơi khác, hoặc sửa đổi dữ liệu được truyền đến route.

Các trường hợp sử dụng phổ biến bao gồm:
- **Kiểm tra xác thực** -- chuyển hướng người dùng chưa xác thực đến trang đăng nhập
- **Truy cập dựa trên vai trò** -- hạn chế trang cho người dùng admin
- **Xác thực dữ liệu** -- đảm bảo dữ liệu cần thiết tồn tại trước khi điều hướng
- **Làm giàu dữ liệu** -- đính kèm dữ liệu bổ sung vào route

Guards được thực thi **theo thứ tự** trước khi điều hướng xảy ra. Nếu bất kỳ guard nào trả về `handled`, điều hướng dừng lại (chuyển hướng hoặc hủy bỏ).

<div id="creating-a-route-guard"></div>

## Tạo Route Guard

Tạo route guard bằng Metro CLI:

``` bash
metro make:route_guard auth
```

Lệnh này tạo một tệp guard:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class AuthRouteGuard extends NyRouteGuard {
  AuthRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Thêm logic guard của bạn ở đây
    return next();
  }
}
```

<div id="guard-lifecycle"></div>

## Vòng đời Guard

Mọi route guard có hai phương thức vòng đời:

<div id="on-before"></div>

### onBefore

Được gọi **trước** khi điều hướng xảy ra. Đây là nơi bạn kiểm tra điều kiện và quyết định cho phép, chuyển hướng, hoặc hủy điều hướng.

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

Giá trị trả về:
- `next()` -- tiếp tục đến guard tiếp theo hoặc điều hướng đến route
- `redirect(path)` -- chuyển hướng đến route khác
- `abort()` -- hủy điều hướng hoàn toàn

<div id="on-after"></div>

### onAfter

Được gọi **sau** khi điều hướng thành công. Sử dụng cho phân tích, ghi log, hoặc các tác dụng phụ sau điều hướng.

``` dart
@override
Future<void> onAfter(RouteContext context) async {
  // Ghi nhận lượt xem trang
  Analytics.trackPageView(context.routeName);
}
```

<div id="route-context"></div>

## RouteContext

Đối tượng `RouteContext` được truyền đến tất cả phương thức vòng đời của guard và chứa thông tin về điều hướng:

| Thuộc tính | Kiểu | Mô tả |
|----------|------|-------------|
| `context` | `BuildContext?` | Build context hiện tại |
| `data` | `dynamic` | Dữ liệu được truyền đến route |
| `queryParameters` | `Map<String, String>` | Tham số query URL |
| `routeName` | `String` | Tên/đường dẫn của route đích |
| `originalRouteName` | `String?` | Tên route gốc trước khi chuyển đổi |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  // Truy cập thông tin route
  String route = context.routeName;
  dynamic routeData = context.data;
  Map<String, String> params = context.queryParameters;

  return next();
}
```

### Chuyển đổi Route Context

Tạo bản sao với dữ liệu khác:

``` dart
// Thay đổi kiểu dữ liệu
RouteContext<User> userContext = context.withData<User>(currentUser);

// Sao chép với các trường đã sửa đổi
RouteContext updated = context.copyWith(
  data: enrichedData,
  queryParameters: {"tab": "settings"},
);
```

<div id="guard-actions"></div>

## Hành động Guard

<div id="next"></div>

### next

Tiếp tục đến guard tiếp theo trong chuỗi, hoặc điều hướng đến route nếu đây là guard cuối cùng:

``` dart
return next();
```

<div id="redirect"></div>

### redirect

Chuyển hướng người dùng đến route khác:

``` dart
return redirect(LoginPage.path);
```

Với các tùy chọn bổ sung:

``` dart
return redirect(
  LoginPage.path,
  data: {"returnTo": context.routeName},
  navigationType: NavigationType.pushReplace,
  queryParameters: {"source": "guard"},
);
```

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `path` | `Object` | bắt buộc | Chuỗi đường dẫn route hoặc RouteView |
| `data` | `dynamic` | null | Dữ liệu truyền đến route chuyển hướng |
| `queryParameters` | `Map<String, dynamic>?` | null | Tham số query |
| `navigationType` | `NavigationType` | `pushReplace` | Phương thức điều hướng |
| `result` | `dynamic` | null | Kết quả trả về |
| `removeUntilPredicate` | `Function?` | null | Điều kiện xóa route |
| `transitionType` | `TransitionType?` | null | Kiểu chuyển trang |
| `onPop` | `Function(dynamic)?` | null | Callback khi pop |

<div id="abort"></div>

### abort

Hủy điều hướng mà không chuyển hướng. Người dùng ở lại trang hiện tại:

``` dart
return abort();
```

<div id="set-data"></div>

### setData

Sửa đổi dữ liệu sẽ được truyền đến các guard tiếp theo và route đích:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  User user = await fetchUser();

  // Làm giàu dữ liệu route
  setData({"user": user, "originalData": context.data});

  return next();
}
```

<div id="applying-guards"></div>

## Áp dụng Guards cho Routes

Thêm guards vào các route riêng lẻ trong tệp router:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  // Thêm một guard
  router.add(
    ProfilePage.path,
    routeGuards: [AuthRouteGuard()],
  );

  // Thêm nhiều guards (thực thi theo thứ tự)
  router.add(
    AdminPage.path,
    routeGuards: [AuthRouteGuard(), AdminRoleGuard()],
  );
});
```

<div id="group-guards"></div>

## Group Guards

Áp dụng guards cho nhiều routes cùng lúc bằng route groups:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  // Tất cả routes trong group này yêu cầu xác thực
  router.group(() {
    return {
      'prefix': '/dashboard',
      'route_guards': [AuthRouteGuard()],
    };
  }, (router) {
    router.add(DashboardPage.path);
    router.add(SettingsPage.path);
    router.add(ProfilePage.path);
  });
});
```

<div id="guard-composition"></div>

## Kết hợp Guard

{{ config('app.name') }} cung cấp các công cụ để kết hợp guards cho các mẫu có thể tái sử dụng.

<div id="guard-stack"></div>

### GuardStack

Kết hợp nhiều guards thành một guard có thể tái sử dụng:

``` dart
final protectedRoute = GuardStack([
  AuthRouteGuard(),
  VerifyEmailGuard(),
  TwoFactorGuard(),
]);

// Sử dụng stack cho route
router.add(
  SecurePage.path,
  routeGuards: [protectedRoute],
);
```

`GuardStack` thực thi guards theo thứ tự. Nếu bất kỳ guard nào trả về `handled`, các guard còn lại sẽ bị bỏ qua.

<div id="conditional-guard"></div>

### ConditionalGuard

Áp dụng guard chỉ khi điều kiện là true:

``` dart
router.add(
  BetaPage.path,
  routeGuards: [
    ConditionalGuard(
      condition: (context) => context.queryParameters.containsKey("beta"),
      guard: BetaAccessGuard(),
    ),
  ],
);
```

Nếu điều kiện trả về `false`, guard sẽ bị bỏ qua và điều hướng tiếp tục.

<div id="parameterized-guard"></div>

### ParameterizedGuard

Tạo guards nhận tham số cấu hình:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params); // params = các vai trò được phép

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    String? role = Auth.data(field: 'role');

    if (role == null || !params.contains(role)) {
      return redirect(UnauthorizedPage.path);
    }

    return next();
  }
}

// Sử dụng
router.add(
  AdminPage.path,
  routeGuards: [RoleGuard(["admin", "super_admin"])],
);
```

<div id="examples"></div>

## Ví dụ

### Guard xác thực

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

### Guard đăng ký với tham số

``` dart
class SubscriptionGuard extends ParameterizedGuard<List<String>> {
  SubscriptionGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    String? plan = Auth.data(field: 'subscription');
    bool hasAccess = params.contains(plan);

    if (!hasAccess) {
      return redirect(UpgradePage.path, data: {"plans": params});
    }

    setData({"plan": plan});
    return next();
  }
}

// Yêu cầu đăng ký premium hoặc pro
router.add(
  PremiumPage.path,
  routeGuards: [
    AuthRouteGuard(),
    SubscriptionGuard(["premium", "pro"]),
  ],
);
```

### Guard ghi log

``` dart
class LoggingGuard extends NyRouteGuard {
  LoggingGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    print("Đang điều hướng đến: ${context.routeName}");
    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    print("Đã đến: ${context.routeName}");
  }
}
```
