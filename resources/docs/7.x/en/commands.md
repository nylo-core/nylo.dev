# Commands

---

<a name="section-1"></a>
- [Introduction](#introduction)
- [Creating Commands](#creating-commands)
- [Command Structure](#command-structure)
- [Running Commands](#running-commands)
- [Command Registry](#command-registry)
- [Options and Flags](#options-and-flags)
  - [Adding Options](#adding-options)
  - [Adding Flags](#adding-flags)
- [Command Result](#command-result)
- [Interactive Input](#interactive-input)
  - [Text Input](#text-input)
  - [Confirmation](#confirmation)
  - [Single Selection](#single-selection)
  - [Multiple Selection](#multiple-selection)
  - [Secret Input](#secret-input)
- [Output Formatting](#output-formatting)
- [Spinner and Progress](#spinner-and-progress)
  - [Using withSpinner](#using-withspinner)
  - [Manual Spinner Control](#manual-spinner-control)
  - [Progress Bar](#progress-bar)
  - [Processing Items with Progress](#processing-items-with-progress)
- [API Helper](#api-helper)
- [File System Helpers](#file-system-helpers)
- [JSON and YAML Helpers](#json-and-yaml-helpers)
- [Dart File Manipulation](#dart-file-manipulation)
- [Directory Helpers](#directory-helpers)
- [Case Conversion Helpers](#case-conversion-helpers)
- [Project Path Helpers](#project-path-helpers)
- [Platform Helpers](#platform-helpers)
- [Dart and Flutter Commands](#dart-and-flutter-commands)
- [Validation Helpers](#validation-helpers)
- [File Scaffolding](#file-scaffolding)
- [Task Runner](#task-runner)
- [Table Output](#table-output)
- [Examples](#examples)
  - [Current Time Command](#current-time-command)
  - [Download Fonts Command](#download-fonts-command)
  - [Deployment Pipeline Command](#deployment-pipeline-command)

<div id="introduction"></div>

## Introduction

Commands let you extend {{ config('app.name') }}'s CLI with custom project-specific tooling. By subclassing `NyCustomCommand`, you can automate repetitive tasks, build deployment workflows, generate code, or add any functionality you need directly in your terminal.

Every custom command has access to a rich set of built-in helpers for file I/O, JSON/YAML, interactive prompts, spinners, progress bars, API requests, and more -- all without importing extra packages.

> **Note:** Custom commands run outside the Flutter runtime. You cannot import `nylo_framework.dart` in your commands. Use `ny_cli.dart` instead.

<div id="creating-commands"></div>

## Creating Commands

Create a new command using Metro or the Dart CLI:

```bash
metro make:command current_time
```

You can specify a category for your command using the `--category` option:

```bash
metro make:command current_time --category="project"
```

This creates a new file at `lib/app/commands/current_time.dart` and registers it in the command registry.

<div id="command-structure"></div>

## Command Structure

Every command extends `NyCustomCommand` and implements two key methods:

- **`builder()`** -- configure options and flags
- **`handle()`** -- execute the command logic

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

## Running Commands

Run your command using Metro:

```bash
metro app:current_time
```

The command name follows the pattern `category:name`. When you run `metro` without arguments, your custom commands appear under the **Custom Commands** section:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

To display help for a command:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## Command Registry

All custom commands are registered in `lib/app/commands/commands.json`. This file is updated automatically when you use `make:command`:

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

Each entry has:

| Field | Description |
|-------|-------------|
| `name` | The command name (used after the category prefix) |
| `category` | The command category (e.g. `app`, `project`) |
| `script` | The Dart file in `lib/app/commands/` |

<div id="options-and-flags"></div>

## Options and Flags

Configure your command's options and flags in the `builder()` method using `CommandBuilder`.

<div id="adding-options"></div>

### Adding Options

Options accept a value from the user:

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

Usage:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `name` | `String` | Option name |
| `abbr` | `String?` | Single-character abbreviation |
| `help` | `String?` | Help text shown with `--help` |
| `allowed` | `List<String>?` | Restrict to allowed values |
| `defaultValue` | `String?` | Default if not provided |

<div id="adding-flags"></div>

### Adding Flags

Flags are boolean toggles:

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

Usage:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `name` | `String` | Flag name |
| `abbr` | `String?` | Single-character abbreviation |
| `help` | `String?` | Help text shown with `--help` |
| `defaultValue` | `bool` | Default value (default: `false`) |

<div id="command-result"></div>

## Command Result

The `handle()` method receives a `CommandResult` object with typed accessors for reading parsed options, flags, and arguments.

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

| Method / Property | Returns | Description |
|-------------------|---------|-------------|
| `getString(String name, {String? defaultValue})` | `String?` | Get string value |
| `getBool(String name, {bool? defaultValue})` | `bool?` | Get boolean value |
| `getInt(String name, {int? defaultValue})` | `int?` | Get integer value |
| `get<T>(String name)` | `T?` | Get typed value |
| `hasForceFlag` | `bool` | Whether `--force` was passed |
| `hasHelpFlag` | `bool` | Whether `--help` was passed |
| `arguments` | `List<String>` | All command-line arguments |
| `rest` | `List<String>` | Unparsed rest arguments |

<div id="interactive-input"></div>

## Interactive Input

`NyCustomCommand` provides methods for collecting user input in the terminal.

<div id="text-input"></div>

### Text Input

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `question` | `String` | The question to display |
| `defaultValue` | `String` | Default if user presses Enter (default: `''`) |

<div id="confirmation"></div>

### Confirmation

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `question` | `String` | The yes/no question |
| `defaultValue` | `bool` | Default answer (default: `false`) |

<div id="single-selection"></div>

### Single Selection

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `question` | `String` | The prompt text |
| `options` | `List<String>` | Available choices |
| `defaultOption` | `String?` | Pre-selected option |

<div id="multiple-selection"></div>

### Multiple Selection

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

The user enters comma-separated numbers or `"all"`.

<div id="secret-input"></div>

### Secret Input

```dart
final apiKey = promptSecret('Enter your API key:');
```

Input is hidden from the terminal display. Falls back to visible input if echo mode is not supported.

<div id="output-formatting"></div>

## Output Formatting

Methods for printing styled output to the console:

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

| Method | Description |
|--------|-------------|
| `info(String message)` | Print blue text |
| `error(String message)` | Print red text |
| `success(String message)` | Print green text |
| `warning(String message)` | Print yellow text |
| `line(String message)` | Print plain text (no color) |
| `newLine([int count = 1])` | Print blank lines |
| `comment(String message)` | Print gray/muted text |
| `alert(String message)` | Print a bordered alert box |
| `abort([String? message, int exitCode = 1])` | Exit the command with an error |

<div id="spinner-and-progress"></div>

## Spinner and Progress

Spinners and progress bars provide visual feedback during long-running operations.

<div id="using-withspinner"></div>

### Using withSpinner

Wrap an async task with an automatic spinner:

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

| Parameter | Type | Description |
|-----------|------|-------------|
| `task` | `Future<T> Function()` | The async function to execute |
| `message` | `String` | Text shown while spinner runs |
| `successMessage` | `String?` | Shown on success |
| `errorMessage` | `String?` | Shown on failure |

<div id="manual-spinner-control"></div>

### Manual Spinner Control

For multi-step workflows, create a spinner and control it manually:

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

**ConsoleSpinner methods:**

| Method | Description |
|--------|-------------|
| `start([String? message])` | Start the spinner animation |
| `update(String message)` | Change the displayed message |
| `stop({String? completionMessage, bool success = true})` | Stop the spinner |

<div id="progress-bar"></div>

### Progress Bar

Create and manage a progress bar manually:

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**ConsoleProgressBar methods:**

| Method / Property | Description |
|-------------------|-------------|
| `start()` | Start the progress bar |
| `tick([int amount = 1])` | Increment progress |
| `update(int value)` | Set progress to a specific value |
| `updateMessage(String newMessage)` | Change the displayed message |
| `complete([String? completionMessage])` | Complete with optional message |
| `stop()` | Stop without completing |
| `current` | Current progress value (getter) |
| `percentage` | Progress as a percentage 0-100 (getter) |

<div id="processing-items-with-progress"></div>

### Processing Items with Progress

Process a list of items with automatic progress tracking:

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

The `api` helper provides a simplified wrapper around Dio for making HTTP requests:

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

Combine with `withSpinner` for a better user experience:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

The `ApiService` supports `get`, `post`, `put`, `delete`, and `patch` methods, each accepting optional `queryParameters`, `data`, `options`, and `cancelToken`.

<div id="file-system-helpers"></div>

## File System Helpers

Built-in file system helpers so you don't need to import `dart:io`:

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

| Method | Description |
|--------|-------------|
| `fileExists(String path)` | Returns `true` if the file exists |
| `directoryExists(String path)` | Returns `true` if the directory exists |
| `readFile(String path)` | Read file as string (async) |
| `readFileSync(String path)` | Read file as string (sync) |
| `writeFile(String path, String content)` | Write content to file (async) |
| `writeFileSync(String path, String content)` | Write content to file (sync) |
| `appendFile(String path, String content)` | Append content to file |
| `ensureDirectory(String path)` | Create directory if it doesn't exist |
| `deleteFile(String path)` | Delete a file |
| `copyFile(String source, String destination)` | Copy a file |

<div id="json-and-yaml-helpers"></div>

## JSON and YAML Helpers

Read and write JSON and YAML files with built-in helpers:

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

| Method | Description |
|--------|-------------|
| `readJson(String path)` | Read JSON file as `Map<String, dynamic>` |
| `readJsonArray(String path)` | Read JSON file as `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Write data as JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Append to a JSON array file |
| `readYaml(String path)` | Read YAML file as `Map<String, dynamic>` |

<div id="dart-file-manipulation"></div>

## Dart File Manipulation

Helpers for programmatically editing Dart source files -- useful when building scaffolding tools:

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

| Method | Description |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Add import to Dart file (skips if present) |
| `insertBeforeClosingBrace(String filePath, String code)` | Insert code before last `}` in file |
| `fileContains(String filePath, String identifier)` | Check if file contains a string |
| `fileContainsPattern(String filePath, Pattern pattern)` | Check if file matches a pattern |

<div id="directory-helpers"></div>

## Directory Helpers

Helpers for working with directories and finding files:

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

| Method | Description |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | List directory contents |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Find files matching criteria |
| `deleteDirectory(String path)` | Delete directory recursively |
| `copyDirectory(String source, String destination)` | Copy directory recursively |

<div id="case-conversion-helpers"></div>

## Case Conversion Helpers

Convert strings between naming conventions without importing the `recase` package:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| Method | Output Format | Example |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Project Path Helpers

Getters for standard {{ config('app.name') }} project directories, returning paths relative to the project root:

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

| Property | Path |
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
| `projectPath(String relativePath)` | Resolves a relative path within the project |

<div id="platform-helpers"></div>

## Platform Helpers

Check the platform and access environment variables:

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

| Property / Method | Description |
|-------------------|-------------|
| `isWindows` | `true` if running on Windows |
| `isMacOS` | `true` if running on macOS |
| `isLinux` | `true` if running on Linux |
| `workingDirectory` | Current working directory path |
| `env(String key, [String defaultValue = ''])` | Read system environment variable |

<div id="dart-and-flutter-commands"></div>

## Dart and Flutter Commands

Run common Dart and Flutter CLI commands as helper methods. Each returns the process exit code:

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

| Method | Description |
|--------|-------------|
| `dartFormat(String path)` | Run `dart format` on a file or directory |
| `dartAnalyze([String? path])` | Run `dart analyze` |
| `flutterPubGet()` | Run `flutter pub get` |
| `flutterClean()` | Run `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Run `flutter build <target>` |
| `flutterTest([String? path])` | Run `flutter test` |

<div id="validation-helpers"></div>

## Validation Helpers

Helpers for validating and cleaning user input for code generation:

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

| Method | Description |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Validate a Dart identifier name |
| `requireArgument(CommandResult result, {String? message})` | Require non-empty first argument or abort |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Clean and PascalCase a class name |
| `cleanFileName(String name, {String extension = '.dart'})` | Clean and snake_case a file name |

<div id="file-scaffolding"></div>

## File Scaffolding

Create one or many files with content using the scaffolding system.

### Single File

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

| Parameter | Type | Description |
|-----------|------|-------------|
| `path` | `String` | File path to create |
| `content` | `String` | File content |
| `force` | `bool` | Overwrite if exists (default: `false`) |
| `successMessage` | `String?` | Message shown on success |

### Multiple Files

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

The `ScaffoldFile` class:

| Property | Type | Description |
|----------|------|-------------|
| `path` | `String` | File path to create |
| `content` | `String` | File content |
| `successMessage` | `String?` | Message shown on success |

<div id="task-runner"></div>

## Task Runner

Run a series of named tasks with automatic status output.

### Basic Task Runner

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

### Task Runner with Spinner

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

The `CommandTask` class:

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `name` | `String` | required | Task name shown in output |
| `action` | `Future<void> Function()` | required | Async function to execute |
| `stopOnError` | `bool` | `true` | Whether to stop remaining tasks on failure |

<div id="table-output"></div>

## Table Output

Display formatted ASCII tables in the console:

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

Output:

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

## Examples

<div id="current-time-command"></div>

### Current Time Command

A simple command that displays the current time:

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

### Download Fonts Command

A command that downloads and installs Google Fonts into the project:

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

### Deployment Pipeline Command

A command that runs a full deployment pipeline using the task runner:

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
