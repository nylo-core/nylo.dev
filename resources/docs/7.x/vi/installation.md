# Installation

---

<a name="section-1"></a>
- [Cài đặt](#install "Cài đặt")
- [Chạy dự án](#running-the-project "Chạy dự án")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

Ba lệnh sẽ đưa bạn từ một thư mục trống đến ứng dụng Flutter đang chạy, với định tuyến, mạng, theme và tạo mã đã được thiết lập sẵn.

<x-doc-strip label="Trước khi bắt đầu" items="Đã cài Flutter SDK, Dart 3" linkText="Yêu cầu đầy đủ" linkHref="/vi/docs/{{ $version }}/requirements" />

## Cài đặt

Chạy các lệnh này theo thứ tự. Mỗi lệnh đều có thể chạy lại an toàn.

<x-doc-steps>
<x-doc-step number="1" title="Cài đặt Nylo CLI toàn cục">
Lệnh này cài đặt công cụ CLI {{ config('app.name') }} trên toàn hệ thống của bạn.

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="Tạo dự án mới">
Lệnh này clone template {{ config('app.name') }}, cấu hình dự án với tên ứng dụng của bạn, và cài đặt tất cả các phụ thuộc tự động.

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Thiết lập alias Metro">
Điều này cấu hình lệnh `metro` cho dự án của bạn, cho phép bạn sử dụng các lệnh Metro CLI mà không cần cú pháp `dart run` đầy đủ.

``` bash
cd my_app
nylo init
```
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="Những gì bạn nhận được" items="Định tuyến và điều hướng được cấu hình sẵn
Boilerplate dịch vụ API
Thiết lập theme và bản địa hóa
Metro CLI để tạo mã" />


<div id="running-the-project"></div>

## Chạy dự án

Các dự án {{ config('app.name') }} chạy giống như bất kỳ ứng dụng Flutter tiêu chuẩn nào.

<x-doc-tabs tabs="Terminal, Android Studio, VS Code">
<x-doc-tab label="Terminal">

``` bash
flutter run
```

Nếu build thành công, ứng dụng sẽ hiển thị màn hình mặc định của {{ config('app.name') }}.
</x-doc-tab>

<x-doc-tab label="Android Studio">
Mở thư mục dự án, chọn thiết bị từ bộ chọn đích, sau đó nhấn **Run**.

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Tài liệu Flutter: chạy và gỡ lỗi ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
Mở thư mục dự án, sau đó chạy **Debug: Start Without Debugging** từ command palette.

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Tài liệu Flutter: chạy không có breakpoint ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro tạo các file dự án cho bạn. Chạy không có đối số để xem menu hoặc gọi trực tiếp một lệnh.

``` plaintext
$ metro

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
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### Tham chiếu lệnh

Mỗi lệnh nhận một tên, ví dụ `metro make:page settings_page`

<x-doc-commands title="Lệnh widget" rows="
metro make:page | Tạo một trang mới
metro make:stateful_widget | Tạo một stateful widget
metro make:stateless_widget | Tạo một stateless widget
metro make:state_managed_widget | Tạo một widget quản lý trạng thái
metro make:navigation_hub | Tạo một navigation hub (thanh điều hướng dưới)
metro make:journey_widget | Tạo một journey widget cho navigation hub
metro make:bottom_sheet_modal | Tạo một bottom sheet modal
metro make:button | Tạo một widget nút tùy chỉnh
metro make:form | Tạo một form có validation
" />

<x-doc-commands title="Lệnh trợ giúp" rows="
metro make:model | Tạo một lớp model
metro make:provider | Tạo một provider
metro make:api_service | Tạo một dịch vụ API
metro make:controller | Tạo một controller
metro make:event | Tạo một event
metro make:route_guard | Tạo một route guard
metro make:config | Tạo một file cấu hình
metro make:interceptor | Tạo một network interceptor
metro make:command | Tạo một lệnh Metro tùy chỉnh
metro make:env | Tạo cấu hình môi trường từ .env
" />

### Ví dụ sử dụng

``` bash
# Tạo một trang mới
metro make:page settings_page

# Tạo một model
metro make:model User

# Tạo một dịch vụ API
metro make:api_service user_api_service
```
