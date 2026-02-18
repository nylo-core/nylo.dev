# Metro CLI ツール

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [インストール](#install "インストール")
- Make コマンド
  - [コントローラの作成](#make-controller "コントローラの作成")
  - [モデルの作成](#make-model "モデルの作成")
  - [ページの作成](#make-page "ページの作成")
  - [Stateless ウィジェットの作成](#make-stateless-widget "Stateless ウィジェットの作成")
  - [Stateful ウィジェットの作成](#make-stateful-widget "Stateful ウィジェットの作成")
  - [ジャーニーウィジェットの作成](#make-journey-widget "ジャーニーウィジェットの作成")
  - [API Service の作成](#make-api-service "API Service の作成")
  - [イベントの作成](#make-event "イベントの作成")
  - [プロバイダの作成](#make-provider "プロバイダの作成")
  - [テーマの作成](#make-theme "テーマの作成")
  - [フォームの作成](#make-forms "フォームの作成")
  - [ルートガードの作成](#make-route-guard "ルートガードの作成")
  - [設定ファイルの作成](#make-config-file "設定ファイルの作成")
  - [コマンドの作成](#make-command "コマンドの作成")
  - [状態管理ウィジェットの作成](#make-state-managed-widget "状態管理ウィジェットの作成")
  - [Navigation Hub の作成](#make-navigation-hub "Navigation Hub の作成")
  - [ボトムシートモーダルの作成](#make-bottom-sheet-modal "ボトムシートモーダルの作成")
  - [ボタンの作成](#make-button "ボタンの作成")
  - [インターセプターの作成](#make-interceptor "インターセプターの作成")
  - [Env ファイルの作成](#make-env-file "Env ファイルの作成")
  - [キーの生成](#make-key "キーの生成")
- アプリアイコン
  - [アプリアイコンのビルド](#build-app-icons "アプリアイコンのビルド")
- カスタムコマンド
  - [カスタムコマンドの作成](#creating-custom-commands "カスタムコマンドの作成")
  - [カスタムコマンドの実行](#running-custom-commands "カスタムコマンドの実行")
  - [コマンドにオプションを追加](#adding-options-to-custom-commands "コマンドにオプションを追加")
  - [コマンドにフラグを追加](#adding-flags-to-custom-commands "コマンドにフラグを追加")
  - [ヘルパーメソッド](#custom-command-helper-methods "ヘルパーメソッド")
  - [インタラクティブ入力メソッド](#interactive-input-methods "インタラクティブ入力メソッド")
  - [出力フォーマット](#output-formatting "出力フォーマット")
  - [ファイルシステムヘルパー](#file-system-helpers "ファイルシステムヘルパー")
  - [JSON と YAML ヘルパー](#json-yaml-helpers "JSON と YAML ヘルパー")
  - [ケース変換ヘルパー](#case-conversion-helpers "ケース変換ヘルパー")
  - [プロジェクトパスヘルパー](#project-path-helpers "プロジェクトパスヘルパー")
  - [プラットフォームヘルパー](#platform-helpers "プラットフォームヘルパー")
  - [Dart と Flutter コマンド](#dart-flutter-commands "Dart と Flutter コマンド")
  - [Dart ファイル操作](#dart-file-manipulation "Dart ファイル操作")
  - [ディレクトリヘルパー](#directory-helpers "ディレクトリヘルパー")
  - [バリデーションヘルパー](#validation-helpers "バリデーションヘルパー")
  - [ファイルスキャフォールディング](#file-scaffolding "ファイルスキャフォールディング")
  - [タスクランナー](#task-runner "タスクランナー")
  - [テーブル出力](#table-output "テーブル出力")
  - [プログレスバー](#progress-bar "プログレスバー")


<div id="introduction"></div>

## はじめに

Metro は {{ config('app.name') }} フレームワークの内部で動作する CLI ツールです。
開発を高速化するための多くの便利なツールを提供しています。

<div id="install"></div>

## インストール

`nylo init` を使用して新しい Nylo プロジェクトを作成すると、`metro` コマンドはターミナルに自動的に設定されます。どの Nylo プロジェクトでもすぐに使い始めることができます。

プロジェクトディレクトリから `metro` を実行して、利用可能なすべてのコマンドを確認できます:

``` bash
metro
```

以下のような出力が表示されるはずです。

``` bash
Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

Usage:
    command [options] [arguments]

Options
    -h

All commands:

[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:theme
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
  make:key
```

<div id="make-controller"></div>

## コントローラの作成

- [新しいコントローラの作成](#making-a-new-controller "新しいコントローラの作成")
- [コントローラの強制作成](#forcefully-make-a-controller "コントローラの強制作成")
<div id="making-a-new-controller"></div>

### 新しいコントローラの作成

ターミナルで以下を実行して新しいコントローラを作成できます。

``` bash
metro make:controller profile_controller
```

これにより、`lib/app/controllers/` ディレクトリ内に新しいコントローラが存在しない場合に作成されます。

<div id="forcefully-make-a-controller"></div>

### コントローラの強制作成

**引数:**

`--force` または `-f` フラグを使用すると、既存のコントローラが存在する場合でも上書きします。

``` bash
metro make:controller profile_controller --force
```

<div id="make-model"></div>

## モデルの作成

- [新しいモデルの作成](#making-a-new-model "新しいモデルの作成")
- [JSON からモデルを作成](#make-model-from-json "JSON からモデルを作成")
- [モデルの強制作成](#forcefully-make-a-model "モデルの強制作成")
<div id="making-a-new-model"></div>

### 新しいモデルの作成

ターミナルで以下を実行して新しいモデルを作成できます。

``` bash
metro make:model product
```

新しく作成されたモデルは `lib/app/models/` に配置されます。

<div id="make-model-from-json"></div>

### JSON からモデルを作成

**引数:**

`--json` または `-j` フラグを使用すると、JSON ペイロードから新しいモデルを作成します。

``` bash
metro make:model product --json
```

その後、ターミナルに JSON を貼り付けると、モデルが生成されます。

<div id="forcefully-make-a-model"></div>

### モデルの強制作成

**引数:**

`--force` または `-f` フラグを使用すると、既存のモデルが存在する場合でも上書きします。

``` bash
metro make:model product --force
```

<div id="make-page"></div>

## ページの作成

- [新しいページの作成](#making-a-new-page "新しいページの作成")
- [コントローラ付きページの作成](#create-a-page-with-a-controller "コントローラ付きページの作成")
- [認証ページの作成](#create-an-auth-page "認証ページの作成")
- [初期ページの作成](#create-an-initial-page "初期ページの作成")
- [ページの強制作成](#forcefully-make-a-page "ページの強制作成")

<div id="making-a-new-page"></div>

### 新しいページの作成

ターミナルで以下を実行して新しいページを作成できます。

``` bash
metro make:page product_page
```

これにより、`lib/resources/pages/` ディレクトリ内に新しいページが存在しない場合に作成されます。

<div id="create-a-page-with-a-controller"></div>

### コントローラ付きページの作成

ターミナルで以下を実行してコントローラ付きの新しいページを作成できます。

**引数:**

`--controller` または `-c` フラグを使用すると、コントローラ付きの新しいページが作成されます。

``` bash
metro make:page product_page -c
```

<div id="create-an-auth-page"></div>

### 認証ページの作成

ターミナルで以下を実行して新しい認証ページを作成できます。

**引数:**

`--auth` または `-a` フラグを使用すると、新しい認証ページが作成されます。

``` bash
metro make:page login_page -a
```

<div id="create-an-initial-page"></div>

### 初期ページの作成

ターミナルで以下を実行して新しい初期ページを作成できます。

**引数:**

`--initial` または `-i` フラグを使用すると、新しい初期ページが作成されます。

``` bash
metro make:page home_page -i
```

<div id="forcefully-make-a-page"></div>

### ページの強制作成

**引数:**

`--force` または `-f` フラグを使用すると、既存のページが存在する場合でも上書きします。

``` bash
metro make:page product_page --force
```

<div id="make-stateless-widget"></div>

## Stateless ウィジェットの作成

- [新しい Stateless ウィジェットの作成](#making-a-new-stateless-widget "新しい Stateless ウィジェットの作成")
- [Stateless ウィジェットの強制作成](#forcefully-make-a-stateless-widget "Stateless ウィジェットの強制作成")
<div id="making-a-new-stateless-widget"></div>

### 新しい Stateless ウィジェットの作成

ターミナルで以下を実行して新しい Stateless ウィジェットを作成できます。

``` bash
metro make:stateless_widget product_rating_widget
```

上記のコマンドにより、`lib/resources/widgets/` ディレクトリ内に新しいウィジェットが存在しない場合に作成されます。

<div id="forcefully-make-a-stateless-widget"></div>

### Stateless ウィジェットの強制作成

**引数:**

`--force` または `-f` フラグを使用すると、既存のウィジェットが存在する場合でも上書きします。

``` bash
metro make:stateless_widget product_rating_widget --force
```

<div id="make-stateful-widget"></div>

## Stateful ウィジェットの作成

- [新しい Stateful ウィジェットの作成](#making-a-new-stateful-widget "新しい Stateful ウィジェットの作成")
- [Stateful ウィジェットの強制作成](#forcefully-make-a-stateful-widget "Stateful ウィジェットの強制作成")

<div id="making-a-new-stateful-widget"></div>

### 新しい Stateful ウィジェットの作成

ターミナルで以下を実行して新しい Stateful ウィジェットを作成できます。

``` bash
metro make:stateful_widget product_rating_widget
```

上記のコマンドにより、`lib/resources/widgets/` ディレクトリ内に新しいウィジェットが存在しない場合に作成されます。

<div id="forcefully-make-a-stateful-widget"></div>

### Stateful ウィジェットの強制作成

**引数:**

`--force` または `-f` フラグを使用すると、既存のウィジェットが存在する場合でも上書きします。

``` bash
metro make:stateful_widget product_rating_widget --force
```

<div id="make-journey-widget"></div>

## ジャーニーウィジェットの作成

- [新しいジャーニーウィジェットの作成](#making-a-new-journey-widget "新しいジャーニーウィジェットの作成")
- [ジャーニーウィジェットの強制作成](#forcefully-make-a-journey-widget "ジャーニーウィジェットの強制作成")

<div id="making-a-new-journey-widget"></div>

### 新しいジャーニーウィジェットの作成

ターミナルで以下を実行して新しいジャーニーウィジェットを作成できます。

``` bash
metro make:journey_widget product_journey --parent="[NAVIGATION_HUB]"

# BaseNavigationHub がある場合の完全な例
metro make:journey_widget welcome,user_dob,user_photos --parent="Base"
```

上記のコマンドにより、`lib/resources/widgets/` ディレクトリ内に新しいウィジェットが存在しない場合に作成されます。

`--parent` 引数は、新しいジャーニーウィジェットが追加される親ウィジェットを指定するために使用されます。

例

``` bash
metro make:navigation_hub onboarding
```

次に、新しいジャーニーウィジェットを追加します。
``` bash
metro make:journey_widget welcome,user_dob,user_photos --parent="onboarding"
```

<div id="forcefully-make-a-journey-widget"></div>

### ジャーニーウィジェットの強制作成
**引数:**
`--force` または `-f` フラグを使用すると、既存のウィジェットが存在する場合でも上書きします。

``` bash
metro make:journey_widget product_journey --force --parent="[YOUR_NAVIGATION_HUB]"
```

<div id="make-api-service"></div>

## API Service の作成

- [新しい API Service の作成](#making-a-new-api-service "新しい API Service の作成")
- [モデル付き API Service の作成](#making-a-new-api-service-with-a-model "モデル付き API Service の作成")
- [Postman を使用した API Service の作成](#make-api-service-using-postman "Postman を使用した API Service の作成")
- [API Service の強制作成](#forcefully-make-an-api-service "API Service の強制作成")

<div id="making-a-new-api-service"></div>

### 新しい API Service の作成

ターミナルで以下を実行して新しい API Service を作成できます。

``` bash
metro make:api_service user_api_service
```

新しく作成された API Service は `lib/app/networking/` に配置されます。

<div id="making-a-new-api-service-with-a-model"></div>

### モデル付き API Service の作成

ターミナルで以下を実行してモデル付きの新しい API Service を作成できます。

**引数:**

`--model` または `-m` オプションを使用すると、モデル付きの新しい API Service が作成されます。

``` bash
metro make:api_service user --model="User"
```

新しく作成された API Service は `lib/app/networking/` に配置されます。

### API Service の強制作成

**引数:**

`--force` または `-f` フラグを使用すると、既存の API Service が存在する場合でも上書きします。

``` bash
metro make:api_service user --force
```

<div id="make-event"></div>

## イベントの作成

- [新しいイベントの作成](#making-a-new-event "新しいイベントの作成")
- [イベントの強制作成](#forcefully-make-an-event "イベントの強制作成")

<div id="making-a-new-event"></div>

### 新しいイベントの作成

ターミナルで以下を実行して新しいイベントを作成できます。

``` bash
metro make:event login_event
```

これにより、`lib/app/events` に新しいイベントが作成されます。

<div id="forcefully-make-an-event"></div>

### イベントの強制作成

**引数:**

`--force` または `-f` フラグを使用すると、既存のイベントが存在する場合でも上書きします。

``` bash
metro make:event login_event --force
```

<div id="make-provider"></div>

## プロバイダの作成

- [新しいプロバイダの作成](#making-a-new-provider "新しいプロバイダの作成")
- [プロバイダの強制作成](#forcefully-make-a-provider "プロバイダの強制作成")

<div id="making-a-new-provider"></div>

### 新しいプロバイダの作成

以下のコマンドを使用してアプリケーションに新しいプロバイダを作成します。

``` bash
metro make:provider firebase_provider
```

新しく作成されたプロバイダは `lib/app/providers/` に配置されます。

<div id="forcefully-make-a-provider"></div>

### プロバイダの強制作成

**引数:**

`--force` または `-f` フラグを使用すると、既存のプロバイダが存在する場合でも上書きします。

``` bash
metro make:provider firebase_provider --force
```

<div id="make-theme"></div>

## テーマの作成

- [新しいテーマの作成](#making-a-new-theme "新しいテーマの作成")
- [テーマの強制作成](#forcefully-make-a-theme "テーマの強制作成")

<div id="making-a-new-theme"></div>

### 新しいテーマの作成

ターミナルで以下を実行してテーマを作成できます。

``` bash
metro make:theme bright_theme
```

これにより、`lib/resources/themes/` に新しいテーマが作成されます。

<div id="forcefully-make-a-theme"></div>

### テーマの強制作成

**引数:**

`--force` または `-f` フラグを使用すると、既存のテーマが存在する場合でも上書きします。

``` bash
metro make:theme bright_theme --force
```

<div id="make-forms"></div>

## フォームの作成

- [新しいフォームの作成](#making-a-new-form "新しいフォームの作成")
- [フォームの強制作成](#forcefully-make-a-form "フォームの強制作成")

<div id="making-a-new-form"></div>

### 新しいフォームの作成

ターミナルで以下を実行して新しいフォームを作成できます。

``` bash
metro make:form car_advert_form
```

これにより、`lib/app/forms` に新しいフォームが作成されます。

<div id="forcefully-make-a-form"></div>

### フォームの強制作成

**引数:**

`--force` または `-f` フラグを使用すると、既存のフォームが存在する場合でも上書きします。

``` bash
metro make:form login_form --force
```

<div id="make-route-guard"></div>

## ルートガードの作成

- [新しいルートガードの作成](#making-a-new-route-guard "新しいルートガードの作成")
- [ルートガードの強制作成](#forcefully-make-a-route-guard "ルートガードの強制作成")

<div id="making-a-new-route-guard"></div>

### 新しいルートガードの作成

ターミナルで以下を実行してルートガードを作成できます。

``` bash
metro make:route_guard premium_content
```

これにより、`lib/app/route_guards` に新しいルートガードが作成されます。

<div id="forcefully-make-a-route-guard"></div>

### ルートガードの強制作成

**引数:**

`--force` または `-f` フラグを使用すると、既存のルートガードが存在する場合でも上書きします。

``` bash
metro make:route_guard premium_content --force
```

<div id="make-config-file"></div>

## 設定ファイルの作成

- [新しい設定ファイルの作成](#making-a-new-config-file "新しい設定ファイルの作成")
- [設定ファイルの強制作成](#forcefully-make-a-config-file "設定ファイルの強制作成")

<div id="making-a-new-config-file"></div>

### 新しい設定ファイルの作成

ターミナルで以下を実行して新しい設定ファイルを作成できます。

``` bash
metro make:config shopping_settings
```

これにより、`lib/app/config` に新しい設定ファイルが作成されます。

<div id="forcefully-make-a-config-file"></div>

### 設定ファイルの強制作成

**引数:**

`--force` または `-f` フラグを使用すると、既存の設定ファイルが存在する場合でも上書きします。

``` bash
metro make:config app_config --force
```


<div id="make-command"></div>

## コマンドの作成

- [新しいコマンドの作成](#making-a-new-command "新しいコマンドの作成")
- [コマンドの強制作成](#forcefully-make-a-command "コマンドの強制作成")

<div id="making-a-new-command"></div>

### 新しいコマンドの作成

ターミナルで以下を実行して新しいコマンドを作成できます。

``` bash
metro make:command my_command
```

これにより、`lib/app/commands` に新しいコマンドが作成されます。

<div id="forcefully-make-a-command"></div>

### コマンドの強制作成

**引数:**
`--force` または `-f` フラグを使用すると、既存のコマンドが存在する場合でも上書きします。

``` bash
metro make:command my_command --force
```


<div id="make-state-managed-widget"></div>

## 状態管理ウィジェットの作成

ターミナルで以下を実行して新しい状態管理ウィジェットを作成できます。

``` bash
metro make:state_managed_widget product_rating_widget
```

上記のコマンドにより、`lib/resources/widgets/` に新しいウィジェットが作成されます。

`--force` または `-f` フラグを使用すると、既存のウィジェットが存在する場合でも上書きします。

``` bash
metro make:state_managed_widget product_rating_widget --force
```

<div id="make-navigation-hub"></div>

## Navigation Hub の作成

ターミナルで以下を実行して新しい Navigation Hub を作成できます。

``` bash
metro make:navigation_hub dashboard
```

これにより、`lib/resources/pages/` に新しい Navigation Hub が作成され、ルートが自動的に追加されます。

**引数:**

| フラグ | 短縮形 | 説明 |
|------|-------|-------------|
| `--auth` | `-a` | 認証ページとして作成 |
| `--initial` | `-i` | 初期ページとして作成 |
| `--force` | `-f` | 既存のファイルを上書き |

``` bash
# 初期ページとして作成
metro make:navigation_hub dashboard --initial
```

<div id="make-bottom-sheet-modal"></div>

## ボトムシートモーダルの作成

ターミナルで以下を実行して新しいボトムシートモーダルを作成できます。

``` bash
metro make:bottom_sheet_modal payment_options
```

これにより、`lib/resources/widgets/` に新しいボトムシートモーダルが作成されます。

`--force` または `-f` フラグを使用すると、既存のモーダルが存在する場合でも上書きします。

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="make-button"></div>

## ボタンの作成

ターミナルで以下を実行して新しいボタンウィジェットを作成できます。

``` bash
metro make:button checkout_button
```

これにより、`lib/resources/widgets/` に新しいボタンウィジェットが作成されます。

`--force` または `-f` フラグを使用すると、既存のボタンが存在する場合でも上書きします。

``` bash
metro make:button checkout_button --force
```

<div id="make-interceptor"></div>

## インターセプターの作成

ターミナルで以下を実行して新しいネットワークインターセプターを作成できます。

``` bash
metro make:interceptor auth_interceptor
```

これにより、`lib/app/networking/dio/interceptors/` に新しいインターセプターが作成されます。

`--force` または `-f` フラグを使用すると、既存のインターセプターが存在する場合でも上書きします。

``` bash
metro make:interceptor auth_interceptor --force
```

<div id="make-env-file"></div>

## Env ファイルの作成

ターミナルで以下を実行して新しい環境ファイルを作成できます。

``` bash
metro make:env .env.staging
```

これにより、プロジェクトルートに新しい `.env` ファイルが作成されます。

<div id="make-key"></div>

## キーの生成

環境暗号化用の安全な `APP_KEY` を生成します。これは v7 の暗号化された `.env` ファイルに使用されます。

``` bash
metro make:key
```

**引数:**

| フラグ / オプション | 短縮形 | 説明 |
|---------------|-------|-------------|
| `--force` | `-f` | 既存の APP_KEY を上書き |
| `--file` | `-e` | 対象の .env ファイル（デフォルト: `.env`） |

``` bash
# キーを生成して既存のものを上書き
metro make:key --force

# 特定の env ファイル用のキーを生成
metro make:key --file=.env.production
```

<div id="build-app-icons"></div>

## アプリアイコンのビルド

以下のコマンドを実行して、iOS と Android 用のすべてのアプリアイコンを生成できます。

``` bash
dart run flutter_launcher_icons:main
```

これは `pubspec.yaml` ファイル内の <b>flutter_icons</b> 設定を使用します。

<div id="custom-commands"></div>

## カスタムコマンド

カスタムコマンドを使用すると、プロジェクト固有のコマンドで Nylo の CLI を拡張できます。この機能により、繰り返しのタスクを自動化したり、デプロイワークフローを実装したり、プロジェクトのコマンドラインツールにカスタム機能を追加したりできます。

- [カスタムコマンドの作成](#creating-custom-commands)
- [カスタムコマンドの実行](#running-custom-commands)
- [コマンドにオプションを追加](#adding-options-to-custom-commands)
- [コマンドにフラグを追加](#adding-flags-to-custom-commands)
- [ヘルパーメソッド](#custom-command-helper-methods)

> **注意:** 現在、カスタムコマンドでは nylo_framework.dart をインポートできません。代わりに ny_cli.dart を使用してください。

<div id="creating-custom-commands"></div>

## カスタムコマンドの作成

新しいカスタムコマンドを作成するには、`make:command` 機能を使用します:

```bash
metro make:command current_time
```

`--category` オプションを使用してコマンドのカテゴリを指定できます:

```bash
# カテゴリを指定
metro make:command current_time --category="project"
```

これにより、`lib/app/commands/current_time.dart` に以下の構造で新しいコマンドファイルが作成されます:

``` dart
import 'package:nylo_framework/metro/ny_cli.dart';

void main(arguments) => _CurrentTimeCommand(arguments).run();

/// Current Time コマンド
///
/// 使用方法:
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

      // 現在時刻を取得
      final now = DateTime.now();
      final DateFormat dateFormat = DateFormat(format);

      // 現在時刻をフォーマット
      final formattedTime = dateFormat.format(now);
      info("The current time is " + formattedTime);
  }
}
```

コマンドは `lib/app/commands/commands.json` ファイルに自動的に登録されます。このファイルには登録されたすべてのコマンドのリストが含まれています:

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

<div id="running-custom-commands"></div>

## カスタムコマンドの実行

作成したカスタムコマンドは、Metro のショートハンドまたは完全な Dart コマンドを使用して実行できます:

```bash
metro app:current_time
```

引数なしで `metro` を実行すると、「Custom Commands」セクションにカスタムコマンドが一覧表示されます:

```
[Custom Commands]
  app:app_icon
  app:clear_pub
  project:install_firebase
  project:deploy
```

コマンドのヘルプ情報を表示するには、`--help` または `-h` フラグを使用します:

```bash
metro project:install_firebase --help
```

<div id="adding-options-to-custom-commands"></div>

## コマンドにオプションを追加

オプションを使用すると、コマンドがユーザーからの追加入力を受け取ることができます。`builder` メソッドでコマンドにオプションを追加できます:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  // デフォルト値付きのオプションを追加
  command.addOption(
    'environment',     // オプション名
    abbr: 'e',         // 短縮形の略語
    help: 'Target deployment environment', // ヘルプテキスト
    defaultValue: 'development',  // デフォルト値
    allowed: ['development', 'staging', 'production'] // 許可される値
  );

  return command;
}
```

コマンドの `handle` メソッドでオプション値にアクセスします:

```dart
@override
Future<void> handle(CommandResult result) async {
  final environment = result.getString('environment');
  info('Deploying to $environment environment...');

  // コマンドの実装...
}
```

使用例:

```bash
metro project:deploy --environment=production
# または略語を使用
metro project:deploy -e production
```

<div id="adding-flags-to-custom-commands"></div>

## コマンドにフラグを追加

フラグはオン/オフを切り替えられるブーリアンオプションです。`addFlag` メソッドを使用してコマンドにフラグを追加します:

```dart
@override
CommandBuilder builder(CommandBuilder command) {

  command.addFlag(
    'verbose',       // フラグ名
    abbr: 'v',       // 短縮形の略語
    help: 'Enable verbose output', // ヘルプテキスト
    defaultValue: false  // デフォルトはオフ
  );

  return command;
}
```

コマンドの `handle` メソッドでフラグの状態を確認します:

```dart
@override
Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');

  if (verbose) {
    info('Verbose mode enabled');
    // 追加のログ出力...
  }

  // コマンドの実装...
}
```

使用例:

```bash
metro project:deploy --verbose
# または略語を使用
metro project:deploy -v
```

<div id="custom-command-helper-methods"></div>

## ヘルパーメソッド

`NyCustomCommand` 基底クラスは、一般的なタスクを支援するためのいくつかのヘルパーメソッドを提供しています:

#### メッセージの出力

異なる色でメッセージを出力するためのメソッド:

| |  |
|-------------|-------------|
| [`info`](#custom-command-helper-formatting)      | 情報メッセージを青色テキストで出力 |
| [`error`](#custom-command-helper-formatting)     | エラーメッセージを赤色テキストで出力 |
| [`success`](#custom-command-helper-formatting)   | 成功メッセージを緑色テキストで出力 |
| [`warning`](#custom-command-helper-formatting)   | 警告メッセージを黄色テキストで出力 |

#### プロセスの実行

プロセスを実行してコンソールに出力を表示します:

| |  |
|-------------|-------------|
| [`addPackage`](#custom-command-helper-add-package) | `pubspec.yaml` にパッケージを追加 |
| [`addPackages`](#custom-command-helper-add-packages) | `pubspec.yaml` に複数のパッケージを追加 |
| [`runProcess`](#custom-command-helper-run-process) | 外部プロセスを実行してコンソールに出力を表示 |
| [`prompt`](#custom-command-helper-prompt)    | ユーザー入力をテキストとして収集 |
| [`confirm`](#custom-command-helper-confirm)   | はい/いいえの質問をしてブーリアン結果を返す |
| [`select`](#custom-command-helper-select)    | オプションのリストを提示してユーザーに 1 つ選択させる |
| [`multiSelect`](#custom-command-helper-multi-select) | ユーザーにリストから複数のオプションを選択させる |

#### ネットワークリクエスト

コンソールからネットワークリクエストを行います:

| |  |
|-------------|-------------|
| [`api`](#custom-command-helper-multi-select) | Nylo API クライアントを使用して API 呼び出しを行う |


#### ローディングスピナー

関数の実行中にローディングスピナーを表示します:

| |  |
|-------------|-------------|
| [`withSpinner`](#using-with-spinner) | 関数の実行中にローディングスピナーを表示 |
| [`createSpinner`](#manual-spinner-control) | 手動制御用のスピナーインスタンスを作成 |

#### カスタムコマンドヘルパー

コマンド引数を管理するための以下のヘルパーメソッドも使用できます:

| |  |
|-------------|-------------|
| [`getString`](#custom-command-helper-get-string) | コマンド引数から文字列値を取得 |
| [`getBool`](#custom-command-helper-get-bool)   | コマンド引数からブーリアン値を取得 |
| [`getInt`](#custom-command-helper-get-int)    | コマンド引数から整数値を取得 |
| [`sleep`](#custom-command-helper-sleep) | 指定した時間だけ実行を一時停止 |


### 外部プロセスの実行

```dart
// プロセスを実行してコンソールに出力を表示
await runProcess('flutter build web --release');

// サイレントモードでプロセスを実行
await runProcess('flutter pub get', silent: true);

// 特定のディレクトリでプロセスを実行
await runProcess('git pull', workingDirectory: './my-project');
```

### パッケージ管理

<div id="custom-command-helper-add-package"></div>
<div id="custom-command-helper-add-packages"></div>

```dart
// pubspec.yaml にパッケージを追加
addPackage('firebase_core', version: '^2.4.0');

// dev パッケージを pubspec.yaml に追加
addPackage('build_runner', dev: true);

// 複数のパッケージを一度に追加
addPackages(['firebase_auth', 'firebase_storage', 'quickalert']);
```

<div id="custom-command-helper-formatting"></div>

### 出力フォーマット

```dart
// 色分けされたステータスメッセージを出力
info('Processing files...');    // 青色テキスト
error('Operation failed');      // 赤色テキスト
success('Deployment complete'); // 緑色テキスト
warning('Outdated package');    // 黄色テキスト
```

<div id="interactive-input-methods"></div>

## インタラクティブ入力メソッド

`NyCustomCommand` 基底クラスは、ターミナルでユーザー入力を収集するためのいくつかのメソッドを提供しています。これらのメソッドにより、カスタムコマンド用のインタラクティブなコマンドラインインターフェースを簡単に作成できます。

<div id="custom-command-helper-prompt"></div>

### テキスト入力

```dart
String prompt(String question, {String defaultValue = ''})
```

ユーザーに質問を表示し、テキストの回答を収集します。

**パラメータ:**
- `question`: 表示する質問またはプロンプト
- `defaultValue`: ユーザーが Enter のみを押した場合のオプションのデフォルト値

**戻り値:** ユーザーの入力を文字列として返します。入力がない場合はデフォルト値を返します

**例:**
```dart
final name = prompt('What is your project name?', defaultValue: 'my_app');
final description = prompt('Enter a project description:');
```

<div id="custom-command-helper-confirm"></div>

### 確認

```dart
bool confirm(String question, {bool defaultValue = false})
```

ユーザーにはい/いいえの質問をしてブーリアン結果を返します。

**パラメータ:**
- `question`: はい/いいえの質問
- `defaultValue`: デフォルトの回答（true ははい、false はいいえ）

**戻り値:** ユーザーがはいと回答した場合は `true`、いいえの場合は `false`

**例:**
```dart
if (confirm('Would you like to continue?', defaultValue: true)) {
  // ユーザーが確認したか、Enter を押した（デフォルトを受け入れた）
  await runProcess('flutter pub get');
} else {
  // ユーザーが拒否した
  info('Operation canceled');
}
```

<div id="custom-command-helper-select"></div>

### 単一選択

```dart
String select(String question, List<String> options, {String? defaultOption})
```

オプションのリストを提示してユーザーに 1 つ選択させます。

**パラメータ:**
- `question`: 選択プロンプト
- `options`: 利用可能なオプションのリスト
- `defaultOption`: オプションのデフォルト選択

**戻り値:** 選択されたオプションを文字列として返します

**例:**
```dart
final environment = select(
  'Select deployment environment:',
  ['development', 'staging', 'production'],
  defaultOption: 'development'
);

info('Deploying to $environment environment...');
```

<div id="custom-command-helper-multi-select"></div>

### 複数選択

```dart
List<String> multiSelect(String question, List<String> options)
```

ユーザーにリストから複数のオプションを選択させます。

**パラメータ:**
- `question`: 選択プロンプト
- `options`: 利用可能なオプションのリスト

**戻り値:** 選択されたオプションのリスト

**例:**
```dart
final packages = multiSelect(
  'Select packages to install:',
  ['firebase_auth', 'dio', 'provider', 'shared_preferences', 'path_provider']
);

if (packages.isNotEmpty) {
  info('Installing ${packages.length} packages...');
  addPackages(packages);
  await runProcess('flutter pub get');
}
```

<div id="custom-command-helper-api"></div>

## API ヘルパーメソッド

`api` ヘルパーメソッドは、カスタムコマンドからのネットワークリクエストを簡素化します。

```dart
Future<T?> api<T>(Future<T?> Function(ApiService) request) async
```

## 基本的な使用例

### GET リクエスト

```dart
// データを取得
final userData = await api((request) =>
  request.get('https://api.example.com/users/1')
);
```

### POST リクエスト

```dart
// リソースを作成
final result = await api((request) =>
  request.post(
    'https://api.example.com/items',
    data: {'name': 'New Item', 'price': 19.99}
  )
);
```

### PUT リクエスト

```dart
// リソースを更新
final updateResult = await api((request) =>
  request.put(
    'https://api.example.com/items/42',
    data: {'name': 'Updated Item', 'price': 29.99}
  )
);
```

### DELETE リクエスト

```dart
// リソースを削除
final deleteResult = await api((request) => request.delete('https://api.example.com/items/42'));
```

### PATCH リクエスト

```dart
// リソースを部分的に更新
final patchResult = await api((request) => request.patch(
    'https://api.example.com/items/42',
    data: {'price': 24.99}
  )
);
```

### クエリパラメータ付き

```dart
// クエリパラメータを追加
final searchResults = await api((request) => request.get(
    'https://api.example.com/search',
    queryParameters: {'q': 'keyword', 'limit': 10}
  )
);
```

### スピナー付き

```dart
// より良い UI のためにスピナーと併用
final data = await withSpinner(
  task: () async {
    final data = await api((request) => request.get('https://api.example.com/config'));
    // データを処理
  },
  message: 'Loading configuration',
);
```


<div id="using-spinners"></div>

## スピナー機能

スピナーは、カスタムコマンド内の長時間実行される操作中に視覚的なフィードバックを提供します。コマンドが非同期タスクを実行している間、メッセージとともにアニメーションインジケーターを表示し、進捗状況を示してユーザー体験を向上させます。

- [withSpinner の使用](#using-with-spinner)
- [手動スピナー制御](#manual-spinner-control)
- [使用例](#spinner-examples)

<div id="using-with-spinner"></div>

## withSpinner の使用

`withSpinner` メソッドを使用すると、非同期タスクをスピナーアニメーションでラップでき、タスクの開始時に自動的に開始し、完了または失敗時に停止します:

```dart
Future<T> withSpinner<T>({
  required Future<T> Function() task,
  required String message,
  String? successMessage,
  String? errorMessage,
}) async
```

**パラメータ:**
- `task`: 実行する非同期関数
- `message`: スピナーの実行中に表示するテキスト
- `successMessage`: 正常完了時に表示するオプションのメッセージ
- `errorMessage`: タスク失敗時に表示するオプションのメッセージ

**戻り値:** タスク関数の結果

**例:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // スピナー付きでタスクを実行
  final projectFiles = await withSpinner(
    task: () async {
      // 長時間実行されるタスク（例: プロジェクトファイルの解析）
      await sleep(2);
      return ['pubspec.yaml', 'lib/main.dart', 'README.md'];
    },
    message: 'Analyzing project structure',
    successMessage: 'Project analysis complete',
    errorMessage: 'Failed to analyze project',
  );

  // 結果を使用して続行
  info('Found ${projectFiles.length} key files');
}
```

<div id="manual-spinner-control"></div>

## 手動スピナー制御

スピナーの状態を手動で制御する必要があるより複雑なシナリオでは、スピナーインスタンスを作成できます:

```dart
ConsoleSpinner createSpinner(String message)
```

**パラメータ:**
- `message`: スピナーの実行中に表示するテキスト

**戻り値:** 手動で制御できる `ConsoleSpinner` インスタンス

**手動制御の例:**
```dart
@override
Future<void> handle(CommandResult result) async {
  // スピナーインスタンスを作成
  final spinner = createSpinner('Deploying to production');
  spinner.start();

  try {
    // 最初のタスク
    await runProcess('flutter clean', silent: true);
    spinner.update('Building release version');

    // 2 番目のタスク
    await runProcess('flutter build web --release', silent: true);
    spinner.update('Uploading to server');

    // 3 番目のタスク
    await runProcess('./deploy.sh', silent: true);

    // 正常に完了
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);
  } catch (e) {
    // 失敗を処理
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
    rethrow;
  }
}
```

<div id="spinner-examples"></div>

## 使用例

### スピナー付きのシンプルなタスク

```dart
@override
Future<void> handle(CommandResult result) async {
  await withSpinner(
    task: () async {
      // 依存関係のインストール
      await runProcess('flutter pub get', silent: true);
      return true;
    },
    message: 'Installing dependencies',
    successMessage: 'Dependencies installed successfully',
  );
}
```

### 複数の連続操作

```dart
@override
Future<void> handle(CommandResult result) async {
  // 最初の操作（スピナー付き）
  await withSpinner(
    task: () => runProcess('flutter clean', silent: true),
    message: 'Cleaning project',
  );

  // 2 番目の操作（スピナー付き）
  await withSpinner(
    task: () => runProcess('flutter pub get', silent: true),
    message: 'Updating dependencies',
  );

  // 3 番目の操作（スピナー付き）
  final buildSuccess = await withSpinner(
    task: () async {
      await runProcess('flutter build apk --release', silent: true);
      return true;
    },
    message: 'Building release APK',
    successMessage: 'Release APK built successfully',
  );

  if (buildSuccess) {
    success('Build process completed');
  }
}
```

### 手動制御による複雑なワークフロー

```dart
@override
Future<void> handle(CommandResult result) async {
  final spinner = createSpinner('Starting deployment process');
  spinner.start();

  try {
    // ステータス更新付きで複数のステップを実行
    spinner.update('Step 1: Cleaning project');
    await runProcess('flutter clean', silent: true);

    spinner.update('Step 2: Fetching dependencies');
    await runProcess('flutter pub get', silent: true);

    spinner.update('Step 3: Building release');
    await runProcess('flutter build web --release', silent: true);

    // プロセスを完了
    spinner.stop(completionMessage: 'Deployment completed successfully', success: true);

  } catch (e) {
    spinner.stop(completionMessage: 'Deployment failed: $e', success: false);
  }
}
```

カスタムコマンドでスピナーを使用すると、長時間実行される操作中にユーザーに明確な視覚的フィードバックを提供し、より洗練されたプロフェッショナルなコマンドライン体験を実現できます。

<div id="custom-command-helper-get-string"></div>

### オプションから文字列値を取得

```dart
String getString(String name, {String defaultValue = ''})
```

**パラメータ:**

- `name`: 取得するオプションの名前
- `defaultValue`: オプションが指定されていない場合のオプションのデフォルト値

**戻り値:** オプションの値を文字列として返します

**例:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("name", defaultValue: "Anthony");
  return command;
}

Future<void> handle(CommandResult result) async {
  final name = result.getString('name');
  info('Hello, $name!');
}
```

<div id="custom-command-helper-get-bool"></div>

### オプションからブーリアン値を取得

```dart
bool getBool(String name, {bool defaultValue = false})
```

**パラメータ:**
- `name`: 取得するオプションの名前
- `defaultValue`: オプションが指定されていない場合のオプションのデフォルト値

**戻り値:** オプションの値をブーリアンとして返します


**例:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addFlag("verbose", defaultValue: false);
  return command;
}

Future<void> handle(CommandResult result) async {
  final verbose = result.getBool('verbose');
  if (verbose) {
    info('Verbose mode enabled');
  } else {
    info('Verbose mode disabled');
  }
}
```

<div id="custom-command-helper-get-int"></div>

### オプションから整数値を取得

```dart
int getInt(String name, {int defaultValue = 0})
```

**パラメータ:**
- `name`: 取得するオプションの名前
- `defaultValue`: オプションが指定されていない場合のオプションのデフォルト値

**戻り値:** オプションの値を整数として返します

**例:**
```dart
@override
CommandBuilder builder(CommandBuilder command) {
  command.addOption("count", defaultValue: 5);
  return command;
}

Future<void> handle(CommandResult result) async {
  final count = result.getInt('count');
  info('Count is set to $count');
}
```

<div id="custom-command-helper-sleep"></div>

### 指定した時間だけスリープ

```dart
void sleep(int seconds)
```

**パラメータ:**
- `seconds`: スリープする秒数

**戻り値:** なし

**例:**
```dart
@override
Future<void> handle(CommandResult result) async {
  info('Sleeping for 5 seconds...');
  await sleep(5);
  info('Awake now!');
}
```

<div id="output-formatting"></div>

## 出力フォーマット

基本的な `info`、`error`、`success`、`warning` メソッドに加えて、`NyCustomCommand` は追加の出力ヘルパーを提供しています:

```dart
@override
Future<void> handle(CommandResult result) async {
  // プレーンテキストを出力（色なし）
  line('Processing your request...');

  // 空行を出力
  newLine();       // 1 行の空行
  newLine(3);      // 3 行の空行

  // ミュートされたコメントを出力（グレーテキスト）
  comment('This is a background note');

  // 目立つアラートボックスを出力
  alert('Important: Please read carefully');

  // ask は prompt のエイリアス
  final name = ask('What is your name?');

  // 機密データ用の非表示入力（例: パスワード、API キー）
  final apiKey = promptSecret('Enter your API key:');

  // エラーメッセージと終了コードでコマンドを中止
  if (name.isEmpty) {
    abort('Name is required');  // 終了コード 1 で終了
  }
}
```

| メソッド | 説明 |
|--------|-------------|
| `line(String message)` | 色なしのプレーンテキストを出力 |
| `newLine([int count = 1])` | 空行を出力 |
| `comment(String message)` | ミュートされた/グレーのテキストを出力 |
| `alert(String message)` | 目立つアラートボックスを出力 |
| `ask(String question, {String defaultValue})` | `prompt` のエイリアス |
| `promptSecret(String question)` | 機密データ用の非表示入力 |
| `abort([String? message, int exitCode = 1])` | エラーでコマンドを終了 |

<div id="file-system-helpers"></div>

## ファイルシステムヘルパー

`NyCustomCommand` には、一般的な操作のために `dart:io` を手動でインポートする必要がないよう、組み込みのファイルシステムヘルパーが含まれています。

### ファイルの読み書き

```dart
@override
Future<void> handle(CommandResult result) async {
  // ファイルが存在するか確認
  if (fileExists('lib/config/app.dart')) {
    info('Config file found');
  }

  // ディレクトリが存在するか確認
  if (directoryExists('lib/app/models')) {
    info('Models directory found');
  }

  // ファイルを読み込み（非同期）
  String content = await readFile('pubspec.yaml');

  // ファイルを読み込み（同期）
  String contentSync = readFileSync('pubspec.yaml');

  // ファイルに書き込み（非同期）
  await writeFile('lib/generated/output.dart', 'class Output {}');

  // ファイルに書き込み（同期）
  writeFileSync('lib/generated/output.dart', 'class Output {}');

  // ファイルにコンテンツを追加
  await appendFile('log.txt', 'New log entry\n');

  // ディレクトリが存在することを確認（存在しない場合は作成）
  await ensureDirectory('lib/generated');

  // ファイルを削除
  await deleteFile('lib/generated/output.dart');

  // ファイルをコピー
  await copyFile('lib/config/app.dart', 'lib/config/app.bak.dart');
}
```

| メソッド | 説明 |
|--------|-------------|
| `fileExists(String path)` | ファイルが存在する場合 `true` を返す |
| `directoryExists(String path)` | ディレクトリが存在する場合 `true` を返す |
| `readFile(String path)` | ファイルを文字列として読み込み（非同期） |
| `readFileSync(String path)` | ファイルを文字列として読み込み（同期） |
| `writeFile(String path, String content)` | ファイルにコンテンツを書き込み（非同期） |
| `writeFileSync(String path, String content)` | ファイルにコンテンツを書き込み（同期） |
| `appendFile(String path, String content)` | ファイルにコンテンツを追加 |
| `ensureDirectory(String path)` | ディレクトリが存在しない場合は作成 |
| `deleteFile(String path)` | ファイルを削除 |
| `copyFile(String source, String destination)` | ファイルをコピー |

<div id="json-yaml-helpers"></div>

## JSON と YAML ヘルパー

組み込みのヘルパーで JSON と YAML ファイルを読み書きします。

```dart
@override
Future<void> handle(CommandResult result) async {
  // JSON ファイルを Map として読み込み
  Map<String, dynamic> config = await readJson('config.json');

  // JSON ファイルを List として読み込み
  List<dynamic> items = await readJsonArray('lib/app/commands/commands.json');

  // データを JSON ファイルに書き込み（デフォルトでプリティプリント）
  await writeJson('output.json', {'name': 'MyApp', 'version': '1.0.0'});

  // コンパクトな JSON で書き込み
  await writeJson('output.json', data, pretty: false);

  // JSON 配列ファイルにアイテムを追加
  // ファイルに [{"name": "a"}] が含まれている場合、その配列に追加
  await appendToJsonArray(
    'lib/app/commands/commands.json',
    {'name': 'my_command', 'category': 'app', 'script': 'my_command.dart'},
    uniqueKey: 'name',  // このキーで重複を防止
  );

  // YAML ファイルを Map として読み込み
  Map<String, dynamic> pubspec = await readYaml('pubspec.yaml');
  info('Project: ${pubspec['name']}');
}
```

| メソッド | 説明 |
|--------|-------------|
| `readJson(String path)` | JSON ファイルを `Map<String, dynamic>` として読み込み |
| `readJsonArray(String path)` | JSON ファイルを `List<dynamic>` として読み込み |
| `writeJson(String path, dynamic data, {bool pretty = true})` | データを JSON として書き込み |
| `appendToJsonArray(String path, Map item, {String? uniqueKey})` | JSON 配列ファイルに追加 |
| `readYaml(String path)` | YAML ファイルを `Map<String, dynamic>` として読み込み |

<div id="case-conversion-helpers"></div>

## ケース変換ヘルパー

`recase` パッケージをインポートせずに命名規則間で文字列を変換します。

```dart
@override
Future<void> handle(CommandResult result) async {
  String input = 'user profile page';

  info(snakeCase(input));    // user_profile_page
  info(camelCase(input));    // userProfilePage
  info(pascalCase(input));   // UserProfilePage
  info(titleCase(input));    // User Profile Page
  info(kebabCase(input));    // user-profile-page
  info(constantCase(input)); // USER_PROFILE_PAGE
}
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

標準的な {{ config('app.name') }} プロジェクトディレクトリのゲッター。プロジェクトルートからの相対パスを返します。

```dart
@override
Future<void> handle(CommandResult result) async {
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

  // プロジェクトルートからのカスタムパスを構築
  String customPath = projectPath('lib/app/services/auth_service.dart');
}
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

プラットフォームの確認と環境変数へのアクセス。

```dart
@override
Future<void> handle(CommandResult result) async {
  // プラットフォームの確認
  if (isWindows) {
    info('Running on Windows');
  } else if (isMacOS) {
    info('Running on macOS');
  } else if (isLinux) {
    info('Running on Linux');
  }

  // 現在の作業ディレクトリ
  info('Working in: $workingDirectory');

  // システム環境変数の読み込み
  String home = env('HOME', '/default/path');
}
```

| プロパティ / メソッド | 説明 |
|-------------------|-------------|
| `isWindows` | Windows で実行中の場合 `true` |
| `isMacOS` | macOS で実行中の場合 `true` |
| `isLinux` | Linux で実行中の場合 `true` |
| `workingDirectory` | 現在の作業ディレクトリパス |
| `env(String key, [String defaultValue = ''])` | システム環境変数を読み込み |

<div id="dart-flutter-commands"></div>

## Dart と Flutter コマンド

一般的な Dart と Flutter の CLI コマンドをヘルパーメソッドとして実行します。各メソッドはプロセスの終了コードを返します。

```dart
@override
Future<void> handle(CommandResult result) async {
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
  await flutterTest('test/unit/');  // 特定のディレクトリ
}
```

| メソッド | 説明 |
|--------|-------------|
| `dartFormat(String path)` | ファイルまたはディレクトリに対して `dart format` を実行 |
| `dartAnalyze([String? path])` | `dart analyze` を実行 |
| `flutterPubGet()` | `flutter pub get` を実行 |
| `flutterClean()` | `flutter clean` を実行 |
| `flutterBuild(String target, {List<String> args})` | `flutter build <target>` を実行 |
| `flutterTest([String? path])` | `flutter test` を実行 |

<div id="dart-file-manipulation"></div>

## Dart ファイル操作

Dart ファイルをプログラムで編集するためのヘルパー。スキャフォールディングツールを構築する際に便利です。

```dart
@override
Future<void> handle(CommandResult result) async {
  // Dart ファイルにインポート文を追加（重複を回避）
  await addImport(
    'lib/bootstrap/providers.dart',
    "import '/app/providers/firebase_provider.dart';",
  );

  // ファイルの最後の閉じ括弧の前にコードを挿入
  // 登録マップにエントリを追加する場合に便利
  await insertBeforeClosingBrace(
    'lib/bootstrap/providers.dart',
    '  FirebaseProvider(),',
  );

  // ファイルに特定の文字列が含まれているか確認
  bool hasImport = await fileContains(
    'lib/bootstrap/providers.dart',
    'firebase_provider',
  );

  // ファイルが正規表現パターンに一致するか確認
  bool hasClass = await fileContainsPattern(
    'lib/app/models/user.dart',
    RegExp(r'class User'),
  );
}
```

| メソッド | 説明 |
|--------|-------------|
| `addImport(String filePath, String importStatement)` | Dart ファイルにインポートを追加（既に存在する場合はスキップ） |
| `insertBeforeClosingBrace(String filePath, String code)` | ファイルの最後の `}` の前にコードを挿入 |
| `fileContains(String filePath, String identifier)` | ファイルに文字列が含まれているか確認 |
| `fileContainsPattern(String filePath, Pattern pattern)` | ファイルがパターンに一致するか確認 |

<div id="directory-helpers"></div>

## ディレクトリヘルパー

ディレクトリの操作やファイルの検索に役立つヘルパー。

```dart
@override
Future<void> handle(CommandResult result) async {
  // ディレクトリの内容を一覧表示
  var entities = listDirectory('lib/app/models');
  for (var entity in entities) {
    info(entity.path);
  }

  // 再帰的に一覧表示
  var allEntities = listDirectory('lib/', recursive: true);

  // 条件に一致するファイルを検索
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

  // ディレクトリをコピー（再帰的）
  await copyDirectory('lib/templates', 'lib/generated');
}
```

| メソッド | 説明 |
|--------|-------------|
| `listDirectory(String path, {bool recursive = false})` | ディレクトリの内容を一覧表示 |
| `findFiles(String directory, {String? extension, Pattern? namePattern, bool recursive = true})` | 条件に一致するファイルを検索 |
| `deleteDirectory(String path)` | ディレクトリを再帰的に削除 |
| `copyDirectory(String source, String destination)` | ディレクトリを再帰的にコピー |

<div id="validation-helpers"></div>

## バリデーションヘルパー

コード生成用のユーザー入力のバリデーションとクリーニングのためのヘルパー。

```dart
@override
Future<void> handle(CommandResult result) async {
  // Dart 識別子を検証
  if (!isValidDartIdentifier('MyClass')) {
    error('Invalid Dart identifier');
  }

  // 空でない最初の引数を要求
  String name = requireArgument(result, message: 'Please provide a name');

  // クラス名をクリーン（PascalCase、サフィックスの削除）
  String className = cleanClassName('user_model', removeSuffixes: ['_model']);
  // 結果: 'User'

  // ファイル名をクリーン（拡張子付き snake_case）
  String fileName = cleanFileName('UserModel', extension: '.dart');
  // 結果: 'user_model.dart'
}
```

| メソッド | 説明 |
|--------|-------------|
| `isValidDartIdentifier(String name)` | Dart 識別子名を検証 |
| `requireArgument(CommandResult result, {String? message})` | 空でない最初の引数を要求、なければ中止 |
| `cleanClassName(String name, {List<String> removeSuffixes})` | クラス名をクリーンして PascalCase に変換 |
| `cleanFileName(String name, {String extension = '.dart'})` | ファイル名をクリーンして snake_case に変換 |

<div id="file-scaffolding"></div>

## ファイルスキャフォールディング

スキャフォールディングシステムを使用して、1 つまたは複数のファイルをコンテンツ付きで作成します。

### 単一ファイル

```dart
@override
Future<void> handle(CommandResult result) async {
  await scaffold(
    path: 'lib/app/services/auth_service.dart',
    content: '''
class AuthService {
  Future<bool> login(String email, String password) async {
    // TODO: login を実装
    return false;
  }
}
''',
    force: false,  // 既存の場合は上書きしない
    successMessage: 'AuthService created',
  );
}
```

### 複数ファイル

```dart
@override
Future<void> handle(CommandResult result) async {
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
}
```

`ScaffoldFile` クラスは以下を受け取ります:

| プロパティ | 型 | 説明 |
|----------|------|-------------|
| `path` | `String` | 作成するファイルパス |
| `content` | `String` | ファイルの内容 |
| `successMessage` | `String?` | 成功時に表示するメッセージ |

<div id="task-runner"></div>

## タスクランナー

自動的なステータス出力付きで一連の名前付きタスクを実行します。

### 基本的なタスクランナー

```dart
@override
Future<void> handle(CommandResult result) async {
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
      stopOnError: true,  // これが失敗した場合パイプラインを停止（デフォルト）
    ),
  ]);
}
```

### スピナー付きタスクランナー

```dart
@override
Future<void> handle(CommandResult result) async {
  await runTasksWithSpinner([
    CommandTask(
      name: 'Preparing release',
      action: () async {
        await flutterClean();
        await flutterPubGet();
      },
    ),
    CommandTask(
      name: 'Building APK',
      action: () => flutterBuild('apk', args: ['--release']),
    ),
  ]);
}
```

`CommandTask` クラスは以下を受け取ります:

| プロパティ | 型 | デフォルト | 説明 |
|----------|------|---------|-------------|
| `name` | `String` | 必須 | 出力に表示されるタスク名 |
| `action` | `Future<void> Function()` | 必須 | 実行する非同期関数 |
| `stopOnError` | `bool` | `true` | このタスクが失敗した場合に残りのタスクを停止するかどうか |

<div id="table-output"></div>

## テーブル出力

コンソールにフォーマットされた ASCII テーブルを表示します。

```dart
@override
Future<void> handle(CommandResult result) async {
  table(
    ['Name', 'Version', 'Status'],
    [
      ['nylo_framework', '7.0.0', 'installed'],
      ['nylo_support', '7.0.0', 'installed'],
      ['dio', '5.4.0', 'installed'],
    ],
  );
}
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

<div id="progress-bar"></div>

## プログレスバー

既知のアイテム数を持つ操作のプログレスバーを表示します。

### 手動プログレスバー

```dart
@override
Future<void> handle(CommandResult result) async {
  // 100 アイテム用のプログレスバーを作成
  final progress = progressBar(100, message: 'Processing files');
  progress.start();

  for (int i = 0; i < 100; i++) {
    await Future.delayed(Duration(milliseconds: 50));
    progress.tick();  // 1 ずつインクリメント
  }

  progress.complete('All files processed');
}
```

### プログレス付きアイテム処理

```dart
@override
Future<void> handle(CommandResult result) async {
  final files = findFiles('lib/', extension: '.dart');

  // 自動プログレス追跡付きでアイテムを処理
  final results = await withProgress<File, String>(
    items: files,
    process: (file, index) async {
      // 各ファイルを処理
      return file.path;
    },
    message: 'Analyzing Dart files',
    completionMessage: 'Analysis complete',
  );

  info('Processed ${results.length} files');
}
```

### 同期プログレス

```dart
@override
Future<void> handle(CommandResult result) async {
  final items = ['a', 'b', 'c', 'd', 'e'];

  final results = withProgressSync<String, String>(
    items: items,
    process: (item, index) {
      // 同期処理
      return item.toUpperCase();
    },
    message: 'Converting items',
  );

  info('Results: $results');
}
```

`ConsoleProgressBar` クラスは以下を提供します:

| メソッド | 説明 |
|--------|-------------|
| `start()` | プログレスバーを開始 |
| `tick([int amount = 1])` | プログレスをインクリメント |
| `update(int value)` | プログレスを特定の値に設定 |
| `updateMessage(String newMessage)` | 表示メッセージを変更 |
| `complete([String? completionMessage])` | オプションのメッセージ付きで完了 |
| `stop()` | 完了せずに停止 |
| `current` | 現在のプログレス値（ゲッター） |
| `percentage` | パーセンテージとしてのプログレス（ゲッター） |
