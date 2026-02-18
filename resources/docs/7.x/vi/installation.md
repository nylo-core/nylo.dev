# Installation

---

<a name="section-1"></a>
- [Cài đặt](#install "Cài đặt")
- [Chạy dự án](#running-the-project "Chạy dự án")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## Cài đặt

### 1. Cài đặt nylo_installer toàn cục

``` bash
dart pub global activate nylo_installer
```

Lệnh này cài đặt công cụ CLI {{ config('app.name') }} trên toàn hệ thống của bạn.

### 2. Tạo dự án mới

``` bash
nylo new my_app
```

Lệnh này clone template {{ config('app.name') }}, cấu hình dự án với tên ứng dụng của bạn, và cài đặt tất cả các phụ thuộc tự động.

### 3. Thiết lập Metro CLI alias

``` bash
cd my_app
nylo init
```

Điều này cấu hình lệnh `metro` cho dự án của bạn, cho phép bạn sử dụng các lệnh Metro CLI mà không cần cú pháp `dart run` đầy đủ.

Sau khi cài đặt, bạn sẽ có một cấu trúc dự án Flutter hoàn chỉnh với:
- Định tuyến và điều hướng được cấu hình sẵn
- Boilerplate dịch vụ API
- Thiết lập theme và bản địa hóa
- Metro CLI để tạo mã

<div id="running-the-project"></div>

## Chạy dự án

Các dự án {{ config('app.name') }} chạy giống như bất kỳ ứng dụng Flutter tiêu chuẩn nào.

### Sử dụng Terminal

``` bash
flutter run
```

### Sử dụng IDE

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Chạy và gỡ lỗi</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Chạy ứng dụng không có breakpoints</a>

Nếu build thành công, ứng dụng sẽ hiển thị màn hình mặc định của {{ config('app.name') }}.


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} bao gồm công cụ CLI có tên **Metro** để tạo các file dự án.

### Chạy Metro

``` bash
metro
```

Lệnh này hiển thị menu Metro:

``` bash
Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

Usage:
    command [options] [arguments]

Options
    -h

All commands:

[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Tham chiếu lệnh Metro

| Lệnh | Mô tả |
|-------|-------|
| `make:page` | Tạo một trang mới |
| `make:stateful_widget` | Tạo một stateful widget |
| `make:stateless_widget` | Tạo một stateless widget |
| `make:state_managed_widget` | Tạo một widget quản lý trạng thái |
| `make:navigation_hub` | Tạo một navigation hub (thanh điều hướng dưới) |
| `make:journey_widget` | Tạo một journey widget cho navigation hub |
| `make:bottom_sheet_modal` | Tạo một bottom sheet modal |
| `make:button` | Tạo một widget nút tùy chỉnh |
| `make:form` | Tạo một form có validation |
| `make:model` | Tạo một lớp model |
| `make:provider` | Tạo một provider |
| `make:api_service` | Tạo một dịch vụ API |
| `make:controller` | Tạo một controller |
| `make:event` | Tạo một event |
| `make:theme` | Tạo một theme |
| `make:route_guard` | Tạo một route guard |
| `make:config` | Tạo một file cấu hình |
| `make:interceptor` | Tạo một network interceptor |
| `make:command` | Tạo một lệnh Metro tùy chỉnh |
| `make:env` | Tạo cấu hình môi trường từ .env |

### Ví dụ sử dụng

``` bash
# Tạo một trang mới
metro make:page settings_page

# Tạo một model
metro make:model User

# Tạo một dịch vụ API
metro make:api_service user_api_service
```
