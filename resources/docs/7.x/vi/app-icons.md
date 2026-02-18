# App Icons

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Tạo biểu tượng ứng dụng](#generating-app-icons "Tạo biểu tượng ứng dụng")
- [Thêm biểu tượng ứng dụng của bạn](#adding-your-app-icon "Thêm biểu tượng ứng dụng của bạn")
- [Yêu cầu biểu tượng ứng dụng](#app-icon-requirements "Yêu cầu biểu tượng ứng dụng")
- [Cấu hình](#configuration "Cấu hình")
- [Số đếm huy hiệu](#badge-count "Số đếm huy hiệu")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} v7 sử dụng <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> để tạo biểu tượng ứng dụng cho iOS và Android từ một hình ảnh nguồn duy nhất.

Biểu tượng ứng dụng của bạn nên được đặt trong thư mục `assets/app_icon/` với kích thước **1024x1024 pixel**.

<div id="generating-app-icons"></div>

## Tạo biểu tượng ứng dụng

Chạy lệnh sau để tạo biểu tượng cho tất cả nền tảng:

``` bash
dart run flutter_launcher_icons
```

Lệnh này đọc biểu tượng nguồn từ `assets/app_icon/` và tạo ra:
- Biểu tượng iOS trong `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
- Biểu tượng Android trong `android/app/src/main/res/mipmap-*/`

<div id="adding-your-app-icon"></div>

## Thêm biểu tượng ứng dụng của bạn

1. Tạo biểu tượng dưới dạng file **PNG 1024x1024**
2. Đặt nó trong `assets/app_icon/` (ví dụ: `assets/app_icon/icon.png`)
3. Cập nhật `image_path` trong `pubspec.yaml` nếu cần:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. Chạy lệnh tạo biểu tượng

<div id="app-icon-requirements"></div>

## Yêu cầu biểu tượng ứng dụng

| Thuộc tính | Giá trị |
|-----------|-------|
| Định dạng | PNG |
| Kích thước | 1024x1024 pixel |
| Lớp | Phẳng, không có độ trong suốt |

### Đặt tên file

Giữ tên file đơn giản, không có ký tự đặc biệt:
- `app_icon.png`
- `icon.png`

### Hướng dẫn theo nền tảng

Để biết yêu cầu chi tiết, tham khảo hướng dẫn chính thức của từng nền tảng:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple Human Interface Guidelines - App Icons</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Icon Design Specifications</a>

<div id="configuration"></div>

## Cấu hình

Tùy chỉnh việc tạo biểu tượng trong `pubspec.yaml`:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"

  # Optional: Use different icons per platform
  # image_path_android: "assets/app_icon/android_icon.png"
  # image_path_ios: "assets/app_icon/ios_icon.png"

  # Optional: Adaptive icons for Android
  # adaptive_icon_background: "#ffffff"
  # adaptive_icon_foreground: "assets/app_icon/foreground.png"

  # Optional: Remove alpha channel for iOS
  # remove_alpha_ios: true
```

Xem <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">tài liệu flutter_launcher_icons</a> để biết tất cả các tùy chọn khả dụng.

<div id="badge-count"></div>

## Số đếm huy hiệu

{{ config('app.name') }} cung cấp các hàm trợ giúp để quản lý số đếm huy hiệu ứng dụng (số hiển thị trên biểu tượng ứng dụng):

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### Hỗ trợ nền tảng

Số đếm huy hiệu được hỗ trợ trên:
- **iOS**: Hỗ trợ gốc
- **Android**: Yêu cầu launcher hỗ trợ (hầu hết các launcher đều hỗ trợ)
- **Web**: Không hỗ trợ

### Trường hợp sử dụng

Các tình huống phổ biến cho số đếm huy hiệu:
- Thông báo chưa đọc
- Tin nhắn chờ xử lý
- Sản phẩm trong giỏ hàng
- Nhiệm vụ chưa hoàn thành

``` dart
// Example: Update badge when new messages arrive
void onNewMessage() async {
  int unreadCount = await MessageService.getUnreadCount();
  await setBadgeNumber(unreadCount);
}

// Example: Clear badge when user views messages
void onMessagesViewed() async {
  await clearBadgeNumber();
}
```
