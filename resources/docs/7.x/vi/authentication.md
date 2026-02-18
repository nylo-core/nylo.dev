# Authentication

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về xác thực")
- Cơ bản
  - [Xác thực người dùng](#authenticating-users "Xác thực người dùng")
  - [Lấy dữ liệu xác thực](#retrieving-auth-data "Lấy dữ liệu xác thực")
  - [Cập nhật dữ liệu xác thực](#updating-auth-data "Cập nhật dữ liệu xác thực")
  - [Đăng xuất](#logging-out "Đăng xuất")
  - [Kiểm tra xác thực](#checking-authentication "Kiểm tra xác thực")
- Nâng cao
  - [Nhiều phiên](#multiple-sessions "Nhiều phiên")
  - [Device ID](#device-id "Device ID")
  - [Đồng bộ với Backpack](#syncing-to-backpack "Đồng bộ với Backpack")
- Cấu hình route
  - [Route khởi tạo](#initial-route "Route khởi tạo")
  - [Route đã xác thực](#authenticated-route "Route đã xác thực")
  - [Route xem trước](#preview-route "Route xem trước")
  - [Route không xác định](#unknown-route "Route không xác định")
- [Hàm trợ giúp](#helper-functions "Hàm trợ giúp")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} v7 cung cấp hệ thống xác thực toàn diện thông qua class `Auth`. Nó xử lý lưu trữ an toàn thông tin đăng nhập của người dùng, quản lý phiên và hỗ trợ nhiều phiên được đặt tên cho các ngữ cảnh xác thực khác nhau.

Dữ liệu xác thực được lưu trữ an toàn và đồng bộ với Backpack (kho lưu trữ key-value trong bộ nhớ) để truy cập đồng bộ nhanh chóng trong toàn bộ ứng dụng của bạn.

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

## Xác thực người dùng

Sử dụng `Auth.authenticate()` để lưu trữ dữ liệu phiên người dùng:

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

### Ví dụ thực tế

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

## Lấy dữ liệu xác thực

Lấy dữ liệu xác thực đã lưu bằng `Auth.data()`:

``` dart
// Get all auth data
dynamic userData = Auth.data();
print(userData); // {"token": "ey2sdm...", "userId": 123}

// Get a specific field
String? token = Auth.data(field: 'token');
int? userId = Auth.data(field: 'userId');
```

Phương thức `Auth.data()` đọc từ Backpack (kho lưu trữ key-value trong bộ nhớ của {{ config('app.name') }}) để truy cập đồng bộ nhanh chóng. Dữ liệu được tự động đồng bộ với Backpack khi bạn xác thực.


<div id="updating-auth-data"></div>

## Cập nhật dữ liệu xác thực

{{ config('app.name') }} v7 giới thiệu `Auth.set()` để cập nhật dữ liệu xác thực:

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

## Đăng xuất

Xóa người dùng đã xác thực bằng `Auth.logout()`:

``` dart
Future<void> handleLogout() async {
  await Auth.logout();

  // Navigate to login page
  routeTo(LoginPage.path, removeUntil: (_) => false);
}
```

### Đăng xuất khỏi tất cả phiên

Khi sử dụng nhiều phiên, xóa tất cả:

``` dart
// Logout from default and all named sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```


<div id="checking-authentication"></div>

## Kiểm tra xác thực

Kiểm tra xem người dùng hiện đang được xác thực hay không:

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

## Nhiều phiên

{{ config('app.name') }} v7 hỗ trợ nhiều phiên xác thực được đặt tên cho các ngữ cảnh khác nhau. Điều này hữu ích khi bạn cần theo dõi các loại xác thực khác nhau một cách riêng biệt (ví dụ: đăng nhập người dùng vs đăng ký thiết bị vs truy cập quản trị).

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

### Đọc từ phiên được đặt tên

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

### Đăng xuất theo phiên cụ thể

``` dart
// Logout from default session only
await Auth.logout();

// Logout from device session only
await Auth.logout(session: 'device');

// Logout from all sessions
await Auth.logoutAll(sessions: ['device', 'admin']);
```

### Kiểm tra xác thực theo phiên

``` dart
bool userLoggedIn = await Auth.isAuthenticated();
bool deviceRegistered = await Auth.isAuthenticated(session: 'device');
bool isAdmin = await Auth.isAuthenticated(session: 'admin');
```


<div id="device-id"></div>

## Device ID

{{ config('app.name') }} v7 cung cấp mã định danh thiết bị duy nhất tồn tại qua các phiên ứng dụng:

``` dart
String deviceId = await Auth.deviceId();
// Example: "550e8400-e29b-41d4-a716-446655440000-1704067200000"
```

Device ID:
- Được tạo một lần và lưu trữ vĩnh viễn
- Duy nhất cho mỗi thiết bị/cài đặt
- Hữu ích cho đăng ký thiết bị, phân tích hoặc thông báo đẩy

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

## Đồng bộ với Backpack

Dữ liệu xác thực được tự động đồng bộ với Backpack khi bạn xác thực. Để đồng bộ thủ công (ví dụ: khi khởi động ứng dụng):

``` dart
// Sync default session
await Auth.syncToBackpack();

// Sync specific session
await Auth.syncToBackpack(session: 'device');

// Sync all sessions
await Auth.syncAllToBackpack(sessions: ['device', 'admin']);
```

Điều này hữu ích trong quá trình khởi động ứng dụng để đảm bảo dữ liệu xác thực có sẵn trong Backpack cho truy cập đồng bộ nhanh chóng.


<div id="initial-route"></div>

## Route khởi tạo

Route khởi tạo là trang đầu tiên người dùng thấy khi mở ứng dụng. Đặt nó bằng `.initialRoute()` trong router:

``` dart
// routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path);
});
```

Bạn cũng có thể đặt route khởi tạo có điều kiện bằng tham số `when`:

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

Điều hướng trở lại route khởi tạo từ bất cứ đâu bằng `routeToInitial()`:

``` dart
void _goHome() {
  routeToInitial();
}
```


<div id="authenticated-route"></div>

## Route đã xác thực

Route đã xác thực ghi đè route khởi tạo khi người dùng đã đăng nhập. Đặt nó bằng `.authenticatedRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(ProfilePage.path);
  router.add(SettingsPage.path);
});
```

Khi ứng dụng khởi động:
- `Auth.isAuthenticated()` trả về `true` → người dùng thấy **route đã xác thực** (HomePage)
- `Auth.isAuthenticated()` trả về `false` → người dùng thấy **route khởi tạo** (LoginPage)

Bạn cũng có thể đặt route đã xác thực có điều kiện:

``` dart
router.add(HomePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

Điều hướng đến route đã xác thực bằng chương trình sử dụng `routeToAuthenticatedRoute()`:

``` dart
// After login
await Auth.authenticate(data: user);
routeToAuthenticatedRoute();
```

**Xem thêm:** [Router](/docs/{{ $version }}/router) để xem tài liệu đầy đủ về routing bao gồm guards và deep linking.


<div id="preview-route"></div>

## Route xem trước

Trong quá trình phát triển, bạn có thể muốn xem trước nhanh một trang cụ thể mà không cần thay đổi route khởi tạo hoặc route đã xác thực. Sử dụng `.previewRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(LoginPage.path).initialRoute();

  router.add(HomePage.path).authenticatedRoute();

  router.add(SettingsPage.path).previewRoute(); // Opens first during development
});
```

`previewRoute()` ghi đè **cả** `initialRoute()` và `authenticatedRoute()`, khiến route được chỉ định trở thành trang đầu tiên hiển thị bất kể trạng thái xác thực.

> **Cảnh báo:** Hãy xóa `.previewRoute()` trước khi phát hành ứng dụng.


<div id="unknown-route"></div>

## Route không xác định

Định nghĩa trang dự phòng khi người dùng điều hướng đến route không tồn tại. Đặt nó bằng `.unknownRoute()`:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

### Kết hợp tất cả

Đây là cấu hình router hoàn chỉnh với tất cả các loại route:

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

| Phương thức route | Mục đích |
|--------------|---------|
| `.initialRoute()` | Trang đầu tiên hiển thị cho người dùng chưa xác thực |
| `.authenticatedRoute()` | Trang đầu tiên hiển thị cho người dùng đã xác thực |
| `.previewRoute()` | Ghi đè cả hai trong quá trình phát triển |
| `.unknownRoute()` | Hiển thị khi không tìm thấy route |


<div id="helper-functions"></div>

## Hàm trợ giúp

{{ config('app.name') }} v7 cung cấp các hàm trợ giúp phản ánh các phương thức của class `Auth`:

| Hàm trợ giúp | Tương đương |
|-----------------|------------|
| `authAuthenticate(data: user)` | `Auth.authenticate(data: user)` |
| `authData()` | `Auth.data()` |
| `authData(field: 'token')` | `Auth.data(field: 'token')` |
| `authSet((data) => {...})` | `Auth.set((data) => {...})` |
| `authIsAuthenticated()` | `Auth.isAuthenticated()` |
| `authLogout()` | `Auth.logout()` |
| `authLogoutAll(sessions: [...])` | `Auth.logoutAll(sessions: [...])` |
| `authSyncToBackpack()` | `Auth.syncToBackpack()` |
| `authKey()` | Khóa lưu trữ cho phiên mặc định |
| `authDeviceId()` | `Auth.deviceId()` |

Tất cả các hàm trợ giúp chấp nhận các tham số giống như các phương thức tương ứng của class `Auth`, bao gồm tham số `session` tùy chọn:

``` dart
// Authenticate with a named session
await authAuthenticate(data: device, session: 'device');

// Read from a named session
dynamic deviceData = authData(session: 'device');

// Check a named session
bool deviceAuth = await authIsAuthenticated(session: 'device');
```
