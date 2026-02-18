# ロギング

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [ログレベル](#log-levels "ログレベル")
- [ログメソッド](#log-methods "ログメソッド")
- [JSON ロギング](#json-logging "JSON ロギング")
- [カラー出力](#colored-output "カラー出力")
- [ログリスナー](#log-listeners "ログリスナー")
- [ヘルパーエクステンション](#helper-extensions "ヘルパーエクステンション")
- [設定](#configuration "設定")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} v7 は包括的なロギングシステムを提供します。

ログは `.env` ファイルで `APP_DEBUG=true` の場合にのみ出力され、本番アプリをクリーンに保ちます。

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 基本的なロギング
printInfo("Hello World");
printDebug("Debug message");
printError("Error occurred");
```

<div id="log-levels"></div>

## ログレベル

{{ config('app.name') }} v7 は、カラー出力を持つ複数のログレベルをサポートしています:

| レベル | メソッド | 色 | 用途 |
|-------|--------|-------|----------|
| Debug | `printDebug()` | シアン | 詳細なデバッグ情報 |
| Info | `printInfo()` | ブルー | 一般的な情報 |
| Error | `printError()` | レッド | エラーと例外 |

``` dart
printDebug("Fetching user ID: 123");
printInfo("App initialized");
printError("Network request failed");
```

出力例:
```
[2025-01-27 10:30:45] [debug] Fetching user ID: 123
[2025-01-27 10:30:45] [info] App initialized
[2025-01-27 10:30:46] [error] Network request failed
```

<div id="log-methods"></div>

## ログメソッド

### 基本的なロギング

``` dart
// クラスメソッド
printInfo("Information message");
printDebug("Debug message");
printError("Error message");
printJson({"key": "value"});
```

### スタックトレース付きエラー

より良いデバッグのために、スタックトレース付きでエラーをログに記録します:

``` dart
try {
  await someOperation();
} catch (e, stackTrace) {
  printError(e, stackTrace: stackTrace);
}
```

### デバッグモードに関係なく強制出力

`APP_DEBUG=false` の場合でも出力するには `alwaysPrint: true` を使用します:

``` dart
printInfo("Critical info", alwaysPrint: true);
printError("Critical error", alwaysPrint: true);
```

### 次のログを表示（一回限りのオーバーライド）

`APP_DEBUG=false` のときに単一のログを出力します:

``` dart
// .env: APP_DEBUG=false

printInfo("This won't print");

showNextLog();
printInfo("This will print"); // 一度だけ出力

printInfo("This won't print again");
```

<div id="json-logging"></div>

## JSON ロギング

{{ config('app.name') }} v7 には専用の JSON ロギングメソッドが含まれています:

``` dart
Map<String, dynamic> userData = {
  "id": 123,
  "name": "Anthony",
  "email": "anthony@example.com"
};

// コンパクト JSON
printJson(userData);
// {"id":123,"name":"Anthony","email":"anthony@example.com"}

// 整形された JSON
printJson(userData, prettyPrint: true);
// {
//   "id": 123,
//   "name": "Anthony",
//   "email": "anthony@example.com"
// }
```

<div id="colored-output"></div>

## カラー出力

{{ config('app.name') }} v7 はデバッグモードで ANSI カラーをログ出力に使用します。各ログレベルには識別しやすいように異なる色が割り当てられています。

### カラーの無効化

``` dart
// カラー出力をグローバルに無効化
NyLogger.useColors = false;
```

カラーは以下の場合に自動的に無効化されます:
- リリースモード時
- ターミナルが ANSI エスケープコードをサポートしていない場合

<div id="log-listeners"></div>

## ログリスナー

{{ config('app.name') }} v7 では、すべてのログエントリをリアルタイムで監視できます:

``` dart
// ログリスナーの設定
NyLogger.onLog = (NyLogEntry entry) {
  print("Log: [${entry.type}] ${entry.message}");

  // クラッシュレポートサービスに送信
  if (entry.type == 'error') {
    CrashReporter.log(entry.message, stackTrace: entry.stackTrace);
  }
};
```

### NyLogEntry のプロパティ

``` dart
NyLogger.onLog = (NyLogEntry entry) {
  entry.message;    // ログメッセージ
  entry.type;       // ログレベル (debug, info, warning, error, success, verbose)
  entry.dateTime;   // ログの作成日時
  entry.stackTrace; // スタックトレース（エラー時）
};
```

### ユースケース

- エラーをクラッシュレポートサービスに送信（Sentry、Firebase Crashlytics）
- カスタムログビューアの構築
- デバッグ用にログを保存
- リアルタイムでアプリの動作を監視

``` dart
// 例: エラーを Sentry に送信
NyLogger.onLog = (entry) {
  if (entry.type == 'error') {
    Sentry.captureMessage(
      entry.message,
      level: SentryLevel.error,
    );
  }
};
```

<div id="helper-extensions"></div>

## ヘルパーエクステンション

{{ config('app.name') }} はロギング用の便利なエクステンションメソッドを提供します:

### dump()

任意の値をコンソールに出力します:

``` dart
String project = 'Nylo';
project.dump(); // 'Nylo'

List<String> seasons = ['Spring', 'Summer', 'Fall', 'Winter'];
seasons.dump(); // ['Spring', 'Summer', 'Fall', 'Winter']

int age = 25;
age.dump(); // 25

// 関数構文
dump("Hello World");
```

### dd() - Dump and Die

値を出力し、即座に実行を停止します（デバッグに便利）:

``` dart
String code = 'Dart';
code.dd(); // 'Dart' を出力して実行を停止

// 関数構文
dd("Debug point reached");
```

<div id="configuration"></div>

## 設定

### 環境変数

`.env` ファイルでロギングの動作を制御します:

``` bash
# すべてのロギングを有効/無効にする
APP_DEBUG=true
```

### ログの日時表示

{{ config('app.name') }} はログ出力にタイムスタンプを含めることができます。Nylo のセットアップで設定します:

``` dart
// ブートプロバイダー内で
Nylo.instance.showDateTimeInLogs(true);
```

タイムスタンプ付きの出力:
```
[2025-01-27 10:30:45] [info] User logged in
```

タイムスタンプなしの出力:
```
[info] User logged in
```

### ベストプラクティス

1. **適切なログレベルを使用する** - すべてをエラーとしてログに記録しない
2. **本番環境で詳細なログを削除する** - 本番環境では `APP_DEBUG=false` を維持
3. **コンテキストを含める** - デバッグに関連するデータをログに記録
4. **構造化ロギングを使用する** - 複雑なデータには `NyLogger.json()` を使用
5. **エラー監視を設定する** - `NyLogger.onLog` を使用してエラーをキャッチ

``` dart
// 良いロギングの実践
NyLogger.info("User ${user.id} logged in from ${device.platform}");
NyLogger.error("API request failed", stackTrace: stackTrace, alwaysPrint: true);
NyLogger.json(response.data, prettyPrint: true);
```
