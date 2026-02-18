# App Icons

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การสร้างไอคอนแอป](#generating-app-icons "การสร้างไอคอนแอป")
- [การเพิ่มไอคอนแอปของคุณ](#adding-your-app-icon "การเพิ่มไอคอนแอปของคุณ")
- [ข้อกำหนดไอคอนแอป](#app-icon-requirements "ข้อกำหนดไอคอนแอป")
- [การกำหนดค่า](#configuration "การกำหนดค่า")
- [จำนวนป้ายกำกับ](#badge-count "จำนวนป้ายกำกับ")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} v7 ใช้ <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> เพื่อสร้างไอคอนแอปสำหรับ iOS และ Android จากรูปภาพต้นฉบับเดียว

ไอคอนแอปของคุณควรวางไว้ในไดเรกทอรี `assets/app_icon/` โดยมีขนาด **1024x1024 พิกเซล**

<div id="generating-app-icons"></div>

## การสร้างไอคอนแอป

รันคำสั่งต่อไปนี้เพื่อสร้างไอคอนสำหรับทุกแพลตฟอร์ม:

``` bash
dart run flutter_launcher_icons
```

คำสั่งนี้อ่านไอคอนต้นฉบับจาก `assets/app_icon/` และสร้าง:
- ไอคอน iOS ใน `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
- ไอคอน Android ใน `android/app/src/main/res/mipmap-*/`

<div id="adding-your-app-icon"></div>

## การเพิ่มไอคอนแอปของคุณ

1. สร้างไอคอนของคุณเป็นไฟล์ **PNG ขนาด 1024x1024**
2. วางไว้ใน `assets/app_icon/` (เช่น `assets/app_icon/icon.png`)
3. อัปเดต `image_path` ใน `pubspec.yaml` ของคุณหากจำเป็น:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. รันคำสั่งสร้างไอคอน

<div id="app-icon-requirements"></div>

## ข้อกำหนดไอคอนแอป

| คุณลักษณะ | ค่า |
|-----------|-------|
| รูปแบบ | PNG |
| ขนาด | 1024x1024 พิกเซล |
| เลเยอร์ | แบนราบไม่มีความโปร่งใส |

### การตั้งชื่อไฟล์

ตั้งชื่อไฟล์ให้เรียบง่ายโดยไม่มีอักขระพิเศษ:
- `app_icon.png`
- `icon.png`

### แนวทางของแพลตฟอร์ม

สำหรับข้อกำหนดโดยละเอียด โปรดดูแนวทางอย่างเป็นทางการของแพลตฟอร์ม:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple Human Interface Guidelines - App Icons</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Icon Design Specifications</a>

<div id="configuration"></div>

## การกำหนดค่า

ปรับแต่งการสร้างไอคอนใน `pubspec.yaml` ของคุณ:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"

  # Optional: Use different icons per platform
  # image_path_android: "assets/app_icon/android_icon.png"
  # image_path_ios: "assets/app_icon/ios_icon.png"

  # Optional: Adaptive icons for Android
  # adaptive_icon_background: "#ffffff"
  # adaptive_icon_foreground: "assets/app_icon/foreground.png"

  # Optional: Remove alpha channel for iOS
  # remove_alpha_ios: true
```

ดู <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">เอกสาร flutter_launcher_icons</a> สำหรับตัวเลือกทั้งหมดที่มี

<div id="badge-count"></div>

## จำนวนป้ายกำกับ

{{ config('app.name') }} มีฟังก์ชันช่วยเหลือสำหรับจัดการจำนวนป้ายกำกับแอป (ตัวเลขที่แสดงบนไอคอนแอป):

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### การรองรับแพลตฟอร์ม

จำนวนป้ายกำกับรองรับบน:
- **iOS**: รองรับโดยธรรมชาติ
- **Android**: ต้องการการรองรับจาก launcher (launcher ส่วนใหญ่รองรับ)
- **Web**: ไม่รองรับ

### กรณีการใช้งาน

สถานการณ์ทั่วไปสำหรับจำนวนป้ายกำกับ:
- การแจ้งเตือนที่ยังไม่ได้อ่าน
- ข้อความที่รอดำเนินการ
- สินค้าในตะกร้า
- งานที่ยังไม่เสร็จ

``` dart
// Example: Update badge when new messages arrive
void onNewMessage() async {
  int unreadCount = await MessageService.getUnreadCount();
  await setBadgeNumber(unreadCount);
}

// Example: Clear badge when user views messages
void onMessagesViewed() async {
  await clearBadgeNumber();
}
```
