# Komutlar

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Komut Oluşturma](#creating-commands "Komut oluşturma")
- [Komut Yapısı](#command-structure "Komut yapısı")
- [Komutları Çalıştırma](#running-commands "Komutları çalıştırma")
- [Komut Kaydı](#command-registry "Komut kaydı")
- [Seçenekler ve Bayraklar](#options-and-flags "Seçenekler ve bayraklar")
  - [Seçenek Ekleme](#adding-options "Seçenek ekleme")
  - [Bayrak Ekleme](#adding-flags "Bayrak ekleme")
- [Komut Sonucu](#command-result "Komut sonucu")
- [Etkileşimli Girdi](#interactive-input "Etkileşimli girdi")
  - [Metin Girdisi](#text-input "Metin girdisi")
  - [Onay](#confirmation "Onay")
  - [Tekli Seçim](#single-selection "Tekli seçim")
  - [Çoklu Seçim](#multiple-selection "Çoklu seçim")
  - [Gizli Girdi](#secret-input "Gizli girdi")
- [Çıktı Biçimlendirme](#output-formatting "Çıktı biçimlendirme")
- [Döndürücü ve İlerleme](#spinner-and-progress "Döndürücü ve ilerleme")
  - [withSpinner Kullanımı](#using-withspinner "withSpinner kullanımı")
  - [Manuel Döndürücü Kontrolü](#manual-spinner-control "Manuel döndürücü kontrolü")
  - [İlerleme Çubuğu](#progress-bar "İlerleme çubuğu")
  - [İlerleme ile Öğe İşleme](#processing-items-with-progress "İlerleme ile öğe işleme")
- [API Yardımcısı](#api-helper "API yardımcısı")
- [Dosya Sistemi Yardımcıları](#file-system-helpers "Dosya sistemi yardımcıları")
- [JSON ve YAML Yardımcıları](#json-and-yaml-helpers "JSON ve YAML yardımcıları")
- [Dart Dosya Manipülasyonu](#dart-file-manipulation "Dart dosya manipülasyonu")
- [Dizin Yardımcıları](#directory-helpers "Dizin yardımcıları")
- [Büyük/Küçük Harf Dönüşüm Yardımcıları](#case-conversion-helpers "Büyük/küçük harf dönüşüm yardımcıları")
- [Proje Yol Yardımcıları](#project-path-helpers "Proje yol yardımcıları")
- [Platform Yardımcıları](#platform-helpers "Platform yardımcıları")
- [Dart ve Flutter Komutları](#dart-and-flutter-commands "Dart ve Flutter komutları")
- [Doğrulama Yardımcıları](#validation-helpers "Doğrulama yardımcıları")
- [Dosya İskelesi](#file-scaffolding "Dosya iskelesi")
- [Görev Çalıştırıcı](#task-runner "Görev çalıştırıcı")
- [Tablo Çıktısı](#table-output "Tablo çıktısı")
- [Örnekler](#examples "Örnekler")
  - [Güncel Saat Komutu](#current-time-command "Güncel saat komutu")
  - [Font İndirme Komutu](#download-fonts-command "Font indirme komutu")
  - [Dağıtım Hattı Komutu](#deployment-pipeline-command "Dağıtım hattı komutu")

<div id="introduction"></div>

## Giriş

Komutlar, {{ config('app.name') }}'nun CLI'sını projeye özel araçlarla genişletmenizi sağlar. `NyCustomCommand` alt sınıfını oluşturarak tekrarlayan görevleri otomatikleştirebilir, dağıtım iş akışları oluşturabilir, kod üretebilir veya ihtiyaç duyduğunuz herhangi bir işlevi doğrudan terminalinize ekleyebilirsiniz.

Her özel komut; dosya G/Ç, JSON/YAML, etkileşimli istemler, döndürücüler, ilerleme çubukları, API istekleri ve daha fazlası için zengin bir yerleşik yardımcı setine erişim sağlar -- ekstra paket içe aktarmaya gerek kalmadan.

> **Not:** Özel komutlar Flutter çalışma zamanı dışında çalışır. Komutlarınızda `nylo_framework.dart` dosyasını içe aktaramazsınız. Bunun yerine `ny_cli.dart` kullanın.

<div id="creating-commands"></div>

## Komut Oluşturma

Metro veya Dart CLI kullanarak yeni bir komut oluşturun:

```bash
metro make:command current_time
```

`--category` seçeneğini kullanarak komutunuz için bir kategori belirleyebilirsiniz:

```bash
metro make:command current_time --category="project"
```

Bu, `lib/app/commands/current_time.dart` konumunda yeni bir dosya oluşturur ve komut kaydına kaydeder.

<div id="command-structure"></div>

## Komut Yapısı

Her komut `NyCustomCommand` sınıfını genişletir ve iki temel metodu uygular:

- **`builder()`** -- seçenekleri ve bayrakları yapılandırır
- **`handle()`** -- komut mantığını yürütür

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

## Komutları Çalıştırma

Metro veya Dart kullanarak komutunuzu çalıştırın:

```bash
metro app:current_time
```

Komut adı `kategori:isim` kalıbını izler. `metro` komutunu argümansız çalıştırdığınızda, özel komutlar **Custom Commands** bölümünde görünür:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

Bir komut için yardım bilgilerini görüntülemek için:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## Komut Kaydı

Tüm özel komutlar `lib/app/commands/commands.json` dosyasında kayıtlıdır. Bu dosya, `make:command` kullandığınızda otomatik olarak güncellenir:

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

Her giriş şunları içerir:

| Alan | Açıklama |
|-------|-------------|
| `name` | Komut adı (kategori önekinden sonra kullanılır) |
| `category` | Komut kategorisi (ör. `app`, `project`) |
| `script` | `lib/app/commands/` içindeki Dart dosyası |

<div id="options-and-flags"></div>

## Seçenekler ve Bayraklar

Komutunuzun seçeneklerini ve bayraklarını `builder()` metodu içinde `CommandBuilder` kullanarak yapılandırın.

<div id="adding-options"></div>

### Seçenek Ekleme

Seçenekler kullanıcıdan bir değer kabul eder:

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

Kullanım:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `name` | `String` | Seçenek adı |
| `abbr` | `String?` | Tek karakter kısaltma |
| `help` | `String?` | `--help` ile gösterilen yardım metni |
| `allowed` | `List<String>?` | İzin verilen değerlerle sınırla |
| `defaultValue` | `String?` | Sağlanmazsa varsayılan değer |

<div id="adding-flags"></div>

### Bayrak Ekleme

Bayraklar boolean değer anahtarlarıdır:

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

Kullanım:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `name` | `String` | Bayrak adı |
| `abbr` | `String?` | Tek karakter kısaltma |
| `help` | `String?` | `--help` ile gösterilen yardım metni |
| `defaultValue` | `bool` | Varsayılan değer (varsayılan: `false`) |

<div id="command-result"></div>

## Komut Sonucu

`handle()` metodu, ayrıştırılmış seçenekleri, bayrakları ve argümanları okumak için tipli erişimcilere sahip bir `CommandResult` nesnesi alır.

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

| Metot / Özellik | Döndürür | Açıklama |
|-------------------|---------|-------------|
| `getString(String name, {String? defaultValue})` | `String?` | String değer al |
| `getBool(String name, {bool? defaultValue})` | `bool?` | Boolean değer al |
| `getInt(String name, {int? defaultValue})` | `int?` | Tamsayı değer al |
| `get<T>(String name)` | `T?` | Tipli değer al |
| `hasForceFlag` | `bool` | `--force` geçilip geçilmediği |
| `hasHelpFlag` | `bool` | `--help` geçilip geçilmediği |
| `arguments` | `List<String>` | Tüm komut satırı argümanları |
| `rest` | `List<String>` | Ayrıştırılmamış kalan argümanlar |

<div id="interactive-input"></div>

## Etkileşimli Girdi

`NyCustomCommand`, terminalde kullanıcı girdisi toplamak için metotlar sağlar.

<div id="text-input"></div>

### Metin Girdisi

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `question` | `String` | Görüntülenecek soru |
| `defaultValue` | `String` | Kullanıcı Enter'a basarsa varsayılan değer (varsayılan: `''`) |

<div id="confirmation"></div>

### Onay

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `question` | `String` | Evet/hayır sorusu |
| `defaultValue` | `bool` | Varsayılan yanıt (varsayılan: `false`) |

<div id="single-selection"></div>

### Tekli Seçim

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `question` | `String` | İstem metni |
| `options` | `List<String>` | Mevcut seçenekler |
| `defaultOption` | `String?` | Önceden seçilmiş seçenek |

<div id="multiple-selection"></div>

### Çoklu Seçim

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

Kullanıcı virgülle ayrılmış numaralar veya `"all"` girer.

<div id="secret-input"></div>

### Gizli Girdi

```dart
final apiKey = promptSecret('Enter your API key:');
```

Girdi terminal ekranında gizlenir. Echo modu desteklenmiyorsa görünür girdiye geri döner.

<div id="output-formatting"></div>

## Çıktı Biçimlendirme

Konsola biçimlendirilmiş çıktı yazdırmak için metotlar:

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

| Metot | Açıklama |
|--------|-------------|
| `info(String message)` | Mavi metin yazdır |
| `error(String message)` | Kırmızı metin yazdır |
| `success(String message)` | Yeşil metin yazdır |
| `warning(String message)` | Sarı metin yazdır |
| `line(String message)` | Düz metin yazdır (renksiz) |
| `newLine([int count = 1])` | Boş satır yazdır |
| `comment(String message)` | Gri/soluk metin yazdır |
| `alert(String message)` | Kenarlıklı uyarı kutusu yazdır |
| `abort([String? message, int exitCode = 1])` | Komutu bir hata ile sonlandır |

<div id="spinner-and-progress"></div>

## Döndürücü ve İlerleme

Döndürücüler ve ilerleme çubukları, uzun süren işlemler sırasında görsel geri bildirim sağlar.

<div id="using-withspinner"></div>

### withSpinner Kullanımı

Eşzamansız bir görevi otomatik döndürücü ile sarın:

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

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `task` | `Future<T> Function()` | Yürütülecek eşzamansız fonksiyon |
| `message` | `String` | Döndürücü çalışırken gösterilen metin |
| `successMessage` | `String?` | Başarı durumunda gösterilir |
| `errorMessage` | `String?` | Hata durumunda gösterilir |

<div id="manual-spinner-control"></div>

### Manuel Döndürücü Kontrolü

Çok adımlı iş akışları için bir döndürücü oluşturun ve manuel olarak kontrol edin:

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

**ConsoleSpinner metotları:**

| Metot | Açıklama |
|--------|-------------|
| `start([String? message])` | Döndürücü animasyonunu başlat |
| `update(String message)` | Görüntülenen mesajı değiştir |
| `stop({String? completionMessage, bool success = true})` | Döndürücüyü durdur |

<div id="progress-bar"></div>

### İlerleme Çubuğu

Manuel olarak bir ilerleme çubuğu oluşturun ve yönetin:

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**ConsoleProgressBar metotları:**

| Metot / Özellik | Açıklama |
|-------------------|-------------|
| `start()` | İlerleme çubuğunu başlat |
| `tick([int amount = 1])` | İlerlemeyi artır |
| `update(int value)` | İlerlemeyi belirli bir değere ayarla |
| `updateMessage(String newMessage)` | Görüntülenen mesajı değiştir |
| `complete([String? completionMessage])` | İsteğe bağlı mesajla tamamla |
| `stop()` | Tamamlamadan durdur |
| `current` | Mevcut ilerleme değeri (getter) |
| `percentage` | 0-100 arası yüzde olarak ilerleme (getter) |

<div id="processing-items-with-progress"></div>

### İlerleme ile Öğe İşleme

Otomatik ilerleme takibi ile bir öğe listesini işleyin:

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

## API Yardımcısı

`api` yardımcısı, HTTP istekleri yapmak için Dio etrafında basitleştirilmiş bir sarmalayıcı sağlar:

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

Daha iyi bir kullanıcı deneyimi için `withSpinner` ile birleştirin:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

`ApiService`; `get`, `post`, `put`, `delete` ve `patch` metotlarını destekler. Her biri isteğe bağlı `queryParameters`, `data`, `options` ve `cancelToken` parametreleri kabul eder.

<div id="file-system-helpers"></div>

## Dosya Sistemi Yardımcıları

`dart:io` içe aktarmanıza gerek kalmadan kullanabileceğiniz yerleşik dosya sistemi yardımcıları:

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

| Metot | Açıklama |
|--------|-------------|
| `fileExists(String path)` | Dosya varsa `true` döndürür |
| `directoryExists(String path)` | Dizin varsa `true` döndürür |
| `readFile(String path)` | Dosyayı string olarak oku (eşzamansız) |
| `readFileSync(String path)` | Dosyayı string olarak oku (eşzamanlı) |
| `writeFile(String path, String content)` | Dosyaya içerik yaz (eşzamansız) |
| `writeFileSync(String path, String content)` | Dosyaya içerik yaz (eşzamanlı) |
| `appendFile(String path, String content)` | Dosyaya içerik ekle |
| `ensureDirectory(String path)` | Yoksa dizin oluştur |
| `deleteFile(String path)` | Dosyayı sil |
| `copyFile(String source, String destination)` | Dosyayı kopyala |

<div id="json-and-yaml-helpers"></div>

## JSON ve YAML Yardımcıları

Yerleşik yardımcılarla JSON ve YAML dosyalarını okuyun ve yazın:

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

| Metot | Açıklama |
|--------|-------------|
| `readJson(String path)` | JSON dosyasını `Map<String, dynamic>` olarak oku |
| `readJsonArray(String path)` | JSON dosyasını `List<dynamic>` olarak oku |
| `writeJson(String path, dynamic data, {bool pretty = true})` | Veriyi JSON olarak yaz |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | JSON dizi dosyasına ekle |
| `readYaml(String path)` | YAML dosyasını `Map<String, dynamic>` olarak oku |

<div id="dart-file-manipulation"></div>

## Dart Dosya Manipülasyonu

Dart kaynak dosyalarını programatik olarak düzenlemek için yardımcılar -- iskele araçları oluştururken kullanışlıdır:

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

| Metot | Açıklama |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Dart dosyasına import ekle (mevcutsa atla) |
| `insertBeforeClosingBrace(String filePath, String code)` | Dosyadaki son `}` işaretinden önce kod ekle |
| `fileContains(String filePath, String identifier)` | Dosyanın bir string içerip içermediğini kontrol et |
| `fileContainsPattern(String filePath, Pattern pattern)` | Dosyanın bir kalıpla eşleşip eşleşmediğini kontrol et |

<div id="directory-helpers"></div>

## Dizin Yardımcıları

Dizinlerle çalışmak ve dosya bulmak için yardımcılar:

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

| Metot | Açıklama |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | Dizin içeriğini listele |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | Kriterlere uyan dosyaları bul |
| `deleteDirectory(String path)` | Dizini özyinelemeli olarak sil |
| `copyDirectory(String source, String destination)` | Dizini özyinelemeli olarak kopyala |

<div id="case-conversion-helpers"></div>

## Büyük/Küçük Harf Dönüşüm Yardımcıları

`recase` paketini içe aktarmadan stringleri adlandırma kuralları arasında dönüştürün:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| Metot | Çıktı Formatı | Örnek |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## Proje Yol Yardımcıları

Standart {{ config('app.name') }} proje dizinleri için getter'lar, proje köküne göre yollar döndürür:

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

| Özellik | Yol |
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
| `projectPath(String relativePath)` | Proje içinde göreli bir yolu çözümler |

<div id="platform-helpers"></div>

## Platform Yardımcıları

Platformu kontrol edin ve ortam değişkenlerine erişin:

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

| Özellik / Metot | Açıklama |
|-------------------|-------------|
| `isWindows` | Windows üzerinde çalışıyorsa `true` |
| `isMacOS` | macOS üzerinde çalışıyorsa `true` |
| `isLinux` | Linux üzerinde çalışıyorsa `true` |
| `workingDirectory` | Mevcut çalışma dizini yolu |
| `env(String key, [String defaultValue = ''])` | Sistem ortam değişkenini oku |

<div id="dart-and-flutter-commands"></div>

## Dart ve Flutter Komutları

Yaygın Dart ve Flutter CLI komutlarını yardımcı metotlar olarak çalıştırın. Her biri işlem çıkış kodunu döndürür:

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

| Metot | Açıklama |
|--------|-------------|
| `dartFormat(String path)` | Bir dosya veya dizinde `dart format` çalıştır |
| `dartAnalyze([String? path])` | `dart analyze` çalıştır |
| `flutterPubGet()` | `flutter pub get` çalıştır |
| `flutterClean()` | `flutter clean` çalıştır |
| `flutterBuild(String target, {List<String> args})` | `flutter build <target>` çalıştır |
| `flutterTest([String? path])` | `flutter test` çalıştır |

<div id="validation-helpers"></div>

## Doğrulama Yardımcıları

Kod üretimi için kullanıcı girdisini doğrulamak ve temizlemek amacıyla yardımcılar:

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

| Metot | Açıklama |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Dart tanımlayıcı adını doğrula |
| `requireArgument(CommandResult result, {String? message})` | Boş olmayan ilk argümanı zorunlu kıl veya iptal et |
| `cleanClassName(String name, {List<String> removeSuffixes})` | Sınıf adını temizle ve PascalCase yap |
| `cleanFileName(String name, {String extension = '.dart'})` | Dosya adını temizle ve snake_case yap |

<div id="file-scaffolding"></div>

## Dosya İskelesi

İskele sistemi kullanarak bir veya birden fazla dosyayı içerikle oluşturun.

### Tek Dosya

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

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `path` | `String` | Oluşturulacak dosya yolu |
| `content` | `String` | Dosya içeriği |
| `force` | `bool` | Varsa üzerine yaz (varsayılan: `false`) |
| `successMessage` | `String?` | Başarı durumunda gösterilen mesaj |

### Birden Fazla Dosya

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

`ScaffoldFile` sınıfı:

| Özellik | Tür | Açıklama |
|----------|------|-------------|
| `path` | `String` | Oluşturulacak dosya yolu |
| `content` | `String` | Dosya içeriği |
| `successMessage` | `String?` | Başarı durumunda gösterilen mesaj |

<div id="task-runner"></div>

## Görev Çalıştırıcı

Otomatik durum çıktısı ile bir dizi adlandırılmış görevi çalıştırın.

### Temel Görev Çalıştırıcı

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

### Döndürücülü Görev Çalıştırıcı

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

`CommandTask` sınıfı:

| Özellik | Tür | Varsayılan | Açıklama |
|----------|------|---------|-------------|
| `name` | `String` | zorunlu | Çıktıda gösterilen görev adı |
| `action` | `Future<void> Function()` | zorunlu | Yürütülecek eşzamansız fonksiyon |
| `stopOnError` | `bool` | `true` | Hata durumunda kalan görevleri durdur |

<div id="table-output"></div>

## Tablo Çıktısı

Konsolda biçimlendirilmiş ASCII tabloları görüntüleyin:

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

Çıktı:

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

## Örnekler

<div id="current-time-command"></div>

### Güncel Saat Komutu

Güncel saati gösteren basit bir komut:

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

### Font İndirme Komutu

Google Fonts'u projeye indirip yükleyen bir komut:

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

### Dağıtım Hattı Komutu

Görev çalıştırıcısını kullanarak tam bir dağıtım hattı çalıştıran komut:

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
