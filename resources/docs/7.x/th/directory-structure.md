# Directory Structure

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับโครงสร้างไดเรกทอรี")
- [ไดเรกทอรีราก](#root-directory "ไดเรกทอรีราก")
- [ไดเรกทอรี lib](#lib-directory "ไดเรกทอรี lib")
  - [app](#app-directory "ไดเรกทอรี app")
  - [bootstrap](#bootstrap-directory "ไดเรกทอรี bootstrap")
  - [config](#config-directory "ไดเรกทอรี config")
  - [resources](#resources-directory "ไดเรกทอรี resources")
  - [routes](#routes-directory "ไดเรกทอรี routes")
- [ไดเรกทอรี Assets](#assets-directory "ไดเรกทอรี assets")
- [ตัวช่วย Asset](#asset-helpers "ตัวช่วย asset")


<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} ใช้โครงสร้างไดเรกทอรีที่เรียบร้อยและเป็นระเบียบ ได้รับแรงบันดาลใจจาก <a href="https://github.com/laravel/laravel" target="_BLANK">Laravel</a> โครงสร้างนี้ช่วยรักษาความสม่ำเสมอในทุกโปรเจกต์และทำให้ค้นหาไฟล์ได้ง่าย

<div id="root-directory"></div>

## ไดเรกทอรีราก

```
nylo_app/
├── android/          # ไฟล์ platform ของ Android
├── assets/           # รูปภาพ ฟอนต์ และ asset อื่นๆ
├── ios/              # ไฟล์ platform ของ iOS
├── lang/             # ไฟล์ JSON ภาษา/การแปล
├── lib/              # โค้ดแอปพลิเคชัน Dart
├── test/             # ไฟล์ทดสอบ
├── .env              # ตัวแปร environment
├── pubspec.yaml      # dependency และการตั้งค่าโปรเจกต์
└── ...
```

<div id="lib-directory"></div>

## ไดเรกทอรี lib

โฟลเดอร์ `lib/` ประกอบด้วยโค้ดแอปพลิเคชัน Dart ทั้งหมดของคุณ:

```
lib/
├── app/              # ลอจิกของแอปพลิเคชัน
├── bootstrap/        # การตั้งค่าการบูต
├── config/           # ไฟล์การตั้งค่า
├── resources/        # คอมโพเนนต์ UI
├── routes/           # การกำหนด route
└── main.dart         # จุดเริ่มต้นของแอปพลิเคชัน
```

<div id="app-directory"></div>

### app/

ไดเรกทอรี `app/` ประกอบด้วยลอจิกหลักของแอปพลิเคชันของคุณ:

| ไดเรกทอรี | วัตถุประสงค์ |
|-----------|---------|
| `commands/` | คำสั่ง Metro CLI แบบกำหนดเอง |
| `controllers/` | controller ของหน้าสำหรับ business logic |
| `events/` | คลาส event สำหรับระบบ event |
| `forms/` | คลาส form พร้อมการตรวจสอบ |
| `models/` | คลาส data model |
| `networking/` | บริการ API และการตั้งค่าเครือข่าย |
| `networking/dio/interceptors/` | Dio HTTP interceptors |
| `providers/` | service provider ที่บูตเมื่อเริ่มต้นแอป |
| `services/` | คลาส service ทั่วไป |

<div id="bootstrap-directory"></div>

### bootstrap/

ไดเรกทอรี `bootstrap/` ประกอบด้วยไฟล์ที่กำหนดค่าวิธีการบูตแอปของคุณ:

| ไฟล์ | วัตถุประสงค์ |
|------|---------|
| `boot.dart` | การตั้งค่าลำดับการบูตหลัก |
| `decoders.dart` | การลงทะเบียน model และ API decoder |
| `env.g.dart` | การตั้งค่า environment ที่เข้ารหัสที่สร้างขึ้น |
| `events.dart` | การลงทะเบียน event |
| `extensions.dart` | extension แบบกำหนดเอง |
| `helpers.dart` | ฟังก์ชันตัวช่วยแบบกำหนดเอง |
| `providers.dart` | การลงทะเบียน provider |
| `theme.dart` | การตั้งค่าธีม |

<div id="config-directory"></div>

### config/

ไดเรกทอรี `config/` ประกอบด้วยการตั้งค่าแอปพลิเคชัน:

| ไฟล์ | วัตถุประสงค์ |
|------|---------|
| `app.dart` | การตั้งค่าแอปหลัก |
| `design.dart` | การออกแบบแอป (ฟอนต์ โลโก้ ตัวโหลด) |
| `localization.dart` | การตั้งค่าภาษาและ locale |
| `storage_keys.dart` | การกำหนด key สำหรับ local storage |
| `toast_notification.dart` | สไตล์การแจ้งเตือน toast |

<div id="resources-directory"></div>

### resources/

ไดเรกทอรี `resources/` ประกอบด้วยคอมโพเนนต์ UI:

| ไดเรกทอรี | วัตถุประสงค์ |
|-----------|---------|
| `pages/` | widget หน้า (หน้าจอ) |
| `themes/` | การกำหนดธีม |
| `themes/light/` | สีธีมสว่าง |
| `themes/dark/` | สีธีมมืด |
| `widgets/` | คอมโพเนนต์ widget ที่ใช้ซ้ำได้ |
| `widgets/buttons/` | widget ปุ่มแบบกำหนดเอง |
| `widgets/bottom_sheet_modals/` | widget bottom sheet modal |

<div id="routes-directory"></div>

### routes/

ไดเรกทอรี `routes/` ประกอบด้วยการตั้งค่า routing:

| ไฟล์/ไดเรกทอรี | วัตถุประสงค์ |
|----------------|---------|
| `router.dart` | การกำหนด route |
| `guards/` | คลาส route guard |

<div id="assets-directory"></div>

## ไดเรกทอรี Assets

ไดเรกทอรี `assets/` เก็บไฟล์ static:

```
assets/
├── app_icon/         # แหล่งที่มาของไอคอนแอป
├── fonts/            # ฟอนต์แบบกำหนดเอง
└── images/           # asset รูปภาพ
```

### การลงทะเบียน Assets

Assets จะถูกลงทะเบียนใน `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
    - lang/
```

<div id="asset-helpers"></div>

## ตัวช่วย Asset

{{ config('app.name') }} มีตัวช่วยสำหรับการทำงานกับ assets

### Asset รูปภาพ

``` dart
// วิธีมาตรฐานของ Flutter
Image.asset(
  'assets/images/logo.png',
  height: 50,
  width: 50,
)

// ใช้ widget LocalAsset
LocalAsset.image(
  "logo.png",
  height: 50,
  width: 50,
)
```

### Asset ทั่วไป

``` dart
// รับ path ของ asset ใดๆ
String fontPath = getAsset('fonts/custom.ttf');

// ตัวอย่างวิดีโอ
VideoPlayerController.asset(
  getAsset('videos/intro.mp4')
)
```

### ไฟล์ภาษา

ไฟล์ภาษาจะเก็บอยู่ใน `lang/` ที่รากของโปรเจกต์:

```
lang/
├── en.json           # อังกฤษ
├── es.json           # สเปน
├── fr.json           # ฝรั่งเศส
└── ...
```

ดู [Localization](/docs/7.x/localization) สำหรับรายละเอียดเพิ่มเติม
