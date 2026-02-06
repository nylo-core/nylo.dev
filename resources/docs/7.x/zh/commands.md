# 命令

---

<a name="section-1"></a>
- [简介](#introduction)
- [创建命令](#creating-commands)
- [命令结构](#command-structure)
- [运行命令](#running-commands)
- [命令注册表](#command-registry)
- [选项和标志](#options-and-flags)
  - [添加选项](#adding-options)
  - [添加标志](#adding-flags)
- [命令结果](#command-result)
- [交互式输入](#interactive-input)
  - [文本输入](#text-input)
  - [确认](#confirmation)
  - [单项选择](#single-selection)
  - [多项选择](#multiple-selection)
  - [隐秘输入](#secret-input)
- [输出格式化](#output-formatting)
- [加载动画和进度条](#spinner-and-progress)
  - [使用 withSpinner](#using-withspinner)
  - [手动控制加载动画](#manual-spinner-control)
  - [进度条](#progress-bar)
  - [带进度处理项目](#processing-items-with-progress)
- [API 辅助工具](#api-helper)
- [文件系统辅助工具](#file-system-helpers)
- [JSON 和 YAML 辅助工具](#json-and-yaml-helpers)
- [Dart 文件操作](#dart-file-manipulation)
- [目录辅助工具](#directory-helpers)
- [大小写转换辅助工具](#case-conversion-helpers)
- [项目路径辅助工具](#project-path-helpers)
- [平台辅助工具](#platform-helpers)
- [Dart 和 Flutter 命令](#dart-and-flutter-commands)
- [验证辅助工具](#validation-helpers)
- [文件脚手架](#file-scaffolding)
- [任务运行器](#task-runner)
- [表格输出](#table-output)
- [示例](#examples)
  - [当前时间命令](#current-time-command)
  - [下载字体命令](#download-fonts-command)
  - [部署流水线命令](#deployment-pipeline-command)

<div id="introduction"></div>

## 简介

命令允许您使用自定义的项目专用工具扩展 {{ config('app.name') }} 的 CLI。通过继承 `NyCustomCommand`，您可以自动化重复任务、构建部署工作流、生成代码，或在终端中直接添加任何您需要的功能。

每个自定义命令都可以访问一组丰富的内置辅助工具，包括文件 I/O、JSON/YAML、交互式提示、加载动画、进度条、API 请求等——无需导入额外的包。

> **注意：** 自定义命令在 Flutter 运行时之外运行。您不能在命令中导入 `nylo_framework.dart`，请改用 `ny_cli.dart`。

<div id="creating-commands"></div>

## 创建命令

使用 Metro 或 Dart CLI 创建新命令：

```bash
metro make:command current_time
```

您可以使用 `--category` 选项为命令指定类别：

```bash
metro make:command current_time --category="project"
```

这将在 `lib/app/commands/current_time.dart` 创建一个新文件，并将其注册到命令注册表中。

<div id="command-structure"></div>

## 命令结构

每个命令都继承 `NyCustomCommand` 并实现两个关键方法：

- **`builder()`** -- 配置选项和标志
- **`handle()`** -- 执行命令逻辑

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

## 运行命令

使用 Metro 或 Dart 运行您的命令：

```bash
metro app:current_time
```

命令名称遵循 `category:name` 的模式。当您不带参数运行 `metro` 时，自定义命令会显示在 **Custom Commands** 部分下：

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

要显示命令的帮助信息：

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## 命令注册表

所有自定义命令都注册在 `lib/app/commands/commands.json` 中。当您使用 `make:command` 时，此文件会自动更新：

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

每个条目包含：

| 字段 | 描述 |
|-------|-------------|
| `name` | 命令名称（在类别前缀之后使用） |
| `category` | 命令类别（例如 `app`、`project`） |
| `script` | `lib/app/commands/` 中的 Dart 文件 |

<div id="options-and-flags"></div>

## 选项和标志

在 `builder()` 方法中使用 `CommandBuilder` 配置命令的选项和标志。

<div id="adding-options"></div>

### 添加选项

选项接受用户提供的值：

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

用法：

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `name` | `String` | 选项名称 |
| `abbr` | `String?` | 单字符缩写 |
| `help` | `String?` | 使用 `--help` 时显示的帮助文本 |
| `allowed` | `List<String>?` | 限制为允许的值 |
| `defaultValue` | `String?` | 未提供时的默认值 |

<div id="adding-flags"></div>

### 添加标志

标志是布尔开关：

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

用法：

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `name` | `String` | 标志名称 |
| `abbr` | `String?` | 单字符缩写 |
| `help` | `String?` | 使用 `--help` 时显示的帮助文本 |
| `defaultValue` | `bool` | 默认值（默认：`false`） |

<div id="command-result"></div>

## 命令结果

`handle()` 方法接收一个 `CommandResult` 对象，该对象提供类型化的访问器用于读取已解析的选项、标志和参数。

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

| 方法 / 属性 | 返回类型 | 描述 |
|-------------------|---------|-------------|
| `getString(String name, {String? defaultValue})` | `String?` | 获取字符串值 |
| `getBool(String name, {bool? defaultValue})` | `bool?` | 获取布尔值 |
| `getInt(String name, {int? defaultValue})` | `int?` | 获取整数值 |
| `get<T>(String name)` | `T?` | 获取类型化的值 |
| `hasForceFlag` | `bool` | 是否传递了 `--force` |
| `hasHelpFlag` | `bool` | 是否传递了 `--help` |
| `arguments` | `List<String>` | 所有命令行参数 |
| `rest` | `List<String>` | 未解析的剩余参数 |

<div id="interactive-input"></div>

## 交互式输入

`NyCustomCommand` 提供了在终端中收集用户输入的方法。

<div id="text-input"></div>

### 文本输入

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `question` | `String` | 要显示的问题 |
| `defaultValue` | `String` | 用户按回车时的默认值（默认：`''`） |

<div id="confirmation"></div>

### 确认

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `question` | `String` | 是/否问题 |
| `defaultValue` | `bool` | 默认答案（默认：`false`） |

<div id="single-selection"></div>

### 单项选择

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `question` | `String` | 提示文本 |
| `options` | `List<String>` | 可用选项 |
| `defaultOption` | `String?` | 预选选项 |

<div id="multiple-selection"></div>

### 多项选择

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

用户输入以逗号分隔的编号或 `"all"`。

<div id="secret-input"></div>

### 隐秘输入

```dart
final apiKey = promptSecret('Enter your API key:');
```

输入内容在终端中不可见。如果不支持回显模式，则回退为可见输入。

<div id="output-formatting"></div>

## 输出格式化

用于在控制台中打印样式化输出的方法：

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

| 方法 | 描述 |
|--------|-------------|
| `info(String message)` | 打印蓝色文本 |
| `error(String message)` | 打印红色文本 |
| `success(String message)` | 打印绿色文本 |
| `warning(String message)` | 打印黄色文本 |
| `line(String message)` | 打印纯文本（无颜色） |
| `newLine([int count = 1])` | 打印空行 |
| `comment(String message)` | 打印灰色/弱化文本 |
| `alert(String message)` | 打印带边框的提醒框 |
| `abort([String? message, int exitCode = 1])` | 以错误退出命令 |

<div id="spinner-and-progress"></div>

## 加载动画和进度条

加载动画和进度条在长时间运行的操作中提供视觉反馈。

<div id="using-withspinner"></div>

### 使用 withSpinner

使用自动加载动画包装异步任务：

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

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `task` | `Future<T> Function()` | 要执行的异步函数 |
| `message` | `String` | 加载动画运行时显示的文本 |
| `successMessage` | `String?` | 成功时显示的文本 |
| `errorMessage` | `String?` | 失败时显示的文本 |

<div id="manual-spinner-control"></div>

### 手动控制加载动画

对于多步骤工作流，创建加载动画并手动控制它：

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

**ConsoleSpinner 方法：**

| 方法 | 描述 |
|--------|-------------|
| `start([String? message])` | 启动加载动画 |
| `update(String message)` | 更改显示的消息 |
| `stop({String? completionMessage, bool success = true})` | 停止加载动画 |

<div id="progress-bar"></div>

### 进度条

手动创建和管理进度条：

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**ConsoleProgressBar 方法：**

| 方法 / 属性 | 描述 |
|-------------------|-------------|
| `start()` | 启动进度条 |
| `tick([int amount = 1])` | 增加进度 |
| `update(int value)` | 将进度设置为特定值 |
| `updateMessage(String newMessage)` | 更改显示的消息 |
| `complete([String? completionMessage])` | 完成并显示可选消息 |
| `stop()` | 停止但不完成 |
| `current` | 当前进度值（getter） |
| `percentage` | 进度百分比 0-100（getter） |

<div id="processing-items-with-progress"></div>

### 带进度处理项目

处理项目列表并自动跟踪进度：

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

## API 辅助工具

`api` 辅助工具提供了对 Dio 的简化封装，用于发起 HTTP 请求：

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

结合 `withSpinner` 使用可以获得更好的用户体验：

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

`ApiService` 支持 `get`、`post`、`put`、`delete` 和 `patch` 方法，每个方法都接受可选的 `queryParameters`、`data`、`options` 和 `cancelToken`。

<div id="file-system-helpers"></div>

## 文件系统辅助工具

内置的文件系统辅助工具，无需导入 `dart:io`：

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

| 方法 | 描述 |
|--------|-------------|
| `fileExists(String path)` | 如果文件存在则返回 `true` |
| `directoryExists(String path)` | 如果目录存在则返回 `true` |
| `readFile(String path)` | 以字符串形式读取文件（异步） |
| `readFileSync(String path)` | 以字符串形式读取文件（同步） |
| `writeFile(String path, String content)` | 将内容写入文件（异步） |
| `writeFileSync(String path, String content)` | 将内容写入文件（同步） |
| `appendFile(String path, String content)` | 向文件追加内容 |
| `ensureDirectory(String path)` | 如果目录不存在则创建 |
| `deleteFile(String path)` | 删除文件 |
| `copyFile(String source, String destination)` | 复制文件 |

<div id="json-and-yaml-helpers"></div>

## JSON 和 YAML 辅助工具

使用内置辅助工具读取和写入 JSON 和 YAML 文件：

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

| 方法 | 描述 |
|--------|-------------|
| `readJson(String path)` | 将 JSON 文件读取为 `Map<String, dynamic>` |
| `readJsonArray(String path)` | 将 JSON 文件读取为 `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | 将数据写入为 JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | 向 JSON 数组文件追加条目 |
| `readYaml(String path)` | 将 YAML 文件读取为 `Map<String, dynamic>` |

<div id="dart-file-manipulation"></div>

## Dart 文件操作

用于以编程方式编辑 Dart 源文件的辅助工具——在构建脚手架工具时非常有用：

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

| 方法 | 描述 |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | 向 Dart 文件添加导入（如已存在则跳过） |
| `insertBeforeClosingBrace(String filePath, String code)` | 在文件最后一个 `}` 之前插入代码 |
| `fileContains(String filePath, String identifier)` | 检查文件是否包含字符串 |
| `fileContainsPattern(String filePath, Pattern pattern)` | 检查文件是否匹配模式 |

<div id="directory-helpers"></div>

## 目录辅助工具

用于处理目录和查找文件的辅助工具：

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

| 方法 | 描述 |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | 列出目录内容 |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | 查找匹配条件的文件 |
| `deleteDirectory(String path)` | 递归删除目录 |
| `copyDirectory(String source, String destination)` | 递归复制目录 |

<div id="case-conversion-helpers"></div>

## 大小写转换辅助工具

在命名约定之间转换字符串，无需导入 `recase` 包：

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| 方法 | 输出格式 | 示例 |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## 项目路径辅助工具

标准 {{ config('app.name') }} 项目目录的 getter，返回相对于项目根目录的路径：

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

| 属性 | 路径 |
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
| `projectPath(String relativePath)` | 解析项目内的相对路径 |

<div id="platform-helpers"></div>

## 平台辅助工具

检查平台并访问环境变量：

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

| 属性 / 方法 | 描述 |
|-------------------|-------------|
| `isWindows` | 如果在 Windows 上运行则为 `true` |
| `isMacOS` | 如果在 macOS 上运行则为 `true` |
| `isLinux` | 如果在 Linux 上运行则为 `true` |
| `workingDirectory` | 当前工作目录路径 |
| `env(String key, [String defaultValue = ''])` | 读取系统环境变量 |

<div id="dart-and-flutter-commands"></div>

## Dart 和 Flutter 命令

将常用的 Dart 和 Flutter CLI 命令作为辅助方法运行。每个方法返回进程退出码：

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

| 方法 | 描述 |
|--------|-------------|
| `dartFormat(String path)` | 对文件或目录运行 `dart format` |
| `dartAnalyze([String? path])` | 运行 `dart analyze` |
| `flutterPubGet()` | 运行 `flutter pub get` |
| `flutterClean()` | 运行 `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | 运行 `flutter build <target>` |
| `flutterTest([String? path])` | 运行 `flutter test` |

<div id="validation-helpers"></div>

## 验证辅助工具

用于验证和清理用户输入以进行代码生成的辅助工具：

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

| 方法 | 描述 |
|--------|-------------|
| `isValidDartIdentifier(String name)` | 验证 Dart 标识符名称 |
| `requireArgument(CommandResult result, {String? message})` | 要求非空的第一个参数，否则中止 |
| `cleanClassName(String name, {List<String> removeSuffixes})` | 清理并将类名转为 PascalCase |
| `cleanFileName(String name, {String extension = '.dart'})` | 清理并将文件名转为 snake_case |

<div id="file-scaffolding"></div>

## 文件脚手架

使用脚手架系统创建一个或多个带内容的文件。

### 单个文件

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

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `path` | `String` | 要创建的文件路径 |
| `content` | `String` | 文件内容 |
| `force` | `bool` | 如果存在则覆盖（默认：`false`） |
| `successMessage` | `String?` | 成功时显示的消息 |

### 多个文件

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

`ScaffoldFile` 类：

| 属性 | 类型 | 描述 |
|----------|------|-------------|
| `path` | `String` | 要创建的文件路径 |
| `content` | `String` | 文件内容 |
| `successMessage` | `String?` | 成功时显示的消息 |

<div id="task-runner"></div>

## 任务运行器

运行一系列命名任务并自动输出状态。

### 基本任务运行器

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

### 带加载动画的任务运行器

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

`CommandTask` 类：

| 属性 | 类型 | 默认值 | 描述 |
|----------|------|---------|-------------|
| `name` | `String` | 必填 | 输出中显示的任务名称 |
| `action` | `Future<void> Function()` | 必填 | 要执行的异步函数 |
| `stopOnError` | `bool` | `true` | 失败时是否停止剩余任务 |

<div id="table-output"></div>

## 表格输出

在控制台中显示格式化的 ASCII 表格：

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

输出：

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

## 示例

<div id="current-time-command"></div>

### 当前时间命令

一个显示当前时间的简单命令：

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

### 下载字体命令

一个将 Google Fonts 下载并安装到项目中的命令：

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

### 部署流水线命令

一个使用任务运行器执行完整部署流水线的命令：

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
