# 設定

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- 環境
  - [.env ファイル](#env-file ".env ファイル")
  - [環境設定の生成](#generating-env "環境設定の生成")
  - [値の取得](#retrieving-values "値の取得")
  - [Config クラスの作成](#creating-config-classes "Config クラスの作成")
  - [変数の型](#variable-types "変数の型")
- [環境フレーバー](#environment-flavours "環境フレーバー")
- [ビルド時インジェクション](#build-time-injection "ビルド時インジェクション")


<div id="introduction"></div>

## はじめに

{{ config('app.name') }} v7 は安全な環境設定システムを使用します。環境変数は `.env` ファイルに保存され、その後アプリで使用するために生成された Dart ファイル（`env.g.dart`）に暗号化されます。

このアプローチは以下を提供します:
- **セキュリティ**: 環境値はコンパイルされたアプリ内で XOR 暗号化されます
- **型安全性**: 値は適切な型に自動的にパースされます
- **ビルド時の柔軟性**: 開発、ステージング、本番の異なる設定

<div id="env-file"></div>

## .env ファイル

プロジェクトルートの `.env` ファイルに設定変数が含まれています:

``` bash
# 環境設定
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### 利用可能な変数

| 変数 | 説明 |
|----------|-------------|
| `APP_KEY` | **必須**。暗号化用の 32 文字のシークレットキー |
| `APP_NAME` | アプリケーション名 |
| `APP_ENV` | 環境: `developing` または `production` |
| `APP_DEBUG` | デバッグモードの有効化（`true`/`false`） |
| `APP_URL` | アプリの URL |
| `API_BASE_URL` | API リクエストのベース URL |
| `ASSET_PATH` | アセットフォルダへのパス |
| `DEFAULT_LOCALE` | デフォルトの言語コード |

<div id="generating-env"></div>

## 環境設定の生成

{{ config('app.name') }} v7 では、アプリが環境変数にアクセスするために、暗号化された環境ファイルを生成する必要があります。

### ステップ 1: APP_KEY の生成

まず、安全な APP_KEY を生成します:

``` bash
metro make:key
```

これにより、`.env` ファイルに 32 文字の `APP_KEY` が追加されます。

### ステップ 2: env.g.dart の生成

暗号化された環境ファイルを生成します:

``` bash
metro make:env
```

これにより、暗号化された環境変数を含む `lib/bootstrap/env.g.dart` が作成されます。

環境設定はアプリ起動時に自動的に登録されます -- `main.dart` の `Nylo.init(env: Env.get, ...)` がこれを処理します。追加のセットアップは不要です。

### 変更後の再生成

`.env` ファイルを変更したら、設定を再生成します:

``` bash
metro make:env --force
```

`--force` フラグは既存の `env.g.dart` を上書きします。

<div id="retrieving-values"></div>

## 値の取得

環境変数にアクセスする推奨方法は、**Config クラス** を通じてです。`lib/config/app.dart` ファイルは `getEnv()` を使用して、環境変数を型付きの静的フィールドとして公開します:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

アプリコード内で Config クラスを通じて値にアクセスします:

``` dart
// アプリ内のどこからでも
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

このパターンにより、環境変数へのアクセスを Config クラスに集約できます。`getEnv()` ヘルパーは、アプリコード内で直接使用するのではなく、Config クラス内で使用するべきです。

<div id="creating-config-classes"></div>

## Config クラスの作成

Metro を使用して、サードパーティサービスや機能固有の設定用にカスタム Config クラスを作成できます:

``` bash
metro make:config RevenueCat
```

これにより、`lib/config/revenue_cat_config.dart` に新しい設定ファイルが作成されます:

``` dart
final class RevenueCatConfig {
  // 設定値をここに追加
}
```

### 例: RevenueCat の設定

**ステップ 1:** `.env` ファイルに環境変数を追加します:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**ステップ 2:** これらの値を参照するように Config クラスを更新します:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**ステップ 3:** 環境設定を再生成します:

``` bash
metro make:env --force
```

**ステップ 4:** アプリで Config クラスを使用します:

``` dart
import '/config/revenue_cat_config.dart';

// RevenueCat の初期化
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// エンタイトルメントの確認
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // プレミアムアクセスを付与
}
```

このアプローチにより、API キーと設定値が安全かつ一元管理され、環境ごとに異なる値を簡単に管理できます。

<div id="variable-types"></div>

## 変数の型

`.env` ファイルの値は自動的にパースされます:

| .env の値 | Dart の型 | 例 |
|------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (空文字列) |


<div id="environment-flavours"></div>

## 環境フレーバー

開発、ステージング、本番の異なる設定を作成します。

### ステップ 1: 環境ファイルの作成

別々の `.env` ファイルを作成します:

``` bash
.env                  # 開発（デフォルト）
.env.staging          # ステージング
.env.production       # 本番
```

`.env.production` の例:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### ステップ 2: 環境設定の生成

特定の env ファイルから生成します:

``` bash
# 本番用
metro make:env --file=".env.production" --force

# ステージング用
metro make:env --file=".env.staging" --force
```

### ステップ 3: アプリのビルド

適切な設定でビルドします:

``` bash
# 開発
flutter run

# 本番ビルド
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## ビルド時インジェクション

セキュリティを強化するために、ソースコードに埋め込む代わりに、ビルド時に APP_KEY を注入できます。

### --dart-define モードで生成

``` bash
metro make:env --dart-define
```

これにより、APP_KEY を埋め込まない `env.g.dart` が生成されます。

### APP_KEY インジェクションでビルド

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# 実行
flutter run --dart-define=APP_KEY=your-secret-key
```

このアプローチにより、APP_KEY がソースコードから除外されます。以下の場合に便利です:
- シークレットが注入される CI/CD パイプライン
- オープンソースプロジェクト
- 強化されたセキュリティ要件

### ベストプラクティス

1. **`.env` をバージョン管理にコミットしない** - `.gitignore` に追加してください
2. **`.env-example` を使用する** - 機密値を含まないテンプレートをコミットしてください
3. **変更後に再生成する** - `.env` を変更したら必ず `metro make:env --force` を実行してください
4. **環境ごとに異なるキーを使用する** - 開発、ステージング、本番で一意の APP_KEY を使用してください
