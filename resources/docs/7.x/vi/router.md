# Router

---

<a name="section-1"></a>

- [Giới thiệu](#introduction "Giới thiệu")
- Cơ bản
  - [Thêm routes](#adding-routes "Thêm routes")
  - [Điều hướng đến trang](#navigating-to-pages "Điều hướng đến trang")
  - [Route khởi tạo](#initial-route "Route khởi tạo")
  - [Route xem trước](#preview-route "Route xem trước")
  - [Route đã xác thực](#authenticated-route "Route đã xác thực")
  - [Route không xác định](#unknown-route "Route không xác định")
- Gửi dữ liệu sang trang khác
  - [Truyền dữ liệu sang trang khác](#passing-data-to-another-page "Truyền dữ liệu sang trang khác")
- Điều hướng
  - [Các kiểu điều hướng](#navigation-types "Các kiểu điều hướng")
  - [Điều hướng quay lại](#navigating-back "Điều hướng quay lại")
  - [Điều hướng có điều kiện](#conditional-navigation "Điều hướng có điều kiện")
  - [Hiệu ứng chuyển trang](#page-transitions "Hiệu ứng chuyển trang")
  - [Lịch sử Route](#route-history "Lịch sử Route")
  - [Cập nhật ngăn xếp Route](#update-route-stack "Cập nhật ngăn xếp Route")
- Tham số route
  - [Sử dụng tham số Route](#route-parameters "Tham số Route")
  - [Query Parameters](#query-parameters "Query Parameters")
- Route Guards
  - [Tạo Route Guards](#route-guards "Route Guards")
  - [Vòng đời NyRouteGuard](#nyroute-guard-lifecycle "Vòng đời NyRouteGuard")
  - [Các phương thức helper của Guard](#guard-helper-methods "Các phương thức helper của Guard")
  - [Guards có tham số](#parameterized-guards "Guards có tham số")
  - [Guard Stacks](#guard-stacks "Guard Stacks")
  - [Guards có điều kiện](#conditional-guards "Guards có điều kiện")
- Nhóm Route
  - [Nhóm Route](#route-groups "Nhóm Route")
- [Deep linking](#deep-linking "Deep linking")
- [Nâng cao](#advanced "Nâng cao")



<div id="introduction"></div>

## Giới thiệu

Routes cho phép bạn định nghĩa các trang khác nhau trong ứng dụng và điều hướng giữa chúng.

Sử dụng routes khi bạn cần:
- Định nghĩa các trang có sẵn trong ứng dụng
- Điều hướng người dùng giữa các màn hình
- Bảo vệ trang sau xác thực
- Truyền dữ liệu từ trang này sang trang khác
- Xử lý deep links từ URL

Bạn có thể thêm routes trong tệp `lib/routes/router.dart`.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // thêm routes
  // router.add(AccountPage.path);

});
```

> **Mẹo:** Bạn có thể tạo routes thủ công hoặc sử dụng công cụ <a href="/docs/{{ $version }}/metro">Metro</a> CLI để tạo tự động.

Dưới đây là ví dụ tạo trang 'account' sử dụng Metro.

``` bash
metro make:page account_page
```

``` dart
// Tự động thêm route mới vào /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

Bạn cũng có thể cần truyền dữ liệu từ một view sang view khác. Trong {{ config('app.name') }}, điều này có thể thực hiện bằng `NyStatefulWidget` (một stateful widget tích hợp truy cập dữ liệu route). Chúng ta sẽ đi sâu hơn để giải thích cách hoạt động.


<div id="adding-routes"></div>

## Thêm routes

Đây là cách dễ nhất để thêm routes mới vào dự án.

Chạy lệnh dưới đây để tạo trang mới.

```bash
metro make:page profile_page
```

Sau khi chạy lệnh trên, nó sẽ tạo một Widget mới tên `ProfilePage` và thêm vào thư mục `resources/pages/`.
Nó cũng sẽ thêm route mới vào tệp `lib/routes/router.dart`.

Tệp: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // Route mới
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## Điều hướng đến trang

Bạn có thể điều hướng đến trang mới sử dụng helper `routeTo`.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## Route khởi tạo

Trong router, bạn có thể định nghĩa trang đầu tiên được tải bằng phương thức `.initialRoute()`.

Sau khi thiết lập route khởi tạo, nó sẽ là trang đầu tiên tải khi bạn mở ứng dụng.

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // route khởi tạo mới
});
```


### Route khởi tạo có điều kiện

Bạn cũng có thể thiết lập route khởi tạo có điều kiện sử dụng tham số `when`:

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

### Điều hướng đến Route khởi tạo

Sử dụng `routeToInitial()` để điều hướng đến route khởi tạo của ứng dụng:

``` dart
void _goHome() {
    routeToInitial();
}
```

Lệnh này sẽ điều hướng đến route được đánh dấu `.initialRoute()` và xóa ngăn xếp điều hướng.

<div id="preview-route"></div>

## Route xem trước

Trong quá trình phát triển, bạn có thể muốn xem trước nhanh một trang cụ thể mà không cần thay đổi route khởi tạo vĩnh viễn. Sử dụng `.previewRoute()` để tạm thời biến bất kỳ route nào thành route khởi tạo:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // Sẽ hiển thị đầu tiên trong quá trình phát triển
});
```

Phương thức `previewRoute()`:
- Ghi đè mọi thiết lập `initialRoute()` và `authenticatedRoute()` hiện có
- Biến route được chỉ định thành route khởi tạo
- Hữu ích để kiểm tra nhanh các trang cụ thể trong quá trình phát triển

> **Cảnh báo:** Hãy xóa `.previewRoute()` trước khi phát hành ứng dụng!

<div id="authenticated-route"></div>

## Route đã xác thực

Trong ứng dụng, bạn có thể định nghĩa một route làm route khởi tạo khi người dùng đã xác thực.
Điều này sẽ tự động ghi đè route khởi tạo mặc định và là trang đầu tiên người dùng thấy khi đăng nhập.

Đầu tiên, người dùng cần được đăng nhập bằng helper `Auth.authenticate({...})`.

Bây giờ, khi họ mở ứng dụng, route bạn đã định nghĩa sẽ là trang mặc định cho đến khi họ đăng xuất.

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // trang xác thực
});
```

### Route đã xác thực có điều kiện

Bạn cũng có thể thiết lập route đã xác thực có điều kiện:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### Điều hướng đến Route đã xác thực

Bạn có thể điều hướng đến trang đã xác thực bằng helper `routeToAuthenticatedRoute()`:

``` dart
routeToAuthenticatedRoute();
```

**Xem thêm:** [Authentication](/docs/{{ $version }}/authentication) để biết chi tiết về xác thực người dùng và quản lý phiên.


<div id="unknown-route"></div>

## Route không xác định

Bạn có thể định nghĩa route để xử lý trường hợp 404/không tìm thấy bằng `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

Khi người dùng điều hướng đến route không tồn tại, họ sẽ thấy trang route không xác định.


<div id="route-guards"></div>

## Route guards

Route guards bảo vệ trang khỏi truy cập trái phép. Chúng chạy trước khi điều hướng hoàn tất, cho phép bạn chuyển hướng người dùng hoặc chặn truy cập dựa trên điều kiện.

Sử dụng route guards khi bạn cần:
- Bảo vệ trang khỏi người dùng chưa xác thực
- Kiểm tra quyền trước khi cho phép truy cập
- Chuyển hướng người dùng dựa trên điều kiện (ví dụ: chưa hoàn thành onboarding)
- Ghi log hoặc theo dõi lượt xem trang

Để tạo Route Guard mới, chạy lệnh dưới đây.

``` bash
metro make:route_guard dashboard
```

Tiếp theo, thêm Route Guard mới vào route.

``` dart
// Tệp: /routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(LoginPage.path);

  router.add(DashboardPage.path,
    routeGuards: [
      DashboardRouteGuard() // Thêm guard
    ]
  ); // trang bị hạn chế
});
```

Bạn cũng có thể thiết lập route guards bằng phương thức `addRouteGuard`:

``` dart
// Tệp: /routes/router.dart
appRouter() => nyRoutes((router) {
    router.add(DashboardPage.path)
            .addRouteGuard(MyRouteGuard());

    // hoặc thêm nhiều guards

    router.add(DashboardPage.path)
            .addRouteGuards([MyRouteGuard(), MyOtherRouteGuard()]);
})
```

<div id="nyroute-guard-lifecycle"></div>

## Vòng đời NyRouteGuard

Trong v7, route guards sử dụng class `NyRouteGuard` với ba phương thức vòng đời:

- **`onBefore(RouteContext context)`** - Được gọi trước khi điều hướng. Trả về `next()` để tiếp tục, `redirect()` để chuyển hướng, hoặc `abort()` để dừng.
- **`onAfter(RouteContext context)`** - Được gọi sau khi điều hướng thành công đến route.

### Ví dụ cơ bản

Tệp: **/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Kiểm tra xem họ có thể truy cập trang không
    bool userLoggedIn = await Auth.isAuthenticated();

    if (userLoggedIn == false) {
      return redirect(LoginPage.path);
    }

    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    // Theo dõi lượt xem trang sau khi điều hướng thành công
    Analytics.trackPageView(context.routeName);
  }
}
```

### RouteContext

Class `RouteContext` cung cấp quyền truy cập thông tin điều hướng:

| Thuộc tính | Kiểu | Mô tả |
|----------|------|-------------|
| `context` | `BuildContext?` | Build context hiện tại |
| `data` | `dynamic` | Dữ liệu được truyền đến route |
| `queryParameters` | `Map<String, String>` | Tham số query URL |
| `routeName` | `String` | Tên/đường dẫn route |
| `originalRouteName` | `String?` | Tên route gốc trước khi chuyển đổi |

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

## Các phương thức helper của Guard

### next()

Tiếp tục đến guard tiếp theo hoặc đến route:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Cho phép điều hướng tiếp tục
}
```

### redirect()

Chuyển hướng đến route khác:

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

Phương thức `redirect()` chấp nhận:

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `path` | `Object` | Đường dẫn route hoặc RouteView |
| `data` | `dynamic` | Dữ liệu truyền đến route |
| `queryParameters` | `Map<String, dynamic>?` | Tham số query |
| `navigationType` | `NavigationType` | Kiểu điều hướng (mặc định: pushReplace) |
| `transitionType` | `TransitionType?` | Hiệu ứng chuyển trang |
| `onPop` | `Function(dynamic)?` | Callback khi route bị đóng |

### abort()

Dừng điều hướng mà không chuyển hướng:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (isMaintenanceMode) {
    showMaintenanceDialog();
    return abort(); // Người dùng ở lại route hiện tại
  }
  return next();
}
```

### setData()

Chỉnh sửa dữ liệu truyền đến các guards tiếp theo và route:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## Guards có tham số

Sử dụng `ParameterizedGuard` khi bạn cần cấu hình hành vi guard theo từng route:

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

// Cách sử dụng:
router.add(AdminPage.path, routeGuards: [
  RoleGuard(['admin', 'moderator'])
]);
```

<div id="guard-stacks"></div>

## Guard Stacks

Kết hợp nhiều guards thành một guard tái sử dụng bằng `GuardStack`:

``` dart
// Tạo các tổ hợp guard tái sử dụng
final adminGuards = GuardStack([
  AuthGuard(),
  RoleGuard(['admin']),
  AuditLogGuard(),
]);

router.add(AdminPage.path, routeGuards: [adminGuards]);
```

<div id="conditional-guards"></div>

## Guards có điều kiện

Áp dụng guards có điều kiện dựa trên một predicate:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## Truyền dữ liệu sang trang khác

Trong phần này, chúng ta sẽ hướng dẫn cách truyền dữ liệu từ widget này sang widget khác.

Từ Widget của bạn, sử dụng helper `routeTo` và truyền `data` bạn muốn gửi đến trang mới.

``` dart
// Widget HomePage
void _pressedSettings() {
    routeTo(SettingsPage.path, data: "Hello World");
}
...
// Widget SettingsPage (trang khác)
class _SettingsPageState extends NyPage<SettingsPage> {
  ...
  @override
  get init => () {
    print(widget.data()); // Hello World
    // hoặc
    print(data()); // Hello World
  };
```

Thêm ví dụ

``` dart
// Widget trang Home
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo(ProfilePage.path, data: user);
  }

...

// Widget trang Profile (trang khác)
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    User user = widget.data();
    print(user.firstName); // Anthony

  };
```


<div id="route-groups"></div>

## Nhóm Route

Nhóm route tổ chức các routes liên quan và áp dụng cài đặt chung. Chúng hữu ích khi nhiều routes cần cùng guards, tiền tố URL hoặc kiểu chuyển trang.

Sử dụng nhóm route khi bạn cần:
- Áp dụng cùng route guard cho nhiều trang
- Thêm tiền tố URL cho một tập hợp routes (ví dụ: `/admin/...`)
- Thiết lập cùng hiệu ứng chuyển trang cho các routes liên quan

Bạn có thể định nghĩa nhóm route như ví dụ dưới đây.

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

#### Cài đặt tùy chọn cho nhóm route:

| Cài đặt | Kiểu | Mô tả |
|---------|------|-------------|
| `route_guards` | `List<RouteGuard>` | Áp dụng route guards cho tất cả routes trong nhóm |
| `prefix` | `String` | Thêm tiền tố cho tất cả đường dẫn route trong nhóm |
| `transition_type` | `TransitionType` | Thiết lập hiệu ứng chuyển cho tất cả routes trong nhóm |
| `transition` | `PageTransitionType` | Thiết lập kiểu chuyển trang (đã lỗi thời, dùng transition_type) |
| `transition_settings` | `PageTransitionSettings` | Thiết lập cài đặt chuyển trang |


<div id="route-parameters"></div>

## Sử dụng tham số Route

Khi bạn tạo trang mới, bạn có thể cập nhật route để chấp nhận tham số.

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

Bây giờ, khi bạn điều hướng đến trang, bạn có thể truyền `userId`

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

Bạn có thể truy cập tham số trong trang mới như sau.

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## Query Parameters

Khi điều hướng đến trang mới, bạn cũng có thể cung cấp query parameters.

Hãy xem ví dụ.

```dart
  // Trang Home
  routeTo(ProfilePage.path, queryParameters: {"user": "7"});
  // điều hướng đến trang profile

  ...

  // Trang Profile
  @override
  get init => () {
    print(widget.queryParameters()); // {"user": 7}
    // hoặc
    print(queryParameters()); // {"user": 7}
  };
```

> **Lưu ý:** Miễn là widget trang kế thừa class `NyStatefulWidget` và `NyPage`, bạn có thể gọi `widget.queryParameters()` để lấy tất cả query parameters từ tên route.

```dart
// Ví dụ trang
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// Trang Home
class MyHomePage extends NyStatefulWidget<HomeController> {
  ...
}

class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () {
    widget.queryParameters(); // {"hello": "World", "say": "I love code"}
    // hoặc
    queryParameters(); // {"hello": "World", "say": "I love code"}
  };
```

> **Mẹo:** Query parameters phải tuân theo giao thức HTTP, Ví dụ: /account?userId=1&tab=2


<div id="page-transitions"></div>

## Hiệu ứng chuyển trang

Bạn có thể thêm hiệu ứng chuyển khi điều hướng từ trang này bằng cách chỉnh sửa tệp `router.dart`.

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

### Các hiệu ứng chuyển trang có sẵn

#### Hiệu ứng cơ bản
- **`TransitionType.fade()`** - Làm mờ dần trang mới khi làm mờ trang cũ
- **`TransitionType.theme()`** - Sử dụng hiệu ứng chuyển trang của theme ứng dụng

#### Hiệu ứng trượt theo hướng
- **`TransitionType.rightToLeft()`** - Trượt từ cạnh phải màn hình
- **`TransitionType.leftToRight()`** - Trượt từ cạnh trái màn hình
- **`TransitionType.topToBottom()`** - Trượt từ cạnh trên màn hình
- **`TransitionType.bottomToTop()`** - Trượt từ cạnh dưới màn hình

#### Hiệu ứng trượt kết hợp mờ dần
- **`TransitionType.rightToLeftWithFade()`** - Trượt và mờ dần từ cạnh phải
- **`TransitionType.leftToRightWithFade()`** - Trượt và mờ dần từ cạnh trái

#### Hiệu ứng biến đổi
- **`TransitionType.scale(alignment: ...)`** - Phóng to từ điểm căn chỉnh chỉ định
- **`TransitionType.rotate(alignment: ...)`** - Xoay quanh điểm căn chỉnh chỉ định
- **`TransitionType.size(alignment: ...)`** - Phóng lớn từ điểm căn chỉnh chỉ định

#### Hiệu ứng nối (Yêu cầu widget hiện tại)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - Trang hiện tại thoát sang phải trong khi trang mới vào từ trái
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - Trang hiện tại thoát sang trái trong khi trang mới vào từ phải
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - Trang hiện tại thoát xuống trong khi trang mới vào từ trên
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - Trang hiện tại thoát lên trong khi trang mới vào từ dưới

#### Hiệu ứng Pop (Yêu cầu widget hiện tại)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - Trang hiện tại thoát sang phải, trang mới giữ nguyên
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - Trang hiện tại thoát sang trái, trang mới giữ nguyên
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - Trang hiện tại thoát xuống, trang mới giữ nguyên
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - Trang hiện tại thoát lên, trang mới giữ nguyên

#### Hiệu ứng Material Design Shared Axis
- **`TransitionType.sharedAxisHorizontal()`** - Hiệu ứng trượt ngang và mờ dần
- **`TransitionType.sharedAxisVertical()`** - Hiệu ứng trượt dọc và mờ dần
- **`TransitionType.sharedAxisScale()`** - Hiệu ứng phóng to và mờ dần

#### Tham số tùy chỉnh
Mỗi hiệu ứng chấp nhận các tham số tùy chọn sau:

| Tham số | Mô tả | Mặc định |
|-----------|-------------|---------|
| `curve` | Đường cong hoạt ảnh | Đường cong theo nền tảng |
| `duration` | Thời lượng hoạt ảnh | Thời lượng theo nền tảng |
| `reverseDuration` | Thời lượng hoạt ảnh đảo | Giống duration |
| `fullscreenDialog` | Route có phải dialog toàn màn hình không | `false` |
| `opaque` | Route có bị mờ không | `false` |


``` dart
// Widget trang Home
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(ProfilePage.path,
      transitionType: TransitionType.bottomToTop()
    );
  }
...
```


<div id="navigation-types"></div>

## Các kiểu điều hướng

Khi điều hướng, bạn có thể chỉ định một trong các kiểu sau nếu sử dụng helper `routeTo`.

| Kiểu | Mô tả |
|------|-------------|
| `NavigationType.push` | Đẩy trang mới vào ngăn xếp route |
| `NavigationType.pushReplace` | Thay thế route hiện tại, hủy route trước khi route mới hoàn thành |
| `NavigationType.popAndPushNamed` | Đóng route hiện tại và đẩy route có tên thay thế |
| `NavigationType.pushAndRemoveUntil` | Đẩy và xóa routes cho đến khi predicate trả về true |
| `NavigationType.pushAndForgetAll` | Đẩy đến trang mới và hủy tất cả trang khác trong ngăn xếp |

``` dart
// Widget trang Home
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

## Điều hướng quay lại

Khi ở trang mới, bạn có thể sử dụng helper `pop()` để quay lại trang trước.

``` dart
// Widget SettingsPage
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop();
    // hoặc
    Navigator.pop(context);
  }
...
```

Nếu bạn muốn trả về giá trị cho widget trước, cung cấp `result` như ví dụ dưới đây.

``` dart
// Widget SettingsPage
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// Lấy giá trị từ widget trước bằng tham số `onPop`
// Widget HomePage
class _HomePageState extends NyPage<HomePage> {

  _viewSettings() {
    routeTo(SettingsPage.path, onPop: (value) {
      print(value); // {"status": "COMPLETE"}
    });
  }
...

```

<div id="conditional-navigation"></div>

## Điều hướng có điều kiện

Sử dụng `routeIf()` để chỉ điều hướng khi điều kiện được đáp ứng:

``` dart
// Chỉ điều hướng nếu người dùng đã đăng nhập
routeIf(isLoggedIn, DashboardPage.path);

// Với tùy chọn bổ sung
routeIf(
  hasPermission('view_reports'),
  ReportsPage.path,
  data: {'filters': defaultFilters},
  navigationType: NavigationType.push,
);
```

Nếu điều kiện là `false`, không có điều hướng xảy ra.


<div id="route-history"></div>

## Lịch sử Route

Trong {{ config('app.name') }}, bạn có thể truy cập thông tin lịch sử route bằng các helper dưới đây.

``` dart
// Lấy lịch sử route
Nylo.getRouteHistory(); // List<dynamic>

// Lấy route hiện tại
Nylo.getCurrentRoute(); // Route<dynamic>?

// Lấy route trước
Nylo.getPreviousRoute(); // Route<dynamic>?

// Lấy tên route hiện tại
Nylo.getCurrentRouteName(); // String?

// Lấy tên route trước
Nylo.getPreviousRouteName(); // String?

// Lấy arguments route hiện tại
Nylo.getCurrentRouteArguments(); // dynamic

// Lấy arguments route trước
Nylo.getPreviousRouteArguments(); // dynamic
```


<div id="update-route-stack"></div>

## Cập nhật ngăn xếp Route

Bạn có thể cập nhật ngăn xếp điều hướng theo chương trình bằng `NyNavigator.updateStack()`:

``` dart
// Cập nhật ngăn xếp với danh sách routes
NyNavigator.updateStack([
  HomePage.path,
  SettingsPage.path,
  ProfilePage.path,
], replace: true);

// Truyền dữ liệu cho routes cụ thể
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

| Tham số | Kiểu | Mặc định | Mô tả |
|-----------|------|---------|-------------|
| `routes` | `List<String>` | bắt buộc | Danh sách đường dẫn route để điều hướng |
| `replace` | `bool` | `true` | Có thay thế ngăn xếp hiện tại không |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | Dữ liệu truyền cho routes cụ thể |

Điều này hữu ích cho:
- Các tình huống deep linking
- Khôi phục trạng thái điều hướng
- Xây dựng luồng điều hướng phức tạp


<div id="deep-linking"></div>

## Deep Linking

Deep linking cho phép người dùng điều hướng trực tiếp đến nội dung cụ thể trong ứng dụng bằng URL. Điều này hữu ích cho:
- Chia sẻ liên kết trực tiếp đến nội dung ứng dụng cụ thể
- Chiến dịch marketing nhắm đến tính năng cụ thể trong ứng dụng
- Xử lý thông báo mở màn hình ứng dụng cụ thể
- Chuyển đổi liền mạch từ web sang ứng dụng

## Thiết lập

Trước khi triển khai deep linking, đảm bảo dự án được cấu hình đúng:

### 1. Cấu hình nền tảng

**iOS**: Cấu hình universal links trong dự án Xcode
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Hướng dẫn cấu hình Universal Links Flutter</a>

**Android**: Thiết lập app links trong AndroidManifest.xml
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Hướng dẫn cấu hình App Links Flutter</a>

### 2. Định nghĩa Routes

Tất cả routes có thể truy cập qua deep links phải được đăng ký trong cấu hình router:

```dart
// Tệp: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  // Routes cơ bản
  router.add(HomePage.path).initialRoute();
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);

  // Route với tham số
  router.add(HotelBookingPage.path);
});
```

## Sử dụng Deep Links

Sau khi cấu hình, ứng dụng có thể xử lý URL đến ở nhiều định dạng:

### Deep Links cơ bản

Điều hướng đơn giản đến các trang cụ thể:

``` bash
https://yourdomain.com/profile       // Mở trang profile
https://yourdomain.com/settings      // Mở trang settings
```

Để kích hoạt điều hướng theo chương trình trong ứng dụng:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### Tham số đường dẫn

Cho các routes yêu cầu dữ liệu động trong đường dẫn:

#### Định nghĩa Route

```dart
class HotelBookingPage extends NyStatefulWidget {
  // Định nghĩa route với placeholder tham số {id}
  static RouteView path = ("/hotel/{id}/booking", (_) => HotelBookingPage());

  HotelBookingPage({super.key}) : super(child: () => _HotelBookingPageState());
}

class _HotelBookingPageState extends NyPage<HotelBookingPage> {
  @override
  get init => () {
    // Truy cập tham số đường dẫn
    final hotelId = queryParameters()["id"]; // Trả về "87" cho URL ../hotel/87/booking
    print("Loading hotel ID: $hotelId");

    // Sử dụng ID để lấy dữ liệu khách sạn hoặc thực hiện thao tác
  };

  // Phần còn lại của triển khai trang
}
```

#### Định dạng URL

``` bash
https://yourdomain.com/hotel/87/booking
```

#### Điều hướng theo chương trình

```dart
// Điều hướng với tham số
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### Query Parameters

Cho tham số tùy chọn hoặc khi cần nhiều giá trị động:

#### Định dạng URL

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### Truy cập Query Parameters

```dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  get init => () {
    // Lấy tất cả query parameters
    final params = queryParameters();

    // Truy cập tham số cụ thể
    final userId = params["user"];            // "20"
    final activeTab = params["tab"];          // "posts"

    // Phương pháp truy cập thay thế
    final params2 = widget.queryParameters();
    print(params2);                           // {"user": "20", "tab": "posts"}
  };
}
```

#### Điều hướng theo chương trình với Query Parameters

```dart
// Điều hướng với query parameters
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Kết hợp tham số đường dẫn và query
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## Xử lý Deep Links

Bạn có thể xử lý sự kiện deep link trong `RouteProvider`:

```dart
class RouteProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.addRouter(appRouter());

    // Xử lý deep links
    nylo.onDeepLink(_onDeepLink);
    return nylo;
  }

  _onDeepLink(String route, Map<String, String>? data) {
    print("Deep link route: $route");
    print("Deep link data: $data");

    // Cập nhật ngăn xếp route cho deep links
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

### Kiểm thử Deep Links

Để phát triển và kiểm thử, bạn có thể mô phỏng kích hoạt deep link bằng ADB (Android) hoặc xcrun (iOS):

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### Mẹo gỡ lỗi

- In tất cả tham số trong phương thức init để xác minh phân tích đúng
- Kiểm thử các định dạng URL khác nhau để đảm bảo ứng dụng xử lý đúng
- Nhớ rằng query parameters luôn nhận dạng chuỗi, chuyển đổi sang kiểu phù hợp khi cần

---

## Các mẫu phổ biến

### Chuyển đổi kiểu tham số

Vì tất cả tham số URL được truyền dạng chuỗi, bạn thường cần chuyển đổi:

```dart
// Chuyển đổi tham số chuỗi sang kiểu phù hợp
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### Tham số tùy chọn

Xử lý trường hợp tham số có thể thiếu:

```dart
final userId = queryParameters()["user"];
if (userId != null) {
  // Tải hồ sơ người dùng cụ thể
} else {
  // Tải hồ sơ người dùng hiện tại
}

// Hoặc kiểm tra hasQueryParameter
if (hasQueryParameter('status')) {
  // Thực hiện thao tác với tham số status
} else {
  // Xử lý khi thiếu tham số
}
```


<div id="advanced"></div>

## Nâng cao

### Kiểm tra Route có tồn tại không

Bạn có thể kiểm tra xem route có được đăng ký trong router không:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### Các phương thức NyRouter

Class `NyRouter` cung cấp nhiều phương thức hữu ích:

| Phương thức | Mô tả |
|--------|-------------|
| `getRegisteredRouteNames()` | Lấy tất cả tên route đã đăng ký dạng danh sách |
| `getRegisteredRoutes()` | Lấy tất cả routes đã đăng ký dạng map |
| `containsRoutes(routes)` | Kiểm tra router có chứa tất cả routes chỉ định không |
| `getInitialRouteName()` | Lấy tên route khởi tạo |
| `getAuthRouteName()` | Lấy tên route đã xác thực |
| `getUnknownRouteName()` | Lấy tên route không xác định/404 |

### Lấy Arguments Route

Bạn có thể lấy arguments route bằng `NyRouter.args<T>()`:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    // Lấy arguments có kiểu
    final args = NyRouter.args<NyArgument>(context);
    final userData = args?.data;

    return Scaffold(...);
  }
}
```

### NyArgument và NyQueryParameters

Dữ liệu truyền giữa các routes được bọc trong các class này:

``` dart
// NyArgument chứa dữ liệu route
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters chứa query parameters URL
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
