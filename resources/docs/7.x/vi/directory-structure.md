# Directory Structure

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về cấu trúc thư mục")
- [Thư mục gốc](#root-directory "Thư mục gốc")
- [Thư mục lib](#lib-directory "Thư mục lib")
  - [app](#app-directory "Thư mục app")
  - [bootstrap](#bootstrap-directory "Thư mục bootstrap")
  - [config](#config-directory "Thư mục config")
  - [resources](#resources-directory "Thư mục resources")
  - [routes](#routes-directory "Thư mục routes")
- [Thư mục tài nguyên](#assets-directory "Thư mục tài nguyên")
- [Helper tài nguyên](#asset-helpers "Helper tài nguyên")


<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} sử dụng cấu trúc thư mục gọn gàng, có tổ chức lấy cảm hứng từ <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a>. Cấu trúc này giúp duy trì tính nhất quán giữa các dự án và giúp dễ dàng tìm file.

<div id="root-directory"></div>

## Thư mục gốc

```
nylo_app/
├── android/          # Android platform files
├── assets/           # Images, fonts, and other assets
├── ios/              # iOS platform files
├── lang/             # Language/translation JSON files
├── lib/              # Dart application code
├── test/             # Test files
├── .env              # Environment variables
├── pubspec.yaml      # Dependencies and project config
└── ...
```

<div id="lib-directory"></div>

## Thư mục lib

Thư mục `lib/` chứa tất cả mã Dart của ứng dụng:

```
lib/
├── app/              # Application logic
├── bootstrap/        # Boot configuration
├── config/           # Configuration files
├── resources/        # UI components
├── routes/           # Route definitions
└── main.dart         # Application entry point
```

<div id="app-directory"></div>

### app/

Thư mục `app/` chứa logic cốt lõi của ứng dụng:

| Thư mục | Mục đích |
|-----------|---------|
| `commands/` | Các lệnh Metro CLI tùy chỉnh |
| `controllers/` | Controller trang cho logic nghiệp vụ |
| `events/` | Các class sự kiện cho hệ thống sự kiện |
| `forms/` | Các class biểu mẫu với xác thực |
| `models/` | Các class model dữ liệu |
| `networking/` | Dịch vụ API và cấu hình mạng |
| `networking/dio/interceptors/` | Interceptor HTTP Dio |
| `providers/` | Service provider được khởi chạy khi app bắt đầu |
| `services/` | Các class service tổng quát |

<div id="bootstrap-directory"></div>

### bootstrap/

Thư mục `bootstrap/` chứa các file cấu hình cách ứng dụng khởi động:

| File | Mục đích |
|------|---------|
| `boot.dart` | Cấu hình trình tự khởi động chính |
| `decoders.dart` | Đăng ký model và API decoders |
| `env.g.dart` | Cấu hình môi trường đã mã hóa được tạo tự động |
| `events.dart` | Đăng ký sự kiện |
| `extensions.dart` | Extension tùy chỉnh |
| `helpers.dart` | Hàm helper tùy chỉnh |
| `providers.dart` | Đăng ký provider |
| `theme.dart` | Cấu hình theme |

<div id="config-directory"></div>

### config/

Thư mục `config/` chứa cấu hình ứng dụng:

| File | Mục đích |
|------|---------|
| `app.dart` | Cài đặt ứng dụng cốt lõi |
| `design.dart` | Thiết kế ứng dụng (font, logo, loader) |
| `localization.dart` | Cài đặt ngôn ngữ và locale |
| `storage_keys.dart` | Định nghĩa khóa lưu trữ cục bộ |
| `toast_notification.dart` | Kiểu thông báo toast |

<div id="resources-directory"></div>

### resources/

Thư mục `resources/` chứa các thành phần UI:

| Thư mục | Mục đích |
|-----------|---------|
| `pages/` | Widget trang (màn hình) |
| `themes/` | Định nghĩa theme |
| `themes/light/` | Màu theme sáng |
| `themes/dark/` | Màu theme tối |
| `widgets/` | Thành phần widget tái sử dụng |
| `widgets/buttons/` | Widget nút tùy chỉnh |
| `widgets/bottom_sheet_modals/` | Widget bottom sheet modal |

<div id="routes-directory"></div>

### routes/

Thư mục `routes/` chứa cấu hình routing:

| File/Thư mục | Mục đích |
|----------------|---------|
| `router.dart` | Định nghĩa route |
| `guards/` | Các class route guard |

<div id="assets-directory"></div>

## Thư mục tài nguyên

Thư mục `assets/` lưu trữ các file tĩnh:

```
assets/
├── app_icon/         # App icon source
├── fonts/            # Custom fonts
└── images/           # Image assets
```

### Đăng ký tài nguyên

Tài nguyên được đăng ký trong `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## Helper tài nguyên

{{ config('app.name') }} cung cấp các helper để làm việc với tài nguyên.

### Tài nguyên hình ảnh

``` dart
// Standard Flutter way
Image.asset(
  'assets/images/logo.png',
  height: 50,
  width: 50,
)

// Using LocalAsset widget
LocalAsset.image(
  "logo.png",
  height: 50,
  width: 50,
)
```

### Tài nguyên tổng quát

``` dart
// Get any asset path
String fontPath = getAsset('fonts/custom.ttf');

// Video example
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### File ngôn ngữ

File ngôn ngữ được lưu trữ trong `lang/` tại thư mục gốc dự án:

```
lang/
├── en.json           # English
├── es.json           # Spanish
├── fr.json           # French
└── ...
```

Xem [Bản địa hóa](/docs/7.x/localization) để biết thêm chi tiết.
