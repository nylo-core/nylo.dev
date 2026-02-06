# Comandi

---

<a name="section-1"></a>
- [Introduzione](#introduction)
- [Creare Comandi](#creating-commands)
- [Struttura del Comando](#command-structure)
- [Eseguire Comandi](#running-commands)
- [Registro dei Comandi](#command-registry)
- [Opzioni e Flag](#options-and-flags)
  - [Aggiungere Opzioni](#adding-options)
  - [Aggiungere Flag](#adding-flags)
- [Risultato del Comando](#command-result)
- [Input Interattivo](#interactive-input)
  - [Input Testuale](#text-input)
  - [Conferma](#confirmation)
  - [Selezione Singola](#single-selection)
  - [Selezione Multipla](#multiple-selection)
  - [Input Segreto](#secret-input)
- [Formattazione dell'Output](#output-formatting)
- [Spinner e Progresso](#spinner-and-progress)
  - [Utilizzare withSpinner](#using-withspinner)
  - [Controllo Manuale dello Spinner](#manual-spinner-control)
  - [Barra di Progresso](#progress-bar)
  - [Elaborazione di Elementi con Progresso](#processing-items-with-progress)
- [Helper API](#api-helper)
- [Helper per il File System](#file-system-helpers)
- [Helper JSON e YAML](#json-and-yaml-helpers)
- [Manipolazione di File Dart](#dart-file-manipulation)
- [Helper per le Directory](#directory-helpers)
- [Helper per la Conversione di Formato](#case-conversion-helpers)
- [Helper per i Percorsi del Progetto](#project-path-helpers)
- [Helper per la Piattaforma](#platform-helpers)
- [Comandi Dart e Flutter](#dart-and-flutter-commands)
- [Helper per la Validazione](#validation-helpers)
- [Scaffolding di File](#file-scaffolding)
- [Task Runner](#task-runner)
- [Output a Tabella](#table-output)
- [Esempi](#examples)
  - [Comando Ora Corrente](#current-time-command)
  - [Comando Download Font](#download-fonts-command)
  - [Comando Pipeline di Deploy](#deployment-pipeline-command)

<div id="introduction"></div>

## Introduzione

I comandi ti permettono di estendere la CLI di {{ config('app.name') }} con strumenti personalizzati specifici per il progetto. Estendendo `NyCustomCommand`, puoi automatizzare attivita' ripetitive, costruire workflow di deploy, generare codice o aggiungere qualsiasi funzionalita' di cui hai bisogno direttamente nel tuo terminale.

Ogni comando personalizzato ha accesso a un ricco set di helper integrati per I/O su file, JSON/YAML, prompt interattivi, spinner, barre di progresso, richieste API e altro ancora -- il tutto senza importare pacchetti aggiuntivi.

> **Nota:** I comandi personalizzati vengono eseguiti al di fuori del runtime Flutter. Non puoi importare `nylo_framework.dart` nei tuoi comandi. Usa invece `ny_cli.dart`.

<div id="creating-commands"></div>

## Creare Comandi

Crea un nuovo comando utilizzando Metro o la CLI Dart:

```bash
metro make:command current_time
```

Puoi specificare una categoria per il tuo comando utilizzando l'opzione `--category`:

```bash
metro make:command current_time --category="project"
```

Questo crea un nuovo file in `lib/app/commands/current_time.dart` e lo registra nel registro dei comandi.

<div id="command-structure"></div>

## Struttura del Comando

Ogni comando estende `NyCustomCommand` e implementa due metodi chiave:

- **`builder()`** -- configura opzioni e flag
- **`handle()`** -- esegue la logica del comando

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

## Eseguire Comandi

Esegui il tuo comando utilizzando Metro o Dart:

```bash
metro app:current_time
```

Il nome del comando segue il formato `categoria:nome`. Quando esegui `metro` senza argomenti, i comandi personalizzati appaiono nella sezione **Custom Commands**:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

Per visualizzare l'aiuto per un comando:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## Registro dei Comandi

Tutti i comandi personalizzati sono registrati in `lib/app/commands/commands.json`. Questo file viene aggiornato automaticamente quando usi `make:command`:

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

Ogni voce contiene:

| Campo | Descrizione |
|-------|-------------|
| `name` | Il nome del comando (usato dopo il prefisso della categoria) |
| `category` | La categoria del comando (es. `app`, `project`) |
| `script` | Il file Dart in `lib/app/commands/` |

<div id="options-and-flags"></div>

## Opzioni e Flag

Configura le opzioni e i flag del tuo comando nel metodo `builder()` utilizzando `CommandBuilder`.

<div id="adding-options"></div>

### Aggiungere Opzioni

Le opzioni accettano un valore dall'utente:

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

Utilizzo:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `name` | `String` | Nome dell'opzione |
| `abbr` | `String?` | Abbreviazione a singolo carattere |
| `help` | `String?` | Testo di aiuto mostrato con `--help` |
| `allowed` | `List<String>?` | Limita ai valori consentiti |
| `defaultValue` | `String?` | Valore predefinito se non fornito |

<div id="adding-flags"></div>

### Aggiungere Flag

I flag sono interruttori booleani:

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

Utilizzo:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `name` | `String` | Nome del flag |
| `abbr` | `String?` | Abbreviazione a singolo carattere |
| `help` | `String?` | Testo di aiuto mostrato con `--help` |
| `defaultValue` | `bool` | Valore predefinito (default: `false`) |

<div id="command-result"></div>

## Risultato del Comando

Il metodo `handle()` riceve un oggetto `CommandResult` con accessori tipizzati per leggere opzioni, flag e argomenti analizzati.

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

| Metodo / Proprieta' | Ritorna | Descrizione |
|----------------------|---------|-------------|
| `getString(String name, {String? defaultValue})` | `String?` | Ottieni il valore stringa |
| `getBool(String name, {bool? defaultValue})` | `bool?` | Ottieni il valore booleano |
| `getInt(String name, {int? defaultValue})` | `int?` | Ottieni il valore intero |
| `get<T>(String name)` | `T?` | Ottieni il valore tipizzato |
| `hasForceFlag` | `bool` | Se `--force` e' stato passato |
| `hasHelpFlag` | `bool` | Se `--help` e' stato passato |
| `arguments` | `List<String>` | Tutti gli argomenti della riga di comando |
| `rest` | `List<String>` | Argomenti residui non analizzati |

<div id="interactive-input"></div>

## Input Interattivo

`NyCustomCommand` fornisce metodi per raccogliere l'input dell'utente nel terminale.

<div id="text-input"></div>

### Input Testuale

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `question` | `String` | La domanda da visualizzare |
| `defaultValue` | `String` | Valore predefinito se l'utente preme Invio (default: `''`) |

<div id="confirmation"></div>

### Conferma

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `question` | `String` | La domanda si/no |
| `defaultValue` | `bool` | Risposta predefinita (default: `false`) |

<div id="single-selection"></div>

### Selezione Singola

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `question` | `String` | Il testo del prompt |
| `options` | `List<String>` | Le scelte disponibili |
| `defaultOption` | `String?` | Opzione preselezionata |

<div id="multiple-selection"></div>

### Selezione Multipla

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

L'utente inserisce numeri separati da virgola oppure `"all"`.

<div id="secret-input"></div>

### Input Segreto

```dart
final apiKey = promptSecret('Enter your API key:');
```

L'input viene nascosto dalla visualizzazione nel terminale. Se la modalita' echo non e' supportata, viene usato l'input visibile come fallback.

<div id="output-formatting"></div>

## Formattazione dell'Output

Metodi per stampare output formattato nella console:

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

| Metodo | Descrizione |
|--------|-------------|
| `info(String message)` | Stampa testo blu |
| `error(String message)` | Stampa testo rosso |
| `success(String message)` | Stampa testo verde |
| `warning(String message)` | Stampa testo giallo |
| `line(String message)` | Stampa testo semplice (senza colore) |
| `newLine([int count = 1])` | Stampa righe vuote |
| `comment(String message)` | Stampa testo grigio/attenuato |
| `alert(String message)` | Stampa un riquadro di avviso con bordo |
| `abort([String? message, int exitCode = 1])` | Esce dal comando con un errore |

<div id="spinner-and-progress"></div>

## Spinner e Progresso

Gli spinner e le barre di progresso forniscono un feedback visivo durante operazioni di lunga durata.

<div id="using-withspinner"></div>

### Utilizzare withSpinner

Avvolgi un'attivita' asincrona con uno spinner automatico:

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

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `task` | `Future<T> Function()` | La funzione asincrona da eseguire |
| `message` | `String` | Testo mostrato durante l'esecuzione dello spinner |
| `successMessage` | `String?` | Mostrato in caso di successo |
| `errorMessage` | `String?` | Mostrato in caso di errore |

<div id="manual-spinner-control"></div>

### Controllo Manuale dello Spinner

Per workflow a piu' passaggi, crea uno spinner e controllalo manualmente:

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

**Metodi di ConsoleSpinner:**

| Metodo | Descrizione |
|--------|-------------|
| `start([String? message])` | Avvia l'animazione dello spinner |
| `update(String message)` | Cambia il messaggio visualizzato |
| `stop({String? completionMessage, bool success = true})` | Ferma lo spinner |

<div id="progress-bar"></div>

### Barra di Progresso

Crea e gestisci una barra di progresso manualmente:

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**Metodi di ConsoleProgressBar:**

| Metodo / Proprieta' | Descrizione |
|----------------------|-------------|
| `start()` | Avvia la barra di progresso |
| `tick([int amount = 1])` | Incrementa il progresso |
| `update(int value)` | Imposta il progresso a un valore specifico |
| `updateMessage(String newMessage)` | Cambia il messaggio visualizzato |
| `complete([String? completionMessage])` | Completa con messaggio opzionale |
| `stop()` | Ferma senza completare |
| `current` | Valore corrente del progresso (getter) |
| `percentage` | Progresso in percentuale 0-100 (getter) |

<div id="processing-items-with-progress"></div>

### Elaborazione di Elementi con Progresso

Elabora una lista di elementi con tracciamento automatico del progresso:

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

L'helper `api` fornisce un wrapper semplificato attorno a Dio per effettuare richieste HTTP:

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

Combina con `withSpinner` per una migliore esperienza utente:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

`ApiService` supporta i metodi `get`, `post`, `put`, `delete` e `patch`, ognuno dei quali accetta opzionalmente `queryParameters`, `data`, `options` e `cancelToken`.

<div id="file-system-helpers"></div>

## Helper per il File System

Helper integrati per il file system, cosi' non devi importare `dart:io`:

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

| Metodo | Descrizione |
|--------|-------------|
| `fileExists(String path)` | Restituisce `true` se il file esiste |
| `directoryExists(String path)` | Restituisce `true` se la directory esiste |
| `readFile(String path)` | Legge il file come stringa (asincrono) |
| `readFileSync(String path)` | Legge il file come stringa (sincrono) |
| `writeFile(String path, String content)` | Scrive il contenuto nel file (asincrono) |
| `writeFileSync(String path, String content)` | Scrive il contenuto nel file (sincrono) |
| `appendFile(String path, String content)` | Aggiunge contenuto al file |
| `ensureDirectory(String path)` | Crea la directory se non esiste |
| `deleteFile(String path)` | Elimina un file |
| `copyFile(String source, String destination)` | Copia un file |

<div id="json-and-yaml-helpers"></div>

## Helper JSON e YAML

Leggi e scrivi file JSON e YAML con gli helper integrati:

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

| Metodo | Descrizione |
|--------|-------------|
| `readJson(String path)` | Legge un file JSON come `Map<String, dynamic>` |
| `readJsonArray(String path)` | Legge un file JSON come `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Scrive dati come JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Aggiunge a un file di array JSON |
| `readYaml(String path)` | Legge un file YAML come `Map<String, dynamic>` |

<div id="dart-file-manipulation"></div>

## Manipolazione di File Dart

Helper per modificare programmaticamente file sorgente Dart -- utili quando si costruiscono strumenti di scaffolding:

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

| Metodo | Descrizione |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Aggiunge un import al file Dart (salta se presente) |
| `insertBeforeClosingBrace(String filePath, String code)` | Inserisce codice prima dell'ultima `}` nel file |
| `fileContains(String filePath, String identifier)` | Verifica se il file contiene una stringa |
| `fileContainsPattern(String filePath, Pattern pattern)` | Verifica se il file corrisponde a un pattern |

<div id="directory-helpers"></div>

## Helper per le Directory

Helper per lavorare con directory e trovare file:

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

| Metodo | Descrizione |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Elenca il contenuto della directory |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Trova file corrispondenti ai criteri |
| `deleteDirectory(String path)` | Elimina la directory ricorsivamente |
| `copyDirectory(String source, String destination)` | Copia la directory ricorsivamente |

<div id="case-conversion-helpers"></div>

## Helper per la Conversione di Formato

Converti stringhe tra diverse convenzioni di denominazione senza importare il pacchetto `recase`:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| Metodo | Formato di Output | Esempio |
|--------|-------------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Helper per i Percorsi del Progetto

Getter per le directory standard del progetto {{ config('app.name') }}, che restituiscono percorsi relativi alla radice del progetto:

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

| Proprieta' | Percorso |
|------------|----------|
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
| `projectPath(String relativePath)` | Risolve un percorso relativo all'interno del progetto |

<div id="platform-helpers"></div>

## Helper per la Piattaforma

Verifica la piattaforma e accedi alle variabili d'ambiente:

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

| Proprieta' / Metodo | Descrizione |
|----------------------|-------------|
| `isWindows` | `true` se in esecuzione su Windows |
| `isMacOS` | `true` se in esecuzione su macOS |
| `isLinux` | `true` se in esecuzione su Linux |
| `workingDirectory` | Percorso della directory di lavoro corrente |
| `env(String key, [String defaultValue = ''])` | Legge una variabile d'ambiente di sistema |

<div id="dart-and-flutter-commands"></div>

## Comandi Dart e Flutter

Esegui comandi comuni della CLI Dart e Flutter come metodi helper. Ognuno restituisce il codice di uscita del processo:

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

| Metodo | Descrizione |
|--------|-------------|
| `dartFormat(String path)` | Esegue `dart format` su un file o directory |
| `dartAnalyze([String? path])` | Esegue `dart analyze` |
| `flutterPubGet()` | Esegue `flutter pub get` |
| `flutterClean()` | Esegue `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Esegue `flutter build <target>` |
| `flutterTest([String? path])` | Esegue `flutter test` |

<div id="validation-helpers"></div>

## Helper per la Validazione

Helper per validare e pulire l'input dell'utente per la generazione di codice:

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

| Metodo | Descrizione |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Valida un nome identificatore Dart |
| `requireArgument(CommandResult result, {String? message})` | Richiede un primo argomento non vuoto oppure interrompe |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Pulisce e converte in PascalCase un nome di classe |
| `cleanFileName(String name, {String extension = '.dart'})` | Pulisce e converte in snake_case un nome di file |

<div id="file-scaffolding"></div>

## Scaffolding di File

Crea uno o piu' file con contenuto utilizzando il sistema di scaffolding.

### File Singolo

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

| Parametro | Tipo | Descrizione |
|-----------|------|-------------|
| `path` | `String` | Percorso del file da creare |
| `content` | `String` | Contenuto del file |
| `force` | `bool` | Sovrascrive se esiste (default: `false`) |
| `successMessage` | `String?` | Messaggio mostrato in caso di successo |

### File Multipli

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

La classe `ScaffoldFile`:

| Proprieta' | Tipo | Descrizione |
|------------|------|-------------|
| `path` | `String` | Percorso del file da creare |
| `content` | `String` | Contenuto del file |
| `successMessage` | `String?` | Messaggio mostrato in caso di successo |

<div id="task-runner"></div>

## Task Runner

Esegui una serie di task con nome e output di stato automatico.

### Task Runner Base

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

### Task Runner con Spinner

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

La classe `CommandTask`:

| Proprieta' | Tipo | Default | Descrizione |
|------------|------|---------|-------------|
| `name` | `String` | obbligatorio | Nome del task mostrato nell'output |
| `action` | `Future<void> Function()` | obbligatorio | Funzione asincrona da eseguire |
| `stopOnError` | `bool` | `true` | Se fermare i task rimanenti in caso di errore |

<div id="table-output"></div>

## Output a Tabella

Visualizza tabelle ASCII formattate nella console:

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

## Esempi

<div id="current-time-command"></div>

### Comando Ora Corrente

Un semplice comando che visualizza l'ora corrente:

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

### Comando Download Font

Un comando che scarica e installa Google Fonts nel progetto:

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

### Comando Pipeline di Deploy

Un comando che esegue una pipeline completa di deploy utilizzando il task runner:

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
