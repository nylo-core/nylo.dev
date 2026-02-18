# Requirements

---

<a name="section-1"></a>
- [Yêu cầu hệ thống](#system-requirements "Yêu cầu hệ thống")
- [Cài đặt Flutter](#installing-flutter "Cài đặt Flutter")
- [Xác minh cài đặt](#verifying-installation "Xác minh cài đặt")
- [Thiết lập trình soạn thảo](#set-up-an-editor "Thiết lập trình soạn thảo")


<div id="system-requirements"></div>

## Yêu cầu hệ thống

{{ config('app.name') }} v7 yêu cầu các phiên bản tối thiểu sau:

| Yêu cầu | Phiên bản tối thiểu |
|-------------|-----------------|
| **Flutter** | 3.24.0 trở lên |
| **Dart SDK** | 3.10.7 trở lên |

### Hỗ trợ nền tảng

{{ config('app.name') }} hỗ trợ tất cả các nền tảng mà Flutter hỗ trợ:

| Nền tảng | Hỗ trợ |
|----------|---------|
| iOS | ✓ Hỗ trợ đầy đủ |
| Android | ✓ Hỗ trợ đầy đủ |
| Web | ✓ Hỗ trợ đầy đủ |
| macOS | ✓ Hỗ trợ đầy đủ |
| Windows | ✓ Hỗ trợ đầy đủ |
| Linux | ✓ Hỗ trợ đầy đủ |

<div id="installing-flutter"></div>

## Cài đặt Flutter

Nếu bạn chưa cài đặt Flutter, hãy làm theo hướng dẫn cài đặt chính thức cho hệ điều hành của bạn:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Hướng dẫn cài đặt Flutter</a>

<div id="verifying-installation"></div>

## Xác minh cài đặt

Sau khi cài đặt Flutter, hãy xác minh thiết lập của bạn:

### Kiểm tra phiên bản Flutter

``` bash
flutter --version
```

Bạn sẽ thấy đầu ra tương tự như:

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### Cập nhật Flutter (nếu cần)

Nếu phiên bản Flutter của bạn thấp hơn 3.24.0, hãy nâng cấp lên bản phát hành ổn định mới nhất:

``` bash
flutter channel stable
flutter upgrade
```

### Chạy Flutter Doctor

Xác minh môi trường phát triển của bạn đã được cấu hình đúng:

``` bash
flutter doctor -v
```

Lệnh này kiểm tra:
- Cài đặt Flutter SDK
- Bộ công cụ Android (cho phát triển Android)
- Xcode (cho phát triển iOS/macOS)
- Các thiết bị đã kết nối
- Plugin IDE

Hãy sửa mọi vấn đề được báo cáo trước khi tiến hành cài đặt {{ config('app.name') }}.

<div id="set-up-an-editor"></div>

## Thiết lập trình soạn thảo

Chọn một IDE có hỗ trợ Flutter:

### Visual Studio Code (Khuyến nghị)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> nhẹ và có hỗ trợ Flutter tuyệt vời.

1. Cài đặt <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>
2. Cài đặt <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">Extension Flutter</a>
3. Cài đặt <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">Extension Dart</a>

Hướng dẫn thiết lập: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">Thiết lập Flutter cho VS Code</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> cung cấp IDE đầy đủ tính năng với hỗ trợ trình giả lập tích hợp.

1. Cài đặt <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>
2. Cài đặt plugin Flutter (Preferences → Plugins → Flutter)
3. Cài đặt plugin Dart

Hướng dẫn thiết lập: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Thiết lập Flutter cho Android Studio</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community hoặc Ultimate) cũng hỗ trợ phát triển Flutter.

1. Cài đặt IntelliJ IDEA
2. Cài đặt plugin Flutter (Preferences → Plugins → Flutter)
3. Cài đặt plugin Dart

Khi trình soạn thảo của bạn đã được cấu hình, bạn đã sẵn sàng để [cài đặt {{ config('app.name') }}](/docs/7.x/installation).
