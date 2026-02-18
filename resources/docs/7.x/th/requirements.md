# Requirements

---

<a name="section-1"></a>
- [ความต้องการของระบบ](#system-requirements "ความต้องการของระบบ")
- [การติดตั้ง Flutter](#installing-flutter "การติดตั้ง Flutter")
- [การตรวจสอบการติดตั้งของคุณ](#verifying-installation "การตรวจสอบการติดตั้งของคุณ")
- [ตั้งค่าโปรแกรมแก้ไขโค้ด](#set-up-an-editor "ตั้งค่าโปรแกรมแก้ไขโค้ด")


<div id="system-requirements"></div>

## ความต้องการของระบบ

{{ config('app.name') }} v7 ต้องการเวอร์ชันขั้นต่ำดังต่อไปนี้:

| ความต้องการ | เวอร์ชันขั้นต่ำ |
|-------------|-----------------|
| **Flutter** | 3.24.0 หรือสูงกว่า |
| **Dart SDK** | 3.10.7 หรือสูงกว่า |

### การรองรับแพลตฟอร์ม

{{ config('app.name') }} รองรับทุกแพลตฟอร์มที่ Flutter รองรับ:

| แพลตฟอร์ม | การรองรับ |
|----------|---------|
| iOS | ✓ รองรับเต็มรูปแบบ |
| Android | ✓ รองรับเต็มรูปแบบ |
| Web | ✓ รองรับเต็มรูปแบบ |
| macOS | ✓ รองรับเต็มรูปแบบ |
| Windows | ✓ รองรับเต็มรูปแบบ |
| Linux | ✓ รองรับเต็มรูปแบบ |

<div id="installing-flutter"></div>

## การติดตั้ง Flutter

หากคุณยังไม่ได้ติดตั้ง Flutter ให้ทำตามคู่มือการติดตั้งอย่างเป็นทางการสำหรับระบบปฏิบัติการของคุณ:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">คู่มือการติดตั้ง Flutter</a>

<div id="verifying-installation"></div>

## การตรวจสอบการติดตั้งของคุณ

หลังจากติดตั้ง Flutter แล้ว ให้ตรวจสอบการตั้งค่าของคุณ:

### ตรวจสอบเวอร์ชัน Flutter

``` bash
flutter --version
```

คุณควรเห็นผลลัพธ์คล้ายกับ:

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### อัปเดต Flutter (หากจำเป็น)

หากเวอร์ชัน Flutter ของคุณต่ำกว่า 3.24.0 ให้อัปเกรดเป็นรุ่นเสถียรล่าสุด:

``` bash
flutter channel stable
flutter upgrade
```

### เรียกใช้ Flutter Doctor

ตรวจสอบว่าสภาพแวดล้อมการพัฒนาของคุณตั้งค่าได้อย่างถูกต้อง:

``` bash
flutter doctor -v
```

คำสั่งนี้ตรวจสอบ:
- การติดตั้ง Flutter SDK
- Android toolchain (สำหรับการพัฒนา Android)
- Xcode (สำหรับการพัฒนา iOS/macOS)
- อุปกรณ์ที่เชื่อมต่อ
- ปลั๊กอิน IDE

แก้ไขปัญหาที่รายงานก่อนดำเนินการติดตั้ง {{ config('app.name') }}

<div id="set-up-an-editor"></div>

## ตั้งค่าโปรแกรมแก้ไขโค้ด

เลือก IDE ที่รองรับ Flutter:

### Visual Studio Code (แนะนำ)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> เบาและรองรับ Flutter ได้อย่างยอดเยี่ยม

1. ติดตั้ง <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>
2. ติดตั้ง <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">ส่วนขยาย Flutter</a>
3. ติดตั้ง <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">ส่วนขยาย Dart</a>

คู่มือการตั้งค่า: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">การตั้งค่า Flutter สำหรับ VS Code</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> เป็น IDE ที่มีฟีเจอร์ครบถ้วนพร้อมการรองรับ emulator ในตัว

1. ติดตั้ง <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>
2. ติดตั้งปลั๊กอิน Flutter (Preferences → Plugins → Flutter)
3. ติดตั้งปลั๊กอิน Dart

คู่มือการตั้งค่า: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">การตั้งค่า Flutter สำหรับ Android Studio</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community หรือ Ultimate) ก็รองรับการพัฒนา Flutter เช่นกัน

1. ติดตั้ง IntelliJ IDEA
2. ติดตั้งปลั๊กอิน Flutter (Preferences → Plugins → Flutter)
3. ติดตั้งปลั๊กอิน Dart

เมื่อตั้งค่าโปรแกรมแก้ไขโค้ดของคุณเรียบร้อยแล้ว คุณพร้อมที่จะ[ติดตั้ง {{ config('app.name') }}](/docs/7.x/installation)
