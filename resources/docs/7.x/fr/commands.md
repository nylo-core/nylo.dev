# Commandes

---

<a name="section-1"></a>
- [Introduction](#introduction)
- [Creer des commandes](#creating-commands)
- [Structure d'une commande](#command-structure)
- [Executer des commandes](#running-commands)
- [Registre des commandes](#command-registry)
- [Options et drapeaux](#options-and-flags)
  - [Ajouter des options](#adding-options)
  - [Ajouter des drapeaux](#adding-flags)
- [Resultat de la commande](#command-result)
- [Saisie interactive](#interactive-input)
  - [Saisie de texte](#text-input)
  - [Confirmation](#confirmation)
  - [Selection unique](#single-selection)
  - [Selection multiple](#multiple-selection)
  - [Saisie secrete](#secret-input)
- [Formatage de la sortie](#output-formatting)
- [Spinner et progression](#spinner-and-progress)
  - [Utiliser withSpinner](#using-withspinner)
  - [Controle manuel du spinner](#manual-spinner-control)
  - [Barre de progression](#progress-bar)
  - [Traiter des elements avec progression](#processing-items-with-progress)
- [Helper API](#api-helper)
- [Helpers du systeme de fichiers](#file-system-helpers)
- [Helpers JSON et YAML](#json-and-yaml-helpers)
- [Manipulation de fichiers Dart](#dart-file-manipulation)
- [Helpers de repertoires](#directory-helpers)
- [Helpers de conversion de casse](#case-conversion-helpers)
- [Helpers de chemins du projet](#project-path-helpers)
- [Helpers de plateforme](#platform-helpers)
- [Commandes Dart et Flutter](#dart-and-flutter-commands)
- [Helpers de validation](#validation-helpers)
- [Scaffolding de fichiers](#file-scaffolding)
- [Executeur de taches](#task-runner)
- [Sortie en tableau](#table-output)
- [Exemples](#examples)
  - [Commande heure actuelle](#current-time-command)
  - [Commande de telechargement de polices](#download-fonts-command)
  - [Commande de pipeline de deploiement](#deployment-pipeline-command)

<div id="introduction"></div>

## Introduction

Les commandes vous permettent d'etendre le CLI de {{ config('app.name') }} avec des outils personnalises specifiques a votre projet. En heritant de `NyCustomCommand`, vous pouvez automatiser des taches repetitives, creer des workflows de deploiement, generer du code ou ajouter toute fonctionnalite dont vous avez besoin directement dans votre terminal.

Chaque commande personnalisee a acces a un riche ensemble de helpers integres pour les E/S de fichiers, JSON/YAML, les invites interactives, les spinners, les barres de progression, les requetes API, et plus encore -- le tout sans importer de packages supplementaires.

> **Note :** Les commandes personnalisees s'executent en dehors du runtime Flutter. Vous ne pouvez pas importer `nylo_framework.dart` dans vos commandes. Utilisez `ny_cli.dart` a la place.

<div id="creating-commands"></div>

## Creer des commandes

Creez une nouvelle commande en utilisant Metro ou le CLI Dart :

```bash
metro make:command current_time
```

Vous pouvez specifier une categorie pour votre commande en utilisant l'option `--category` :

```bash
metro make:command current_time --category="project"
```

Cela cree un nouveau fichier dans `lib/app/commands/current_time.dart` et l'enregistre dans le registre des commandes.

<div id="command-structure"></div>

## Structure d'une commande

Chaque commande etend `NyCustomCommand` et implemente deux methodes cles :

- **`builder()`** -- configurer les options et les drapeaux
- **`handle()`** -- executer la logique de la commande

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

## Executer des commandes

Executez votre commande en utilisant Metro ou Dart :

```bash
metro app:current_time
```

Le nom de la commande suit le modele `categorie:nom`. Lorsque vous executez `metro` sans arguments, les commandes personnalisees apparaissent dans la section **Custom Commands** :

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

Pour afficher l'aide d'une commande :

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## Registre des commandes

Toutes les commandes personnalisees sont enregistrees dans `lib/app/commands/commands.json`. Ce fichier est mis a jour automatiquement lorsque vous utilisez `make:command` :

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

Chaque entree contient :

| Champ | Description |
|-------|-------------|
| `name` | Le nom de la commande (utilise apres le prefixe de categorie) |
| `category` | La categorie de la commande (par ex. `app`, `project`) |
| `script` | Le fichier Dart dans `lib/app/commands/` |

<div id="options-and-flags"></div>

## Options et drapeaux

Configurez les options et les drapeaux de votre commande dans la methode `builder()` en utilisant `CommandBuilder`.

<div id="adding-options"></div>

### Ajouter des options

Les options acceptent une valeur de l'utilisateur :

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

Utilisation :

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

| Parametre | Type | Description |
|-----------|------|-------------|
| `name` | `String` | Nom de l'option |
| `abbr` | `String?` | Abreviation d'un seul caractere |
| `help` | `String?` | Texte d'aide affiche avec `--help` |
| `allowed` | `List<String>?` | Restreindre aux valeurs autorisees |
| `defaultValue` | `String?` | Valeur par defaut si non fournie |

<div id="adding-flags"></div>

### Ajouter des drapeaux

Les drapeaux sont des interrupteurs booleens :

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

Utilisation :

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| Parametre | Type | Description |
|-----------|------|-------------|
| `name` | `String` | Nom du drapeau |
| `abbr` | `String?` | Abreviation d'un seul caractere |
| `help` | `String?` | Texte d'aide affiche avec `--help` |
| `defaultValue` | `bool` | Valeur par defaut (par defaut : `false`) |

<div id="command-result"></div>

## Resultat de la commande

La methode `handle()` recoit un objet `CommandResult` avec des accesseurs types pour lire les options, drapeaux et arguments analyses.

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

| Methode / Propriete | Retourne | Description |
|-------------------|---------|-------------|
| `getString(String name, {String? defaultValue})` | `String?` | Obtenir une valeur de type chaine |
| `getBool(String name, {bool? defaultValue})` | `bool?` | Obtenir une valeur booleenne |
| `getInt(String name, {int? defaultValue})` | `int?` | Obtenir une valeur entiere |
| `get<T>(String name)` | `T?` | Obtenir une valeur typee |
| `hasForceFlag` | `bool` | Si `--force` a ete passe |
| `hasHelpFlag` | `bool` | Si `--help` a ete passe |
| `arguments` | `List<String>` | Tous les arguments de la ligne de commande |
| `rest` | `List<String>` | Arguments restants non analyses |

<div id="interactive-input"></div>

## Saisie interactive

`NyCustomCommand` fournit des methodes pour collecter les saisies de l'utilisateur dans le terminal.

<div id="text-input"></div>

### Saisie de texte

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| Parametre | Type | Description |
|-----------|------|-------------|
| `question` | `String` | La question a afficher |
| `defaultValue` | `String` | Valeur par defaut si l'utilisateur appuie sur Entree (par defaut : `''`) |

<div id="confirmation"></div>

### Confirmation

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| Parametre | Type | Description |
|-----------|------|-------------|
| `question` | `String` | La question oui/non |
| `defaultValue` | `bool` | Reponse par defaut (par defaut : `false`) |

<div id="single-selection"></div>

### Selection unique

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| Parametre | Type | Description |
|-----------|------|-------------|
| `question` | `String` | Le texte de l'invite |
| `options` | `List<String>` | Choix disponibles |
| `defaultOption` | `String?` | Option pre-selectionnee |

<div id="multiple-selection"></div>

### Selection multiple

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

L'utilisateur saisit des numeros separes par des virgules ou `"all"`.

<div id="secret-input"></div>

### Saisie secrete

```dart
final apiKey = promptSecret('Enter your API key:');
```

La saisie est masquee dans le terminal. Revient a une saisie visible si le mode echo n'est pas supporte.

<div id="output-formatting"></div>

## Formatage de la sortie

Methodes pour afficher une sortie stylisee dans la console :

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

| Methode | Description |
|--------|-------------|
| `info(String message)` | Afficher du texte bleu |
| `error(String message)` | Afficher du texte rouge |
| `success(String message)` | Afficher du texte vert |
| `warning(String message)` | Afficher du texte jaune |
| `line(String message)` | Afficher du texte brut (sans couleur) |
| `newLine([int count = 1])` | Afficher des lignes vides |
| `comment(String message)` | Afficher du texte gris/attenue |
| `alert(String message)` | Afficher une boite d'alerte encadree |
| `abort([String? message, int exitCode = 1])` | Quitter la commande avec une erreur |

<div id="spinner-and-progress"></div>

## Spinner et progression

Les spinners et les barres de progression fournissent un retour visuel pendant les operations de longue duree.

<div id="using-withspinner"></div>

### Utiliser withSpinner

Encapsulez une tache asynchrone avec un spinner automatique :

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

| Parametre | Type | Description |
|-----------|------|-------------|
| `task` | `Future<T> Function()` | La fonction asynchrone a executer |
| `message` | `String` | Texte affiche pendant l'execution du spinner |
| `successMessage` | `String?` | Affiche en cas de succes |
| `errorMessage` | `String?` | Affiche en cas d'echec |

<div id="manual-spinner-control"></div>

### Controle manuel du spinner

Pour les workflows a plusieurs etapes, creez un spinner et controlez-le manuellement :

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

**Methodes de ConsoleSpinner :**

| Methode | Description |
|--------|-------------|
| `start([String? message])` | Demarrer l'animation du spinner |
| `update(String message)` | Modifier le message affiche |
| `stop({String? completionMessage, bool success = true})` | Arreter le spinner |

<div id="progress-bar"></div>

### Barre de progression

Creez et gerez une barre de progression manuellement :

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**Methodes de ConsoleProgressBar :**

| Methode / Propriete | Description |
|-------------------|-------------|
| `start()` | Demarrer la barre de progression |
| `tick([int amount = 1])` | Incrementer la progression |
| `update(int value)` | Definir la progression a une valeur specifique |
| `updateMessage(String newMessage)` | Modifier le message affiche |
| `complete([String? completionMessage])` | Terminer avec un message optionnel |
| `stop()` | Arreter sans terminer |
| `current` | Valeur de progression actuelle (getter) |
| `percentage` | Progression en pourcentage 0-100 (getter) |

<div id="processing-items-with-progress"></div>

### Traiter des elements avec progression

Traitez une liste d'elements avec un suivi automatique de la progression :

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

Le helper `api` fournit un wrapper simplifie autour de Dio pour effectuer des requetes HTTP :

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

Combinez avec `withSpinner` pour une meilleure experience utilisateur :

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

`ApiService` supporte les methodes `get`, `post`, `put`, `delete` et `patch`, chacune acceptant des parametres optionnels `queryParameters`, `data`, `options` et `cancelToken`.

<div id="file-system-helpers"></div>

## Helpers du systeme de fichiers

Helpers integres pour le systeme de fichiers afin que vous n'ayez pas besoin d'importer `dart:io` :

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

| Methode | Description |
|--------|-------------|
| `fileExists(String path)` | Retourne `true` si le fichier existe |
| `directoryExists(String path)` | Retourne `true` si le repertoire existe |
| `readFile(String path)` | Lire le fichier en tant que chaine (asynchrone) |
| `readFileSync(String path)` | Lire le fichier en tant que chaine (synchrone) |
| `writeFile(String path, String content)` | Ecrire du contenu dans un fichier (asynchrone) |
| `writeFileSync(String path, String content)` | Ecrire du contenu dans un fichier (synchrone) |
| `appendFile(String path, String content)` | Ajouter du contenu a un fichier |
| `ensureDirectory(String path)` | Creer le repertoire s'il n'existe pas |
| `deleteFile(String path)` | Supprimer un fichier |
| `copyFile(String source, String destination)` | Copier un fichier |

<div id="json-and-yaml-helpers"></div>

## Helpers JSON et YAML

Lisez et ecrivez des fichiers JSON et YAML avec les helpers integres :

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

| Methode | Description |
|--------|-------------|
| `readJson(String path)` | Lire un fichier JSON en tant que `Map<String, dynamic>` |
| `readJsonArray(String path)` | Lire un fichier JSON en tant que `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Ecrire des donnees au format JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Ajouter a un fichier de tableau JSON |
| `readYaml(String path)` | Lire un fichier YAML en tant que `Map<String, dynamic>` |

<div id="dart-file-manipulation"></div>

## Manipulation de fichiers Dart

Helpers pour editer programmatiquement des fichiers source Dart -- utiles lors de la creation d'outils de scaffolding :

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

| Methode | Description |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Ajouter un import a un fichier Dart (ignore si deja present) |
| `insertBeforeClosingBrace(String filePath, String code)` | Inserer du code avant le dernier `}` du fichier |
| `fileContains(String filePath, String identifier)` | Verifier si un fichier contient une chaine |
| `fileContainsPattern(String filePath, Pattern pattern)` | Verifier si un fichier correspond a un motif |

<div id="directory-helpers"></div>

## Helpers de repertoires

Helpers pour travailler avec les repertoires et rechercher des fichiers :

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

| Methode | Description |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Lister le contenu d'un repertoire |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Trouver des fichiers correspondant aux criteres |
| `deleteDirectory(String path)` | Supprimer un repertoire recursivement |
| `copyDirectory(String source, String destination)` | Copier un repertoire recursivement |

<div id="case-conversion-helpers"></div>

## Helpers de conversion de casse

Convertissez des chaines entre les conventions de nommage sans importer le package `recase` :

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| Methode | Format de sortie | Exemple |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Helpers de chemins du projet

Getters pour les repertoires standard d'un projet {{ config('app.name') }}, retournant des chemins relatifs a la racine du projet :

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

| Propriete | Chemin |
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
| `projectPath(String relativePath)` | Resout un chemin relatif dans le projet |

<div id="platform-helpers"></div>

## Helpers de plateforme

Verifiez la plateforme et accedez aux variables d'environnement :

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

| Propriete / Methode | Description |
|-------------------|-------------|
| `isWindows` | `true` si execution sur Windows |
| `isMacOS` | `true` si execution sur macOS |
| `isLinux` | `true` si execution sur Linux |
| `workingDirectory` | Chemin du repertoire de travail actuel |
| `env(String key, [String defaultValue = ''])` | Lire une variable d'environnement systeme |

<div id="dart-and-flutter-commands"></div>

## Commandes Dart et Flutter

Executez des commandes courantes du CLI Dart et Flutter en tant que methodes helper. Chacune retourne le code de sortie du processus :

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

| Methode | Description |
|--------|-------------|
| `dartFormat(String path)` | Executer `dart format` sur un fichier ou repertoire |
| `dartAnalyze([String? path])` | Executer `dart analyze` |
| `flutterPubGet()` | Executer `flutter pub get` |
| `flutterClean()` | Executer `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Executer `flutter build <target>` |
| `flutterTest([String? path])` | Executer `flutter test` |

<div id="validation-helpers"></div>

## Helpers de validation

Helpers pour valider et nettoyer les saisies utilisateur pour la generation de code :

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

| Methode | Description |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Valider un nom d'identifiant Dart |
| `requireArgument(CommandResult result, {String? message})` | Exiger un premier argument non vide ou abandonner |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Nettoyer et mettre en PascalCase un nom de classe |
| `cleanFileName(String name, {String extension = '.dart'})` | Nettoyer et mettre en snake_case un nom de fichier |

<div id="file-scaffolding"></div>

## Scaffolding de fichiers

Creez un ou plusieurs fichiers avec du contenu en utilisant le systeme de scaffolding.

### Fichier unique

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

| Parametre | Type | Description |
|-----------|------|-------------|
| `path` | `String` | Chemin du fichier a creer |
| `content` | `String` | Contenu du fichier |
| `force` | `bool` | Ecraser si existant (par defaut : `false`) |
| `successMessage` | `String?` | Message affiche en cas de succes |

### Fichiers multiples

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

La classe `ScaffoldFile` :

| Propriete | Type | Description |
|----------|------|-------------|
| `path` | `String` | Chemin du fichier a creer |
| `content` | `String` | Contenu du fichier |
| `successMessage` | `String?` | Message affiche en cas de succes |

<div id="task-runner"></div>

## Executeur de taches

Executez une serie de taches nommees avec une sortie de statut automatique.

### Executeur de taches basique

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

### Executeur de taches avec spinner

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

La classe `CommandTask` :

| Propriete | Type | Defaut | Description |
|----------|------|---------|-------------|
| `name` | `String` | requis | Nom de la tache affiche dans la sortie |
| `action` | `Future<void> Function()` | requis | Fonction asynchrone a executer |
| `stopOnError` | `bool` | `true` | Arreter les taches restantes en cas d'echec |

<div id="table-output"></div>

## Sortie en tableau

Affichez des tableaux ASCII formates dans la console :

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

Sortie :

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

## Exemples

<div id="current-time-command"></div>

### Commande heure actuelle

Une commande simple qui affiche l'heure actuelle :

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

### Commande de telechargement de polices

Une commande qui telecharge et installe des polices Google Fonts dans le projet :

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

### Commande de pipeline de deploiement

Une commande qui execute un pipeline de deploiement complet en utilisant l'executeur de taches :

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
