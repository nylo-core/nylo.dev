# Commands

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Crear comandos](#creating-commands "Crear comandos")
- [Estructura del comando](#command-structure "Estructura del comando")
- [Ejecutar comandos](#running-commands "Ejecutar comandos")
- [Registro de comandos](#command-registry "Registro de comandos")
- [Opciones y flags](#options-and-flags "Opciones y flags")
  - [Agregar opciones](#adding-options "Agregar opciones")
  - [Agregar flags](#adding-flags "Agregar flags")
- [Resultado del comando](#command-result "Resultado del comando")
- [Entrada interactiva](#interactive-input "Entrada interactiva")
  - [Entrada de texto](#text-input "Entrada de texto")
  - [Confirmacion](#confirmation "Confirmacion")
  - [Seleccion unica](#single-selection "Seleccion unica")
  - [Seleccion multiple](#multiple-selection "Seleccion multiple")
  - [Entrada secreta](#secret-input "Entrada secreta")
- [Formato de salida](#output-formatting "Formato de salida")
- [Spinner y progreso](#spinner-and-progress "Spinner y progreso")
  - [Usar withSpinner](#using-withspinner "Usar withSpinner")
  - [Control manual del spinner](#manual-spinner-control "Control manual del spinner")
  - [Barra de progreso](#progress-bar "Barra de progreso")
  - [Procesar elementos con progreso](#processing-items-with-progress "Procesar elementos con progreso")
- [Helper de API](#api-helper "Helper de API")
- [Helpers del sistema de archivos](#file-system-helpers "Helpers del sistema de archivos")
- [Helpers de JSON y YAML](#json-and-yaml-helpers "Helpers de JSON y YAML")
- [Manipulacion de archivos Dart](#dart-file-manipulation "Manipulacion de archivos Dart")
- [Helpers de directorios](#directory-helpers "Helpers de directorios")
- [Helpers de conversion de mayusculas](#case-conversion-helpers "Helpers de conversion de mayusculas")
- [Helpers de rutas del proyecto](#project-path-helpers "Helpers de rutas del proyecto")
- [Helpers de plataforma](#platform-helpers "Helpers de plataforma")
- [Comandos de Dart y Flutter](#dart-and-flutter-commands "Comandos de Dart y Flutter")
- [Helpers de validacion](#validation-helpers "Helpers de validacion")
- [Scaffolding de archivos](#file-scaffolding "Scaffolding de archivos")
- [Ejecutor de tareas](#task-runner "Ejecutor de tareas")
- [Salida en tabla](#table-output "Salida en tabla")
- [Ejemplos](#examples "Ejemplos")
  - [Comando de hora actual](#current-time-command "Comando de hora actual")
  - [Comando de descarga de fuentes](#download-fonts-command "Comando de descarga de fuentes")
  - [Comando de pipeline de despliegue](#deployment-pipeline-command "Comando de pipeline de despliegue")

<div id="introduction"></div>

## Introduccion

Los comandos te permiten extender la CLI de {{ config('app.name') }} con herramientas personalizadas especificas del proyecto. Al crear subclases de `NyCustomCommand`, puedes automatizar tareas repetitivas, construir flujos de despliegue, generar codigo, o agregar cualquier funcionalidad que necesites directamente en tu terminal.

Cada comando personalizado tiene acceso a un rico conjunto de helpers integrados para E/S de archivos, JSON/YAML, prompts interactivos, spinners, barras de progreso, solicitudes API, y mas -- todo sin importar paquetes adicionales.

> **Nota:** Los comandos personalizados se ejecutan fuera del entorno de ejecucion de Flutter. No puedes importar `nylo_framework.dart` en tus comandos. Usa `ny_cli.dart` en su lugar.

<div id="creating-commands"></div>

## Crear comandos

Crea un nuevo comando usando Metro o la CLI de Dart:

```bash
metro make:command current_time
```

Puedes especificar una categoria para tu comando usando la opcion `--category`:

```bash
metro make:command current_time --category="project"
```

Esto crea un nuevo archivo en `lib/app/commands/current_time.dart` y lo registra en el registro de comandos.

<div id="command-structure"></div>

## Estructura del comando

Cada comando extiende `NyCustomCommand` e implementa dos metodos clave:

- **`builder()`** -- configura opciones y flags
- **`handle()`** -- ejecuta la logica del comando

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

## Ejecutar comandos

Ejecuta tu comando usando Metro:

```bash
metro app:current_time
```

El nombre del comando sigue el patron `categoria:nombre`. Cuando ejecutas `metro` sin argumentos, tus comandos personalizados aparecen en la seccion **Custom Commands**:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

Para mostrar la ayuda de un comando:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## Registro de comandos

Todos los comandos personalizados se registran en `lib/app/commands/commands.json`. Este archivo se actualiza automaticamente cuando usas `make:command`:

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

Cada entrada tiene:

| Campo | Descripcion |
|-------|-------------|
| `name` | El nombre del comando (usado despues del prefijo de categoria) |
| `category` | La categoria del comando (ej. `app`, `project`) |
| `script` | El archivo Dart en `lib/app/commands/` |

<div id="options-and-flags"></div>

## Opciones y flags

Configura las opciones y flags de tu comando en el metodo `builder()` usando `CommandBuilder`.

<div id="adding-options"></div>

### Agregar opciones

Las opciones aceptan un valor del usuario:

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

Uso:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `name` | `String` | Nombre de la opcion |
| `abbr` | `String?` | Abreviatura de un caracter |
| `help` | `String?` | Texto de ayuda mostrado con `--help` |
| `allowed` | `List<String>?` | Restringir a valores permitidos |
| `defaultValue` | `String?` | Valor por defecto si no se proporciona |

<div id="adding-flags"></div>

### Agregar flags

Los flags son alternadores booleanos:

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

Uso:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `name` | `String` | Nombre del flag |
| `abbr` | `String?` | Abreviatura de un caracter |
| `help` | `String?` | Texto de ayuda mostrado con `--help` |
| `defaultValue` | `bool` | Valor por defecto (por defecto: `false`) |

<div id="command-result"></div>

## Resultado del comando

El metodo `handle()` recibe un objeto `CommandResult` con accesores tipados para leer opciones, flags y argumentos parseados.

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

| Metodo / Propiedad | Devuelve | Descripcion |
|-------------------|---------|-------------|
| `getString(String name, {String? defaultValue})` | `String?` | Obtener valor de cadena |
| `getBool(String name, {bool? defaultValue})` | `bool?` | Obtener valor booleano |
| `getInt(String name, {int? defaultValue})` | `int?` | Obtener valor entero |
| `get<T>(String name)` | `T?` | Obtener valor con tipo |
| `hasForceFlag` | `bool` | Si se paso `--force` |
| `hasHelpFlag` | `bool` | Si se paso `--help` |
| `arguments` | `List<String>` | Todos los argumentos de linea de comandos |
| `rest` | `List<String>` | Argumentos no parseados |

<div id="interactive-input"></div>

## Entrada interactiva

`NyCustomCommand` proporciona metodos para recopilar entrada del usuario en la terminal.

<div id="text-input"></div>

### Entrada de texto

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `question` | `String` | La pregunta a mostrar |
| `defaultValue` | `String` | Valor por defecto si el usuario presiona Enter (por defecto: `''`) |

<div id="confirmation"></div>

### Confirmacion

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `question` | `String` | La pregunta de si/no |
| `defaultValue` | `bool` | Respuesta por defecto (por defecto: `false`) |

<div id="single-selection"></div>

### Seleccion unica

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `question` | `String` | El texto del prompt |
| `options` | `List<String>` | Opciones disponibles |
| `defaultOption` | `String?` | Opcion preseleccionada |

<div id="multiple-selection"></div>

### Seleccion multiple

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

El usuario introduce numeros separados por comas o `"all"`.

<div id="secret-input"></div>

### Entrada secreta

```dart
final apiKey = promptSecret('Enter your API key:');
```

La entrada se oculta en la pantalla de la terminal. Cambia a entrada visible si el modo de eco no esta soportado.

<div id="output-formatting"></div>

## Formato de salida

Metodos para imprimir salida con estilo en la consola:

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

| Metodo | Descripcion |
|--------|-------------|
| `info(String message)` | Imprimir texto azul |
| `error(String message)` | Imprimir texto rojo |
| `success(String message)` | Imprimir texto verde |
| `warning(String message)` | Imprimir texto amarillo |
| `line(String message)` | Imprimir texto plano (sin color) |
| `newLine([int count = 1])` | Imprimir lineas en blanco |
| `comment(String message)` | Imprimir texto gris/tenue |
| `alert(String message)` | Imprimir cuadro de alerta con borde |
| `abort([String? message, int exitCode = 1])` | Salir del comando con un error |

<div id="spinner-and-progress"></div>

## Spinner y progreso

Los spinners y barras de progreso proporcionan retroalimentacion visual durante operaciones de larga duracion.

<div id="using-withspinner"></div>

### Usar withSpinner

Envuelve una tarea asincrona con un spinner automatico:

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

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `task` | `Future<T> Function()` | La funcion asincrona a ejecutar |
| `message` | `String` | Texto mostrado mientras el spinner gira |
| `successMessage` | `String?` | Mostrado en caso de exito |
| `errorMessage` | `String?` | Mostrado en caso de error |

<div id="manual-spinner-control"></div>

### Control manual del spinner

Para flujos de trabajo de multiples pasos, crea un spinner y controlalo manualmente:

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

**Metodos de ConsoleSpinner:**

| Metodo | Descripcion |
|--------|-------------|
| `start([String? message])` | Iniciar la animacion del spinner |
| `update(String message)` | Cambiar el mensaje mostrado |
| `stop({String? completionMessage, bool success = true})` | Detener el spinner |

<div id="progress-bar"></div>

### Barra de progreso

Crea y gestiona una barra de progreso manualmente:

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**Metodos de ConsoleProgressBar:**

| Metodo / Propiedad | Descripcion |
|-------------------|-------------|
| `start()` | Iniciar la barra de progreso |
| `tick([int amount = 1])` | Incrementar progreso |
| `update(int value)` | Establecer progreso a un valor especifico |
| `updateMessage(String newMessage)` | Cambiar el mensaje mostrado |
| `complete([String? completionMessage])` | Completar con mensaje opcional |
| `stop()` | Detener sin completar |
| `current` | Valor de progreso actual (getter) |
| `percentage` | Progreso como porcentaje 0-100 (getter) |

<div id="processing-items-with-progress"></div>

### Procesar elementos con progreso

Procesa una lista de elementos con seguimiento automatico de progreso:

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

## Helper de API

El helper `api` proporciona un envoltorio simplificado sobre Dio para realizar solicitudes HTTP:

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

Combinalo con `withSpinner` para una mejor experiencia de usuario:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

El `ApiService` soporta metodos `get`, `post`, `put`, `delete` y `patch`, cada uno aceptando opcionalmente `queryParameters`, `data`, `options` y `cancelToken`.

<div id="file-system-helpers"></div>

## Helpers del sistema de archivos

Helpers integrados del sistema de archivos para que no necesites importar `dart:io`:

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

| Metodo | Descripcion |
|--------|-------------|
| `fileExists(String path)` | Devuelve `true` si el archivo existe |
| `directoryExists(String path)` | Devuelve `true` si el directorio existe |
| `readFile(String path)` | Leer archivo como cadena (asincrono) |
| `readFileSync(String path)` | Leer archivo como cadena (sincrono) |
| `writeFile(String path, String content)` | Escribir contenido en archivo (asincrono) |
| `writeFileSync(String path, String content)` | Escribir contenido en archivo (sincrono) |
| `appendFile(String path, String content)` | Agregar contenido al archivo |
| `ensureDirectory(String path)` | Crear directorio si no existe |
| `deleteFile(String path)` | Eliminar un archivo |
| `copyFile(String source, String destination)` | Copiar un archivo |

<div id="json-and-yaml-helpers"></div>

## Helpers de JSON y YAML

Lee y escribe archivos JSON y YAML con helpers integrados:

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

| Metodo | Descripcion |
|--------|-------------|
| `readJson(String path)` | Leer archivo JSON como `Map<String, dynamic>` |
| `readJsonArray(String path)` | Leer archivo JSON como `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Escribir datos como JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Agregar a un archivo de array JSON |
| `readYaml(String path)` | Leer archivo YAML como `Map<String, dynamic>` |

<div id="dart-file-manipulation"></div>

## Manipulacion de archivos Dart

Helpers para editar archivos fuente Dart programaticamente -- utiles al construir herramientas de scaffolding:

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

| Metodo | Descripcion |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Agregar import a archivo Dart (omite si ya existe) |
| `insertBeforeClosingBrace(String filePath, String code)` | Insertar codigo antes del ultimo `}` en el archivo |
| `fileContains(String filePath, String identifier)` | Verificar si el archivo contiene una cadena |
| `fileContainsPattern(String filePath, Pattern pattern)` | Verificar si el archivo coincide con un patron |

<div id="directory-helpers"></div>

## Helpers de directorios

Helpers para trabajar con directorios y encontrar archivos:

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

| Metodo | Descripcion |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Listar contenido del directorio |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Encontrar archivos que coincidan con criterios |
| `deleteDirectory(String path)` | Eliminar directorio recursivamente |
| `copyDirectory(String source, String destination)` | Copiar directorio recursivamente |

<div id="case-conversion-helpers"></div>

## Helpers de conversion de mayusculas

Convierte cadenas entre convenciones de nombres sin importar el paquete `recase`:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| Metodo | Formato de salida | Ejemplo |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Helpers de rutas del proyecto

Getters para directorios estandar de proyectos {{ config('app.name') }}, devolviendo rutas relativas a la raiz del proyecto:

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

| Propiedad | Ruta |
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
| `projectPath(String relativePath)` | Resuelve una ruta relativa dentro del proyecto |

<div id="platform-helpers"></div>

## Helpers de plataforma

Verifica la plataforma y accede a variables de entorno:

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

| Propiedad / Metodo | Descripcion |
|-------------------|-------------|
| `isWindows` | `true` si se ejecuta en Windows |
| `isMacOS` | `true` si se ejecuta en macOS |
| `isLinux` | `true` si se ejecuta en Linux |
| `workingDirectory` | Ruta del directorio de trabajo actual |
| `env(String key, [String defaultValue = ''])` | Leer variable de entorno del sistema |

<div id="dart-and-flutter-commands"></div>

## Comandos de Dart y Flutter

Ejecuta comandos comunes de la CLI de Dart y Flutter como metodos auxiliares. Cada uno devuelve el codigo de salida del proceso:

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

| Metodo | Descripcion |
|--------|-------------|
| `dartFormat(String path)` | Ejecutar `dart format` en un archivo o directorio |
| `dartAnalyze([String? path])` | Ejecutar `dart analyze` |
| `flutterPubGet()` | Ejecutar `flutter pub get` |
| `flutterClean()` | Ejecutar `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Ejecutar `flutter build <target>` |
| `flutterTest([String? path])` | Ejecutar `flutter test` |

<div id="validation-helpers"></div>

## Helpers de validacion

Helpers para validar y limpiar entrada del usuario para generacion de codigo:

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

| Metodo | Descripcion |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Validar un nombre de identificador Dart |
| `requireArgument(CommandResult result, {String? message})` | Requerir primer argumento no vacio o abortar |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Limpiar y convertir a PascalCase un nombre de clase |
| `cleanFileName(String name, {String extension = '.dart'})` | Limpiar y convertir a snake_case un nombre de archivo |

<div id="file-scaffolding"></div>

## Scaffolding de archivos

Crea uno o varios archivos con contenido usando el sistema de scaffolding.

### Archivo unico

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

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `path` | `String` | Ruta del archivo a crear |
| `content` | `String` | Contenido del archivo |
| `force` | `bool` | Sobrescribir si existe (por defecto: `false`) |
| `successMessage` | `String?` | Mensaje mostrado en caso de exito |

### Multiples archivos

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

La clase `ScaffoldFile`:

| Propiedad | Tipo | Descripcion |
|----------|------|-------------|
| `path` | `String` | Ruta del archivo a crear |
| `content` | `String` | Contenido del archivo |
| `successMessage` | `String?` | Mensaje mostrado en caso de exito |

<div id="task-runner"></div>

## Ejecutor de tareas

Ejecuta una serie de tareas nombradas con salida de estado automatica.

### Ejecutor de tareas basico

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

### Ejecutor de tareas con spinner

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

La clase `CommandTask`:

| Propiedad | Tipo | Predeterminado | Descripcion |
|----------|------|---------|-------------|
| `name` | `String` | requerido | Nombre de la tarea mostrado en la salida |
| `action` | `Future<void> Function()` | requerido | Funcion asincrona a ejecutar |
| `stopOnError` | `bool` | `true` | Si se detienen las tareas restantes en caso de error |

<div id="table-output"></div>

## Salida en tabla

Muestra tablas ASCII formateadas en la consola:

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

Salida:

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

## Ejemplos

<div id="current-time-command"></div>

### Comando de hora actual

Un comando simple que muestra la hora actual:

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

### Comando de descarga de fuentes

Un comando que descarga e instala Google Fonts en el proyecto:

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

### Comando de pipeline de despliegue

Un comando que ejecuta un pipeline de despliegue completo usando el ejecutor de tareas:

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
