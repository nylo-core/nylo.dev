# Perintah

---

<a name="section-1"></a>
- [Pengantar](#introduction)
- [Membuat Perintah](#creating-commands)
- [Struktur Perintah](#command-structure)
- [Menjalankan Perintah](#running-commands)
- [Registri Perintah](#command-registry)
- [Opsi dan Flag](#options-and-flags)
  - [Menambahkan Opsi](#adding-options)
  - [Menambahkan Flag](#adding-flags)
- [Hasil Perintah](#command-result)
- [Input Interaktif](#interactive-input)
  - [Input Teks](#text-input)
  - [Konfirmasi](#confirmation)
  - [Pilihan Tunggal](#single-selection)
  - [Pilihan Ganda](#multiple-selection)
  - [Input Rahasia](#secret-input)
- [Format Output](#output-formatting)
- [Spinner dan Progres](#spinner-and-progress)
  - [Menggunakan withSpinner](#using-withspinner)
  - [Kontrol Spinner Manual](#manual-spinner-control)
  - [Progress Bar](#progress-bar)
  - [Memproses Item dengan Progres](#processing-items-with-progress)
- [Helper API](#api-helper)
- [Helper Sistem File](#file-system-helpers)
- [Helper JSON dan YAML](#json-and-yaml-helpers)
- [Manipulasi File Dart](#dart-file-manipulation)
- [Helper Direktori](#directory-helpers)
- [Helper Konversi Huruf](#case-conversion-helpers)
- [Helper Path Proyek](#project-path-helpers)
- [Helper Platform](#platform-helpers)
- [Perintah Dart dan Flutter](#dart-and-flutter-commands)
- [Helper Validasi](#validation-helpers)
- [Scaffolding File](#file-scaffolding)
- [Task Runner](#task-runner)
- [Output Tabel](#table-output)
- [Contoh](#examples)
  - [Perintah Waktu Saat Ini](#current-time-command)
  - [Perintah Unduh Font](#download-fonts-command)
  - [Perintah Pipeline Deployment](#deployment-pipeline-command)

<div id="introduction"></div>

## Pengantar

Perintah memungkinkan Anda memperluas CLI {{ config('app.name') }} dengan tooling khusus proyek. Dengan membuat subclass dari `NyCustomCommand`, Anda dapat mengotomatiskan tugas-tugas berulang, membangun alur kerja deployment, menghasilkan kode, atau menambahkan fungsionalitas apa pun yang Anda butuhkan langsung di terminal.

Setiap perintah kustom memiliki akses ke sekumpulan helper bawaan untuk file I/O, JSON/YAML, prompt interaktif, spinner, progress bar, permintaan API, dan lainnya -- semuanya tanpa perlu mengimpor paket tambahan.

> **Catatan:** Perintah kustom berjalan di luar runtime Flutter. Anda tidak dapat mengimpor `nylo_framework.dart` di perintah Anda. Gunakan `ny_cli.dart` sebagai gantinya.

<div id="creating-commands"></div>

## Membuat Perintah

Buat perintah baru menggunakan Metro atau Dart CLI:

```bash
metro make:command current_time
```

Anda dapat menentukan kategori untuk perintah Anda menggunakan opsi `--category`:

```bash
metro make:command current_time --category="project"
```

Ini akan membuat file baru di `lib/app/commands/current_time.dart` dan mendaftarkannya di registri perintah.

<div id="command-structure"></div>

## Struktur Perintah

Setiap perintah meng-extend `NyCustomCommand` dan mengimplementasikan dua method utama:

- **`builder()`** -- mengonfigurasi opsi dan flag
- **`handle()`** -- mengeksekusi logika perintah

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

## Menjalankan Perintah

Jalankan perintah Anda menggunakan Metro atau Dart:

```bash
metro app:current_time
```

Nama perintah mengikuti pola `category:name`. Saat Anda menjalankan `metro` tanpa argumen, perintah kustom akan muncul di bagian **Custom Commands**:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

Untuk menampilkan bantuan untuk sebuah perintah:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## Registri Perintah

Semua perintah kustom didaftarkan di `lib/app/commands/commands.json`. File ini diperbarui secara otomatis saat Anda menggunakan `make:command`:

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

Setiap entri memiliki:

| Field | Deskripsi |
|-------|-----------|
| `name` | Nama perintah (digunakan setelah prefiks kategori) |
| `category` | Kategori perintah (misalnya `app`, `project`) |
| `script` | File Dart di `lib/app/commands/` |

<div id="options-and-flags"></div>

## Opsi dan Flag

Konfigurasikan opsi dan flag perintah Anda di method `builder()` menggunakan `CommandBuilder`.

<div id="adding-options"></div>

### Menambahkan Opsi

Opsi menerima nilai dari pengguna:

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

Penggunaan:

```bash
metro project:deploy --environment=production
# atau menggunakan singkatan
metro project:deploy -e production
```

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `name` | `String` | Nama opsi |
| `abbr` | `String?` | Singkatan satu karakter |
| `help` | `String?` | Teks bantuan yang ditampilkan dengan `--help` |
| `allowed` | `List<String>?` | Membatasi ke nilai yang diizinkan |
| `defaultValue` | `String?` | Nilai default jika tidak diberikan |

<div id="adding-flags"></div>

### Menambahkan Flag

Flag adalah toggle boolean:

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

Penggunaan:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `name` | `String` | Nama flag |
| `abbr` | `String?` | Singkatan satu karakter |
| `help` | `String?` | Teks bantuan yang ditampilkan dengan `--help` |
| `defaultValue` | `bool` | Nilai default (default: `false`) |

<div id="command-result"></div>

## Hasil Perintah

Method `handle()` menerima objek `CommandResult` dengan aksesor bertipe untuk membaca opsi, flag, dan argumen yang telah di-parse.

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

| Method / Properti | Mengembalikan | Deskripsi |
|-------------------|---------------|-----------|
| `getString(String name, {String? defaultValue})` | `String?` | Mendapatkan nilai string |
| `getBool(String name, {bool? defaultValue})` | `bool?` | Mendapatkan nilai boolean |
| `getInt(String name, {int? defaultValue})` | `int?` | Mendapatkan nilai integer |
| `get<T>(String name)` | `T?` | Mendapatkan nilai bertipe |
| `hasForceFlag` | `bool` | Apakah `--force` diberikan |
| `hasHelpFlag` | `bool` | Apakah `--help` diberikan |
| `arguments` | `List<String>` | Semua argumen baris perintah |
| `rest` | `List<String>` | Argumen rest yang belum di-parse |

<div id="interactive-input"></div>

## Input Interaktif

`NyCustomCommand` menyediakan method untuk mengumpulkan input pengguna di terminal.

<div id="text-input"></div>

### Input Teks

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `question` | `String` | Pertanyaan yang ditampilkan |
| `defaultValue` | `String` | Nilai default jika pengguna menekan Enter (default: `''`) |

<div id="confirmation"></div>

### Konfirmasi

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `question` | `String` | Pertanyaan ya/tidak |
| `defaultValue` | `bool` | Jawaban default (default: `false`) |

<div id="single-selection"></div>

### Pilihan Tunggal

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `question` | `String` | Teks prompt |
| `options` | `List<String>` | Pilihan yang tersedia |
| `defaultOption` | `String?` | Opsi yang sudah dipilih sebelumnya |

<div id="multiple-selection"></div>

### Pilihan Ganda

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

Pengguna memasukkan nomor yang dipisahkan koma atau `"all"`.

<div id="secret-input"></div>

### Input Rahasia

```dart
final apiKey = promptSecret('Enter your API key:');
```

Input disembunyikan dari tampilan terminal. Akan kembali ke input yang terlihat jika mode echo tidak didukung.

<div id="output-formatting"></div>

## Format Output

Method untuk mencetak output bergaya ke konsol:

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

| Method | Deskripsi |
|--------|-----------|
| `info(String message)` | Mencetak teks biru |
| `error(String message)` | Mencetak teks merah |
| `success(String message)` | Mencetak teks hijau |
| `warning(String message)` | Mencetak teks kuning |
| `line(String message)` | Mencetak teks biasa (tanpa warna) |
| `newLine([int count = 1])` | Mencetak baris kosong |
| `comment(String message)` | Mencetak teks abu-abu/redup |
| `alert(String message)` | Mencetak kotak peringatan berbingkai |
| `abort([String? message, int exitCode = 1])` | Keluar dari perintah dengan error |

<div id="spinner-and-progress"></div>

## Spinner dan Progres

Spinner dan progress bar memberikan umpan balik visual selama operasi yang berjalan lama.

<div id="using-withspinner"></div>

### Menggunakan withSpinner

Bungkus tugas async dengan spinner otomatis:

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

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `task` | `Future<T> Function()` | Fungsi async yang dieksekusi |
| `message` | `String` | Teks yang ditampilkan saat spinner berjalan |
| `successMessage` | `String?` | Ditampilkan saat berhasil |
| `errorMessage` | `String?` | Ditampilkan saat gagal |

<div id="manual-spinner-control"></div>

### Kontrol Spinner Manual

Untuk alur kerja multi-langkah, buat spinner dan kontrol secara manual:

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

**Method ConsoleSpinner:**

| Method | Deskripsi |
|--------|-----------|
| `start([String? message])` | Memulai animasi spinner |
| `update(String message)` | Mengubah pesan yang ditampilkan |
| `stop({String? completionMessage, bool success = true})` | Menghentikan spinner |

<div id="progress-bar"></div>

### Progress Bar

Membuat dan mengelola progress bar secara manual:

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**Method ConsoleProgressBar:**

| Method / Properti | Deskripsi |
|-------------------|-----------|
| `start()` | Memulai progress bar |
| `tick([int amount = 1])` | Menambah progres |
| `update(int value)` | Mengatur progres ke nilai tertentu |
| `updateMessage(String newMessage)` | Mengubah pesan yang ditampilkan |
| `complete([String? completionMessage])` | Menyelesaikan dengan pesan opsional |
| `stop()` | Menghentikan tanpa menyelesaikan |
| `current` | Nilai progres saat ini (getter) |
| `percentage` | Progres sebagai persentase 0-100 (getter) |

<div id="processing-items-with-progress"></div>

### Memproses Item dengan Progres

Memproses daftar item dengan pelacakan progres otomatis:

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

Helper `api` menyediakan wrapper sederhana di atas Dio untuk membuat permintaan HTTP:

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

Kombinasikan dengan `withSpinner` untuk pengalaman pengguna yang lebih baik:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

`ApiService` mendukung method `get`, `post`, `put`, `delete`, dan `patch`, masing-masing menerima parameter opsional `queryParameters`, `data`, `options`, dan `cancelToken`.

<div id="file-system-helpers"></div>

## Helper Sistem File

Helper sistem file bawaan sehingga Anda tidak perlu mengimpor `dart:io`:

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

| Method | Deskripsi |
|--------|-----------|
| `fileExists(String path)` | Mengembalikan `true` jika file ada |
| `directoryExists(String path)` | Mengembalikan `true` jika direktori ada |
| `readFile(String path)` | Membaca file sebagai string (async) |
| `readFileSync(String path)` | Membaca file sebagai string (sync) |
| `writeFile(String path, String content)` | Menulis konten ke file (async) |
| `writeFileSync(String path, String content)` | Menulis konten ke file (sync) |
| `appendFile(String path, String content)` | Menambahkan konten ke file |
| `ensureDirectory(String path)` | Membuat direktori jika belum ada |
| `deleteFile(String path)` | Menghapus file |
| `copyFile(String source, String destination)` | Menyalin file |

<div id="json-and-yaml-helpers"></div>

## Helper JSON dan YAML

Membaca dan menulis file JSON dan YAML dengan helper bawaan:

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

| Method | Deskripsi |
|--------|-----------|
| `readJson(String path)` | Membaca file JSON sebagai `Map<String, dynamic>` |
| `readJsonArray(String path)` | Membaca file JSON sebagai `List<dynamic>` |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Menulis data sebagai JSON |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | Menambahkan ke file array JSON |
| `readYaml(String path)` | Membaca file YAML sebagai `Map<String, dynamic>` |

<div id="dart-file-manipulation"></div>

## Manipulasi File Dart

Helper untuk mengedit file sumber Dart secara programatik -- berguna saat membangun alat scaffolding:

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

| Method | Deskripsi |
|--------|-----------|
| `addImport(String filePath, String importStatement)` | Menambahkan import ke file Dart (dilewati jika sudah ada) |
| `insertBeforeClosingBrace(String filePath, String code)` | Menyisipkan kode sebelum `}` terakhir di file |
| `fileContains(String filePath, String identifier)` | Memeriksa apakah file berisi string tertentu |
| `fileContainsPattern(String filePath, Pattern pattern)` | Memeriksa apakah file cocok dengan pola tertentu |

<div id="directory-helpers"></div>

## Helper Direktori

Helper untuk bekerja dengan direktori dan menemukan file:

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

| Method | Deskripsi |
|--------|-----------|
| `listDirectory(String path, {bool recursive = false})` | Menampilkan isi direktori |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Menemukan file yang sesuai kriteria |
| `deleteDirectory(String path)` | Menghapus direktori secara rekursif |
| `copyDirectory(String source, String destination)` | Menyalin direktori secara rekursif |

<div id="case-conversion-helpers"></div>

## Helper Konversi Huruf

Mengonversi string antar konvensi penamaan tanpa mengimpor paket `recase`:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| Method | Format Output | Contoh |
|--------|---------------|--------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Helper Path Proyek

Getter untuk direktori standar proyek {{ config('app.name') }}, mengembalikan path relatif terhadap root proyek:

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

| Properti | Path |
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
| `projectPath(String relativePath)` | Menyelesaikan path relatif di dalam proyek |

<div id="platform-helpers"></div>

## Helper Platform

Memeriksa platform dan mengakses variabel lingkungan:

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

| Properti / Method | Deskripsi |
|-------------------|-----------|
| `isWindows` | `true` jika berjalan di Windows |
| `isMacOS` | `true` jika berjalan di macOS |
| `isLinux` | `true` jika berjalan di Linux |
| `workingDirectory` | Path direktori kerja saat ini |
| `env(String key, [String defaultValue = ''])` | Membaca variabel lingkungan sistem |

<div id="dart-and-flutter-commands"></div>

## Perintah Dart dan Flutter

Menjalankan perintah CLI Dart dan Flutter umum sebagai method helper. Masing-masing mengembalikan kode exit proses:

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

| Method | Deskripsi |
|--------|-----------|
| `dartFormat(String path)` | Menjalankan `dart format` pada file atau direktori |
| `dartAnalyze([String? path])` | Menjalankan `dart analyze` |
| `flutterPubGet()` | Menjalankan `flutter pub get` |
| `flutterClean()` | Menjalankan `flutter clean` |
| `flutterBuild(String target, {List<String> args})` | Menjalankan `flutter build <target>` |
| `flutterTest([String? path])` | Menjalankan `flutter test` |

<div id="validation-helpers"></div>

## Helper Validasi

Helper untuk memvalidasi dan membersihkan input pengguna untuk pembuatan kode:

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

| Method | Deskripsi |
|--------|-----------|
| `isValidDartIdentifier(String name)` | Memvalidasi nama identifier Dart |
| `requireArgument(CommandResult result, {String? message})` | Memerlukan argumen pertama yang tidak kosong atau membatalkan |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Membersihkan dan mengubah nama kelas ke PascalCase |
| `cleanFileName(String name, {String extension = '.dart'})` | Membersihkan dan mengubah nama file ke snake_case |

<div id="file-scaffolding"></div>

## Scaffolding File

Membuat satu atau banyak file dengan konten menggunakan sistem scaffolding.

### File Tunggal

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

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `path` | `String` | Path file yang akan dibuat |
| `content` | `String` | Konten file |
| `force` | `bool` | Timpa jika sudah ada (default: `false`) |
| `successMessage` | `String?` | Pesan yang ditampilkan saat berhasil |

### Banyak File

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

Kelas `ScaffoldFile`:

| Properti | Tipe | Deskripsi |
|----------|------|-----------|
| `path` | `String` | Path file yang akan dibuat |
| `content` | `String` | Konten file |
| `successMessage` | `String?` | Pesan yang ditampilkan saat berhasil |

<div id="task-runner"></div>

## Task Runner

Menjalankan serangkaian tugas bernama dengan output status otomatis.

### Task Runner Dasar

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

### Task Runner dengan Spinner

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

Kelas `CommandTask`:

| Properti | Tipe | Default | Deskripsi |
|----------|------|---------|-----------|
| `name` | `String` | wajib | Nama tugas yang ditampilkan di output |
| `action` | `Future<void> Function()` | wajib | Fungsi async yang dieksekusi |
| `stopOnError` | `bool` | `true` | Apakah menghentikan tugas yang tersisa jika gagal |

<div id="table-output"></div>

## Output Tabel

Menampilkan tabel ASCII terformat di konsol:

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

## Contoh

<div id="current-time-command"></div>

### Perintah Waktu Saat Ini

Perintah sederhana yang menampilkan waktu saat ini:

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

### Perintah Unduh Font

Perintah yang mengunduh dan menginstal Google Fonts ke dalam proyek:

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

### Perintah Pipeline Deployment

Perintah yang menjalankan pipeline deployment lengkap menggunakan task runner:

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
