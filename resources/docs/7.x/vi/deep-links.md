# Deep Links

---

<a name="section-1"></a>

- [Giới thiệu](#introduction "Giới thiệu")
- [Bắt đầu](#getting-started "Bắt đầu")
  - [Bật Deep Links](#enable-deep-links "Bật Deep Links")
  - [Tạo Provider](#generate-a-provider "Tạo Provider")
- [Cấu hình nền tảng](#platform-configuration "Cấu hình nền tảng")
  - [Cấu hình Android](#android-configuration "Cấu hình Android")
  - [Cấu hình iOS](#ios-configuration "Cấu hình iOS")
- [Chặn bắt liên kết](#intercepting-links "Chặn bắt liên kết")
- [Route dự phòng](#fallback-route "Route dự phòng")
- [Kiểm thử](#testing "Kiểm thử")
- [Tài liệu API](#api-reference "Tài liệu API")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} đi kèm hỗ trợ deep link hạng nhất được cung cấp bởi [`app_links`](https://pub.dev/packages/app_links). Các URI đến từ Android App Links, iOS Universal Links, URL scheme tùy chỉnh và URL web được tự động thu nhận và định tuyến qua các route đã đăng ký.

Một dòng duy nhất kích hoạt toàn bộ quy trình:

```dart
nylo.useDeepLinks();
```

Khi một URI đến, {{ config('app.name') }} trích xuất đường dẫn và tham số query rồi gọi `routeTo()` với chúng. Các lần khởi động lạnh (ứng dụng được mở bằng liên kết) được phát lại sau khi navigator sẵn sàng, còn các liên kết khởi động ấm (nhận được khi ứng dụng đang chạy) được xử lý theo cách tương tự.

<div id="getting-started"></div>

## Bắt đầu

<div id="enable-deep-links"></div>

### Bật Deep Links

Bên trong phương thức `setup` của bất kỳ provider nào, gọi `useDeepLinks()`:

```dart
import 'package:nylo_framework/nylo_framework.dart';

class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

Đăng ký provider trong `config/providers.dart` để nó chạy khi ứng dụng khởi động.

<div id="generate-a-provider"></div>

### Tạo Provider

Metro tạo cấu trúc provider cho bạn:

```bash
dart run nylo_framework:main make:deep_link_provider
```

Lệnh này tạo `lib/app/providers/deep_link_provider.dart` với `useDeepLinks()` đã được kết nối sẵn và một ví dụ `onIncomingLink` đã được chú thích. Provider cũng được tự động thêm vào `config/providers.dart`.

<div id="platform-configuration"></div>

## Cấu hình nền tảng

<div id="android-configuration"></div>

### Cấu hình Android

Thêm `<intent-filter>` vào `<activity>` chính trong `android/app/src/main/AndroidManifest.xml`. Ví dụ dưới đây chấp nhận cả Android App Links (`https://example.com/...`) và scheme tùy chỉnh (`myapp://...`):

```xml
<activity
    android:name=".MainActivity"
    android:exported="true"
    android:launchMode="singleTop">

    <!-- Standard Flutter intent-filter -->
    <intent-filter>
        <action android:name="android.intent.action.MAIN" />
        <category android:name="android.intent.category.LAUNCHER" />
    </intent-filter>

    <!-- Android App Links -->
    <intent-filter android:autoVerify="true">
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="https"
              android:host="example.com" />
    </intent-filter>

    <!-- Custom URL scheme -->
    <intent-filter>
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="myapp" />
    </intent-filter>
</activity>
```

Đối với App Links đã xác minh, hãy lưu trữ tệp `assetlinks.json` tại `https://example.com/.well-known/assetlinks.json`. Xem <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">hướng dẫn Flutter App Links</a> để biết chi tiết đầy đủ.

<div id="ios-configuration"></div>

### Cấu hình iOS

Đối với **URL scheme tùy chỉnh**, thêm mục `CFBundleURLTypes` vào `ios/Runner/Info.plist`:

```xml
<key>CFBundleURLTypes</key>
<array>
    <dict>
        <key>CFBundleURLName</key>
        <string>com.example.myapp</string>
        <key>CFBundleURLSchemes</key>
        <array>
            <string>myapp</string>
        </array>
    </dict>
</array>
```

Đối với **Universal Links**, bật khả năng Associated Domains trong Xcode và lưu trữ tệp `apple-app-site-association` trên domain của bạn. Xem <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">hướng dẫn Flutter Universal Links</a> để biết toàn bộ hướng dẫn từng bước.

<div id="intercepting-links"></div>

## Chặn bắt liên kết

Dùng `onIncomingLink` để chạy logic trước khi {{ config('app.name') }} định tuyến URI. Trả về `true` để {{ config('app.name') }} tự động định tuyến, hoặc `false` để tự xử lý URI:

```dart
class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();

    nylo.onIncomingLink((Uri uri) async {
      // Require auth on /account/* routes
      if (uri.path.startsWith('/account') && !(await Auth.isAuthenticated())) {
        routeTo(LoginPage.path);
        return false;
      }

      // Track analytics for every incoming link
      Analytics.track('deep_link_opened', {'path': uri.path});

      return true;
    });

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

Callback nhận đầy đủ `Uri`, bao gồm scheme, host, đường dẫn và tham số query.

<div id="fallback-route"></div>

## Route dự phòng

Truyền `fallbackRoute` vào `useDeepLinks()` để điều hướng đến nơi phù hợp khi đường dẫn URI đến không được đăng ký:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

Khi đường dẫn URI khớp với route đã đăng ký, {{ config('app.name') }} sẽ định tuyến đến đó. Ngược lại, người dùng sẽ đến route dự phòng.

<div id="testing"></div>

## Kiểm thử

Mô phỏng deep links đến từ dòng lệnh:

```bash
# Android — custom scheme
adb shell am start -W -a android.intent.action.VIEW \
    -d "myapp://user/42?ref=test" com.example.myapp

# Android — App Link
adb shell am start -W -a android.intent.action.VIEW \
    -d "https://example.com/user/42?ref=test" com.example.myapp

# iOS Simulator
xcrun simctl openurl booted "myapp://user/42?ref=test"
```

Đóng ứng dụng giữa các lần kiểm thử để xác minh hành vi khởi động lạnh, và kích hoạt URL khi ứng dụng đang ở foreground để xác minh khởi động ấm.

<div id="api-reference"></div>

## Tài liệu API

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

Bật tính năng thu nhận deep link ở cấp nền tảng. Gọi bên trong phương thức `setup` của provider.

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | Route để điều hướng đến khi đường dẫn URI đến không được đăng ký. |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

Đăng ký callback được gọi cho mỗi URI đến. Trả về `true` để {{ config('app.name') }} tự động định tuyến, hoặc `false` để ngăn định tuyến tự động.

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | Nhận URI đến. |
