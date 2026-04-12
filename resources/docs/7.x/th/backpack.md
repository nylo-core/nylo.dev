# Backpack

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การใช้งานพื้นฐาน](#basic-usage "การใช้งานพื้นฐาน")
- [การอ่านข้อมูล](#reading-data "การอ่านข้อมูล")
- [การบันทึกข้อมูล](#saving-data "การบันทึกข้อมูล")
- [การลบข้อมูล](#deleting-data "การลบข้อมูล")
- [เซสชัน](#sessions "เซสชัน")
- [การเข้าถึงอินสแตนซ์ Nylo](#nylo-instance "การเข้าถึงอินสแตนซ์ Nylo")
- [ฟังก์ชันช่วยเหลือ](#helper-functions "ฟังก์ชันช่วยเหลือ")
- [การรวมเข้ากับ NyStorage](#integration-with-nystorage "การรวมเข้ากับ NyStorage")
- [ตัวอย่าง](#examples "ตัวอย่างเชิงปฏิบัติ")

<div id="introduction"></div>

## บทนำ

**Backpack** เป็นระบบจัดเก็บ singleton ในหน่วยความจำใน {{ config('app.name') }} ให้การเข้าถึงข้อมูลแบบ synchronous ที่รวดเร็วระหว่างการทำงานของแอป ต่างจาก `NyStorage` ที่เก็บข้อมูลถาวรลงอุปกรณ์ Backpack จัดเก็บข้อมูลในหน่วยความจำและจะถูกล้างเมื่อปิดแอป

Backpack ถูกใช้ภายในโดยเฟรมเวิร์กเพื่อจัดเก็บอินสแตนซ์ที่สำคัญเช่น อ็อบเจกต์แอป `Nylo`, `EventBus` และข้อมูลการยืนยันตัวตน คุณยังสามารถใช้เพื่อจัดเก็บข้อมูลของคุณเองที่ต้องการเข้าถึงอย่างรวดเร็วโดยไม่ต้องเรียก async

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// บันทึกค่า
Backpack.instance.save("user_name", "Anthony");

// อ่านค่า (synchronous)
String? name = Backpack.instance.read("user_name");

// ลบค่า
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## การใช้งานพื้นฐาน

Backpack ใช้ **รูปแบบ singleton** -- เข้าถึงผ่าน `Backpack.instance`:

``` dart
// บันทึกข้อมูล
Backpack.instance.save("theme", "dark");

// อ่านข้อมูล
String? theme = Backpack.instance.read("theme"); // "dark"

// ตรวจสอบว่ามีข้อมูลหรือไม่
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## การอ่านข้อมูล

อ่านค่าจาก Backpack โดยใช้เมธอด `read<T>()` รองรับ generic types และค่าเริ่มต้นที่เป็นตัวเลือก:

``` dart
// อ่าน String
String? name = Backpack.instance.read<String>("name");

// อ่านพร้อมค่าเริ่มต้น
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// อ่าน int
int? score = Backpack.instance.read<int>("score");
```

Backpack จะ deserialize ค่าที่จัดเก็บเป็นอ็อบเจกต์ model โดยอัตโนมัติเมื่อระบุ type ซึ่งใช้ได้กับทั้งสตริง JSON และค่า `Map<String, dynamic>` ดิบ:

``` dart
// ถ้า model User ถูกจัดเก็บเป็นสตริง JSON จะถูก deserialize
User? user = Backpack.instance.read<User>("current_user");

// ถ้า Map ดิบถูกจัดเก็บ (เช่น ผ่าน syncKeys จาก NyStorage) ก็จะ
// ถูก deserialize เป็น model ที่มี type โดยอัตโนมัติเมื่ออ่าน
Backpack.instance.save("current_user", {"name": "Alice", "age": 30});
User? user = Backpack.instance.read<User>("current_user"); // คืนค่า User
```

<div id="saving-data"></div>

## การบันทึกข้อมูล

บันทึกค่าโดยใช้เมธอด `save()`:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### การเพิ่มข้อมูลต่อท้าย

ใช้ `append()` เพื่อเพิ่มค่าลงในรายการที่จัดเก็บไว้ที่คีย์:

``` dart
// เพิ่มต่อท้ายรายการ
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// เพิ่มพร้อมกำหนดขีดจำกัด (เก็บเฉพาะ N รายการสุดท้าย)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## การลบข้อมูล

### ลบคีย์เดียว

``` dart
Backpack.instance.delete("api_token");
```

### ลบข้อมูลทั้งหมด

เมธอด `deleteAll()` ลบค่าทั้งหมด **ยกเว้น** คีย์สงวนของเฟรมเวิร์ก (`nylo` และ `event_bus`):

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## เซสชัน

Backpack มีการจัดการเซสชันสำหรับจัดระเบียบข้อมูลเป็นกลุ่มที่มีชื่อ สิ่งนี้มีประโยชน์สำหรับการจัดเก็บข้อมูลที่เกี่ยวข้องกันไว้ด้วยกัน

### อัปเดตค่าเซสชัน

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### ดึงค่าเซสชัน

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### ลบคีย์เซสชัน

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### ล้างเซสชันทั้งหมด

``` dart
Backpack.instance.sessionFlush("cart");
```

### ดึงข้อมูลเซสชันทั้งหมด

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## การเข้าถึงอินสแตนซ์ Nylo

Backpack จัดเก็บอินสแตนซ์แอปพลิเคชัน `Nylo` คุณสามารถดึงได้โดยใช้:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

ตรวจสอบว่าอินสแตนซ์ Nylo ได้ถูกเริ่มต้นแล้วหรือไม่:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## ฟังก์ชันช่วยเหลือ

{{ config('app.name') }} มีฟังก์ชันช่วยเหลือแบบ global สำหรับการดำเนินการ Backpack ทั่วไป:

| ฟังก์ชัน | คำอธิบาย |
|----------|-------------|
| `backpackRead<T>(key)` | อ่านค่าจาก Backpack |
| `backpackSave(key, value)` | บันทึกค่าลงใน Backpack |
| `backpackDelete(key)` | ลบค่าจาก Backpack |
| `backpackDeleteAll()` | ลบค่าทั้งหมด (รักษาคีย์เฟรมเวิร์ก) |
| `backpackNylo()` | ดึงอินสแตนซ์ Nylo จาก Backpack |

### ตัวอย่าง

``` dart
// การใช้ฟังก์ชันช่วยเหลือ
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// เข้าถึงอินสแตนซ์ Nylo
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## การรวมเข้ากับ NyStorage

Backpack รวมเข้ากับ `NyStorage` สำหรับการจัดเก็บแบบถาวร + ในหน่วยความจำร่วมกัน:

``` dart
// บันทึกลงทั้ง NyStorage (ถาวร) และ Backpack (ในหน่วยความจำ)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// เข้าถึงได้แบบ synchronous ผ่าน Backpack
String? token = Backpack.instance.read("auth_token");

// เมื่อลบจาก NyStorage ให้ล้างจาก Backpack ด้วย
await NyStorage.deleteAll(andFromBackpack: true);
```

รูปแบบนี้มีประโยชน์สำหรับข้อมูลเช่น token การยืนยันตัวตนที่ต้องการทั้งความถาวรและการเข้าถึงแบบ synchronous ที่รวดเร็ว (เช่น ใน HTTP interceptors)

<div id="examples"></div>

## ตัวอย่าง

### การจัดเก็บ Auth Token สำหรับคำขอ API

``` dart
// ใน auth interceptor ของคุณ
class BearerAuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    String? userToken = Backpack.instance.read(StorageKeysConfig.auth);

    if (userToken != null) {
      options.headers.addAll({"Authorization": "Bearer $userToken"});
    }

    return super.onRequest(options, handler);
  }
}
```

### การจัดการตะกร้าแบบเซสชัน

``` dart
// เพิ่มรายการลงในเซสชันตะกร้า
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// อ่านข้อมูลตะกร้า
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// ล้างตะกร้า
Backpack.instance.sessionFlush("cart");
```

### Feature Flags แบบรวดเร็ว

``` dart
// จัดเก็บ feature flags ใน Backpack เพื่อเข้าถึงอย่างรวดเร็ว
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// ตรวจสอบ feature flag
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
