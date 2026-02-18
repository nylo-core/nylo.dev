# Metro CLI tool

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cài đặt](#install "Cài đặt Metro Alias cho {{ config('app.name') }}")
- Các lệnh Make
  - [Tạo controller](#make-controller "Tạo controller mới")
  - [Tạo model](#make-model "Tạo model mới")
  - [Tạo page](#make-page "Tạo page mới")
  - [Tạo stateless widget](#make-stateless-widget "Tạo stateless widget mới")
  - [Tạo stateful widget](#make-stateful-widget "Tạo stateful widget mới")
  - [Tạo journey widget](#make-journey-widget "Tạo journey widget mới")
  - [Tạo API Service](#make-api-service "Tạo API Service mới")
  - [Tạo Event](#make-event "Tạo Event mới")
  - [Tạo Provider](#make-provider "Tạo provider mới")
  - [Tạo Theme](#make-theme "Tạo theme mới")
  - [Tạo Forms](#make-forms "Tạo form mới")
  - [Tạo Route Guard](#make-route-guard "Tạo route guard mới")
  - [Tạo Config File](#make-config-file "Tạo config file mới")
  - [Tạo Command](#make-command "Tạo command mới")
  - [Tạo State Managed Widget](#make-state-managed-widget "Tạo state managed widget mới")
  - [Tạo Navigation Hub](#make-navigation-hub "Tạo navigation hub mới")
  - [Tạo Bottom Sheet Modal](#make-bottom-sheet-modal "Tạo bottom sheet modal mới")
  - [Tạo Button](#make-button "Tạo button mới")
  - [Tạo Interceptor](#make-interceptor "Tạo interceptor mới")
  - [Tạo Env File](#make-env-file "Tạo env file mới")
  - [Tạo Key](#make-key "Tạo APP_KEY")
- App Icons
  - [Tạo App Icons](#build-app-icons "Tạo App Icons với Metro")
- Lệnh tùy chỉnh
  - [Tạo lệnh tùy chỉnh](#creating-custom-commands "Tạo lệnh tùy chỉnh")
  - [Chạy lệnh tùy chỉnh](#running-custom-commands "Chạy lệnh tùy chỉnh")
  - [Thêm tùy chọn vào lệnh](#adding-options-to-custom-commands "Thêm tùy chọn vào lệnh tùy chỉnh")
  - [Thêm cờ vào lệnh](#adding-flags-to-custom-commands "Thêm cờ vào lệnh tùy chỉnh")
  - [Các phương thức hỗ trợ](#custom-command-helper-methods "Các phương thức hỗ trợ cho lệnh tùy chỉnh")
  - [Phương thức nhập liệu tương tác](#interactive-input-methods "Phương thức nhập liệu tương tác")
  - [Định dạng đầu ra](#output-formatting "Định dạng đầu ra")
  - [Hỗ trợ hệ thống tệp](#file-system-helpers "Hỗ trợ hệ thống tệp")
  - [Hỗ trợ JSON và YAML](#json-yaml-helpers "Hỗ trợ JSON và YAML")
  - [Hỗ trợ chuyển đổi kiểu chữ](#case-conversion-helpers "Hỗ trợ chuyển đổi kiểu chữ")
  - [Hỗ trợ đường dẫn dự án](#project-path-helpers "Hỗ trợ đường dẫn dự án")
  - [Hỗ trợ nền tảng](#platform-helpers "Hỗ trợ nền tảng")
  - [Lệnh Dart và Flutter](#dart-flutter-commands "Lệnh Dart và Flutter")
  - [Thao tác tệp Dart](#dart-file-manipulation "Thao tác tệp Dart")
  - [Hỗ trợ thư mục](#directory-helpers "Hỗ trợ thư mục")
  - [Hỗ trợ xác thực](#validation-helpers "Hỗ trợ xác thực")
  - [Tạo khung tệp](#file-scaffolding "Tạo khung tệp")
  - [Trình chạy tác vụ](#task-runner "Trình chạy tác vụ")
  - [Đầu ra dạng bảng](#table-output "Đầu ra dạng bảng")
  - [Thanh tiến trình](#progress-bar "Thanh tiến trình")


<div id="introduction"></div>

## Giới thiệu

Metro là một công cụ CLI hoạt động bên trong framework {{ config('app.name') }}.
Nó cung cấp rất nhiều công cụ hữu ích để tăng tốc quá trình phát triển.

<div id="install"></div>

## Cài đặt

Khi bạn tạo một dự án Nylo mới bằng `nylo init`, lệnh `metro` sẽ được tự động cấu hình cho terminal của bạn. Bạn có thể bắt đầu sử dụng ngay lập tức trong bất kỳ dự án Nylo nào.

Chạy `metro` từ thư mục dự án của bạn để xem tất cả các lệnh có sẵn:

``` bash
metro
```

Bạn sẽ thấy đầu ra tương tự như bên dưới.

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
  make:key
```

<div id="make-controller"></div>

## Tạo controller

- [Tạo controller mới](#making-a-new-controller "Tạo controller mới với Metro")
- [Buộc tạo controller](#forcefully-make-a-controller "Buộc tạo controller mới với Metro")
<div id="making-a-new-controller"></div>

### Tạo controller mới

Bạn có thể tạo controller mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:controller profile_controller
```

Lệnh này sẽ tạo một controller mới nếu nó chưa tồn tại trong thư mục `lib/app/controllers/`.

<div id="forcefully-make-a-controller"></div>

### Buộc tạo controller

**Tham số:**

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè controller hiện có nếu nó đã tồn tại.

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## Tạo model

- [Tạo model mới](#making-a-new-model "Tạo model mới với Metro")
- [Tạo model từ JSON](#make-model-from-json "Tạo model mới từ JSON với Metro")
- [Buộc tạo model](#forcefully-make-a-model "Buộc tạo model mới với Metro")
<div id="making-a-new-model"></div>

### Tạo model mới

Bạn có thể tạo model mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:model product
```

Lệnh này sẽ đặt model mới được tạo trong `lib/app/models/`.

<div id="make-model-from-json"></div>

### Tạo model từ JSON

**Tham số:**

Sử dụng cờ `--json` hoặc `-j` sẽ tạo model mới từ dữ liệu JSON.

``` bash
metro make:model product --json
```

Sau đó, bạn có thể dán JSON vào terminal và nó sẽ tạo model cho bạn.

<div id="forcefully-make-a-model"></div>

### Buộc tạo model

**Tham số:**

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè model hiện có nếu nó đã tồn tại.

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## Tạo page

- [Tạo page mới](#making-a-new-page "Tạo page mới với Metro")
- [Tạo page với controller](#create-a-page-with-a-controller "Tạo page mới với controller bằng Metro")
- [Tạo trang xác thực](#create-an-auth-page "Tạo trang xác thực mới với Metro")
- [Tạo trang khởi tạo](#create-an-initial-page "Tạo trang khởi tạo mới với Metro")
- [Buộc tạo page](#forcefully-make-a-page "Buộc tạo page mới với Metro")

<div id="making-a-new-page"></div>

### Tạo page mới

Bạn có thể tạo page mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:page product_page
```

Lệnh này sẽ tạo page mới nếu nó chưa tồn tại trong thư mục `lib/resources/pages/`.

<div id="create-a-page-with-a-controller"></div>

### Tạo page với controller

Bạn có thể tạo page mới cùng với controller bằng cách chạy lệnh sau trong terminal.

**Tham số:**

Sử dụng cờ `--controller` hoặc `-c` sẽ tạo page mới cùng với controller.

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### Tạo trang xác thực

Bạn có thể tạo trang xác thực mới bằng cách chạy lệnh sau trong terminal.

**Tham số:**

Sử dụng cờ `--auth` hoặc `-a` sẽ tạo trang xác thực mới.

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### Tạo trang khởi tạo

Bạn có thể tạo trang khởi tạo mới bằng cách chạy lệnh sau trong terminal.

**Tham số:**

Sử dụng cờ `--initial` hoặc `-i` sẽ tạo trang khởi tạo mới.

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### Buộc tạo page

**Tham số:**

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè page hiện có nếu nó đã tồn tại.

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Tạo stateless widget

- [Tạo stateless widget mới](#making-a-new-stateless-widget "Tạo stateless widget mới với Metro")
- [Buộc tạo stateless widget](#forcefully-make-a-stateless-widget "Buộc tạo stateless widget mới với Metro")
<div id="making-a-new-stateless-widget"></div>

### Tạo stateless widget mới

Bạn có thể tạo stateless widget mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:stateless_widget product_rating_widget
```

Lệnh trên sẽ tạo widget mới nếu nó chưa tồn tại trong thư mục `lib/resources/widgets/`.

<div id="forcefully-make-a-stateless-widget"></div>

### Buộc tạo stateless widget

**Tham số:**

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè widget hiện có nếu nó đã tồn tại.

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Tạo stateful widget

- [Tạo stateful widget mới](#making-a-new-stateful-widget "Tạo stateful widget mới với Metro")
- [Buộc tạo stateful widget](#forcefully-make-a-stateful-widget "Buộc tạo stateful widget mới với Metro")

<div id="making-a-new-stateful-widget"></div>

### Tạo stateful widget mới

Bạn có thể tạo stateful widget mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:stateful_widget product_rating_widget
```

Lệnh trên sẽ tạo widget mới nếu nó chưa tồn tại trong thư mục `lib/resources/widgets/`.

<div id="forcefully-make-a-stateful-widget"></div>

### Buộc tạo stateful widget

**Tham số:**

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè widget hiện có nếu nó đã tồn tại.

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## Tạo journey widget

- [Tạo journey widget mới](#making-a-new-journey-widget "Tạo journey widget mới với Metro")
- [Buộc tạo journey widget](#forcefully-make-a-journey-widget "Buộc tạo journey widget mới với Metro")

<div id="making-a-new-journey-widget"></div>

### Tạo journey widget mới

Bạn có thể tạo journey widget mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# Ví dụ đầy đủ nếu bạn có BaseNavigationHub
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

Lệnh trên sẽ tạo widget mới nếu nó chưa tồn tại trong thư mục `lib/resources/widgets/`.

Tham số `--parent` được sử dụng để chỉ định widget cha mà journey widget mới sẽ được thêm vào.

Ví dụ

``` bash
metro make:navigation_hub onboarding
```

Tiếp theo, thêm các journey widget mới.
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### Buộc tạo journey widget
**Tham số:**
Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè widget hiện có nếu nó đã tồn tại.

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## Tạo API Service

- [Tạo API Service mới](#making-a-new-api-service "Tạo API Service mới với Metro")
- [Tạo API Service với model](#making-a-new-api-service-with-a-model "Tạo API Service mới với model bằng Metro")
- [Tạo API Service bằng Postman](#make-api-service-using-postman "Tạo API Service bằng Postman")
- [Buộc tạo API Service](#forcefully-make-an-api-service "Buộc tạo API Service mới với Metro")

<div id="making-a-new-api-service"></div>

### Tạo API Service mới

Bạn có thể tạo API service mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:api_service user_api_service
```

Lệnh này sẽ đặt API service mới được tạo trong `lib/app/networking/`.

<div id="making-a-new-api-service-with-a-model"></div>

### Tạo API Service mới với model

Bạn có thể tạo API service mới cùng với model bằng cách chạy lệnh sau trong terminal.

**Tham số:**

Sử dụng tùy chọn `--model` hoặc `-m` sẽ tạo API service mới cùng với model.

``` bash
metro make:api_service user --model="User"
```

Lệnh này sẽ đặt API service mới được tạo trong `lib/app/networking/`.

### Buộc tạo API Service

**Tham số:**

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè API Service hiện có nếu nó đã tồn tại.

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## Tạo event

- [Tạo event mới](#making-a-new-event "Tạo event mới với Metro")
- [Buộc tạo event](#forcefully-make-an-event "Buộc tạo event mới với Metro")

<div id="making-a-new-event"></div>

### Tạo event mới

Bạn có thể tạo event mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:event login_event
```

Lệnh này sẽ tạo event mới trong `lib/app/events`.

<div id="forcefully-make-an-event"></div>

### Buộc tạo event

**Tham số:**

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè event hiện có nếu nó đã tồn tại.

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## Tạo provider

- [Tạo provider mới](#making-a-new-provider "Tạo provider mới với Metro")
- [Buộc tạo provider](#forcefully-make-a-provider "Buộc tạo provider mới với Metro")

<div id="making-a-new-provider"></div>

### Tạo provider mới

Tạo provider mới trong ứng dụng của bạn bằng lệnh dưới đây.

``` bash
metro make:provider firebase_provider
```

Lệnh này sẽ đặt provider mới được tạo trong `lib/app/providers/`.

<div id="forcefully-make-a-provider"></div>

### Buộc tạo provider

**Tham số:**

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè provider hiện có nếu nó đã tồn tại.

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## Tạo theme

- [Tạo theme mới](#making-a-new-theme "Tạo theme mới với Metro")
- [Buộc tạo theme](#forcefully-make-a-theme "Buộc tạo theme mới với Metro")

<div id="making-a-new-theme"></div>

### Tạo theme mới

Bạn có thể tạo theme bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:theme bright_theme
```

Lệnh này sẽ tạo theme mới trong `lib/resources/themes/`.

<div id="forcefully-make-a-theme"></div>

### Buộc tạo theme

**Tham số:**

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè theme hiện có nếu nó đã tồn tại.

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## Tạo Forms

- [Tạo form mới](#making-a-new-form "Tạo form mới với Metro")
- [Buộc tạo form](#forcefully-make-a-form "Buộc tạo form mới với Metro")

<div id="making-a-new-form"></div>

### Tạo form mới

Bạn có thể tạo form mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:form car_advert_form
```

Lệnh này sẽ tạo form mới trong `lib/app/forms`.

<div id="forcefully-make-a-form"></div>

### Buộc tạo form

**Tham số:**

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè form hiện có nếu nó đã tồn tại.

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## Tạo Route Guard

- [Tạo route guard mới](#making-a-new-route-guard "Tạo route guard mới với Metro")
- [Buộc tạo route guard](#forcefully-make-a-route-guard "Buộc tạo route guard mới với Metro")

<div id="making-a-new-route-guard"></div>

### Tạo route guard mới

Bạn có thể tạo route guard bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:route_guard premium_content
```

Lệnh này sẽ tạo route guard mới trong `lib/app/route_guards`.

<div id="forcefully-make-a-route-guard"></div>

### Buộc tạo route guard

**Tham số:**

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè route guard hiện có nếu nó đã tồn tại.

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## Tạo Config File

- [Tạo config file mới](#making-a-new-config-file "Tạo config file mới với Metro")
- [Buộc tạo config file](#forcefully-make-a-config-file "Buộc tạo config file mới với Metro")

<div id="making-a-new-config-file"></div>

### Tạo config file mới

Bạn có thể tạo config file mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:config shopping_settings
```

Lệnh này sẽ tạo config file mới trong `lib/app/config`.

<div id="forcefully-make-a-config-file"></div>

### Buộc tạo config file

**Tham số:**

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè config file hiện có nếu nó đã tồn tại.

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## Tạo Command

- [Tạo command mới](#making-a-new-command "Tạo command mới với Metro")
- [Buộc tạo command](#forcefully-make-a-command "Buộc tạo command mới với Metro")

<div id="making-a-new-command"></div>

### Tạo command mới

Bạn có thể tạo command mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:command my_command
```

Lệnh này sẽ tạo command mới trong `lib/app/commands`.

<div id="forcefully-make-a-command"></div>

### Buộc tạo command

**Tham số:**
Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè command hiện có nếu nó đã tồn tại.

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## Tạo State Managed Widget

Bạn có thể tạo state managed widget mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:state_managed_widget product_rating_widget
```

Lệnh trên sẽ tạo widget mới trong `lib/resources/widgets/`.

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè widget hiện có nếu nó đã tồn tại.

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Tạo Navigation Hub

Bạn có thể tạo navigation hub mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:navigation_hub dashboard
```

Lệnh này sẽ tạo navigation hub mới trong `lib/resources/pages/` và tự động thêm route.

**Tham số:**

| Cờ | Viết tắt | Mô tả |
|------|-------|-------------|
| `--auth` | `-a` | Tạo dưới dạng trang xác thực |
| `--initial` | `-i` | Tạo dưới dạng trang khởi tạo |
| `--force` | `-f` | Ghi đè nếu đã tồn tại |

``` bash
# Tạo dưới dạng trang khởi tạo
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## Tạo Bottom Sheet Modal

Bạn có thể tạo bottom sheet modal mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:bottom_sheet_modal payment_options
```

Lệnh này sẽ tạo bottom sheet modal mới trong `lib/resources/widgets/`.

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè modal hiện có nếu nó đã tồn tại.

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## Tạo Button

Bạn có thể tạo button widget mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:button checkout_button
```

Lệnh này sẽ tạo button widget mới trong `lib/resources/widgets/`.

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè button hiện có nếu nó đã tồn tại.

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## Tạo Interceptor

Bạn có thể tạo network interceptor mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:interceptor auth_interceptor
```

Lệnh này sẽ tạo interceptor mới trong `lib/app/networking/dio/interceptors/`.

Sử dụng cờ `--force` hoặc `-f` sẽ ghi đè interceptor hiện có nếu nó đã tồn tại.

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Tạo Env File

Bạn có thể tạo tệp môi trường mới bằng cách chạy lệnh sau trong terminal.

``` bash
metro make:env .env.staging
```

Lệnh này sẽ tạo tệp `.env` mới trong thư mục gốc của dự án.

<div id="make-key"></div>

## Tạo Key

Tạo `APP_KEY` bảo mật cho mã hóa môi trường. Khóa này được sử dụng cho các tệp `.env` được mã hóa trong v7.

``` bash
metro make:key
```

**Tham số:**

| Cờ / Tùy chọn | Viết tắt | Mô tả |
|---------------|-------|-------------|
| `--force` | `-f` | Ghi đè APP_KEY hiện có |
| `--file` | `-e` | Tệp .env đích (mặc định: `.env`) |

``` bash
# Tạo key và ghi đè key hiện có
metro make:key --force

# Tạo key cho tệp env cụ thể
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## Tạo App Icons

Bạn có thể tạo tất cả app icons cho IOS và Android bằng cách chạy lệnh dưới đây.

``` bash
dart run flutter_launcher_icons:main
```

Lệnh này sử dụng cấu hình <b>flutter_icons</b> trong tệp `pubspec.yaml` của bạn.

<div id="custom-commands"></div>

## Lệnh tùy chỉnh

Lệnh tùy chỉnh cho phép bạn mở rộng CLI của Nylo với các lệnh riêng cho dự án của bạn. Tính năng này cho phép bạn tự động hóa các tác vụ lặp đi lặp lại, triển khai quy trình deploy, hoặc thêm bất kỳ chức năng tùy chỉnh nào trực tiếp vào công cụ dòng lệnh của dự án.

- [Tạo lệnh tùy chỉnh](#creating-custom-commands)
- [Chạy lệnh tùy chỉnh](#running-custom-commands)
- [Thêm tùy chọn vào lệnh](#adding-options-to-custom-commands)
- [Thêm cờ vào lệnh](#adding-flags-to-custom-commands)
- [Các phương thức hỗ trợ](#custom-command-helper-methods)

> **Lưu ý:** Hiện tại bạn không thể import nylo_framework.dart trong các lệnh tùy chỉnh, vui lòng sử dụng ny_cli.dart thay thế.

<div id="creating-custom-commands"></div>

## Tạo lệnh tùy chỉnh

Để tạo lệnh tùy chỉnh mới, bạn có thể sử dụng tính năng `make:command`:

```bash
metro make:command current_time
```

Bạn có thể chỉ định danh mục cho lệnh bằng tùy chọn `--category`:

```bash
# Chỉ định danh mục
metro make:command current_time --category="project"
```

Lệnh này sẽ tạo tệp command mới tại `lib/app/commands/current_time.dart` với cấu trúc sau:

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
      final format = result.getString("format");

      // Get the current time
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // Format the current time
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

Lệnh sẽ tự động được đăng ký trong tệp `lib/app/commands/commands.json`, chứa danh sách tất cả các lệnh đã đăng ký:

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

<div id="running-custom-commands"></div>

## Chạy lệnh tùy chỉnh

Sau khi tạo xong, bạn có thể chạy lệnh tùy chỉnh bằng cách sử dụng viết tắt Metro hoặc lệnh Dart đầy đủ:

```bash
metro app:current_time
```

Khi bạn chạy `metro` mà không có tham số, bạn sẽ thấy các lệnh tùy chỉnh được liệt kê trong menu dưới phần "Custom Commands":

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

Để hiển thị thông tin trợ giúp cho lệnh, sử dụng cờ `--help` hoặc `-h`:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## Thêm tùy chọn vào lệnh

Tùy chọn cho phép lệnh của bạn nhận đầu vào bổ sung từ người dùng. Bạn có thể thêm tùy chọn vào lệnh trong phương thức `builder`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // Thêm tùy chọn với giá trị mặc định
  command.addOption(
    'environment',     // tên tùy chọn
    abbr: 'e',         // viết tắt
    help: 'Target deployment environment', // văn bản trợ giúp
    defaultValue: 'development',  // giá trị mặc định
    allowed: ['development', 'staging', 'production'] // giá trị được phép
  );

  return command;
}
```

Sau đó truy cập giá trị tùy chọn trong phương thức `handle` của lệnh:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // Triển khai lệnh...
}
```

Ví dụ sử dụng:

```bash
metro project:deploy --environment=production
# hoặc sử dụng viết tắt
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## Thêm cờ vào lệnh

Cờ là các tùy chọn boolean có thể bật hoặc tắt. Thêm cờ vào lệnh bằng phương thức `addFlag`:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // tên cờ
    abbr: 'v',       // viết tắt
    help: 'Enable verbose output', // văn bản trợ giúp
    defaultValue: false  // mặc định tắt
  );

  return command;
}
```

Sau đó kiểm tra trạng thái cờ trong phương thức `handle` của lệnh:

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // Ghi log bổ sung...
  }

  // Triển khai lệnh...
}
```

Ví dụ sử dụng:

```bash
metro project:deploy --verbose
# hoặc sử dụng viết tắt
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## Các phương thức hỗ trợ

Class cơ sở `NyCustomCommand` cung cấp nhiều phương thức hỗ trợ cho các tác vụ thường gặp:

#### In thông báo

Dưới đây là một số phương thức in thông báo với các màu khác nhau:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | In thông báo thông tin bằng chữ màu xanh dương |
| [`error`](#custom-command-helper-formatting)     | In thông báo lỗi bằng chữ màu đỏ |
| [`success`](#custom-command-helper-formatting)   | In thông báo thành công bằng chữ màu xanh lá |
| [`warning`](#custom-command-helper-formatting)   | In thông báo cảnh báo bằng chữ màu vàng |

#### Chạy tiến trình

Chạy tiến trình và hiển thị đầu ra trong console:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | Thêm package vào `pubspec.yaml` |
| [`addPackages`](#custom-command-helper-add-packages) | Thêm nhiều package vào `pubspec.yaml` |
| [`runProcess`](#custom-command-helper-run-process) | Chạy tiến trình bên ngoài và hiển thị đầu ra trong console |
| [`prompt`](#custom-command-helper-prompt)    | Thu thập đầu vào từ người dùng dưới dạng văn bản |
| [`confirm`](#custom-command-helper-confirm)   | Đặt câu hỏi có/không và trả về kết quả boolean |
| [`select`](#custom-command-helper-select)    | Hiển thị danh sách tùy chọn và cho người dùng chọn một |
| [`multiSelect`](#custom-command-helper-multi-select) | Cho phép người dùng chọn nhiều tùy chọn từ danh sách |

#### Yêu cầu mạng

Thực hiện yêu cầu mạng qua console:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Thực hiện API call sử dụng Nylo API client |


#### Vòng quay tải

Hiển thị vòng quay tải trong khi thực thi một hàm:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | Hiển thị vòng quay tải trong khi thực thi một hàm |
| [`createSpinner`](#manual-spinner-control) | Tạo instance spinner để điều khiển thủ công |

#### Hỗ trợ lệnh tùy chỉnh

Bạn cũng có thể sử dụng các phương thức hỗ trợ sau để quản lý tham số lệnh:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | Lấy giá trị chuỗi từ tham số lệnh |
| [`getBool`](#custom-command-helper-get-bool)   | Lấy giá trị boolean từ tham số lệnh |
| [`getInt`](#custom-command-helper-get-int)    | Lấy giá trị số nguyên từ tham số lệnh |
| [`sleep`](#custom-command-helper-sleep) | Tạm dừng thực thi trong khoảng thời gian chỉ định |


### Chạy tiến trình bên ngoài

```dart
// Chạy tiến trình với đầu ra hiển thị trong console
await runProcess('flutter build web --release');

// Chạy tiến trình im lặng
await runProcess('flutter pub get', silent: true);

// Chạy tiến trình trong thư mục cụ thể
await runProcess('git pull', workingDirectory: './my-project');
```

### Quản lý Package

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// Thêm package vào pubspec.yaml
addPackage('firebase_core', version: '^2.4.0');

// Thêm dev package vào pubspec.yaml
addPackage('build_runner', dev: true);

// Thêm nhiều package cùng lúc
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### Định dạng đầu ra

```dart
// In thông báo trạng thái với mã màu
info('Processing files...');    // Chữ xanh dương
error('Operation failed');      // Chữ đỏ
success('Deployment complete'); // Chữ xanh lá
warning('Outdated package');    // Chữ vàng
```

<div id="interactive-input-methods"></div>

## Phương thức nhập liệu tương tác

Class cơ sở `NyCustomCommand` cung cấp nhiều phương thức để thu thập đầu vào từ người dùng trong terminal. Các phương thức này giúp bạn dễ dàng tạo giao diện dòng lệnh tương tác cho các lệnh tùy chỉnh.

<div id="custom-command-helper-prompt"></div>

### Nhập văn bản

```dart
String prompt(String question, {String defaultValue = ''})
```

Hiển thị câu hỏi cho người dùng và thu thập phản hồi văn bản.

**Tham số:**
- `question`: Câu hỏi hoặc lời nhắc hiển thị
- `defaultValue`: Giá trị mặc định tùy chọn nếu người dùng chỉ nhấn Enter

**Trả về:** Đầu vào của người dùng dưới dạng chuỗi, hoặc giá trị mặc định nếu không có đầu vào

**Ví dụ:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### Xác nhận

```dart
bool confirm(String question, {bool defaultValue = false})
```

Đặt câu hỏi có/không cho người dùng và trả về kết quả boolean.

**Tham số:**
- `question`: Câu hỏi có/không cần hỏi
- `defaultValue`: Câu trả lời mặc định (true cho có, false cho không)

**Trả về:** `true` nếu người dùng trả lời có, `false` nếu trả lời không

**Ví dụ:**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // Người dùng xác nhận hoặc nhấn Enter (chấp nhận mặc định)
  await runProcess('flutter pub get');
} else {
  // Người dùng từ chối
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### Chọn đơn

```dart
String select(String question, List<String> options, {String? defaultOption})
```

Hiển thị danh sách tùy chọn và cho phép người dùng chọn một.

**Tham số:**
- `question`: Lời nhắc chọn
- `options`: Danh sách các tùy chọn có sẵn
- `defaultOption`: Tùy chọn mặc định (không bắt buộc)

**Trả về:** Tùy chọn được chọn dưới dạng chuỗi

**Ví dụ:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### Chọn nhiều

```dart
List<String> multiSelect(String question, List<String> options)
```

Cho phép người dùng chọn nhiều tùy chọn từ danh sách.

**Tham số:**
- `question`: Lời nhắc chọn
- `options`: Danh sách các tùy chọn có sẵn

**Trả về:** Danh sách các tùy chọn được chọn

**Ví dụ:**
```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences', 'path_provider']
);

if (packages.isNotEmpty) {
  info('Installing ${packages.length} packages...');
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

<div id="custom-command-helper-api"></div>

## Phương thức hỗ trợ API

Phương thức hỗ trợ `api` đơn giản hóa việc thực hiện yêu cầu mạng từ các lệnh tùy chỉnh.

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## Ví dụ sử dụng cơ bản

### Yêu cầu GET

```dart
// Lấy dữ liệu
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### Yêu cầu POST

```dart
// Tạo tài nguyên
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### Yêu cầu PUT

```dart
// Cập nhật tài nguyên
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### Yêu cầu DELETE

```dart
// Xóa tài nguyên
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### Yêu cầu PATCH

```dart
// Cập nhật một phần tài nguyên
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### Với tham số truy vấn

```dart
// Thêm tham số truy vấn
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### Với Spinner

```dart
// Sử dụng với spinner để UI tốt hơn
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // Xử lý dữ liệu
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## Chức năng Spinner

Spinner cung cấp phản hồi trực quan trong quá trình thực thi các thao tác kéo dài trong lệnh tùy chỉnh. Chúng hiển thị chỉ báo hoạt hình cùng với thông báo trong khi lệnh thực thi các tác vụ bất đồng bộ, nâng cao trải nghiệm người dùng bằng cách hiển thị tiến trình và trạng thái.

- [Sử dụng với spinner](#using-with-spinner)
- [Điều khiển spinner thủ công](#manual-spinner-control)
- [Ví dụ](#spinner-examples)

<div id="using-with-spinner"></div>

## Sử dụng với spinner

Phương thức `withSpinner` cho phép bạn bọc một tác vụ bất đồng bộ với hoạt hình spinner tự động bắt đầu khi tác vụ bắt đầu và dừng khi hoàn thành hoặc thất bại:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**Tham số:**
- `task`: Hàm bất đồng bộ cần thực thi
- `message`: Văn bản hiển thị khi spinner đang chạy
- `successMessage`: Thông báo tùy chọn hiển thị khi hoàn thành thành công
- `errorMessage`: Thông báo tùy chọn hiển thị khi tác vụ thất bại

**Trả về:** Kết quả của hàm task

**Ví dụ:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Chạy tác vụ với spinner
  final projectFiles = await withSpinner(
    task: () async {
      // Tác vụ kéo dài (ví dụ: phân tích tệp dự án)
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // Tiếp tục với kết quả
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## Điều khiển Spinner thủ công

Với các tình huống phức tạp hơn khi bạn cần điều khiển trạng thái spinner thủ công, bạn có thể tạo instance spinner:

```dart
ConsoleSpinner createSpinner(String message)
```

**Tham số:**
- `message`: Văn bản hiển thị khi spinner đang chạy

**Trả về:** Instance `ConsoleSpinner` mà bạn có thể điều khiển thủ công

**Ví dụ với điều khiển thủ công:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // Tạo instance spinner
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // Tác vụ đầu tiên
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // Tác vụ thứ hai
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // Tác vụ thứ ba
    await runProcess('./deploy.sh', silent: true);

    // Hoàn thành thành công
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // Xử lý lỗi
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>

## Ví dụ

### Tác vụ đơn giản với Spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // Cài đặt dependencies
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### Nhiều thao tác liên tiếp

```dart
@override
Future<void> handle(CommandResult result) async {
  // Thao tác đầu tiên với spinner
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // Thao tác thứ hai với spinner
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // Thao tác thứ ba với spinner
  final buildSuccess = await withSpinner(
    task: () async {
      await runProcess('flutter build apk --release', silent: true);
      return true;
    },
    message: 'Building release APK',
    successMessage: 'Release APK built successfully',
  );

  if (buildSuccess) {
    success('Build process completed');
  }
}
```

### Quy trình phức tạp với điều khiển thủ công

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();

  try {
    // Chạy nhiều bước với cập nhật trạng thái
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // Hoàn thành quy trình
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

Sử dụng spinner trong các lệnh tùy chỉnh cung cấp phản hồi trực quan rõ ràng cho người dùng trong quá trình thực thi các thao tác kéo dài, tạo trải nghiệm dòng lệnh chuyên nghiệp hơn.

<div id="custom-command-helper-get-string"></div>

### Lấy giá trị chuỗi từ tùy chọn

```dart
String getString(String name, {String defaultValue = ''})
```

**Tham số:**

- `name`: Tên tùy chọn cần lấy
- `defaultValue`: Giá trị mặc định tùy chọn nếu không được cung cấp

**Trả về:** Giá trị của tùy chọn dưới dạng chuỗi

**Ví dụ:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("name", defaultValue: "Anthony");
  return command;
}

Future<void> handle(CommandResult result) async {
  final name = result.getString('name');
  info('Hello, $name!');
}
```

<div id="custom-command-helper-get-bool"></div>

### Lấy giá trị boolean từ tùy chọn

```dart
bool getBool(String name, {bool defaultValue = false})
```

**Tham số:**
- `name`: Tên tùy chọn cần lấy
- `defaultValue`: Giá trị mặc định tùy chọn nếu không được cung cấp

**Trả về:** Giá trị của tùy chọn dưới dạng boolean


**Ví dụ:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag("verbose", defaultValue: false);
  return command;
}

Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  if (verbose) {
    info('Verbose mode enabled');
  } else {
    info('Verbose mode disabled');
  }
}
```

<div id="custom-command-helper-get-int"></div>

### Lấy giá trị số nguyên từ tùy chọn

```dart
int getInt(String name, {int defaultValue = 0})
```

**Tham số:**
- `name`: Tên tùy chọn cần lấy
- `defaultValue`: Giá trị mặc định tùy chọn nếu không được cung cấp

**Trả về:** Giá trị của tùy chọn dưới dạng số nguyên

**Ví dụ:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("count", defaultValue: 5);
  return command;
}

Future<void> handle(CommandResult result) async {
  final count = result.getInt('count');
  info('Count is set to $count');
}
```

<div id="custom-command-helper-sleep"></div>

### Tạm dừng trong khoảng thời gian chỉ định

```dart
void sleep(int seconds)
```

**Tham số:**
- `seconds`: Số giây cần tạm dừng

**Trả về:** Không có

**Ví dụ:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## Định dạng đầu ra

Ngoài các phương thức cơ bản `info`, `error`, `success`, và `warning`, `NyCustomCommand` còn cung cấp các hỗ trợ đầu ra bổ sung:

```dart
@override
Future<void> handle(CommandResult result) async {
  // In văn bản thuần (không màu)
  line('Processing your request...');

  // In dòng trống
  newLine();       // một dòng trống
  newLine(3);      // ba dòng trống

  // In chú thích mờ (chữ xám)
  comment('This is a background note');

  // In hộp cảnh báo nổi bật
  alert('Important: Please read carefully');

  // Ask là bí danh của prompt
  final name = ask('What is your name?');

  // Đầu vào ẩn cho dữ liệu nhạy cảm (ví dụ: mật khẩu, API key)
  final apiKey = promptSecret('Enter your API key:');

  // Hủy lệnh với thông báo lỗi và mã thoát
  if (name.isEmpty) {
    abort('Name is required');  // thoát với mã 1
  }
}
```

| Phương thức | Mô tả |
|--------|-------------|
| `line(String message)` | In văn bản thuần không màu |
| `newLine([int count = 1])` | In dòng trống |
| `comment(String message)` | In chữ mờ/xám |
| `alert(String message)` | In hộp cảnh báo nổi bật |
| `ask(String question, {String defaultValue})` | Bí danh của `prompt` |
| `promptSecret(String question)` | Đầu vào ẩn cho dữ liệu nhạy cảm |
| `abort([String? message, int exitCode = 1])` | Thoát lệnh với lỗi |

<div id="file-system-helpers"></div>

## Hỗ trợ hệ thống tệp

`NyCustomCommand` bao gồm các hỗ trợ hệ thống tệp tích hợp sẵn để bạn không cần import `dart:io` thủ công cho các thao tác thông dụng.

### Đọc và ghi tệp

```dart
@override
Future<void> handle(CommandResult result) async {
  // Kiểm tra tệp có tồn tại không
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // Kiểm tra thư mục có tồn tại không
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // Đọc tệp (bất đồng bộ)
  String content = await readFile('pubspec.yaml');

  // Đọc tệp (đồng bộ)
  String contentSync = readFileSync('pubspec.yaml');

  // Ghi vào tệp (bất đồng bộ)
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // Ghi vào tệp (đồng bộ)
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // Nối nội dung vào tệp
  await appendFile('log.txt', 'New log entry\n');

  // Đảm bảo thư mục tồn tại (tạo nếu thiếu)
  await ensureDirectory('lib/generated');

  // Xóa tệp
  await deleteFile('lib/generated/output.dart');

  // Sao chép tệp
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| Phương thức | Mô tả |
|--------|-------------|
| `fileExists(String path)` | Trả về `true` nếu tệp tồn tại |
| `directoryExists(String path)` | Trả về `true` nếu thư mục tồn tại |
| `readFile(String path)` | Đọc tệp dưới dạng chuỗi (bất đồng bộ) |
| `readFileSync(String path)` | Đọc tệp dưới dạng chuỗi (đồng bộ) |
| `writeFile(String path, String content)` | Ghi nội dung vào tệp (bất đồng bộ) |
| `writeFileSync(String path, String content)` | Ghi nội dung vào tệp (đồng bộ) |
| `appendFile(String path, String content)` | Nối nội dung vào tệp |
| `ensureDirectory(String path)` | Tạo thư mục nếu chưa tồn tại |
| `deleteFile(String path)` | Xóa tệp |
| `copyFile(String source, String destination)` | Sao chép tệp |

<div id="json-yaml-helpers"></div>

## Hỗ trợ JSON và YAML

Đọc và ghi tệp JSON và YAML với các hỗ trợ tích hợp sẵn.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Đọc tệp JSON dưới dạng Map
  Map<String, dynamic> config = await readJson('config.json');

  // Đọc tệp JSON dưới dạng List
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // Ghi dữ liệu vào tệp JSON (mặc định đẹp)
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // Ghi JSON gọn
  await writeJson('output.json', data, pretty: false);

  // Nối phần tử vào tệp mảng JSON
  // Nếu tệp chứa [{"name": "a"}], lệnh này sẽ thêm vào mảng đó
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // tránh trùng lặp theo khóa này
  );

  // Đọc tệp YAML dưới dạng Map
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| Phương thức | Mô tả |
|--------|-------------|
| `readJson(String path)` | Đọc tệp JSON dưới dạng `Map<String, dynamic>` |
| `readJsonArray(String path)` | Đọc tệp JSON dưới dạng `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Ghi dữ liệu dưới dạng JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Nối vào tệp mảng JSON |
| `readYaml(String path)` | Đọc tệp YAML dưới dạng `Map<String, dynamic>` |

<div id="case-conversion-helpers"></div>

## Hỗ trợ chuyển đổi kiểu chữ

Chuyển đổi chuỗi giữa các quy ước đặt tên mà không cần import package `recase`.

```dart
@override
Future<void> handle(CommandResult result) async {
  String input = 'user profile page';

  info(snakeCase(input));    // user_profile_page
  info(camelCase(input));    // userProfilePage
  info(pascalCase(input));   // UserProfilePage
  info(titleCase(input));    // User Profile Page
  info(kebabCase(input));    // user-profile-page
  info(constantCase(input)); // USER_PROFILE_PAGE
}
```

| Phương thức | Định dạng đầu ra | Ví dụ |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Hỗ trợ đường dẫn dự án

Các getter cho thư mục chuẩn của dự án {{ config('app.name') }}. Chúng trả về đường dẫn tương đối so với thư mục gốc dự án.

```dart
@override
Future<void> handle(CommandResult result) async {
  info(modelsPath);       // lib/app/models
  info(controllersPath);  // lib/app/controllers
  info(widgetsPath);      // lib/resources/widgets
  info(pagesPath);        // lib/resources/pages
  info(commandsPath);     // lib/app/commands
  info(configPath);       // lib/config
  info(providersPath);    // lib/app/providers
  info(eventsPath);       // lib/app/events
  info(networkingPath);   // lib/app/networking
  info(themesPath);       // lib/resources/themes

  // Xây dựng đường dẫn tùy chỉnh tương đối so với thư mục gốc dự án
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
```

| Thuộc tính | Đường dẫn |
|----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | Phân giải đường dẫn tương đối trong dự án |

<div id="platform-helpers"></div>

## Hỗ trợ nền tảng

Kiểm tra nền tảng và truy cập biến môi trường.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Kiểm tra nền tảng
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // Thư mục làm việc hiện tại
  info('Working in: $workingDirectory');

  // Đọc biến môi trường hệ thống
  String home = env('HOME', '/default/path');
}
```

| Thuộc tính / Phương thức | Mô tả |
|-------------------|-------------|
| `isWindows` | `true` nếu đang chạy trên Windows |
| `isMacOS` | `true` nếu đang chạy trên macOS |
| `isLinux` | `true` nếu đang chạy trên Linux |
| `workingDirectory` | Đường dẫn thư mục làm việc hiện tại |
| `env(String key, [String defaultValue = ''])` | Đọc biến môi trường hệ thống |

<div id="dart-flutter-commands"></div>

## Lệnh Dart và Flutter

Chạy các lệnh CLI Dart và Flutter thông dụng dưới dạng phương thức hỗ trợ. Mỗi phương thức trả về mã thoát của tiến trình.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Định dạng tệp hoặc thư mục Dart
  await dartFormat('lib/app/models/user.dart');

  // Chạy dart analyze
  int analyzeResult = await dartAnalyze('lib/');

  // Chạy flutter pub get
  await flutterPubGet();

  // Chạy flutter clean
  await flutterClean();

  // Build cho target với tham số bổ sung
  await flutterBuild('apk', args: ['--release', '--split-per-abi']);
  await flutterBuild('web', args: ['--release']);

  // Chạy flutter test
  await flutterTest();
  await flutterTest('test/unit/');  // thư mục cụ thể
}
```

| Phương thức | Mô tả |
|--------|-------------|
| `dartFormat(String path)` | Chạy `dart format` trên tệp hoặc thư mục |
| `dartAnalyze([String? path])` | Chạy `dart analyze` |
| `flutterPubGet()` | Chạy `flutter pub get` |
| `flutterClean()` | Chạy `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Chạy `flutter build <target>` |
| `flutterTest([String? path])` | Chạy `flutter test` |

<div id="dart-file-manipulation"></div>

## Thao tác tệp Dart

Các hỗ trợ để chỉnh sửa tệp Dart theo chương trình, hữu ích khi xây dựng công cụ tạo khung.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Thêm câu lệnh import vào tệp Dart (tránh trùng lặp)
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // Chèn mã trước dấu ngoặc nhọn đóng cuối cùng trong tệp
  // Hữu ích để thêm mục vào bản đồ đăng ký
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // Kiểm tra tệp có chứa chuỗi cụ thể không
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // Kiểm tra tệp có khớp với mẫu regex không
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| Phương thức | Mô tả |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Thêm import vào tệp Dart (bỏ qua nếu đã có) |
| `insertBeforeClosingBrace(String filePath, String code)` | Chèn mã trước `}` cuối cùng trong tệp |
| `fileContains(String filePath, String identifier)` | Kiểm tra tệp có chứa chuỗi không |
| `fileContainsPattern(String filePath, Pattern pattern)` | Kiểm tra tệp có khớp với mẫu không |

<div id="directory-helpers"></div>

## Hỗ trợ thư mục

Các hỗ trợ để làm việc với thư mục và tìm tệp.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Liệt kê nội dung thư mục
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // Liệt kê đệ quy
  var allEntities = listDirectory('lib/', recursive: true);

  // Tìm tệp theo tiêu chí
  List<File> dartFiles = findFiles(
    'lib/app/models',
    extension: '.dart',
    recursive: true,
  );

  // Tìm tệp theo mẫu tên
  List<File> testFiles = findFiles(
    'test/',
    namePattern: RegExp(r'_test\.dart$'),
  );

  // Xóa thư mục đệ quy
  await deleteDirectory('build/');

  // Sao chép thư mục (đệ quy)
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| Phương thức | Mô tả |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Liệt kê nội dung thư mục |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Tìm tệp theo tiêu chí |
| `deleteDirectory(String path)` | Xóa thư mục đệ quy |
| `copyDirectory(String source, String destination)` | Sao chép thư mục đệ quy |

<div id="validation-helpers"></div>

## Hỗ trợ xác thực

Các hỗ trợ để xác thực và làm sạch đầu vào người dùng cho việc tạo mã.

```dart
@override
Future<void> handle(CommandResult result) async {
  // Xác thực định danh Dart
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // Yêu cầu tham số đầu tiên không rỗng
  String name = requireArgument(result, message: 'Please provide a name');

  // Làm sạch tên class (PascalCase, xóa hậu tố)
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // Trả về: 'User'

  // Làm sạch tên tệp (snake_case với phần mở rộng)
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // Trả về: 'user_model.dart'
}
```

| Phương thức | Mô tả |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Xác thực tên định danh Dart |
| `requireArgument(CommandResult result, {String? message})` | Yêu cầu tham số đầu tiên không rỗng hoặc hủy |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Làm sạch và PascalCase tên class |
| `cleanFileName(String name, {String extension = '.dart'})` | Làm sạch và snake_case tên tệp |

<div id="file-scaffolding"></div>

## Tạo khung tệp

Tạo một hoặc nhiều tệp với nội dung bằng hệ thống tạo khung.

### Tệp đơn

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: implement login
    return false;
  }
}
''',
    force: false,  // không ghi đè nếu đã tồn tại
    successMessage: 'AuthService created',
  );
}
```

### Nhiều tệp

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffoldMany([
    ScaffoldFile(
      path: 'lib/app/models/product.dart',
      content: 'class Product {}',
      successMessage: 'Product model created',
    ),
    ScaffoldFile(
      path: 'lib/app/networking/product_api_service.dart',
      content: 'class ProductApiService {}',
      successMessage: 'Product API service created',
    ),
  ], force: false);
}
```

Class `ScaffoldFile` chấp nhận:

| Thuộc tính | Kiểu | Mô tả |
|----------|------|-------------|
| `path` | `String` | Đường dẫn tệp cần tạo |
| `content` | `String` | Nội dung tệp |
| `successMessage` | `String?` | Thông báo hiển thị khi thành công |

<div id="task-runner"></div>

## Trình chạy tác vụ

Chạy một loạt tác vụ được đặt tên với đầu ra trạng thái tự động.

### Trình chạy tác vụ cơ bản

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasks([
    CommandTask(
      'Clean project',
      () => runProcess('flutter clean', silent: true),
    ),
    CommandTask(
      'Fetch dependencies',
      () => runProcess('flutter pub get', silent: true),
    ),
    CommandTask(
      'Run tests',
      () => runProcess('flutter test', silent: true),
      stopOnError: true,  // dừng pipeline nếu thất bại (mặc định)
    ),
  ]);
}
```

### Trình chạy tác vụ với Spinner

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasksWithSpinner([
    CommandTask(
      name: 'Preparing release',
      action: () async {
        await flutterClean();
        await flutterPubGet();
      },
    ),
    CommandTask(
      name: 'Building APK',
      action: () => flutterBuild('apk', args: ['--release']),
    ),
  ]);
}
```

Class `CommandTask` chấp nhận:

| Thuộc tính | Kiểu | Mặc định | Mô tả |
|----------|------|---------|-------------|
| `name` | `String` | bắt buộc | Tên tác vụ hiển thị trong đầu ra |
| `action` | `Future<void> Function()` | bắt buộc | Hàm bất đồng bộ cần thực thi |
| `stopOnError` | `bool` | `true` | Có dừng các tác vụ còn lại nếu tác vụ này thất bại hay không |

<div id="table-output"></div>

## Đầu ra dạng bảng

Hiển thị bảng ASCII được định dạng trong console.

```dart
@override
Future<void> handle(CommandResult result) async {
  table(
    ['Name', 'Version', 'Status'],
    [
      ['nylo_framework', '7.0.0', 'installed'],
      ['nylo_support', '7.0.0', 'installed'],
      ['dio', '5.4.0', 'installed'],
    ],
  );
}
```

Đầu ra:

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="progress-bar"></div>

## Thanh tiến trình

Hiển thị thanh tiến trình cho các thao tác có số lượng phần tử đã biết.

### Thanh tiến trình thủ công

```dart
@override
Future<void> handle(CommandResult result) async {
  // Tạo thanh tiến trình cho 100 phần tử
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // tăng thêm 1
  }

  progress.complete('All files processed');
}
```

### Xử lý phần tử với tiến trình

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // Xử lý phần tử với theo dõi tiến trình tự động
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // xử lý từng tệp
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### Tiến trình đồng bộ

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // xử lý đồng bộ
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

Class `ConsoleProgressBar` cung cấp:

| Phương thức | Mô tả |
|--------|-------------|
| `start()` | Bắt đầu thanh tiến trình |
| `tick([int amount = 1])` | Tăng tiến trình |
| `update(int value)` | Đặt tiến trình tới giá trị cụ thể |
| `updateMessage(String newMessage)` | Thay đổi thông báo hiển thị |
| `complete([String? completionMessage])` | Hoàn thành với thông báo tùy chọn |
| `stop()` | Dừng mà không hoàn thành |
| `current` | Giá trị tiến trình hiện tại (getter) |
| `percentage` | Tiến trình dưới dạng phần trăm (getter) |
