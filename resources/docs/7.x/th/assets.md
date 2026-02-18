# Assets

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับ assets")
- ไฟล์
  - [การแสดงรูปภาพ](#displaying-images "การแสดงรูปภาพ")
  - [เส้นทาง Asset แบบกำหนดเอง](#custom-asset-paths "เส้นทาง asset แบบกำหนดเอง")
  - [การส่งคืนเส้นทาง Asset](#returning-asset-paths "การส่งคืนเส้นทาง asset")
- การจัดการ Assets
  - [การเพิ่มไฟล์ใหม่](#adding-new-files "การเพิ่มไฟล์ใหม่")
  - [การกำหนดค่า Asset](#asset-configuration "การกำหนดค่า asset")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} v7 มีเมธอดช่วยเหลือสำหรับจัดการ assets ในแอป Flutter ของคุณ Assets ถูกจัดเก็บในไดเรกทอรี `assets/` และรวมถึงรูปภาพ, วิดีโอ, ฟอนต์ และไฟล์อื่นๆ

โครงสร้าง asset เริ่มต้น:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## การแสดงรูปภาพ

ใช้ widget `LocalAsset()` เพื่อแสดงรูปภาพจาก assets:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

ทั้งสองวิธีส่งคืนเส้นทาง asset แบบเต็มรวมถึงไดเรกทอรี asset ที่กำหนดค่าไว้

<div id="custom-asset-paths"></div>

## เส้นทาง Asset แบบกำหนดเอง

เพื่อรองรับไดเรกทอรีย่อย asset ที่แตกต่างกัน คุณสามารถเพิ่ม constructor แบบกำหนดเองให้กับ widget `LocalAsset`

``` dart
// /resources/widgets/local_asset_widget.dart
class LocalAsset extends StatelessWidget {
  // images
  const LocalAsset.image(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/$assetName";

  // icons <- new constructor for icons folder
  const LocalAsset.icons(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/icons/$assetName";
}

// Usage examples
LocalAsset.icons("icon_name.png", width: 32, height: 32)
LocalAsset.image("logo.png", width: 100, height: 100)
```

<div id="returning-asset-paths"></div>

## การส่งคืนเส้นทาง Asset

ใช้ `getAsset()` สำหรับไฟล์ประเภทใดก็ได้ในไดเรกทอรี `assets/`:

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### การใช้กับ Widget ต่างๆ

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## การเพิ่มไฟล์ใหม่

1. วางไฟล์ของคุณในไดเรกทอรีย่อยที่เหมาะสมของ `assets/`:
   - รูปภาพ: `assets/images/`
   - วิดีโอ: `assets/videos/`
   - ฟอนต์: `assets/fonts/`
   - อื่นๆ: `assets/data/` หรือโฟลเดอร์ที่กำหนดเอง

2. ตรวจสอบให้แน่ใจว่าโฟลเดอร์ถูกระบุใน `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## การกำหนดค่า Asset

{{ config('app.name') }} v7 กำหนดค่าเส้นทาง asset ผ่านตัวแปรสภาพแวดล้อม `ASSET_PATH` ในไฟล์ `.env` ของคุณ:

``` bash
ASSET_PATH="assets"
```

ฟังก์ชันช่วยเหลือจะเพิ่มเส้นทางนี้นำหน้าโดยอัตโนมัติ ดังนั้นคุณไม่จำเป็นต้องระบุ `assets/` ในการเรียกของคุณ:

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### การเปลี่ยนเส้นทางหลัก

หากคุณต้องการโครงสร้าง asset ที่แตกต่าง ให้อัปเดต `ASSET_PATH` ในไฟล์ `.env` ของคุณ:

``` bash
# Use a different base folder
ASSET_PATH="res"
```

หลังจากเปลี่ยนแล้ว ให้สร้างการกำหนดค่าสภาพแวดล้อมใหม่:

``` bash
metro make:env --force
```
