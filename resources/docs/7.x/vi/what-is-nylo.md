# What is {{ config('app.name') }}?

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- Phát triển ứng dụng
    - [Mới với Flutter?](#new-to-flutter "Mới với Flutter?")
    - [Lịch bảo trì và phát hành](#maintenance-and-release-schedule "Lịch bảo trì và phát hành")
- Ghi công
    - [Các phụ thuộc của Framework](#framework-dependencies "Các phụ thuộc của Framework")
    - [Người đóng góp](#contributors "Người đóng góp")


<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} là một micro-framework cho Flutter được thiết kế để giúp đơn giản hóa việc phát triển ứng dụng. Nó cung cấp một bộ khung có cấu trúc với các thiết lập cần thiết được cấu hình sẵn để bạn có thể tập trung vào việc xây dựng các tính năng của ứng dụng thay vì thiết lập cơ sở hạ tầng.

Ngay từ đầu, {{ config('app.name') }} bao gồm:

- **Routing** - Quản lý route đơn giản, khai báo với guards và deep linking
- **Networking** - Dịch vụ API với Dio, interceptors và chuyển đổi response
- **Quản lý State** - State phản ứng với NyState và cập nhật state toàn cục
- **Bản địa hóa** - Hỗ trợ đa ngôn ngữ với tệp dịch JSON
- **Theme** - Chế độ sáng/tối với chuyển đổi theme
- **Lưu trữ cục bộ** - Lưu trữ an toàn với Backpack và NyStorage
- **Form** - Xử lý form với validation và các loại trường
- **Thông báo đẩy** - Hỗ trợ thông báo cục bộ và từ xa
- **Công cụ CLI (Metro)** - Tạo trang, controller, model và nhiều hơn nữa

<div id="new-to-flutter"></div>

## Mới với Flutter?

Nếu bạn mới bắt đầu với Flutter, hãy bắt đầu với các tài nguyên chính thức:

- <a href="https://flutter.dev" target="_BLANK">Tài liệu Flutter</a> - Hướng dẫn toàn diện và tham chiếu API
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Kênh YouTube Flutter</a> - Hướng dẫn và cập nhật
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> - Các công thức thực tế cho các tác vụ phổ biến

Khi bạn đã quen với các kiến thức cơ bản của Flutter, {{ config('app.name') }} sẽ trở nên trực quan vì nó được xây dựng dựa trên các mẫu Flutter tiêu chuẩn.


<div id="maintenance-and-release-schedule"></div>

## Lịch bảo trì và phát hành

{{ config('app.name') }} tuân theo <a href="https://semver.org" target="_BLANK">Semantic Versioning</a>:

- **Bản phát hành chính** (7.x → 8.x) - Mỗi năm một lần cho các thay đổi không tương thích ngược
- **Bản phát hành phụ** (7.0 → 7.1) - Tính năng mới, tương thích ngược
- **Bản vá lỗi** (7.0.0 → 7.0.1) - Sửa lỗi và cải tiến nhỏ

Sửa lỗi và vá bảo mật được xử lý kịp thời qua các repository GitHub.


<div id="framework-dependencies"></div>

## Các phụ thuộc của Framework

{{ config('app.name') }} v7 được xây dựng trên các gói mã nguồn mở sau:

### Phụ thuộc cốt lõi

| Gói | Mục đích |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | HTTP client cho các yêu cầu API |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | Lưu trữ cục bộ an toàn |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | Quốc tế hóa và định dạng |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | Tiện ích mở rộng phản ứng cho streams |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | So sánh giá trị cho các đối tượng |

### UI & Widgets

| Gói | Mục đích |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | Hiệu ứng tải khung xương |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | Thông báo toast |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | Chức năng kéo-để-làm-mới |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | Bố cục lưới xen kẽ |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | Trường chọn ngày |

### Thông báo & Kết nối

| Gói | Mục đích |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | Thông báo đẩy cục bộ |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | Trạng thái kết nối mạng |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | Huy hiệu biểu tượng ứng dụng |

### Tiện ích

| Gói | Mục đích |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | Mở URL và ứng dụng |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | Chuyển đổi kiểu chữ chuỗi |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | Tạo UUID |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | Đường dẫn hệ thống tệp |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | Tạo mặt nạ đầu vào |


<div id="contributors"></div>

## Người đóng góp

Cảm ơn tất cả những người đã đóng góp cho {{ config('app.name') }}! Nếu bạn đã đóng góp, hãy liên hệ qua <a href="mailto:support@nylo.dev">support@nylo.dev</a> để được thêm vào đây.

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (Người sáng lập)
- <a href="https://github.com/Abdulrasheed1729" target="_BLANK">Abdulrasheed1729</a>
- <a href="https://github.com/Rashid-Khabeer" target="_BLANK">Rashid-Khabeer</a>
- <a href="https://github.com/lpdevit" target="_BLANK">lpdevit</a>
- <a href="https://github.com/youssefKadaouiAbbassi" target="_BLANK">youssefKadaouiAbbassi</a>
- <a href="https://github.com/jeremyhalin" target="_BLANK">jeremyhalin</a>
- <a href="https://github.com/abdulawalarif" target="_BLANK">abdulawalarif</a>
- <a href="https://github.com/lepresk" target="_BLANK">lepresk</a>
- <a href="https://github.com/joshua1996" target="_BLANK">joshua1996</a>
- <a href="https://github.com/stensonb" target="_BLANK">stensonb</a>
- <a href="https://github.com/ruwiss" target="_BLANK">ruwiss</a>
- <a href="https://github.com/rytisder" target="_BLANK">rytisder</a>
- <a href="https://github.com/israelins85" target="_BLANK">israelins85</a>
- <a href="https://github.com/voytech-net" target="_BLANK">voytech-net</a>
- <a href="https://github.com/sadobass" target="_BLANK">sadobass</a>
