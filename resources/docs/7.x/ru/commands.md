# Commands

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Создание команд](#creating-commands "Создание команд")
- [Структура команды](#command-structure "Структура команды")
- [Запуск команд](#running-commands "Запуск команд")
- [Реестр команд](#command-registry "Реестр команд")
- [Опции и флаги](#options-and-flags "Опции и флаги")
  - [Добавление опций](#adding-options "Добавление опций")
  - [Добавление флагов](#adding-flags "Добавление флагов")
- [Результат команды](#command-result "Результат команды")
- [Интерактивный ввод](#interactive-input "Интерактивный ввод")
  - [Текстовый ввод](#text-input "Текстовый ввод")
  - [Подтверждение](#confirmation "Подтверждение")
  - [Одиночный выбор](#single-selection "Одиночный выбор")
  - [Множественный выбор](#multiple-selection "Множественный выбор")
  - [Секретный ввод](#secret-input "Секретный ввод")
- [Форматирование вывода](#output-formatting "Форматирование вывода")
- [Спиннер и прогресс](#spinner-and-progress "Спиннер и прогресс")
  - [Использование withSpinner](#using-withspinner "Использование withSpinner")
  - [Ручное управление спиннером](#manual-spinner-control "Ручное управление спиннером")
  - [Индикатор прогресса](#progress-bar "Индикатор прогресса")
  - [Обработка элементов с прогрессом](#processing-items-with-progress "Обработка элементов с прогрессом")
- [Помощник для API](#api-helper "Помощник для API")
- [Помощники файловой системы](#file-system-helpers "Помощники файловой системы")
- [Помощники JSON и YAML](#json-and-yaml-helpers "Помощники JSON и YAML")
- [Манипуляции с Dart-файлами](#dart-file-manipulation "Манипуляции с Dart-файлами")
- [Помощники для директорий](#directory-helpers "Помощники для директорий")
- [Помощники преобразования регистра](#case-conversion-helpers "Помощники преобразования регистра")
- [Помощники путей проекта](#project-path-helpers "Помощники путей проекта")
- [Помощники платформы](#platform-helpers "Помощники платформы")
- [Команды Dart и Flutter](#dart-and-flutter-commands "Команды Dart и Flutter")
- [Помощники валидации](#validation-helpers "Помощники валидации")
- [Генерация файлов](#file-scaffolding "Генерация файлов")
- [Запуск задач](#task-runner "Запуск задач")
- [Табличный вывод](#table-output "Табличный вывод")
- [Примеры](#examples "Примеры")
  - [Команда текущего времени](#current-time-command "Команда текущего времени")
  - [Команда загрузки шрифтов](#download-fonts-command "Команда загрузки шрифтов")
  - [Команда конвейера развёртывания](#deployment-pipeline-command "Команда конвейера развёртывания")

<div id="introduction"></div>

## Введение

Команды позволяют расширять CLI {{ config('app.name') }} собственным инструментарием, специфичным для проекта. Наследуя `NyCustomCommand`, вы можете автоматизировать рутинные задачи, создавать рабочие процессы развёртывания, генерировать код или добавлять любой необходимый функционал прямо из терминала.

Каждая пользовательская команда имеет доступ к богатому набору встроенных помощников для файлового ввода-вывода, JSON/YAML, интерактивных запросов, спиннеров, индикаторов прогресса, API-запросов и многого другого -- без необходимости импортировать дополнительные пакеты.

> **Примечание:** Пользовательские команды выполняются вне среды Flutter. Вы не можете импортировать `nylo_framework.dart` в своих командах. Используйте вместо этого `ny_cli.dart`.

<div id="creating-commands"></div>

## Создание команд

Создайте новую команду с помощью Metro или Dart CLI:

```bash
metro make:command current_time
```

Вы можете указать категорию для команды с помощью опции `--category`:

```bash
metro make:command current_time --category="project"
```

Эта команда создаёт новый файл `lib/app/commands/current_time.dart` и регистрирует его в реестре команд.

<div id="command-structure"></div>

## Структура команды

Каждая команда наследует `NyCustomCommand` и реализует два ключевых метода:

- **`builder()`** -- настройка опций и флагов
- **`handle()`** -- выполнение логики команды

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

## Запуск команд

Запустите команду с помощью Metro:

```bash
metro app:current_time
```

Имя команды следует шаблону `категория:имя`. Когда вы запускаете `metro` без аргументов, ваши пользовательские команды отображаются в разделе **Custom Commands**:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

Для отображения справки по команде:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## Реестр команд

Все пользовательские команды регистрируются в `lib/app/commands/commands.json`. Этот файл обновляется автоматически при использовании `make:command`:

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

Каждая запись содержит:

| Поле | Описание |
|------|----------|
| `name` | Имя команды (используется после префикса категории) |
| `category` | Категория команды (например, `app`, `project`) |
| `script` | Dart-файл в `lib/app/commands/` |

<div id="options-and-flags"></div>

## Опции и флаги

Настраивайте опции и флаги команды в методе `builder()` с помощью `CommandBuilder`.

<div id="adding-options"></div>

### Добавление опций

Опции принимают значение от пользователя:

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

Использование:

```bash
metro project:deploy --environment=production
# или с сокращением
metro project:deploy -e production
```

| Параметр | Тип | Описание |
|----------|-----|----------|
| `name` | `String` | Имя опции |
| `abbr` | `String?` | Однобуквенное сокращение |
| `help` | `String?` | Текст справки для `--help` |
| `allowed` | `List<String>?` | Ограничение допустимых значений |
| `defaultValue` | `String?` | Значение по умолчанию |

<div id="adding-flags"></div>

### Добавление флагов

Флаги -- это булевые переключатели:

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

Использование:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| Параметр | Тип | Описание |
|----------|-----|----------|
| `name` | `String` | Имя флага |
| `abbr` | `String?` | Однобуквенное сокращение |
| `help` | `String?` | Текст справки для `--help` |
| `defaultValue` | `bool` | Значение по умолчанию (по умолчанию: `false`) |

<div id="command-result"></div>

## Результат команды

Метод `handle()` получает объект `CommandResult` с типизированными методами доступа для чтения разобранных опций, флагов и аргументов.

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

| Метод / Свойство | Возвращает | Описание |
|------------------|------------|----------|
| `getString(String name, {String? defaultValue})` | `String?` | Получить строковое значение |
| `getBool(String name, {bool? defaultValue})` | `bool?` | Получить булево значение |
| `getInt(String name, {int? defaultValue})` | `int?` | Получить целочисленное значение |
| `get<T>(String name)` | `T?` | Получить типизированное значение |
| `hasForceFlag` | `bool` | Был ли передан `--force` |
| `hasHelpFlag` | `bool` | Был ли передан `--help` |
| `arguments` | `List<String>` | Все аргументы командной строки |
| `rest` | `List<String>` | Неразобранные аргументы |

<div id="interactive-input"></div>

## Интерактивный ввод

`NyCustomCommand` предоставляет методы для сбора пользовательского ввода в терминале.

<div id="text-input"></div>

### Текстовый ввод

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| Параметр | Тип | Описание |
|----------|-----|----------|
| `question` | `String` | Отображаемый вопрос |
| `defaultValue` | `String` | Значение по умолчанию при нажатии Enter (по умолчанию: `''`) |

<div id="confirmation"></div>

### Подтверждение

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| Параметр | Тип | Описание |
|----------|-----|----------|
| `question` | `String` | Вопрос да/нет |
| `defaultValue` | `bool` | Ответ по умолчанию (по умолчанию: `false`) |

<div id="single-selection"></div>

### Одиночный выбор

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| Параметр | Тип | Описание |
|----------|-----|----------|
| `question` | `String` | Текст приглашения |
| `options` | `List<String>` | Доступные варианты |
| `defaultOption` | `String?` | Предварительно выбранный вариант |

<div id="multiple-selection"></div>

### Множественный выбор

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

Пользователь вводит номера через запятую или `"all"`.

<div id="secret-input"></div>

### Секретный ввод

```dart
final apiKey = promptSecret('Enter your API key:');
```

Ввод скрыт от отображения в терминале. При отсутствии поддержки режима скрытия ввод отображается видимым.

<div id="output-formatting"></div>

## Форматирование вывода

Методы для печати стилизованного вывода в консоль:

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

| Метод | Описание |
|-------|----------|
| `info(String message)` | Печать синего текста |
| `error(String message)` | Печать красного текста |
| `success(String message)` | Печать зелёного текста |
| `warning(String message)` | Печать жёлтого текста |
| `line(String message)` | Печать обычного текста (без цвета) |
| `newLine([int count = 1])` | Печать пустых строк |
| `comment(String message)` | Печать серого/приглушённого текста |
| `alert(String message)` | Печать обрамлённого блока оповещения |
| `abort([String? message, int exitCode = 1])` | Выход из команды с ошибкой |

<div id="spinner-and-progress"></div>

## Спиннер и прогресс

Спиннеры и индикаторы прогресса обеспечивают визуальную обратную связь во время длительных операций.

<div id="using-withspinner"></div>

### Использование withSpinner

Оберните асинхронную задачу автоматическим спиннером:

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

| Параметр | Тип | Описание |
|----------|-----|----------|
| `task` | `Future<T> Function()` | Асинхронная функция для выполнения |
| `message` | `String` | Текст во время работы спиннера |
| `successMessage` | `String?` | Отображается при успехе |
| `errorMessage` | `String?` | Отображается при ошибке |

<div id="manual-spinner-control"></div>

### Ручное управление спиннером

Для многоэтапных процессов создайте спиннер и управляйте им вручную:

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

**Методы ConsoleSpinner:**

| Метод | Описание |
|-------|----------|
| `start([String? message])` | Запустить анимацию спиннера |
| `update(String message)` | Изменить отображаемое сообщение |
| `stop({String? completionMessage, bool success = true})` | Остановить спиннер |

<div id="progress-bar"></div>

### Индикатор прогресса

Создание и управление индикатором прогресса вручную:

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**Методы ConsoleProgressBar:**

| Метод / Свойство | Описание |
|------------------|----------|
| `start()` | Запустить индикатор прогресса |
| `tick([int amount = 1])` | Увеличить прогресс |
| `update(int value)` | Установить прогресс на определённое значение |
| `updateMessage(String newMessage)` | Изменить отображаемое сообщение |
| `complete([String? completionMessage])` | Завершить с необязательным сообщением |
| `stop()` | Остановить без завершения |
| `current` | Текущее значение прогресса (геттер) |
| `percentage` | Прогресс в процентах 0-100 (геттер) |

<div id="processing-items-with-progress"></div>

### Обработка элементов с прогрессом

Обработка списка элементов с автоматическим отслеживанием прогресса:

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

## Помощник для API

Помощник `api` предоставляет упрощённую обёртку вокруг Dio для выполнения HTTP-запросов:

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

Комбинируйте с `withSpinner` для лучшего пользовательского опыта:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

`ApiService` поддерживает методы `get`, `post`, `put`, `delete` и `patch`, каждый из которых принимает необязательные `queryParameters`, `data`, `options` и `cancelToken`.

<div id="file-system-helpers"></div>

## Помощники файловой системы

Встроенные помощники файловой системы, чтобы не нужно было импортировать `dart:io`:

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

| Метод | Описание |
|-------|----------|
| `fileExists(String path)` | Возвращает `true`, если файл существует |
| `directoryExists(String path)` | Возвращает `true`, если директория существует |
| `readFile(String path)` | Чтение файла как строки (асинхронно) |
| `readFileSync(String path)` | Чтение файла как строки (синхронно) |
| `writeFile(String path, String content)` | Запись содержимого в файл (асинхронно) |
| `writeFileSync(String path, String content)` | Запись содержимого в файл (синхронно) |
| `appendFile(String path, String content)` | Добавление содержимого в файл |
| `ensureDirectory(String path)` | Создание директории, если она не существует |
| `deleteFile(String path)` | Удаление файла |
| `copyFile(String source, String destination)` | Копирование файла |

<div id="json-and-yaml-helpers"></div>

## Помощники JSON и YAML

Чтение и запись файлов JSON и YAML с помощью встроенных помощников:

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

| Метод | Описание |
|-------|----------|
| `readJson(String path)` | Чтение JSON-файла как `Map<String, dynamic>` |
| `readJsonArray(String path)` | Чтение JSON-файла как `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Запись данных в формате JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Добавление в файл JSON-массива |
| `readYaml(String path)` | Чтение YAML-файла как `Map<String, dynamic>` |

<div id="dart-file-manipulation"></div>

## Манипуляции с Dart-файлами

Помощники для программного редактирования исходных файлов Dart -- полезны при создании инструментов генерации кода:

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

| Метод | Описание |
|-------|----------|
| `addImport(String filePath, String importStatement)` | Добавление импорта в Dart-файл (пропускает при наличии) |
| `insertBeforeClosingBrace(String filePath, String code)` | Вставка кода перед последней `}` в файле |
| `fileContains(String filePath, String identifier)` | Проверка наличия строки в файле |
| `fileContainsPattern(String filePath, Pattern pattern)` | Проверка соответствия файла шаблону |

<div id="directory-helpers"></div>

## Помощники для директорий

Помощники для работы с директориями и поиска файлов:

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

| Метод | Описание |
|-------|----------|
| `listDirectory(String path, {bool recursive = false})` | Просмотр содержимого директории |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Поиск файлов по критериям |
| `deleteDirectory(String path)` | Рекурсивное удаление директории |
| `copyDirectory(String source, String destination)` | Рекурсивное копирование директории |

<div id="case-conversion-helpers"></div>

## Помощники преобразования регистра

Преобразование строк между соглашениями об именовании без импорта пакета `recase`:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| Метод | Формат вывода | Пример |
|-------|---------------|--------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Помощники путей проекта

Геттеры для стандартных директорий проекта {{ config('app.name') }}, возвращающие пути относительно корня проекта:

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

| Свойство | Путь |
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
| `projectPath(String relativePath)` | Разрешает относительный путь внутри проекта |

<div id="platform-helpers"></div>

## Помощники платформы

Проверка платформы и доступ к переменным окружения:

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

| Свойство / Метод | Описание |
|------------------|----------|
| `isWindows` | `true`, если запущено на Windows |
| `isMacOS` | `true`, если запущено на macOS |
| `isLinux` | `true`, если запущено на Linux |
| `workingDirectory` | Путь текущей рабочей директории |
| `env(String key, [String defaultValue = ''])` | Чтение переменной окружения системы |

<div id="dart-and-flutter-commands"></div>

## Команды Dart и Flutter

Запуск распространённых команд CLI Dart и Flutter как вспомогательных методов. Каждый возвращает код завершения процесса:

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

| Метод | Описание |
|-------|----------|
| `dartFormat(String path)` | Выполнить `dart format` для файла или директории |
| `dartAnalyze([String? path])` | Выполнить `dart analyze` |
| `flutterPubGet()` | Выполнить `flutter pub get` |
| `flutterClean()` | Выполнить `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Выполнить `flutter build <target>` |
| `flutterTest([String? path])` | Выполнить `flutter test` |

<div id="validation-helpers"></div>

## Помощники валидации

Помощники для валидации и очистки пользовательского ввода при генерации кода:

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

| Метод | Описание |
|-------|----------|
| `isValidDartIdentifier(String name)` | Проверка корректности идентификатора Dart |
| `requireArgument(CommandResult result, {String? message})` | Требование непустого первого аргумента или прерывание |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Очистка и преобразование имени класса в PascalCase |
| `cleanFileName(String name, {String extension = '.dart'})` | Очистка и преобразование имени файла в snake_case |

<div id="file-scaffolding"></div>

## Генерация файлов

Создание одного или нескольких файлов с содержимым с помощью системы генерации.

### Один файл

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

| Параметр | Тип | Описание |
|----------|-----|----------|
| `path` | `String` | Путь создаваемого файла |
| `content` | `String` | Содержимое файла |
| `force` | `bool` | Перезаписать, если существует (по умолчанию: `false`) |
| `successMessage` | `String?` | Сообщение при успешном создании |

### Несколько файлов

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

Класс `ScaffoldFile`:

| Свойство | Тип | Описание |
|----------|-----|----------|
| `path` | `String` | Путь создаваемого файла |
| `content` | `String` | Содержимое файла |
| `successMessage` | `String?` | Сообщение при успешном создании |

<div id="task-runner"></div>

## Запуск задач

Запуск серии именованных задач с автоматическим выводом статуса.

### Базовый запуск задач

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

### Запуск задач со спиннером

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

Класс `CommandTask`:

| Свойство | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `name` | `String` | обязательный | Имя задачи, отображаемое в выводе |
| `action` | `Future<void> Function()` | обязательный | Асинхронная функция для выполнения |
| `stopOnError` | `bool` | `true` | Останавливать ли оставшиеся задачи при ошибке |

<div id="table-output"></div>

## Табличный вывод

Отображение форматированных ASCII-таблиц в консоли:

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

Вывод:

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

## Примеры

<div id="current-time-command"></div>

### Команда текущего времени

Простая команда, отображающая текущее время:

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

### Команда загрузки шрифтов

Команда, которая загружает и устанавливает Google Fonts в проект:

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

### Команда конвейера развёртывания

Команда, которая запускает полный конвейер развёртывания с помощью запуска задач:

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
