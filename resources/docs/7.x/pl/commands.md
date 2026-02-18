# Commands

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Tworzenie komend](#creating-commands "Tworzenie komend")
- [Struktura komendy](#command-structure "Struktura komendy")
- [Uruchamianie komend](#running-commands "Uruchamianie komend")
- [Rejestr komend](#command-registry "Rejestr komend")
- [Opcje i flagi](#options-and-flags "Opcje i flagi")
  - [Dodawanie opcji](#adding-options "Dodawanie opcji")
  - [Dodawanie flag](#adding-flags "Dodawanie flag")
- [Wynik komendy](#command-result "Wynik komendy")
- [Interaktywne wejście](#interactive-input "Interaktywne wejście")
  - [Wejście tekstowe](#text-input "Wejście tekstowe")
  - [Potwierdzenie](#confirmation "Potwierdzenie")
  - [Pojedynczy wybór](#single-selection "Pojedynczy wybór")
  - [Wielokrotny wybór](#multiple-selection "Wielokrotny wybór")
  - [Tajne wejście](#secret-input "Tajne wejście")
- [Formatowanie wyjścia](#output-formatting "Formatowanie wyjścia")
- [Spinner i postęp](#spinner-and-progress "Spinner i postęp")
  - [Użycie withSpinner](#using-withspinner "Użycie withSpinner")
  - [Ręczna kontrola spinnera](#manual-spinner-control "Ręczna kontrola spinnera")
  - [Pasek postępu](#progress-bar "Pasek postępu")
  - [Przetwarzanie elementów z postępem](#processing-items-with-progress "Przetwarzanie elementów z postępem")
- [Helper API](#api-helper "Helper API")
- [Helpery systemu plików](#file-system-helpers "Helpery systemu plików")
- [Helpery JSON i YAML](#json-and-yaml-helpers "Helpery JSON i YAML")
- [Manipulacja plikami Dart](#dart-file-manipulation "Manipulacja plikami Dart")
- [Helpery katalogów](#directory-helpers "Helpery katalogów")
- [Helpery konwersji wielkości liter](#case-conversion-helpers "Helpery konwersji wielkości liter")
- [Helpery ścieżek projektu](#project-path-helpers "Helpery ścieżek projektu")
- [Helpery platformy](#platform-helpers "Helpery platformy")
- [Komendy Dart i Flutter](#dart-and-flutter-commands "Komendy Dart i Flutter")
- [Helpery walidacji](#validation-helpers "Helpery walidacji")
- [Scaffolding plików](#file-scaffolding "Scaffolding plików")
- [Task Runner](#task-runner "Task Runner")
- [Wyjście tabelaryczne](#table-output "Wyjście tabelaryczne")
- [Przykłady](#examples "Przykłady")
  - [Komenda aktualnego czasu](#current-time-command "Komenda aktualnego czasu")
  - [Komenda pobierania czcionek](#download-fonts-command "Komenda pobierania czcionek")
  - [Komenda pipeline deploymentu](#deployment-pipeline-command "Komenda pipeline deploymentu")

<div id="introduction"></div>

## Wprowadzenie

Komendy pozwalają rozszerzać CLI {{ config('app.name') }} o niestandardowe narzędzia specyficzne dla projektu. Poprzez dziedziczenie z `NyCustomCommand` możesz automatyzować powtarzalne zadania, budować przepływy pracy deploymentu, generować kod lub dodawać dowolną funkcjonalność bezpośrednio w terminalu.

Każda niestandardowa komenda ma dostęp do bogatego zestawu wbudowanych helperów do operacji na plikach, JSON/YAML, interaktywnych promptów, spinnerów, pasków postępu, żądań API i więcej -- bez potrzeby importowania dodatkowych pakietów.

> **Uwaga:** Niestandardowe komendy działają poza środowiskiem Flutter. Nie możesz importować `nylo_framework.dart` w komendach. Użyj zamiast tego `ny_cli.dart`.

<div id="creating-commands"></div>

## Tworzenie komend

Utwórz nową komendę za pomocą Metro lub Dart CLI:

```bash
metro make:command current_time
```

Możesz określić kategorię komendy za pomocą opcji `--category`:

```bash
metro make:command current_time --category="project"
```

To tworzy nowy plik w `lib/app/commands/current_time.dart` i rejestruje go w rejestrze komend.

<div id="command-structure"></div>

## Struktura komendy

Każda komenda rozszerza `NyCustomCommand` i implementuje dwie kluczowe metody:

- **`builder()`** -- konfiguracja opcji i flag
- **`handle()`** -- wykonanie logiki komendy

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

## Uruchamianie komend

Uruchom komendę za pomocą Metro:

```bash
metro app:current_time
```

Nazwa komendy następuje wzorcem `kategoria:nazwa`. Gdy uruchomisz `metro` bez argumentów, niestandardowe komendy pojawiają się w sekcji **Custom Commands**:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

Aby wyświetlić pomoc dla komendy:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## Rejestr komend

Wszystkie niestandardowe komendy są rejestrowane w `lib/app/commands/commands.json`. Ten plik jest aktualizowany automatycznie przy użyciu `make:command`:

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

Każdy wpis zawiera:

| Pole | Opis |
|------|------|
| `name` | Nazwa komendy (używana po prefiksie kategorii) |
| `category` | Kategoria komendy (np. `app`, `project`) |
| `script` | Plik Dart w `lib/app/commands/` |

<div id="options-and-flags"></div>

## Opcje i flagi

Konfiguruj opcje i flagi komendy w metodzie `builder()` za pomocą `CommandBuilder`.

<div id="adding-options"></div>

### Dodawanie opcji

Opcje przyjmują wartość od użytkownika:

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

Użycie:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

| Parametr | Typ | Opis |
|----------|-----|------|
| `name` | `String` | Nazwa opcji |
| `abbr` | `String?` | Jednoznakowy skrót |
| `help` | `String?` | Tekst pomocy wyświetlany z `--help` |
| `allowed` | `List<String>?` | Ograniczenie do dozwolonych wartości |
| `defaultValue` | `String?` | Wartość domyślna, gdy nie podano |

<div id="adding-flags"></div>

### Dodawanie flag

Flagi to przełączniki logiczne:

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

Użycie:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| Parametr | Typ | Opis |
|----------|-----|------|
| `name` | `String` | Nazwa flagi |
| `abbr` | `String?` | Jednoznakowy skrót |
| `help` | `String?` | Tekst pomocy wyświetlany z `--help` |
| `defaultValue` | `bool` | Wartość domyślna (domyślnie: `false`) |

<div id="command-result"></div>

## Wynik komendy

Metoda `handle()` otrzymuje obiekt `CommandResult` z typowanymi akcesorami do odczytu sparsowanych opcji, flag i argumentów.

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

| Metoda / Właściwość | Zwraca | Opis |
|---------------------|--------|------|
| `getString(String name, {String? defaultValue})` | `String?` | Pobierz wartość tekstową |
| `getBool(String name, {bool? defaultValue})` | `bool?` | Pobierz wartość logiczną |
| `getInt(String name, {int? defaultValue})` | `int?` | Pobierz wartość całkowitą |
| `get<T>(String name)` | `T?` | Pobierz typowaną wartość |
| `hasForceFlag` | `bool` | Czy przekazano `--force` |
| `hasHelpFlag` | `bool` | Czy przekazano `--help` |
| `arguments` | `List<String>` | Wszystkie argumenty wiersza poleceń |
| `rest` | `List<String>` | Niesparsowane pozostałe argumenty |

<div id="interactive-input"></div>

## Interaktywne wejście

`NyCustomCommand` udostępnia metody do zbierania danych wejściowych od użytkownika w terminalu.

<div id="text-input"></div>

### Wejście tekstowe

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| Parametr | Typ | Opis |
|----------|-----|------|
| `question` | `String` | Pytanie do wyświetlenia |
| `defaultValue` | `String` | Wartość domyślna po naciśnięciu Enter (domyślnie: `''`) |

<div id="confirmation"></div>

### Potwierdzenie

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| Parametr | Typ | Opis |
|----------|-----|------|
| `question` | `String` | Pytanie tak/nie |
| `defaultValue` | `bool` | Domyślna odpowiedź (domyślnie: `false`) |

<div id="single-selection"></div>

### Pojedynczy wybór

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| Parametr | Typ | Opis |
|----------|-----|------|
| `question` | `String` | Tekst promptu |
| `options` | `List<String>` | Dostępne opcje |
| `defaultOption` | `String?` | Wstępnie wybrana opcja |

<div id="multiple-selection"></div>

### Wielokrotny wybór

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

Użytkownik wprowadza numery oddzielone przecinkami lub `"all"`.

<div id="secret-input"></div>

### Tajne wejście

```dart
final apiKey = promptSecret('Enter your API key:');
```

Dane wejściowe są ukryte na wyświetlaczu terminala. Przechodzi na widoczne wejście, jeśli tryb echo nie jest obsługiwany.

<div id="output-formatting"></div>

## Formatowanie wyjścia

Metody do drukowania stylizowanego wyjścia w konsoli:

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

| Metoda | Opis |
|--------|------|
| `info(String message)` | Drukuj niebieski tekst |
| `error(String message)` | Drukuj czerwony tekst |
| `success(String message)` | Drukuj zielony tekst |
| `warning(String message)` | Drukuj żółty tekst |
| `line(String message)` | Drukuj zwykły tekst (bez koloru) |
| `newLine([int count = 1])` | Drukuj puste linie |
| `comment(String message)` | Drukuj szary/wyciszony tekst |
| `alert(String message)` | Drukuj obramowane pole alertu |
| `abort([String? message, int exitCode = 1])` | Zakończ komendę z błędem |

<div id="spinner-and-progress"></div>

## Spinner i postęp

Spinnery i paski postępu zapewniają wizualną informację zwrotną podczas długotrwałych operacji.

<div id="using-withspinner"></div>

### Użycie withSpinner

Opakuj zadanie asynchroniczne automatycznym spinnerem:

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

| Parametr | Typ | Opis |
|----------|-----|------|
| `task` | `Future<T> Function()` | Funkcja asynchroniczna do wykonania |
| `message` | `String` | Tekst wyświetlany podczas działania spinnera |
| `successMessage` | `String?` | Wyświetlany po sukcesie |
| `errorMessage` | `String?` | Wyświetlany po błędzie |

<div id="manual-spinner-control"></div>

### Ręczna kontrola spinnera

Do wieloetapowych przepływów pracy utwórz spinner i kontroluj go ręcznie:

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

**Metody ConsoleSpinner:**

| Metoda | Opis |
|--------|------|
| `start([String? message])` | Rozpocznij animację spinnera |
| `update(String message)` | Zmień wyświetlany komunikat |
| `stop({String? completionMessage, bool success = true})` | Zatrzymaj spinner |

<div id="progress-bar"></div>

### Pasek postępu

Utwórz i zarządzaj paskiem postępu ręcznie:

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**Metody ConsoleProgressBar:**

| Metoda / Właściwość | Opis |
|---------------------|------|
| `start()` | Rozpocznij pasek postępu |
| `tick([int amount = 1])` | Zwiększ postęp |
| `update(int value)` | Ustaw postęp na konkretną wartość |
| `updateMessage(String newMessage)` | Zmień wyświetlany komunikat |
| `complete([String? completionMessage])` | Zakończ z opcjonalnym komunikatem |
| `stop()` | Zatrzymaj bez kończenia |
| `current` | Bieżąca wartość postępu (getter) |
| `percentage` | Postęp jako procent 0-100 (getter) |

<div id="processing-items-with-progress"></div>

### Przetwarzanie elementów z postępem

Przetwarzaj listę elementów z automatycznym śledzeniem postępu:

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

## Helper API

Helper `api` udostępnia uproszczony wrapper wokół Dio do wykonywania żądań HTTP:

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

Połącz z `withSpinner` dla lepszego doświadczenia użytkownika:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

`ApiService` obsługuje metody `get`, `post`, `put`, `delete` i `patch`, każda przyjmująca opcjonalne `queryParameters`, `data`, `options` i `cancelToken`.

<div id="file-system-helpers"></div>

## Helpery systemu plików

Wbudowane helpery systemu plików, aby nie musieć importować `dart:io`:

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

| Metoda | Opis |
|--------|------|
| `fileExists(String path)` | Zwraca `true`, jeśli plik istnieje |
| `directoryExists(String path)` | Zwraca `true`, jeśli katalog istnieje |
| `readFile(String path)` | Odczytaj plik jako string (asynchronicznie) |
| `readFileSync(String path)` | Odczytaj plik jako string (synchronicznie) |
| `writeFile(String path, String content)` | Zapisz zawartość do pliku (asynchronicznie) |
| `writeFileSync(String path, String content)` | Zapisz zawartość do pliku (synchronicznie) |
| `appendFile(String path, String content)` | Dopisz zawartość do pliku |
| `ensureDirectory(String path)` | Utwórz katalog, jeśli nie istnieje |
| `deleteFile(String path)` | Usuń plik |
| `copyFile(String source, String destination)` | Skopiuj plik |

<div id="json-and-yaml-helpers"></div>

## Helpery JSON i YAML

Odczytuj i zapisuj pliki JSON i YAML za pomocą wbudowanych helperów:

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

| Metoda | Opis |
|--------|------|
| `readJson(String path)` | Odczytaj plik JSON jako `Map<String, dynamic>` |
| `readJsonArray(String path)` | Odczytaj plik JSON jako `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Zapisz dane jako JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Dopisz do pliku tablicy JSON |
| `readYaml(String path)` | Odczytaj plik YAML jako `Map<String, dynamic>` |

<div id="dart-file-manipulation"></div>

## Manipulacja plikami Dart

Helpery do programowego edytowania plików źródłowych Dart -- przydatne przy budowaniu narzędzi scaffoldingu:

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

| Metoda | Opis |
|--------|------|
| `addImport(String filePath, String importStatement)` | Dodaj import do pliku Dart (pomija, jeśli istnieje) |
| `insertBeforeClosingBrace(String filePath, String code)` | Wstaw kod przed ostatnim `}` w pliku |
| `fileContains(String filePath, String identifier)` | Sprawdź, czy plik zawiera string |
| `fileContainsPattern(String filePath, Pattern pattern)` | Sprawdź, czy plik pasuje do wzorca |

<div id="directory-helpers"></div>

## Helpery katalogów

Helpery do pracy z katalogami i znajdowania plików:

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

| Metoda | Opis |
|--------|------|
| `listDirectory(String path, {bool recursive = false})` | Wylistuj zawartość katalogu |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Znajdź pliki pasujące do kryteriów |
| `deleteDirectory(String path)` | Usuń katalog rekurencyjnie |
| `copyDirectory(String source, String destination)` | Skopiuj katalog rekurencyjnie |

<div id="case-conversion-helpers"></div>

## Helpery konwersji wielkości liter

Konwertuj stringi między konwencjami nazewnictwa bez importowania pakietu `recase`:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| Metoda | Format wyjściowy | Przykład |
|--------|-----------------|----------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Helpery ścieżek projektu

Gettery do standardowych katalogów projektu {{ config('app.name') }}, zwracające ścieżki względem katalogu głównego projektu:

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

| Właściwość | Ścieżka |
|------------|---------|
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
| `projectPath(String relativePath)` | Rozwiązuje ścieżkę względną w projekcie |

<div id="platform-helpers"></div>

## Helpery platformy

Sprawdzaj platformę i uzyskuj dostęp do zmiennych środowiskowych:

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

| Właściwość / Metoda | Opis |
|---------------------|------|
| `isWindows` | `true` jeśli działa na Windows |
| `isMacOS` | `true` jeśli działa na macOS |
| `isLinux` | `true` jeśli działa na Linux |
| `workingDirectory` | Bieżący katalog roboczy |
| `env(String key, [String defaultValue = ''])` | Odczytaj systemową zmienną środowiskową |

<div id="dart-and-flutter-commands"></div>

## Komendy Dart i Flutter

Uruchamiaj popularne komendy CLI Dart i Flutter jako metody pomocnicze. Każda zwraca kod wyjścia procesu:

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

| Metoda | Opis |
|--------|------|
| `dartFormat(String path)` | Uruchom `dart format` na pliku lub katalogu |
| `dartAnalyze([String? path])` | Uruchom `dart analyze` |
| `flutterPubGet()` | Uruchom `flutter pub get` |
| `flutterClean()` | Uruchom `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Uruchom `flutter build <target>` |
| `flutterTest([String? path])` | Uruchom `flutter test` |

<div id="validation-helpers"></div>

## Helpery walidacji

Helpery do walidacji i czyszczenia danych wejściowych użytkownika do generowania kodu:

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

| Metoda | Opis |
|--------|------|
| `isValidDartIdentifier(String name)` | Waliduj nazwę identyfikatora Dart |
| `requireArgument(CommandResult result, {String? message})` | Wymagaj niepustego pierwszego argumentu lub przerwij |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Wyczyść i konwertuj nazwę klasy na PascalCase |
| `cleanFileName(String name, {String extension = '.dart'})` | Wyczyść i konwertuj nazwę pliku na snake_case |

<div id="file-scaffolding"></div>

## Scaffolding plików

Twórz jeden lub wiele plików z zawartością za pomocą systemu scaffoldingu.

### Pojedynczy plik

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

| Parametr | Typ | Opis |
|----------|-----|------|
| `path` | `String` | Ścieżka pliku do utworzenia |
| `content` | `String` | Zawartość pliku |
| `force` | `bool` | Nadpisz, jeśli istnieje (domyślnie: `false`) |
| `successMessage` | `String?` | Komunikat wyświetlany po sukcesie |

### Wiele plików

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

Klasa `ScaffoldFile`:

| Właściwość | Typ | Opis |
|------------|-----|------|
| `path` | `String` | Ścieżka pliku do utworzenia |
| `content` | `String` | Zawartość pliku |
| `successMessage` | `String?` | Komunikat wyświetlany po sukcesie |

<div id="task-runner"></div>

## Task Runner

Uruchamiaj serię nazwanych zadań z automatycznym wyjściem statusu.

### Podstawowy Task Runner

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

### Task Runner ze spinnerem

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

Klasa `CommandTask`:

| Właściwość | Typ | Domyślnie | Opis |
|------------|-----|-----------|------|
| `name` | `String` | wymagany | Nazwa zadania wyświetlana na wyjściu |
| `action` | `Future<void> Function()` | wymagany | Funkcja asynchroniczna do wykonania |
| `stopOnError` | `bool` | `true` | Czy zatrzymać pozostałe zadania po błędzie |

<div id="table-output"></div>

## Wyjście tabelaryczne

Wyświetlaj sformatowane tabele ASCII w konsoli:

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

Wyjście:

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

## Przykłady

<div id="current-time-command"></div>

### Komenda aktualnego czasu

Prosta komenda wyświetlająca aktualny czas:

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

### Komenda pobierania czcionek

Komenda pobierająca i instalująca czcionki Google Fonts w projekcie:

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

### Komenda pipeline deploymentu

Komenda uruchamiająca pełny pipeline deploymentu za pomocą task runnera:

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
