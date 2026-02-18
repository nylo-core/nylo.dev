# Logging

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [ระดับ Log](#log-levels "ระดับ Log")
- [เมธอด Log](#log-methods "เมธอด Log")
- [การบันทึก JSON](#json-logging "การบันทึก JSON")
- [ผลลัพธ์แบบมีสี](#colored-output "ผลลัพธ์แบบมีสี")
- [ตัวฟัง Log](#log-listeners "ตัวฟัง Log")
- [ส่วนขยายตัวช่วย](#helper-extensions "ส่วนขยายตัวช่วย")
- [การตั้งค่า](#configuration "การตั้งค่า")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} v7 มีระบบการบันทึก log ที่ครบถ้วน

Logs จะถูกพิมพ์เฉพาะเมื่อ `APP_DEBUG=true` ในไฟล์ `.env` ของคุณ เพื่อให้แอปที่ใช้งานจริงสะอาด

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic logging
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## ระดับ Log

{{ config('app.name') }} v7 รองรับระดับ log หลายระดับพร้อมผลลัพธ์แบบมีสี:

| ระดับ | เมธอด | สี | กรณีใช้งาน |
|-------|--------|-------|----------|
| Debug | `printDebug()` | ฟ้าอมเขียว | ข้อมูลการดีบักโดยละเอียด |
| Info | `printInfo()` | น้ำเงิน | ข้อมูลทั่วไป |
| Error | `printError()` | แดง | ข้อผิดพลาดและ exceptions |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

ตัวอย่างผลลัพธ์:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## เมธอด Log

### การบันทึกพื้นฐาน

``` dart
// Class methods
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### ข้อผิดพลาดพร้อม Stack Trace

บันทึกข้อผิดพลาดพร้อม stack traces เพื่อการดีบักที่ดีขึ้น:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### บังคับพิมพ์โดยไม่สนโหมด Debug

ใช้ `alwaysPrint: true` เพื่อพิมพ์แม้ `APP_DEBUG=false`:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### แสดง Log ถัดไป (การเขียนทับครั้งเดียว)

พิมพ์ log ครั้งเดียวเมื่อ `APP_DEBUG=false`:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Prints once

printInfo("This won't print again");
```

<div id="json-logging"></div>

## การบันทึก JSON

{{ config('app.name') }} v7 มีเมธอดการบันทึก JSON โดยเฉพาะ:

``` dart
Map<String, dynamic> userData = {
  "id": 123,
  "name": "Anthony",
  "email": "anthony@example.com"
};

// Compact JSON
printJson(userData);
// {"id":123,"name":"Anthony","email":"anthony@example.com"}

// Pretty printed JSON
printJson(userData, prettyPrint: true);
// {
//   "id": 123,
//   "name": "Anthony",
//   "email": "anthony@example.com"
// }
```

<div id="colored-output"></div>

## ผลลัพธ์แบบมีสี

{{ config('app.name') }} v7 ใช้สี ANSI สำหรับผลลัพธ์ log ในโหมด debug แต่ละระดับ log มีสีที่แตกต่างกันเพื่อให้ระบุได้ง่าย

### ปิดสี

``` dart
// Disable colored output globally
NyLogger.useColors = false;
```

สีจะถูกปิดโดยอัตโนมัติ:
- ในโหมด release
- เมื่อ terminal ไม่รองรับ ANSI escape codes

<div id="log-listeners"></div>

## ตัวฟัง Log

{{ config('app.name') }} v7 ช่วยให้คุณฟังรายการ log ทั้งหมดแบบเรียลไทม์:

``` dart
// Set up a log listener
NyLogger.onLog = (NyLogEntry entry) {
  print("Log: [${entry.type}] ${entry.message}");

  // Send to crash reporting service
  if (entry.type == 'error') {
    CrashReporter.log(entry.message, stackTrace: entry.stackTrace);
  }
};
```

### คุณสมบัติของ NyLogEntry

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // The log message
  entry.type;       // Log level (debug, info, warning, error, success, verbose)
  entry.dateTime;   // When the log was created
  entry.stackTrace; // Stack trace (for errors)
};
```

### กรณีใช้งาน

- ส่งข้อผิดพลาดไปยังบริการรายงานข้อผิดพลาด (Sentry, Firebase Crashlytics)
- สร้างตัวดู log ที่กำหนดเอง
- จัดเก็บ logs สำหรับการดีบัก
- ตรวจสอบพฤติกรรมแอปแบบเรียลไทม์

``` dart
// Example: Send errors to Sentry
NyLogger.onLog = (entry) {
  if (entry.type == 'error') {
    Sentry.captureMessage(
      entry.message,
      level: SentryLevel.error,
    );
  }
};
```

<div id="helper-extensions"></div>

## ส่วนขยายตัวช่วย

{{ config('app.name') }} มีเมธอดส่วนขยายที่สะดวกสำหรับการบันทึก:

### dump()

พิมพ์ค่าใดก็ได้ไปยังคอนโซล:

``` dart
String project = 'Nylo';
project.dump(); // 'Nylo'

List<String> seasons = ['Spring', 'Summer', 'Fall', 'Winter'];
seasons.dump(); // ['Spring', 'Summer', 'Fall', 'Winter']

int age = 25;
age.dump(); // 25

// Function syntax
dump("Hello World");
```

### dd() - Dump and Die

พิมพ์ค่าและหยุดทำงานทันที (มีประโยชน์สำหรับการดีบัก):

``` dart
String code = 'Dart';
code.dd(); // Prints 'Dart' and stops execution

// Function syntax
dd("Debug point reached");
```

<div id="configuration"></div>

## การตั้งค่า

### ตัวแปรสภาพแวดล้อม

ควบคุมพฤติกรรมการบันทึกในไฟล์ `.env` ของคุณ:

``` bash
# Enable/disable all logging
APP_DEBUG=true
```

### DateTime ใน Logs

{{ config('app.name') }} สามารถรวม timestamps ในผลลัพธ์ log ตั้งค่านี้ในการตั้งค่า Nylo ของคุณ:

``` dart
// In your boot provider
Nylo.instance.showDateTimeInLogs(true);
```

ผลลัพธ์พร้อม timestamps:
```
[2025-01-27 10:30:45] [info] User logged in
```

ผลลัพธ์ไม่มี timestamps:
```
[info] User logged in
```

### แนวทางปฏิบัติที่ดี

1. **ใช้ระดับ log ที่เหมาะสม** - อย่าบันทึกทุกอย่างเป็นข้อผิดพลาด
2. **ลบ logs ที่มากเกินไปในการใช้งานจริง** - ตั้ง `APP_DEBUG=false` ในการใช้งานจริง
3. **รวมบริบท** - บันทึกข้อมูลที่เกี่ยวข้องสำหรับการดีบัก
4. **ใช้การบันทึกแบบมีโครงสร้าง** - `NyLogger.json()` สำหรับข้อมูลที่ซับซ้อน
5. **ตั้งค่าการตรวจสอบข้อผิดพลาด** - ใช้ `NyLogger.onLog` เพื่อจับข้อผิดพลาด

``` dart
// Good logging practice
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```
