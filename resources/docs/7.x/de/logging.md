# Logging

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Log-Level](#log-levels "Log-Level")
- [Log-Methoden](#log-methods "Log-Methoden")
- [JSON-Logging](#json-logging "JSON-Logging")
- [Farbige Ausgabe](#colored-output "Farbige Ausgabe")
- [Log-Listener](#log-listeners "Log-Listener")
- [Hilfs-Extensions](#helper-extensions "Hilfs-Extensions")
- [Konfiguration](#configuration "Konfiguration")

<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} v7 bietet ein umfassendes Logging-System.

Logs werden nur ausgegeben, wenn `APP_DEBUG=true` in Ihrer `.env`-Datei gesetzt ist, um Produktions-Apps sauber zu halten.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic logging
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## Log-Level

{{ config('app.name') }} v7 unterstützt mehrere Log-Level mit farbiger Ausgabe:

| Level | Methode | Farbe | Anwendungsfall |
|-------|---------|-------|---------------|
| Debug | `printDebug()` | Cyan | Detaillierte Debug-Informationen |
| Info | `printInfo()` | Blau | Allgemeine Informationen |
| Error | `printError()` | Rot | Fehler und Ausnahmen |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

Ausgabebeispiel:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## Log-Methoden

### Einfaches Logging

``` dart
// Class methods
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### Fehler mit Stack-Trace

Protokollieren Sie Fehler mit Stack-Traces für besseres Debugging:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### Erzwungene Ausgabe unabhängig vom Debug-Modus

Verwenden Sie `alwaysPrint: true`, um auch bei `APP_DEBUG=false` auszugeben:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### Nächsten Log anzeigen (Einmalige Überschreibung)

Einen einzelnen Log ausgeben, wenn `APP_DEBUG=false`:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Prints once

printInfo("This won't print again");
```

<div id="json-logging"></div>

## JSON-Logging

{{ config('app.name') }} v7 enthält eine dedizierte JSON-Logging-Methode:

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

## Farbige Ausgabe

{{ config('app.name') }} v7 verwendet ANSI-Farben für die Log-Ausgabe im Debug-Modus. Jedes Log-Level hat eine eigene Farbe zur einfachen Identifizierung.

### Farben deaktivieren

``` dart
// Disable colored output globally
NyLogger.useColors = false;
```

Farben werden automatisch deaktiviert:
- Im Release-Modus
- Wenn das Terminal keine ANSI-Escape-Codes unterstützt

<div id="log-listeners"></div>

## Log-Listener

{{ config('app.name') }} v7 ermöglicht es Ihnen, alle Log-Einträge in Echtzeit mitzuhören:

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

### NyLogEntry-Eigenschaften

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // The log message
  entry.type;       // Log level (debug, info, warning, error, success, verbose)
  entry.dateTime;   // When the log was created
  entry.stackTrace; // Stack trace (for errors)
};
```

### Anwendungsfälle

- Fehler an Crash-Reporting-Dienste senden (Sentry, Firebase Crashlytics)
- Benutzerdefinierte Log-Viewer erstellen
- Logs zur Fehlerbehebung speichern
- App-Verhalten in Echtzeit überwachen

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

## Hilfs-Extensions

{{ config('app.name') }} bietet praktische Extension-Methoden für das Logging:

### dump()

Einen beliebigen Wert in der Konsole ausgeben:

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

Einen Wert ausgeben und die Ausführung sofort beenden (nützlich zum Debuggen):

``` dart
String code = 'Dart';
code.dd(); // Prints 'Dart' and stops execution

// Function syntax
dd("Debug point reached");
```

<div id="configuration"></div>

## Konfiguration

### Umgebungsvariablen

Steuern Sie das Logging-Verhalten in Ihrer `.env`-Datei:

``` bash
# Enable/disable all logging
APP_DEBUG=true
```

### DateTime in Logs

{{ config('app.name') }} kann Zeitstempel in der Log-Ausgabe einfügen. Konfigurieren Sie dies in Ihrem Nylo-Setup:

``` dart
// In your boot provider
Nylo.instance.showDateTimeInLogs(true);
```

Ausgabe mit Zeitstempeln:
```
[2025-01-27 10:30:45] [info] User logged in
```

Ausgabe ohne Zeitstempel:
```
[info] User logged in
```

### Best Practices

1. **Verwenden Sie angemessene Log-Level** - Protokollieren Sie nicht alles als Fehler
2. **Entfernen Sie ausführliche Logs in Produktion** - Behalten Sie `APP_DEBUG=false` in Produktion bei
3. **Fügen Sie Kontext hinzu** - Protokollieren Sie relevante Daten zum Debuggen
4. **Verwenden Sie strukturiertes Logging** - `NyLogger.json()` für komplexe Daten
5. **Richten Sie Fehlerüberwachung ein** - Verwenden Sie `NyLogger.onLog`, um Fehler abzufangen

``` dart
// Good logging practice
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```

