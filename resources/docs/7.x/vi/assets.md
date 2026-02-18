# Assets

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về tài nguyên")
- File
  - [Hiển thị hình ảnh](#displaying-images "Hiển thị hình ảnh")
  - [Đường dẫn tài nguyên tùy chỉnh](#custom-asset-paths "Đường dẫn tài nguyên tùy chỉnh")
  - [Trả về đường dẫn tài nguyên](#returning-asset-paths "Trả về đường dẫn tài nguyên")
- Quản lý tài nguyên
  - [Thêm file mới](#adding-new-files "Thêm file mới")
  - [Cấu hình tài nguyên](#asset-configuration "Cấu hình tài nguyên")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} v7 cung cấp các phương thức trợ giúp để quản lý tài nguyên trong ứng dụng Flutter của bạn. Tài nguyên được lưu trữ trong thư mục `assets/` và bao gồm hình ảnh, video, font chữ và các file khác.

Cấu trúc tài nguyên mặc định:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## Hiển thị hình ảnh

Sử dụng widget `LocalAsset()` để hiển thị hình ảnh từ tài nguyên:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

Cả hai phương thức đều trả về đường dẫn tài nguyên đầy đủ bao gồm thư mục tài nguyên đã cấu hình.

<div id="custom-asset-paths"></div>

## Đường dẫn tài nguyên tùy chỉnh

Để hỗ trợ các thư mục con tài nguyên khác nhau, bạn có thể thêm các constructor tùy chỉnh vào widget `LocalAsset`.

``` dart
// /resources/widgets/local_asset_widget.dart
class LocalAsset extends StatelessWidget {
  // images
  const LocalAsset.image(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/$assetName";

  // icons <- new constructor for icons folder
  const LocalAsset.icons(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/icons/$assetName";
}

// Usage examples
LocalAsset.icons("icon_name.png", width: 32, height: 32)
LocalAsset.image("logo.png", width: 100, height: 100)
```

<div id="returning-asset-paths"></div>

## Trả về đường dẫn tài nguyên

Sử dụng `getAsset()` cho bất kỳ loại file nào trong thư mục `assets/`:

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### Sử dụng với các Widget khác nhau

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## Thêm file mới

1. Đặt file của bạn vào thư mục con thích hợp của `assets/`:
   - Hình ảnh: `assets/images/`
   - Video: `assets/videos/`
   - Font chữ: `assets/fonts/`
   - Khác: `assets/data/` hoặc thư mục tùy chỉnh

2. Đảm bảo thư mục được liệt kê trong `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## Cấu hình tài nguyên

{{ config('app.name') }} v7 cấu hình đường dẫn tài nguyên thông qua biến môi trường `ASSET_PATH` trong file `.env` của bạn:

``` bash
ASSET_PATH="assets"
```

Các hàm trợ giúp tự động thêm đường dẫn này vào trước, nên bạn không cần thêm `assets/` trong các lời gọi:

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### Thay đổi đường dẫn gốc

Nếu bạn cần cấu trúc tài nguyên khác, cập nhật `ASSET_PATH` trong file `.env`:

``` bash
# Use a different base folder
ASSET_PATH="res"
```

Sau khi thay đổi, tạo lại cấu hình môi trường:

``` bash
metro make:env --force
```
