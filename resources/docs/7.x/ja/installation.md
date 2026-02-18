# インストール

---

<a name="section-1"></a>
- [インストール](#install "インストール")
- [プロジェクトの実行](#running-the-project "プロジェクトの実行")
- [Metro CLI](#metro-cli "Metro CLI")


<div id="install"></div>

## インストール

### 1. nylo_installer をグローバルにインストール

``` bash
dart pub global activate nylo_installer
```

{{ config('app.name') }} の CLI ツールをシステムにグローバルインストールします。

### 2. 新しいプロジェクトを作成

``` bash
nylo new my_app
```

このコマンドは {{ config('app.name') }} テンプレートをクローンし、アプリ名でプロジェクトを設定し、すべての依存関係を自動的にインストールします。

### 3. Metro CLI エイリアスの設定

``` bash
cd my_app
nylo init
```

プロジェクト用の `metro` コマンドを設定します。これにより、`dart run` の完全な構文を使わずに Metro CLI コマンドを使用できるようになります。

インストール後、以下が含まれた完全な Flutter プロジェクト構造が用意されます:
- ルーティングとナビゲーションの事前設定
- API サービスのボイラープレート
- テーマとローカライゼーションのセットアップ
- コード生成用の Metro CLI


<div id="running-the-project"></div>

## プロジェクトの実行

{{ config('app.name') }} プロジェクトは標準的な Flutter アプリと同じように実行できます。

### ターミナルを使用する場合

``` bash
flutter run
```

### IDE を使用する場合

- **Android Studio**: <a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">実行とデバッグ</a>
- **VS Code**: <a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">ブレークポイントなしで実行</a>

ビルドが成功すると、{{ config('app.name') }} のデフォルトランディング画面が表示されます。


<div id="metro-cli"></div>

## Metro CLI

{{ config('app.name') }} には、プロジェクトファイルを生成するための **Metro** という CLI ツールが含まれています。

### Metro の実行

``` bash
metro
```

Metro メニューが表示されます:

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
```

### Metro コマンドリファレンス

| コマンド | 説明 |
|---------|-------------|
| `make:page` | 新しいページを作成 |
| `make:stateful_widget` | Stateful Widget を作成 |
| `make:stateless_widget` | Stateless Widget を作成 |
| `make:state_managed_widget` | State 管理 Widget を作成 |
| `make:navigation_hub` | Navigation Hub（ボトムナビ）を作成 |
| `make:journey_widget` | Navigation Hub 用の Journey Widget を作成 |
| `make:bottom_sheet_modal` | ボトムシートモーダルを作成 |
| `make:button` | カスタムボタン Widget を作成 |
| `make:form` | バリデーション付きフォームを作成 |
| `make:model` | Model クラスを作成 |
| `make:provider` | Provider を作成 |
| `make:api_service` | API サービスを作成 |
| `make:controller` | Controller を作成 |
| `make:event` | イベントを作成 |
| `make:theme` | テーマを作成 |
| `make:route_guard` | Route Guard を作成 |
| `make:config` | 設定ファイルを作成 |
| `make:interceptor` | ネットワークインターセプターを作成 |
| `make:command` | カスタム Metro コマンドを作成 |
| `make:env` | .env から環境設定を生成 |

### 使用例

``` bash
# 新しいページを作成
metro make:page settings_page

# Model を作成
metro make:model User

# API サービスを作成
metro make:api_service user_api_service
```
