# Providers

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [สร้าง provider](#create-a-provider "สร้าง provider")
- [อ็อบเจกต์ Provider](#provider-object "อ็อบเจกต์ Provider")


<div id="introduction"></div>

## บทนำเกี่ยวกับ Providers

ใน {{ config('app.name') }} providers จะถูกบูตเริ่มต้นจากไฟล์ <b>main.dart</b> ของคุณเมื่อแอปพลิเคชันทำงาน providers ทั้งหมดของคุณอยู่ใน `/lib/app/providers/*` คุณสามารถแก้ไขไฟล์เหล่านี้หรือสร้าง providers ของคุณโดยใช้ <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a>

Providers สามารถใช้เมื่อคุณต้องเริ่มต้นคลาส แพ็คเกจ หรือสร้างบางอย่างก่อนที่แอปจะโหลดเริ่มต้น เช่น คลาส `route_provider.dart` มีหน้าที่เพิ่มเส้นทางทั้งหมดให้กับ {{ config('app.name') }}

### เจาะลึก

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/7.x

/// Main entry point for the application.
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // Uncomment showSplashScreen to show the splash screen
    // File: lib/resources/widgets/splash_screen.dart
  );
}
```

### วงจรชีวิต

- `Boot.{{ config('app.name') }}` จะวนลูปผ่าน providers ที่ลงทะเบียนไว้ในไฟล์ <b>config/providers.dart</b> และบูตพวกมัน

- `Boot.Finished` จะถูกเรียกทันทีหลังจาก **"Boot.{{ config('app.name') }}"** เสร็จสิ้น เมธอดนี้จะผูกอินสแตนซ์ {{ config('app.name') }} กับ `Backpack` ด้วยค่า 'nylo'

เช่น Backpack.instance.read('nylo'); // อินสแตนซ์ {{ config('app.name') }}


<div id="create-a-provider"></div>

## สร้าง Provider ใหม่

คุณสามารถสร้าง providers ใหม่ได้โดยรันคำสั่งด้านล่างใน terminal

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## อ็อบเจกต์ Provider

Provider ของคุณจะมีสองเมธอด `setup(Nylo nylo)` และ `boot(Nylo nylo)`

เมื่อแอปทำงานเป็นครั้งแรก โค้ดใดๆ ภายในเมธอด **setup** จะถูกเรียกทำงานก่อน คุณยังสามารถจัดการอ็อบเจกต์ `Nylo` ได้เหมือนในตัวอย่างด้านล่าง

ตัวอย่าง: `lib/app/providers/app_provider.dart`

```dart
class AppProvider extends NyProvider {

  @override
  Future<Nylo?> setup(Nylo nylo) async {
    await NyLocalization.instance.init(
        localeType: localeType,
        languageCode: languageCode,
        languagesList: languagesList,
        assetsDirectory: assetsDirectory,
        valuesAsMap: valuesAsMap);

    return nylo;
  }

  @override
  Future<void> boot(Nylo nylo) async {
    User user = await Auth.user();
    if (!user.isSubscribed) {
      await Auth.remove();
    }
  }
}
```

### วงจรชีวิต

1. `setup(Nylo nylo)` - เริ่มต้น provider ของคุณ ส่งคืนอินสแตนซ์ `Nylo` หรือ `null`
2. `boot(Nylo nylo)` - เรียกหลังจาก providers ทั้งหมดทำ setup เสร็จ ใช้สำหรับการเริ่มต้นที่ขึ้นอยู่กับ providers อื่นที่พร้อมแล้ว

> ภายในเมธอด `setup` คุณต้อง **ส่งคืน** อินสแตนซ์ของ `Nylo` หรือ `null` เหมือนตัวอย่างข้างต้น
