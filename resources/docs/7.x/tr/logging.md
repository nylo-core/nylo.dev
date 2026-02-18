# Günlükleme

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Günlük Seviyeleri](#log-levels "Günlük Seviyeleri")
- [Günlük Metotları](#log-methods "Günlük Metotları")
- [JSON Günlükleme](#json-logging "JSON Günlükleme")
- [Renkli Çıktı](#colored-output "Renkli Çıktı")
- [Günlük Dinleyicileri](#log-listeners "Günlük Dinleyicileri")
- [Yardımcı Uzantılar](#helper-extensions "Yardımcı Uzantılar")
- [Yapılandırma](#configuration "Yapılandırma")

<div id="introduction"></div>

## Giriş

{{ config('app.name') }} v7 kapsamlı bir günlükleme sistemi sağlar.

Günlükler yalnızca `.env` dosyanızda `APP_DEBUG=true` olduğunda yazdırılır, böylece üretim uygulamaları temiz kalır.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic logging
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## Günlük Seviyeleri

{{ config('app.name') }} v7, renkli çıktıyla birden fazla günlük seviyesini destekler:

| Seviye | Metot | Renk | Kullanım Alanı |
|-------|--------|-------|----------|
| Debug | `printDebug()` | Cyan | Ayrıntılı hata ayıklama bilgisi |
| Info | `printInfo()` | Mavi | Genel bilgi |
| Error | `printError()` | Kırmızı | Hatalar ve istisnalar |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

Çıktı örneği:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## Günlük Metotları

### Temel Günlükleme

``` dart
// Class methods
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### Yığın İzlemeli Hata

Daha iyi hata ayıklama için yığın izlemeleriyle hataları günlüğe kaydedin:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### Hata Ayıklama Modundan Bağımsız Zorunlu Yazdırma

`APP_DEBUG=false` olduğunda bile yazdırmak için `alwaysPrint: true` kullanın:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### Sonraki Günlüğü Göster (Tek Seferlik Geçersiz Kılma)

`APP_DEBUG=false` durumunda tek bir günlük yazdırın:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Prints once

printInfo("This won't print again");
```

<div id="json-logging"></div>

## JSON Günlükleme

{{ config('app.name') }} v7, özel bir JSON günlükleme metodu içerir:

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

## Renkli Çıktı

{{ config('app.name') }} v7, hata ayıklama modunda günlük çıktısı için ANSI renkleri kullanır. Her günlük seviyesinin kolay tanımlama için farklı bir rengi vardır.

### Renkleri Devre Dışı Bırakma

``` dart
// Disable colored output globally
NyLogger.useColors = false;
```

Renkler otomatik olarak devre dışı bırakılır:
- Sürüm modunda
- Terminal ANSI kaçış kodlarını desteklemediğinde

<div id="log-listeners"></div>

## Günlük Dinleyicileri

{{ config('app.name') }} v7, tüm günlük girişlerini gerçek zamanlı olarak dinlemenize olanak tanır:

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

### NyLogEntry Özellikleri

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // The log message
  entry.type;       // Log level (debug, info, warning, error, success, verbose)
  entry.dateTime;   // When the log was created
  entry.stackTrace; // Stack trace (for errors)
};
```

### Kullanım Alanları

- Hataları çökme raporlama servislerine gönderme (Sentry, Firebase Crashlytics)
- Özel günlük görüntüleyicileri oluşturma
- Hata ayıklama için günlükleri saklama
- Uygulama davranışını gerçek zamanlı izleme

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

## Yardımcı Uzantılar

{{ config('app.name') }}, günlükleme için kullanışlı uzantı metotları sağlar:

### dump()

Herhangi bir değeri konsola yazdırın:

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

### dd() - Yazdır ve Dur

Bir değeri yazdırın ve hemen durdurun (hata ayıklama için kullanışlıdır):

``` dart
String code = 'Dart';
code.dd(); // Prints 'Dart' and stops execution

// Function syntax
dd("Debug point reached");
```

<div id="configuration"></div>

## Yapılandırma

### Ortam Değişkenleri

`.env` dosyanızda günlükleme davranışını kontrol edin:

``` bash
# Enable/disable all logging
APP_DEBUG=true
```

### Günlüklerde TarihSaat

{{ config('app.name') }}, günlük çıktısına zaman damgaları ekleyebilir. Bunu Nylo kurulumunuzda yapılandırın:

``` dart
// In your boot provider
Nylo.instance.showDateTimeInLogs(true);
```

Zaman damgalı çıktı:
```
[2025-01-27 10:30:45] [info] User logged in
```

Zaman damgasız çıktı:
```
[info] User logged in
```

### En İyi Uygulamalar

1. **Uygun günlük seviyelerini kullanın** - Her şeyi hata olarak günlüklemeyin
2. **Üretimde ayrıntılı günlükleri kaldırın** - Üretimde `APP_DEBUG=false` tutun
3. **Bağlam ekleyin** - Hata ayıklama için ilgili verileri günlükleyin
4. **Yapılandırılmış günlükleme kullanın** - Karmaşık veriler için `NyLogger.json()`
5. **Hata izleme kurun** - Hataları yakalamak için `NyLogger.onLog` kullanın

``` dart
// Good logging practice
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```

