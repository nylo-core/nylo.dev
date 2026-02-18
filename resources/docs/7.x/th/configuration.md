# Configuration

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับการตั้งค่า")
- สภาพแวดล้อม
  - [ไฟล์ .env](#env-file "ไฟล์ .env")
  - [การสร้างการตั้งค่าสภาพแวดล้อม](#generating-env "การสร้างการตั้งค่าสภาพแวดล้อม")
  - [การเรียกใช้ค่า](#retrieving-values "การเรียกใช้ค่าสภาพแวดล้อม")
  - [การสร้างคลาส Config](#creating-config-classes "การสร้างคลาส Config")
  - [ชนิดของตัวแปร](#variable-types "ชนิดของตัวแปรสภาพแวดล้อม")
- [Environment Flavours](#environment-flavours "Environment Flavours")
- [การ Inject ในเวลา Build](#build-time-injection "การ Inject ในเวลา Build")


<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} v7 ใช้ระบบการตั้งค่าสภาพแวดล้อมที่ปลอดภัย ตัวแปรสภาพแวดล้อมของคุณจะถูกเก็บในไฟล์ `.env` จากนั้นเข้ารหัสเป็นไฟล์ Dart ที่สร้างขึ้น (`env.g.dart`) เพื่อใช้ในแอปของคุณ

วิธีการนี้ให้:
- **ความปลอดภัย**: ค่าสภาพแวดล้อมถูกเข้ารหัส XOR ในแอปที่คอมไพล์แล้ว
- **Type safety**: ค่าต่างๆ จะถูก parse เป็นชนิดที่เหมาะสมโดยอัตโนมัติ
- **ความยืดหยุ่นในเวลา build**: การตั้งค่าที่แตกต่างกันสำหรับ development, staging และ production

<div id="env-file"></div>

## ไฟล์ .env

ไฟล์ `.env` ที่ root ของโปรเจกต์ประกอบด้วยตัวแปรการตั้งค่าของคุณ:

``` bash
# Environment configuration
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### ตัวแปรที่ใช้ได้

| ตัวแปร | คำอธิบาย |
|----------|-------------|
| `APP_KEY` | **จำเป็น** คีย์ลับ 32 ตัวอักษรสำหรับการเข้ารหัส |
| `APP_NAME` | ชื่อแอปพลิเคชันของคุณ |
| `APP_ENV` | สภาพแวดล้อม: `developing` หรือ `production` |
| `APP_DEBUG` | เปิดใช้โหมด debug (`true`/`false`) |
| `APP_URL` | URL ของแอปของคุณ |
| `API_BASE_URL` | URL พื้นฐานสำหรับ API request |
| `ASSET_PATH` | เส้นทางไปยังโฟลเดอร์ assets |
| `DEFAULT_LOCALE` | รหัสภาษาเริ่มต้น |

<div id="generating-env"></div>

## การสร้างการตั้งค่าสภาพแวดล้อม

{{ config('app.name') }} v7 ต้องการให้คุณสร้างไฟล์สภาพแวดล้อมที่เข้ารหัสก่อนที่แอปของคุณจะสามารถเข้าถึงค่า env ได้

### ขั้นตอนที่ 1: สร้าง APP_KEY

ก่อนอื่น สร้าง APP_KEY ที่ปลอดภัย:

``` bash
metro make:key
```

คำสั่งนี้จะเพิ่ม `APP_KEY` 32 ตัวอักษรลงในไฟล์ `.env` ของคุณ

### ขั้นตอนที่ 2: สร้าง env.g.dart

สร้างไฟล์สภาพแวดล้อมที่เข้ารหัส:

``` bash
metro make:env
```

คำสั่งนี้จะสร้าง `lib/bootstrap/env.g.dart` พร้อมตัวแปรสภาพแวดล้อมที่เข้ารหัสของคุณ

env ของคุณจะถูกลงทะเบียนโดยอัตโนมัติเมื่อแอปเริ่มทำงาน — `Nylo.init(env: Env.get, ...)` ใน `main.dart` จะจัดการให้คุณ ไม่ต้องตั้งค่าเพิ่มเติม

### การสร้างใหม่หลังแก้ไข

เมื่อคุณแก้ไขไฟล์ `.env` ให้สร้างการตั้งค่าใหม่:

``` bash
metro make:env --force
```

flag `--force` จะเขียนทับ `env.g.dart` ที่มีอยู่

<div id="retrieving-values"></div>

## การเรียกใช้ค่า

วิธีที่แนะนำในการเข้าถึงค่าสภาพแวดล้อมคือผ่าน **คลาส config** ไฟล์ `lib/config/app.dart` ของคุณใช้ `getEnv()` เพื่อเปิดเผยค่า env เป็น static field ที่มีชนิดข้อมูล:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

จากนั้นในโค้ดแอปของคุณ เข้าถึงค่าผ่านคลาส config:

``` dart
// Anywhere in your app
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

รูปแบบนี้ทำให้การเข้าถึง env รวมศูนย์อยู่ในคลาส config ของคุณ ตัวช่วย `getEnv()` ควรใช้ภายในคลาส config แทนที่จะใช้โดยตรงในโค้ดแอป

<div id="creating-config-classes"></div>

## การสร้างคลาส Config

คุณสามารถสร้างคลาส config แบบกำหนดเองสำหรับบริการของบุคคลที่สามหรือการตั้งค่าเฉพาะฟีเจอร์โดยใช้ Metro:

``` bash
metro make:config RevenueCat
```

คำสั่งนี้จะสร้างไฟล์ config ใหม่ที่ `lib/config/revenue_cat_config.dart`:

``` dart
final class RevenueCatConfig {
  // Add your config values here
}
```

### ตัวอย่าง: การตั้งค่า RevenueCat

**ขั้นตอนที่ 1:** เพิ่มตัวแปรสภาพแวดล้อมลงในไฟล์ `.env` ของคุณ:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**ขั้นตอนที่ 2:** อัปเดตคลาส config ของคุณเพื่ออ้างอิงค่าเหล่านี้:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**ขั้นตอนที่ 3:** สร้างการตั้งค่าสภาพแวดล้อมใหม่:

``` bash
metro make:env --force
```

**ขั้นตอนที่ 4:** ใช้คลาส config ในแอปของคุณ:

``` dart
import '/config/revenue_cat_config.dart';

// Initialize RevenueCat
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// Check entitlements
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // Grant premium access
}
```

วิธีการนี้ทำให้ API key และค่าการตั้งค่าของคุณปลอดภัยและรวมศูนย์ ทำให้ง่ายต่อการจัดการค่าที่แตกต่างกันในแต่ละสภาพแวดล้อม

<div id="variable-types"></div>

## ชนิดของตัวแปร

ค่าในไฟล์ `.env` ของคุณจะถูก parse โดยอัตโนมัติ:

| ค่า .env | ชนิด Dart | ตัวอย่าง |
|------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (string ว่าง) |


<div id="environment-flavours"></div>

## Environment Flavours

สร้างการตั้งค่าที่แตกต่างกันสำหรับ development, staging และ production

### ขั้นตอนที่ 1: สร้างไฟล์สภาพแวดล้อม

สร้างไฟล์ `.env` แยกกัน:

``` bash
.env                  # Development (ค่าเริ่มต้น)
.env.staging          # Staging
.env.production       # Production
```

ตัวอย่าง `.env.production`:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### ขั้นตอนที่ 2: สร้างการตั้งค่าสภาพแวดล้อม

สร้างจากไฟล์ env ที่ระบุ:

``` bash
# สำหรับ production
metro make:env --file=".env.production" --force

# สำหรับ staging
metro make:env --file=".env.staging" --force
```

### ขั้นตอนที่ 3: Build แอปของคุณ

Build ด้วยการตั้งค่าที่เหมาะสม:

``` bash
# Development
flutter run

# Production build
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## การ Inject ในเวลา Build

เพื่อความปลอดภัยที่มากขึ้น คุณสามารถ inject APP_KEY ในเวลา build แทนที่จะฝังไว้ในซอร์สโค้ด

### สร้างด้วยโหมด --dart-define

``` bash
metro make:env --dart-define
```

คำสั่งนี้จะสร้าง `env.g.dart` โดยไม่ฝัง APP_KEY

### Build ด้วยการ Inject APP_KEY

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Run
flutter run --dart-define=APP_KEY=your-secret-key
```

วิธีการนี้ทำให้ APP_KEY ไม่อยู่ในซอร์สโค้ดของคุณ ซึ่งมีประโยชน์สำหรับ:
- CI/CD pipeline ที่ secret ถูก inject เข้ามา
- โปรเจกต์ open source
- ความต้องการด้านความปลอดภัยที่สูงขึ้น

### แนวปฏิบัติที่ดี

1. **อย่า commit `.env` ไปยัง version control** - เพิ่มไว้ใน `.gitignore`
2. **ใช้ `.env-example`** - commit เทมเพลตที่ไม่มีค่าที่ละเอียดอ่อน
3. **สร้างใหม่หลังแก้ไข** - รัน `metro make:env --force` ทุกครั้งหลังแก้ไข `.env`
4. **ใช้คีย์ที่แตกต่างกันในแต่ละสภาพแวดล้อม** - ใช้ APP_KEY ที่ไม่ซ้ำกันสำหรับ development, staging และ production
