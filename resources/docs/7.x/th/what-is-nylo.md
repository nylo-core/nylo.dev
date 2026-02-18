# What is {{ config('app.name') }}?

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- การพัฒนาแอป
    - [ใหม่กับ Flutter?](#new-to-flutter "ใหม่กับ Flutter?")
    - [การบำรุงรักษาและกำหนดการออกเวอร์ชัน](#maintenance-and-release-schedule "การบำรุงรักษาและกำหนดการออกเวอร์ชัน")
- เครดิต
    - [แพ็คเกจที่ Framework ใช้](#framework-dependencies "แพ็คเกจที่ Framework ใช้")
    - [ผู้มีส่วนร่วม](#contributors "ผู้มีส่วนร่วม")


<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} เป็น micro-framework สำหรับ Flutter ที่ออกแบบมาเพื่อช่วยให้การพัฒนาแอปง่ายขึ้น มี boilerplate ที่มีโครงสร้างพร้อมการตั้งค่าพื้นฐานที่กำหนดค่าไว้ล่วงหน้า เพื่อให้คุณมุ่งเน้นไปที่การสร้างฟีเจอร์ของแอปแทนการตั้งค่าโครงสร้างพื้นฐาน

{{ config('app.name') }} มีพร้อมใช้งานทันที:

- **Routing** - การจัดการเส้นทางแบบ declarative ที่ง่าย พร้อม guard และ deep linking
- **Networking** - บริการ API พร้อม Dio, interceptor และ response morphing
- **State Management** - state แบบ reactive พร้อม NyState และการอัปเดต state แบบ global
- **Localization** - รองรับหลายภาษาด้วยไฟล์แปลภาษา JSON
- **Themes** - โหมด light/dark พร้อมการเปลี่ยนธีม
- **Local Storage** - ที่จัดเก็บข้อมูลที่ปลอดภัยด้วย Backpack และ NyStorage
- **Forms** - การจัดการฟอร์มพร้อมการตรวจสอบความถูกต้องและประเภทฟิลด์
- **Push Notifications** - รองรับการแจ้งเตือนแบบ local และ remote
- **CLI Tool (Metro)** - สร้างหน้าเพจ, controller, model และอื่นๆ

<div id="new-to-flutter"></div>

## ใหม่กับ Flutter?

หากคุณเป็นมือใหม่กับ Flutter เริ่มต้นด้วยแหล่งข้อมูลอย่างเป็นทางการ:

- <a href="https://flutter.dev" target="_BLANK">เอกสาร Flutter</a> - คู่มือที่ครอบคลุมและเอกสารอ้างอิง API
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">ช่อง YouTube ของ Flutter</a> - บทเรียนและอัปเดต
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> - สูตรสำเร็จสำหรับงานทั่วไป

เมื่อคุณคุ้นเคยกับพื้นฐานของ Flutter แล้ว {{ config('app.name') }} จะเข้าใจได้ง่ายเพราะสร้างบนรูปแบบมาตรฐานของ Flutter


<div id="maintenance-and-release-schedule"></div>

## การบำรุงรักษาและกำหนดการออกเวอร์ชัน

{{ config('app.name') }} ปฏิบัติตาม <a href="https://semver.org" target="_BLANK">Semantic Versioning</a>:

- **Major release** (7.x → 8.x) - ปีละครั้งสำหรับ breaking change
- **Minor release** (7.0 → 7.1) - ฟีเจอร์ใหม่ เข้ากันได้กับเวอร์ชันก่อนหน้า
- **Patch release** (7.0.0 → 7.0.1) - แก้ไขบั๊กและปรับปรุงเล็กน้อย

การแก้ไขบั๊กและแพตช์ด้านความปลอดภัยจะได้รับการจัดการอย่างรวดเร็วผ่าน GitHub repository


<div id="framework-dependencies"></div>

## แพ็คเกจที่ Framework ใช้

{{ config('app.name') }} v7 สร้างบนแพ็คเกจโอเพนซอร์สเหล่านี้:

### แพ็คเกจหลัก

| แพ็คเกจ | วัตถุประสงค์ |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | HTTP client สำหรับ API request |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | ที่จัดเก็บข้อมูลในเครื่องที่ปลอดภัย |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | การแปลภาษาและการจัดรูปแบบ |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | ส่วนขยาย reactive สำหรับ stream |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | การเปรียบเทียบค่าของออบเจ็กต์ |

### UI & Widget

| แพ็คเกจ | วัตถุประสงค์ |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | เอฟเฟกต์ skeleton loading |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | การแจ้งเตือนแบบ toast |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | ฟังก์ชันดึงเพื่อรีเฟรช |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | เลย์เอาต์ grid แบบ staggered |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | ฟิลด์เลือกวันที่ |

### การแจ้งเตือน & การเชื่อมต่อ

| แพ็คเกจ | วัตถุประสงค์ |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | การแจ้งเตือนแบบ push ในเครื่อง |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | สถานะการเชื่อมต่อเครือข่าย |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | ป้ายกำกับไอคอนแอป |

### ยูทิลิตี้

| แพ็คเกจ | วัตถุประสงค์ |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | เปิด URL และแอป |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | แปลงรูปแบบตัวอักษรของสตริง |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | สร้าง UUID |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | เส้นทางไฟล์ระบบ |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | การมาสก์อินพุต |


<div id="contributors"></div>

## ผู้มีส่วนร่วม

ขอบคุณทุกคนที่มีส่วนร่วมใน {{ config('app.name') }}! หากคุณเคยมีส่วนร่วม ติดต่อผ่าน <a href="mailto:support@nylo.dev">support@nylo.dev</a> เพื่อเพิ่มชื่อที่นี่

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (ผู้สร้าง)
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
