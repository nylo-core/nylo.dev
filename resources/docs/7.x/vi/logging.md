# Logging

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Các mức log](#log-levels "Các mức log")
- [Phương thức log](#log-methods "Phương thức log")
- [Log JSON](#json-logging "Log JSON")
- [Đầu ra có màu](#colored-output "Đầu ra có màu")
- [Log Listener](#log-listeners "Log Listener")
- [Extension helper](#helper-extensions "Extension helper")
- [Cấu hình](#configuration "Cấu hình")

<div id="introduction"></div>

## Giới thiệu

{{ config('app.name') }} v7 cung cấp hệ thống ghi log toàn diện.

Log chỉ được in khi `APP_DEBUG=true` trong file `.env`, giữ cho ứng dụng production sạch sẽ.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic logging
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## Các mức log

{{ config('app.name') }} v7 hỗ trợ nhiều mức log với đầu ra có màu:

| Mức | Phương thức | Màu | Trường hợp sử dụng |
|-----|-------------|-----|---------------------|
| Debug | `printDebug()` | Cyan | Thông tin gỡ lỗi chi tiết |
| Info | `printInfo()` | Xanh | Thông tin chung |
| Error | `printError()` | Đỏ | Lỗi và ngoại lệ |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

Ví dụ đầu ra:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## Phương thức log

### Ghi log cơ bản

``` dart
// Class methods
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### Lỗi với Stack Trace

Ghi log lỗi với stack trace để gỡ lỗi tốt hơn:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### Buộc in bất kể chế độ Debug

Sử dụng `alwaysPrint: true` để in ngay cả khi `APP_DEBUG=false`:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### Hiển thị log tiếp theo (ghi đè một lần)

In một log đơn lẻ khi `APP_DEBUG=false`:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Prints once

printInfo("This won't print again");
```

<div id="json-logging"></div>

## Log JSON

{{ config('app.name') }} v7 bao gồm phương thức ghi log JSON chuyên dụng:

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

## Đầu ra có màu

{{ config('app.name') }} v7 sử dụng màu ANSI cho đầu ra log ở chế độ debug. Mỗi mức log có màu riêng biệt để dễ nhận biết.

### Tắt màu

``` dart
// Disable colored output globally
NyLogger.useColors = false;
```

Màu được tự động tắt:
- Ở chế độ release
- Khi terminal không hỗ trợ mã ANSI escape

<div id="log-listeners"></div>

## Log Listener

{{ config('app.name') }} v7 cho phép bạn lắng nghe tất cả log entry theo thời gian thực:

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

### Thuộc tính NyLogEntry

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // The log message
  entry.type;       // Log level (debug, info, warning, error, success, verbose)
  entry.dateTime;   // When the log was created
  entry.stackTrace; // Stack trace (for errors)
};
```

### Trường hợp sử dụng

- Gửi lỗi đến dịch vụ báo cáo sự cố (Sentry, Firebase Crashlytics)
- Xây dựng trình xem log tùy chỉnh
- Lưu trữ log để gỡ lỗi
- Giám sát hành vi ứng dụng theo thời gian thực

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

## Extension helper

{{ config('app.name') }} cung cấp các phương thức extension tiện lợi cho ghi log:

### dump()

In bất kỳ giá trị nào ra console:

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

In giá trị và thoát ngay lập tức (hữu ích cho gỡ lỗi):

``` dart
String code = 'Dart';
code.dd(); // Prints 'Dart' and stops execution

// Function syntax
dd("Debug point reached");
```

<div id="configuration"></div>

## Cấu hình

### Biến môi trường

Kiểm soát hành vi ghi log trong file `.env`:

``` bash
# Enable/disable all logging
APP_DEBUG=true
```

### DateTime trong Log

{{ config('app.name') }} có thể bao gồm dấu thời gian trong đầu ra log. Cấu hình trong thiết lập Nylo:

``` dart
// In your boot provider
Nylo.instance.showDateTimeInLogs(true);
```

Đầu ra với dấu thời gian:
```
[2025-01-27 10:30:45] [info] User logged in
```

Đầu ra không có dấu thời gian:
```
[info] User logged in
```

### Thực hành tốt nhất

1. **Sử dụng mức log phù hợp** - Đừng ghi tất cả dưới dạng lỗi
2. **Loại bỏ log dài dòng trong production** - Giữ `APP_DEBUG=false` trong production
3. **Bao gồm ngữ cảnh** - Ghi dữ liệu liên quan để gỡ lỗi
4. **Sử dụng log có cấu trúc** - `NyLogger.json()` cho dữ liệu phức tạp
5. **Thiết lập giám sát lỗi** - Sử dụng `NyLogger.onLog` để bắt lỗi

``` dart
// Good logging practice
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```
