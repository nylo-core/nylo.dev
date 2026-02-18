# Commands

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Tạo Command](#creating-commands "Tạo Command")
- [Cấu trúc Command](#command-structure "Cấu trúc Command")
- [Chạy Command](#running-commands "Chạy Command")
- [Đăng ký Command](#command-registry "Đăng ký Command")
- [Tùy chọn và Cờ](#options-and-flags "Tùy chọn và Cờ")
  - [Thêm tùy chọn](#adding-options "Thêm tùy chọn")
  - [Thêm cờ](#adding-flags "Thêm cờ")
- [Kết quả Command](#command-result "Kết quả Command")
- [Nhập liệu tương tác](#interactive-input "Nhập liệu tương tác")
  - [Nhập văn bản](#text-input "Nhập văn bản")
  - [Xác nhận](#confirmation "Xác nhận")
  - [Chọn đơn](#single-selection "Chọn đơn")
  - [Chọn nhiều](#multiple-selection "Chọn nhiều")
  - [Nhập bí mật](#secret-input "Nhập bí mật")
- [Định dạng đầu ra](#output-formatting "Định dạng đầu ra")
- [Spinner và thanh tiến trình](#spinner-and-progress "Spinner và thanh tiến trình")
  - [Sử dụng withSpinner](#using-withspinner "Sử dụng withSpinner")
  - [Điều khiển Spinner thủ công](#manual-spinner-control "Điều khiển Spinner thủ công")
  - [Thanh tiến trình](#progress-bar "Thanh tiến trình")
  - [Xử lý item với tiến trình](#processing-items-with-progress "Xử lý item với tiến trình")
- [API Helper](#api-helper "API Helper")
- [File System Helper](#file-system-helpers "File System Helper")
- [JSON và YAML Helper](#json-and-yaml-helpers "JSON và YAML Helper")
- [Thao tác tệp Dart](#dart-file-manipulation "Thao tác tệp Dart")
- [Directory Helper](#directory-helpers "Directory Helper")
- [Helper chuyển đổi kiểu chữ](#case-conversion-helpers "Helper chuyển đổi kiểu chữ")
- [Helper đường dẫn dự án](#project-path-helpers "Helper đường dẫn dự án")
- [Helper nền tảng](#platform-helpers "Helper nền tảng")
- [Lệnh Dart và Flutter](#dart-and-flutter-commands "Lệnh Dart và Flutter")
- [Helper xác thực](#validation-helpers "Helper xác thực")
- [Scaffolding tệp](#file-scaffolding "Scaffolding tệp")
- [Trình chạy tác vụ](#task-runner "Trình chạy tác vụ")
- [Xuất bảng](#table-output "Xuất bảng")
- [Ví dụ](#examples "Ví dụ")
  - [Command thời gian hiện tại](#current-time-command "Command thời gian hiện tại")
  - [Command tải font](#download-fonts-command "Command tải font")
  - [Command pipeline triển khai](#deployment-pipeline-command "Command pipeline triển khai")

<div id="introduction"></div>

## Giới thiệu

Command cho phép bạn mở rộng CLI của {{ config('app.name') }} với các công cụ tùy chỉnh riêng cho dự án. Bằng cách kế thừa `NyCustomCommand`, bạn có thể tự động hóa các tác vụ lặp lại, xây dựng quy trình triển khai, tạo mã, hoặc thêm bất kỳ chức năng nào bạn cần trực tiếp trong terminal.

Mỗi command tùy chỉnh đều có quyền truy cập vào một bộ helper dựng sẵn phong phú cho I/O tệp, JSON/YAML, prompt tương tác, spinner, thanh tiến trình, yêu cầu API, và nhiều hơn nữa -- tất cả mà không cần import thêm package.

> **Lưu ý:** Command tùy chỉnh chạy bên ngoài Flutter runtime. Bạn không thể import `nylo_framework.dart` trong các command. Sử dụng `ny_cli.dart` thay thế.

<div id="creating-commands"></div>

## Tạo Command

Tạo command mới bằng Metro hoặc Dart CLI:

```bash
metro make:command current_time
```

Bạn có thể chỉ định danh mục cho command bằng tùy chọn `--category`:

```bash
metro make:command current_time --category="project"
```

Lệnh này tạo một tệp mới tại `lib/app/commands/current_time.dart` và đăng ký nó trong danh sách command.

<div id="command-structure"></div>

## Cấu trúc Command

Mỗi command kế thừa `NyCustomCommand` và triển khai hai phương thức chính:

- **`builder()`** -- cấu hình tùy chọn và cờ
- **`handle()`** -- thực thi logic của command

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

## Chạy Command

Chạy command của bạn bằng Metro:

```bash
metro app:current_time
```

Tên command theo mẫu `category:name`. Khi bạn chạy `metro` mà không có tham số, các command tùy chỉnh sẽ xuất hiện trong phần **Custom Commands**:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

Để hiển thị trợ giúp cho một command:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## Đăng ký Command

Tất cả command tùy chỉnh được đăng ký trong `lib/app/commands/commands.json`. Tệp này được cập nhật tự động khi bạn sử dụng `make:command`:

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

Mỗi mục có:

| Trường | Mô tả |
|-------|-------------|
| `name` | Tên command (sử dụng sau tiền tố danh mục) |
| `category` | Danh mục command (ví dụ: `app`, `project`) |
| `script` | Tệp Dart trong `lib/app/commands/` |

<div id="options-and-flags"></div>

## Tùy chọn và Cờ

Cấu hình tùy chọn và cờ của command trong phương thức `builder()` bằng `CommandBuilder`.

<div id="adding-options"></div>

### Thêm tùy chọn

Tùy chọn nhận giá trị từ người dùng:

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

Cách sử dụng:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `name` | `String` | Tên tùy chọn |
| `abbr` | `String?` | Viết tắt một ký tự |
| `help` | `String?` | Văn bản trợ giúp hiển thị với `--help` |
| `allowed` | `List<String>?` | Giới hạn các giá trị cho phép |
| `defaultValue` | `String?` | Giá trị mặc định nếu không cung cấp |

<div id="adding-flags"></div>

### Thêm cờ

Cờ là các chuyển đổi boolean:

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

Cách sử dụng:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `name` | `String` | Tên cờ |
| `abbr` | `String?` | Viết tắt một ký tự |
| `help` | `String?` | Văn bản trợ giúp hiển thị với `--help` |
| `defaultValue` | `bool` | Giá trị mặc định (mặc định: `false`) |

<div id="command-result"></div>

## Kết quả Command

Phương thức `handle()` nhận một đối tượng `CommandResult` với các accessor có kiểu để đọc tùy chọn, cờ và tham số đã phân tích.

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

| Phương thức / Thuộc tính | Trả về | Mô tả |
|-------------------|---------|-------------|
| `getString(String name, {String? defaultValue})` | `String?` | Lấy giá trị chuỗi |
| `getBool(String name, {bool? defaultValue})` | `bool?` | Lấy giá trị boolean |
| `getInt(String name, {int? defaultValue})` | `int?` | Lấy giá trị số nguyên |
| `get<T>(String name)` | `T?` | Lấy giá trị có kiểu |
| `hasForceFlag` | `bool` | Có truyền `--force` hay không |
| `hasHelpFlag` | `bool` | Có truyền `--help` hay không |
| `arguments` | `List<String>` | Tất cả tham số dòng lệnh |
| `rest` | `List<String>` | Tham số còn lại chưa phân tích |

<div id="interactive-input"></div>

## Nhập liệu tương tác

`NyCustomCommand` cung cấp các phương thức để thu thập đầu vào từ người dùng trong terminal.

<div id="text-input"></div>

### Nhập văn bản

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `question` | `String` | Câu hỏi hiển thị |
| `defaultValue` | `String` | Giá trị mặc định nếu người dùng nhấn Enter (mặc định: `''`) |

<div id="confirmation"></div>

### Xác nhận

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `question` | `String` | Câu hỏi có/không |
| `defaultValue` | `bool` | Câu trả lời mặc định (mặc định: `false`) |

<div id="single-selection"></div>

### Chọn đơn

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `question` | `String` | Văn bản prompt |
| `options` | `List<String>` | Các lựa chọn có sẵn |
| `defaultOption` | `String?` | Tùy chọn được chọn sẵn |

<div id="multiple-selection"></div>

### Chọn nhiều

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

Người dùng nhập các số phân cách bằng dấu phẩy hoặc `"all"`.

<div id="secret-input"></div>

### Nhập bí mật

```dart
final apiKey = promptSecret('Enter your API key:');
```

Đầu vào được ẩn khỏi màn hình terminal. Chuyển sang hiển thị nếu chế độ echo không được hỗ trợ.

<div id="output-formatting"></div>

## Định dạng đầu ra

Các phương thức để in đầu ra có kiểu dáng ra console:

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

| Phương thức | Mô tả |
|--------|-------------|
| `info(String message)` | In văn bản màu xanh dương |
| `error(String message)` | In văn bản màu đỏ |
| `success(String message)` | In văn bản màu xanh lá |
| `warning(String message)` | In văn bản màu vàng |
| `line(String message)` | In văn bản thuần (không màu) |
| `newLine([int count = 1])` | In dòng trống |
| `comment(String message)` | In văn bản xám/mờ |
| `alert(String message)` | In hộp cảnh báo có viền |
| `abort([String? message, int exitCode = 1])` | Thoát command với lỗi |

<div id="spinner-and-progress"></div>

## Spinner và thanh tiến trình

Spinner và thanh tiến trình cung cấp phản hồi trực quan trong các thao tác chạy lâu.

<div id="using-withspinner"></div>

### Sử dụng withSpinner

Bọc một tác vụ bất đồng bộ với spinner tự động:

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

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `task` | `Future<T> Function()` | Hàm bất đồng bộ cần thực thi |
| `message` | `String` | Văn bản hiển thị khi spinner chạy |
| `successMessage` | `String?` | Hiển thị khi thành công |
| `errorMessage` | `String?` | Hiển thị khi thất bại |

<div id="manual-spinner-control"></div>

### Điều khiển Spinner thủ công

Cho các quy trình nhiều bước, tạo spinner và điều khiển thủ công:

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

**Phương thức ConsoleSpinner:**

| Phương thức | Mô tả |
|--------|-------------|
| `start([String? message])` | Bắt đầu hoạt ảnh spinner |
| `update(String message)` | Thay đổi thông báo hiển thị |
| `stop({String? completionMessage, bool success = true})` | Dừng spinner |

<div id="progress-bar"></div>

### Thanh tiến trình

Tạo và quản lý thanh tiến trình thủ công:

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**Phương thức ConsoleProgressBar:**

| Phương thức / Thuộc tính | Mô tả |
|-------------------|-------------|
| `start()` | Bắt đầu thanh tiến trình |
| `tick([int amount = 1])` | Tăng tiến trình |
| `update(int value)` | Đặt tiến trình theo giá trị cụ thể |
| `updateMessage(String newMessage)` | Thay đổi thông báo hiển thị |
| `complete([String? completionMessage])` | Hoàn tất với thông báo tùy chọn |
| `stop()` | Dừng mà không hoàn tất |
| `current` | Giá trị tiến trình hiện tại (getter) |
| `percentage` | Tiến trình dạng phần trăm 0-100 (getter) |

<div id="processing-items-with-progress"></div>

### Xử lý item với tiến trình

Xử lý danh sách item với theo dõi tiến trình tự động:

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

## API Helper

Helper `api` cung cấp trình bao bọc đơn giản hóa quanh Dio cho các yêu cầu HTTP:

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

Kết hợp với `withSpinner` để có trải nghiệm tốt hơn:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

`ApiService` hỗ trợ các phương thức `get`, `post`, `put`, `delete`, và `patch`, mỗi phương thức chấp nhận tùy chọn `queryParameters`, `data`, `options`, và `cancelToken`.

<div id="file-system-helpers"></div>

## File System Helper

Các helper hệ thống tệp dựng sẵn để bạn không cần import `dart:io`:

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

| Phương thức | Mô tả |
|--------|-------------|
| `fileExists(String path)` | Trả về `true` nếu tệp tồn tại |
| `directoryExists(String path)` | Trả về `true` nếu thư mục tồn tại |
| `readFile(String path)` | Đọc tệp dạng chuỗi (bất đồng bộ) |
| `readFileSync(String path)` | Đọc tệp dạng chuỗi (đồng bộ) |
| `writeFile(String path, String content)` | Ghi nội dung vào tệp (bất đồng bộ) |
| `writeFileSync(String path, String content)` | Ghi nội dung vào tệp (đồng bộ) |
| `appendFile(String path, String content)` | Nối nội dung vào tệp |
| `ensureDirectory(String path)` | Tạo thư mục nếu chưa tồn tại |
| `deleteFile(String path)` | Xóa tệp |
| `copyFile(String source, String destination)` | Sao chép tệp |

<div id="json-and-yaml-helpers"></div>

## JSON và YAML Helper

Đọc và ghi tệp JSON và YAML với các helper dựng sẵn:

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

| Phương thức | Mô tả |
|--------|-------------|
| `readJson(String path)` | Đọc tệp JSON dạng `Map<String, dynamic>` |
| `readJsonArray(String path)` | Đọc tệp JSON dạng `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Ghi dữ liệu dạng JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Nối vào tệp mảng JSON |
| `readYaml(String path)` | Đọc tệp YAML dạng `Map<String, dynamic>` |

<div id="dart-file-manipulation"></div>

## Thao tác tệp Dart

Các helper để chỉnh sửa tệp nguồn Dart theo chương trình -- hữu ích khi xây dựng công cụ scaffolding:

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

| Phương thức | Mô tả |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Thêm import vào tệp Dart (bỏ qua nếu đã có) |
| `insertBeforeClosingBrace(String filePath, String code)` | Chèn mã trước `}` cuối cùng trong tệp |
| `fileContains(String filePath, String identifier)` | Kiểm tra xem tệp có chứa chuỗi không |
| `fileContainsPattern(String filePath, Pattern pattern)` | Kiểm tra xem tệp có khớp mẫu không |

<div id="directory-helpers"></div>

## Directory Helper

Các helper để làm việc với thư mục và tìm tệp:

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

| Phương thức | Mô tả |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Liệt kê nội dung thư mục |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Tìm tệp khớp tiêu chí |
| `deleteDirectory(String path)` | Xóa thư mục đệ quy |
| `copyDirectory(String source, String destination)` | Sao chép thư mục đệ quy |

<div id="case-conversion-helpers"></div>

## Helper chuyển đổi kiểu chữ

Chuyển đổi chuỗi giữa các quy ước đặt tên mà không cần import package `recase`:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| Phương thức | Định dạng đầu ra | Ví dụ |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Helper đường dẫn dự án

Các getter cho thư mục dự án {{ config('app.name') }} tiêu chuẩn, trả về đường dẫn tương đối so với thư mục gốc dự án:

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

| Thuộc tính | Đường dẫn |
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
| `projectPath(String relativePath)` | Giải quyết đường dẫn tương đối trong dự án |

<div id="platform-helpers"></div>

## Helper nền tảng

Kiểm tra nền tảng và truy cập biến môi trường:

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

| Thuộc tính / Phương thức | Mô tả |
|-------------------|-------------|
| `isWindows` | `true` nếu đang chạy trên Windows |
| `isMacOS` | `true` nếu đang chạy trên macOS |
| `isLinux` | `true` nếu đang chạy trên Linux |
| `workingDirectory` | Đường dẫn thư mục làm việc hiện tại |
| `env(String key, [String defaultValue = ''])` | Đọc biến môi trường hệ thống |

<div id="dart-and-flutter-commands"></div>

## Lệnh Dart và Flutter

Chạy các lệnh CLI Dart và Flutter phổ biến dưới dạng phương thức helper. Mỗi lệnh trả về mã thoát tiến trình:

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

| Phương thức | Mô tả |
|--------|-------------|
| `dartFormat(String path)` | Chạy `dart format` trên tệp hoặc thư mục |
| `dartAnalyze([String? path])` | Chạy `dart analyze` |
| `flutterPubGet()` | Chạy `flutter pub get` |
| `flutterClean()` | Chạy `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Chạy `flutter build <target>` |
| `flutterTest([String? path])` | Chạy `flutter test` |

<div id="validation-helpers"></div>

## Helper xác thực

Các helper để xác thực và làm sạch đầu vào người dùng cho việc tạo mã:

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

| Phương thức | Mô tả |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Xác thực tên định danh Dart |
| `requireArgument(CommandResult result, {String? message})` | Yêu cầu tham số đầu tiên không rỗng hoặc hủy bỏ |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Làm sạch và chuyển sang PascalCase tên class |
| `cleanFileName(String name, {String extension = '.dart'})` | Làm sạch và chuyển sang snake_case tên tệp |

<div id="file-scaffolding"></div>

## Scaffolding tệp

Tạo một hoặc nhiều tệp với nội dung sử dụng hệ thống scaffolding.

### Tệp đơn

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

| Tham số | Kiểu | Mô tả |
|-----------|------|-------------|
| `path` | `String` | Đường dẫn tệp cần tạo |
| `content` | `String` | Nội dung tệp |
| `force` | `bool` | Ghi đè nếu tồn tại (mặc định: `false`) |
| `successMessage` | `String?` | Thông báo hiển thị khi thành công |

### Nhiều tệp

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

Class `ScaffoldFile`:

| Thuộc tính | Kiểu | Mô tả |
|----------|------|-------------|
| `path` | `String` | Đường dẫn tệp cần tạo |
| `content` | `String` | Nội dung tệp |
| `successMessage` | `String?` | Thông báo hiển thị khi thành công |

<div id="task-runner"></div>

## Trình chạy tác vụ

Chạy một loạt tác vụ có tên với đầu ra trạng thái tự động.

### Trình chạy tác vụ cơ bản

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

### Trình chạy tác vụ với Spinner

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

Class `CommandTask`:

| Thuộc tính | Kiểu | Mặc định | Mô tả |
|----------|------|---------|-------------|
| `name` | `String` | bắt buộc | Tên tác vụ hiển thị trong đầu ra |
| `action` | `Future<void> Function()` | bắt buộc | Hàm bất đồng bộ cần thực thi |
| `stopOnError` | `bool` | `true` | Có dừng các tác vụ còn lại khi thất bại không |

<div id="table-output"></div>

## Xuất bảng

Hiển thị bảng ASCII định dạng trong console:

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

Đầu ra:

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

## Ví dụ

<div id="current-time-command"></div>

### Command thời gian hiện tại

Một command đơn giản hiển thị thời gian hiện tại:

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

### Command tải font

Một command tải và cài đặt Google Fonts vào dự án:

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

### Command pipeline triển khai

Một command chạy pipeline triển khai đầy đủ sử dụng trình chạy tác vụ:

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
