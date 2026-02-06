# कमांड्स

---

<a name="section-1"></a>
- [परिचय](#introduction)
- [कमांड्स बनाना](#creating-commands)
- [कमांड स्ट्रक्चर](#command-structure)
- [कमांड्स चलाना](#running-commands)
- [कमांड रजिस्ट्री](#command-registry)
- [ऑप्शन्स और फ्लैग्स](#options-and-flags)
  - [ऑप्शन्स जोड़ना](#adding-options)
  - [फ्लैग्स जोड़ना](#adding-flags)
- [कमांड रिज़ल्ट](#command-result)
- [इंटरैक्टिव इनपुट](#interactive-input)
  - [टेक्स्ट इनपुट](#text-input)
  - [कन्फर्मेशन](#confirmation)
  - [सिंगल सिलेक्शन](#single-selection)
  - [मल्टीपल सिलेक्शन](#multiple-selection)
  - [सीक्रेट इनपुट](#secret-input)
- [आउटपुट फॉर्मेटिंग](#output-formatting)
- [स्पिनर और प्रोग्रेस](#spinner-and-progress)
  - [withSpinner का उपयोग करना](#using-withspinner)
  - [मैन्युअल स्पिनर कंट्रोल](#manual-spinner-control)
  - [प्रोग्रेस बार](#progress-bar)
  - [प्रोग्रेस के साथ आइटम्स प्रोसेस करना](#processing-items-with-progress)
- [API हेल्पर](#api-helper)
- [फाइल सिस्टम हेल्पर्स](#file-system-helpers)
- [JSON और YAML हेल्पर्स](#json-and-yaml-helpers)
- [Dart फाइल मैनिपुलेशन](#dart-file-manipulation)
- [डायरेक्टरी हेल्पर्स](#directory-helpers)
- [केस कन्वर्शन हेल्पर्स](#case-conversion-helpers)
- [प्रोजेक्ट पाथ हेल्पर्स](#project-path-helpers)
- [प्लेटफॉर्म हेल्पर्स](#platform-helpers)
- [Dart और Flutter कमांड्स](#dart-and-flutter-commands)
- [वैलिडेशन हेल्पर्स](#validation-helpers)
- [फाइल स्कैफोल्डिंग](#file-scaffolding)
- [टास्क रनर](#task-runner)
- [टेबल आउटपुट](#table-output)
- [उदाहरण](#examples)
  - [करंट टाइम कमांड](#current-time-command)
  - [डाउनलोड फॉन्ट्स कमांड](#download-fonts-command)
  - [डिप्लॉयमेंट पाइपलाइन कमांड](#deployment-pipeline-command)

<div id="introduction"></div>

## परिचय

कमांड्स आपको {{ config('app.name') }} के CLI को कस्टम प्रोजेक्ट-विशिष्ट टूलिंग के साथ विस्तारित करने देते हैं। `NyCustomCommand` को सबक्लास करके, आप दोहराए जाने वाले कार्यों को ऑटोमेट कर सकते हैं, डिप्लॉयमेंट वर्कफ़्लो बना सकते हैं, कोड जेनरेट कर सकते हैं, या अपनी ज़रूरत की कोई भी कार्यक्षमता सीधे अपने टर्मिनल में जोड़ सकते हैं।

हर कस्टम कमांड के पास फाइल I/O, JSON/YAML, इंटरैक्टिव प्रॉम्प्ट्स, स्पिनर्स, प्रोग्रेस बार्स, API रिक्वेस्ट्स और अन्य बिल्ट-इन हेल्पर्स का समृद्ध सेट उपलब्ध होता है -- बिना अतिरिक्त पैकेज इम्पोर्ट किए।

> **नोट:** कस्टम कमांड्स Flutter रनटाइम के बाहर चलते हैं। आप अपने कमांड्स में `nylo_framework.dart` इम्पोर्ट नहीं कर सकते। इसके बजाय `ny_cli.dart` का उपयोग करें।

<div id="creating-commands"></div>

## कमांड्स बनाना

Metro या Dart CLI का उपयोग करके नया कमांड बनाएँ:

```bash
metro make:command current_time
```

आप `--category` ऑप्शन का उपयोग करके अपने कमांड के लिए एक कैटेगरी निर्दिष्ट कर सकते हैं:

```bash
metro make:command current_time --category="project"
```

यह `lib/app/commands/current_time.dart` पर एक नई फाइल बनाता है और इसे कमांड रजिस्ट्री में रजिस्टर करता है।

<div id="command-structure"></div>

## कमांड स्ट्रक्चर

हर कमांड `NyCustomCommand` को एक्सटेंड करता है और दो मुख्य मेथड्स इम्प्लीमेंट करता है:

- **`builder()`** -- ऑप्शन्स और फ्लैग्स कॉन्फ़िगर करें
- **`handle()`** -- कमांड लॉजिक एक्ज़ीक्यूट करें

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

## कमांड्स चलाना

Metro या Dart का उपयोग करके अपना कमांड चलाएँ:

```bash
metro app:current_time
```

कमांड का नाम `category:name` पैटर्न का अनुसरण करता है। जब आप बिना आर्ग्युमेंट्स के `metro` चलाते हैं, तो कस्टम कमांड्स **Custom Commands** सेक्शन के अंतर्गत दिखाई देते हैं:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

किसी कमांड के लिए हेल्प प्रदर्शित करने के लिए:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## कमांड रजिस्ट्री

सभी कस्टम कमांड्स `lib/app/commands/commands.json` में रजिस्टर होते हैं। जब आप `make:command` का उपयोग करते हैं तो यह फाइल स्वचालित रूप से अपडेट हो जाती है:

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

प्रत्येक एंट्री में होता है:

| फील्ड | विवरण |
|-------|-------------|
| `name` | कमांड का नाम (कैटेगरी प्रीफिक्स के बाद उपयोग किया जाता है) |
| `category` | कमांड की कैटेगरी (जैसे `app`, `project`) |
| `script` | `lib/app/commands/` में Dart फाइल |

<div id="options-and-flags"></div>

## ऑप्शन्स और फ्लैग्स

`CommandBuilder` का उपयोग करके `builder()` मेथड में अपने कमांड के ऑप्शन्स और फ्लैग्स कॉन्फ़िगर करें।

<div id="adding-options"></div>

### ऑप्शन्स जोड़ना

ऑप्शन्स यूज़र से एक वैल्यू स्वीकार करते हैं:

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

उपयोग:

```bash
metro project:deploy --environment=production
# or using abbreviation
metro project:deploy -e production
```

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `name` | `String` | ऑप्शन का नाम |
| `abbr` | `String?` | सिंगल-कैरेक्टर संक्षिप्त नाम |
| `help` | `String?` | `--help` के साथ दिखाया जाने वाला हेल्प टेक्स्ट |
| `allowed` | `List<String>?` | अनुमत वैल्यूज़ तक सीमित करें |
| `defaultValue` | `String?` | न दिए जाने पर डिफ़ॉल्ट वैल्यू |

<div id="adding-flags"></div>

### फ्लैग्स जोड़ना

फ्लैग्स बूलियन टॉगल हैं:

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

उपयोग:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `name` | `String` | फ्लैग का नाम |
| `abbr` | `String?` | सिंगल-कैरेक्टर संक्षिप्त नाम |
| `help` | `String?` | `--help` के साथ दिखाया जाने वाला हेल्प टेक्स्ट |
| `defaultValue` | `bool` | डिफ़ॉल्ट वैल्यू (डिफ़ॉल्ट: `false`) |

<div id="command-result"></div>

## कमांड रिज़ल्ट

`handle()` मेथड एक `CommandResult` ऑब्जेक्ट प्राप्त करता है जिसमें पार्स किए गए ऑप्शन्स, फ्लैग्स और आर्ग्युमेंट्स को पढ़ने के लिए टाइप्ड एक्सेसर्स होते हैं।

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

| मेथड / प्रॉपर्टी | रिटर्न | विवरण |
|-------------------|---------|-------------|
| `getString(String name, {String? defaultValue})` | `String?` | स्ट्रिंग वैल्यू प्राप्त करें |
| `getBool(String name, {bool? defaultValue})` | `bool?` | बूलियन वैल्यू प्राप्त करें |
| `getInt(String name, {int? defaultValue})` | `int?` | इंटीजर वैल्यू प्राप्त करें |
| `get<T>(String name)` | `T?` | टाइप्ड वैल्यू प्राप्त करें |
| `hasForceFlag` | `bool` | क्या `--force` पास किया गया था |
| `hasHelpFlag` | `bool` | क्या `--help` पास किया गया था |
| `arguments` | `List<String>` | सभी कमांड-लाइन आर्ग्युमेंट्स |
| `rest` | `List<String>` | अनपार्स्ड शेष आर्ग्युमेंट्स |

<div id="interactive-input"></div>

## इंटरैक्टिव इनपुट

`NyCustomCommand` टर्मिनल में यूज़र इनपुट एकत्र करने के लिए मेथड्स प्रदान करता है।

<div id="text-input"></div>

### टेक्स्ट इनपुट

```dart
// Ask a question with optional default
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() is an alias for prompt()
final description = ask('Enter a description:');
```

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `question` | `String` | प्रदर्शित करने के लिए प्रश्न |
| `defaultValue` | `String` | यूज़र द्वारा Enter दबाने पर डिफ़ॉल्ट (डिफ़ॉल्ट: `''`) |

<div id="confirmation"></div>

### कन्फर्मेशन

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `question` | `String` | हाँ/नहीं प्रश्न |
| `defaultValue` | `bool` | डिफ़ॉल्ट उत्तर (डिफ़ॉल्ट: `false`) |

<div id="single-selection"></div>

### सिंगल सिलेक्शन

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `question` | `String` | प्रॉम्प्ट टेक्स्ट |
| `options` | `List<String>` | उपलब्ध विकल्प |
| `defaultOption` | `String?` | पहले से चुना हुआ विकल्प |

<div id="multiple-selection"></div>

### मल्टीपल सिलेक्शन

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

यूज़र कॉमा-सेपरेटेड नंबर्स या `"all"` दर्ज करता है।

<div id="secret-input"></div>

### सीक्रेट इनपुट

```dart
final apiKey = promptSecret('Enter your API key:');
```

इनपुट टर्मिनल डिस्प्ले से छिपा रहता है। यदि इको मोड सपोर्टेड नहीं है तो विज़िबल इनपुट पर फॉलबैक होता है।

<div id="output-formatting"></div>

## आउटपुट फॉर्मेटिंग

कंसोल पर स्टाइल्ड आउटपुट प्रिंट करने के मेथड्स:

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

| मेथड | विवरण |
|--------|-------------|
| `info(String message)` | नीला टेक्स्ट प्रिंट करें |
| `error(String message)` | लाल टेक्स्ट प्रिंट करें |
| `success(String message)` | हरा टेक्स्ट प्रिंट करें |
| `warning(String message)` | पीला टेक्स्ट प्रिंट करें |
| `line(String message)` | प्लेन टेक्स्ट प्रिंट करें (बिना रंग) |
| `newLine([int count = 1])` | ब्लैंक लाइन्स प्रिंट करें |
| `comment(String message)` | ग्रे/म्यूटेड टेक्स्ट प्रिंट करें |
| `alert(String message)` | बॉर्डर वाला अलर्ट बॉक्स प्रिंट करें |
| `abort([String? message, int exitCode = 1])` | एरर के साथ कमांड से बाहर निकलें |

<div id="spinner-and-progress"></div>

## स्पिनर और प्रोग्रेस

स्पिनर्स और प्रोग्रेस बार्स लंबे समय तक चलने वाले ऑपरेशन्स के दौरान विज़ुअल फीडबैक प्रदान करते हैं।

<div id="using-withspinner"></div>

### withSpinner का उपयोग करना

एक ऑटोमैटिक स्पिनर के साथ एसिंक टास्क को रैप करें:

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

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `task` | `Future<T> Function()` | एक्ज़ीक्यूट करने के लिए एसिंक फंक्शन |
| `message` | `String` | स्पिनर चलने के दौरान दिखाया जाने वाला टेक्स्ट |
| `successMessage` | `String?` | सफलता पर दिखाया जाता है |
| `errorMessage` | `String?` | विफलता पर दिखाया जाता है |

<div id="manual-spinner-control"></div>

### मैन्युअल स्पिनर कंट्रोल

मल्टी-स्टेप वर्कफ़्लो के लिए, स्पिनर बनाएँ और इसे मैन्युअली कंट्रोल करें:

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

**ConsoleSpinner मेथड्स:**

| मेथड | विवरण |
|--------|-------------|
| `start([String? message])` | स्पिनर एनिमेशन शुरू करें |
| `update(String message)` | प्रदर्शित मैसेज बदलें |
| `stop({String? completionMessage, bool success = true})` | स्पिनर रोकें |

<div id="progress-bar"></div>

### प्रोग्रेस बार

मैन्युअली प्रोग्रेस बार बनाएँ और प्रबंधित करें:

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**ConsoleProgressBar मेथड्स:**

| मेथड / प्रॉपर्टी | विवरण |
|-------------------|-------------|
| `start()` | प्रोग्रेस बार शुरू करें |
| `tick([int amount = 1])` | प्रोग्रेस बढ़ाएँ |
| `update(int value)` | प्रोग्रेस को एक विशिष्ट वैल्यू पर सेट करें |
| `updateMessage(String newMessage)` | प्रदर्शित मैसेज बदलें |
| `complete([String? completionMessage])` | वैकल्पिक मैसेज के साथ पूरा करें |
| `stop()` | बिना पूरा किए रोकें |
| `current` | वर्तमान प्रोग्रेस वैल्यू (गेटर) |
| `percentage` | प्रोग्रेस प्रतिशत 0-100 (गेटर) |

<div id="processing-items-with-progress"></div>

### प्रोग्रेस के साथ आइटम्स प्रोसेस करना

ऑटोमैटिक प्रोग्रेस ट्रैकिंग के साथ आइटम्स की सूची प्रोसेस करें:

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

## API हेल्पर

`api` हेल्पर HTTP रिक्वेस्ट्स करने के लिए Dio के ऊपर एक सरलीकृत रैपर प्रदान करता है:

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

बेहतर यूज़र अनुभव के लिए `withSpinner` के साथ मिलाएँ:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

`ApiService` `get`, `post`, `put`, `delete`, और `patch` मेथड्स सपोर्ट करता है, प्रत्येक वैकल्पिक `queryParameters`, `data`, `options`, और `cancelToken` स्वीकार करता है।

<div id="file-system-helpers"></div>

## फाइल सिस्टम हेल्पर्स

बिल्ट-इन फाइल सिस्टम हेल्पर्स ताकि आपको `dart:io` इम्पोर्ट करने की आवश्यकता न हो:

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

| मेथड | विवरण |
|--------|-------------|
| `fileExists(String path)` | यदि फाइल मौजूद है तो `true` रिटर्न करता है |
| `directoryExists(String path)` | यदि डायरेक्टरी मौजूद है तो `true` रिटर्न करता है |
| `readFile(String path)` | फाइल को स्ट्रिंग के रूप में पढ़ें (एसिंक) |
| `readFileSync(String path)` | फाइल को स्ट्रिंग के रूप में पढ़ें (सिंक) |
| `writeFile(String path, String content)` | फाइल में कंटेंट लिखें (एसिंक) |
| `writeFileSync(String path, String content)` | फाइल में कंटेंट लिखें (सिंक) |
| `appendFile(String path, String content)` | फाइल में कंटेंट जोड़ें |
| `ensureDirectory(String path)` | यदि डायरेक्टरी मौजूद नहीं है तो बनाएँ |
| `deleteFile(String path)` | फाइल डिलीट करें |
| `copyFile(String source, String destination)` | फाइल कॉपी करें |

<div id="json-and-yaml-helpers"></div>

## JSON और YAML हेल्पर्स

बिल्ट-इन हेल्पर्स के साथ JSON और YAML फाइलें पढ़ें और लिखें:

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

| मेथड | विवरण |
|--------|-------------|
| `readJson(String path)` | JSON फाइल को `Map<String, dynamic>` के रूप में पढ़ें |
| `readJsonArray(String path)` | JSON फाइल को `List<dynamic>` के रूप में पढ़ें |
| `writeJson(String path, dynamic data, {bool pretty = true})` | डेटा को JSON के रूप में लिखें |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | JSON एरे फाइल में जोड़ें |
| `readYaml(String path)` | YAML फाइल को `Map<String, dynamic>` के रूप में पढ़ें |

<div id="dart-file-manipulation"></div>

## Dart फाइल मैनिपुलेशन

Dart सोर्स फाइलों को प्रोग्रामेटिक रूप से एडिट करने के हेल्पर्स -- स्कैफोल्डिंग टूल्स बनाते समय उपयोगी:

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

| मेथड | विवरण |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Dart फाइल में इम्पोर्ट जोड़ें (यदि मौजूद है तो स्किप करता है) |
| `insertBeforeClosingBrace(String filePath, String code)` | फाइल में अंतिम `}` से पहले कोड इंसर्ट करें |
| `fileContains(String filePath, String identifier)` | जाँचें कि फाइल में कोई स्ट्रिंग है या नहीं |
| `fileContainsPattern(String filePath, Pattern pattern)` | जाँचें कि फाइल किसी पैटर्न से मैच करती है या नहीं |

<div id="directory-helpers"></div>

## डायरेक्टरी हेल्पर्स

डायरेक्टरीज़ और फाइलें खोजने के लिए हेल्पर्स:

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

| मेथड | विवरण |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | डायरेक्टरी की सामग्री सूचीबद्ध करें |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | मानदंड से मेल खाने वाली फाइलें खोजें |
| `deleteDirectory(String path)` | डायरेक्टरी को रिकर्सिवली डिलीट करें |
| `copyDirectory(String source, String destination)` | डायरेक्टरी को रिकर्सिवली कॉपी करें |

<div id="case-conversion-helpers"></div>

## केस कन्वर्शन हेल्पर्स

`recase` पैकेज इम्पोर्ट किए बिना स्ट्रिंग्स को नामकरण कन्वेंशन्स के बीच कन्वर्ट करें:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| मेथड | आउटपुट फॉर्मेट | उदाहरण |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## प्रोजेक्ट पाथ हेल्पर्स

मानक {{ config('app.name') }} प्रोजेक्ट डायरेक्टरीज़ के लिए गेटर्स, प्रोजेक्ट रूट के सापेक्ष पाथ रिटर्न करते हैं:

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

| प्रॉपर्टी | पाथ |
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
| `projectPath(String relativePath)` | प्रोजेक्ट के भीतर एक रिलेटिव पाथ रिज़ॉल्व करता है |

<div id="platform-helpers"></div>

## प्लेटफॉर्म हेल्पर्स

प्लेटफॉर्म की जाँच करें और एनवायरनमेंट वेरिएबल्स एक्सेस करें:

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

| प्रॉपर्टी / मेथड | विवरण |
|-------------------|-------------|
| `isWindows` | यदि Windows पर चल रहा है तो `true` |
| `isMacOS` | यदि macOS पर चल रहा है तो `true` |
| `isLinux` | यदि Linux पर चल रहा है तो `true` |
| `workingDirectory` | वर्तमान वर्किंग डायरेक्टरी पाथ |
| `env(String key, [String defaultValue = ''])` | सिस्टम एनवायरनमेंट वेरिएबल पढ़ें |

<div id="dart-and-flutter-commands"></div>

## Dart और Flutter कमांड्स

सामान्य Dart और Flutter CLI कमांड्स को हेल्पर मेथड्स के रूप में चलाएँ। प्रत्येक प्रोसेस एग्ज़िट कोड रिटर्न करता है:

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

| मेथड | विवरण |
|--------|-------------|
| `dartFormat(String path)` | किसी फाइल या डायरेक्टरी पर `dart format` चलाएँ |
| `dartAnalyze([String? path])` | `dart analyze` चलाएँ |
| `flutterPubGet()` | `flutter pub get` चलाएँ |
| `flutterClean()` | `flutter clean` चलाएँ |
| `flutterBuild(String target, {List<String> args})` | `flutter build <target>` चलाएँ |
| `flutterTest([String? path])` | `flutter test` चलाएँ |

<div id="validation-helpers"></div>

## वैलिडेशन हेल्पर्स

कोड जेनरेशन के लिए यूज़र इनपुट को वैलिडेट और क्लीन करने के हेल्पर्स:

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

| मेथड | विवरण |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Dart आइडेंटिफ़ायर नाम को वैलिडेट करें |
| `requireArgument(CommandResult result, {String? message})` | नॉन-एम्प्टी पहले आर्ग्युमेंट की आवश्यकता है अन्यथा एबॉर्ट करें |
| `cleanClassName(String name, {List<String> removeSuffixes})` | क्लास नाम क्लीन और PascalCase करें |
| `cleanFileName(String name, {String extension = '.dart'})` | फाइल नाम क्लीन और snake_case करें |

<div id="file-scaffolding"></div>

## फाइल स्कैफोल्डिंग

स्कैफोल्डिंग सिस्टम का उपयोग करके कंटेंट के साथ एक या अनेक फाइलें बनाएँ।

### सिंगल फाइल

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

| पैरामीटर | टाइप | विवरण |
|-----------|------|-------------|
| `path` | `String` | बनाने के लिए फाइल पाथ |
| `content` | `String` | फाइल कंटेंट |
| `force` | `bool` | यदि मौजूद है तो ओवरराइट करें (डिफ़ॉल्ट: `false`) |
| `successMessage` | `String?` | सफलता पर दिखाया जाने वाला मैसेज |

### मल्टीपल फाइलें

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

`ScaffoldFile` क्लास:

| प्रॉपर्टी | टाइप | विवरण |
|----------|------|-------------|
| `path` | `String` | बनाने के लिए फाइल पाथ |
| `content` | `String` | फाइल कंटेंट |
| `successMessage` | `String?` | सफलता पर दिखाया जाने वाला मैसेज |

<div id="task-runner"></div>

## टास्क रनर

ऑटोमैटिक स्टेटस आउटपुट के साथ नामित टास्क्स की श्रृंखला चलाएँ।

### बेसिक टास्क रनर

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

### स्पिनर के साथ टास्क रनर

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

`CommandTask` क्लास:

| प्रॉपर्टी | टाइप | डिफ़ॉल्ट | विवरण |
|----------|------|---------|-------------|
| `name` | `String` | आवश्यक | आउटपुट में दिखाया जाने वाला टास्क नाम |
| `action` | `Future<void> Function()` | आवश्यक | एक्ज़ीक्यूट करने के लिए एसिंक फंक्शन |
| `stopOnError` | `bool` | `true` | विफलता पर शेष टास्क्स रोकने हैं या नहीं |

<div id="table-output"></div>

## टेबल आउटपुट

कंसोल में फॉर्मेटेड ASCII टेबल प्रदर्शित करें:

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

आउटपुट:

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

## उदाहरण

<div id="current-time-command"></div>

### करंट टाइम कमांड

एक सरल कमांड जो वर्तमान समय प्रदर्शित करता है:

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

### डाउनलोड फॉन्ट्स कमांड

एक कमांड जो Google Fonts डाउनलोड करके प्रोजेक्ट में इंस्टॉल करता है:

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

### डिप्लॉयमेंट पाइपलाइन कमांड

एक कमांड जो टास्क रनर का उपयोग करके पूरी डिप्लॉयमेंट पाइपलाइन चलाता है:

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
