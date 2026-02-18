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

// Save a value
Backpack.instance.save("user_name", "Anthony");

// Read a value (synchronous)
String? name = Backpack.instance.read("user_name");

// Delete a value
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## การใช้งานพื้นฐาน

Backpack ใช้ **รูปแบบ singleton** -- เข้าถึงผ่าน `Backpack.instance`:

``` dart
// Save data
Backpack.instance.save("theme", "dark");

// Read data
String? theme = Backpack.instance.read("theme"); // "dark"

// Check if data exists
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## การอ่านข้อมูล

อ่านค่าจาก Backpack โดยใช้เมธอด `read<T>()` รองรับ generic types และค่าเริ่มต้นที่เป็นตัวเลือก:

``` dart
// Read a String
String? name = Backpack.instance.read<String>("name");

// Read with a default value
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Read an int
int? score = Backpack.instance.read<int>("score");
```

Backpack จะ deserialize สตริง JSON เป็นอ็อบเจกต์ model โดยอัตโนมัติเมื่อระบุ type:

``` dart
// If a User model is stored as JSON, it will be deserialized
User? user = Backpack.instance.read<User>("current_user");
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
// Append to a list
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Append with a limit (keeps only the last N items)
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
// Using helper functions
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Access the Nylo instance
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## การรวมเข้ากับ NyStorage

Backpack รวมเข้ากับ `NyStorage` สำหรับการจัดเก็บแบบถาวร + ในหน่วยความจำร่วมกัน:

``` dart
// Save to both NyStorage (persistent) and Backpack (in-memory)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read("auth_token");

// When deleting from NyStorage, also clear from Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

รูปแบบนี้มีประโยชน์สำหรับข้อมูลเช่น token การยืนยันตัวตนที่ต้องการทั้งความถาวรและการเข้าถึงแบบ synchronous ที่รวดเร็ว (เช่น ใน HTTP interceptors)

<div id="examples"></div>

## ตัวอย่าง

### การจัดเก็บ Auth Token สำหรับคำขอ API

``` dart
// In your auth interceptor
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
// Add items to a cart session
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Read cart data
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Clear the cart
Backpack.instance.sessionFlush("cart");
```

### Feature Flags แบบรวดเร็ว

``` dart
// Store feature flags in Backpack for fast access
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Check a feature flag
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
