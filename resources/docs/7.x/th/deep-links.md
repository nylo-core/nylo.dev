# Deep Links

---

<a name="section-1"></a>

- [บทนำ](#introduction "บทนำ")
- [เริ่มต้นใช้งาน](#getting-started "เริ่มต้นใช้งาน")
  - [เปิดใช้งาน Deep Links](#enable-deep-links "เปิดใช้งาน Deep Links")
  - [สร้าง Provider](#generate-a-provider "สร้าง Provider")
- [การตั้งค่า Platform](#platform-configuration "การตั้งค่า Platform")
  - [การตั้งค่า Android](#android-configuration "การตั้งค่า Android")
  - [การตั้งค่า iOS](#ios-configuration "การตั้งค่า iOS")
- [การดักจับลิงก์](#intercepting-links "การดักจับลิงก์")
- [เส้นทาง Fallback](#fallback-route "เส้นทาง Fallback")
- [การทดสอบ](#testing "การทดสอบ")
- [เอกสาร API](#api-reference "เอกสาร API")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} มาพร้อมการรองรับ deep link ระดับ first-class ที่ขับเคลื่อนโดย [`app_links`](https://pub.dev/packages/app_links) URI ที่เข้ามาจาก Android App Links, iOS Universal Links, custom URL scheme และ web URL จะถูกจับโดยอัตโนมัติและส่งต่อผ่านเส้นทางที่ลงทะเบียนไว้ของคุณ

เพียงบรรทัดเดียวเปิดใช้งานทั้ง pipeline:

```dart
nylo.useDeepLinks();
```

เมื่อ URI เข้ามา {{ config('app.name') }} จะดึง path และ query parameter แล้วเรียก `routeTo()` พร้อมกับข้อมูลเหล่านั้น การเปิดแอปแบบ cold-start (แอปถูกเปิดผ่านลิงก์) จะถูกเล่นซ้ำเมื่อ navigator พร้อม และลิงก์แบบ warm-start (ที่ได้รับขณะแอปทำงานอยู่) จะถูกจัดการในลักษณะเดียวกัน

<div id="getting-started"></div>

## เริ่มต้นใช้งาน

<div id="enable-deep-links"></div>

### เปิดใช้งาน Deep Links

ภายใน method `setup` ของ provider ใดก็ได้ ให้เรียก `useDeepLinks()`:

```dart
import 'package:nylo_framework/nylo_framework.dart';

class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

ลงทะเบียน provider ใน `config/providers.dart` เพื่อให้ทำงานระหว่างการบูตแอป

<div id="generate-a-provider"></div>

### สร้าง Provider

Metro จะสร้าง provider ให้คุณโดยอัตโนมัติ:

```bash
dart run nylo_framework:main make:deep_link_provider
```

คำสั่งนี้จะสร้าง `lib/app/providers/deep_link_provider.dart` พร้อม `useDeepLinks()` ที่เชื่อมต่อไว้แล้วและตัวอย่าง `onIncomingLink` แบบคอมเมนต์ นอกจากนี้ยังเพิ่ม provider ลงใน `config/providers.dart` โดยอัตโนมัติ

<div id="platform-configuration"></div>

## การตั้งค่า Platform

<div id="android-configuration"></div>

### การตั้งค่า Android

เพิ่ม `<intent-filter>` ลงใน `<activity>` หลักของคุณใน `android/app/src/main/AndroidManifest.xml` ตัวอย่างด้านล่างรองรับทั้ง Android App Links (`https://example.com/...`) และ custom scheme (`myapp://...`):

```xml
<activity
    android:name=".MainActivity"
    android:exported="true"
    android:launchMode="singleTop">

    <!-- Standard Flutter intent-filter -->
    <intent-filter>
        <action android:name="android.intent.action.MAIN" />
        <category android:name="android.intent.category.LAUNCHER" />
    </intent-filter>

    <!-- Android App Links -->
    <intent-filter android:autoVerify="true">
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="https"
              android:host="example.com" />
    </intent-filter>

    <!-- Custom URL scheme -->
    <intent-filter>
        <action android:name="android.intent.action.VIEW" />
        <category android:name="android.intent.category.DEFAULT" />
        <category android:name="android.intent.category.BROWSABLE" />
        <data android:scheme="myapp" />
    </intent-filter>
</activity>
```

สำหรับ App Links ที่ได้รับการยืนยัน ให้โฮสต์ไฟล์ `assetlinks.json` ที่ `https://example.com/.well-known/assetlinks.json` ดูรายละเอียดทั้งหมดได้ที่ <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links" target="_BLANK">คู่มือ Flutter App Links</a>

<div id="ios-configuration"></div>

### การตั้งค่า iOS

สำหรับ **custom URL scheme** ให้เพิ่มรายการ `CFBundleURLTypes` ใน `ios/Runner/Info.plist`:

```xml
<key>CFBundleURLTypes</key>
<array>
    <dict>
        <key>CFBundleURLName</key>
        <string>com.example.myapp</string>
        <key>CFBundleURLSchemes</key>
        <array>
            <string>myapp</string>
        </array>
    </dict>
</array>
```

สำหรับ **Universal Links** ให้เปิดใช้งาน Associated Domains capability ใน Xcode และโฮสต์ไฟล์ `apple-app-site-association` บนโดเมนของคุณ ดูขั้นตอนทั้งหมดได้ที่ <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links" target="_BLANK">คู่มือ Flutter Universal Links</a>

<div id="intercepting-links"></div>

## การดักจับลิงก์

ใช้ `onIncomingLink` เพื่อรันลอจิกก่อนที่ {{ config('app.name') }} จะส่งต่อ URI คืนค่า `true` เพื่อให้ {{ config('app.name') }} ส่งต่อโดยอัตโนมัติ หรือ `false` เพื่อจัดการ URI เอง:

```dart
class DeepLinkProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useDeepLinks();

    nylo.onIncomingLink((Uri uri) async {
      // Require auth on /account/* routes
      if (uri.path.startsWith('/account') && !(await Auth.isAuthenticated())) {
        routeTo(LoginPage.path);
        return false;
      }

      // Track analytics for every incoming link
      Analytics.track('deep_link_opened', {'path': uri.path});

      return true;
    });

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

callback จะได้รับ `Uri` แบบเต็ม รวมถึง scheme, host, path และ query parameter

<div id="fallback-route"></div>

## เส้นทาง Fallback

ส่ง `fallbackRoute` ไปยัง `useDeepLinks()` เพื่อไปยังหน้าที่เหมาะสมเมื่อ path ของ URI ที่เข้ามาไม่ได้ลงทะเบียนไว้:

```dart
nylo.useDeepLinks(fallbackRoute: HomePage.path);
```

เมื่อ path ของ URI ตรงกับเส้นทางที่ลงทะเบียนไว้ {{ config('app.name') }} จะส่งต่อไปยังเส้นทางนั้น หากไม่ตรง ผู้ใช้จะถูกนำไปยัง fallback

<div id="testing"></div>

## การทดสอบ

จำลอง deep link ที่เข้ามาจาก command line:

```bash
# Android — custom scheme
adb shell am start -W -a android.intent.action.VIEW \
    -d "myapp://user/42?ref=test" com.example.myapp

# Android — App Link
adb shell am start -W -a android.intent.action.VIEW \
    -d "https://example.com/user/42?ref=test" com.example.myapp

# iOS Simulator
xcrun simctl openurl booted "myapp://user/42?ref=test"
```

ปิดแอประหว่างการทดสอบเพื่อตรวจสอบพฤติกรรม cold-start และทริกเกอร์ URL ขณะที่แอปอยู่ใน foreground เพื่อตรวจสอบ warm-start

<div id="api-reference"></div>

## เอกสาร API

### useDeepLinks

```dart
void useDeepLinks({String? fallbackRoute})
```

เปิดใช้งานการจับ deep link ของ platform เรียกใช้ภายใน method `setup` ของ provider

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `fallbackRoute` | `String?` | เส้นทางที่จะนำทางไปเมื่อ path ของ URI ที่เข้ามาไม่ได้ลงทะเบียนไว้ |

### onIncomingLink

```dart
void onIncomingLink(Future<bool> Function(Uri uri) callback)
```

ลงทะเบียน callback ที่จะถูกเรียกสำหรับทุก URI ที่เข้ามา คืนค่า `true` เพื่อให้ {{ config('app.name') }} ส่งต่อโดยอัตโนมัติ หรือ `false` เพื่อยับยั้งการส่งต่ออัตโนมัติ

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `callback` | `Future<bool> Function(Uri uri)` | รับ URI ที่เข้ามา |
