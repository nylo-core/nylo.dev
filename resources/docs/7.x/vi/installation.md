# Installation

---

<a name="section-1"></a>
- [Cài đặt](#install "Cài đặt")
- [Chạy dự án](#running-the-project "Chạy dự án")
- [Metro CLI](#metro-cli "Metro CLI")
- [Nylo CLI](#nylo-cli "Nylo CLI")
  - [Dọn dẹp dự án](#nylo-clean "Dọn dẹp dự án")
  - [Làm mới iOS pods](#nylo-ios-pod-refresh "Làm mới iOS pods")
  - [Chạy Metro không cần alias](#nylo-metro "Chạy Metro không cần alias")
  - [Cập nhật CLI](#nylo-self-update "Cập nhật CLI")

<div id="install"></div>

Ba lệnh sẽ đưa bạn từ một thư mục trống đến ứng dụng Flutter đang chạy, với định tuyến, mạng, theme và tạo mã đã được thiết lập sẵn.

<x-doc-strip label="Trước khi bắt đầu" items="Đã cài Flutter SDK, Dart 3" linkText="Yêu cầu đầy đủ" linkHref="/vi/docs/{{ $version }}/requirements" />

## Cài đặt

Chạy các lệnh này theo thứ tự. Mỗi lệnh đều có thể chạy lại an toàn.

<x-doc-steps>
<x-doc-step number="1" title="Cài đặt Nylo CLI toàn cục">
Đưa lệnh `nylo` vào hệ thống của bạn.

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

Sau đó, khởi động lại terminal của bạn, hoặc chạy lệnh `source` mà `nylo init` in ra.
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


<div id="nylo-cli"></div>

## Nylo CLI

<!-- uncertain: label "Requires" has no established translation in this locale file's existing x-doc-strip usage -->
<x-doc-strip label="Yêu cầu" items="nylo_installer 1.8.0+" linkText="Cập nhật CLI" linkHref="#nylo-self-update" />

Lệnh `nylo` ở bước 1 làm được nhiều hơn là chỉ tạo dự án. Chạy nó từ thư mục dự án của bạn để dọn dẹp bản build, làm mới iOS pods, chạy test, và kiểm tra bản dịch của bạn.

<x-doc-commands title="Lệnh dự án" rows="
nylo new | Tạo một dự án mới
nylo init | Thiết lập alias metro trong shell của bạn
nylo clean | Chạy flutter clean và flutter pub get
nylo ios:pod-refresh | Cài đặt lại iOS pods của bạn
nylo metro | Chạy một lệnh Metro không cần alias
" />

<x-doc-commands title="Kiểm thử và bản dịch" rows="
nylo test | Định dạng và chạy test của bạn
nylo locale:find-untranslated | Tìm văn bản chưa được bọc trong .tr()
nylo locale:check-missing-keys | Tìm khóa còn thiếu trong file ngôn ngữ của bạn
" />

<x-doc-commands title="Bản thân CLI" rows="
nylo self-update | Cập nhật nylo lên phiên bản mới nhất
nylo --version | Hiển thị phiên bản đã cài đặt
" />

Các lệnh có tùy chọn sẽ liệt kê chúng bằng `-h`, ví dụ `nylo test -h`. Kiểm thử được trình bày trong [Chạy Test](/docs/{{ $version }}/testing#running-tests), và các lệnh `locale:` trong [Kiểm tra bản dịch](/docs/{{ $version }}/localization#auditing-translations).

<div id="nylo-clean"></div>

### Dọn dẹp dự án

`nylo clean` chạy `flutter clean` rồi đến `flutter pub get`. Thêm cờ nền tảng khi việc dọn dẹp thông thường chưa đủ:

``` bash
# flutter clean, sau đó flutter pub get
nylo clean

# Đồng thời xóa ios/Pods, ios/.symlinks và ios/Podfile.lock, rồi chạy pod install --repo-update
nylo clean --ios

# Đồng thời chạy gradlew clean trong android/
nylo clean --android

# Cả hai điều trên
nylo clean --all
```

Nếu một dự án không có thư mục `ios/` hoặc `android/`, nền tảng đó sẽ bị bỏ qua kèm cảnh báo. `pod install` chỉ chạy trên macOS.

<div id="nylo-ios-pod-refresh"></div>

### Làm mới iOS pods

Khi CocoaPods bị lệch đồng bộ, `nylo ios:pod-refresh` sẽ xóa `ios/Pods`, `ios/.symlinks` và `ios/Podfile.lock`, sau đó chạy `pod install --repo-update` trong thư mục `ios/`:

``` bash
nylo ios:pod-refresh
```

Chạy lệnh này từ thư mục gốc dự án trên macOS. Khác với `nylo clean --ios`, nó không chạy `flutter clean` hay `flutter pub get`.

<div id="nylo-metro"></div>

### Chạy Metro không cần alias

`nylo metro` chuyển mọi thứ phía sau nó đến Metro (`dart run nylo_framework:main`). Hãy dùng lệnh này trước khi bạn chạy `nylo init`, hoặc trên Windows, nơi `nylo init` không thể thêm alias `metro` giúp bạn:

``` bash
nylo metro make:page settings_page
nylo metro make:model User
```

<div id="nylo-self-update"></div>

### Cập nhật CLI

``` bash
nylo self-update
```

Lệnh này kiểm tra pub.dev và cài đặt lại `nylo_installer` nếu có phiên bản mới hơn. Sau khi một lệnh hoàn tất, `nylo` cũng cho bạn biết khi có bản cập nhật. Nó sẽ im lặng khi đầu ra không hướng đến terminal, vì vậy thông báo không bao giờ xuất hiện trong log CI hay báo cáo được pipe.

Để xem phiên bản bạn đã cài đặt:

``` bash
nylo --version
```
