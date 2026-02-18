# Commands

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การสร้างคำสั่ง](#creating-commands "การสร้างคำสั่ง")
- [โครงสร้างคำสั่ง](#command-structure "โครงสร้างคำสั่ง")
- [การเรียกใช้คำสั่ง](#running-commands "การเรียกใช้คำสั่ง")
- [ทะเบียนคำสั่ง](#command-registry "ทะเบียนคำสั่ง")
- [ตัวเลือกและ Flag](#options-and-flags "ตัวเลือกและ Flag")
  - [การเพิ่มตัวเลือก](#adding-options "การเพิ่มตัวเลือก")
  - [การเพิ่ม Flag](#adding-flags "การเพิ่ม Flag")
- [ผลลัพธ์คำสั่ง](#command-result "ผลลัพธ์คำสั่ง")
- [การรับข้อมูลแบบโต้ตอบ](#interactive-input "การรับข้อมูลแบบโต้ตอบ")
  - [การป้อนข้อความ](#text-input "การป้อนข้อความ")
  - [การยืนยัน](#confirmation "การยืนยัน")
  - [การเลือกรายการเดียว](#single-selection "การเลือกรายการเดียว")
  - [การเลือกหลายรายการ](#multiple-selection "การเลือกหลายรายการ")
  - [การป้อนข้อมูลลับ](#secret-input "การป้อนข้อมูลลับ")
- [การจัดรูปแบบผลลัพธ์](#output-formatting "การจัดรูปแบบผลลัพธ์")
- [Spinner และ Progress](#spinner-and-progress "Spinner และ Progress")
  - [การใช้ withSpinner](#using-withspinner "การใช้ withSpinner")
  - [การควบคุม Spinner ด้วยตนเอง](#manual-spinner-control "การควบคุม Spinner ด้วยตนเอง")
  - [แถบความคืบหน้า](#progress-bar "แถบความคืบหน้า")
  - [การประมวลผลรายการพร้อมความคืบหน้า](#processing-items-with-progress "การประมวลผลรายการพร้อมความคืบหน้า")
- [ตัวช่วย API](#api-helper "ตัวช่วย API")
- [ตัวช่วยระบบไฟล์](#file-system-helpers "ตัวช่วยระบบไฟล์")
- [ตัวช่วย JSON และ YAML](#json-and-yaml-helpers "ตัวช่วย JSON และ YAML")
- [การจัดการไฟล์ Dart](#dart-file-manipulation "การจัดการไฟล์ Dart")
- [ตัวช่วยไดเรกทอรี](#directory-helpers "ตัวช่วยไดเรกทอรี")
- [ตัวช่วยแปลงรูปแบบตัวอักษร](#case-conversion-helpers "ตัวช่วยแปลงรูปแบบตัวอักษร")
- [ตัวช่วยเส้นทางโปรเจกต์](#project-path-helpers "ตัวช่วยเส้นทางโปรเจกต์")
- [ตัวช่วยแพลตฟอร์ม](#platform-helpers "ตัวช่วยแพลตฟอร์ม")
- [คำสั่ง Dart และ Flutter](#dart-and-flutter-commands "คำสั่ง Dart และ Flutter")
- [ตัวช่วยตรวจสอบความถูกต้อง](#validation-helpers "ตัวช่วยตรวจสอบความถูกต้อง")
- [การสร้างไฟล์แบบ Scaffold](#file-scaffolding "การสร้างไฟล์แบบ Scaffold")
- [Task Runner](#task-runner "Task Runner")
- [ผลลัพธ์แบบตาราง](#table-output "ผลลัพธ์แบบตาราง")
- [ตัวอย่าง](#examples "ตัวอย่าง")
  - [คำสั่งเวลาปัจจุบัน](#current-time-command "คำสั่งเวลาปัจจุบัน")
  - [คำสั่งดาวน์โหลดฟอนต์](#download-fonts-command "คำสั่งดาวน์โหลดฟอนต์")
  - [คำสั่ง Deployment Pipeline](#deployment-pipeline-command "คำสั่ง Deployment Pipeline")

<div id="introduction"></div>

## บทนำ

คำสั่งช่วยให้คุณขยาย CLI ของ {{ config('app.name') }} ด้วยเครื่องมือที่กำหนดเองเฉพาะโปรเจกต์ โดยการสืบทอดจาก `NyCustomCommand` คุณสามารถทำให้งานซ้ำๆ เป็นอัตโนมัติ สร้าง workflow สำหรับการ deploy สร้างโค้ด หรือเพิ่มฟังก์ชันใดก็ได้ที่คุณต้องการโดยตรงในเทอร์มินัลของคุณ

ทุกคำสั่งที่กำหนดเองมีชุดตัวช่วยในตัวมากมายสำหรับ file I/O, JSON/YAML, prompt แบบโต้ตอบ, spinner, แถบความคืบหน้า, คำขอ API และอื่นๆ — ทั้งหมดโดยไม่ต้อง import แพ็กเกจเพิ่มเติม

> **หมายเหตุ:** คำสั่งที่กำหนดเองทำงานนอก Flutter runtime คุณไม่สามารถ import `nylo_framework.dart` ในคำสั่งของคุณได้ ใช้ `ny_cli.dart` แทน

<div id="creating-commands"></div>

## การสร้างคำสั่ง

สร้างคำสั่งใหม่โดยใช้ Metro หรือ Dart CLI:

```bash
metro make:command current_time
```

คุณสามารถระบุหมวดหมู่สำหรับคำสั่งโดยใช้ตัวเลือก `--category`:

```bash
metro make:command current_time --category="project"
```

คำสั่งนี้สร้างไฟล์ใหม่ที่ `lib/app/commands/current_time.dart` และลงทะเบียนในทะเบียนคำสั่ง

<div id="command-structure"></div>

## โครงสร้างคำสั่ง

ทุกคำสั่งสืบทอดจาก `NyCustomCommand` และใช้สองเมธอดหลัก:

- **`builder()`** -- กำหนดค่าตัวเลือกและ flag
- **`handle()`** -- ดำเนินการตรรกะของคำสั่ง

```dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time Command
///
/// Usage:
///   metro app:current_time
class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final format = result.getString("format");
    info("Current time format: $format");
  }
}
```

<div id="running-commands"></div>

## การเรียกใช้คำสั่ง

เรียกใช้คำสั่งโดยใช้ Metro:

```bash
metro app:current_time
```

ชื่อคำสั่งใช้รูปแบบ `category:name` เมื่อคุณเรียก `metro` โดยไม่มีอาร์กิวเมนต์ คำสั่งที่กำหนดเองของคุณจะปรากฏภายใต้ส่วน **Custom Commands**:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

เพื่อแสดงข้อมูลช่วยเหลือสำหรับคำสั่ง:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## ทะเบียนคำสั่ง

คำสั่งที่กำหนดเองทั้งหมดลงทะเบียนใน `lib/app/commands/commands.json` ไฟล์นี้อัปเดตโดยอัตโนมัติเมื่อคุณใช้ `make:command`:

```json
[
  {
    "name": "install_firebase",
    "category": "project",
    "script": "install_firebase.dart"
  },
  {
    "name": "current_time",
    "category": "app",
    "script": "current_time.dart"
  }
]
```

แต่ละรายการมี:

| ฟิลด์ | คำอธิบาย |
|-------|-------------|
| `name` | ชื่อคำสั่ง (ใช้หลังคำนำหน้าหมวดหมู่) |
| `category` | หมวดหมู่คำสั่ง (เช่น `app`, `project`) |
| `script` | ไฟล์ Dart ใน `lib/app/commands/` |

<div id="options-and-flags"></div>

## ตัวเลือกและ Flag

กำหนดค่าตัวเลือกและ flag ของคำสั่งในเมธอด `builder()` โดยใช้ `CommandBuilder`

<div id="adding-options"></div>

### การเพิ่มตัวเลือก

ตัวเลือกรับค่าจากผู้ใช้:

```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption(
    'environment',
    abbr: 'e',
    help: 'Target deployment environment',
    defaultValue: 'development',
    allowed: ['development', 'staging', 'production'],
  );
  return command;
}
```

การใช้งาน:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `name` | `String` | ชื่อตัวเลือก |
| `abbr` | `String?` | ตัวย่อหนึ่งตัวอักษร |
| `help` | `String?` | ข้อความช่วยเหลือที่แสดงกับ `--help` |
| `allowed` | `List<String>?` | จำกัดเฉพาะค่าที่อนุญาต |
| `defaultValue` | `String?` | ค่าเริ่มต้นถ้าไม่ได้ระบุ |

<div id="adding-flags"></div>

### การเพิ่ม Flag

Flag เป็นสวิตช์แบบ boolean:

```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag(
    'verbose',
    abbr: 'v',
    help: 'Enable verbose output',
    defaultValue: false,
  );
  return command;
}
```

การใช้งาน:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `name` | `String` | ชื่อ flag |
| `abbr` | `String?` | ตัวย่อหนึ่งตัวอักษร |
| `help` | `String?` | ข้อความช่วยเหลือที่แสดงกับ `--help` |
| `defaultValue` | `bool` | ค่าเริ่มต้น (ค่าเริ่มต้น: `false`) |

<div id="command-result"></div>

## ผลลัพธ์คำสั่ง

เมธอด `handle()` รับอ็อบเจกต์ `CommandResult` พร้อมตัวเข้าถึงแบบมีชนิดข้อมูลสำหรับอ่านตัวเลือก flag และอาร์กิวเมนต์ที่แยกวิเคราะห์แล้ว

```dart
@override
Future<void> handle(CommandResult result) async {
  // Get a string option
  final name = result.getString('name');

  // Get a boolean flag
  final verbose = result.getBool('verbose');

  // Get an integer option
  final count = result.getInt('count');

  // Generic typed access
  final value = result.get<String>('key');

  // Built-in flag checks
  if (result.hasForceFlag) { /* --force was passed */ }
  if (result.hasHelpFlag) { /* --help was passed */ }

  // Raw arguments
  List<String> allArgs = result.arguments;
  List<String> unparsed = result.rest;
}
```

| เมธอด / คุณสมบัติ | คืนค่า | คำอธิบาย |
|-------------------|---------|-------------|
| `getString(String name, {String? defaultValue})` | `String?` | รับค่าแบบ string |
| `getBool(String name, {bool? defaultValue})` | `bool?` | รับค่าแบบ boolean |
| `getInt(String name, {int? defaultValue})` | `int?` | รับค่าแบบ integer |
| `get<T>(String name)` | `T?` | รับค่าแบบมีชนิดข้อมูล |
| `hasForceFlag` | `bool` | ว่าส่ง `--force` มาหรือไม่ |
| `hasHelpFlag` | `bool` | ว่าส่ง `--help` มาหรือไม่ |
| `arguments` | `List<String>` | อาร์กิวเมนต์ command-line ทั้งหมด |
| `rest` | `List<String>` | อาร์กิวเมนต์ที่ยังไม่ได้แยกวิเคราะห์ |

<div id="interactive-input"></div>

## การรับข้อมูลแบบโต้ตอบ

`NyCustomCommand` มีเมธอดสำหรับรวบรวมข้อมูลจากผู้ใช้ในเทอร์มินัล

<div id="text-input"></div>

### การป้อนข้อความ

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `question` | `String` | คำถามที่จะแสดง |
| `defaultValue` | `String` | ค่าเริ่มต้นถ้าผู้ใช้กด Enter (ค่าเริ่มต้น: `''`) |

<div id="confirmation"></div>

### การยืนยัน

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `question` | `String` | คำถามแบบ yes/no |
| `defaultValue` | `bool` | คำตอบเริ่มต้น (ค่าเริ่มต้น: `false`) |

<div id="single-selection"></div>

### การเลือกรายการเดียว

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `question` | `String` | ข้อความ prompt |
| `options` | `List<String>` | ตัวเลือกที่มี |
| `defaultOption` | `String?` | ตัวเลือกที่เลือกไว้ล่วงหน้า |

<div id="multiple-selection"></div>

### การเลือกหลายรายการ

```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences'],
);

if (packages.isNotEmpty) {
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

ผู้ใช้ป้อนตัวเลขคั่นด้วยจุลภาคหรือ `"all"`

<div id="secret-input"></div>

### การป้อนข้อมูลลับ

```dart
final apiKey = promptSecret('Enter your API key:');
```

ข้อมูลที่ป้อนจะถูกซ่อนจากการแสดงผลในเทอร์มินัล จะกลับไปใช้การป้อนแบบมองเห็นได้ถ้าไม่รองรับโหมด echo

<div id="output-formatting"></div>

## การจัดรูปแบบผลลัพธ์

เมธอดสำหรับพิมพ์ผลลัพธ์แบบมีสไตล์ไปยังคอนโซล:

```dart
@override
Future<void> handle(CommandResult result) async {
  info('Processing files...');       // Blue text
  error('Operation failed');         // Red text
  success('Deployment complete');    // Green text
  warning('Outdated package');       // Yellow text
  line('Plain text output');         // No color
  comment('Background note');        // Gray text
  alert('Important notice');         // Bordered alert box
  newLine();                         // One blank line
  newLine(3);                        // Three blank lines

  // Exit the command with an error
  abort('Fatal error occurred');     // Prints red, exits with code 1
}
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `info(String message)` | พิมพ์ข้อความสีน้ำเงิน |
| `error(String message)` | พิมพ์ข้อความสีแดง |
| `success(String message)` | พิมพ์ข้อความสีเขียว |
| `warning(String message)` | พิมพ์ข้อความสีเหลือง |
| `line(String message)` | พิมพ์ข้อความธรรมดา (ไม่มีสี) |
| `newLine([int count = 1])` | พิมพ์บรรทัดว่าง |
| `comment(String message)` | พิมพ์ข้อความสีเทา/จาง |
| `alert(String message)` | พิมพ์กล่องแจ้งเตือนมีกรอบ |
| `abort([String? message, int exitCode = 1])` | ออกจากคำสั่งพร้อมข้อผิดพลาด |

<div id="spinner-and-progress"></div>

## Spinner และ Progress

Spinner และแถบความคืบหน้าให้การตอบสนองทางภาพระหว่างการดำเนินการที่ใช้เวลานาน

<div id="using-withspinner"></div>

### การใช้ withSpinner

ครอบงาน async ด้วย spinner อัตโนมัติ:

```dart
final projectFiles = await withSpinner(
  task: () async {
    await sleep(2);
    return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
  },
  message: 'Analyzing project structure',
  successMessage: 'Project analysis complete',
  errorMessage: 'Failed to analyze project',
);

info('Found ${projectFiles.length} key files');
```

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `task` | `Future<T> Function()` | ฟังก์ชัน async ที่จะดำเนินการ |
| `message` | `String` | ข้อความที่แสดงขณะ spinner ทำงาน |
| `successMessage` | `String?` | แสดงเมื่อสำเร็จ |
| `errorMessage` | `String?` | แสดงเมื่อล้มเหลว |

<div id="manual-spinner-control"></div>

### การควบคุม Spinner ด้วยตนเอง

สำหรับ workflow หลายขั้นตอน สร้าง spinner และควบคุมด้วยตนเอง:

```dart
final spinner = createSpinner('Deploying to production');
spinner.start();

try {
  await runProcess('flutter clean', silent: true);
  spinner.update('Building release version');

  await runProcess('flutter build web --release', silent: true);
  spinner.update('Uploading to server');

  await runProcess('./deploy.sh', silent: true);
  spinner.stop(completionMessage: 'Deployment completed', success: true);
} catch (e) {
  spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
}
```

**เมธอด ConsoleSpinner:**

| เมธอด | คำอธิบาย |
|--------|-------------|
| `start([String? message])` | เริ่มแอนิเมชัน spinner |
| `update(String message)` | เปลี่ยนข้อความที่แสดง |
| `stop({String? completionMessage, bool success = true})` | หยุด spinner |

<div id="progress-bar"></div>

### แถบความคืบหน้า

สร้างและจัดการแถบความคืบหน้าด้วยตนเอง:

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**เมธอด ConsoleProgressBar:**

| เมธอด / คุณสมบัติ | คำอธิบาย |
|-------------------|-------------|
| `start()` | เริ่มแถบความคืบหน้า |
| `tick([int amount = 1])` | เพิ่มความคืบหน้า |
| `update(int value)` | ตั้งค่าความคืบหน้าเป็นค่าเฉพาะ |
| `updateMessage(String newMessage)` | เปลี่ยนข้อความที่แสดง |
| `complete([String? completionMessage])` | เสร็จสิ้นพร้อมข้อความเสริม |
| `stop()` | หยุดโดยไม่เสร็จสิ้น |
| `current` | ค่าความคืบหน้าปัจจุบัน (getter) |
| `percentage` | ความคืบหน้าเป็นเปอร์เซ็นต์ 0-100 (getter) |

<div id="processing-items-with-progress"></div>

### การประมวลผลรายการพร้อมความคืบหน้า

ประมวลผลรายการพร้อมการติดตามความคืบหน้าอัตโนมัติ:

```dart
// Async processing
final results = await withProgress<File, String>(
  items: findFiles('lib/', extension: '.dart'),
  process: (file, index) async {
    return file.path;
  },
  message: 'Analyzing Dart files',
  completionMessage: 'Analysis complete',
);

// Synchronous processing
final upperItems = withProgressSync<String, String>(
  items: ['a', 'b', 'c', 'd', 'e'],
  process: (item, index) => item.toUpperCase(),
  message: 'Converting items',
);
```

<div id="api-helper"></div>

## ตัวช่วย API

ตัวช่วย `api` เป็น wrapper ที่เรียบง่ายรอบ Dio สำหรับส่งคำขอ HTTP:

```dart
// GET request
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);

// POST request
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99},
  )
);

// PUT request
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99},
  )
);

// DELETE request
final deleteResult = await api((request) =>
  request.delete('https://api.example.com/items/42')
);

// PATCH request
final patchResult = await api((request) =>
  request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99},
  )
);

// With query parameters
final searchResults = await api((request) =>
  request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10},
  )
);
```

รวมกับ `withSpinner` เพื่อประสบการณ์ผู้ใช้ที่ดีขึ้น:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

`ApiService` รองรับเมธอด `get`, `post`, `put`, `delete` และ `patch` แต่ละเมธอดรับ `queryParameters`, `data`, `options` และ `cancelToken` แบบเสริม

<div id="file-system-helpers"></div>

## ตัวช่วยระบบไฟล์

ตัวช่วยระบบไฟล์ในตัวเพื่อให้คุณไม่ต้อง import `dart:io`:

```dart
// Check existence
if (fileExists('lib/config/app.dart')) { /* ... */ }
if (directoryExists('lib/app/models')) { /* ... */ }

// Read files
String content = await readFile('pubspec.yaml');
String contentSync = readFileSync('pubspec.yaml');

// Write files
await writeFile('lib/generated/output.dart', 'class Output {}');
writeFileSync('lib/generated/output.dart', 'class Output {}');

// Append to a file
await appendFile('log.txt', 'New log entry\n');

// Ensure a directory exists
await ensureDirectory('lib/generated');

// Delete and copy files
await deleteFile('lib/generated/output.dart');
await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `fileExists(String path)` | คืนค่า `true` ถ้าไฟล์มีอยู่ |
| `directoryExists(String path)` | คืนค่า `true` ถ้าไดเรกทอรีมีอยู่ |
| `readFile(String path)` | อ่านไฟล์เป็น string (async) |
| `readFileSync(String path)` | อ่านไฟล์เป็น string (sync) |
| `writeFile(String path, String content)` | เขียนเนื้อหาไปยังไฟล์ (async) |
| `writeFileSync(String path, String content)` | เขียนเนื้อหาไปยังไฟล์ (sync) |
| `appendFile(String path, String content)` | เพิ่มเนื้อหาต่อท้ายไฟล์ |
| `ensureDirectory(String path)` | สร้างไดเรกทอรีถ้ายังไม่มี |
| `deleteFile(String path)` | ลบไฟล์ |
| `copyFile(String source, String destination)` | คัดลอกไฟล์ |

<div id="json-and-yaml-helpers"></div>

## ตัวช่วย JSON และ YAML

อ่านและเขียนไฟล์ JSON และ YAML ด้วยตัวช่วยในตัว:

```dart
// Read JSON as Map
Map<String, dynamic> config = await readJson('config.json');

// Read JSON as List
List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

// Write JSON (pretty printed by default)
await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

// Write compact JSON
await writeJson('output.json', data, pretty: false);

// Append to a JSON array file (with duplicate prevention)
await appendToJsonArray(
  'lib/app/commands/commands.json',
  {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
  uniqueKey: 'name',
);

// Read YAML as Map
Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
info('Project: ${pubspec['name']}');
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `readJson(String path)` | อ่านไฟล์ JSON เป็น `Map<String, dynamic>` |
| `readJsonArray(String path)` | อ่านไฟล์ JSON เป็น `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | เขียนข้อมูลเป็น JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | เพิ่มต่อท้ายไฟล์ JSON array |
| `readYaml(String path)` | อ่านไฟล์ YAML เป็น `Map<String, dynamic>` |

<div id="dart-file-manipulation"></div>

## การจัดการไฟล์ Dart

ตัวช่วยสำหรับแก้ไขไฟล์ซอร์สโค้ด Dart แบบเป็นโปรแกรม — มีประโยชน์เมื่อสร้างเครื่องมือ scaffolding:

```dart
// Add an import (skips if already present)
await addImport(
  'lib/bootstrap/providers.dart',
  "import '/app/providers/firebase_provider.dart';",
);

// Insert code before the last closing brace
await insertBeforeClosingBrace(
  'lib/bootstrap/providers.dart',
  '  FirebaseProvider(),',
);

// Check if a file contains a string
bool hasImport = await fileContains(
  'lib/bootstrap/providers.dart',
  'firebase_provider',
);

// Check if a file matches a regex pattern
bool hasClass = await fileContainsPattern(
  'lib/app/models/user.dart',
  RegExp(r'class User'),
);
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | เพิ่ม import ไปยังไฟล์ Dart (ข้ามถ้ามีอยู่แล้ว) |
| `insertBeforeClosingBrace(String filePath, String code)` | แทรกโค้ดก่อน `}` ตัวสุดท้ายในไฟล์ |
| `fileContains(String filePath, String identifier)` | ตรวจสอบว่าไฟล์มี string ที่ระบุหรือไม่ |
| `fileContainsPattern(String filePath, Pattern pattern)` | ตรวจสอบว่าไฟล์ตรงกับ pattern หรือไม่ |

<div id="directory-helpers"></div>

## ตัวช่วยไดเรกทอรี

ตัวช่วยสำหรับทำงานกับไดเรกทอรีและค้นหาไฟล์:

```dart
// List directory contents
var entities = listDirectory('lib/app/models');
var allEntities = listDirectory('lib/', recursive: true);

// Find files by extension
List<File> dartFiles = findFiles(
  'lib/app/models',
  extension: '.dart',
  recursive: true,
);

// Find files by name pattern
List<File> testFiles = findFiles(
  'test/',
  namePattern: RegExp(r'_test\.dart$'),
);

// Delete a directory recursively
await deleteDirectory('build/');

// Copy a directory recursively
await copyDirectory('lib/templates', 'lib/generated');
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | แสดงเนื้อหาไดเรกทอรี |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | ค้นหาไฟล์ที่ตรงตามเกณฑ์ |
| `deleteDirectory(String path)` | ลบไดเรกทอรีแบบ recursive |
| `copyDirectory(String source, String destination)` | คัดลอกไดเรกทอรีแบบ recursive |

<div id="case-conversion-helpers"></div>

## ตัวช่วยแปลงรูปแบบตัวอักษร

แปลง string ระหว่างรูปแบบการตั้งชื่อต่างๆ โดยไม่ต้อง import แพ็กเกจ `recase`:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| เมธอด | รูปแบบผลลัพธ์ | ตัวอย่าง |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## ตัวช่วยเส้นทางโปรเจกต์

Getter สำหรับไดเรกทอรีโปรเจกต์ {{ config('app.name') }} มาตรฐาน คืนค่าเส้นทางสัมพัทธ์จากรากโปรเจกต์:

```dart
info(modelsPath);       // lib/app/models
info(controllersPath);  // lib/app/controllers
info(widgetsPath);      // lib/resources/widgets
info(pagesPath);        // lib/resources/pages
info(commandsPath);     // lib/app/commands
info(configPath);       // lib/config
info(providersPath);    // lib/app/providers
info(eventsPath);       // lib/app/events
info(networkingPath);   // lib/app/networking
info(themesPath);       // lib/resources/themes

// Build a custom path
String customPath = projectPath('app/services/auth_service.dart');
// Returns: lib/app/services/auth_service.dart
```

| คุณสมบัติ | เส้นทาง |
|----------|------|
| `modelsPath` | `lib/app/models` |
| `controllersPath` | `lib/app/controllers` |
| `widgetsPath` | `lib/resources/widgets` |
| `pagesPath` | `lib/resources/pages` |
| `commandsPath` | `lib/app/commands` |
| `configPath` | `lib/config` |
| `providersPath` | `lib/app/providers` |
| `eventsPath` | `lib/app/events` |
| `networkingPath` | `lib/app/networking` |
| `themesPath` | `lib/resources/themes` |
| `projectPath(String relativePath)` | แก้ไขเส้นทางสัมพัทธ์ภายในโปรเจกต์ |

<div id="platform-helpers"></div>

## ตัวช่วยแพลตฟอร์ม

ตรวจสอบแพลตฟอร์มและเข้าถึงตัวแปรสภาพแวดล้อม:

```dart
if (isWindows) {
  info('Running on Windows');
} else if (isMacOS) {
  info('Running on macOS');
} else if (isLinux) {
  info('Running on Linux');
}

info('Working in: $workingDirectory');

String home = env('HOME', '/default/path');
```

| คุณสมบัติ / เมธอด | คำอธิบาย |
|-------------------|-------------|
| `isWindows` | `true` ถ้าทำงานบน Windows |
| `isMacOS` | `true` ถ้าทำงานบน macOS |
| `isLinux` | `true` ถ้าทำงานบน Linux |
| `workingDirectory` | เส้นทางไดเรกทอรีทำงานปัจจุบัน |
| `env(String key, [String defaultValue = ''])` | อ่านตัวแปรสภาพแวดล้อมของระบบ |

<div id="dart-and-flutter-commands"></div>

## คำสั่ง Dart และ Flutter

เรียกใช้คำสั่ง Dart และ Flutter CLI ทั่วไปเป็นเมธอดช่วยเหลือ แต่ละเมธอดคืนค่า exit code ของ process:

```dart
// Format a Dart file or directory
await dartFormat('lib/app/models/user.dart');

// Run dart analyze
int analyzeResult = await dartAnalyze('lib/');

// Run flutter pub get
await flutterPubGet();

// Run flutter clean
await flutterClean();

// Build for a target with additional args
await flutterBuild('apk', args: ['--release', '--split-per-abi']);
await flutterBuild('web', args: ['--release']);

// Run flutter test
await flutterTest();
await flutterTest('test/unit/');
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `dartFormat(String path)` | เรียก `dart format` บนไฟล์หรือไดเรกทอรี |
| `dartAnalyze([String? path])` | เรียก `dart analyze` |
| `flutterPubGet()` | เรียก `flutter pub get` |
| `flutterClean()` | เรียก `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | เรียก `flutter build <target>` |
| `flutterTest([String? path])` | เรียก `flutter test` |

<div id="validation-helpers"></div>

## ตัวช่วยตรวจสอบความถูกต้อง

ตัวช่วยสำหรับตรวจสอบและทำความสะอาดข้อมูลจากผู้ใช้สำหรับการสร้างโค้ด:

```dart
// Validate a Dart identifier
if (!isValidDartIdentifier('MyClass')) {
  error('Invalid Dart identifier');
}

// Require a non-empty first argument (aborts if missing)
String name = requireArgument(result, message: 'Please provide a name');

// Clean a class name (PascalCase, remove suffixes)
String className = cleanClassName('user_model', removeSuffixes: ['_model']);
// Returns: 'User'

// Clean a file name (snake_case with extension)
String fileName = cleanFileName('UserModel', extension: '.dart');
// Returns: 'user_model.dart'
```

| เมธอด | คำอธิบาย |
|--------|-------------|
| `isValidDartIdentifier(String name)` | ตรวจสอบชื่อตัวระบุ Dart ว่าถูกต้องหรือไม่ |
| `requireArgument(CommandResult result, {String? message})` | ต้องการอาร์กิวเมนต์แรกที่ไม่ว่างหรือจะ abort |
| `cleanClassName(String name, {List<String> removeSuffixes})` | ทำความสะอาดและแปลงชื่อคลาสเป็น PascalCase |
| `cleanFileName(String name, {String extension = '.dart'})` | ทำความสะอาดและแปลงชื่อไฟล์เป็น snake_case |

<div id="file-scaffolding"></div>

## การสร้างไฟล์แบบ Scaffold

สร้างไฟล์หนึ่งหรือหลายไฟล์พร้อมเนื้อหาโดยใช้ระบบ scaffolding

### ไฟล์เดียว

```dart
await scaffold(
  path: 'lib/app/services/auth_service.dart',
  content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    return false;
  }
}
''',
  force: false,
  successMessage: 'AuthService created',
);
```

| พารามิเตอร์ | ชนิด | คำอธิบาย |
|-----------|------|-------------|
| `path` | `String` | เส้นทางไฟล์ที่จะสร้าง |
| `content` | `String` | เนื้อหาไฟล์ |
| `force` | `bool` | เขียนทับถ้ามีอยู่แล้ว (ค่าเริ่มต้น: `false`) |
| `successMessage` | `String?` | ข้อความที่แสดงเมื่อสำเร็จ |

### หลายไฟล์

```dart
await scaffoldMany([
  ScaffoldFile(
    path: 'lib/app/models/product.dart',
    content: 'class Product {}',
    successMessage: 'Product model created',
  ),
  ScaffoldFile(
    path: 'lib/app/networking/product_api_service.dart',
    content: 'class ProductApiService {}',
    successMessage: 'Product API service created',
  ),
], force: false);
```

คลาส `ScaffoldFile`:

| คุณสมบัติ | ชนิด | คำอธิบาย |
|----------|------|-------------|
| `path` | `String` | เส้นทางไฟล์ที่จะสร้าง |
| `content` | `String` | เนื้อหาไฟล์ |
| `successMessage` | `String?` | ข้อความที่แสดงเมื่อสำเร็จ |

<div id="task-runner"></div>

## Task Runner

เรียกใช้งานหลายงานตามลำดับพร้อมผลลัพธ์สถานะอัตโนมัติ

### Task Runner พื้นฐาน

```dart
await runTasks([
  CommandTask(
    'Clean project',
    () => runProcess('flutter clean', silent: true),
  ),
  CommandTask(
    'Fetch dependencies',
    () => runProcess('flutter pub get', silent: true),
  ),
  CommandTask(
    'Run tests',
    () => runProcess('flutter test', silent: true),
    stopOnError: true,
  ),
]);
```

### Task Runner พร้อม Spinner

```dart
await runTasksWithSpinner([
  CommandTask(
    'Preparing release',
    () async {
      await flutterClean();
      await flutterPubGet();
    },
  ),
  CommandTask(
    'Building APK',
    () => flutterBuild('apk', args: ['--release']),
  ),
]);
```

คลาส `CommandTask`:

| คุณสมบัติ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|----------|------|---------|-------------|
| `name` | `String` | จำเป็น | ชื่องานที่แสดงในผลลัพธ์ |
| `action` | `Future<void> Function()` | จำเป็น | ฟังก์ชัน async ที่จะดำเนินการ |
| `stopOnError` | `bool` | `true` | ว่าจะหยุดงานที่เหลือเมื่อล้มเหลวหรือไม่ |

<div id="table-output"></div>

## ผลลัพธ์แบบตาราง

แสดงตาราง ASCII ที่จัดรูปแบบแล้วในคอนโซล:

```dart
table(
  ['Name', 'Version', 'Status'],
  [
    ['nylo_framework', '7.0.0', 'installed'],
    ['nylo_support', '7.0.0', 'installed'],
    ['dio', '5.4.0', 'installed'],
  ],
);
```

ผลลัพธ์:

```
┌─────────────────┬─────────┬───────────┐
│ Name            │ Version │ Status    │
├─────────────────┼─────────┼───────────┤
│ nylo_framework  │ 7.0.0   │ installed │
│ nylo_support    │ 7.0.0   │ installed │
│ dio             │ 5.4.0   │ installed │
└─────────────────┴─────────┴───────────┘
```

<div id="examples"></div>

## ตัวอย่าง

<div id="current-time-command"></div>

### คำสั่งเวลาปัจจุบัน

คำสั่งง่ายๆ ที่แสดงเวลาปัจจุบัน:

```dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

class _CurrentTimeCommand extends NyCustomCommand {
  _CurrentTimeCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('format', defaultValue: 'HH:mm:ss');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final format = result.getString("format");
    final now = DateTime.now();
    info("The current time is ${now.toIso8601String()}");
    info("Requested format: $format");
  }
}
```

<div id="download-fonts-command"></div>

### คำสั่งดาวน์โหลดฟอนต์

คำสั่งที่ดาวน์โหลดและติดตั้ง Google Fonts ลงในโปรเจกต์:

```dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _DownloadFontsCommand(arguments).run();

class _DownloadFontsCommand extends NyCustomCommand {
  _DownloadFontsCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('font', abbr: 'f', help: 'Font family name');
    command.addFlag('verbose', abbr: 'v', defaultValue: false);
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final fontName = result.getString('font') ??
        prompt('Enter font family name:', defaultValue: 'Roboto');

    final verbose = result.getBool('verbose') ?? false;

    await withSpinner(
      task: () async {
        await ensureDirectory('assets/fonts');
        final fontData = await api((request) =>
          request.get('https://fonts.google.com/download?family=$fontName')
        );
        if (fontData != null) {
          await writeFile('assets/fonts/$fontName.ttf', fontData.toString());
        }
      },
      message: 'Downloading $fontName font',
      successMessage: '$fontName font installed',
      errorMessage: 'Failed to download $fontName',
    );

    if (verbose) {
      info('Font saved to: assets/fonts/$fontName.ttf');
    }
  }
}
```

<div id="deployment-pipeline-command"></div>

### คำสั่ง Deployment Pipeline

คำสั่งที่เรียกใช้ deployment pipeline เต็มรูปแบบโดยใช้ task runner:

```dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _DeployCommand(arguments).run();

class _DeployCommand extends NyCustomCommand {
  _DeployCommand(super.arguments);

  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption(
      'environment',
      abbr: 'e',
      defaultValue: 'development',
      allowed: ['development', 'staging', 'production'],
    );
    command.addFlag('skip-tests', defaultValue: false);
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final env = result.getString('environment') ?? 'development';
    final skipTests = result.getBool('skip-tests') ?? false;

    alert('Deploying to $env');
    newLine();

    if (env == 'production') {
      if (!confirm('Are you sure you want to deploy to production?')) {
        abort('Deployment canceled');
      }
    }

    final tasks = <CommandTask>[
      CommandTask('Clean project', () => flutterClean()),
      CommandTask('Fetch dependencies', () => flutterPubGet()),
    ];

    if (!skipTests) {
      tasks.add(CommandTask(
        'Run tests',
        () => flutterTest(),
        stopOnError: true,
      ));
    }

    tasks.add(CommandTask(
      'Build for web',
      () => flutterBuild('web', args: ['--release']),
    ));

    await runTasksWithSpinner(tasks);

    newLine();
    success('Deployment to $env completed');

    table(
      ['Step', 'Status'],
      tasks.map((t) => [t.name, 'Done']).toList(),
    );
  }
}
```
