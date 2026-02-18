# Cache

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- พื้นฐาน
  - [บันทึกพร้อมเวลาหมดอายุ](#save-with-expiration "บันทึกพร้อมเวลาหมดอายุ")
  - [บันทึกถาวร](#save-forever "บันทึกถาวร")
  - [ดึงข้อมูล](#retrieve-data "ดึงข้อมูล")
  - [จัดเก็บข้อมูลโดยตรง](#store-data-directly "จัดเก็บข้อมูลโดยตรง")
  - [ลบข้อมูล](#remove-data "ลบข้อมูล")
  - [ตรวจสอบแคช](#check-cache "ตรวจสอบแคช")
- เครือข่าย
  - [การแคชการตอบกลับ API](#caching-api-responses "การแคชการตอบกลับ API")
- [การรองรับแพลตฟอร์ม](#platform-support "การรองรับแพลตฟอร์ม")
- [อ้างอิง API](#api-reference "อ้างอิง API")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} v7 มีระบบแคชแบบไฟล์สำหรับการจัดเก็บและดึงข้อมูลอย่างมีประสิทธิภาพ การแคชมีประโยชน์สำหรับการจัดเก็บข้อมูลที่มีต้นทุนสูงเช่นการตอบกลับ API หรือผลลัพธ์ที่คำนวณแล้ว

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Cache a value for 60 seconds
String? value = await cache().saveRemember("my_key", 60, () {
  return "Hello World";
});

// Retrieve the cached value
String? cached = await cache().get("my_key");

// Remove from cache
await cache().clear("my_key");
```

<div id="save-with-expiration"></div>

## บันทึกพร้อมเวลาหมดอายุ

ใช้ `saveRemember` เพื่อแคชค่าพร้อมเวลาหมดอายุ:

``` dart
String key = "user_profile";
int seconds = 300; // 5 minutes

Map<String, dynamic>? profile = await cache().saveRemember(key, seconds, () async {
  // This callback only runs on cache miss
  printInfo("Fetching from API...");
  return await api<UserApiService>((request) => request.getProfile());
});
```

ในการเรียกครั้งถัดไปภายในช่วงเวลาหมดอายุ ค่าที่แคชจะถูกส่งคืนโดยไม่เรียกใช้ callback

<div id="save-forever"></div>

## บันทึกถาวร

ใช้ `saveForever` เพื่อแคชข้อมูลอย่างไม่มีกำหนด:

``` dart
String key = "app_config";

Map<String, dynamic>? config = await cache().saveForever(key, () async {
  return await api<ConfigApiService>((request) => request.getConfig());
});
```

ข้อมูลจะยังคงถูกแคชจนกว่าจะถูกลบอย่างชัดเจนหรือแคชของแอปถูกล้าง

<div id="retrieve-data"></div>

## ดึงข้อมูล

ดึงค่าที่แคชโดยตรง:

``` dart
// Retrieve cached value
String? value = await cache().get<String>("my_key");

// With type casting
Map<String, dynamic>? data = await cache().get<Map<String, dynamic>>("user_data");

// Returns null if not found or expired
if (value == null) {
  print("Cache miss or expired");
}
```

หากรายการที่แคชหมดอายุแล้ว `get()` จะลบออกโดยอัตโนมัติและส่งคืน `null`

<div id="store-data-directly"></div>

## จัดเก็บข้อมูลโดยตรง

ใช้ `put` เพื่อจัดเก็บค่าโดยตรงโดยไม่ต้องใช้ callback:

``` dart
// Store with expiration
await cache().put("session_token", "abc123", seconds: 3600);

// Store forever (no seconds parameter)
await cache().put("device_id", "xyz789");
```

<div id="remove-data"></div>

## ลบข้อมูล

``` dart
// Remove a single item
await cache().clear("my_key");

// Remove all cached items
await cache().flush();
```

<div id="check-cache"></div>

## ตรวจสอบแคช

``` dart
// Check if a key exists
bool exists = await cache().has("my_key");

// Get all cached keys
List<String> keys = await cache().documents();

// Get total cache size in bytes
int sizeInBytes = await cache().size();
print("Cache size: ${sizeInBytes / 1024} KB");
```

<div id="caching-api-responses"></div>

## การแคชการตอบกลับ API

### การใช้ตัวช่วย api()

แคชการตอบกลับ API โดยตรง:

``` dart
Map<String, dynamic>? response = await api<ApiService>(
  (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
  cacheDuration: const Duration(minutes: 5),
  cacheKey: "github_repo_info",
);

printInfo(response);
```

### การใช้ NyApiService

กำหนดการแคชในเมธอดบริการ API ของคุณ:

``` dart
class ApiService extends NyApiService {

  Future<Map<String, dynamic>?> getRepoInfo() async {
    return await network(
      request: (request) => request.get("https://api.github.com/repos/nylo-core/nylo"),
      cacheKey: "github_repo_info",
      cacheDuration: const Duration(hours: 1),
    );
  }

  Future<List<User>?> getUsers() async {
    return await network<List<User>>(
      request: (request) => request.get("/users"),
      cacheKey: "users_list",
      cacheDuration: const Duration(minutes: 10),
    );
  }
}
```

จากนั้นเรียกเมธอด:

``` dart
Map<String, dynamic>? repoInfo = await api<ApiService>(
  (request) => request.getRepoInfo()
);
```

<div id="platform-support"></div>

## การรองรับแพลตฟอร์ม

แคชของ {{ config('app.name') }} ใช้การจัดเก็บแบบไฟล์และมีการรองรับแพลตฟอร์มดังนี้:

| แพลตฟอร์ม | การรองรับ |
|----------|---------|
| iOS | รองรับเต็มรูปแบบ |
| Android | รองรับเต็มรูปแบบ |
| macOS | รองรับเต็มรูปแบบ |
| Windows | รองรับเต็มรูปแบบ |
| Linux | รองรับเต็มรูปแบบ |
| Web | ไม่พร้อมใช้งาน |

บนแพลตฟอร์ม web แคชจะลดระดับอย่างราบรื่น -- callbacks จะถูกเรียกใช้เสมอและการแคชจะถูกข้าม

``` dart
// Check if cache is available
if (cache().isAvailable) {
  // Use caching
} else {
  // Web platform - cache not available
}
```

<div id="api-reference"></div>

## อ้างอิง API

### เมธอด

| เมธอด | คำอธิบาย |
|--------|-------------|
| `saveRemember<T>(key, seconds, callback)` | แคชค่าพร้อมเวลาหมดอายุ ส่งคืนค่าที่แคชหรือผลลัพธ์ callback |
| `saveForever<T>(key, callback)` | แคชค่าอย่างไม่มีกำหนด ส่งคืนค่าที่แคชหรือผลลัพธ์ callback |
| `get<T>(key)` | ดึงค่าที่แคช ส่งคืน `null` หากไม่พบหรือหมดอายุ |
| `put<T>(key, value, {seconds})` | จัดเก็บค่าโดยตรง เวลาหมดอายุเป็นวินาทีที่เป็นตัวเลือก |
| `clear(key)` | ลบรายการที่แคชเฉพาะ |
| `flush()` | ลบรายการที่แคชทั้งหมด |
| `has(key)` | ตรวจสอบว่าคีย์มีอยู่ในแคชหรือไม่ ส่งคืน `bool` |
| `documents()` | ดึงรายการคีย์แคชทั้งหมด ส่งคืน `List<String>` |
| `size()` | ดึงขนาดแคชทั้งหมดเป็นไบต์ ส่งคืน `int` |

### คุณสมบัติ

| คุณสมบัติ | ประเภท | คำอธิบาย |
|----------|------|-------------|
| `isAvailable` | `bool` | แคชพร้อมใช้งานบนแพลตฟอร์มปัจจุบันหรือไม่ |
