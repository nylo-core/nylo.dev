# Befehle

---

<a name="section-1"></a>
- [Einleitung](#introduction)
- [Befehle erstellen](#creating-commands)
- [Befehlsstruktur](#command-structure)
- [Befehle ausführen](#running-commands)
- [Befehlsregister](#command-registry)
- [Optionen und Flags](#options-and-flags)
  - [Optionen hinzufügen](#adding-options)
  - [Flags hinzufügen](#adding-flags)
- [Befehlsergebnis](#command-result)
- [Interaktive Eingabe](#interactive-input)
  - [Texteingabe](#text-input)
  - [Bestätigung](#confirmation)
  - [Einfachauswahl](#single-selection)
  - [Mehrfachauswahl](#multiple-selection)
  - [Geheime Eingabe](#secret-input)
- [Ausgabeformatierung](#output-formatting)
- [Spinner und Fortschritt](#spinner-and-progress)
  - [withSpinner verwenden](#using-withspinner)
  - [Manuelle Spinner-Steuerung](#manual-spinner-control)
  - [Fortschrittsbalken](#progress-bar)
  - [Elemente mit Fortschritt verarbeiten](#processing-items-with-progress)
- [API-Helfer](#api-helper)
- [Dateisystem-Helfer](#file-system-helpers)
- [JSON- und YAML-Helfer](#json-and-yaml-helpers)
- [Dart-Dateimanipulation](#dart-file-manipulation)
- [Verzeichnis-Helfer](#directory-helpers)
- [Groß-/Kleinschreibungs-Helfer](#case-conversion-helpers)
- [Projektpfad-Helfer](#project-path-helpers)
- [Plattform-Helfer](#platform-helpers)
- [Dart- und Flutter-Befehle](#dart-and-flutter-commands)
- [Validierungs-Helfer](#validation-helpers)
- [Datei-Scaffolding](#file-scaffolding)
- [Task-Runner](#task-runner)
- [Tabellenausgabe](#table-output)
- [Beispiele](#examples)
  - [Aktuelle-Uhrzeit-Befehl](#current-time-command)
  - [Schriften-herunterladen-Befehl](#download-fonts-command)
  - [Deployment-Pipeline-Befehl](#deployment-pipeline-command)

<div id="introduction"></div>

## Einleitung

Befehle ermöglichen es Ihnen, die CLI von {{ config('app.name') }} mit benutzerdefinierten, projektspezifischen Werkzeugen zu erweitern. Indem Sie `NyCustomCommand` ableiten, können Sie wiederkehrende Aufgaben automatisieren, Deployment-Workflows erstellen, Code generieren oder jede beliebige Funktionalität direkt in Ihrem Terminal hinzufügen.

Jeder benutzerdefinierte Befehl hat Zugriff auf eine umfangreiche Sammlung integrierter Helfer für Datei-I/O, JSON/YAML, interaktive Eingabeaufforderungen, Spinner, Fortschrittsbalken, API-Anfragen und mehr -- alles ohne zusätzliche Pakete importieren zu müssen.

> **Hinweis:** Benutzerdefinierte Befehle laufen außerhalb der Flutter-Laufzeitumgebung. Sie können `nylo_framework.dart` nicht in Ihren Befehlen importieren. Verwenden Sie stattdessen `ny_cli.dart`.

<div id="creating-commands"></div>

## Befehle erstellen

Erstellen Sie einen neuen Befehl mit Metro oder der Dart-CLI:

```bash
metro make:command current_time
```

Sie können eine Kategorie für Ihren Befehl mit der Option `--category` angeben:

```bash
metro make:command current_time --category="project"
```

Dies erstellt eine neue Datei unter `lib/app/commands/current_time.dart` und registriert sie im Befehlsregister.

<div id="command-structure"></div>

## Befehlsstruktur

Jeder Befehl erweitert `NyCustomCommand` und implementiert zwei zentrale Methoden:

- **`builder()`** -- Optionen und Flags konfigurieren
- **`handle()`** -- die Befehlslogik ausführen

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

## Befehle ausführen

Führen Sie Ihren Befehl mit Metro oder Dart aus:

```bash
metro app:current_time
```

Der Befehlsname folgt dem Muster `kategorie:name`. Wenn Sie `metro` ohne Argumente ausführen, erscheinen benutzerdefinierte Befehle im Abschnitt **Custom Commands**:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

Um die Hilfe für einen Befehl anzuzeigen:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## Befehlsregister

Alle benutzerdefinierten Befehle werden in `lib/app/commands/commands.json` registriert. Diese Datei wird automatisch aktualisiert, wenn Sie `make:command` verwenden:

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

Jeder Eintrag hat:

| Feld | Beschreibung |
|------|-------------|
| `name` | Der Befehlsname (wird nach dem Kategoriepräfix verwendet) |
| `category` | Die Befehlskategorie (z.B. `app`, `project`) |
| `script` | Die Dart-Datei in `lib/app/commands/` |

<div id="options-and-flags"></div>

## Optionen und Flags

Konfigurieren Sie die Optionen und Flags Ihres Befehls in der Methode `builder()` mit `CommandBuilder`.

<div id="adding-options"></div>

### Optionen hinzufügen

Optionen akzeptieren einen Wert vom Benutzer:

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

Verwendung:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `name` | `String` | Optionsname |
| `abbr` | `String?` | Einzeichen-Abkürzung |
| `help` | `String?` | Hilfetext, der mit `--help` angezeigt wird |
| `allowed` | `List<String>?` | Auf erlaubte Werte beschränken |
| `defaultValue` | `String?` | Standardwert, wenn nicht angegeben |

<div id="adding-flags"></div>

### Flags hinzufügen

Flags sind boolesche Schalter:

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

Verwendung:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `name` | `String` | Flag-Name |
| `abbr` | `String?` | Einzeichen-Abkürzung |
| `help` | `String?` | Hilfetext, der mit `--help` angezeigt wird |
| `defaultValue` | `bool` | Standardwert (Standard: `false`) |

<div id="command-result"></div>

## Befehlsergebnis

Die Methode `handle()` erhält ein `CommandResult`-Objekt mit typisierten Zugriffsmethoden zum Lesen von geparsten Optionen, Flags und Argumenten.

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

| Methode / Eigenschaft | Rückgabewert | Beschreibung |
|-----------------------|-------------|-------------|
| `getString(String name, {String? defaultValue})` | `String?` | Zeichenkettenwert abrufen |
| `getBool(String name, {bool? defaultValue})` | `bool?` | Booleschen Wert abrufen |
| `getInt(String name, {int? defaultValue})` | `int?` | Ganzzahlwert abrufen |
| `get<T>(String name)` | `T?` | Typisierten Wert abrufen |
| `hasForceFlag` | `bool` | Ob `--force` übergeben wurde |
| `hasHelpFlag` | `bool` | Ob `--help` übergeben wurde |
| `arguments` | `List<String>` | Alle Befehlszeilenargumente |
| `rest` | `List<String>` | Nicht geparste restliche Argumente |

<div id="interactive-input"></div>

## Interaktive Eingabe

`NyCustomCommand` stellt Methoden zur Erfassung von Benutzereingaben im Terminal bereit.

<div id="text-input"></div>

### Texteingabe

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `question` | `String` | Die anzuzeigende Frage |
| `defaultValue` | `String` | Standardwert, wenn der Benutzer Enter drückt (Standard: `''`) |

<div id="confirmation"></div>

### Bestätigung

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `question` | `String` | Die Ja/Nein-Frage |
| `defaultValue` | `bool` | Standardantwort (Standard: `false`) |

<div id="single-selection"></div>

### Einfachauswahl

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `question` | `String` | Der Eingabeaufforderungstext |
| `options` | `List<String>` | Verfügbare Auswahlmöglichkeiten |
| `defaultOption` | `String?` | Vorausgewählte Option |

<div id="multiple-selection"></div>

### Mehrfachauswahl

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

Der Benutzer gibt kommagetrennte Nummern oder `"all"` ein.

<div id="secret-input"></div>

### Geheime Eingabe

```dart
final apiKey = promptSecret('Enter your API key:');
```

Die Eingabe wird in der Terminalanzeige verborgen. Fällt auf sichtbare Eingabe zurück, wenn der Echo-Modus nicht unterstützt wird.

<div id="output-formatting"></div>

## Ausgabeformatierung

Methoden zur Ausgabe von formatiertem Text in der Konsole:

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

| Methode | Beschreibung |
|---------|-------------|
| `info(String message)` | Blauen Text ausgeben |
| `error(String message)` | Roten Text ausgeben |
| `success(String message)` | Grünen Text ausgeben |
| `warning(String message)` | Gelben Text ausgeben |
| `line(String message)` | Einfachen Text ausgeben (ohne Farbe) |
| `newLine([int count = 1])` | Leerzeilen ausgeben |
| `comment(String message)` | Grauen/gedämpften Text ausgeben |
| `alert(String message)` | Ein umrandetes Hinweisfeld ausgeben |
| `abort([String? message, int exitCode = 1])` | Den Befehl mit einem Fehler beenden |

<div id="spinner-and-progress"></div>

## Spinner und Fortschritt

Spinner und Fortschrittsbalken bieten visuelles Feedback bei lang andauernden Operationen.

<div id="using-withspinner"></div>

### withSpinner verwenden

Umschließen Sie eine asynchrone Aufgabe mit einem automatischen Spinner:

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

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `task` | `Future<T> Function()` | Die auszuführende asynchrone Funktion |
| `message` | `String` | Text, der während des Spinner-Laufs angezeigt wird |
| `successMessage` | `String?` | Wird bei Erfolg angezeigt |
| `errorMessage` | `String?` | Wird bei Fehler angezeigt |

<div id="manual-spinner-control"></div>

### Manuelle Spinner-Steuerung

Für mehrstufige Workflows erstellen Sie einen Spinner und steuern ihn manuell:

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

**ConsoleSpinner-Methoden:**

| Methode | Beschreibung |
|---------|-------------|
| `start([String? message])` | Die Spinner-Animation starten |
| `update(String message)` | Die angezeigte Nachricht ändern |
| `stop({String? completionMessage, bool success = true})` | Den Spinner stoppen |

<div id="progress-bar"></div>

### Fortschrittsbalken

Einen Fortschrittsbalken erstellen und manuell verwalten:

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**ConsoleProgressBar-Methoden:**

| Methode / Eigenschaft | Beschreibung |
|-----------------------|-------------|
| `start()` | Den Fortschrittsbalken starten |
| `tick([int amount = 1])` | Fortschritt inkrementieren |
| `update(int value)` | Fortschritt auf einen bestimmten Wert setzen |
| `updateMessage(String newMessage)` | Die angezeigte Nachricht ändern |
| `complete([String? completionMessage])` | Mit optionaler Nachricht abschließen |
| `stop()` | Stoppen ohne abzuschließen |
| `current` | Aktueller Fortschrittswert (Getter) |
| `percentage` | Fortschritt als Prozentsatz 0-100 (Getter) |

<div id="processing-items-with-progress"></div>

### Elemente mit Fortschritt verarbeiten

Eine Liste von Elementen mit automatischer Fortschrittsverfolgung verarbeiten:

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

## API-Helfer

Der `api`-Helfer bietet einen vereinfachten Wrapper um Dio für HTTP-Anfragen:

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

Kombinieren Sie es mit `withSpinner` für eine bessere Benutzererfahrung:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

Der `ApiService` unterstützt die Methoden `get`, `post`, `put`, `delete` und `patch`, die jeweils optionale `queryParameters`, `data`, `options` und `cancelToken` akzeptieren.

<div id="file-system-helpers"></div>

## Dateisystem-Helfer

Integrierte Dateisystem-Helfer, sodass Sie `dart:io` nicht importieren müssen:

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

| Methode | Beschreibung |
|---------|-------------|
| `fileExists(String path)` | Gibt `true` zurück, wenn die Datei existiert |
| `directoryExists(String path)` | Gibt `true` zurück, wenn das Verzeichnis existiert |
| `readFile(String path)` | Datei als Zeichenkette lesen (asynchron) |
| `readFileSync(String path)` | Datei als Zeichenkette lesen (synchron) |
| `writeFile(String path, String content)` | Inhalt in Datei schreiben (asynchron) |
| `writeFileSync(String path, String content)` | Inhalt in Datei schreiben (synchron) |
| `appendFile(String path, String content)` | Inhalt an Datei anhängen |
| `ensureDirectory(String path)` | Verzeichnis erstellen, falls es nicht existiert |
| `deleteFile(String path)` | Eine Datei löschen |
| `copyFile(String source, String destination)` | Eine Datei kopieren |

<div id="json-and-yaml-helpers"></div>

## JSON- und YAML-Helfer

JSON- und YAML-Dateien mit integrierten Helfern lesen und schreiben:

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

| Methode | Beschreibung |
|---------|-------------|
| `readJson(String path)` | JSON-Datei als `Map<String, dynamic>` lesen |
| `readJsonArray(String path)` | JSON-Datei als `List<dynamic>` lesen |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Daten als JSON schreiben |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | An eine JSON-Array-Datei anhängen |
| `readYaml(String path)` | YAML-Datei als `Map<String, dynamic>` lesen |

<div id="dart-file-manipulation"></div>

## Dart-Dateimanipulation

Helfer zum programmatischen Bearbeiten von Dart-Quelldateien -- nützlich beim Erstellen von Scaffolding-Werkzeugen:

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

| Methode | Beschreibung |
|---------|-------------|
| `addImport(String filePath, String importStatement)` | Import zu Dart-Datei hinzufügen (überspringt, wenn vorhanden) |
| `insertBeforeClosingBrace(String filePath, String code)` | Code vor letzter `}` in der Datei einfügen |
| `fileContains(String filePath, String identifier)` | Prüfen, ob Datei eine Zeichenkette enthält |
| `fileContainsPattern(String filePath, Pattern pattern)` | Prüfen, ob Datei einem Muster entspricht |

<div id="directory-helpers"></div>

## Verzeichnis-Helfer

Helfer für die Arbeit mit Verzeichnissen und das Finden von Dateien:

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

| Methode | Beschreibung |
|---------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Verzeichnisinhalte auflisten |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Dateien nach Kriterien finden |
| `deleteDirectory(String path)` | Verzeichnis rekursiv löschen |
| `copyDirectory(String source, String destination)` | Verzeichnis rekursiv kopieren |

<div id="case-conversion-helpers"></div>

## Groß-/Kleinschreibungs-Helfer

Zeichenketten zwischen Namenskonventionen umwandeln, ohne das Paket `recase` importieren zu müssen:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| Methode | Ausgabeformat | Beispiel |
|---------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Projektpfad-Helfer

Getter für Standard-Projektverzeichnisse von {{ config('app.name') }}, die Pfade relativ zum Projektstamm zurückgeben:

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

| Eigenschaft | Pfad |
|-------------|------|
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
| `projectPath(String relativePath)` | Löst einen relativen Pfad innerhalb des Projekts auf |

<div id="platform-helpers"></div>

## Plattform-Helfer

Plattform prüfen und auf Umgebungsvariablen zugreifen:

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

| Eigenschaft / Methode | Beschreibung |
|-----------------------|-------------|
| `isWindows` | `true`, wenn auf Windows ausgeführt |
| `isMacOS` | `true`, wenn auf macOS ausgeführt |
| `isLinux` | `true`, wenn auf Linux ausgeführt |
| `workingDirectory` | Aktueller Arbeitsverzeichnispfad |
| `env(String key, [String defaultValue = ''])` | System-Umgebungsvariable lesen |

<div id="dart-and-flutter-commands"></div>

## Dart- und Flutter-Befehle

Gängige Dart- und Flutter-CLI-Befehle als Hilfsmethoden ausführen. Jede gibt den Prozess-Exit-Code zurück:

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

| Methode | Beschreibung |
|---------|-------------|
| `dartFormat(String path)` | `dart format` auf eine Datei oder ein Verzeichnis ausführen |
| `dartAnalyze([String? path])` | `dart analyze` ausführen |
| `flutterPubGet()` | `flutter pub get` ausführen |
| `flutterClean()` | `flutter clean` ausführen |
| `flutterBuild(String target, {List<String> args})` | `flutter build <target>` ausführen |
| `flutterTest([String? path])` | `flutter test` ausführen |

<div id="validation-helpers"></div>

## Validierungs-Helfer

Helfer zum Validieren und Bereinigen von Benutzereingaben für die Codegenerierung:

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

| Methode | Beschreibung |
|---------|-------------|
| `isValidDartIdentifier(String name)` | Einen Dart-Bezeichnernamen validieren |
| `requireArgument(CommandResult result, {String? message})` | Nicht-leeres erstes Argument erfordern oder abbrechen |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Klassennamen bereinigen und in PascalCase umwandeln |
| `cleanFileName(String name, {String extension = '.dart'})` | Dateinamen bereinigen und in snake_case umwandeln |

<div id="file-scaffolding"></div>

## Datei-Scaffolding

Eine oder mehrere Dateien mit Inhalt über das Scaffolding-System erstellen.

### Einzelne Datei

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

| Parameter | Typ | Beschreibung |
|-----------|-----|-------------|
| `path` | `String` | Dateipfad zum Erstellen |
| `content` | `String` | Dateiinhalt |
| `force` | `bool` | Überschreiben, wenn vorhanden (Standard: `false`) |
| `successMessage` | `String?` | Nachricht, die bei Erfolg angezeigt wird |

### Mehrere Dateien

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

Die Klasse `ScaffoldFile`:

| Eigenschaft | Typ | Beschreibung |
|-------------|-----|-------------|
| `path` | `String` | Dateipfad zum Erstellen |
| `content` | `String` | Dateiinhalt |
| `successMessage` | `String?` | Nachricht, die bei Erfolg angezeigt wird |

<div id="task-runner"></div>

## Task-Runner

Eine Reihe benannter Aufgaben mit automatischer Statusausgabe ausführen.

### Einfacher Task-Runner

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

### Task-Runner mit Spinner

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

Die Klasse `CommandTask`:

| Eigenschaft | Typ | Standard | Beschreibung |
|-------------|-----|---------|-------------|
| `name` | `String` | erforderlich | Aufgabenname, der in der Ausgabe angezeigt wird |
| `action` | `Future<void> Function()` | erforderlich | Auszuführende asynchrone Funktion |
| `stopOnError` | `bool` | `true` | Ob verbleibende Aufgaben bei Fehler gestoppt werden sollen |

<div id="table-output"></div>

## Tabellenausgabe

Formatierte ASCII-Tabellen in der Konsole anzeigen:

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

Ausgabe:

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

## Beispiele

<div id="current-time-command"></div>

### Aktuelle-Uhrzeit-Befehl

Ein einfacher Befehl, der die aktuelle Uhrzeit anzeigt:

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

### Schriften-herunterladen-Befehl

Ein Befehl, der Google Fonts herunterlädt und im Projekt installiert:

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

### Deployment-Pipeline-Befehl

Ein Befehl, der eine vollständige Deployment-Pipeline mit dem Task-Runner ausführt:

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
