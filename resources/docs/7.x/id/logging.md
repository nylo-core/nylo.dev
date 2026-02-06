# Logging

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Level Log](#log-levels "Level log")
- [Method Log](#log-methods "Method log")
- [Logging JSON](#json-logging "Logging JSON")
- [Output Berwarna](#colored-output "Output berwarna")
- [Listener Log](#log-listeners "Listener log")
- [Extension Helper](#helper-extensions "Extension helper")
- [Konfigurasi](#configuration "Konfigurasi")

<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} v7 menyediakan sistem logging yang komprehensif.

Log hanya dicetak ketika `APP_DEBUG=true` di file `.env` Anda, menjaga aplikasi produksi tetap bersih.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic logging
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## Level Log

{{ config('app.name') }} v7 mendukung beberapa level log dengan output berwarna:

| Level | Method | Warna | Kasus Penggunaan |
|-------|--------|-------|------------------|
| Debug | `printDebug()` | Cyan | Info debugging detail |
| Info | `printInfo()` | Biru | Informasi umum |
| Error | `printError()` | Merah | Error dan exception |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

Contoh output:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## Method Log

### Logging Dasar

``` dart
// Class methods
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### Error dengan Stack Trace

Log error dengan stack trace untuk debugging yang lebih baik:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### Paksa Cetak Terlepas dari Mode Debug

Gunakan `alwaysPrint: true` untuk mencetak bahkan ketika `APP_DEBUG=false`:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### Tampilkan Log Berikutnya (Override Sekali Pakai)

Cetak satu log ketika `APP_DEBUG=false`:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Prints once

printInfo("This won't print again");
```

<div id="json-logging"></div>

## Logging JSON

{{ config('app.name') }} v7 menyertakan method logging JSON khusus:

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

## Output Berwarna

{{ config('app.name') }} v7 menggunakan warna ANSI untuk output log dalam mode debug. Setiap level log memiliki warna yang berbeda untuk identifikasi mudah.

### Menonaktifkan Warna

``` dart
// Disable colored output globally
NyLogger.useColors = false;
```

Warna secara otomatis dinonaktifkan:
- Dalam mode release
- Ketika terminal tidak mendukung kode escape ANSI

<div id="log-listeners"></div>

## Listener Log

{{ config('app.name') }} v7 memungkinkan Anda mendengarkan semua entri log secara real-time:

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

### Properti NyLogEntry

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // The log message
  entry.type;       // Log level (debug, info, warning, error, success, verbose)
  entry.dateTime;   // When the log was created
  entry.stackTrace; // Stack trace (for errors)
};
```

### Kasus Penggunaan

- Kirim error ke layanan pelaporan crash (Sentry, Firebase Crashlytics)
- Bangun penampil log kustom
- Simpan log untuk debugging
- Pantau perilaku aplikasi secara real-time

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

## Extension Helper

{{ config('app.name') }} menyediakan method extension yang praktis untuk logging:

### dump()

Cetak nilai apa pun ke konsol:

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

Cetak nilai dan langsung keluar (berguna untuk debugging):

``` dart
String code = 'Dart';
code.dd(); // Prints 'Dart' and stops execution

// Function syntax
dd("Debug point reached");
```

<div id="configuration"></div>

## Konfigurasi

### Variabel Environment

Kontrol perilaku logging di file `.env` Anda:

``` bash
# Enable/disable all logging
APP_DEBUG=true
```

### DateTime di Log

{{ config('app.name') }} dapat menyertakan timestamp di output log. Konfigurasikan ini di setup Nylo Anda:

``` dart
// In your boot provider
Nylo.instance.showDateTimeInLogs(true);
```

Output dengan timestamp:
```
[2025-01-27 10:30:45] [info] User logged in
```

Output tanpa timestamp:
```
[info] User logged in
```

### Praktik Terbaik

1. **Gunakan level log yang sesuai** - Jangan log semua sebagai error
2. **Hapus log verbose di produksi** - Tetap `APP_DEBUG=false` di produksi
3. **Sertakan konteks** - Log data yang relevan untuk debugging
4. **Gunakan logging terstruktur** - `NyLogger.json()` untuk data kompleks
5. **Siapkan monitoring error** - Gunakan `NyLogger.onLog` untuk menangkap error

``` dart
// Good logging practice
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```

