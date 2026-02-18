# Installation

---

<a name="section-1"></a>
- [ติดตั้ง](#install "ติดตั้ง")
- [การรันโปรเจกต์](#running-the-project "การรันโปรเจกต์")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## ติดตั้ง

### 1. ติดตั้ง nylo_installer แบบ global

``` bash
dart pub global activate nylo_installer
```

คำสั่งนี้จะติดตั้งเครื่องมือ CLI ของ {{ config('app.name') }} แบบ global บนระบบของคุณ

### 2. สร้างโปรเจกต์ใหม่

``` bash
nylo new my_app
```

คำสั่งนี้จะ clone เทมเพลต {{ config('app.name') }} ตั้งค่าโปรเจกต์ด้วยชื่อแอปของคุณ และติดตั้ง dependencies ทั้งหมดโดยอัตโนมัติ

### 3. ตั้งค่า Metro CLI alias

``` bash
cd my_app
nylo init
```

คำสั่งนี้จะตั้งค่าคำสั่ง `metro` สำหรับโปรเจกต์ของคุณ ช่วยให้คุณใช้คำสั่ง Metro CLI ได้โดยไม่ต้องพิมพ์ `dart run` เต็มรูปแบบ

หลังจากติดตั้ง คุณจะมีโครงสร้างโปรเจกต์ Flutter ที่สมบูรณ์พร้อม:
- การกำหนดเส้นทางและการนำทางที่ตั้งค่าไว้ล่วงหน้า
- โครงร่าง API service
- การตั้งค่าธีมและการแปลภาษา
- Metro CLI สำหรับการสร้างโค้ด


<div id="running-the-project"></div>

## การรันโปรเจกต์

โปรเจกต์ {{ config('app.name') }} รันเหมือนกับแอป Flutter มาตรฐาน

### ใช้ Terminal

``` bash
flutter run
```

### ใช้ IDE

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">การรันและการดีบัก</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">รันแอปโดยไม่มี breakpoints</a>

หากการ build สำเร็จ แอปจะแสดงหน้าจอเริ่มต้นของ {{ config('app.name') }}


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} มาพร้อมกับเครื่องมือ CLI ที่เรียกว่า **Metro** สำหรับการสร้างไฟล์โปรเจกต์

### การรัน Metro

``` bash
metro
```

คำสั่งนี้จะแสดงเมนู Metro:

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

### ข้อมูลอ้างอิงคำสั่ง Metro

| คำสั่ง | คำอธิบาย |
|---------|-------------|
| `make:page` | สร้างหน้าใหม่ |
| `make:stateful_widget` | สร้าง stateful widget |
| `make:stateless_widget` | สร้าง stateless widget |
| `make:state_managed_widget` | สร้าง state-managed widget |
| `make:navigation_hub` | สร้าง navigation hub (แถบนำทางด้านล่าง) |
| `make:journey_widget` | สร้า journey widget สำหรับ navigation hub |
| `make:bottom_sheet_modal` | สร้าง bottom sheet modal |
| `make:button` | สร้าง widget ปุ่มที่กำหนดเอง |
| `make:form` | สร้างฟอร์มพร้อมการตรวจสอบ |
| `make:model` | สร้างคลาสโมเดล |
| `make:provider` | สร้าง provider |
| `make:api_service` | สร้าง API service |
| `make:controller` | สร้าง controller |
| `make:event` | สร้าง event |
| `make:theme` | สร้างธีม |
| `make:route_guard` | สร้าง route guard |
| `make:config` | สร้างไฟล์ config |
| `make:interceptor` | สร้าง network interceptor |
| `make:command` | สร้างคำสั่ง Metro ที่กำหนดเอง |
| `make:env` | สร้าง environment config จาก .env |

### ตัวอย่างการใช้งาน

``` bash
# สร้างหน้าใหม่
metro make:page settings_page

# สร้างโมเดล
metro make:model User

# สร้าง API service
metro make:api_service user_api_service
```
