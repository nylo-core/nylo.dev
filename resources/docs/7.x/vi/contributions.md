# Contributing to {{ config('app.name') }}

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về đóng góp")
- [Bắt đầu](#getting-started "Bắt đầu đóng góp")
- [Môi trường phát triển](#development-environment "Thiết lập môi trường phát triển")
- [Hướng dẫn phát triển](#development-guidelines "Hướng dẫn phát triển")
- [Gửi thay đổi](#submitting-changes "Cách gửi thay đổi")
- [Báo cáo lỗi](#reporting-issues "Cách báo cáo lỗi")


<div id="introduction"></div>

## Giới thiệu

Cảm ơn bạn đã cân nhắc đóng góp cho {{ config('app.name') }}!

Hướng dẫn này sẽ giúp bạn hiểu cách đóng góp cho micro-framework. Cho dù bạn sửa lỗi, thêm tính năng hay cải thiện tài liệu, đóng góp của bạn đều có giá trị với cộng đồng {{ config('app.name') }}.

{{ config('app.name') }} được chia thành ba repository:

| Repository | Mục đích |
|------------|---------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | Ứng dụng boilerplate |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | Các class framework cốt lõi (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | Thư viện hỗ trợ với widget, helper, tiện ích (nylo_support) |

<div id="getting-started"></div>

## Bắt đầu

### Fork các repository

Fork các repository bạn muốn đóng góp:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Fork Nylo Boilerplate</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Fork Framework</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Fork Support</a>

### Clone các fork của bạn

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## Môi trường phát triển

### Yêu cầu

Đảm bảo bạn đã cài đặt những phần sau:

| Yêu cầu | Phiên bản tối thiểu |
|-------------|-----------------|
| Flutter | 3.24.0 trở lên |
| Dart SDK | 3.10.7 trở lên |

### Liên kết package cục bộ

Mở Nylo boilerplate trong trình soạn thảo và thêm dependency overrides để sử dụng các repository framework và support cục bộ:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Path to your local framework repository
  nylo_support:
    path: ../support    # Path to your local support repository
```

Chạy `flutter pub get` để cài đặt dependencies.

Bây giờ các thay đổi bạn thực hiện trên repository framework hoặc support sẽ được phản ánh trong Nylo boilerplate.

### Kiểm tra thay đổi

Chạy ứng dụng boilerplate để kiểm tra thay đổi:

``` bash
flutter run
```

Đối với thay đổi widget hoặc helper, hãy cân nhắc thêm test trong repository thích hợp.

<div id="development-guidelines"></div>

## Hướng dẫn phát triển

### Phong cách mã nguồn

- Tuân theo <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">hướng dẫn phong cách Dart</a> chính thức
- Sử dụng tên biến và hàm có ý nghĩa
- Viết chú thích rõ ràng cho logic phức tạp
- Bao gồm tài liệu cho các API công khai
- Giữ mã nguồn modular và dễ bảo trì

### Tài liệu

Khi thêm tính năng mới:

- Thêm chú thích dartdoc cho các class và phương thức công khai
- Cập nhật các file tài liệu liên quan nếu cần
- Bao gồm ví dụ mã trong tài liệu

### Kiểm thử

Trước khi gửi thay đổi:

- Kiểm tra trên cả thiết bị/trình giả lập iOS và Android
- Xác minh tương thích ngược khi có thể
- Ghi chú rõ ràng bất kỳ thay đổi phá vỡ nào
- Chạy các test hiện có để đảm bảo không có gì bị hỏng

<div id="submitting-changes"></div>

## Gửi thay đổi

### Thảo luận trước

Đối với tính năng mới, tốt nhất nên thảo luận với cộng đồng trước:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub Discussions</a>

### Tạo branch

``` bash
git checkout -b feature/your-feature-name
```

Sử dụng tên branch mô tả:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### Commit thay đổi

``` bash
git add .
git commit -m "Add: Your feature description"
```

Sử dụng thông báo commit rõ ràng:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### Push và tạo Pull Request

``` bash
git push origin feature/your-feature-name
```

Sau đó tạo pull request trên GitHub.

### Hướng dẫn Pull Request

- Cung cấp mô tả rõ ràng về thay đổi của bạn
- Tham chiếu các issue liên quan
- Bao gồm ảnh chụp màn hình hoặc ví dụ mã nếu có
- Đảm bảo PR của bạn chỉ giải quyết một vấn đề
- Giữ thay đổi tập trung và nguyên tử

<div id="reporting-issues"></div>

## Báo cáo lỗi

### Trước khi báo cáo

1. Kiểm tra xem issue đã tồn tại trên GitHub chưa
2. Đảm bảo bạn đang sử dụng phiên bản mới nhất
3. Thử tái tạo lỗi trong dự án mới

### Nơi báo cáo

Báo cáo issue trên repository thích hợp:

- **Lỗi boilerplate**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **Lỗi framework**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **Lỗi thư viện hỗ trợ**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### Mẫu issue

Cung cấp thông tin chi tiết:

``` markdown
### Description
Brief description of the issue

### Steps to Reproduce
1. Step one
2. Step two
3. Step three

### Expected Behavior
What should happen

### Actual Behavior
What actually happens

### Environment
- Flutter: 3.24.x
- Dart SDK: 3.10.x
- nylo_framework: ^7.0.0
- OS: macOS/Windows/Linux
- Device: iPhone 15/Pixel 8 (if applicable)

### Code Example
```dart
// Minimal code to reproduce the issue
```
```

### Lấy thông tin phiên bản

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
