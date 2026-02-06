# Logging

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Log Levels](#log-levels "Log levels")
- [Log Methods](#log-methods "Log methods")
- [JSON Logging](#json-logging "JSON logging")
- [Colored Output](#colored-output "Colored output")
- [Log Listeners](#log-listeners "Log listeners")
- [Helper Extensions](#helper-extensions "Helper extensions")
- [Configuration](#configuration "Configuration")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 provides a comprehensive logging system. 

Logs are only printed when `APP_DEBUG=true` in your `.env` file, keeping production apps clean.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic logging
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## Log Levels

{{ config('app.name') }} v7 supports multiple log levels with colored output:

| Level | Method | Color | Use Case |
|-------|--------|-------|----------|
| Debug | `printDebug()` | Cyan | Detailed debugging info |
| Info | `printInfo()` | Blue | General information |
| Error | `printError()` | Red | Errors and exceptions |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

Output example:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## Log Methods

### Basic Logging

``` dart
// Class methods
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### Error with Stack Trace

Log errors with stack traces for better debugging:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### Force Print Regardless of Debug Mode

Use `alwaysPrint: true` to print even when `APP_DEBUG=false`:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### Show Next Log (One-Time Override)

Print a single log when `APP_DEBUG=false`:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Prints once

printInfo("This won't print again");
```

<div id="json-logging"></div>

## JSON Logging

{{ config('app.name') }} v7 includes a dedicated JSON logging method:

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

## Colored Output

{{ config('app.name') }} v7 uses ANSI colors for log output in debug mode. Each log level has a distinct color for easy identification.

### Disable Colors

``` dart
// Disable colored output globally
NyLogger.useColors = false;
```

Colors are automatically disabled:
- In release mode
- When the terminal doesn't support ANSI escape codes

<div id="log-listeners"></div>

## Log Listeners

{{ config('app.name') }} v7 allows you to listen to all log entries in real-time:

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

### NyLogEntry Properties

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // The log message
  entry.type;       // Log level (debug, info, warning, error, success, verbose)
  entry.dateTime;   // When the log was created
  entry.stackTrace; // Stack trace (for errors)
};
```

### Use Cases

- Send errors to crash reporting services (Sentry, Firebase Crashlytics)
- Build custom log viewers
- Store logs for debugging
- Monitor app behavior in real-time

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

## Helper Extensions

{{ config('app.name') }} provides convenient extension methods for logging:

### dump()

Print any value to the console:

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

Print a value and immediately exit (useful for debugging):

``` dart
String code = 'Dart';
code.dd(); // Prints 'Dart' and stops execution

// Function syntax
dd("Debug point reached");
```

<div id="configuration"></div>

## Configuration

### Environment Variables

Control logging behavior in your `.env` file:

``` bash
# Enable/disable all logging
APP_DEBUG=true
```

### DateTime in Logs

{{ config('app.name') }} can include timestamps in log output. Configure this in your Nylo setup:

``` dart
// In your boot provider
Nylo.instance.showDateTimeInLogs(true);
```

Output with timestamps:
```
[2025-01-27 10:30:45] [info] User logged in
```

Output without timestamps:
```
[info] User logged in
```

### Best Practices

1. **Use appropriate log levels** - Don't log everything as errors
2. **Remove verbose logs in production** - Keep `APP_DEBUG=false` in production
3. **Include context** - Log relevant data for debugging
4. **Use structured logging** - `NyLogger.json()` for complex data
5. **Set up error monitoring** - Use `NyLogger.onLog` to catch errors

``` dart
// Good logging practice
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```

