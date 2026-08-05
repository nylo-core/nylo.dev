# インストール

---

<a name="section-1"></a>
- [インストール](#install "インストール")
- [プロジェクトの実行](#running-the-project "プロジェクトの実行")
- [Metro CLI](#metro-cli "Metro CLI")

<div id="install"></div>

3つのコマンドで、空のフォルダーから、ルーティング、ネットワーク、テーマ、コード生成が設定済みの Flutter アプリを起動できます。

<x-doc-strip label="始める前に" items="Flutter SDK をインストール済み, Dart 3" linkText="すべての要件" linkHref="/ja/docs/{{ $version }}/requirements" />

## インストール

次のコマンドを順番に実行してください。どのコマンドも安全に再実行できます。

<x-doc-steps>
<x-doc-step number="1" title="Nylo CLI をグローバルにインストール">
{{ config('app.name') }} の CLI ツールをシステムにグローバルインストールします。

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="新しいプロジェクトを作成">
このコマンドは {{ config('app.name') }} テンプレートをクローンし、アプリ名でプロジェクトを設定し、すべての依存関係を自動的にインストールします。

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Metro エイリアスの設定">
プロジェクト用の `metro` コマンドを設定します。これにより、`dart run` の完全な構文を使わずに Metro CLI コマンドを使用できるようになります。

``` bash
cd my_app
nylo init
```
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="用意されるもの" items="ルーティングとナビゲーションの事前設定
API サービスのボイラープレート
テーマとローカライゼーションのセットアップ
コード生成用の Metro CLI" />


<div id="running-the-project"></div>

## プロジェクトの実行

{{ config('app.name') }} プロジェクトは標準的な Flutter アプリと同じように実行できます。

<x-doc-tabs tabs="ターミナル, Android Studio, VS Code">
<x-doc-tab label="ターミナル">

``` bash
flutter run
```

ビルドが成功すると、{{ config('app.name') }} のデフォルトランディング画面が表示されます。
</x-doc-tab>

<x-doc-tab label="Android Studio">
プロジェクトフォルダーを開き、ターゲットセレクターからデバイスを選択して、**Run** を押します。

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Flutter ドキュメント：実行とデバッグ ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
プロジェクトフォルダーを開き、コマンドパレットから **Debug: Start Without Debugging** を実行します。

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Flutter ドキュメント：ブレークポイントなしで実行 ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro がプロジェクトファイルを生成します。引数なしで実行するとメニューが表示され、コマンドを直接呼び出すこともできます。

``` plaintext
$ metro

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
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### コマンドリファレンス

各コマンドには名前を指定します。例：`metro make:page settings_page`

<x-doc-commands title="ウィジェットコマンド" rows="
metro make:page | 新しいページを作成
metro make:stateful_widget | Stateful Widget を作成
metro make:stateless_widget | Stateless Widget を作成
metro make:state_managed_widget | State 管理 Widget を作成
metro make:navigation_hub | Navigation Hub（ボトムナビ）を作成
metro make:journey_widget | Navigation Hub 用の Journey Widget を作成
metro make:bottom_sheet_modal | ボトムシートモーダルを作成
metro make:button | カスタムボタン Widget を作成
metro make:form | バリデーション付きフォームを作成
" />

<x-doc-commands title="ヘルパーコマンド" rows="
metro make:model | Model クラスを作成
metro make:provider | Provider を作成
metro make:api_service | API サービスを作成
metro make:controller | Controller を作成
metro make:event | イベントを作成
metro make:route_guard | Route Guard を作成
metro make:config | 設定ファイルを作成
metro make:interceptor | ネットワークインターセプターを作成
metro make:command | カスタム Metro コマンドを作成
metro make:env | .env から環境設定を生成
" />

### 使用例

``` bash
# 新しいページを作成
metro make:page settings_page

# Model を作成
metro make:model User

# API サービスを作成
metro make:api_service user_api_service
```
