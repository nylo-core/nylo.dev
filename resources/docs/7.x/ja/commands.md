# コマンド

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [コマンドの作成](#creating-commands "コマンドの作成")
- [コマンドの構造](#command-structure "コマンドの構造")
- [コマンドの実行](#running-commands "コマンドの実行")
- [コマンドレジストリ](#command-registry "コマンドレジストリ")
- [オプションとフラグ](#options-and-flags "オプションとフラグ")
  - [オプションの追加](#adding-options "オプションの追加")
  - [フラグの追加](#adding-flags "フラグの追加")
- [コマンド結果](#command-result "コマンド結果")
- [インタラクティブ入力](#interactive-input "インタラクティブ入力")
  - [テキスト入力](#text-input "テキスト入力")
  - [確認](#confirmation "確認")
  - [単一選択](#single-selection "単一選択")
  - [複数選択](#multiple-selection "複数選択")
  - [シークレット入力](#secret-input "シークレット入力")
- [出力フォーマット](#output-formatting "出力フォーマット")
- [スピナーとプログレス](#spinner-and-progress "スピナーとプログレス")
  - [withSpinner の使用](#using-withspinner "withSpinner の使用")
  - [手動スピナー制御](#manual-spinner-control "手動スピナー制御")
  - [プログレスバー](#progress-bar "プログレスバー")
  - [プログレス付きアイテム処理](#processing-items-with-progress "プログレス付きアイテム処理")
- [API ヘルパー](#api-helper "API ヘルパー")
- [ファイルシステムヘルパー](#file-system-helpers "ファイルシステムヘルパー")
- [JSON・YAML ヘルパー](#json-and-yaml-helpers "JSON・YAML ヘルパー")
- [Dart ファイル操作](#dart-file-manipulation "Dart ファイル操作")
- [ディレクトリヘルパー](#directory-helpers "ディレクトリヘルパー")
- [ケース変換ヘルパー](#case-conversion-helpers "ケース変換ヘルパー")
- [プロジェクトパスヘルパー](#project-path-helpers "プロジェクトパスヘルパー")
- [プラットフォームヘルパー](#platform-helpers "プラットフォームヘルパー")
- [Dart・Flutter コマンド](#dart-and-flutter-commands "Dart・Flutter コマンド")
- [バリデーションヘルパー](#validation-helpers "バリデーションヘルパー")
- [ファイルスキャフォールディング](#file-scaffolding "ファイルスキャフォールディング")
- [タスクランナー](#task-runner "タスクランナー")
- [テーブル出力](#table-output "テーブル出力")
- [使用例](#examples "使用例")
  - [現在時刻コマンド](#current-time-command "現在時刻コマンド")
  - [フォントダウンロードコマンド](#download-fonts-command "フォントダウンロードコマンド")
  - [デプロイパイプラインコマンド](#deployment-pipeline-command "デプロイパイプラインコマンド")

<div id="introduction"></div>

## はじめに

コマンドを使用すると、{{ config('app.name') }} の CLI をプロジェクト固有のカスタムツールで拡張できます。`NyCustomCommand` をサブクラス化することで、繰り返しのタスクを自動化したり、デプロイワークフローを構築したり、コードを生成したり、ターミナルで直接必要な任意の機能を追加したりできます。

すべてのカスタムコマンドには、ファイル I/O、JSON/YAML、インタラクティブプロンプト、スピナー、プログレスバー、API リクエストなど、豊富な組み込みヘルパーセットへのアクセスがあります。追加のパッケージをインポートする必要はありません。

> **注意:** カスタムコマンドは Flutter ランタイムの外部で実行されます。コマンド内で `nylo_framework.dart` をインポートすることはできません。代わりに `ny_cli.dart` を使用してください。

<div id="creating-commands"></div>

## コマンドの作成

Metro または Dart CLI を使用して新しいコマンドを作成します:

```bash
metro make:command current_time
```

`--category` オプションを使用してコマンドのカテゴリを指定できます:

```bash
metro make:command current_time --category="project"
```

これにより `lib/app/commands/current_time.dart` に新しいファイルが作成され、コマンドレジストリに登録されます。

<div id="command-structure"></div>

## コマンドの構造

すべてのコマンドは `NyCustomCommand` を継承し、2 つの主要なメソッドを実装します:

- **`builder()`** -- オプションとフラグを設定
- **`handle()`** -- コマンドロジックを実行

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

## コマンドの実行

Metro を使用してコマンドを実行します:

```bash
metro app:current_time
```

コマンド名は `category:name` のパターンに従います。引数なしで `metro` を実行すると、カスタムコマンドは **Custom Commands** セクションに表示されます:

```
[Custom Commands]
  app:current_time
  project:install_firebase
  project:deploy
```

コマンドのヘルプを表示するには:

```bash
metro app:current_time --help
```

<div id="command-registry"></div>

## コマンドレジストリ

すべてのカスタムコマンドは `lib/app/commands/commands.json` に登録されます。このファイルは `make:command` を使用すると自動的に更新されます:

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

各エントリには以下の項目があります:

| フィールド | 説明 |
|-------|-------------|
| `name` | コマンド名（カテゴリプレフィックスの後に使用） |
| `category` | コマンドカテゴリ（例: `app`、`project`） |
| `script` | `lib/app/commands/` 内の Dart ファイル |

<div id="options-and-flags"></div>

## オプションとフラグ

`builder()` メソッドで `CommandBuilder` を使用してコマンドのオプションとフラグを設定します。

<div id="adding-options"></div>

### オプションの追加

オプションはユーザーからの値を受け取ります:

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

使用方法:

```bash
metro project:deploy --environment=production
# または省略形を使用
metro project:deploy -e production
```

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `name` | `String` | オプション名 |
| `abbr` | `String?` | 1 文字の省略形 |
| `help` | `String?` | `--help` で表示されるヘルプテキスト |
| `allowed` | `List<String>?` | 許可される値に制限 |
| `defaultValue` | `String?` | 未指定時のデフォルト値 |

<div id="adding-flags"></div>

### フラグの追加

フラグはブール値のトグルです:

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

使用方法:

```bash
metro project:deploy --verbose
metro project:deploy -v
```

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `name` | `String` | フラグ名 |
| `abbr` | `String?` | 1 文字の省略形 |
| `help` | `String?` | `--help` で表示されるヘルプテキスト |
| `defaultValue` | `bool` | デフォルト値（デフォルト: `false`） |

<div id="command-result"></div>

## コマンド結果

`handle()` メソッドは、パースされたオプション、フラグ、引数を読み取るための型付きアクセサを持つ `CommandResult` オブジェクトを受け取ります。

```dart
@override
Future<void> handle(CommandResult result) async {
  // 文字列オプションを取得
  final name = result.getString('name');

  // ブールフラグを取得
  final verbose = result.getBool('verbose');

  // 整数オプションを取得
  final count = result.getInt('count');

  // ジェネリック型アクセス
  final value = result.get<String>('key');

  // 組み込みフラグチェック
  if (result.hasForceFlag) { /* --force が渡された */ }
  if (result.hasHelpFlag) { /* --help が渡された */ }

  // 生の引数
  List<String> allArgs = result.arguments;
  List<String> unparsed = result.rest;
}
```

| メソッド / プロパティ | 戻り値 | 説明 |
|-------------------|---------|-------------|
| `getString(String name, {String? defaultValue})` | `String?` | 文字列値を取得 |
| `getBool(String name, {bool? defaultValue})` | `bool?` | ブール値を取得 |
| `getInt(String name, {int? defaultValue})` | `int?` | 整数値を取得 |
| `get<T>(String name)` | `T?` | 型付き値を取得 |
| `hasForceFlag` | `bool` | `--force` が渡されたかどうか |
| `hasHelpFlag` | `bool` | `--help` が渡されたかどうか |
| `arguments` | `List<String>` | すべてのコマンドライン引数 |
| `rest` | `List<String>` | パースされなかった残りの引数 |

<div id="interactive-input"></div>

## インタラクティブ入力

`NyCustomCommand` はターミナルでユーザー入力を収集するためのメソッドを提供します。

<div id="text-input"></div>

### テキスト入力

```dart
// デフォルト値付きの質問
final name = prompt('What is your project name?', defaultValue: 'my_app');

// ask() は prompt() のエイリアス
final description = ask('Enter a description:');
```

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `question` | `String` | 表示する質問 |
| `defaultValue` | `String` | ユーザーが Enter を押した場合のデフォルト値（デフォルト: `''`） |

<div id="confirmation"></div>

### 確認

```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  await runProcess('flutter pub get');
} else {
  info('Operation canceled');
}
```

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `question` | `String` | はい/いいえの質問 |
| `defaultValue` | `bool` | デフォルトの回答（デフォルト: `false`） |

<div id="single-selection"></div>

### 単一選択

```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development',
);
```

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `question` | `String` | プロンプトテキスト |
| `options` | `List<String>` | 選択肢 |
| `defaultOption` | `String?` | 事前選択されたオプション |

<div id="multiple-selection"></div>

### 複数選択

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

ユーザーはカンマ区切りの番号または `"all"` を入力します。

<div id="secret-input"></div>

### シークレット入力

```dart
final apiKey = promptSecret('Enter your API key:');
```

入力はターミナル画面に表示されません。エコーモードがサポートされていない場合は、表示入力にフォールバックします。

<div id="output-formatting"></div>

## 出力フォーマット

コンソールにスタイル付き出力を表示するためのメソッドです:

```dart
@override
Future<void> handle(CommandResult result) async {
  info('Processing files...');       // 青色テキスト
  error('Operation failed');         // 赤色テキスト
  success('Deployment complete');    // 緑色テキスト
  warning('Outdated package');       // 黄色テキスト
  line('Plain text output');         // 色なし
  comment('Background note');        // グレーテキスト
  alert('Important notice');         // 枠付きアラートボックス
  newLine();                         // 空行 1 行
  newLine(3);                        // 空行 3 行

  // エラーでコマンドを終了
  abort('Fatal error occurred');     // 赤色で表示し、終了コード 1 で終了
}
```

| メソッド | 説明 |
|--------|-------------|
| `info(String message)` | 青色テキストを表示 |
| `error(String message)` | 赤色テキストを表示 |
| `success(String message)` | 緑色テキストを表示 |
| `warning(String message)` | 黄色テキストを表示 |
| `line(String message)` | プレーンテキストを表示（色なし） |
| `newLine([int count = 1])` | 空行を表示 |
| `comment(String message)` | グレー/ミュートテキストを表示 |
| `alert(String message)` | 枠付きアラートボックスを表示 |
| `abort([String? message, int exitCode = 1])` | エラーでコマンドを終了 |

<div id="spinner-and-progress"></div>

## スピナーとプログレス

スピナーとプログレスバーは、長時間実行される操作中に視覚的なフィードバックを提供します。

<div id="using-withspinner"></div>

### withSpinner の使用

非同期タスクを自動スピナーでラップします:

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

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `task` | `Future<T> Function()` | 実行する非同期関数 |
| `message` | `String` | スピナー実行中に表示されるテキスト |
| `successMessage` | `String?` | 成功時に表示 |
| `errorMessage` | `String?` | 失敗時に表示 |

<div id="manual-spinner-control"></div>

### 手動スピナー制御

マルチステップワークフローの場合、スピナーを作成して手動で制御します:

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

**ConsoleSpinner メソッド:**

| メソッド | 説明 |
|--------|-------------|
| `start([String? message])` | スピナーアニメーションを開始 |
| `update(String message)` | 表示メッセージを変更 |
| `stop({String? completionMessage, bool success = true})` | スピナーを停止 |

<div id="progress-bar"></div>

### プログレスバー

プログレスバーを作成して手動で管理します:

```dart
final progress = progressBar(100, message: 'Processing files');
progress.start();

for (int i = 0; i < 100; i++) {
  await Future.delayed(Duration(milliseconds: 50));
  progress.tick();
}

progress.complete('All files processed');
```

**ConsoleProgressBar メソッド:**

| メソッド / プロパティ | 説明 |
|-------------------|-------------|
| `start()` | プログレスバーを開始 |
| `tick([int amount = 1])` | プログレスを進める |
| `update(int value)` | プログレスを特定の値に設定 |
| `updateMessage(String newMessage)` | 表示メッセージを変更 |
| `complete([String? completionMessage])` | オプションのメッセージ付きで完了 |
| `stop()` | 完了せずに停止 |
| `current` | 現在のプログレス値（getter） |
| `percentage` | プログレスの割合 0-100（getter） |

<div id="processing-items-with-progress"></div>

### プログレス付きアイテム処理

アイテムリストを自動プログレス追跡付きで処理します:

```dart
// 非同期処理
final results = await withProgress<File, String>(
  items: findFiles('lib/', extension: '.dart'),
  process: (file, index) async {
    return file.path;
  },
  message: 'Analyzing Dart files',
  completionMessage: 'Analysis complete',
);

// 同期処理
final upperItems = withProgressSync<String, String>(
  items: ['a', 'b', 'c', 'd', 'e'],
  process: (item, index) => item.toUpperCase(),
  message: 'Converting items',
);
```

<div id="api-helper"></div>

## API ヘルパー

`api` ヘルパーは、HTTP リクエストを行うための Dio の簡略化されたラッパーを提供します:

```dart
// GET リクエスト
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);

// POST リクエスト
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99},
  )
);

// PUT リクエスト
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99},
  )
);

// DELETE リクエスト
final deleteResult = await api((request) =>
  request.delete('https://api.example.com/items/42')
);

// PATCH リクエスト
final patchResult = await api((request) =>
  request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99},
  )
);

// クエリパラメータ付き
final searchResults = await api((request) =>
  request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10},
  )
);
```

`withSpinner` と組み合わせてより良いユーザー体験を実現します:

```dart
final data = await withSpinner(
  task: () => api((request) =>
    request.get('https://api.example.com/config')
  ),
  message: 'Loading configuration',
);
```

`ApiService` は `get`、`post`、`put`、`delete`、`patch` メソッドをサポートしており、それぞれオプションの `queryParameters`、`data`、`options`、`cancelToken` を受け取ります。

<div id="file-system-helpers"></div>

## ファイルシステムヘルパー

`dart:io` をインポートする必要がない組み込みファイルシステムヘルパーです:

```dart
// 存在チェック
if (fileExists('lib/config/app.dart')) { /* ... */ }
if (directoryExists('lib/app/models')) { /* ... */ }

// ファイル読み込み
String content = await readFile('pubspec.yaml');
String contentSync = readFileSync('pubspec.yaml');

// ファイル書き込み
await writeFile('lib/generated/output.dart', 'class Output {}');
writeFileSync('lib/generated/output.dart', 'class Output {}');

// ファイルに追記
await appendFile('log.txt', 'New log entry\n');

// ディレクトリの存在を保証
await ensureDirectory('lib/generated');

// ファイルの削除とコピー
await deleteFile('lib/generated/output.dart');
await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
```

| メソッド | 説明 |
|--------|-------------|
| `fileExists(String path)` | ファイルが存在する場合 `true` を返す |
| `directoryExists(String path)` | ディレクトリが存在する場合 `true` を返す |
| `readFile(String path)` | ファイルを文字列として読み込む（非同期） |
| `readFileSync(String path)` | ファイルを文字列として読み込む（同期） |
| `writeFile(String path, String content)` | ファイルにコンテンツを書き込む（非同期） |
| `writeFileSync(String path, String content)` | ファイルにコンテンツを書き込む（同期） |
| `appendFile(String path, String content)` | ファイルにコンテンツを追記 |
| `ensureDirectory(String path)` | ディレクトリが存在しない場合は作成 |
| `deleteFile(String path)` | ファイルを削除 |
| `copyFile(String source, String destination)` | ファイルをコピー |

<div id="json-and-yaml-helpers"></div>

## JSON・YAML ヘルパー

組み込みヘルパーで JSON ファイルと YAML ファイルを読み書きします:

```dart
// JSON を Map として読み込む
Map<String, dynamic> config = await readJson('config.json');

// JSON を List として読み込む
List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

// JSON を書き込む（デフォルトで整形出力）
await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

// コンパクトな JSON を書き込む
await writeJson('output.json', data, pretty: false);

// JSON 配列ファイルに追加（重複防止付き）
await appendToJsonArray(
  'lib/app/commands/commands.json',
  {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
  uniqueKey: 'name',
);

// YAML を Map として読み込む
Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
info('Project: ${pubspec['name']}');
```

| メソッド | 説明 |
|--------|-------------|
| `readJson(String path)` | JSON ファイルを `Map<String, dynamic>` として読み込む |
| `readJsonArray(String path)` | JSON ファイルを `List<dynamic>` として読み込む |
| `writeJson(String path, dynamic data, {bool pretty = true})` | データを JSON として書き込む |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | JSON 配列ファイルに追加 |
| `readYaml(String path)` | YAML ファイルを `Map<String, dynamic>` として読み込む |

<div id="dart-file-manipulation"></div>

## Dart ファイル操作

Dart ソースファイルをプログラム的に編集するためのヘルパーです。スキャフォールディングツールの構築に便利です:

```dart
// インポートを追加（既に存在する場合はスキップ）
await addImport(
  'lib/bootstrap/providers.dart',
  "import '/app/providers/firebase_provider.dart';",
);

// 最後の閉じ中括弧の前にコードを挿入
await insertBeforeClosingBrace(
  'lib/bootstrap/providers.dart',
  '  FirebaseProvider(),',
);

// ファイルに文字列が含まれているか確認
bool hasImport = await fileContains(
  'lib/bootstrap/providers.dart',
  'firebase_provider',
);

// ファイルが正規表現パターンに一致するか確認
bool hasClass = await fileContainsPattern(
  'lib/app/models/user.dart',
  RegExp(r'class User'),
);
```

| メソッド | 説明 |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Dart ファイルにインポートを追加（存在する場合はスキップ） |
| `insertBeforeClosingBrace(String filePath, String code)` | ファイル内の最後の `}` の前にコードを挿入 |
| `fileContains(String filePath, String identifier)` | ファイルに文字列が含まれているか確認 |
| `fileContainsPattern(String filePath, Pattern pattern)` | ファイルがパターンに一致するか確認 |

<div id="directory-helpers"></div>

## ディレクトリヘルパー

ディレクトリの操作とファイル検索のためのヘルパーです:

```dart
// ディレクトリの内容を一覧表示
var entities = listDirectory('lib/app/models');
var allEntities = listDirectory('lib/', recursive: true);

// 拡張子でファイルを検索
List<File> dartFiles = findFiles(
  'lib/app/models',
  extension: '.dart',
  recursive: true,
);

// 名前パターンでファイルを検索
List<File> testFiles = findFiles(
  'test/',
  namePattern: RegExp(r'_test\.dart$'),
);

// ディレクトリを再帰的に削除
await deleteDirectory('build/');

// ディレクトリを再帰的にコピー
await copyDirectory('lib/templates', 'lib/generated');
```

| メソッド | 説明 |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | ディレクトリの内容を一覧表示 |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | 条件に一致するファイルを検索 |
| `deleteDirectory(String path)` | ディレクトリを再帰的に削除 |
| `copyDirectory(String source, String destination)` | ディレクトリを再帰的にコピー |

<div id="case-conversion-helpers"></div>

## ケース変換ヘルパー

`recase` パッケージをインポートせずに、命名規則間で文字列を変換します:

```dart
String input = 'user profile page';

snakeCase(input);    // user_profile_page
camelCase(input);    // userProfilePage
pascalCase(input);   // UserProfilePage
titleCase(input);    // User Profile Page
kebabCase(input);    // user-profile-page
constantCase(input); // USER_PROFILE_PAGE
```

| メソッド | 出力形式 | 例 |
|--------|--------------|---------|
| `snakeCase(String input)` | `snake_case` | `user_profile` |
| `camelCase(String input)` | `camelCase` | `userProfile` |
| `pascalCase(String input)` | `PascalCase` | `UserProfile` |
| `titleCase(String input)` | `Title Case` | `User Profile` |
| `kebabCase(String input)` | `kebab-case` | `user-profile` |
| `constantCase(String input)` | `CONSTANT_CASE` | `USER_PROFILE` |

<div id="project-path-helpers"></div>

## プロジェクトパスヘルパー

標準的な {{ config('app.name') }} プロジェクトディレクトリの getter で、プロジェクトルートからの相対パスを返します:

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

// カスタムパスを構築
String customPath = projectPath('app/services/auth_service.dart');
// 戻り値: lib/app/services/auth_service.dart
```

| プロパティ | パス |
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
| `projectPath(String relativePath)` | プロジェクト内の相対パスを解決 |

<div id="platform-helpers"></div>

## プラットフォームヘルパー

プラットフォームの確認と環境変数へのアクセスを行います:

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

| プロパティ / メソッド | 説明 |
|-------------------|-------------|
| `isWindows` | Windows で実行中の場合 `true` |
| `isMacOS` | macOS で実行中の場合 `true` |
| `isLinux` | Linux で実行中の場合 `true` |
| `workingDirectory` | 現在の作業ディレクトリパス |
| `env(String key, [String defaultValue = ''])` | システム環境変数を読み取る |

<div id="dart-and-flutter-commands"></div>

## Dart・Flutter コマンド

一般的な Dart および Flutter CLI コマンドをヘルパーメソッドとして実行します。各メソッドはプロセスの終了コードを返します:

```dart
// Dart ファイルまたはディレクトリをフォーマット
await dartFormat('lib/app/models/user.dart');

// dart analyze を実行
int analyzeResult = await dartAnalyze('lib/');

// flutter pub get を実行
await flutterPubGet();

// flutter clean を実行
await flutterClean();

// 追加の引数付きでターゲットをビルド
await flutterBuild('apk', args: ['--release', '--split-per-abi']);
await flutterBuild('web', args: ['--release']);

// flutter test を実行
await flutterTest();
await flutterTest('test/unit/');
```

| メソッド | 説明 |
|--------|-------------|
| `dartFormat(String path)` | ファイルまたはディレクトリで `dart format` を実行 |
| `dartAnalyze([String? path])` | `dart analyze` を実行 |
| `flutterPubGet()` | `flutter pub get` を実行 |
| `flutterClean()` | `flutter clean` を実行 |
| `flutterBuild(String target, {List<String> args})` | `flutter build <target>` を実行 |
| `flutterTest([String? path])` | `flutter test` を実行 |

<div id="validation-helpers"></div>

## バリデーションヘルパー

コード生成のためのユーザー入力のバリデーションとクリーニングのヘルパーです:

```dart
// Dart 識別子のバリデーション
if (!isValidDartIdentifier('MyClass')) {
  error('Invalid Dart identifier');
}

// 空でない最初の引数を要求（欠如時は中断）
String name = requireArgument(result, message: 'Please provide a name');

// クラス名をクリーニング（PascalCase、サフィックス除去）
String className = cleanClassName('user_model', removeSuffixes: ['_model']);
// 戻り値: 'User'

// ファイル名をクリーニング（snake_case + 拡張子）
String fileName = cleanFileName('UserModel', extension: '.dart');
// 戻り値: 'user_model.dart'
```

| メソッド | 説明 |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Dart 識別子名のバリデーション |
| `requireArgument(CommandResult result, {String? message})` | 空でない最初の引数を要求、または中断 |
| `cleanClassName(String name, {List<String> removeSuffixes})` | クラス名をクリーニングして PascalCase に変換 |
| `cleanFileName(String name, {String extension = '.dart'})` | ファイル名をクリーニングして snake_case に変換 |

<div id="file-scaffolding"></div>

## ファイルスキャフォールディング

スキャフォールディングシステムを使用して、1 つまたは複数のファイルをコンテンツ付きで作成します。

### 単一ファイル

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

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `path` | `String` | 作成するファイルパス |
| `content` | `String` | ファイルの内容 |
| `force` | `bool` | 既存の場合上書き（デフォルト: `false`） |
| `successMessage` | `String?` | 成功時に表示されるメッセージ |

### 複数ファイル

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

`ScaffoldFile` クラス:

| プロパティ | 型 | 説明 |
|----------|------|-------------|
| `path` | `String` | 作成するファイルパス |
| `content` | `String` | ファイルの内容 |
| `successMessage` | `String?` | 成功時に表示されるメッセージ |

<div id="task-runner"></div>

## タスクランナー

自動ステータス出力付きで一連の名前付きタスクを実行します。

### 基本タスクランナー

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

### スピナー付きタスクランナー

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

`CommandTask` クラス:

| プロパティ | 型 | デフォルト | 説明 |
|----------|------|---------|-------------|
| `name` | `String` | 必須 | 出力に表示されるタスク名 |
| `action` | `Future<void> Function()` | 必須 | 実行する非同期関数 |
| `stopOnError` | `bool` | `true` | 失敗時に残りのタスクを停止するかどうか |

<div id="table-output"></div>

## テーブル出力

コンソールにフォーマットされた ASCII テーブルを表示します:

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

出力:

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

## 使用例

<div id="current-time-command"></div>

### 現在時刻コマンド

現在時刻を表示するシンプルなコマンドです:

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

### フォントダウンロードコマンド

Google Fonts をダウンロードしてプロジェクトにインストールするコマンドです:

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

### デプロイパイプラインコマンド

タスクランナーを使用して完全なデプロイパイプラインを実行するコマンドです:

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
