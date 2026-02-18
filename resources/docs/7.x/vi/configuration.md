# Configuration

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về cấu hình")
- Môi trường
  - [File .env](#env-file "File .env")
  - [Tạo cấu hình môi trường](#generating-env "Tạo cấu hình môi trường")
  - [Lấy giá trị](#retrieving-values "Lấy giá trị môi trường")
  - [Tạo class cấu hình](#creating-config-classes "Tạo class cấu hình")
  - [Kiểu biến](#variable-types "Kiểu biến môi trường")
- [Các phiên bản môi trường](#environment-flavours "Các phiên bản môi trường")
- [Tiêm lúc build](#build-time-injection "Tiêm lúc build")


<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} v7 sử dụng hệ thống cấu hình môi trường an toàn. Các biến môi trường của bạn được lưu trữ trong file `.env` và sau đó được mã hóa thành file Dart được tạo tự động (`env.g.dart`) để sử dụng trong ứng dụng.

Cách tiếp cận này cung cấp:
- **Bảo mật**: Các giá trị môi trường được mã hóa XOR trong ứng dụng đã biên dịch
- **An toàn kiểu dữ liệu**: Các giá trị được tự động phân tích thành kiểu phù hợp
- **Linh hoạt lúc build**: Các cấu hình khác nhau cho phát triển, staging và production

<div id="env-file"></div>

## File .env

File `.env` tại thư mục gốc dự án chứa các biến cấu hình của bạn:

``` bash
# Environment configuration
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### Các biến có sẵn

| Biến | Mô tả |
|----------|-------------|
| `APP_KEY` | **Bắt buộc**. Khóa bí mật 32 ký tự để mã hóa |
| `APP_NAME` | Tên ứng dụng của bạn |
| `APP_ENV` | Môi trường: `developing` hoặc `production` |
| `APP_DEBUG` | Bật chế độ debug (`true`/`false`) |
| `APP_URL` | URL ứng dụng của bạn |
| `API_BASE_URL` | URL gốc cho các yêu cầu API |
| `ASSET_PATH` | Đường dẫn đến thư mục tài nguyên |
| `DEFAULT_LOCALE` | Mã ngôn ngữ mặc định |

<div id="generating-env"></div>

## Tạo cấu hình môi trường

{{ config('app.name') }} v7 yêu cầu bạn tạo file môi trường đã mã hóa trước khi ứng dụng có thể truy cập các giá trị env.

### Bước 1: Tạo APP_KEY

Đầu tiên, tạo APP_KEY an toàn:

``` bash
metro make:key
```

Lệnh này thêm `APP_KEY` 32 ký tự vào file `.env` của bạn.

### Bước 2: Tạo env.g.dart

Tạo file môi trường đã mã hóa:

``` bash
metro make:env
```

Lệnh này tạo `lib/bootstrap/env.g.dart` với các biến môi trường đã mã hóa.

Env của bạn được tự động đăng ký khi ứng dụng khởi động — `Nylo.init(env: Env.get, ...)` trong `main.dart` xử lý điều này cho bạn. Không cần thiết lập thêm.

### Tạo lại sau khi thay đổi

Khi bạn chỉnh sửa file `.env`, tạo lại cấu hình:

``` bash
metro make:env --force
```

Cờ `--force` ghi đè `env.g.dart` hiện có.

<div id="retrieving-values"></div>

## Lấy giá trị

Cách được khuyến nghị để truy cập giá trị môi trường là thông qua **class cấu hình**. File `lib/config/app.dart` sử dụng `getEnv()` để hiển thị các giá trị env dưới dạng trường static có kiểu:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

Sau đó trong mã ứng dụng, truy cập giá trị thông qua class cấu hình:

``` dart
// Anywhere in your app
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

Mẫu này giữ truy cập env tập trung trong các class cấu hình. Helper `getEnv()` nên được sử dụng trong các class cấu hình thay vì trực tiếp trong mã ứng dụng.

<div id="creating-config-classes"></div>

## Tạo class cấu hình

Bạn có thể tạo class cấu hình tùy chỉnh cho dịch vụ bên thứ ba hoặc cấu hình theo tính năng bằng Metro:

``` bash
metro make:config RevenueCat
```

Lệnh này tạo file cấu hình mới tại `lib/config/revenue_cat_config.dart`:

``` dart
final class RevenueCatConfig {
  // Add your config values here
}
```

### Ví dụ: Cấu hình RevenueCat

**Bước 1:** Thêm các biến môi trường vào file `.env`:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**Bước 2:** Cập nhật class cấu hình để tham chiếu các giá trị này:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**Bước 3:** Tạo lại cấu hình môi trường:

``` bash
metro make:env --force
```

**Bước 4:** Sử dụng class cấu hình trong ứng dụng:

``` dart
import '/config/revenue_cat_config.dart';

// Initialize RevenueCat
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// Check entitlements
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // Grant premium access
}
```

Cách tiếp cận này giữ các khóa API và giá trị cấu hình an toàn và tập trung, giúp dễ dàng quản lý các giá trị khác nhau giữa các môi trường.

<div id="variable-types"></div>

## Kiểu biến

Các giá trị trong file `.env` được tự động phân tích:

| Giá trị .env | Kiểu Dart | Ví dụ |
|------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (chuỗi rỗng) |


<div id="environment-flavours"></div>

## Các phiên bản môi trường

Tạo các cấu hình khác nhau cho phát triển, staging và production.

### Bước 1: Tạo các file môi trường

Tạo các file `.env` riêng biệt:

``` bash
.env                  # Development (default)
.env.staging          # Staging
.env.production       # Production
```

Ví dụ `.env.production`:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### Bước 2: Tạo cấu hình môi trường

Tạo từ file env cụ thể:

``` bash
# For production
metro make:env --file=".env.production" --force

# For staging
metro make:env --file=".env.staging" --force
```

### Bước 3: Build ứng dụng

Build với cấu hình phù hợp:

``` bash
# Development
flutter run

# Production build
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## Tiêm lúc build

Để tăng cường bảo mật, bạn có thể tiêm APP_KEY lúc build thay vì nhúng nó trong mã nguồn.

### Tạo với chế độ --dart-define

``` bash
metro make:env --dart-define
```

Lệnh này tạo `env.g.dart` mà không nhúng APP_KEY.

### Build với tiêm APP_KEY

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Run
flutter run --dart-define=APP_KEY=your-secret-key
```

Cách tiếp cận này giữ APP_KEY ngoài mã nguồn, hữu ích cho:
- Pipeline CI/CD nơi secret được tiêm
- Dự án mã nguồn mở
- Yêu cầu bảo mật nâng cao

### Các thực hành tốt nhất

1. **Không bao giờ commit `.env` vào quản lý phiên bản** - Thêm nó vào `.gitignore`
2. **Sử dụng `.env-example`** - Commit template không có giá trị nhạy cảm
3. **Tạo lại sau khi thay đổi** - Luôn chạy `metro make:env --force` sau khi chỉnh sửa `.env`
4. **Khóa khác nhau cho mỗi môi trường** - Sử dụng APP_KEY duy nhất cho phát triển, staging và production
