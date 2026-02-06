# 日志

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [日志级别](#log-levels "日志级别")
- [日志方法](#log-methods "日志方法")
- [JSON 日志](#json-logging "JSON 日志")
- [彩色输出](#colored-output "彩色输出")
- [日志监听器](#log-listeners "日志监听器")
- [辅助扩展](#helper-extensions "辅助扩展")
- [配置](#configuration "配置")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} v7 提供了全面的日志系统。

日志仅在 `.env` 文件中设置 `APP_DEBUG=true` 时才会打印，保持生产应用的整洁。

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic logging
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## 日志级别

{{ config('app.name') }} v7 支持多种带有彩色输出的日志级别：

| 级别 | 方法 | 颜色 | 使用场景 |
|-------|--------|-------|----------|
| Debug | `printDebug()` | 青色 | 详细调试信息 |
| Info | `printInfo()` | 蓝色 | 一般信息 |
| Error | `printError()` | 红色 | 错误和异常 |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

输出示例：
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## 日志方法

### 基本日志

``` dart
// Class methods
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### 带堆栈跟踪的错误

记录带有堆栈跟踪的错误以便更好地调试：

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### 无视调试模式强制打印

使用 `alwaysPrint: true` 在 `APP_DEBUG=false` 时也能打印：

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### 显示下一条日志（一次性覆盖）

在 `APP_DEBUG=false` 时打印单条日志：

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // Prints once

printInfo("This won't print again");
```

<div id="json-logging"></div>

## JSON 日志

{{ config('app.name') }} v7 包含专用的 JSON 日志方法：

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

## 彩色输出

{{ config('app.name') }} v7 在调试模式下使用 ANSI 颜色输出日志。每个日志级别都有不同的颜色以便于识别。

### 禁用颜色

``` dart
// Disable colored output globally
NyLogger.useColors = false;
```

颜色会在以下情况自动禁用：
- 在发布模式下
- 当终端不支持 ANSI 转义码时

<div id="log-listeners"></div>

## 日志监听器

{{ config('app.name') }} v7 允许您实时监听所有日志条目：

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

### NyLogEntry 属性

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // The log message
  entry.type;       // Log level (debug, info, warning, error, success, verbose)
  entry.dateTime;   // When the log was created
  entry.stackTrace; // Stack trace (for errors)
};
```

### 使用场景

- 将错误发送到崩溃报告服务（Sentry、Firebase Crashlytics）
- 构建自定义日志查看器
- 存储日志以供调试
- 实时监控应用行为

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

## 辅助扩展

{{ config('app.name') }} 提供了便捷的日志扩展方法：

### dump()

将任意值打印到控制台：

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

### dd() - 打印并终止

打印一个值并立即退出（对调试很有用）：

``` dart
String code = 'Dart';
code.dd(); // Prints 'Dart' and stops execution

// Function syntax
dd("Debug point reached");
```

<div id="configuration"></div>

## 配置

### 环境变量

在 `.env` 文件中控制日志行为：

``` bash
# Enable/disable all logging
APP_DEBUG=true
```

### 日志中的日期时间

{{ config('app.name') }} 可以在日志输出中包含时间戳。在您的 Nylo 设置中进行配置：

``` dart
// In your boot provider
Nylo.instance.showDateTimeInLogs(true);
```

带时间戳的输出：
```
[2025-01-27 10:30:45] [info] User logged in
```

不带时间戳的输出：
```
[info] User logged in
```

### 最佳实践

1. **使用适当的日志级别** - 不要将所有内容都记录为错误
2. **在生产环境中移除详细日志** - 在生产环境中保持 `APP_DEBUG=false`
3. **包含上下文** - 记录用于调试的相关数据
4. **使用结构化日志** - 对复杂数据使用 `NyLogger.json()`
5. **设置错误监控** - 使用 `NyLogger.onLog` 捕获错误

``` dart
// Good logging practice
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```

